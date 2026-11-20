<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseSyncService;

class DatabaseSyncExport extends Command
{
    protected $signature = 'db:sync:export';

    protected $description = 'Export the current live local database schema to the sync file (database/sync/database.sql).';

    protected $service;

    public function __construct(DatabaseSyncService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $result = $this->service->export();
        $this->info("Schema exported: {$result['tables']} tables, " . number_format($result['size']) . " bytes.");
        $this->info("File: " . $result['path']);
        return 0;
    }
}
