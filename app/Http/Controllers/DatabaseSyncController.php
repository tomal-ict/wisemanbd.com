<?php

namespace App\Http\Controllers;

use App\Services\DatabaseSyncService;

class DatabaseSyncController extends Controller
{
    protected $service;

    public function __construct(DatabaseSyncService $service)
    {
        $this->service = $service;
    }

    protected function authorizeSync()
    {
        abort_unless(auth()->check() && auth()->user()->user_type === 'admin', 403);
    }

    public function index()
    {
        $this->authorizeSync();
        $schema = $this->service->syncFileExists() ? $this->service->parseSyncFile() : ['tables' => []];
        $inspection = $this->service->inspect();

        return view('backend.system.database_sync', [
            'service' => $this->service,
            'schema' => $schema,
            'inspection' => $inspection,
        ]);
    }

    public function export()
    {
        $this->authorizeSync();

        try {
            $result = $this->service->export();
            $rowsMsg = isset($result['rows']) ? ', ' . number_format($result['rows']) . ' ' . translate('rows') : '';
            $sizeMb = number_format($result['size'] / 1024 / 1024, 2);
            flash(translate('Database backup and sync file generated successfully!') . ' - ' . $result['tables'] . ' ' . translate('tables') . $rowsMsg . ' (' . $sizeMb . ' MB)')->success();
        } catch (\Throwable $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('database_sync.index');
    }

    public function run()
    {
        $this->authorizeSync();
        $report = $this->service->runSync();

        $messages = [];
        if (count($report['created_tables']) > 0) {
            $messages[] = count($report['created_tables']) . ' ' . translate('tables created');
        }
        if (count($report['added_columns']) > 0) {
            $messages[] = count($report['added_columns']) . ' ' . translate('columns added');
        }
        if (count($report['added_keys']) > 0) {
            $messages[] = count($report['added_keys']) . ' ' . translate('keys added');
        }
        if (!empty($report['merged_tables'])) {
            $messages[] = count($report['merged_tables']) . ' ' . translate('tables merged with data');
        }

        if (count($messages) > 0) {
            flash(translate('Safe sync & data merge completed successfully:') . ' ' . implode(', ', $messages))->success();
        } elseif (count($report['errors']) === 0) {
            flash(translate('Database schema and data are already synchronized.'))->success();
        }

        if (count($report['errors']) > 0) {
            flash(implode(' ', array_slice($report['errors'], 0, 5)))->error();
        }

        return redirect()->route('database_sync.index')->with('sync_report', $report);
    }
}
