

<?php $__env->startSection('content'); ?>
    
   

    <div class="home-banner-area mb-4">
        <?php if(get_setting('home_slider_images') != null): ?>
            <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height full-width-slider" data-arrows="false" data-dots="true" data-autoplay="true" data-infinite='true'>
                <?php $slider_images = json_decode(get_setting('home_slider_images'), true);  ?>
                <?php $__currentLoopData = $slider_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-box">
                        <a href="<?php echo e(json_decode(get_setting('home_slider_links'), true)[$key]); ?>" class="hero-slide-link">
                            <span class="hero-slide-bg" style="background-image: url('<?php echo e(uploaded_asset($slider_images[$key])); ?>');" aria-hidden="true"></span>
                            <img
                                class="d-block mw-100 img-fit overflow-hidden hero-slide-img"
                                src="<?php echo e(uploaded_asset($slider_images[$key])); ?>"
                                alt="<?php echo e(env('APP_NAME')); ?> promo"
                                height="770"
                                onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>';"
                            >
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
 
   
                
    
  <?php if(get_setting('home_banner1_images') != null): ?>
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php
                    $banner_1_imags = json_decode(get_setting('home_banner1_images'));
                    $banner_1_per_row = (int) get_setting('home_banner1_per_row');
                    $banner_1_per_row = ($banner_1_per_row >= 1 && $banner_1_per_row <= 3) ? $banner_1_per_row : 3;
                    $banner_1_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_1_per_row];
                ?>
                <?php $__currentLoopData = $banner_1_imags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($banner_1_col_class); ?>">
                    <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
                        <div class="mb-3 mb-lg-0">
                            <a href="<?php echo e(json_decode(get_setting('home_banner1_links'), true)[$key]); ?>" class="d-block text-reset">
                                <img src="<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>" data-src="<?php echo e(uploaded_asset($banner_1_imags[$key])); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php if(count($banner1_categories) > 0): ?>
        <?php echo $__env->make('frontend.partials.home_banner_categories', ['categories' => $banner1_categories], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    </div>
    <?php endif; ?> 



    
    <?php
        $flash_deal = \App\Models\FlashDeal::where('status', 1)->where('featured', 1)->first();
    ?>
    <?php if($flash_deal != null && strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date): ?>
    <section class="mb-4">
        <div class="container">
            <div class="py-4 py-md-3">

                <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('Flash Sale')); ?></span>
                    </h3>
                    <div class="aiz-count-down ml-auto ml-lg-3 align-items-center" data-date="<?php echo e(date('Y/m/d H:i:s', $flash_deal->end_date)); ?>"></div>
                    <a href="<?php echo e(route('flash-deal-details', $flash_deal->slug)); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md w-100 w-md-auto"><?php echo e(translate('View More')); ?></a>
                </div>

                <div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                    <?php $__currentLoopData = $flash_deal->flash_deal_products->take(20); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $flash_deal_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $product = \App\Models\Product::find($flash_deal_product->product_id);
                        ?>
                        <?php if($product != null && $product->published != 0): ?>
                            <div class="carousel-box">
                                <?php echo $__env->make('frontend.partials.product_box_1',['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
  
  
   
  
  
  
    <?php
        $configured_home_categories = json_decode(get_setting('home_categories'), true) ?: [];
        $has_configured_new_arrivals = in_array('new_arrivals', $configured_home_categories);
        $has_configured_best_selling = in_array('best_selling', $configured_home_categories);
    ?>

    
    <?php if(!$has_configured_new_arrivals): ?>
    <div id="section_best_sellers">
        <?php
            $frontend_section_arrivals = Cache::remember('frontend_section_new_arrivals', 1800, function () {
                return view('frontend.partials.backupfeatured_products_section')->render();
            });
        ?>
        <?php echo $frontend_section_arrivals; ?>

    </div>
    <?php endif; ?>
    
      
    <?php if(get_setting('home_banner2_images') != null): ?>
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php
                    $banner_2_imags = json_decode(get_setting('home_banner2_images'));
                    $banner_2_per_row = (int) get_setting('home_banner2_per_row');
                    $banner_2_per_row = ($banner_2_per_row >= 1 && $banner_2_per_row <= 3) ? $banner_2_per_row : 3;
                    $banner_2_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_2_per_row];
                ?>
                <?php $__currentLoopData = $banner_2_imags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($banner_2_col_class); ?>">
                        <div class="mb-3 mb-lg-0">
                            <a href="<?php echo e(json_decode(get_setting('home_banner2_links'), true)[$key]); ?>" class="d-block text-reset">
                                <img src="<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>" data-src="<?php echo e(uploaded_asset($banner_2_imags[$key])); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php if(count($banner2_categories) > 0): ?>
        <?php echo $__env->make('frontend.partials.home_banner_categories', ['categories' => $banner2_categories], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    </div>
    <?php endif; ?>
        
    <div id="section_home_categories">

    </div>
 
    <?php if(!$has_configured_best_selling): ?>
    <div id="section_best_selling">
        <?php
            $frontend_section_best_selling = Cache::remember('frontend_section_best_selling', 1800, function () {
                return view('frontend.partials.best_selling_section')->render();
            });
        ?>
        <?php echo $frontend_section_best_selling; ?>

    </div>
    <?php endif; ?>
    
   

    
    <div id="section_featured">

    </div>
    
    

   

    <!-- Auction Product -->
    <?php if(addon_is_activated('auction')): ?>
        <div id="auction_products">

        </div>
    <?php endif; ?>
    
   


  

 
    
        
    <div id="section_feature_product">

    </div>

    

    
    <?php if(get_setting('classified_product') == 1): ?>
        <?php
            $classified_products = \App\Models\CustomerProduct::where('status', '1')->where('published', '1')->take(10)->get();
        ?>
           <?php if(count($classified_products) > 0): ?>
               <section class="mb-4">
                   <div class="container">
                       <div class="py-4 py-md-3">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">-->
                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('Classified Ads')); ?></span>-->
                                    
                                </h3>
                                <a href="<?php echo e(route('customer.products')); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md"><?php echo e(translate('View More')); ?></a>
                            </div>
<div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                               <?php $__currentLoopData = $classified_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $classified_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <div class="carousel-box">
                                        <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                            <div class="position-relative">
                                                <a href="<?php echo e(route('customer.product', $classified_product->slug)); ?>" class="d-block">
                                                    <img
                                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                        src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                                        data-src="<?php echo e(uploaded_asset($classified_product->thumbnail_img)); ?>"
                                                        alt="<?php echo e($classified_product->getTranslation('name')); ?>"
                                                        onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
                                                    >
                                                </a>
                                                <div class="absolute-top-left pt-2 pl-2">
                                                    <?php if($classified_product->conditon == 'new'): ?>
                                                       <span class="badge badge-inline badge-success"><?php echo e(translate('new')); ?></span>
                                                    <?php elseif($classified_product->conditon == 'used'): ?>
                                                       <span class="badge badge-inline badge-danger"><?php echo e(translate('Used')); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="p-md-3 p-2 text-left">
                                                <div class="fs-15 mb-1">
                                                    <span class="fw-700 text-primary"><?php echo e(single_price($classified_product->unit_price)); ?></span>
                                                </div>
                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">-->
                                                    <a href="<?php echo e(route('customer.product', $classified_product->slug)); ?>" class="d-block text-reset"><?php echo e($classified_product->getTranslation('name')); ?></a>-->
                                                </h3>
                                            </div>
                                       </div>
                                   </div>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </div>
                       </div>
                   </div>
               </section>
           <?php endif; ?>
       <?php endif; ?>

    
    <?php if(get_setting('home_banner3_images') != null): ?>
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php
                    $banner_3_imags = json_decode(get_setting('home_banner3_images'));
                    $banner_3_per_row = (int) get_setting('home_banner3_per_row');
                    $banner_3_per_row = ($banner_3_per_row >= 1 && $banner_3_per_row <= 3) ? $banner_3_per_row : 3;
                    $banner_3_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_3_per_row];
                ?>
                <?php $__currentLoopData = $banner_3_imags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="<?php echo e($banner_3_col_class); ?>">
                        <div class="mb-3 mb-lg-0">
                            <a href="<?php echo e(json_decode(get_setting('home_banner3_links'), true)[$key]); ?>" class="d-block text-reset">
                                <img src="<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>" data-src="<?php echo e(uploaded_asset($banner_3_imags[$key])); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php if(count($banner3_categories) > 0): ?>
        <?php echo $__env->make('frontend.partials.home_banner_categories', ['categories' => $banner3_categories], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    </div>
    <?php endif; ?>
    
    
    

   
    
    
   

    
    <?php if(get_setting('top10_categories') != null && get_setting('top10_brands') != null): ?>
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php if(get_setting('top10_categories') != null): ?>
                    <div class="col-lg-6">
                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('Top 10 Categories')); ?></span>
                            </h3>
                            <a href="<?php echo e(route('categories.all')); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md"><?php echo e(translate('View All Categories')); ?></a>
                        </div>
                        <div class="row gutters-5">
                            <?php $top10_categories = json_decode(get_setting('top10_categories')); ?>
                            <?php $__currentLoopData = $top10_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $category = \App\Models\Category::find($value); ?>
                                <?php if($category != null): ?>
                                    <div class="col-sm-6">
                                        <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col-3 text-center">
                                                    <img
                                                        src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                                        data-src="<?php echo e(uploaded_asset($category->banner)); ?>"
                                                        alt="<?php echo e($category->getTranslation('name')); ?>"
                                                        class="img-fluid img lazyload h-60px"
                                                        onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
                                                    >
                                                </div>
                                                <div class="col-7">
                                                    <div class="text-truncat-2 pl-3 fs-14 fw-600 text-left"><?php echo e($category->getTranslation('name')); ?></div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <i class="la la-angle-right text-primary"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if(get_setting('top10_brands') != null): ?>
                    <div class="col-lg-6">
                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('Top 10 Brands')); ?></span>
                            </h3>
                            <a href="<?php echo e(route('brands.all')); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md"><?php echo e(translate('View All Brands')); ?></a>
                        </div>
                        <div class="row gutters-5">
                            <?php $top10_brands = json_decode(get_setting('top10_brands')); ?>
                            <?php $__currentLoopData = $top10_brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $brand = \App\Models\Brand::find($value); ?>
                                <?php if($brand != null): ?>
                                    <div class="col-sm-6">
                                        <a href="<?php echo e(route('products.brand', $brand->slug)); ?>" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col-4 text-center">
                                                    <img
                                                        src="<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>"
                                                        data-src="<?php echo e(uploaded_asset($brand->logo)); ?>"
                                                        alt="<?php echo e($brand->getTranslation('name')); ?>"
                                                        class="img-fluid img lazyload h-60px"
                                                        onerror="this.onerror=null;this.src='<?php echo e(static_asset('assets/img/placeholder.jpg')); ?>';"
                                                    >
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-truncate-2 pl-3 fs-14 fw-600 text-left"><?php echo e($brand->getTranslation('name')); ?></div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <i class="la la-angle-right text-primary"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    
    <?php if(count($featured_categories) > 0): ?>
    <section class="mb-4">
        <div class="container">
            <div class="d-flex mb-3 align-items-baseline border-bottom">
                <h3 class="h5 fw-700 mb-0">
                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block"><?php echo e(translate('Featured Categories')); ?></span>
                </h3>
                <a href="<?php echo e(route('categories.all')); ?>" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md"><?php echo e(translate('View All Categories')); ?></a>
            </div>
            <div class="row gutters-10">
                <?php $__currentLoopData = $featured_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    </section>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function(){
            AIZ.plugins.slickCarousel();
            $.post('<?php echo e(route('home.section.home_categories')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            <?php if(addon_is_activated('auction')): ?>
            $.post('<?php echo e(route('home.section.auction_products')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });
            <?php endif; ?>
            $.post('<?php echo e(route('home.section.best_sellers')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
                $('#section_feature_product').html(data);
                AIZ.plugins.slickCarousel();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/index.blade.php ENDPATH**/ ?>