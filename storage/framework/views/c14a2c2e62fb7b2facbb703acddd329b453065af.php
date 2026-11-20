<section class="bg-dark py-5 text-light footer-widget">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-xl-4 text-center text-md-left">
                <div class="mt-4">
                    <a href="<?php echo e(route('home')); ?>" class="d-block">
                        <?php if(get_setting('footer_logo') != null): ?>
                            <img class="lazyload" src="<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>" data-src="<?php echo e(uploaded_asset(get_setting('footer_logo'))); ?>" alt="<?php echo e(env('APP_NAME')); ?>" height="44">
                        <?php else: ?>
                            <img class="lazyload" src="<?php echo e(static_asset('assets/img/placeholder-rect.jpg')); ?>" data-src="<?php echo e(static_asset('assets/img/logo.png')); ?>" alt="<?php echo e(env('APP_NAME')); ?>" height="44">
                        <?php endif; ?>
                    </a>
                    <div class="my-3">
                        <?php echo get_setting('about_us_description',null,App::getLocale()); ?>

                    </div>
                    <div class="d-inline-block d-md-block mb-4">
                        <form class="form-inline" method="POST" action="<?php echo e(route('subscribers.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group mb-0">
                                <input type="email" class="form-control" placeholder="<?php echo e(translate('Your Email Address')); ?>" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <?php echo e(translate('Subscribe')); ?>

                            </button>
                        </form>
                    </div>
                    <div class="w-300px mw-100 mx-auto mx-md-0">
                        <?php if(get_setting('show_play_store', 'on') == 'on' && get_setting('play_store_link') != null): ?>
                            <a href="<?php echo e(get_setting('play_store_link')); ?>" target="_blank" class="d-inline-block mr-3 ml-0">
                                <img src="<?php echo e(static_asset('assets/img/play.png')); ?>" class="mx-100 h-40px">
                            </a>
                        <?php endif; ?>
                        <?php if(get_setting('show_app_store', 'on') == 'on' && get_setting('app_store_link') != null): ?>
                            <a href="<?php echo e(get_setting('app_store_link')); ?>" target="_blank" class="d-inline-block">
                                <img src="<?php echo e(static_asset('assets/img/app.png')); ?>" class="mx-100 h-40px">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 ml-xl-auto col-md-4 mr-0">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                        <?php echo e(translate('Contact Info')); ?>

                    </h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                           <span class="d-block opacity-30"><?php echo e(translate('Address')); ?>:</span>
                           <span class="d-block opacity-70"><?php echo e(get_setting('contact_address',null,App::getLocale())); ?></span>
                        </li>
                        <li class="mb-2">
                           <span class="d-block opacity-30"><?php echo e(translate('Phone')); ?>:</span>
                           <span class="d-block opacity-70"><?php echo e(get_setting('contact_phone')); ?></span>
                        </li>
                        <li class="mb-2">
                           <span class="d-block opacity-30"><?php echo e(translate('Email')); ?>:</span>
                           <span class="d-block opacity-70">
                               <a href="mailto:<?php echo e(get_setting('contact_email')); ?>" class="text-reset"><?php echo e(get_setting('contact_email')); ?></a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                        <?php echo e(get_setting('widget_one',null,App::getLocale())); ?>

                    </h4>
                    <ul class="list-unstyled">
                        <?php if( get_setting('widget_one_labels',null,App::getLocale()) !=  null ): ?>
                            <?php $__currentLoopData = json_decode( get_setting('widget_one_labels',null,App::getLocale()), true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <a href="<?php echo e(json_decode( get_setting('widget_one_links'), true)[$key]); ?>" class="opacity-50 hov-opacity-100 text-reset">
                                    <?php echo e($value); ?>

                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="text-center text-md-left mt-4">
                    <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                        <?php echo e(translate('My Account')); ?>

                    </h4>
                    <ul class="list-unstyled">
                        <?php if(Auth::check()): ?>
                            <li class="mb-2">
                                <a class="opacity-50 hov-opacity-100 text-reset" href="<?php echo e(route('logout')); ?>">
                                    <?php echo e(translate('Logout')); ?>

                                </a>
                            </li>
                        <?php else: ?>
                            <?php if(get_setting('show_my_panel') == 'on'): ?>
                            <li class="mb-2">
                                <a class="opacity-50 hov-opacity-100 text-reset" href="<?php echo e(route('user.login')); ?>">
                                    <?php echo e(translate('Login')); ?>

                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <li class="mb-2">
                            <a class="opacity-50 hov-opacity-100 text-reset" href="<?php echo e(route('purchase_history.index')); ?>">
                                <?php echo e(translate('Order History')); ?>

                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="opacity-50 hov-opacity-100 text-reset" href="<?php echo e(route('wishlists.index')); ?>">
                                <?php echo e(translate('My Wishlist')); ?>

                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="opacity-50 hov-opacity-100 text-reset" href="<?php echo e(route('orders.track')); ?>">
                                <?php echo e(translate('Track Order')); ?>

                            </a>
                        </li>
                        <?php if(addon_is_activated('affiliate_system')): ?>
                            <li class="mb-2">
                                <a class="opacity-50 hov-opacity-100 text-light" href="<?php echo e(route('affiliate.apply')); ?>"><?php echo e(translate('Be an affiliate partner')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php if(get_setting('vendor_system_activation') == 1): ?>
                    <div class="text-center text-md-left mt-4">
                        <h4 class="fs-13 text-uppercase fw-600 border-bottom border-gray-900 pb-2 mb-4">
                            <?php echo e(translate('Be a Seller')); ?>

                        </h4>
                        <a href="<?php echo e(route('shops.create')); ?>" class="btn btn-primary btn-sm shadow-md">
                            <?php echo e(translate('Apply Now')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="pt-3 pb-7 pb-xl-3 bg-black text-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="text-center text-md-left" current-verison="<?php echo e(get_setting("current_version")); ?>">
                    All Rights Reserved By A1systems BD
                </div>
            </div>
            <div class="col-lg-4">
                <?php if( get_setting('show_social_links') ): ?>
                <ul class="list-inline my-3 my-md-0 social colored text-center">
                    <?php if( get_setting('facebook_link') !=  null ): ?>
                    <li class="list-inline-item">
                        <a href="<?php echo e(get_setting('facebook_link')); ?>" target="_blank" class="facebook"><i class="lab la-facebook-f"></i></a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_setting('twitter_link') !=  null ): ?>
                    <li class="list-inline-item">
                        <a href="<?php echo e(get_setting('twitter_link')); ?>" target="_blank" class="twitter"><i class="lab la-twitter"></i></a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_setting('instagram_link') !=  null ): ?>
                    <li class="list-inline-item">
                        <a href="<?php echo e(get_setting('instagram_link')); ?>" target="_blank" class="instagram"><i class="lab la-instagram"></i></a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_setting('youtube_link') !=  null ): ?>
                    <li class="list-inline-item">
                        <a href="<?php echo e(get_setting('youtube_link')); ?>" target="_blank" class="youtube"><i class="lab la-youtube"></i></a>
                    </li>
                    <?php endif; ?>
                    <?php if( get_setting('linkedin_link') !=  null ): ?>
                    <li class="list-inline-item">
                        <a href="<?php echo e(get_setting('linkedin_link')); ?>" target="_blank" class="linkedin"><i class="lab la-linkedin-in"></i></a>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="text-center text-md-right">
                    <ul class="list-inline mb-0">
                                <li class="list-inline-item">
