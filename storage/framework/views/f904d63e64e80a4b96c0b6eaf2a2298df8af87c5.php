<?php if(get_setting('home_categories') != null): ?> 
    <?php $home_categories = json_decode(get_setting('home_categories'), true) ?: []; ?>
    <?php $__currentLoopData = $home_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($value === 'new_arrivals'): ?>
            <?php echo $__env->make('frontend.partials.backupfeatured_products_section', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif($value === 'best_selling'): ?>
            <?php echo $__env->make('frontend.partials.best_selling_section', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <?php $category = \App\Models\Category::find($value); ?>
            <?php if($category != null): ?>
                <section class="mb-4">
                    <div class="container">
                        <div class="py-4 py-md-3">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">
                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e($category->getTranslation('name')); ?></span>
                                </h3>
                                <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md"><?php echo e(translate('View More')); ?></a>
                            </div>
                            <div class="row">
                                <?php $__currentLoopData = get_cached_products($category->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-6 col-md-3 col-lg-5th">
                                        <?php echo $__env->make('frontend.partials.product_box_1',['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/home_categories_section.blade.php ENDPATH**/ ?>