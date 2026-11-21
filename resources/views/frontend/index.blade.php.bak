@extends('frontend.layouts.app')

@section('content')
    {{-- Categories , Sliders . Today's deal --}}
   

    <div class="home-banner-area mb-4">
        @if (get_setting('home_slider_images') != null)
            <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height full-width-slider" data-arrows="false" data-dots="true" data-autoplay="true" data-infinite='true'>
                @php $slider_images = json_decode(get_setting('home_slider_images'), true);  @endphp
                @foreach ($slider_images as $key => $value)
                    <div class="carousel-box">
                        <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}" class="hero-slide-link">
                            <span class="hero-slide-bg" style="background-image: url('{{ uploaded_asset($slider_images[$key]) }}');" aria-hidden="true"></span>
                            <img
                                class="d-block mw-100 img-fit overflow-hidden hero-slide-img"
                                src="{{ uploaded_asset($slider_images[$key]) }}"
                                alt="{{ env('APP_NAME')}} promo"
                                height="770"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                            >
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
 
   
                
    {{-- Banner section 1 --}}
  @if (get_setting('home_banner1_images') != null)
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @php
                    $banner_1_imags = json_decode(get_setting('home_banner1_images'));
                    $banner_1_per_row = (int) get_setting('home_banner1_per_row');
                    $banner_1_per_row = ($banner_1_per_row >= 1 && $banner_1_per_row <= 3) ? $banner_1_per_row : 3;
                    $banner_1_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_1_per_row];
                @endphp
                @foreach ($banner_1_imags as $key => $value)
                    <div class="{{ $banner_1_col_class }}">
                    <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_1_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @if (count($banner1_categories) > 0)
        @include('frontend.partials.home_banner_categories', ['categories' => $banner1_categories])
    @endif
    </div>
    @endif 



    {{-- Flash Deal --}}
    @php
        $flash_deal = \App\Models\FlashDeal::where('status', 1)->where('featured', 1)->first();
    @endphp
    @if($flash_deal != null && strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date)
    <section class="mb-4">
        <div class="container">
            <div class="py-4 py-md-3">

                <div class="d-flex flex-wrap mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Flash Sale') }}</span>
                    </h3>
                    <div class="aiz-count-down ml-auto ml-lg-3 align-items-center" data-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md w-100 w-md-auto">{{ translate('View More') }}</a>
                </div>

                <div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                    @foreach ($flash_deal->flash_deal_products->take(20) as $key => $flash_deal_product)
                        @php
                            $product = \App\Models\Product::find($flash_deal_product->product_id);
                        @endphp
                        @if ($product != null && $product->published != 0)
                            <div class="carousel-box">
                                @include('frontend.partials.product_box_1',['product' => $product])
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
    
  
  
   
  
  
  
    @php
        $configured_home_categories = json_decode(get_setting('home_categories'), true) ?: [];
        $has_configured_new_arrivals = in_array('new_arrivals', $configured_home_categories);
        $has_configured_best_selling = in_array('best_selling', $configured_home_categories);
    @endphp

    {{-- Featured Product / New Arrivals (shown here only if NOT positioned inside Home Categories) --}}
    @if (!$has_configured_new_arrivals)
    <div id="section_best_sellers">
        @php
            $frontend_section_arrivals = Cache::remember('frontend_section_new_arrivals', 1800, function () {
                return view('frontend.partials.backupfeatured_products_section')->render();
            });
        @endphp
        {!! $frontend_section_arrivals !!}
    </div>
    @endif
    
      {{-- Banner Section 2 --}}
    @if (get_setting('home_banner2_images') != null)
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @php
                    $banner_2_imags = json_decode(get_setting('home_banner2_images'));
                    $banner_2_per_row = (int) get_setting('home_banner2_per_row');
                    $banner_2_per_row = ($banner_2_per_row >= 1 && $banner_2_per_row <= 3) ? $banner_2_per_row : 3;
                    $banner_2_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_2_per_row];
                @endphp
                @foreach ($banner_2_imags as $key => $value)
                    <div class="{{ $banner_2_col_class }}">
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner2_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_2_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @if (count($banner2_categories) > 0)
        @include('frontend.partials.home_banner_categories', ['categories' => $banner2_categories])
    @endif
    </div>
    @endif
        {{-- Category wise Products --}}
    <div id="section_home_categories">

    </div>
 {{-- Best Selling  --}}
    @if (!$has_configured_best_selling)
    <div id="section_best_selling">
        @php
            $frontend_section_best_selling = Cache::remember('frontend_section_best_selling', 1800, function () {
                return view('frontend.partials.best_selling_section')->render();
            });
        @endphp
        {!! $frontend_section_best_selling !!}
    </div>
    @endif
    
   

    {{--All Products --}}
    <div id="section_featured">

    </div>
    
    

   

    <!-- Auction Product -->
    @if(addon_is_activated('auction'))
        <div id="auction_products">

        </div>
    @endif
    
   


  

 
    
        {{-- Best sellers --}}
    <div id="section_feature_product">

    </div>

    

    {{-- Classified Product --}}
    @if(get_setting('classified_product') == 1)
        @php
            $classified_products = \App\Models\CustomerProduct::where('status', '1')->where('published', '1')->take(10)->get();
        @endphp
           @if (count($classified_products) > 0)
               <section class="mb-4">
                   <div class="container">
                       <div class="py-4 py-md-3">
                            <div class="d-flex mb-3 align-items-baseline border-bottom">
                                <h3 class="h5 fw-700 mb-0">-->
                                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Classified Ads') }}</span>-->
                                    
                                </h3>
                                <a href="{{ route('customer.products') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View More') }}</a>
                            </div>
