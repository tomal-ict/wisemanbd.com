@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency" content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    <section class="product-detail-reference mb-4 pt-4">
        <div class="container">
            <div class="product-detail-shell">
                <div class="row gutters-20">
                    <div class="col-xl-6 col-lg-6 mb-4 product-media-column">
                        <div class="sticky-top z-3 product-gallery-layout">
                            @php
                                $photos = explode(',', $detailedProduct->photos);
                            @endphp
                            <div class="product-main-media">
                                @if(home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                    <span class="product-sale-badge">{{ translate('Sale') }}</span>
                                @endif
                                <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'>
                                    @foreach ($photos as $key => $photo)
                                        <div class="carousel-box product-click-zoom">
                                            <img
                                                class="img-fluid lazyload"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ uploaded_asset($photo) }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                            >
                                        </div>
                                    @endforeach
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box product-click-zoom">
                                                <img
                                                    class="img-fluid lazyload"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <button type="button" class="product-zoom-hint" aria-label="{{ translate('Click to zoom') }}">
                                    <i class="las la-search-plus"></i>
                                    <span>{{ translate('Click to zoom') }}</span>
                                </button>
                            </div>
                            <div class="product-thumbnail-strip mt-2">
                                <div class="aiz-carousel product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-vertical='false' data-focus-select='true' data-arrows='true'>
                                    @foreach ($photos as $key => $photo)
                                    <div class="carousel-box c-pointer border p-1 rounded">
                                        <img
                                            class="lazyload mw-100 size-50px mx-auto"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($photo) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                        >
                                    </div>
                                    @endforeach
                                    @foreach ($detailedProduct->stocks as $key => $stock)
                                        @if ($stock->image != null)
                                            <div class="carousel-box c-pointer border p-1 rounded" data-variation="{{ $stock->variant }}">
                                                <img
                                                    class="lazyload mw-100 size-50px mx-auto"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($stock->image) }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 product-information-column">
                        <div class="text-left product-information-panel">
                            <div class="product-title-row">
                                <h1 class="mb-0 product-title">
                                    {{ $detailedProduct->getTranslation('name') }}
                                </h1>
                                <button type="button" class="product-wishlist-button" onclick="addToWishList({{ $detailedProduct->id }})" aria-label="{{ translate('Add to wishlist') }}">
                                    <i class="lar la-heart"></i>
                                </button>
                            </div>

                            <div class="row align-items-center product-review-meta">
                                <div class="col-12">
                                    @php
                                        $total = 0;
                                        $total += $detailedProduct->reviews->count();
                                    @endphp
                                    <span class="rating">
                                        {{ renderStarRating($detailedProduct->rating) }}
                                    </span>
                                    <span class="ml-1 opacity-50">({{ $total }} {{ translate('reviews')}})</span>
                                </div>
                                @if ($detailedProduct->est_shipping_days)
                                <div class="col-auto ml">
                                    <small class="mr-2 opacity-50">{{ translate('Estimate Shipping Time')}}: </small>{{ $detailedProduct->est_shipping_days }} {{  translate('Days') }}
                                </div>
                                @endif
                            </div>

                            <hr>

                            <div class="row align-items-center product-seller-meta">
                                <div class="col-auto">
                                    <small class="mr-2 opacity-50">{{ translate('Sold by')}}: </small><br>
                                    @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                                        <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="text-reset">{{ $detailedProduct->user->shop->name }}</a>
                                    @else
                                        {{  translate('Inhouse product') }}
                                    @endif
                                </div>
                                @if (get_setting('conversation_system') == 1)
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-soft-primary" onclick="show_chat_modal()">{{ translate('Message Seller')}}</button>
                                    </div>
                                @endif

                                @if ($detailedProduct->brand != null)
                                    <div class="col-auto">
                                        <a href="{{ route('products.brand',$detailedProduct->brand->slug) }}">
                                            <img src="{{ uploaded_asset($detailedProduct->brand->logo) }}" alt="{{ $detailedProduct->brand->getTranslation('name') }}" height="30">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="product-price-area">
                            @if ($detailedProduct->wholesale_product)
                                <table class="aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Min Qty') }}</th>
                                            <th>{{ translate('Max Qty') }}</th>
                                            <th>{{ translate('Unit Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                                            <tr>
                                                <td>{{ $wholesalePrice->min_qty }}</td>
                                                <td>{{ $wholesalePrice->max_qty }}</td>
                                                <td>{{ single_price($wholesalePrice->price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                @if(home_price($detailedProduct) != home_discounted_price($detailedProduct))
                                    <div class="product-price-line">
                                        <strong class="product-current-price">{{ home_discounted_price($detailedProduct) }}</strong>
                                        <del class="product-previous-price">{{ home_price($detailedProduct) }}</del>
                                        <span class="product-discount-badge">
                                            {{ $detailedProduct->discount_type == 'percent' ? rtrim(rtrim(number_format($detailedProduct->discount, 2), '0'), '.') . '% ' . translate('Off') : translate('Sale') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="product-price-line">
                                        <strong class="product-current-price">{{ home_discounted_price($detailedProduct) }}</strong>
                                    </div>
                                @endif
                            @endif
                            </div>

                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="row no-gutters mt-4">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{  translate('Club Point') }}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border">
                                            <span class="strong-700">{{ $detailedProduct->earn_point }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <hr>

                            <form id="option-choice-form">
                                @csrf
                                <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

                                @if ($detailedProduct->choice_options != null)
                                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)

                                    <div class="row no-gutters product-option-row">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach ($choice->values as $key => $value)
                                                <label class="aiz-megabox pl-0 mr-2">
                                                    <input
                                                        type="radio"
                                                        name="attribute_id_{{ $choice->attribute_id }}"
                                                        value="{{ $value }}"
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2">
                                                        {{ $value }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                @endif

                                @if (count(json_decode($detailedProduct->colors)) > 0)
                                    <div class="row no-gutters product-option-row">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Color')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                                <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip" data-title="{{ \App\Models\Color::where('code', $color)->first()->name }}">
                                                    <input
                                                        type="radio"
                                                        name="color"
                                                        value="{{ \App\Models\Color::where('code', $color)->first()->name }}"
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                        <span class="size-30px d-inline-block rounded" style="background: {{ $color }};"></span>
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                @endif

                                <!-- Quantity + Add to cart -->
                                @if($detailedProduct->sizes != null && is_array(json_decode($detailedProduct->sizes, true)) && count(json_decode($detailedProduct->sizes, true)) > 0)
                                    @php $product_sizes = json_decode($detailedProduct->sizes, true); @endphp
                                    <div class="row no-gutters product-option-row">
                                        <div class="col-sm-2">
                                            <div class="opacity-50 my-2">{{ translate('Size')}}:</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="aiz-radio-inline">
                                                @foreach ($product_sizes as $psize)
                                                <label class="aiz-megabox pl-0 mr-2">
                                                    <input
                                                        type="radio"
                                                        name="size"
                                                        value="{{ $psize }}"
                                                    >
                                                    <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3 mb-2 fw-600">
                                                        {{ $psize }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif
                                @if($detailedProduct->sizeStocks->isNotEmpty() && count(json_decode($detailedProduct->colors)) > 0)
                                <div class="row no-gutters mt-3">
                                    <div class="col-sm-2">
                                        <div class="opacity-50 my-2">{{ translate('Stock by Size')}}:</div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div id="size-stock-display">
                                            @php
                                                $colors = json_decode($detailedProduct->colors);
                                                $firstColor = $colors[0] ?? null;
                                                $sizeGroups = $detailedProduct->sizeStocks->where('color', $firstColor)->groupBy('size');
                                            @endphp
                                            @foreach($sizeGroups as $size => $stocks)
                                                <span class="badge badge-inline border px-2 py-1 fs-12 fw-600 mr-1 mb-1">
                                                    {{ $size }}: {{ $stocks->sum('qty') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    (function() {
                                        var sizeStocks = @json($detailedProduct->sizeStocks->groupBy('size')->map(function($sizes) {
                                            return $sizes->groupBy('color')->map(function($colors) {
                                                return $colors->pluck('qty', 'color')->toArray();
                                            })->toArray();
                                        })->toArray());
                                        var allSizes = Object.keys(sizeStocks);
                                        var selectedColor = @json($firstColor);

                                        function updateSizeStock(color) {
                                            selectedColor = color;
                                            var html = '';
                                            allSizes.forEach(function(size) {
                                                var qty = 0;
                                                if (sizeStocks[size] && sizeStocks[size][color] !== undefined) {
                                                    qty = sizeStocks[size][color];
                                                } else if (sizeStocks[size]) {
                                                    var vals = Object.values(sizeStocks[size]);
                                                    qty = vals.length > 0 ? vals.reduce(function(a,b){return a+b},0) : 0;
                                                }
                                                html += '<span class="badge badge-inline border px-2 py-1 fs-12 fw-600 mr-1 mb-1">' + size + ': ' + qty + '</span>';
                                            });
                                            document.getElementById('size-stock-display').innerHTML = html;
                                            var totalQty = 0;
                                            allSizes.forEach(function(size) {
                                                if (sizeStocks[size] && sizeStocks[size][color] !== undefined) {
                                                    totalQty += sizeStocks[size][color];
                                                } else if (sizeStocks[size]) {
                                                    var vals = Object.values(sizeStocks[size]);
                                                    totalQty += vals.length > 0 ? vals.reduce(function(a,b){return a+b},0) : 0;
                                                }
                                            });
                                            var el = document.getElementById('available-quantity');
                                            if (el) el.textContent = totalQty;
                                        }

                                        document.querySelectorAll('input[name="color"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                updateSizeStock(this.value);
                                            });
                                        });
                                    })();
                                </script>
                                @endif

                                <div class="product-purchase-controls">
                                    <div class="product-quantity-row">
                                        <div class="row no-gutters align-items-center aiz-plus-minus product-quantity-stepper" style="width: 140px;">
                                            <button class="btn col-auto btn-icon btn-sm btn-light" type="button" data-type="minus" data-field="quantity" disabled="">
                                                <i class="las la-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                                            <button class="btn  col-auto btn-icon btn-sm btn-light" type="button" data-type="plus" data-field="quantity">
                                                <i class="las la-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="product-action-row">
                                        @if ($detailedProduct->external_link != null)
                                            <a type="button" class="btn btn-brand buy-now fw-600" href="{{ $detailedProduct->external_link }}" style="background: linear-gradient(135deg, #6a2a8f 0%, #8f3fb8 100%) !important; border-color: transparent !important; color: #ffffff !important;">
                                                <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn)}}
                                            </a>
                                        @else
                                            <button type="button" class="btn add-to-cart fw-600" onclick="addToCart()" style="background: #ffffff !important; border: 2px solid #6a2a8f !important; color: #6a2a8f !important;">
                                                <i class="las la-shopping-bag"></i>
                                                <span class="d-inline-block"> {{ translate('Add to cart')}}</span>
                                            </button>
                                            
                                            <button type="button" class="btn btn-brand buy-now fw-600" onclick="addbuynow()" style="background: linear-gradient(135deg, #6a2a8f 0%, #8f3fb8 100%) !important; border-color: transparent !important; color: #ffffff !important;">
                                                <i class="la la-shopping-cart"></i>
                                                <span class="d-inline-block"> {{ translate('Buy Now') }}</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Hidden container to satisfy JS references without ever rendering or taking space --}}
                                <div id="chosen_price_div" style="display:none !important; position:absolute !important; width:0 !important; height:0 !important; overflow:hidden !important; opacity:0 !important; pointer-events:none !important;" aria-hidden="true">
                                    <strong id="chosen_price"></strong>
                                </div>

                                <style>
                                    .product-detail-reference .product-purchase-controls {
                                        display: flex !important;
                                        flex-direction: row !important;
                                        align-items: center !important;
                                        gap: 12px !important;
                                        flex-wrap: wrap !important;
                                        margin-top: 15px !important;
                                    }
                                    .product-detail-reference .product-quantity-row {
                                        flex-shrink: 0 !important;
                                    }
                                    .product-detail-reference .product-action-row {
                                        display: grid !important;
                                        grid-template-columns: 1fr 1fr !important;
                                        gap: 10px !important;
                                        align-items: center !important;
                                        margin-top: 0 !important;
                                        flex: 1 1 260px !important;
                                    }
                                    .product-detail-reference .product-action-row .add-to-cart,
                                    .product-detail-reference .product-action-row .buy-now {
                                        min-height: 44px !important;
                                        padding: 8px 18px !important;
                                        border-radius: 6px !important;
                                        font-size: 14px !important;
                                        font-weight: 600 !important;
                                        display: inline-flex !important;
                                        align-items: center !important;
                                        justify-content: center !important;
                                        gap: 8px !important;
                                        transition: all 0.25s ease !important;
                                        text-decoration: none !important;
                                        cursor: pointer !important;
                                    }
                                    .product-detail-reference .out-of-stock,
                                    .out-of-stock {
                                        display: none !important;
                                    }
                                    .product-detail-reference .product-action-row .add-to-cart {
                                        display: inline-flex !important;
                                        background: #ffffff !important;
                                        border: 2px solid #6a2a8f !important;
                                        color: #6a2a8f !important;
                                        box-shadow: 0 2px 8px rgba(106, 42, 143, 0.15) !important;
                                    }
                                    .product-detail-reference .product-action-row .add-to-cart:hover,
                                    .product-detail-reference .product-action-row .add-to-cart:focus {
                                        background: linear-gradient(135deg, #6a2a8f 0%, #8f3fb8 100%) !important;
                                        border-color: #6a2a8f !important;
                                        color: #ffffff !important;
                                        box-shadow: 0 4px 14px rgba(106, 42, 143, 0.35) !important;
                                    }
                                    .product-detail-reference .product-action-row .buy-now {
                                        display: inline-flex !important;
                                        background: linear-gradient(135deg, #6a2a8f 0%, #8f3fb8 100%) !important;
                                        border: 1px solid rgba(255, 255, 255, 0.15) !important;
                                        color: #ffffff !important;
                                        box-shadow: 0 3px 12px rgba(106, 42, 143, 0.3) !important;
                                    }
                                    .product-detail-reference .product-action-row .buy-now:hover,
                                    .product-detail-reference .product-action-row .buy-now:focus {
                                        background: linear-gradient(135deg, #541d75 0%, #7b31a3 100%) !important;
                                        border-color: rgba(255, 255, 255, 0.25) !important;
                                        color: #ffffff !important;
                                        box-shadow: 0 6px 18px rgba(96, 32, 128, 0.45) !important;
                                        transform: translateY(-1px) !important;
                                    }
                                    .product-detail-reference #chosen_price_div,
                                    #chosen_price_div {
                                        display: none !important;
                                    }
                                    @media (max-width: 575.98px) {
                                        .product-detail-reference .product-purchase-controls {
                                            flex-direction: column !important;
                                            align-items: stretch !important;
                                            gap: 10px !important;
                                        }
                                        .product-detail-reference .product-quantity-row {
                                            width: 100% !important;
                                        }
                                        .product-detail-reference .product-quantity-row .product-quantity-stepper {
                                            width: 100% !important;
                                            max-width: 180px !important;
                                        }
                                        .product-detail-reference .product-action-row {
                                            width: 100% !important;
                                            margin-top: 4px !important;
                                        }
                                    }
                                </style>
                            </form>

                            <div class="product-service-note">
                                <div class="product-service-heading">
                                    <i class="las la-check-circle"></i>
                                    <strong>{{ translate('Easy Returns & Exchange') }}</strong>
                                    <a href="{{ route('returnpolicy') }}" target="_blank" aria-label="{{ translate('View return policy') }}"><i class="las la-angle-right"></i></a>
                                </div>
                                <div class="product-service-points">
                                    <span><i class="las la-check-circle"></i>{{ translate('Support within 7 days') }}</span>
                                    <span><i class="las la-check-circle"></i>{{ translate('Easy return policy') }}</span>
                                    <span><i class="las la-check-circle"></i>{{ translate('Secure checkout') }}</span>
                                </div>
                            </div>

                            @php
                                $measurement_img_url = null;
                                if ($detailedProduct->measurement_active == 1 && !empty($detailedProduct->measurement_img)) {
                                    if (is_numeric($detailedProduct->measurement_img)) {
                                        $measurement_img_url = uploaded_asset($detailedProduct->measurement_img);
                                    } elseif (\Illuminate\Support\Str::startsWith($detailedProduct->measurement_img, ['http://', 'https://'])) {
                                        $measurement_img_url = $detailedProduct->measurement_img;
                                    } else {
                                        $measurement_img_url = static_asset($detailedProduct->measurement_img);
                                    }
                                }
                            @endphp
                            @if($measurement_img_url)
                                <div class="product-measurement-chart my-3">
                                    <div class="border rounded p-2 bg-white text-center shadow-sm" style="border-color:#e4daf0 !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                                            <span class="font-weight-bold text-dark fs-13 text-uppercase" style="letter-spacing: 0.5px;">
                                                <i class="las la-ruler-combined text-primary mr-1 fs-15"></i> {{ translate('Size Measurement Chart') }}
                                            </span>
                                            <a href="{{ $measurement_img_url }}" target="_blank" class="text-primary fs-12 font-weight-bold" style="text-decoration:none;">
                                                <i class="las la-search-plus"></i> {{ translate('Click to enlarge') }}
                                            </a>
                                        </div>
                                        <a href="{{ $measurement_img_url }}" target="_blank" title="{{ translate('Click to view full measurement chart') }}">
                                            <img src="{{ $measurement_img_url }}" alt="{{ $detailedProduct->getTranslation('name') }} - {{ translate('Measurement Chart') }}" class="img-fluid rounded w-100" style="max-height: 480px; object-fit: contain; cursor: zoom-in; display: block; margin: 0 auto;">
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div class="product-quick-description">
                                <h2>{{ $detailedProduct->getTranslation('name') }}</h2>
                                <p>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($detailedProduct->getTranslation('description')))), 420) }}</p>
                            </div>



                            <div class="d-table width-100 mt-3">
                                <div class="d-table-cell">
                                    <!-- Add to wishlist button -->
                                    <div class="row">
                                       <button type="button" class="btn pl-3 btn-link fw-600 d-none" onclick="addToWishList({{ $detailedProduct->id }})">
                                        {{ translate('Add to wishlist')}}
                                    </button>
                                    <!--<p class="mt-3 fs-15 "><a href="tel:+4733378901">For Order : +01788246452</a></p>-->
                                    
            <!--                          <div class="mt-1">
                                  
                                        <h4 style="color:#c40e32;" class="  pl-0 btn-link fw-600 " ><span class=" d-md-inline-block "> {{ translate('For Order : +8809611790545')}}</span></h4>
                                     </div>-->
                                    
                                                                          </div>
                                    
                                     <!--<div style="padding-left:150px;">-->
                                     <!--    <h4 style="color:#c40e32;"  class="  pl-0  fw-600 " ><span class=" d-md-inline-block "> {{ translate('Cash On Delivery')}}</span></h4>-->
                                     <!--</div>-->
                                  

                                    @if(Auth::check() && addon_is_activated('affiliate_system') && (\App\Models\AffiliateOption::where('type', 'product_sharing')->first()->status || \App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->status) && Auth::user()->affiliate_user != null && Auth::user()->affiliate_user->status)
                                        @php
                                            if(Auth::check()){
                                                if(Auth::user()->referral_code == null){
                                                    Auth::user()->referral_code = substr(Auth::user()->id.Str::random(10), 0, 10);
                                                    Auth::user()->save();
                                                }
                                                $referral_code = Auth::user()->referral_code;
                                                $referral_code_url = URL::to('/product').'/'.$detailedProduct->slug."?product_referral_code=$referral_code";
                                            }
                                        @endphp
                                        <div>
                                            <button type=button id="ref-cpurl-btn" class="btn btn-sm btn-secondary" data-attrcpy="{{ translate('Copied')}}" onclick="CopyToClipboard(this)" data-url="{{$referral_code_url}}">{{ translate('Copy the Promote Link')}}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>


                            @php
                                $refund_sticker = get_setting('refund_sticker');
                            @endphp
                            @if (addon_is_activated('refund_request'))
                                <div class="row no-gutters mt-3">
                                    <div class="col-2">
                                        <div class="opacity-50 mt-2">{{ translate('Refund')}}:</div>
                                    </div>
                                    <div class="col-10">
                                        <a href="{{ route('returnpolicy') }}" target="_blank"> 
                                            @if ($refund_sticker != null) 
                                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36"> 
                                            @else 
                                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36"> 
                                            @endif</a>
                                        <a href="{{ route('returnpolicy') }}" class="ml-2" target="_blank">{{ translate('View Policy') }}</a>
                                    </div>
                                </div>
                            @endif
                            <div class="row no-gutters mt-4">
                                <div class="col-sm-2">
                                    <div class="opacity-50 my-2">{{ translate('Share')}}:</div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="aiz-share"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->seller != null)
                <div class="col-xl-3 order-1 order-xl-0">
                    @if ($detailedProduct->added_by == 'seller' && $detailedProduct->user->seller != null)
                        <div class="bg-white shadow-sm mb-3">
                            <div class="position-relative p-3 text-left">
                                @if ($detailedProduct->user->seller->verification_status)
                                    <div class="absolute-top-right p-2 bg-white z-1">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" viewBox="0 0 287.5 442.2" width="22" height="34">
                                            <polygon style="fill:#F8B517;" points="223.4,442.2 143.8,376.7 64.1,442.2 64.1,215.3 223.4,215.3 "/>
                                            <circle style="fill:#FBD303;" cx="143.8" cy="143.8" r="143.8"/>
                                            <circle style="fill:#F8B517;" cx="143.8" cy="143.8" r="93.6"/>
                                            <polygon style="fill:#FCFCFD;" points="143.8,55.9 163.4,116.6 227.5,116.6 175.6,154.3 195.6,215.3 143.8,177.7 91.9,215.3 111.9,154.3
                                            60,116.6 124.1,116.6 "/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="opacity-50 fs-12 border-bottom">{{ translate('Sold by')}}</div>
                                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="text-reset d-block fw-600">
                                    {{ $detailedProduct->user->shop->name }}
                                    @if ($detailedProduct->user->seller->verification_status == 1)
                                        <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span>
                                    @else
                                        <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span>
                                    @endif
                                </a>
                                <div class="location opacity-70">{{ $detailedProduct->user->shop->address }}</div>
                                <div class="text-center border rounded p-2 mt-3">
                                    <div class="rating">
                                        @if ($total > 0)
                                            {{ renderStarRating($detailedProduct->user->seller->rating) }}
                                        @else
                                            {{ renderStarRating(0) }}
                                        @endif
                                    </div>
                                    <div class="opacity-60 fs-12">({{ $total }} {{ translate('customer reviews')}})</div>
                                </div>
                            </div>
                            <div class="row no-gutters align-items-center border-top">
                                <div class="col">
                                    <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}" class="d-block btn btn-soft-primary rounded-0">{{ translate('Visit Store')}}</a>
                                </div>
                                <div class="col">
                                    <ul class="social list-inline mb-0">
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->facebook }}" class="facebook" target="_blank">
                                                <i class="lab la-facebook-f opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->google }}" class="google" target="_blank">
                                                <i class="lab la-google opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item mr-0">
                                            <a href="{{ $detailedProduct->user->shop->twitter }}" class="twitter" target="_blank">
                                                <i class="lab la-twitter opacity-60"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="{{ $detailedProduct->user->shop->youtube }}" class="youtube" target="_blank">
                                                <i class="lab la-youtube opacity-60"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-xl-9 order-0 order-xl-1">
                @else
                <div class="col-xl-12">
                @endif
                    <div class="bg-white mb-3 shadow-sm rounded">
                        <div class="nav border-bottom aiz-nav-tabs">
                            <a href="#tab_default_1" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset active show"><i class="las la-align-left mr-1"></i>{{ translate('Description')}}</a>
                            @if($detailedProduct->video_link != null)
                                <a href="#tab_default_2" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset"><i class="las la-play-circle mr-1"></i>{{ translate('Video')}}</a>
                            @endif
                            @if($detailedProduct->pdf != null)
                                <a href="#tab_default_3" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset"><i class="las la-download mr-1"></i>{{ translate('Downloads')}}</a>
                            @endif
                                <a href="#tab_default_4" data-toggle="tab" class="p-3 fs-16 fw-600 text-reset"><i class="las la-comments mr-1"></i>{{ translate('Reviews')}}</a>
                        </div>

                        <div class="tab-content pt-0">
                            <div class="tab-pane fade active show" id="tab_default_1">
                                <div class="p-4">
                                    <div class="mw-100 overflow-hidden text-left aiz-editor-data">
                                        <?php echo $detailedProduct->getTranslation('description'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab_default_2">
                                <div class="p-4">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $detailedProduct->video_link)[1] }}"></iframe>
                                        @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                                            <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                                        @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                                            <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_3">
                                <div class="p-4 text-center ">
                                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}" class="btn btn-primary">{{  translate('Download') }}</a>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_default_4">
                                <div class="p-4">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($detailedProduct->reviews as $key => $review)
                                            @if($review->user != null)
                                            <li class="media list-group-item d-flex">
                                                <span class="avatar avatar-md mr-3">
                                                    <img
                                                        class="lazyload"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                        @if($review->user->avatar_original !=null)
                                                            data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                                                        @else
                                                            data-src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        @endif
                                                    >
                                                </span>
                                                <div class="media-body text-left">
                                                    <div class="d-flex justify-content-between">
                                                        <h3 class="fs-15 fw-600 mb-0">{{ $review->user->name }}</h3>
                                                        <span class="rating rating-sm">
                                                            @for ($i=0; $i < $review->rating; $i++)
                                                                <i class="las la-star active"></i>
                                                            @endfor
                                                            @for ($i=0; $i < 5-$review->rating; $i++)
                                                                <i class="las la-star"></i>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    <div class="opacity-60 mb-2">{{ date('d-m-Y', strtotime($review->created_at)) }}</div>
                                                    <p class="comment-text">
                                                        {{ $review->comment }}
                                                    </p>
                                                </div>
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    @if(count($detailedProduct->reviews) <= 0)
                                        <div class="text-center fs-18 opacity-70">
                                            {{  translate('There have been no reviews for this product yet.') }}
                                        </div>
                                    @endif

                                    @if(Auth::check())
                                        @php
                                            $commentable = false;
                                        @endphp
                                        @foreach ($detailedProduct->orderDetails as $key => $orderDetail)
                                            @if($orderDetail->order != null && $orderDetail->order->user_id == Auth::user()->id && $orderDetail->delivery_status == 'delivered' && \App\Models\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null)
                                                @php
                                                    $commentable = true;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($commentable)
                                            <div class="pt-4">
                                                <div class="border-bottom mb-4">
                                                    <h3 class="fs-17 fw-600">
                                                        {{ translate('Write a review')}}
                                                    </h3>
                                                </div>
                                                <form class="form-default" role="form" action="{{ route('reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" class="text-uppercase c-gray-light">{{ translate('Your name')}}</label>
                                                                <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" disabled required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="" class="text-uppercase c-gray-light">{{ translate('Email')}}</label>
                                                                <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control" required disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="opacity-60">{{ translate('Rating')}}</label>
                                                        <div class="rating rating-input">
                                                            <label>
                                                                <input type="radio" name="rating" value="1" required>
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="2">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="3">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="4">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                            <label>
                                                                <input type="radio" name="rating" value="5">
                                                                <i class="las la-star"></i>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="opacity-60">{{ translate('Comment')}}</label>
                                                        <textarea class="form-control" rows="4" name="comment" placeholder="{{ translate('Your review')}}" required></textarea>
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-3">
                                                            {{ translate('Submit review')}}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="bg-white rounded shadow-sm">
                        <div class="border-bottom p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                <span class="mr-4">{{ translate('Related products')}}</span>
                            </h3>
                        </div>
                        <div class="p-3">
                            <div class="aiz-carousel gutters-5" data-items="5" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='true'>
                                @foreach (filter_products(\App\Models\Product::where('category_id', $detailedProduct->category_id)->where('id', '!=', $detailedProduct->id))->limit(10)->get() as $key => $related_product)
                                <div class="carousel-box">
                                    <div class="aiz-card-box border border-light rounded hov-shadow-md my-2 has-transition">
                                        <div class="">
                                            <a href="{{ route('product', $related_product->slug) }}" class="d-block">
                                                <img
                                                    class="img-fit lazyload mx-auto h-140px h-md-210px"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset($related_product->thumbnail_img) }}"
                                                    alt="{{ $related_product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                            </a>
                                        </div>
                                        <div class="p-md-3 p-2 text-left">
                                            <div class="fs-15">
                                                @if(home_base_price($related_product) != home_discounted_base_price($related_product))
                                                    <del class="fw-600 opacity-50 mr-1">{{ home_base_price($related_product) }}</del>
                                                @endif
                                                <span class="fw-700 text-primary">{{ home_discounted_base_price($related_product) }}</span>
                                            </div>
                                            <div class="rating rating-sm mt-1">
                                                {{ renderStarRating($related_product->rating) }}
                                            </div>
                                            <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px">
                                                <a href="{{ route('product', $related_product->slug) }}" class="d-block text-reset">{{ $related_product->getTranslation('name') }}</a>
                                            </h3>
                                            @if (addon_is_activated('club_point'))
                                                <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                                                    {{ translate('Club Point') }}:
                                                    <span class="fw-700 float-right">{{ $related_product->earn_point }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm mt-3">
                <div class="border-bottom p-3">
                    <h3 class="fs-16 fw-600 mb-0">
                        <span class="mr-4">{{ translate('Top Selling Products')}}</span>
                    </h3>
                </div>
                <div class="p-3">
                    <div class="row gutters-5 row-cols-xxl-5 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2">
                        @foreach (filter_products(\App\Models\Product::where('user_id', $detailedProduct->user_id)->orderBy('num_of_sale', 'desc'))->limit(10)->get() as $top_product)
                        <div class="col">
                            @include('frontend.partials.product_box_1', ['product' => $top_product])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg" placeholder="{{ translate('Password')}}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{  translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{  translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a>
                        </div>
                        @if(get_setting('google_login') == 1 ||
                            get_setting('facebook_login') == 1 ||
                            get_setting('twitter_login') == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (get_setting('facebook_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if(get_setting('google_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen product image zoom viewer -->
    <div class="product-zoom-viewer" id="productZoomViewer" role="dialog" aria-modal="true" aria-label="{{ translate('Product image zoom') }}">
        <div class="product-zoom-stage" id="productZoomStage"></div>
        <div class="product-zoom-toolbar">
            <span class="product-zoom-count" id="productZoomCount">1 / 1</span>
            <button type="button" class="product-zoom-btn" id="productZoomOutBtn" aria-label="{{ translate('Zoom out') }}"><i class="las la-minus"></i></button>
            <button type="button" class="product-zoom-btn" id="productZoomInBtn" aria-label="{{ translate('Zoom in') }}"><i class="las la-plus"></i></button>
            <button type="button" class="product-zoom-btn product-zoom-close" id="productZoomCloseBtn" aria-label="{{ translate('Close') }}"><i class="las la-times"></i></button>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
    	});

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }
        function show_chat_modal(){
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

    </script>
    <script>
        (function() {
            var viewer = document.getElementById('productZoomViewer');
            if (!viewer) return;

            var stage = document.getElementById('productZoomStage');
            var gallery = document.querySelector('.product-gallery');
            if (!gallery) return;

            var slides = Array.prototype.slice.call(gallery.querySelectorAll('.carousel-box img'));
            var images = slides.map(function(img) {
                return img.getAttribute('data-src') || img.currentSrc || img.src;
            }).filter(Boolean);
            if (images.length === 0) return;

            var current = 0;
            var zoomLevel = 1;
            var MIN = 1, MAX = 4;
            var imgEl, dragging = false, moved = false;
            var startX = 0, startY = 0, tx = 0, ty = 0, baseTx = 0, baseTy = 0;

            var countEl = document.getElementById('productZoomCount');
            var inBtn = document.getElementById('productZoomInBtn');
            var outBtn = document.getElementById('productZoomOutBtn');
            var closeBtn = document.getElementById('productZoomCloseBtn');

            function open(index) {
                current = index;
                zoomLevel = 1;
                tx = 0; ty = 0;
                stage.innerHTML = '';
                imgEl = document.createElement('img');
                imgEl.src = images[current];
                imgEl.className = 'product-zoom-image';
                imgEl.alt = 'product image';
                stage.appendChild(imgEl);
                stage.appendChild(nav);
                stage.classList.add('open');
                viewer.classList.add('open');
                document.body.style.overflow = 'hidden';
                applyTransform();
            }

            function close() {
                viewer.classList.remove('open');
                stage.classList.remove('open');
                document.body.style.overflow = '';
            }

            function applyTransform() {
                if (!imgEl) return;
                if (zoomLevel <= 1) { tx = 0; ty = 0; }
                imgEl.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(' + zoomLevel + ')';
                countEl.textContent = (current + 1) + ' / ' + images.length;
            }

            function setZoom(level) {
                zoomLevel = Math.min(MAX, Math.max(MIN, level));
                tx = 0; ty = 0;
                applyTransform();
            }

            function clampPan() {
                if (!imgEl) return;
                var r = imgEl.getBoundingClientRect();
                var sx = Math.max(0, (r.width - viewer.clientWidth) / 2);
                var sy = Math.max(0, (r.height - viewer.clientHeight) / 2);
                tx = Math.min(sx, Math.max(-sx, tx));
                ty = Math.min(sy, Math.max(-sy, ty));
                applyTransform();
            }

            gallery.addEventListener('click', function(e) {
                if (slides.length === 0) return;
                if (e.target.closest('button')) return;
                var idx = slides.indexOf(e.target);
                open(idx === -1 ? current : idx);
            });

            document.querySelector('.product-zoom-hint').addEventListener('click', function() {
                var activeSlide = gallery.querySelector('.slick-current img');
                var activeIndex = slides.indexOf(activeSlide);
                open(activeIndex === -1 ? current : activeIndex);
            });

            inBtn.addEventListener('click', function() { setZoom(zoomLevel + 0.6); });
            outBtn.addEventListener('click', function() { setZoom(zoomLevel - 0.6); });
            closeBtn.addEventListener('click', close);

            viewer.addEventListener('click', function(e) {
                if (e.target === viewer || e.target === stage) close();
            });

            viewer.addEventListener('dblclick', function() {
                setZoom(zoomLevel > 1 ? 1 : 2.5);
            });

            viewer.addEventListener('wheel', function(e) {
                e.preventDefault();
                setZoom(zoomLevel + (e.deltaY < 0 ? 0.3 : -0.3));
            }, { passive: false });

            var nav = document.createElement('div');
            nav.className = 'product-zoom-nav';
            stage.appendChild(nav);
            var prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'product-zoom-nav-btn product-zoom-prev';
            prevBtn.setAttribute('data-dir', 'left');
            prevBtn.innerHTML = '<i class="las la-angle-left"></i>';
            prevBtn.setAttribute('aria-label', '{{ translate("Previous") }}');
            var nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'product-zoom-nav-btn product-zoom-next';
            nextBtn.setAttribute('data-dir', 'right');
            nextBtn.innerHTML = '<i class="las la-angle-right"></i>';
            nextBtn.setAttribute('aria-label', '{{ translate("Next") }}');
            nav.appendChild(prevBtn);
            nav.appendChild(nextBtn);
            nav.addEventListener('click', function(e) {
                var btn = e.target.closest('button');
                if (!btn) return;
                if (btn.dataset.dir === 'left') {
                    current = (current - 1 + images.length) % images.length;
                } else {
                    current = (current + 1) % images.length;
                }
                tx = 0; ty = 0; zoomLevel = 1;
                imgEl.src = images[current];
                applyTransform();
                e.stopPropagation();
            });

            viewer.addEventListener('mousedown', function(e) {
                if (e.target !== imgEl) return;
                dragging = true; moved = false;
                startX = e.clientX; startY = e.clientY;
                baseTx = tx; baseTy = ty;
                try { e.preventDefault(); } catch (err) {}
                e.stopPropagation();
            });
            window.addEventListener('mousemove', function(e) {
                if (!dragging) return;
                var dx = e.clientX - startX, dy = e.clientY - startY;
                if (Math.abs(dx) + Math.abs(dy) > 4) moved = true;
                tx = baseTx + dx; ty = baseTy + dy;
                clampPan();
            });
            window.addEventListener('mouseup', function() { dragging = false; });

            document.addEventListener('keydown', function(e) {
                if (!viewer.classList.contains('open')) return;
                if (e.key === 'Escape') close();
                if (e.key === '+' || e.key === '=') setZoom(zoomLevel + 0.6);
                if (e.key === '-' || e.key === '_') setZoom(zoomLevel - 0.6);
                if (e.key === 'ArrowLeft') { current = (current - 1 + images.length) % images.length; tx = 0; ty = 0; zoomLevel = 1; imgEl.src = images[current]; applyTransform(); }
                if (e.key === 'ArrowRight') { current = (current + 1) % images.length; tx = 0; ty = 0; zoomLevel = 1; imgEl.src = images[current]; applyTransform(); }
            });

            var touch = null;
            var touchZoom = 1;
            stage.addEventListener('touchstart', function(e) {
                if (e.touches.length === 1) {
                    touch = { x: e.touches[0].clientX, y: e.touches[0].clientY, mode: 'pan' };
                    baseTx = tx; baseTy = ty;
                } else if (e.touches.length === 2) {
                    touch = { mode: 'zoom', dist: Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY) };
                    touchZoom = zoomLevel;
                }
            }, { passive: true });
            stage.addEventListener('touchmove', function(e) {
                if (!touch) return;
                e.preventDefault();
                if (touch.mode === 'pan' && e.touches.length === 1) {
                    tx = baseTx + (e.touches[0].clientX - touch.x);
                    ty = baseTy + (e.touches[0].clientY - touch.y);
                    clampPan();
                } else if (touch.mode === 'zoom' && e.touches.length === 2) {
                    var d = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
                    setZoom(touchZoom * (d / touch.dist));
                }
            }, { passive: false });
            stage.addEventListener('touchend', function() { touch = null; });

            document.querySelector('.product-zoom-hint').style.display = 'flex';
        })();
    </script>
@endsection
