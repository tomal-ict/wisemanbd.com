<div class="aiz-card-box product-card-reference border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white d-flex flex-column h-100">
    <div class="position-relative product-card-image">
        <a href="{{ route('product', $product->slug) }}" class="d-block h-100">
            <img
                class="img-fit lazyload product-card-image-img"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                alt="{{  $product->getTranslation('name')  }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
            >
        </a>
        @if ($product->wholesale_product)
            <span class="absolute-bottom-left fs-11 text-white fw-600 px-2 lh-1-8" style="background-color: #455a64">
                {{ translate('Wholesale') }}
            </span>
        @endif
    </div>
    <div class="product-card-info flex-grow-1">
        <h3 class="product-card-name mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset" title="{{ $product->getTranslation('name') }}">{{ $product->getTranslation('name') }}</a>
        </h3>
        <div class="product-card-price">
            <span class="product-current-price">{{ home_discounted_base_price($product) }}</span>
            @if(home_base_price($product) != home_discounted_base_price($product))
                <del class="product-old-price">{{ home_base_price($product) }}</del>
            @endif
            @if(discount_in_percentage($product) > 0)
                <span class="product-discount-label">{{ discount_in_percentage($product) }}% {{ translate('OFF') }}</span>
            @endif
        </div>
        @if (addon_is_activated('club_point'))
            <div class="product-card-club-point">
                {{ translate('Club Point') }}:
                <span>{{ $product->earn_point }}</span>
            </div>
        @endif
        @if($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0)
            <div class="mt-1 mb-1">
                @foreach (json_decode($product->sizes, true) as $psize)
                    <span class="badge badge-inline border px-2 py-1 fs-11 fw-600 mr-1">{{ $psize }}</span>
                @endforeach
            </div>
        @endif
    </div>
    <div class="product-card-footer">
        @php
            $pb1_has_options = (count(json_decode($product->colors)) > 0)
                || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
        @endphp
        <a href="javascript:void(0)" class="product-card-cart-button" onclick="addToCartDirect({{ $product->id }}, {{ $pb1_has_options ? 'true' : 'false' }})" data-toggle="tooltip" data-title="{{ translate('Add to cart') }}" aria-label="{{ translate('Add to cart') }}">
            <i class="las la-shopping-cart"></i>
        </a>
        <a href="javascript:void(0)" class="product-card-buy-now-button" onclick="buyNowDirect({{ $product->id }}, {{ $pb1_has_options ? 'true' : 'false' }})">
            <span>{{ translate('Buy Now') }}</span>
            <i class="las la-arrow-right"></i>
        </a>
    </div>
</div>