<span style="color: white;">&nbsp;Website Designed By: <a href="https://tomal-ict.com" target="_blank"><span style="color: white;">Tomal Krishna Howlader</span></a></span>
                                </li>
            
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top" style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important; ">
    <div class="row align-items-center gutters-5">
        <div class="col">
            <a href="<?php echo e(route('home')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-home fs-20 opacity-60 <?php echo e(areActiveRoutes(['home'],'opacity-100 text-primary')); ?>"></i>
                <span class="d-block fs-10 fw-600 opacity-60 <?php echo e(areActiveRoutes(['home'],'opacity-100 fw-600')); ?>"><?php echo e(translate('Home')); ?></span>
            </a>
        </div>
        <div class="col">
            <a href="<?php echo e(route('categories.all')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-list-ul fs-20 opacity-60 <?php echo e(areActiveRoutes(['categories.all'],'opacity-100 text-primary')); ?>"></i>
                <span class="d-block fs-10 fw-600 opacity-60 <?php echo e(areActiveRoutes(['categories.all'],'opacity-100 fw-600')); ?>"><?php echo e(translate('Categories')); ?></span>
            </a>
        </div>
        <?php
            if(auth()->user() != null) {
                $user_id = Auth::user()->id;
                $cart = \App\Models\Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = Session()->get('temp_user_id');
                if($temp_user_id) {
                    $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
                }
            }
        ?>
        <div class="col-auto">
            <a href="<?php echo e(route('cart')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                <span class="align-items-center bg-primary border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px" style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                    <i class="las la-shopping-bag la-2x text-white"></i>
                </span>
                <span class="d-block mt-1 fs-10 fw-600 opacity-60 <?php echo e(areActiveRoutes(['cart'],'opacity-100 fw-600')); ?>">
                    <?php echo e(translate('Cart')); ?>

                    <?php
                        $count = (isset($cart) && count($cart)) ? count($cart) : 0;
                    ?>
                    (<span class="cart-count"><?php echo e($count); ?></span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="<?php echo e(route('all-notifications')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-inline-block position-relative px-2">
                    <i class="las la-bell fs-20 opacity-60 <?php echo e(areActiveRoutes(['all-notifications'],'opacity-100 text-primary')); ?>"></i>
                    <?php if(Auth::check() && count(Auth::user()->unreadNotifications) > 0): ?>
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 7px;top: -2px;"></span>
                    <?php endif; ?>
                </span>
                <span class="d-block fs-10 fw-600 opacity-60 <?php echo e(areActiveRoutes(['all-notifications'],'opacity-100 fw-600')); ?>"><?php echo e(translate('Notifications')); ?></span>
            </a>
        </div>
        <div class="col">
        <?php if(Auth::check()): ?>
            <?php if(isAdmin()): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        <?php if(Auth::user()->photo != null): ?>
                            <img src="<?php echo e(custom_asset(Auth::user()->avatar_original)); ?>" class="rounded-circle size-20px">
                        <?php else: ?>
                            <img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>" class="rounded-circle size-20px">
                        <?php endif; ?>
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60"><?php echo e(translate('Account')); ?></span>
                </a>
            <?php else: ?>
                <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                    <span class="d-block mx-auto">
                        <?php if(Auth::user()->photo != null): ?>
                            <img src="<?php echo e(custom_asset(Auth::user()->avatar_original)); ?>" class="rounded-circle size-20px">
                        <?php else: ?>
                            <img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>" class="rounded-circle size-20px">
                        <?php endif; ?>
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60"><?php echo e(translate('Account')); ?></span>
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e(route('user.login')); ?>" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-block mx-auto">
                    <img src="<?php echo e(static_asset('assets/img/avatar-place.png')); ?>" class="rounded-circle size-20px">
                </span>
                <span class="d-block fs-10 fw-600 opacity-60"><?php echo e(translate('Account')); ?></span>
            </a>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php if(Auth::check() && !isAdmin()): ?>
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            <?php echo $__env->make('frontend.inc.user_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
<?php endif; ?>

    <div class="modal fade" id="guest-modal">
        <div class="modal-dialog modal-lg modal-dialog-zoom order-confirmation-dialog">
            <div class="modal-content">


                <div class="modal-header">
                    <h6 class="modal-title fw-600">Order confirmation</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>

        <form action="<?php echo e(route('guest.payment.checkout')); ?>" method="post">
            <?php echo csrf_field(); ?>
                <div class="modal-body">

    <div class="mb-4">
        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3">
            <div class="col-lg-5 fw-600">Product</div>
            <div class="col-lg-1 fw-600">Price</div>
            <div class="col-lg-2 fw-600 text-center">Quantity</div>
            <div class="col-lg-1 fw-600 text-center">Total</div>
            <div class="col-auto fw-600 text-right">Remove</div>
        </div>
        <ul class="list-group list-group-flush" id="itemadd" style="width:100%;"></ul>
    </div>

    <?php
        $guest_carts = \App\Models\Cart::where('temp_user_id', session('temp_user_id'))->get();
        // Delivery areas configured in Admin > Shipping Configuration are always offered at checkout.
        $guest_delivery_areas = array_values(array_filter(
            json_decode(get_setting('delivery_areas'), true) ?: [],
            function ($area) { return isset($area['label']) && trim($area['label']) !== ''; }
        ));
        if (empty($guest_delivery_areas)) {
            $guest_delivery_areas = [
                ['label' => 'Inside Dhaka', 'charge' => 60],
                ['label' => 'Sub Area', 'charge' => 100],
                ['label' => 'Out Side Dhaka', 'charge' => 120],
            ];
        }
        $guest_delivery_area_enabled = count($guest_delivery_areas) > 0;
        // Default area = "Inside Dhaka" (matched by name), otherwise the first area.
        $guest_default_area = $guest_delivery_areas[0]['label'];
        foreach ($guest_delivery_areas as $guest_area_item) {
            $guest_area_name = mb_strtolower($guest_area_item['label']);
            if ((strpos($guest_area_name, 'inside') !== false && strpos($guest_area_name, 'dhaka') !== false) || mb_strpos($guest_area_name, 'ঢাকার ভিতরে') !== false) {
                $guest_default_area = $guest_area_item['label'];
                break;
            }
        }
        $guest_free_delivery = json_decode(get_setting('free_delivery_condition'), true) ?: ['enabled' => 0, 'min_amount' => 0];
        $guest_payment_options = [];
        if (get_setting('bkash') == 1) { $guest_payment_options['bkash'] = 'bKash'; }
        if (get_setting('nagad') == 1) { $guest_payment_options['nagad'] = 'Nagad'; }
        if (get_setting('sslcommerz_payment') == 1) { $guest_payment_options['sslcommerz'] = 'SSLCommerz'; }
        if (get_setting('aamarpay') == 1) { $guest_payment_options['aamarpay'] = 'Aamarpay'; }
        if (get_setting('paypal_payment') == 1) { $guest_payment_options['paypal'] = 'PayPal'; }
        if (get_setting('stripe_payment') == 1) { $guest_payment_options['stripe'] = 'Stripe'; }
        if (get_setting('mercadopago_payment') == 1) { $guest_payment_options['mercadopago'] = 'Mercado Pago'; }
        if (get_setting('instamojo_payment') == 1) { $guest_payment_options['instamojo'] = 'Instamojo'; }
        if (get_setting('razorpay') == 1) { $guest_payment_options['razorpay'] = 'Razorpay'; }
        if (get_setting('voguepay') == 1) { $guest_payment_options['voguepay'] = 'VoguePay'; }
        if (get_setting('payhere') == 1) { $guest_payment_options['payhere'] = 'PayHere'; }
        if (get_setting('ngenius') == 1) { $guest_payment_options['ngenius'] = 'N-Genius'; }
        if (get_setting('iyzico') == 1) { $guest_payment_options['iyzico'] = 'Iyzico'; }
        if (get_setting('authorizenet') == 1) { $guest_payment_options['authorizenet'] = 'Authorize.Net'; }
        if (get_setting('payku') == 1) { $guest_payment_options['payku'] = 'Payku'; }

        $guest_payment_icons = [
            'bkash' => 'cards/bkash.png',
            'nagad' => 'cards/nagad.png',
            'sslcommerz' => 'cards/sslcommerz.png',
            'aamarpay' => 'cards/aamarpay.png',
            'paypal' => 'cards/paypal.png',
            'stripe' => 'cards/stripe.png',
            'mercadopago' => 'cards/mercadopago.png',
            'instamojo' => 'cards/instamojo.png',
            'razorpay' => 'cards/rozarpay.png',
            'voguepay' => 'cards/vogue.png',
            'payhere' => 'cards/payhere.png',
            'ngenius' => 'cards/ngenius.png',
            'iyzico' => 'cards/iyzico.png',
            'authorizenet' => 'cards/authorizenet.png',
            'payku' => 'cards/payku.png',
        ];


        $guest_cod_available = get_setting('cash_payment') == 1;
        foreach ($guest_carts as $guest_cart) {
            $guest_product = $guest_cart->product;
            if ($guest_product == null || $guest_product->digital == 1 || $guest_product->cash_on_delivery == 0) {
                $guest_cod_available = false;
                break;
            }
        }
        $guest_online_payment_options = $guest_payment_options;
        if ($guest_cod_available) { $guest_payment_options['cash_on_delivery'] = 'Cash on Delivery'; }
    ?>
    <?php if($guest_carts->count() > 0): ?>
        <?php echo $__env->make('frontend.partials.cart_coupon_box', ['carts' => $guest_carts, 'prefix' => 'guest'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    
        <div class="form-group">
            <label><strong>Your Name*</strong></label>
            <input class="form-control" type="text" placeholder="Your Name*" name="name" value="" required>
            
            <input class="form-control" type="hidden" name="email" value="<?php if(Auth()->check()): ?><?php echo e(Auth()->user()->email); ?> <?php else: ?> customer@gmail.com <?php endif; ?>">
        </div>
        
        <div class="form-group">
            <label><strong>Full Address*</strong></label>
            <input class="form-control" type="text" placeholder="Full Address*" name="address" value="" required>
        </div>
        
        <div class="form-group">
            <label><strong>Mobile Number * </strong></label>
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Mobile Number *" pattern="[0-9]{11}" required title="Example: 01xxxxxxxxxx">
            
        </div>
        
        <?php if($guest_delivery_area_enabled): ?>
        <div class="guest-checkout-section">
            <h6 class="guest-checkout-section-title">Select Delivery Area</h6>
            <div class="guest-choice-list">
                <?php $__currentLoopData = $guest_delivery_areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest_delivery_area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="guest-choice-card">
                        <input type="radio" name="city" value="<?php echo e($guest_delivery_area['label']); ?>" <?php if($guest_delivery_area['label'] === $guest_default_area): ?> checked <?php endif; ?> required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-truck guest-choice-icon"></i>
                        <span class="guest-choice-label"><?php echo e($guest_delivery_area['label']); ?></span>
                        <strong class="guest-choice-price">৳<?php echo e($guest_delivery_area['charge']); ?></strong>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="d-none text-success fw-600 mt-2" id="guest-free-delivery-note"></small>
        </div>
        <?php else: ?>
            <input type="hidden" name="city" value="">
        <?php endif; ?>

        <div class="guest-checkout-section mb-0">
            <h6 class="guest-checkout-section-title">Payment Options</h6>
            <input type="hidden" name="payment_option" id="guest-payment-option">
            <div class="guest-choice-list">
                <?php if($guest_cod_available): ?>
                    <label class="guest-choice-card">
                        <input type="radio" name="guest_payment_type" value="cash_on_delivery" required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-money-bill-wave guest-choice-icon"></i>
                        <span class="guest-choice-label">Cash on Delivery</span>
                        <strong class="guest-choice-price guest-payment-total">৳0</strong>
                    </label>
                <?php endif; ?>
                <?php if(!empty($guest_online_payment_options)): ?>
                    <label class="guest-choice-card">
                        <input type="radio" name="guest_payment_type" value="online" required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-credit-card guest-choice-icon"></i>
                        <span class="guest-choice-label">Pay Total Amount</span>
                        <strong class="guest-choice-price guest-payment-total">৳0</strong>
                    </label>
                <?php endif; ?>
            </div>
            <div class="guest-online-payment-options d-none" id="guest-online-payment-options">
                <p class="mb-2 fw-600 fs-13">Select Payment Method</p>
                <div class="guest-online-grid">
                    <?php $__currentLoopData = $guest_online_payment_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_option => $payment_label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="guest-online-payment-choice">
                            <input type="radio" name="guest_online_payment_option" value="<?php echo e($payment_option); ?>">
                            <span class="guest-online-box">
                                <?php if(isset($guest_payment_icons[$payment_option]) && file_exists(public_path('assets/img/' . $guest_payment_icons[$payment_option]))): ?>
                                    <img src="<?php echo e(static_asset('assets/img/' . $guest_payment_icons[$payment_option])); ?>" alt="<?php echo e($payment_label); ?>" class="guest-online-icon">
                                <?php endif; ?>
                                <span class="guest-online-name"><?php echo e($payment_label); ?></span>
                            </span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php if(empty($guest_payment_options)): ?>
                <small class="text-danger">No payment method is currently active.</small>
            <?php endif; ?>
        </div>
        
        </div>
        
        <div class="modal-footer">
            <input type="submit" id="guest-payment-submit" class="btn btn-danger btn-block btn-sm" value="Confirm Order" <?php if(empty($guest_payment_options)): ?> disabled <?php endif; ?>>
        </div>
        
    </form>
            </div>
        </div>
    </div>

    
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="<?php echo e(static_asset('assets/js/jquryui.js')); ?>"></script>
<script type="text/javascript">

    function guest_cart_update(cardid, qty){

        event.preventDefault();

        if(qty > 0){
            $.ajax({
                url : "<?php echo e(Route('cartupdate.cart_update')); ?>",
                method : "get",
                data : {
                    cardid : cardid,
                    qty : qty
                },
                success : function(data){
                    if (data.status == 1) {
                        $("#guest_qty_"+cardid).val(data.stock);
                    }else{
                        $("#guest_price_"+cardid).text("৳"+data.price);
                        $("#guest_total_"+cardid).text("৳"+data.total);
                    }
                    if (data.box && $("#coupon_box_guest").length) {
                        $("#coupon_box_guest").replaceWith(data.box);
                    }
                    guest_refresh_order_summary();
                }
            });
        }
    }

    var guestDeliveryAreas = <?php echo json_encode($guest_delivery_areas, 15, 512) ?>;
    var guestDefaultArea = <?php echo json_encode($guest_default_area ?? null, 15, 512) ?>;
    var guestFreeDelivery = <?php echo json_encode($guest_free_delivery, 15, 512) ?>;

    // Free delivery is checked against the payable product amount (subtotal - coupon), same as the server.
    function guest_charge_should_be_free(amount){
        return !!guestFreeDelivery && Number(guestFreeDelivery.enabled) === 1 &&
            (Number(amount) || 0) >= (Number(guestFreeDelivery.min_amount) || 0);
    }

    function guest_selected_area(){
        var $radios = $('#guest-modal input[name="city"][type="radio"]');
        if ($radios.length && !$radios.filter(':checked').length) {
            var $def = $radios.filter(function(){ return this.value === guestDefaultArea; });
            ($def.length ? $def : $radios.first()).prop('checked', true);
        }
        var city = String($radios.filter(':checked').val() || '');
        return guestDeliveryAreas.find(function(area) { return area.label === city; }) || null;
    }

    function guest_order_charge(amount){
        if (guest_charge_should_be_free(amount)) {
            return 0;
        }
        var selectedArea = guest_selected_area();
        return selectedArea ? Math.max(0, Number(selectedArea.charge) || 0) : 0;
    }

    function guest_format_price(n){
        n = Number(n) || 0;
        return '৳' + (Number.isInteger(n) ? n : n.toFixed(2));
    }

    function guest_summary_html(){
        return '<li class="list-group-item px-0 px-lg-3 bg-light" id="guest-summary-row">' +
            '<div class="d-flex justify-content-between fs-15 mb-1"><span class="opacity-60">Subtotal</span><span class="fw-600" id="guest-order-subtotal">৳0</span></div>' +
            '<div class="d-flex justify-content-between fs-15 mb-1" id="guest-charge-row"><span class="opacity-60">Delivery Charge <small id="guest-charge-area" class="opacity-80"></small></span><span class="fw-600" id="guest-order-charge">৳0</span></div>' +
            '<div class="d-flex justify-content-between fs-15 mb-1 d-none text-success" id="guest-discount-row"><span class="opacity-60">Coupon Discount</span><span class="fw-600" id="guest-order-discount">৳0</span></div>' +
            '<div class="d-flex justify-content-between fs-17 border-top pt-2 mt-2"><span class="fw-700">Total</span><span class="fw-700 text-primary fs-20" id="guest-order-total">৳0</span></div>' +
        '</li>';
    }

    function guest_refresh_order_summary(){
        if (!$('#guest-summary-row').length) return;
        var subtotal = 0;
        $('#itemadd .addItem').each(function(){
            var t = parseFloat($(this).find('[id^="guest_total_"]').text().replace(/[^\d.]/g, '')) || 0;
            subtotal += t;
        });
        var discount = parseFloat($('#coupon_box_guest').attr('data-discount')) || 0;
        var payable = Math.max(0, subtotal - discount);
        var isFree = guest_charge_should_be_free(payable);
        var charge = guest_order_charge(payable);
        var area = guest_selected_area();
        var total = Math.max(0, subtotal + charge - discount);

        $('#guest-order-subtotal').text(guest_format_price(subtotal));
        $('#guest-order-charge').text(isFree ? 'Free' : guest_format_price(charge))
            .toggleClass('text-success', isFree);
        $('#guest-charge-area').text(area ? '(' + area.label + ')' : '');
        $('#guest-order-discount').text('-' + guest_format_price(discount));
        $('#guest-order-total').text(guest_format_price(total));
        $('.guest-payment-total').text(guest_format_price(total));

        $('#guest-modal .guest-choice-card input[name="city"]').each(function(){
            var key = $(this).val();
            var a = guestDeliveryAreas.find(function(x){ return x.label === key; });
            if (a) {
                $(this).closest('.guest-choice-card').find('.guest-choice-price').text(isFree ? 'Free' : guest_format_price(a.charge));
            }
        });

        var $note = $('#guest-free-delivery-note');
        if (guestFreeDelivery && Number(guestFreeDelivery.enabled) === 1 && Number(guestFreeDelivery.min_amount) > 0) {
            if (isFree) {
                $note.text('Congratulations! You got FREE delivery.').removeClass('d-none text-muted').addClass('text-success');
            } else {
                var need = Number(guestFreeDelivery.min_amount) - payable;
                $note.text('Shop ' + guest_format_price(need) + ' more to get FREE delivery.').removeClass('d-none text-success').addClass('text-muted');
            }
        } else {
            $note.addClass('d-none');
        }

        $('#guest-discount-row').toggleClass('d-none', !(discount > 0));
    }

    function guest_qty_step(cart_id, delta){
        var input = document.getElementById('guest_qty_' + cart_id);
        if (!input) return;
        var qty = parseInt(input.value, 10) || 1;
        var min = parseInt(input.min, 10) || 1;
        var max = parseInt(input.max, 10) || 99;
        qty = Math.max(min, Math.min(max, qty + delta));
        input.value = qty;
        guest_cart_update(cart_id, qty);
    }

    function removeguest(id){
        event.preventDefault();
        $.ajax({
                url : "<?php echo e(Route('cartdelete.cart_delete')); ?>",
                method : "get",
                data : {
                    id : id
                }
        });

        $("#guest_remove_"+id).remove();
        $(".cart-count").text($(".addItem").length);
        guest_refresh_order_summary();
        if($(".addItem").length == 0){
            window.location = "<?php echo e(url('/')); ?>";
        }
    }

    function guest_card(){
        event.preventDefault();
        $('#guest-modal').modal();

        $(document).off('click', '#guest-coupon-apply').on('click', '#guest-coupon-apply', function(){
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                code: $('#apply-coupon-form-guest input[name="code"]').val(),
                owner_id: $('#apply-coupon-form-guest input[name="owner_id"]').val()
            };
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('checkout.apply_coupon_code')); ?>',
                data: data,
                success: function(d){
                    if (d.response_message) {
                        if (d.response_message.response == 'success') {
                            if (d.guest_box_html) { $('#coupon_box_guest').replaceWith(d.guest_box_html); }
                            AIZ.plugins.notify('success', d.response_message.message);
                        } else {
                            AIZ.plugins.notify(d.response_message.response, d.response_message.message);
                        }
                    }
                    guest_refresh_order_summary();
                }
            });
        });

        $(document).off('click', '#guest-coupon-remove').on('click', '#guest-coupon-remove', function(){
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                owner_id: $('#remove-coupon-form-guest input[name="owner_id"]').val()
            };
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('checkout.remove_coupon_code')); ?>',
                data: data,
                success: function(d){
                    if (d.guest_box_html) { $('#coupon_box_guest').replaceWith(d.guest_box_html); }
                    if (d.response_message) { AIZ.plugins.notify('success', d.response_message.message); }
                    guest_refresh_order_summary();
                }
            });
        });

        $.ajax({
                url : "<?php echo e(Route('getguestcard.getguest_card')); ?>",
                method : "get",
                success : function(data){



                if(data.guest_data.length > 0){
                    $("#itemadd").html("");

                    $.each(data.guest_data, function(key, values){

                        var variationHtml = '';
                        if (values.variation) {
                            variationHtml = '<span class="d-block mt-1"><small class="d-inline-block px-2 py-1 rounded bg-soft-secondary text-reset fw-500">'+values.variation+'</small></span>';
                        }

                        $("#itemadd").append(
                            '<li class="list-group-item addItem px-0 px-lg-3" id="guest_remove_'+values.cart_id+'">' +
                                '<div class="row no-gutters align-items-center">' +
                                    '<div class="col-12 col-lg-5 pr-2">' +
                                        '<span class="d-flex align-items-center mr-0">' +
                                            '<span class="mr-2 ml-0"><img src="'+values.icon+'" class="img-fit size-50px rounded"></span>' +
                                            '<span class="d-inline-flex flex-column"><span class="fs-13 opacity-60 lh-1-4" style="white-space:normal;">'+values.title+'</span>'+variationHtml+'</span>' +
                                        '</span>' +
                                    '</div>' +
                                    '<div class="col-4 col-lg-1 my-2 my-lg-0"><span class="fw-600 fs-14" id="guest_price_'+values.cart_id+'">৳'+values.price+'</span></div>' +
                                    '<div class="col-4 col-lg-2 my-2 my-lg-0">' +
                                        '<div class="row no-gutters align-items-center aiz-plus-minus justify-content-lg-center mr-2 ml-0">' +
                                            '<button type="button" class="btn col-auto btn-icon btn-sm btn-circle btn-light" onclick="guest_qty_step('+values.cart_id+', -1)"><i class="las la-minus"></i></button>' +
                                            '<input type="number" name="quantity['+values.cart_id+']" id="guest_qty_'+values.cart_id+'" class="col border-0 text-center flex-grow-1 fs-14 input-number" placeholder="1" value="'+values.qty+'" min="1" max="50" onchange="guest_cart_update('+values.cart_id+', this.value)">' +
                                            '<button type="button" class="btn col-auto btn-icon btn-sm btn-circle btn-light" onclick="guest_qty_step('+values.cart_id+', 1)"><i class="las la-plus"></i></button>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="col-4 col-lg-1 text-lg-center my-2 my-lg-0"><span class="fw-600 fs-14 text-primary" id="guest_total_'+values.cart_id+'">৳'+values.total+'</span></div>' +
                                    '<div class="col-auto col-lg-auto text-right ml-auto"><a href="" onclick="removeguest('+values.cart_id+')" class="btn btn-icon btn-sm btn-soft-primary btn-circle"><i class="las la-trash"></i></a></div>' +
                                '</div>' +
                            '</li>'
                        );

                        

                    });
                    $("#itemadd").append(guest_summary_html());
                    guest_refresh_order_summary();
                }

                }
        });

    $(document).on('change', '#guest-modal input[name="city"]', guest_refresh_order_summary);

    $(document).on('change', '#guest-modal input[name="guest_payment_type"]', function(){
        var isOnline = this.value === 'online';
        $('#guest-online-payment-options').toggleClass('d-none', !isOnline);
        $('#guest-payment-option').val(isOnline ? '' : 'cash_on_delivery');
        $('#guest-payment-submit').val(isOnline ? 'Proceed to Payment' : 'Confirm Order');
        if (!isOnline) {
            $('#guest-modal input[name="guest_online_payment_option"]').prop('checked', false);
        }
    });

    $(document).on('change', '#guest-modal input[name="guest_online_payment_option"]', function(){
        $('#guest-payment-option').val(this.value);
    });

    $('#guest-modal form').on('submit', function(e){
        if (!$('input[name="guest_payment_type"]:checked').length) {
            e.preventDefault();
            return;
        }
        if ($('input[name="guest_payment_type"]:checked').val() === 'online' && !$('input[name="guest_online_payment_option"]:checked').length) {
            e.preventDefault();
            AIZ.plugins.notify('warning', 'Please select a payment method.');
        }
    });



    }

    function guest_save_incomplete_order(){
        clearTimeout(window._sio_timer);
        window._sio_timer = setTimeout(function(){
            var form = $('#guest-modal form');
            if (!form.length) return;
            $.ajax({
                url: '<?php echo e(route('incomplete_order.store')); ?>',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: form.find('input[name="name"]').val(),
                    email: form.find('input[name="email"]').val(),
                    address: form.find('input[name="address"]').val(),
                    phone: form.find('input[name="phone"]').val(),
                    city: form.find('input[name="city"]:checked').val()
                }
            });
        }, 700);
    }

    $(document).on('input change', '#guest-modal form input, #guest-modal form select', guest_save_incomplete_order);
    $('#guest-modal').on('hidden.bs.modal', guest_save_incomplete_order);

</script>
<?php /**PATH E:\xampp\htdocs\1_Asystem\wisemanbd.com\resources\views/frontend/inc/footer.blade.php ENDPATH**/ ?>