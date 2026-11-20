@php
    $floating_carts = collect();

    if (auth()->check()) {
        $floating_carts = \App\Models\Cart::where('user_id', Auth::id())->get();
    } else {
        $temp_user_id = Session()->get('temp_user_id');
        if ($temp_user_id) {
            $floating_carts = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
        }
    }

    $floating_cart_count = $floating_carts->count();
    $floating_cart_total = $floating_carts->sum(function ($cart) {
        return $cart->price * $cart->quantity;
    });
@endphp

@if (auth()->check())
    <a href="{{ route('checkout.shipping_info') }}" class="floating-cart-widget" title="{{ translate('Proceed to Checkout') }}" aria-label="{{ translate('Proceed to Checkout') }}">
        <span class="floating-cart-main">
            <i class="las la-shopping-bag"></i>
            <span class="floating-cart-items"><span class="floating-cart-count cart-count">{{ $floating_cart_count }}</span> {{ translate('Items') }}</span>
        </span>
        <span class="floating-cart-total">{{ single_price($floating_cart_total) }}</span>
    </a>
@else
    <a href="#" onclick="guest_card(); return false;" class="floating-cart-widget" title="{{ translate('Proceed to Checkout') }}" aria-label="{{ translate('Proceed to Checkout') }}">
        <span class="floating-cart-main">
            <i class="las la-shopping-bag"></i>
            <span class="floating-cart-items"><span class="floating-cart-count cart-count">{{ $floating_cart_count }}</span> {{ translate('Items') }}</span>
        </span>
        <span class="floating-cart-total">{{ single_price($floating_cart_total) }}</span>
    </a>
@endif
