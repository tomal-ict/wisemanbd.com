@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = \App\Models\Category::find($category_id)->meta_title;
        $meta_description = \App\Models\Category::find($category_id)->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = \App\Models\Brand::find($brand_id)->meta_title;
        $meta_description = \App\Models\Brand::find($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title         = get_setting('meta_title');
        $meta_description   = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

    <section class="mb-4 pt-3">
        <div class="container sm-px-0">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" >
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>
                                <div class="bg-white shadow-sm rounded mb-3">
                                    <div class="fs-15 fw-600 p-3 border-bottom">
                                        {{ translate('Categories')}}
                                    </div>
                                    <div class="p-3">
                                        <ul class="list-unstyled">
                                            @if (!isset($category_id))
                                                @foreach (\App\Models\Category::where('level', 0)->get() as $category)
                                                    <li class="mb-2 ml-2">
                                                        <a class="text-reset fs-14" href="{{ route('products.category', $category->slug) }}">{{ $category->getTranslation('name') }}</a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="mb-2">
                                                    <a class="text-reset fs-14 fw-600" href="{{ route('search') }}">
                                                        <i class="las la-angle-left"></i>
                                                        {{ translate('All Categories')}}
                                                    </a>
                                                </li>
                                                @if (\App\Models\Category::find($category_id)->parent_id != 0)
                                                    <li class="mb-2">
                                                        <a class="text-reset fs-14 fw-600" href="{{ route('products.category', \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->slug) }}">
                                                            <i class="las la-angle-left"></i>
                                                            {{ \App\Models\Category::find(\App\Models\Category::find($category_id)->parent_id)->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="mb-2">
                                                    <a class="text-reset fs-14 fw-600" href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">
                                                        <i class="las la-angle-left"></i>
                                                        {{ \App\Models\Category::find($category_id)->getTranslation('name') }}
                                                    </a>
                                                </li>
                                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category_id) as $key => $id)
                                                    <li class="ml-4 mb-2">
                                                        <a class="text-reset fs-14" href="{{ route('products.category', \App\Models\Category::find($id)->slug) }}">{{ \App\Models\Category::find($id)->getTranslation('name') }}</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="bg-white shadow-sm rounded mb-3">
                                    <div class="fs-15 fw-600 p-3 border-bottom d-flex justify-content-between align-items-center">
                                        <span>{{ translate('Price range')}}</span>
                                        @if((isset($min_price) && $min_price !== '') || (isset($max_price) && $max_price !== ''))
                                            <a href="javascript:void(0)" onclick="clearPriceFilter()" class="text-danger fs-12 font-weight-normal" style="text-decoration:none;">
                                                <i class="las la-times"></i> {{ translate('Clear') }}
                                            </a>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <div class="aiz-range-slider">
                                            <div
                                                id="input-slider-range"
                                                data-range-value-min="{{ $range_min ?? 0 }}"
                                                data-range-value-max="{{ $range_max ?? 5000 }}"
                                            ></div>

                                            <div class="row mt-2 align-items-center">
                                                <div class="col-6">
                                                    <span class="range-slider-value value-low fs-13 fw-600 opacity-70"
                                                        data-range-value-low="{{ (isset($min_price) && is_numeric($min_price)) ? (float)$min_price : ($range_min ?? 0) }}"
                                                        id="input-slider-range-value-low"
                                                    >{{ single_price((isset($min_price) && is_numeric($min_price)) ? (float)$min_price : ($range_min ?? 0)) }}</span>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <span class="range-slider-value value-high fs-13 fw-600 opacity-70"
                                                        data-range-value-high="{{ (isset($max_price) && is_numeric($max_price)) ? (float)$max_price : ($range_max ?? 5000) }}"
                                                        id="input-slider-range-value-high"
                                                    >{{ single_price((isset($max_price) && is_numeric($max_price)) ? (float)$max_price : ($range_max ?? 5000)) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Manual Min/Max Price Inputs with Apply Button -->
                                        <div class="mt-3 pt-2 border-top">
                                            <div class="row gutters-5">
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text p-1 text-muted fs-11">৳</span>
                                                        </div>
                                                        <input type="number" id="min_price_input" class="form-control form-control-sm px-1 text-center" placeholder="{{ translate('Min') }}" value="{{ $min_price ?? '' }}" min="{{ $range_min ?? 0 }}" max="{{ $range_max ?? 5000 }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text p-1 text-muted fs-11">৳</span>
                                                        </div>
                                                        <input type="number" id="max_price_input" class="form-control form-control-sm px-1 text-center" placeholder="{{ translate('Max') }}" value="{{ $max_price ?? '' }}" min="{{ $range_min ?? 0 }}" max="{{ $range_max ?? 5000 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-sm btn-block mt-2 font-weight-bold" onclick="applyPriceFilter()">
                                                <i class="las la-filter mr-1"></i> {{ translate('Apply Price') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @foreach ($attributes as $attribute)
                                    <div class="bg-white shadow-sm rounded mb-3">
                                        <div class="fs-15 fw-600 p-3 border-bottom">
                                            {{ translate('Filter by') }} {{ $attribute->getTranslation('name') }}
                                        </div>
                                        <div class="p-3">
                                            <div class="aiz-checkbox-list">
                                                @foreach ($attribute->attribute_values as $attribute_value)
                                                    <label class="aiz-checkbox">
                                                        <input
                                                            type="checkbox"
                                                            name="selected_attribute_values[]"
                                                            value="{{ $attribute_value->value }}" @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                            onchange="filter()"
                                                        >
                                                        <span class="aiz-square-check"></span>
                                                        <span>{{ $attribute_value->value }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- <button type="submit" class="btn btn-styled btn-block btn-base-4">Apply filter</button> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">

                        <ul class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item opacity-50">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                            </li>
                            @if(!isset($category_id))
                                <li class="breadcrumb-item fw-600  text-dark">
                                    <a class="text-reset" href="{{ route('search') }}">"{{ translate('All Categories')}}"</a>
                                </li>
                            @else
                                <li class="breadcrumb-item opacity-50">
                                    <a class="text-reset" href="{{ route('search') }}">{{ translate('All Categories')}}</a>
                                </li>
                            @endif
                            @if(isset($category_id))
                                <li class="text-dark fw-600 breadcrumb-item">
                                    <a class="text-reset" href="{{ route('products.category', \App\Models\Category::find($category_id)->slug) }}">"{{ \App\Models\Category::find($category_id)->getTranslation('name') }}"</a>
                                </li>
                            @endif
                        </ul>

                        <div class="text-left">
                            <div class="row gutters-5 flex-wrap align-items-center">
                                <div class="col-lg col-10">
                                    <h1 class="h6 fw-600 text-body">
                                        @if(isset($category_id))
                                            {{ \App\Models\Category::find($category_id)->getTranslation('name') }}
                                        @elseif(isset($query))
                                            {{ translate('Search result for ') }}"{{ $query }}"
                                        @else
                                            {{ translate('All Products') }}
                                        @endif
                                    </h1>
                                    <input type="hidden" name="keyword" value="{{ $query }}">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px">
                                    @if (Route::currentRouteName() != 'products.brand')
                                        <label class="mb-0 opacity-50">{{ translate('Brands')}}</label>
                                        <select class="form-control form-control-sm aiz-selectpicker" data-live-search="true" name="brand" onchange="filter()">
                                            <option value="">{{ translate('All Brands')}}</option>
                                            @foreach (\App\Models\Brand::all() as $brand)
                                                <option value="{{ $brand->slug }}" @isset($brand_id) @if ($brand_id == $brand->id) selected @endif @endisset>{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-6 col-lg-auto mb-3 w-lg-200px">
                                    <label class="mb-0 opacity-50">{{ translate('Sort by')}}</label>
                                    <select class="form-control form-control-sm aiz-selectpicker" name="sort_by" onchange="filter()">
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="min_price" value="{{ $min_price ?? '' }}">
                        <input type="hidden" name="max_price" value="{{ $max_price ?? '' }}">
                        <div class="row gutters-5 row-cols-xxl-4 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-2">
                            @foreach ($products as $key => $product)
                                <div class="col">
                                    @include('frontend.partials.product_box_1',['product' => $product])
                                </div>
                            @endforeach
                        </div>
                        <div class="aiz-pagination aiz-pagination-center mt-4">
                            {{ $products->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }

        function applyPriceFilter(){
            var minVal = $('#min_price_input').val();
            var maxVal = $('#max_price_input').val();
            $('input[name=min_price]').val(minVal);
            $('input[name=max_price]').val(maxVal);
            filter();
        }

        function clearPriceFilter(){
            $('input[name=min_price]').val('');
            $('input[name=max_price]').val('');
            $('#min_price_input').val('');
            $('#max_price_input').val('');
            filter();
        }

        function rangefilter(arg){
            var minVal = Math.round(parseFloat(arg[0]));
            var maxVal = Math.round(parseFloat(arg[1]));
            $('#min_price_input').val(minVal);
            $('#max_price_input').val(maxVal);
            $('input[name=min_price]').val(minVal);
            $('input[name=max_price]').val(maxVal);
            filter();
        }

        $(document).ready(function(){
            var sliderElem = document.getElementById("input-slider-range");
            if (sliderElem && sliderElem.noUiSlider) {
                sliderElem.noUiSlider.on("update", function(values, handle) {
                    var rounded = Math.round(parseFloat(values[handle]));
                    if (handle === 0) {
                        $('#input-slider-range-value-low').text('৳ ' + rounded);
                        $('#min_price_input').val(rounded);
                    } else {
                        $('#input-slider-range-value-high').text('৳ ' + rounded);
                        $('#max_price_input').val(rounded);
                    }
                });
            }

            $('#min_price_input, #max_price_input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    applyPriceFilter();
                }
            });
        });
    </script>
@endsection
