<?php
    if (!isset($categories)) {
        $categories = [];
    }
?>
<?php if(count($categories) > 0): ?>
    <div class="container">
        <div class="row gutters-10">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mt-3">
                    <div class="aiz-card-box product-card-reference category-card-reference border border-light rounded hov-shadow-md has-transition bg-white text-reset h-100 d-flex flex-column">
                        <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="d-block text-reset flex-grow-1">
                            <div class="position-relative product-card-image">
                                <img
                                    src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                    data-src="<?php echo e(uploaded_asset($category->banner)); ?>"
                                    alt="<?php echo e($category->getTranslation('name')); ?>"
                                    class="lazyload product-card-image-img"
                                    onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>';"
                                >
                            </div>
                            <div class="product-card-info flex-grow-1">
                                <h3 class="product-card-name mb-0">
                                    <span class="d-block text-reset"><?php echo e($category->getTranslation('name')); ?></span>
                                </h3>
                            </div>
                        </a>
                        <div class="px-3 pb-3 pt-2">
                            <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="btn btn-brand btn-sm btn-block">
                                <?php echo e(translate('Shop Now')); ?>

                                <i class="las la-arrow-right btn-brand-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?><?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/home_banner_categories.blade.php ENDPATH**/ ?>