@extends('backend.layouts.app')

@section('content')
@php
    $missingTables = $inspection['missing_tables'] ?? [];
    $missingColumns = $inspection['missing_columns'] ?? [];
    $missingIndexes = $inspection['missing_indexes'] ?? [];
    $manualChanges = $inspection['manual_changes'] ?? [];
    $inspectionErrors = $inspection['errors'] ?? [];
    $safeChanges = count($missingTables) + count($missingColumns) + count($missingIndexes);
    $connection = config('database.default');
    $databaseName = config('database.connections.' . $connection . '.database');
@endphp

<div class="row">
    <div class="col-lg-11 col-xxl-9 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="h6 mb-0">
                    <i class="las la-database mr-1"></i>
                    {{ translate('Database Sync') }}
                </h3>
                <div class="card-header-actions">
                    <a href="{{ route('database_sync.index') }}" class="btn btn-soft-secondary btn-sm">
                        <i class="las la-redo-alt"></i> {{ translate('Check Again') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <strong>{{ translate('Safe schema repair') }}</strong>
                    <div class="small mt-1">
                        {{ translate('This tool only creates tables, columns, and indexes that are absent from the deployed database. It never drops tables, deletes data, imports data, or changes existing column definitions.') }}
                    </div>
                </div>

                <div class="row gutters-10 mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded bg-soft-secondary p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fs-15 fw-700">{{ translate('Source Schema') }}</div>
                                @if($service->syncFileExists())
                                    <span class="badge badge-inline badge-success">{{ translate('Ready') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('Missing') }}</span>
                                @endif
                            </div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Schema file') }}</td>
                                        <td class="py-1 fw-600"><code>database/sync/database.sql</code></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Tables in source') }}</td>
                                        <td class="py-1 fw-600">{{ $inspection['source_table_count'] ?? count($schema['tables'] ?? []) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Last updated') }}</td>
                                        <td class="py-1 fw-600">{{ $service->syncFileModifiedAt() ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('File size') }}</td>
                                        <td class="py-1 fw-600">{{ $service->syncFileSize() > 0 ? number_format($service->syncFileSize()) . ' B' : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if($service->canExport())
                                <form action="{{ route('database_sync.export') }}" method="post" class="mt-3" onsubmit="return confirm('{{ translate('Regenerate the source schema from this local database? Deploy the updated source file before running sync on cPanel.') }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="las la-file-export"></i> {{ translate('Regenerate Local Schema File') }}
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning small py-2 px-3 mb-0 mt-3">
                                    {{ translate('This schema file is deployed from local source. It cannot be regenerated on this server.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded bg-soft-secondary p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fs-15 fw-700">{{ translate('Target Database Check') }}</div>
                                @if(count($inspectionErrors))
                                    <span class="badge badge-inline badge-danger">{{ translate('Check failed') }}</span>
                                @elseif($safeChanges || count($manualChanges))
                                    <span class="badge badge-inline badge-warning">{{ translate('Action needed') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('Synchronized') }}</span>
                                @endif
                            </div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Database name') }}</td>
                                        <td class="py-1 fw-600">{{ $databaseName ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Tables currently present') }}</td>
                                        <td class="py-1 fw-600">{{ $inspection['target_table_count'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Missing tables') }}</td>
                                        <td class="py-1 fw-700 {{ count($missingTables) ? 'text-danger' : 'text-success' }}">{{ count($missingTables) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Missing columns') }}</td>
                                        <td class="py-1 fw-700 {{ count($missingColumns) ? 'text-warning' : 'text-success' }}">{{ count($missingColumns) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted">{{ translate('Missing indexes') }}</td>
                                        <td class="py-1 fw-700 {{ count($missingIndexes) ? 'text-warning' : 'text-success' }}">{{ count($missingIndexes) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <form action="{{ route('database_sync.run') }}" method="post" class="mt-3" onsubmit="return confirm('{{ translate('Run safe sync now? Only missing tables, columns, and indexes will be created. Existing data will not be removed.') }}')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block" {{ count($inspectionErrors) || !$safeChanges ? 'disabled' : '' }}>
                                    <i class="las la-sync"></i> {{ translate('Run Safe Sync') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card border bg-soft-light mb-3">
                    <div class="card-body">
                        <h5 class="fw-700 mb-3"><i class="las la-clipboard-check"></i> {{ translate('Current Check Result') }}</h5>

                        @if(count($inspectionErrors))
                            <div class="alert alert-danger mb-0">
                                @foreach($inspectionErrors as $error)
                                    <div class="small mb-1">{{ $error }}</div>
                                @endforeach
                            </div>
                        @elseif(!$safeChanges && !count($manualChanges))
                            <div class="alert alert-success mb-0">
                                <i class="las la-check-circle"></i> {{ translate('The target database already contains every schema item from this source file.') }}
                            </div>
                        @else
                            @if(count($missingTables))
                                <div class="mb-3">
                                    <div class="fw-700 text-danger mb-1">{{ translate('Missing tables') }} ({{ count($missingTables) }})</div>
                                    <div class="small">
                                        @foreach($missingTables as $table)
                                            <code class="mr-1">{{ $table['table'] }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(count($missingColumns))
                                <div class="mb-3">
                                    <div class="fw-700 text-warning mb-1">{{ translate('Missing columns') }} ({{ count($missingColumns) }})</div>
                                    <div class="small">
                                        @foreach($missingColumns as $column)
                                            <code class="mr-1">{{ $column['table'] }}.{{ $column['column'] }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(count($missingIndexes))
                                <div class="mb-3">
                                    <div class="fw-700 text-warning mb-1">{{ translate('Missing indexes') }} ({{ count($missingIndexes) }})</div>
                                    <div class="small">
                                        @foreach($missingIndexes as $index)
                                            <code class="mr-1">{{ $index['table'] }}.{{ $index['name'] }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(count($manualChanges))
                                <div class="alert alert-warning mb-0">
                                    <div class="fw-700 mb-1">{{ translate('Manual review required') }} ({{ count($manualChanges) }})</div>
                                    @foreach($manualChanges as $change)
                                        <div class="small mb-1"><code>{{ $change['label'] }}</code> - {{ $change['reason'] }}</div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                @if(session('sync_report'))
                    @php
                        $report = session('sync_report');
                        $changed = count($report['created_tables'] ?? []) + count($report['added_columns'] ?? []) + count($report['added_keys'] ?? []);
                        $skipped = $report['skipped_changes'] ?? [];
                    @endphp
                    <div class="card border-0 bg-soft-light">
                        <div class="card-body">
                            <h5 class="fw-700 mb-3"><i class="las la-history"></i> {{ translate('Last Sync Report') }}</h5>

                            @if($changed)
                                <div class="alert alert-success py-2 mb-2">
                                    <b>{{ translate('Safe changes applied') }} ({{ $changed }})</b>
                                    @if(count($report['created_tables'] ?? []))
                                        <div class="small">{{ translate('Tables created') }}: {{ implode(', ', $report['created_tables']) }}</div>
                                    @endif
                                    @if(count($report['added_columns'] ?? []))
                                        <div class="small">{{ translate('Columns added') }}: {{ implode(', ', $report['added_columns']) }}</div>
                                    @endif
                                    @if(count($report['added_keys'] ?? []))
                                        <div class="small">{{ translate('Indexes added') }}: {{ implode(', ', $report['added_keys']) }}</div>
                                    @endif
                                </div>
                            @elseif(!count($report['errors'] ?? []))
                                <div class="alert alert-success py-2 mb-2">{{ translate('No safe schema changes were required.') }}</div>
                            @endif

                            @if(count($skipped))
                                <div class="alert alert-warning py-2 mb-2">
                                    <b>{{ translate('Not changed automatically') }} ({{ count($skipped) }})</b>
                                    @foreach($skipped as $change)
                                        <div class="small"><code>{{ $change['label'] }}</code> - {{ $change['reason'] }}</div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($report['errors'] ?? []))
                                <div class="alert alert-danger py-2 mb-0">
                                    <b>{{ translate('Errors') }} ({{ count($report['errors']) }})</b>
                                    @foreach($report['errors'] as $error)
                                        <div class="small mb-1">{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
