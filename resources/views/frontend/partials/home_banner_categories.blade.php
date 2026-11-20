@php
    if (!isset($categories)) {
        $categories = [];
    }
@endphp
@if (count($categories) > 0)
    <div class="container">
        <div class="row gutters-10">
            @foreach ($categories as $category)
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
@endif