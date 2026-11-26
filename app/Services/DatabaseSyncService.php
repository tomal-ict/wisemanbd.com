<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class DatabaseSyncService
{
    protected $syncFile;

    public function __construct()
    {
        $this->syncFile = database_path('sync/database.sql');
    }

    public function syncFilePath()
    {
        return $this->syncFile;
    }

    public function syncFileExists()
    {
        return file_exists($this->syncFile);
    }

    public function syncFileSize()
    {
        return $this->syncFileExists() ? filesize($this->syncFile) : 0;
    }

    public function syncFileModifiedAt()
    {
        return $this->syncFileExists() ? date('Y-m-d H:i:s', filemtime($this->syncFile)) : null;
    }

    public function canExport()
    {
        return true;
    }

    public function currentDatabaseName()
    {
        return DB::connection()->getDatabaseName();
    }

    public function engineVersion()
    {
        try {
            $row = DB::selectOne('SELECT VERSION() AS version');
            return $row ? (string) $row->version : null;
        } catch (Throwable $e) {
            return null;
        }
    }

    protected function assertMySql()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            throw new RuntimeException('Database Sync supports MySQL and MariaDB connections only.');
        }
    }

    protected function quoteIdentifier($identifier)
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    public function liveTables()
    {
        $this->assertMySql();

        $tables = [];
        foreach (DB::select('SHOW TABLES') as $row) {
            $values = array_values((array) $row);
            if (isset($values[0])) {
                $tables[] = $values[0];
            }
        }

        return $tables;
    }

    public function liveTableCount()
    {
        return count($this->liveTables());
    }

    /**
     * Extract a portable CREATE TABLE statement for a table in the source schema.
     */
    protected function getCreateTableStatement($table)
    {
        $row = DB::selectOne('SHOW CREATE TABLE ' . $this->quoteIdentifier($table));
        if (!$row) {
            return null;
        }

        $values = array_values((array) $row);
        if (!isset($values[1])) {
            return null;
        }

        $create = preg_replace('/^CREATE TABLE/i', 'CREATE TABLE IF NOT EXISTS', $values[1], 1);

        // A source schema must not carry the local database's next auto-increment value.
        return preg_replace('/\sAUTO_INCREMENT=\d+\b/i', '', $create);
    }

    /**
     * Split a CREATE TABLE body into top-level column, key, and constraint definitions.
     */
    protected function splitCreateBody($createStatement)
    {
        $open = strpos($createStatement, '(');
        $close = strrpos($createStatement, ')');
        if ($open === false || $close === false || $close < $open) {
            return [];
        }

        $body = substr($createStatement, $open + 1, $close - $open - 1);
        $segments = [];
        $current = '';
        $depth = 0;
        $backtick = false;
        $single = false;
        $double = false;
        $length = strlen($body);

        for ($i = 0; $i < $length; $i++) {
            $character = $body[$i];

            if ($backtick) {
                $current .= $character;
                if ($character === '`' && ($i + 1 >= $length || $body[$i + 1] !== '`')) {
                    $backtick = false;
                } elseif ($character === '`' && $i + 1 < $length && $body[$i + 1] === '`') {
                    $current .= $body[$i + 1];
                    $i++;
                }
                continue;
            }

            if ($single) {
                $current .= $character;
                if ($character === "'") {
                    $single = false;
                }
                continue;
            }

            if ($double) {
                $current .= $character;
                if ($character === '"') {
                    $double = false;
                }
                continue;
            }

            switch ($character) {
                case '`':
                    $backtick = true;
                    $current .= $character;
                    break;
                case "'":
                    $single = true;
                    $current .= $character;
                    break;
                case '"':
                    $double = true;
                    $current .= $character;
                    break;
                case '(':
                    $depth++;
                    $current .= $character;
                    break;
                case ')':
                    $depth--;
                    $current .= $character;
                    break;
                case ',':
                    if ($depth === 0) {
                        $definition = trim($current);
                        if ($definition !== '') {
                            $segments[] = $definition;
                        }
                        $current = '';
                    } else {
                        $current .= $character;
                    }
                    break;
                default:
                    $current .= $character;
                    break;
            }
        }

        $definition = trim($current);
        if ($definition !== '') {
            $segments[] = $definition;
        }

        return $segments;
    }

    protected function parseCreateSegments($segments)
    {
        $columns = [];
        $keys = [];

        foreach ($segments as $segment) {
            if (preg_match('/^`/', $segment)) {
                $columns[] = $segment;
            } else {
                $keys[] = $segment;
            }
        }

        return compact('columns', 'keys');
    }

    protected function columnNameFromDefinition($definition)
    {
        if (preg_match('/^`((?:[^`]|``)+)`/', $definition, $matches)) {
            return str_replace('``', '`', $matches[1]);
        }

        return null;
    }

    /**
     * Export database schema and table data to the sync file.
     * Generates a portable database/sync/database.sql containing CREATE TABLE IF NOT EXISTS
     * and safe INSERT IGNORE statements for seamless merging on any server.
     */
    public function export()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->assertMySql();

        $tables = $this->liveTables();
        sort($tables, SORT_STRING);

        $directory = dirname($this->syncFile);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Could not create the schema directory: ' . $directory);
        }

        $fp = fopen($this->syncFile, 'w');
        if (!$fp) {
            throw new RuntimeException('Could not open the database sync file for writing: ' . $this->syncFile);
        }

        $pdo = DB::connection()->getPdo();
        $totalRows = 0;

        $header = [
            '-- =============================================',
            '-- Ecommerce Full Database Backup & Sync File',
            '-- Contains table schemas, columns, indexes, and full table data.',
            '-- Ready for synchronization and safe merging on any server.',
            '-- Exported at: ' . date('Y-m-d H:i:s'),
            '-- Engine: ' . ($this->engineVersion() ?: 'unknown'),
            '-- Tables: ' . count($tables),
            '-- =============================================',
            '',
        ];
        fwrite($fp, implode("\n", $header) . "\n");

        foreach ($tables as $table) {
            $create = $this->getCreateTableStatement($table);
            if ($create === null) {
                fclose($fp);
                throw new RuntimeException('Could not read the CREATE TABLE statement for ' . $table . '.');
            }

            $parsed = $this->parseCreateSegments($this->splitCreateBody($create));
            fwrite($fp, '-- @@TABLE:' . $table . "\n");
            fwrite($fp, $create . ";\n\n");

            foreach ($parsed['columns'] as $definition) {
                $name = $this->columnNameFromDefinition($definition);
                fwrite($fp, '-- @@COLUMN:' . $table . '|' . ($name !== null ? $name : '?') . '|' . $definition . "\n");
            }

            foreach ($parsed['keys'] as $definition) {
                fwrite($fp, '-- @@KEY:' . $table . '|' . $definition . "\n");
            }

            fwrite($fp, "\n");

            // Export table data safely using INSERT IGNORE
            $count = DB::table($table)->count();
            $totalRows += $count;

            if ($count > 0) {
                fwrite($fp, '-- @@DATA:' . $table . "\n");
                DB::table($table)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($fp, $pdo, $table) {
                    if ($rows->isEmpty()) {
                        return;
                    }
                    $first = (array) $rows[0];
                    $cols = array_map(function ($c) {
                        return '`' . str_replace('`', '``', $c) . '`';
                    }, array_keys($first));
                    $colHeader = implode(', ', $cols);

                    $valStrings = [];
                    foreach ($rows as $row) {
                        $vals = [];
                        foreach ((array) $row as $val) {
                            if ($val === null) {
                                $vals[] = 'NULL';
                            } elseif (is_numeric($val) && !preg_match('/^0\d+/', (string) $val)) {
                                $vals[] = $val;
                            } else {
                                $vals[] = $pdo->quote($val);
                            }
                        }
                        $valStrings[] = '(' . implode(', ', $vals) . ')';
                    }
                    fwrite($fp, "INSERT IGNORE INTO `" . str_replace('`', '``', $table) . "` ($colHeader) VALUES\n" . implode(",\n", $valStrings) . ";\n");
                });
                fwrite($fp, "\n");
            }
        }

        fclose($fp);

        Cache::forget('db_sync_schema');

        return [
            'tables' => count($tables),
            'rows' => $totalRows,
            'size' => filesize($this->syncFile),
            'path' => $this->syncFile,
        ];
    }

    /**
     * Parse the committed schema file into tables, columns, keys, and CREATE statements.
     */
    public function parseSyncFile()
    {
        if (!$this->syncFileExists()) {
            return ['tables' => [], 'createStatements' => []];
        }

        $handle = fopen($this->syncFile, 'r');
        if (!$handle) {
            return ['tables' => [], 'createStatements' => []];
        }

        $tables = [];
        $createStatements = [];
        $currentTable = null;
        $columns = [];
        $keys = [];
        $createBuffer = null;

        while (($line = fgets($handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if (preg_match('/^-- @@TABLE:(.+)$/', $line, $matches)) {
                if ($currentTable !== null) {
                    $tables[$currentTable] = ['columns' => $columns, 'keys' => $keys];
                }

                $currentTable = trim($matches[1]);
                $columns = [];
                $keys = [];
                $createBuffer = null;
                continue;
            }

            if (preg_match('/^-- @@COLUMN:(.+)$/', $line, $matches)) {
                $parts = explode('|', $matches[1], 3);
                if (count($parts) === 3 && $currentTable !== null && $parts[0] === $currentTable) {
                    $columns[] = ['name' => $parts[1], 'definition' => $parts[2]];
                }
                continue;
            }

            if (preg_match('/^-- @@KEY:(.+)$/', $line, $matches)) {
                $parts = explode('|', $matches[1], 2);
                if (count($parts) === 2 && $currentTable !== null && $parts[0] === $currentTable) {
                    $keys[] = trim($parts[1]);
                }
                continue;
            }

            if ($currentTable === null || $line === '' || strpos($line, '--') === 0) {
                continue;
            }

            if ($createBuffer === null) {
                if (preg_match('/^CREATE TABLE IF NOT EXISTS/i', $line)) {
                    $createBuffer = $line;
                    if (substr(rtrim($line), -1) === ';') {
                        $createStatements[$currentTable] = $createBuffer;
                        $createBuffer = null;
                    }
                }
                continue;
            }

            $createBuffer .= "\n" . $line;
            if (substr(rtrim($line), -1) === ';') {
                $createStatements[$currentTable] = $createBuffer;
                $createBuffer = null;
            }
        }

        fclose($handle);

        if ($currentTable !== null) {
            $tables[$currentTable] = ['columns' => $columns, 'keys' => $keys];
        }

        return ['tables' => $tables, 'createStatements' => $createStatements];
    }

    protected function liveMetadata()
    {
        $columns = [];
        $indexes = [];
        $constraints = [];

        foreach (DB::select('SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE()') as $row) {
            if (!isset($columns[$row->TABLE_NAME])) {
                $columns[$row->TABLE_NAME] = [];
            }
            $columns[$row->TABLE_NAME][$row->COLUMN_NAME] = true;
        }

        foreach (DB::select('SELECT TABLE_NAME, INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() GROUP BY TABLE_NAME, INDEX_NAME') as $row) {
            if (!isset($indexes[$row->TABLE_NAME])) {
                $indexes[$row->TABLE_NAME] = [];
            }
            $indexes[$row->TABLE_NAME][$row->INDEX_NAME] = true;
        }

        foreach (DB::select('SELECT TABLE_NAME, CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE()') as $row) {
            if (!isset($constraints[$row->TABLE_NAME])) {
                $constraints[$row->TABLE_NAME] = [];
            }
            $constraints[$row->TABLE_NAME][$row->CONSTRAINT_NAME] = true;
        }

        return compact('columns', 'indexes', 'constraints');
    }

    protected function requiresManualColumnReview($definition)
    {
        $definition = strtoupper($definition);

        return strpos($definition, 'NOT NULL') !== false && strpos($definition, 'DEFAULT') === false;
    }

    protected function keyDetails($definition)
    {
        if (preg_match('/^PRIMARY KEY/i', $definition)) {
            return [
                'name' => 'PRIMARY',
                'type' => 'primary',
                'safe_to_apply' => false,
                'reason' => 'Primary keys on existing tables require manual review.',
            ];
        }

        if (preg_match('/^CONSTRAINT\s+`((?:[^`]|``)+)`/i', $definition, $matches)) {
            return [
                'name' => str_replace('``', '`', $matches[1]),
                'type' => 'constraint',
                'safe_to_apply' => false,
                'reason' => 'Foreign key constraints on existing tables require manual review.',
            ];
        }

        if (preg_match('/^(?:UNIQUE\s+)?(?:FULLTEXT\s+|SPATIAL\s+)?(?:KEY|INDEX)\s+`((?:[^`]|``)+)`/i', $definition, $matches)) {
            return [
                'name' => str_replace('``', '`', $matches[1]),
                'type' => 'index',
                'safe_to_apply' => true,
                'reason' => null,
            ];
        }

        return [
            'name' => 'unknown',
            'type' => 'unknown',
            'safe_to_apply' => false,
            'reason' => 'This key definition is not supported for automatic sync.',
        ];
    }

    protected function emptyInspection()
    {
        return [
            'source_table_count' => 0,
            'target_table_count' => 0,
            'missing_tables' => [],
            'missing_columns' => [],
            'missing_indexes' => [],
            'manual_changes' => [],
            'errors' => [],
        ];
    }

    /**
     * Inspect the target database without changing it.
     */
    public function inspect()
    {
        $inspection = $this->emptyInspection();

        if (!$this->syncFileExists()) {
            $inspection['errors'][] = 'Source schema file is missing: ' . $this->syncFile . '.';
            return $inspection;
        }

        try {
            $this->assertMySql();
            $schema = $this->parseSyncFile();
            $inspection['source_table_count'] = count($schema['tables']);

            if ($inspection['source_table_count'] === 0) {
                $inspection['errors'][] = 'The source schema file contains no tables.';
                return $inspection;
            }

            $liveTables = array_fill_keys($this->liveTables(), true);
            $inspection['target_table_count'] = count($liveTables);
            $metadata = $this->liveMetadata();

            foreach ($schema['tables'] as $table => $definition) {
                if (!isset($liveTables[$table])) {
                    $create = isset($schema['createStatements'][$table]) ? $schema['createStatements'][$table] : null;
                    if ($create === null) {
                        $inspection['errors'][] = 'No CREATE TABLE statement is available for ' . $table . '.';
                    } else {
                        $inspection['missing_tables'][] = ['table' => $table, 'create' => $create];
                    }
                    continue;
                }

                $liveColumns = isset($metadata['columns'][$table]) ? $metadata['columns'][$table] : [];
                foreach ($definition['columns'] as $column) {
                    if (isset($liveColumns[$column['name']])) {
                        continue;
                    }

                    $change = [
                        'table' => $table,
                        'column' => $column['name'],
                        'definition' => $column['definition'],
                    ];

                    if ($this->requiresManualColumnReview($column['definition'])) {
                        $change['type'] = 'column';
                        $change['label'] = $table . '.' . $column['name'];
                        $change['reason'] = 'A required column without a default value needs manual review.';
                        $inspection['manual_changes'][] = $change;
                    } else {
                        $inspection['missing_columns'][] = $change;
                    }
                }

                $liveIndexes = isset($metadata['indexes'][$table]) ? $metadata['indexes'][$table] : [];
                foreach ($definition['keys'] as $keyDefinition) {
                    $key = $this->keyDetails($keyDefinition);
                    if (isset($liveIndexes[$key['name']])) {
                        continue;
                    }

                    $liveConstraints = isset($metadata['constraints'][$table]) ? $metadata['constraints'][$table] : [];
                    if ($key['type'] === 'constraint' && isset($liveConstraints[$key['name']])) {
                        continue;
                    }

                    if (!$key['safe_to_apply']) {
                        $inspection['manual_changes'][] = [
                            'type' => 'key',
                            'label' => $table . '.' . $key['name'],
                            'reason' => $key['reason'],
                        ];
                        continue;
                    }

                    $inspection['missing_indexes'][] = [
                        'table' => $table,
                        'name' => $key['name'],
                        'definition' => $keyDefinition,
                    ];
                }
            }
        } catch (Throwable $e) {
            $inspection['errors'][] = $e->getMessage();
        }

        return $inspection;
    }

    protected function lockName()
    {
        return 'ecommerce_schema_' . md5((string) $this->currentDatabaseName());
    }

    protected function acquireLock()
    {
        $row = DB::selectOne('SELECT GET_LOCK(?, 0) AS locked', [$this->lockName()]);
        $values = $row ? array_values((array) $row) : [];

        return isset($values[0]) && (int) $values[0] === 1;
    }

    protected function releaseLock()
    {
        DB::selectOne('SELECT RELEASE_LOCK(?) AS released', [$this->lockName()]);
    }

    protected function foreignKeyChecks()
    {
        $row = DB::selectOne('SELECT @@FOREIGN_KEY_CHECKS AS enabled');
        $values = $row ? array_values((array) $row) : [];

        return isset($values[0]) ? (int) $values[0] : 1;
    }

    /**
     * Apply absent schema objects (tables, columns, indexes) and merge data
     * safely using INSERT IGNORE. Existing rows are never modified or removed.
     */
    public function runSync()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $this->assertMySql();

        $plan = $this->inspect();
        $report = [
            'created_tables' => [],
            'added_columns' => [],
            'added_keys' => [],
            'merged_tables' => [],
            'skipped_changes' => $plan['manual_changes'] ?? [],
            'errors' => $plan['errors'] ?? [],
            'before' => [
                'missing_tables' => count($plan['missing_tables'] ?? []),
                'missing_columns' => count($plan['missing_columns'] ?? []),
                'missing_indexes' => count($plan['missing_indexes'] ?? []),
            ],
            'remaining' => null,
        ];

        if (count($report['errors']) > 0 && empty($plan['missing_tables']) && empty($plan['missing_columns'])) {
            return $report;
        }

        $locked = $this->acquireLock();
        if (!$locked) {
            $this->releaseLock();
            $locked = $this->acquireLock();
        }

        $foreignKeyChecks = null;

        try {
            $foreignKeyChecks = $this->foreignKeyChecks();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // 1. Create missing tables
            if (!empty($plan['missing_tables'])) {
                foreach ($plan['missing_tables'] as $table) {
                    try {
                        DB::statement($table['create']);
                        $report['created_tables'][] = $table['table'];
                    } catch (Throwable $e) {
                        $report['errors'][] = 'CREATE TABLE ' . $table['table'] . ' failed: ' . $e->getMessage();
                    }
                }
            }

            // 2. Add missing columns
            if (!empty($plan['missing_columns'])) {
                foreach ($plan['missing_columns'] as $column) {
                    try {
                        DB::statement('ALTER TABLE ' . $this->quoteIdentifier($column['table']) . ' ADD COLUMN ' . $column['definition']);
                        $report['added_columns'][] = $column['table'] . '.' . $column['column'];
                    } catch (Throwable $e) {
                        $report['errors'][] = 'ADD COLUMN ' . $column['table'] . '.' . $column['column'] . ' failed: ' . $e->getMessage();
                    }
                }
            }

            // Also attempt to add any column from manual_changes if safely possible
            if (!empty($plan['manual_changes'])) {
                foreach ($plan['manual_changes'] as $change) {
                    if (($change['type'] ?? '') === 'column' && isset($change['table']) && isset($change['definition'])) {
                        try {
                            DB::statement('ALTER TABLE ' . $this->quoteIdentifier($change['table']) . ' ADD COLUMN ' . $change['definition']);
                            $report['added_columns'][] = $change['table'] . '.' . $change['column'];
                        } catch (Throwable $e) {
                            // If failed, remains in manual/skipped list
                        }
                    }
                }
            }

            // 3. Add missing indexes
            if (!empty($plan['missing_indexes'])) {
                foreach ($plan['missing_indexes'] as $index) {
                    try {
                        DB::statement('ALTER TABLE ' . $this->quoteIdentifier($index['table']) . ' ADD ' . $index['definition']);
                        $report['added_keys'][] = $index['table'] . '.' . $index['name'];
                    } catch (Throwable $e) {
                        $report['errors'][] = 'ADD INDEX ' . $index['table'] . '.' . $index['name'] . ' failed: ' . $e->getMessage();
                    }
                }
            }

            // 4. Safe Data Merge using INSERT IGNORE
            if ($this->syncFileExists()) {
                $handle = fopen($this->syncFile, 'r');
                if ($handle) {
                    $buffer = '';
                    $mergedTables = [];
                    while (($line = fgets($handle)) !== false) {
                        $trimmed = trim($line);
                        if (strpos($trimmed, '-- @@DATA:') === 0) {
                            $currentTable = trim(substr($trimmed, 10));
                            $mergedTables[$currentTable] = true;
                            continue;
                        }

                        if (strpos($trimmed, 'INSERT IGNORE INTO') === 0 || $buffer !== '') {
                            $buffer .= $line;
                            if (substr(rtrim($trimmed), -1) === ';') {
                                try {
                                    DB::unprepared($buffer);
                                } catch (Throwable $e) {
                                    $report['errors'][] = 'Data merge notice: ' . substr($e->getMessage(), 0, 160);
                                }
                                $buffer = '';
                            }
                        }
                    }
                    fclose($handle);
                    $report['merged_tables'] = array_keys($mergedTables);
                }
            }
        } finally {
            if ($foreignKeyChecks !== null) {
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS = ' . (int) $foreignKeyChecks);
                } catch (Throwable $e) {
                    $report['errors'][] = 'Could not restore FOREIGN_KEY_CHECKS: ' . $e->getMessage();
                }
            }

            if ($locked) {
                try {
                    $this->releaseLock();
                } catch (Throwable $e) {
                    // ignore
                }
            }
        }

        Cache::forget('db_sync_schema');
        $report['remaining'] = $this->inspect();

        return $report;
    }
}
