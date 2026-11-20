@php
    $variant = isset($variant) ? $variant : 'logged';
    $total = 0;
@endphp
<div class="shadow-sm bg-white p-3 p-lg-4 rounded text-left" id="cart-box">
    <div class="mb-4">
        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
            <div class="col-md-5 fw-600">{{ translate('Product')}}</div>
            <div class="col fw-600">{{ translate('Price')}}</div>
            @if($variant == 'logged')
            <div class="col fw-600">{{ translate('Tax')}}</div>
            @endif
            <div class="col fw-600">{{ translate('Quantity')}}</div>
            <div class="col fw-600">{{ translate('Total')}}</div>
            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
        </div>
        <ul class="list-group list-group-flush">
            @foreach ($carts as $key => $cartItem)
                @php
                    $product = \App\Models\Product::find($cartItem['product_id']);
                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                    $total = $total + ($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity'];
                    $product_name_with_choice = $product->getTranslation('name');
                    if ($cartItem['variation'] != null) {
                        $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variation'];
                    }
                    if (($cartItem['size'] ?? null) != null && $cartItem['size'] != '') {
                        $product_name_with_choice .= ' - '.$cartItem['size'];
                    }
                @endphp
                <li class="list-group-item px-0 px-lg-3">
                    <div class="row gutters-5">
                        <div class="col-lg-5 d-flex">
                            <span class="mr-2 ml-0">
                                <img
                                    src="{{ uploaded_asset($product->thumbnail_img) }}"
                                    class="img-fit size-60px rounded"
                                    alt="{{ $product->getTranslation('name')  }}"
                                >
                            </span>
                            <span class="fs-14 opacity-60">{{ $product_name_with_choice }}</span>
                        </div>

                        <div class="col-lg col-4 order-1 order-lg-0 my-3 my-lg-0">
                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Price')}}</span>
                            <span class="fw-600 fs-16">{{ single_price($cartItem['price']) }}</span>
                        </div>
                        @if($variant == 'logged')
                        <div class="col-lg col-4 order-2 order-lg-0 my-3 my-lg-0">
                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Tax')}}</span>
                            <span class="fw-600 fs-16">{{ single_price($cartItem['tax']) }}</span>
                        </div>
                        @endif

                        <div class="col-lg col-6 order-4 order-lg-0">
                            @if($cartItem['digital'] != 1 && $product->auction_product == 0)
                                <div class="row no-gutters align-items-center aiz-plus-minus mr-2 ml-0">
                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="minus" data-field="quantity[{{ $cartItem['id'] }}]">
                                        <i class="las la-minus"></i>
                                    </button>
                                    <input type="number" name="quantity[{{ $cartItem['id'] }}]" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}" max="{{ $product_stock->qty }}" onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                    <button class="btn col-auto btn-icon btn-sm btn-circle btn-light" type="button" data-type="plus" data-field="quantity[{{ $cartItem['id'] }}]">
                                        <i class="las la-plus"></i>
                                    </button>
                                </div>
                            @elseif($product->auction_product == 1)
                                <span class="fw-600 fs-16">1</span>
                            @endif
                        </div>
                        <div class="col-lg col-4 order-3 order-lg-0 my-3 my-lg-0">
                            <span class="opacity-60 fs-12 d-block d-lg-none">{{ translate('Total')}}</span>
                            <span class="fw-600 fs-16 text-primary">{{ single_price(($cartItem['price'] + $cartItem['tax']) * $cartItem['quantity']) }}</span>
                        </div>
                        <div class="col-lg-auto col-6 order-5 order-lg-0 text-right">
                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" class="btn btn-icon btn-sm btn-soft-primary btn-circle">
                                <i class="las la-trash"></i>
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="px-3 py-2 mb-4 border-top">
        @include('frontend.partials.cart_coupon_box', ['carts' => $carts, 'prefix' => 'cart'])
    </div>

    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
            <a href="{{ route('home') }}" class="btn btn-link">
                <i class="las la-arrow-left"></i>
                {{ translate('Return to shop')}}
            </a>
        </div>
        @if($variant == 'logged')
            <div class="col-md-6 text-center text-md-right">
                <a href="{{ route('checkout.shipping_info') }}" class="btn btn-primary fw-600">
                    {{ translate('Continue to Shipping')}}
                </a>
            </div>
        @else
            <div class="col-md-6 text-center text-md-right">
                <input class="btn btn-primary fw-600" type="submit" value="{{ translate('Order Confirm') }}">
            </div>
        @endif
    </div>
</div>