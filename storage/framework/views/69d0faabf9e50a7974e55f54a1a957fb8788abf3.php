<?php
    $featured_products = Cache::remember('featured_products', 3600, function (){ 
       return filter_products(\App\Models\Product::where('published', 1)->where('featured', '1'))->limit(12)->get();
        
     
    });
?>

<?php if(count($featured_products) > 0): ?>
    <section class="mb-4">
        <div class="container">
            <div class="py-4 py-md-3">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('New Arrivals')); ?></span>
                    </h3>
                </div>
                <!--Previous Code Start -->
                
                <div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="4" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                    <?php $__currentLoopData = $featured_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-box">
                        <?php echo $__env->make('frontend.partials.product_box_1',['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                 <!--Previous Code Ends -->
                 
                 
                
                 
                
                
                
            </div>
        </div>
        
        
         
        
    </section>   
    
    <?php endif; ?>
    
  
    
    
    
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/backupfeatured_products_section.blade.php ENDPATH**/ ?>