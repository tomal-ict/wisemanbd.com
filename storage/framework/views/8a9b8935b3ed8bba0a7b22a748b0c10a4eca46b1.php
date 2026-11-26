<div class="aiz-card-box product-card-reference border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white d-flex flex-column h-100">
    <div class="position-relative product-card-image">
        <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block h-100">
            <img
                class="img-fit lazyload product-card-image-img"
                src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                data-src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>"
                alt="<?php echo e($product->getTranslation('name')); ?>"
                onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
            >
        </a>
        <?php if($product->wholesale_product): ?>
            <span class="absolute-bottom-left fs-11 text-white fw-600 px-2 lh-1-8" style="background-color: #455a64">
                <?php echo e(translate('Wholesale')); ?>

            </span>
        <?php endif; ?>
    </div>
    <div class="product-card-info flex-grow-1">
        <h3 class="product-card-name mb-0">
            <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block text-reset" title="<?php echo e($product->getTranslation('name')); ?>"><?php echo e($product->getTranslation('name')); ?></a>
        </h3>
        <div class="product-card-price">
            <span class="product-current-price"><?php echo e(home_discounted_base_price($product)); ?></span>
            <?php if(home_base_price($product) != home_discounted_base_price($product)): ?>
                <del class="product-old-price"><?php echo e(home_base_price($product)); ?></del>
            <?php endif; ?>
            <?php if(discount_in_percentage($product) > 0): ?>
                <span class="product-discount-label"><?php echo e(discount_in_percentage($product)); ?>% <?php echo e(translate('OFF')); ?></span>
            <?php endif; ?>
        </div>
        <?php if(addon_is_activated('club_point')): ?>
            <div class="product-card-club-point">
                <?php echo e(translate('Club Point')); ?>:
                <span><?php echo e($product->earn_point); ?></span>
            </div>
        <?php endif; ?>
        <?php if($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0): ?>
            <div class="mt-1 mb-1">
                <?php $__currentLoopData = json_decode($product->sizes, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $psize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge badge-inline border px-2 py-1 fs-11 fw-600 mr-1"><?php echo e($psize); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="product-card-footer">
        <?php
            $pb1_has_options = (count(json_decode($product->colors)) > 0)
                || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
        ?>
        <a href="javascript:void(0)" class="product-card-cart-button" onclick="addToCartDirect(<?php echo e($product->id); ?>, <?php echo e($pb1_has_options ? 'true' : 'false'); ?>)" data-toggle="tooltip" data-title="<?php echo e(translate('Add to cart')); ?>" aria-label="<?php echo e(translate('Add to cart')); ?>">
            <i class="las la-shopping-cart"></i>
        </a>
        <a href="javascript:void(0)" class="product-card-buy-now-button" onclick="buyNowDirect(<?php echo e($product->id); ?>, <?php echo e($pb1_has_options ? 'true' : 'false'); ?>)">
            <span><?php echo e(translate('Buy Now')); ?></span>
            <i class="las la-arrow-right"></i>
        </a>
    </div>
</div>
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/product_box_1.blade.php ENDPATH**/ ?>