<div class="aiz-carousel gutters-10" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                               @foreach ($classified_products as $key => $classified_product)
                                   <div class="carousel-box">
                                        <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                            <div class="position-relative">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block">
                                                    <img
                                                        class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($classified_product->thumbnail_img) }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </a>
                                                <div class="absolute-top-left pt-2 pl-2">
                                                    @if($classified_product->conditon == 'new')
                                                       <span class="badge badge-inline badge-success">{{translate('new')}}</span>
                                                    @elseif($classified_product->conditon == 'used')
                                                       <span class="badge badge-inline badge-danger">{{translate('Used')}}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-md-3 p-2 text-left">
                                                <div class="fs-15 mb-1">
                                                    <span class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">-->
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}" class="d-block text-reset">{{ $classified_product->getTranslation('name') }}</a>-->
                                                </h3>
                                            </div>
                                       </div>
                                   </div>
                               @endforeach
                           </div>
                       </div>
                   </div>
               </section>
           @endif
       @endif

    {{-- Banner Section 2 --}}
    @if (get_setting('home_banner3_images') != null)
    <div class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @php
                    $banner_3_imags = json_decode(get_setting('home_banner3_images'));
                    $banner_3_per_row = (int) get_setting('home_banner3_per_row');
                    $banner_3_per_row = ($banner_3_per_row >= 1 && $banner_3_per_row <= 3) ? $banner_3_per_row : 3;
                    $banner_3_col_class = ['1' => 'col-12', '2' => 'col-12 col-md-6', '3' => 'col-12 col-md-4'][$banner_3_per_row];
                @endphp
                @foreach ($banner_3_imags as $key => $value)
                    <div class="{{ $banner_3_col_class }}">
                        <div class="mb-3 mb-lg-0">
                            <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}" class="d-block text-reset">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($banner_3_imags[$key]) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload w-100">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @if (count($banner3_categories) > 0)
        @include('frontend.partials.home_banner_categories', ['categories' => $banner3_categories])
    @endif
    </div>
    @endif
    
    
    

   
    
    
   

    {{-- Top 10 categories and Brands --}}
    @if (get_setting('top10_categories') != null && get_setting('top10_brands') != null)
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @if (get_setting('top10_categories') != null)
                    <div class="col-lg-6">
                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Top 10 Categories') }}</span>
                            </h3>
                            <a href="{{ route('categories.all') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View All Categories') }}</a>
                        </div>
                        <div class="row gutters-5">
                            @php $top10_categories = json_decode(get_setting('top10_categories')); @endphp
                            @foreach ($top10_categories as $key => $value)
                                @php $category = \App\Models\Category::find($value); @endphp
                                @if ($category != null)
                                    <div class="col-sm-6">
                                        <a href="{{ route('products.category', $category->slug) }}" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col-3 text-center">
                                                    <img
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($category->banner) }}"
                                                        alt="{{ $category->getTranslation('name') }}"
                                                        class="img-fluid img lazyload h-60px"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </div>
                                                <div class="col-7">
                                                    <div class="text-truncat-2 pl-3 fs-14 fw-600 text-left">{{ $category->getTranslation('name') }}</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <i class="la la-angle-right text-primary"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if (get_setting('top10_brands') != null)
                    <div class="col-lg-6">
                        <div class="d-flex mb-3 align-items-baseline border-bottom">
                            <h3 class="h5 fw-700 mb-0">
                                <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Top 10 Brands') }}</span>
                            </h3>
                            <a href="{{ route('brands.all') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View All Brands') }}</a>
                        </div>
                        <div class="row gutters-5">
                            @php $top10_brands = json_decode(get_setting('top10_brands')); @endphp
                            @foreach ($top10_brands as $key => $value)
                                @php $brand = \App\Models\Brand::find($value); @endphp
                                @if ($brand != null)
                                    <div class="col-sm-6">
                                        <a href="{{ route('products.brand', $brand->slug) }}" class="bg-white border d-block text-reset rounded p-2 hov-shadow-md mb-2">
                                            <div class="row align-items-center no-gutters">
                                                <div class="col-4 text-center">
                                                    <img
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($brand->logo) }}"
                                                        alt="{{ $brand->getTranslation('name') }}"
                                                        class="img-fluid img lazyload h-60px"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    >
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-truncate-2 pl-3 fs-14 fw-600 text-left">{{ $brand->getTranslation('name') }}</div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <i class="la la-angle-right text-primary"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Categories (Shown above footer) --}}
    @if (count($featured_categories) > 0)
    <section class="mb-4">
        <div class="container">
            <div class="d-flex mb-3 align-items-baseline border-bottom">
                <h3 class="h5 fw-700 mb-0">
                    <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Featured Categories') }}</span>
                </h3>
                <a href="{{ route('categories.all') }}" class="ml-auto mr-0 btn btn-primary btn-sm shadow-md">{{ translate('View All Categories') }}</a>
            </div>
            <div class="row gutters-10">
                @foreach ($featured_categories as $key => $category)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mt-3">
                        <div class="aiz-card-box product-card-reference category-card-reference border border-light rounded hov-shadow-md has-transition bg-white text-reset h-100 d-flex flex-column">
                            <a href="{{ route('products.category', $category->slug) }}" class="d-block text-reset flex-grow-1">
                                <div class="position-relative product-card-image">
                                    <img
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($category->banner) }}"
                                        alt="{{ $category->getTranslation('name') }}"
                                        class="lazyload product-card-image-img"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                                    >
                                </div>
                                <div class="product-card-info flex-grow-1">
                                    <h3 class="product-card-name mb-0">
                                        <span class="d-block text-reset">{{ $category->getTranslation('name') }}</span>
                                    </h3>
                                </div>
                            </a>
                            <div class="px-3 pb-3 pt-2">
                                <a href="{{ route('products.category', $category->slug) }}" class="btn btn-brand btn-sm btn-block">
                                    {{ translate('Shop Now') }}
                                    <i class="las la-arrow-right btn-brand-arrow"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            AIZ.plugins.slickCarousel();
            $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            @if(addon_is_activated('auction'))
            $.post('{{ route('home.section.auction_products') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });
            @endif
            $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_feature_product').html(data);
                AIZ.plugins.slickCarousel();
            });
        });
    </script>
@endsection
