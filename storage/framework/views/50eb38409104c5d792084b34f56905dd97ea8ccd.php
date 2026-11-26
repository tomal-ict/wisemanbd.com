<?php $__env->startSection('content'); ?>
<?php
    $missingTables = $inspection['missing_tables'] ?? [];
    $missingColumns = $inspection['missing_columns'] ?? [];
    $missingIndexes = $inspection['missing_indexes'] ?? [];
    $manualChanges = $inspection['manual_changes'] ?? [];
    $inspectionErrors = $inspection['errors'] ?? [];
    $safeChanges = count($missingTables) + count($missingColumns) + count($missingIndexes);
    $connection = config('database.default');
    $databaseName = config('database.connections.' . $connection . '.database');
?>

<div class="row">
    <div class="col-lg-11 col-xxl-9 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="h6 mb-0">
                    <i class="las la-database mr-1"></i>
                    <?php echo e(translate('Database Sync')); ?>

                </h3>
                <div class="card-header-actions">
                    <a href="<?php echo e(route('database_sync.index')); ?>" class="btn btn-soft-secondary btn-sm">
                        <i class="las la-redo-alt"></i> <?php echo e(translate('Check Again')); ?>

                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <strong><?php echo e(translate('Safe schema repair')); ?></strong>
                    <div class="small mt-1">
                        <?php echo e(translate('This tool only creates tables, columns, and indexes that are absent from the deployed database. It never drops tables, deletes data, imports data, or changes existing column definitions.')); ?>

                    </div>
                </div>

                <div class="row gutters-10 mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded bg-soft-secondary p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fs-15 fw-700"><?php echo e(translate('Source Schema')); ?></div>
                                <?php if($service->syncFileExists()): ?>
                                    <span class="badge badge-inline badge-success"><?php echo e(translate('Ready')); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-inline badge-danger"><?php echo e(translate('Missing')); ?></span>
                                <?php endif; ?>
                            </div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Schema file')); ?></td>
                                        <td class="py-1 fw-600"><code>database/sync/database.sql</code></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Tables in source')); ?></td>
                                        <td class="py-1 fw-600"><?php echo e($inspection['source_table_count'] ?? count($schema['tables'] ?? [])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Last updated')); ?></td>
                                        <td class="py-1 fw-600"><?php echo e($service->syncFileModifiedAt() ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('File size')); ?></td>
                                        <td class="py-1 fw-600"><?php echo e($service->syncFileSize() > 0 ? number_format($service->syncFileSize()) . ' B' : '-'); ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if($service->canExport()): ?>
                                <form action="<?php echo e(route('database_sync.export')); ?>" method="post" class="mt-3" onsubmit="return confirm('<?php echo e(translate('Regenerate the source schema from this local database? Deploy the updated source file before running sync on cPanel.')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="las la-file-export"></i> <?php echo e(translate('Regenerate Local Schema File')); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning small py-2 px-3 mb-0 mt-3">
                                    <?php echo e(translate('This schema file is deployed from local source. It cannot be regenerated on this server.')); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded bg-soft-secondary p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fs-15 fw-700"><?php echo e(translate('Target Database Check')); ?></div>
                                <?php if(count($inspectionErrors)): ?>
                                    <span class="badge badge-inline badge-danger"><?php echo e(translate('Check failed')); ?></span>
                                <?php elseif($safeChanges || count($manualChanges)): ?>
                                    <span class="badge badge-inline badge-warning"><?php echo e(translate('Action needed')); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-inline badge-success"><?php echo e(translate('Synchronized')); ?></span>
                                <?php endif; ?>
                            </div>
                            <table class="table table-sm table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Database name')); ?></td>
                                        <td class="py-1 fw-600"><?php echo e($databaseName ?: '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Tables currently present')); ?></td>
                                        <td class="py-1 fw-600"><?php echo e($inspection['target_table_count'] ?? 0); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Missing tables')); ?></td>
                                        <td class="py-1 fw-700 <?php echo e(count($missingTables) ? 'text-danger' : 'text-success'); ?>"><?php echo e(count($missingTables)); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Missing columns')); ?></td>
                                        <td class="py-1 fw-700 <?php echo e(count($missingColumns) ? 'text-warning' : 'text-success'); ?>"><?php echo e(count($missingColumns)); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 text-muted"><?php echo e(translate('Missing indexes')); ?></td>
                                        <td class="py-1 fw-700 <?php echo e(count($missingIndexes) ? 'text-warning' : 'text-success'); ?>"><?php echo e(count($missingIndexes)); ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <form action="<?php echo e(route('database_sync.run')); ?>" method="post" class="mt-3" onsubmit="return confirm('<?php echo e(translate('Run safe sync now? Only missing tables, columns, and indexes will be created. Existing data will not be removed.')); ?>')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-block" <?php echo e(count($inspectionErrors) || !$safeChanges ? 'disabled' : ''); ?>>
                                    <i class="las la-sync"></i> <?php echo e(translate('Run Safe Sync')); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card border bg-soft-light mb-3">
                    <div class="card-body">
                        <h5 class="fw-700 mb-3"><i class="las la-clipboard-check"></i> <?php echo e(translate('Current Check Result')); ?></h5>

                        <?php if(count($inspectionErrors)): ?>
                            <div class="alert alert-danger mb-0">
                                <?php $__currentLoopData = $inspectionErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="small mb-1"><?php echo e($error); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif(!$safeChanges && !count($manualChanges)): ?>
                            <div class="alert alert-success mb-0">
                                <i class="las la-check-circle"></i> <?php echo e(translate('The target database already contains every schema item from this source file.')); ?>

                            </div>
                        <?php else: ?>
                            <?php if(count($missingTables)): ?>
                                <div class="mb-3">
                                    <div class="fw-700 text-danger mb-1"><?php echo e(translate('Missing tables')); ?> (<?php echo e(count($missingTables)); ?>)</div>
                                    <div class="small">
                                        <?php $__currentLoopData = $missingTables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <code class="mr-1"><?php echo e($table['table']); ?></code>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(count($missingColumns)): ?>
                                <div class="mb-3">
                                    <div class="fw-700 text-warning mb-1"><?php echo e(translate('Missing columns')); ?> (<?php echo e(count($missingColumns)); ?>)</div>
                                    <div class="small">
                                        <?php $__currentLoopData = $missingColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <code class="mr-1"><?php echo e($column['table']); ?>.<?php echo e($column['column']); ?></code>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(count($missingIndexes)): ?>
                                <div class="mb-3">
                                    <div class="fw-700 text-warning mb-1"><?php echo e(translate('Missing indexes')); ?> (<?php echo e(count($missingIndexes)); ?>)</div>
                                    <div class="small">
                                        <?php $__currentLoopData = $missingIndexes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <code class="mr-1"><?php echo e($index['table']); ?>.<?php echo e($index['name']); ?></code>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(count($manualChanges)): ?>
                                <div class="alert alert-warning mb-0">
                                    <div class="fw-700 mb-1"><?php echo e(translate('Manual review required')); ?> (<?php echo e(count($manualChanges)); ?>)</div>
                                    <?php $__currentLoopData = $manualChanges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="small mb-1"><code><?php echo e($change['label']); ?></code> - <?php echo e($change['reason']); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(session('sync_report')): ?>
                    <?php
                        $report = session('sync_report');
                        $changed = count($report['created_tables'] ?? []) + count($report['added_columns'] ?? []) + count($report['added_keys'] ?? []);
                        $skipped = $report['skipped_changes'] ?? [];
                    ?>
                    <div class="card border-0 bg-soft-light">
                        <div class="card-body">
                            <h5 class="fw-700 mb-3"><i class="las la-history"></i> <?php echo e(translate('Last Sync Report')); ?></h5>

                            <?php if($changed): ?>
                                <div class="alert alert-success py-2 mb-2">
                                    <b><?php echo e(translate('Safe changes applied')); ?> (<?php echo e($changed); ?>)</b>
                                    <?php if(count($report['created_tables'] ?? [])): ?>
                                        <div class="small"><?php echo e(translate('Tables created')); ?>: <?php echo e(implode(', ', $report['created_tables'])); ?></div>
                                    <?php endif; ?>
                                    <?php if(count($report['added_columns'] ?? [])): ?>
                                        <div class="small"><?php echo e(translate('Columns added')); ?>: <?php echo e(implode(', ', $report['added_columns'])); ?></div>
                                    <?php endif; ?>
                                    <?php if(count($report['added_keys'] ?? [])): ?>
                                        <div class="small"><?php echo e(translate('Indexes added')); ?>: <?php echo e(implode(', ', $report['added_keys'])); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif(!count($report['errors'] ?? [])): ?>
                                <div class="alert alert-success py-2 mb-2"><?php echo e(translate('No safe schema changes were required.')); ?></div>
                            <?php endif; ?>

                            <?php if(count($skipped)): ?>
                                <div class="alert alert-warning py-2 mb-2">
                                    <b><?php echo e(translate('Not changed automatically')); ?> (<?php echo e(count($skipped)); ?>)</b>
                                    <?php $__currentLoopData = $skipped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="small"><code><?php echo e($change['label']); ?></code> - <?php echo e($change['reason']); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if(count($report['errors'] ?? [])): ?>
                                <div class="alert alert-danger py-2 mb-0">
                                    <b><?php echo e(translate('Errors')); ?> (<?php echo e(count($report['errors'])); ?>)</b>
                                    <?php $__currentLoopData = $report['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="small mb-1"><?php echo e($error); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/backend/system/database_sync.blade.php ENDPATH**/ ?>