<?php
    $prefix = isset($prefix) ? $prefix : 'cv';
    $show_totals = isset($show_totals) ? $show_totals : ($prefix != 'guest');
    $force_english = isset($force_english) ? $force_english : ($prefix == 'guest');
    $lbl = function ($key) use ($force_english) {
        return $force_english ? $key : translate($key);
    };
    $applied_cart = $carts->where('coupon_applied', 1)->first();
    $subtotal = 0;
    $tax = 0;
    foreach ($carts as $cartItem) {
        $subtotal += $cartItem['price'] * $cartItem['quantity'];
        $tax += $cartItem['tax'] * $cartItem['quantity'];
    }
    $discount_total = $carts->sum('discount');
    $grand_total = $subtotal + $tax - $discount_total;
    $coupon_percent = null;
    $coupon_amount_label = null;
    if ($applied_cart != null) {
        $applied_coupon = \App\Models\Coupon::where('code', $applied_cart['coupon_code'])->first();
        if ($applied_coupon != null) {
            if ($applied_coupon->discount_type == 'percent') {
                $coupon_percent = $applied_coupon->discount;
            } else {
                $coupon_amount_label = $applied_coupon->discount;
            }
        }
    }
?>
<div class="coupon-box rounded border p-3 mb-3" id="coupon_box_<?php echo e($prefix); ?>" data-discount="<?php echo e($discount_total); ?>">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="fw-700 fs-14 opacity-70 text-uppercase">
            <i class="las la-ticket-alt text-success mr-1"></i>
            <?php echo e($lbl('Coupon Code')); ?>

        </span>
        <?php if($applied_cart != null): ?>
            <span class="fw-600 fs-12 text-success">
                <i class="las la-check-circle"></i>
                <?php echo e($lbl('Applied')); ?>

            </span>
        <?php endif; ?>
    </div>

    <?php if($applied_cart != null): ?>
        <div class="" id="remove-coupon-form-<?php echo e($prefix); ?>">
            <input type="hidden" name="owner_id" value="<?php echo e($applied_cart['owner_id']); ?>">
            <div class="d-flex align-items-center">
                <span class="fw-700 fs-15 text-success mr-2 rounded-3 px-3 py-2" style="background-color: rgba(33,185,120,0.12);">
                    <i class="las la-tag"></i>
                    <?php echo e($applied_cart['coupon_code']); ?>

                    <?php if($coupon_percent != null): ?>
                        <span>-<?php echo e($coupon_percent); ?>%</span>
                    <?php elseif($coupon_amount_label != null): ?>
                        <span>-<?php echo e(single_price($coupon_amount_label)); ?></span>
                    <?php endif; ?>
                </span>
                <button type="button" id="<?php echo e($prefix); ?>-coupon-remove" class="btn btn-sm btn-soft-danger ml-auto">
                    <i class="las la-times"></i> <?php echo e($lbl('Remove')); ?>

                </button>
            </div>
            <div class="fs-13 text-success fw-600 mt-2">
                <i class="las la-hand-holding-heart"></i>
                <?php echo e($lbl('You saved')); ?> <?php echo e(single_price($discount_total)); ?>

            </div>
        </div>
    <?php else: ?>
        <label class="aiz-checkbox d-block mb-2">
            <input type="checkbox" name="has_coupon_<?php echo e($prefix); ?>" id="has-coupon-<?php echo e($prefix); ?>">
            <span class="opacity-60"><?php echo e($lbl('I have a coupon code')); ?></span>
            <span class="aiz-square-check"></span>
        </label>
        <div class="d-none" id="apply-coupon-form-<?php echo e($prefix); ?>">
            <?php if($carts->count() > 0): ?>
                <input type="hidden" name="owner_id" value="<?php echo e($carts[0]['owner_id']); ?>">
            <?php endif; ?>
            <div class="input-group">
                <input type="text" class="form-control text-success fw-600" name="code" id="coupon-input-<?php echo e($prefix); ?>" placeholder="<?php echo e($lbl('Enter your coupon code')); ?>"
                    onkeydown="if(event.key == 'Enter'){ event.preventDefault(); document.getElementById('<?php echo e($prefix); ?>-coupon-apply').click(); }">
                <div class="input-group-append">
                    <button type="button" id="<?php echo e($prefix); ?>-coupon-apply" class="btn btn-primary px-4">
                        <i class="las la-check-circle"></i> <?php echo e($lbl('Check')); ?>

                    </button>
                </div>
            </div>
            <div class="fs-12 opacity-50 mt-1"><?php echo e($lbl('You can place the order without a coupon code.')); ?></div>
        </div>
    <?php endif; ?>

    <?php if($show_totals): ?>
    <div class="mt-3 pt-3 border-top">
        <div class="d-flex justify-content-between fs-15 mb-1">
            <span class="opacity-60"><?php echo e($lbl('Subtotal')); ?></span>
            <span class="fw-600"><?php echo e(single_price($subtotal)); ?></span>
        </div>
        <?php if($tax > 0): ?>
            <div class="d-flex justify-content-between fs-15 mb-1">
                <span class="opacity-60"><?php echo e($lbl('Tax')); ?></span>
                <span class="fw-600"><?php echo e(single_price($tax)); ?></span>
            </div>
        <?php endif; ?>
        <?php if($discount_total > 0): ?>
            <div class="d-flex justify-content-between fs-15 mb-1 text-success font-weight-bold">
                <span>
                    <i class="las la-ticket-alt"></i>
                    <?php echo e($lbl('Coupon Discount')); ?>

                </span>
                <span class="fw-700">
                    -<?php echo e(single_price($discount_total)); ?>

                    <?php if($coupon_percent != null): ?>
                        (<?php echo e($coupon_percent); ?>%)
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>
        <div class="d-flex justify-content-between fs-17 border-top pt-2 mt-2">
            <span class="fw-700"><?php echo e($lbl('Total')); ?></span>
            <span class="fw-700 text-primary fs-20" id="<?php echo e($prefix); ?>-grand-total"><?php echo e(single_price($grand_total)); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
        (function () {
            var toggle = document.getElementById('has-coupon-<?php echo e($prefix); ?>');
            var box = document.getElementById('apply-coupon-form-<?php echo e($prefix); ?>');
            if (!toggle || !box) return;
            function sync() {
                if (toggle.checked) {
                    box.classList.remove('d-none');
                    var input = document.getElementById('coupon-input-<?php echo e($prefix); ?>');
                    if (input) input.focus();
                } else {
                    box.classList.add('d-none');
                }
            }
            toggle.addEventListener('change', sync);
            sync();
        })();
    </script>
</div><?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/partials/cart_coupon_box.blade.php ENDPATH**/ ?>