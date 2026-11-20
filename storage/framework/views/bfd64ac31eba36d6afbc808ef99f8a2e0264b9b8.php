<?php
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
?>

<?php if(auth()->check()): ?>
    <a href="<?php echo e(route('checkout.shipping_info')); ?>" class="floating-cart-widget" title="<?php echo e(translate('Proceed to Checkout')); ?>" aria-label="<?php echo e(translate('Proceed to Checkout')); ?>">
        <span class="floating-cart-main">
            <i class="las la-shopping-bag"></i>
            <span class="floating-cart-items"><span class="floating-cart-count cart-count"><?php echo e($floating_cart_count); ?></span> <?php echo e(translate('Items')); ?></span>
        </span>
        <span class="floating-cart-total"><?php echo e(single_price($floating_cart_total)); ?></span>
    </a>
<?php else: ?>
    <a href="#" onclick="guest_card(); return false;" class="floating-cart-widget" title="<?php echo e(translate('Proceed to Checkout')); ?>" aria-label="<?php echo e(translate('Proceed to Checkout')); ?>">
        <span class="floating-cart-main">
            <i class="las la-shopping-bag"></i>
            <span class="floating-cart-items"><span class="floating-cart-count cart-count"><?php echo e($floating_cart_count); ?></span> <?php echo e(translate('Items')); ?></span>
        </span>
        <span class="floating-cart-total"><?php echo e(single_price($floating_cart_total)); ?></span>
    </a>
<?php endif; ?>
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/inc/floating_cart.blade.php ENDPATH**/ ?>