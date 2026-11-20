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
            flash(translate('Schema file updated successfully') . ' - ' . $result['tables'] . ' ' . translate('tables') . ', ' . number_format($result['size']) . ' bytes')->success();
        } catch (\Throwable $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('database_sync.index');
    }

    public function run()
    {
        $this->authorizeSync();
        $report = $this->service->runSync();

        if (count($report['created_tables']) > 0 || count($report['added_columns']) > 0 || count($report['added_keys']) > 0) {
            flash(count($report['created_tables']) . ' ' . translate('tables created') . ', ' .
                count($report['added_columns']) . ' ' . translate('columns added') . ', ' .
                count($report['added_keys']) . ' ' . translate('keys added'))->success();
        } elseif (count($report['errors']) === 0) {
            flash(translate('Database is already synchronized.'))->success();
        }

        if (count($report['errors']) > 0) {
            flash(implode(' ', $report['errors']))->error();
        }

        return redirect()->route('database_sync.index')->with('sync_report', $report);
    }
}
