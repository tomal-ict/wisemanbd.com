<div class="category-mega-menu">
    <div class="category-mega-menu-links">
        <?php $__currentLoopData = \App\Utility\CategoryUtility::get_immediate_children($category->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $first_level_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="category-mega-menu-link-group">
                <a class="category-mega-menu-parent" href="<?php echo e(route('products.category', $first_level_category->slug)); ?>"><?php echo e($first_level_category->getTranslation('name')); ?></a>
                <?php $__currentLoopData = \App\Utility\CategoryUtility::get_immediate_children($first_level_category->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $second_level_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="category-mega-menu-child" href="<?php echo e(route('products.category', $second_level_category->slug)); ?>"><?php echo e($second_level_category->getTranslation('name')); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="category-mega-menu-products">
        <div class="category-mega-menu-heading">
            <a href="<?php echo e(route('products.category', $category->slug)); ?>"><?php echo e($category->getTranslation('name')); ?></a>
        </div>
        <?php if($products->isNotEmpty()): ?>
            <div class="category-product-slides" data-interval="5000">
                <?php $__currentLoopData = $products->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="category-product-slide<?php echo e($loop->first ? ' active' : ''); ?>">
                        <?php $__currentLoopData = $product_group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a class="category-product-card" href="<?php echo e(route('product', $product->slug)); ?>" title="<?php echo e($product->getTranslation('name')); ?>">
                                <img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" alt="<?php echo e($product->getTranslation('name')); ?>" onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';">
                                <span><?php echo e($product->getTranslation('name')); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="category-mega-menu-empty"><?php echo e(translate('No products found')); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/category_elements.blade.php ENDPATH**/ ?>