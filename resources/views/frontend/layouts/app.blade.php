<!DOCTYPE html>
@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <title>@yield('meta_title', get_setting('website_name').' | '.get_setting('site_motto'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description') )" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords') )">

    @yield('meta')

    @if(!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
        <meta name="twitter:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ get_setting('meta_title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ route('home') }}" />
        <meta property="og:image" content="{{ uploaded_asset(get_setting('meta_image')) }}" />
        <meta property="og:description" content="{{ get_setting('meta_description') }}" />
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif
<meta name="facebook-domain-verification" content="fyxav3lhjmr6gjtzvyu9o0r4utfakz" />
    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@400;500;600&family=Noto+Sans+Bengali:wght@600;700&display=swap" rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css?v=20261120b') }}">
    @if (addon_is_activated('otp_system'))
        <link rel="stylesheet" href="{{ static_asset('assets/css/intlTelInput.min.css') }}">
    @endif
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{{ translate('Nothing selected') }}',
            nothing_found: '{{ translate('Nothing found') }}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>

    <style>
        body{
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
        }
        :root{
            --primary: {{ get_setting('base_color', '#e62d04') }};
            --hov-primary: {{ get_setting('base_hov_color', '#c52907') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color','#e62d04'),.15) }};
        }

        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }

        .pac-container { z-index: 100000; }
    </style>

@if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
@endif

@if (get_setting('facebook_pixel') == 1 && !in_array(request()->getHost(), ['localhost', '127.0.0.1']) && env('FACEBOOK_PIXEL_ID'))
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@endif

@php
    echo get_setting('header_script');
@endphp

</head>
<body>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column">

        <!-- Header -->
        @include('frontend.inc.nav')

        <main class="frontend-content">
            @yield('content')
        </main>

        @include('frontend.inc.footer')

        @include('frontend.inc.floating_cart')

        <button type="button" id="scrollTopBtn" class="btn btn-soft-primary btn-circle btn-icon shadow-lg" title="Scroll to top" aria-label="Scroll to top">
            <i class="las la-angle-up fs-18"></i>
        </button>

        @include('frontend.inc.chat_widget')
        
    </div>

    @if (get_setting('show_cookies_agreement') == 'on')
        <div class="aiz-cookie-alert shadow-xl">
            <div class="p-3 bg-dark rounded">
                <div class="text-white mb-3">
                    @php
                        echo get_setting('cookies_agreement_text');
                    @endphp
                </div>
                <button class="btn btn-primary aiz-cookie-accept">
                    {{ translate('Ok. I Understood') }}
                </button>
            </div>
        </div>
    @endif

    @if (get_setting('show_website_popup') == 'on')
        <div class="modal website-popup removable-session d-none" data-key="website-popup" data-value="removed">
            <div class="absolute-full bg-black opacity-60"></div>
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-md">
                <div class="modal-content position-relative border-0 rounded-0">
                    <div class="aiz-editor-data">
                        {!! get_setting('website_popup_content') !!}
                    </div>
               {{--     @if (get_setting('show_subscribe_form') == 'on')
                        <div class="pb-5 pt-4 px-5">
                            <form class="" method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" placeholder="{{ translate('Your Phone Number') }}" name="email" maxlength="11" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-3">
                                    {{ translate('Subscribe Now') }}
                                </button>
                            </form>
                        </div>
                    @endif  --}}
                    <button class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                        <i class="la la-close fs-20"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

   @include('frontend.partials.modal')

    <!-- Add to Cart modal (color & size selection) -->
    <div class="modal fade" id="addToCart-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h6">{{ translate('Select Color & Size') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" id="addToCart-modal-body"></div>
            </div>
        </div>
    </div>

    @yield('modal')

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    @if (addon_is_activated('otp_system'))
        <script src="{{ static_asset('assets/js/intlTelInput.js') }}"></script>
    @endif
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
    
    
    
  



    @if (get_setting('facebook_chat') == 1)
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({
                  xfbml            : true,
                  version          : 'v3.3'
                });
              };

              (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif

    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>

    <script>

        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {
                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {_token: AIZ.data.csrf, id:$(el).data('id')}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });
            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}',{_token: AIZ.data.csrf, locale:locale}, function(data){
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}',{_token: AIZ.data.csrf, currency_code:currency_code}, function(data){
                            location.reload();
                        });

                    });
                });
            }
        });

        $('#search').on('keyup', function(){
            search();
        });

        $('#search').on('focus', function(){
            search();
        });

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        function updateNavCart(view,count,total){
            $('.cart-count').html(count);
            if (typeof total !== 'undefined' && total !== null) {
                $('.floating-cart-total').text(total);
            }
            $('#cart_items').html(view);

            if (typeof total === 'undefined' && typeof refreshNavCartSoon === 'function') {
                refreshNavCartSoon();
            }
        }

        function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                updateNavCart(data.nav_cart_view,data.cart_count);
                $('#cart-summary').html(data.cart_view);
                AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
            });
        }

        function addToWishList(id){
            @if (Auth::check() && (Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller'))
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data != 0){
                        $('#wishlist').html(data);
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                    }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function getCartCount(){
            var c = 0;
            $('.cart-count').each(function(){
                var n = parseInt($(this).text());
                if(!isNaN(n)) c = n;
            });
            return c;
        }

        function setCartCount(count){
            $('.cart-count').text(count);
        }

        function refreshNavCart(){
            $.post('{{ route('cart.nav') }}', {
                _token: AIZ.data.csrf
            }, function(data){
                if (data && data.nav_cart_view) {
                    updateNavCart(data.nav_cart_view, data.cart_count, data.cart_total);
                }
            });
        }

        var navCartTimer = null;
        function refreshNavCartSoon(){
            clearTimeout(navCartTimer);
            navCartTimer = setTimeout(function(){
                if (!$('#cart_items').hasClass('show')) {
                    refreshNavCart();
                }
            }, 350);
        }

        $(document).on('show.bs.dropdown', '.nav-cart-box', function(){
            clearTimeout(navCartTimer);
            refreshNavCart();
        });

        function showCartModal(id, buyNow){
            $.post('{{ route('cart.showCartModal') }}', {
                _token : AIZ.data.csrf,
                id     : id,
                buy_now: buyNow ? 1 : 0
            }, function(data){
                $('#addToCart-modal-body').html(data);
                $('#addToCart-modal').modal('show');
            });
        }

        var cartAnimationSource = null;

        document.addEventListener('click', function(event){
            var element = event.target instanceof Element ? event.target : event.target.parentElement;
            var source = element ? element.closest('[onclick*="addToCart"], [onclick*="buyNows"]') : null;

            if (source) {
                cartAnimationSource = source;
            }
        }, true);

        function takeCartAnimationSource(){
            var source = cartAnimationSource;
            cartAnimationSource = null;

            if (source && document.documentElement.contains(source)) {
                return source;
            }

            return document.activeElement instanceof HTMLElement ? document.activeElement : null;
        }

        function isCartAnimationTargetVisible(target){
            if (!target || !target.getClientRects().length) {
                return false;
            }

            var style = window.getComputedStyle(target);
            return style.display !== 'none' && style.visibility !== 'hidden';
        }

        function getCartAnimationTarget(){
            var floatingCart = document.querySelector('.floating-cart-widget');
            var desktopCart = document.getElementById('cart_items');
            var mobileCartIcon = document.querySelector('.aiz-mobile-bottom-nav .la-shopping-bag');
            var mobileCart = mobileCartIcon ? mobileCartIcon.closest('a') : null;
            var targets = [floatingCart, desktopCart, mobileCart];

            for (var i = 0; i < targets.length; i++) {
                if (isCartAnimationTargetVisible(targets[i])) {
                    return targets[i];
                }
            }

            return null;
        }

        function getCartAnimationImage(source){
            var image = null;

            if (source) {
                var card = source.closest('.aiz-card-box');
                var modal = source.closest('.modal');
                image = card ? card.querySelector('.product-card-image-img, img') : null;
                image = image || (modal ? modal.querySelector('.product-card-image-img, .product-gallery img, img') : null);
            }

            image = image || document.querySelector('.product-gallery .slick-current img, .product-gallery img');

            var sourceUrl = image && (image.getAttribute('data-src') || image.currentSrc || image.src);
            return sourceUrl && sourceUrl.indexOf('placeholder') === -1 ? sourceUrl : null;
        }

        function pulseCartTarget(target){
            target.classList.remove('cart-success-pulse');
            void target.offsetWidth;
            target.classList.add('cart-success-pulse');

            window.setTimeout(function(){
                target.classList.remove('cart-success-pulse');
            }, 850);
        }

        function burstCartSuccess(target){
            var rect = target.getBoundingClientRect();
            var cx = rect.left + (rect.width / 2);
            var cy = rect.top + (rect.height / 2);
            var colors = ['#e72a35', '#ffb020', '#22b573'];
            var burstColors = ['#e72a35', '#ff8a1a', '#ffb020', '#22b573', '#4f7cff', '#ffd166'];

            for (var i = 0; i < 10; i++) {
                var dot = document.createElement('span');
                dot.className = 'cart-burst-dot';
                dot.style.background = burstColors[i % burstColors.length];
                dot.style.left = cx + 'px';
                dot.style.top = cy + 'px';
                document.body.appendChild(dot);

                var angle = (Math.PI * 2 * i / 10) + (Math.random() * 0.6 - 0.3);
                var distance = 34 + Math.random() * 26;
                var dx = Math.cos(angle) * distance;
                var dy = Math.sin(angle) * distance;

                if (typeof dot.animate !== 'function') {
                    dot.remove();
                    continue;
                }

                var dotAnimation = dot.animate([
                    { transform: 'translate(-50%, -50%) scale(1)', opacity: 1 },
                    { transform: 'translate(calc(-50% + ' + dx + 'px), calc(-50% + ' + dy + 'px)) scale(0)', opacity: 0 }
                ], {
                    duration: 520 + Math.random() * 180,
                    easing: 'cubic-bezier(0.18, 0.9, 0.3, 1)'
                });

                var removeDot = function(){
                    dot.remove();
                };
                dotAnimation.onfinish = removeDot;
                window.setTimeout(removeDot, 800);
            }

            var plus = document.createElement('span');
            plus.className = 'cart-plus-one';
            plus.style.left = cx + 'px';
            plus.style.top = cy + 'px';
            document.body.appendChild(plus);

            if (typeof plus.animate === 'function') {
                var plusAnimation = plus.animate([
                    { transform: 'translate(-50%, -50%) scale(0.7)', opacity: 0 },
                    { transform: 'translate(-50%, -50%) scale(1.12)', opacity: 1, offset: 0.2 },
                    { transform: 'translate(-50%, calc(-50% - 46px)) scale(1)', opacity: 0 }
                ], {
                    duration: 780,
                    easing: 'ease-out'
                });

                var removePlus = function(){
                    plus.remove();
                };
                plusAnimation.onfinish = removePlus;
                window.setTimeout(removePlus, 950);
            }
            else {
                plus.remove();
            }
        }

        function playCartAddAnimation(source){
            var target = getCartAnimationTarget();
            if (!target) {
                return;
            }

            var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (prefersReduced) {
                pulseCartTarget(target);
                return;
            }

            var targetRect = target.getBoundingClientRect();
            var sourceRect = source && source.getClientRects().length ? source.getBoundingClientRect() : targetRect;
            var size = 62;
            var startX = sourceRect.left + (sourceRect.width / 2) - (size / 2);
            var startY = sourceRect.top + (sourceRect.height / 2) - (size / 2);
            var endX = targetRect.left + (targetRect.width / 2) - (size / 2);
            var endY = targetRect.top + (targetRect.height / 2) - (size / 2);
            var deltaX = endX - startX;
            var deltaY = endY - startY;
            var item = document.createElement('div');
            var imageUrl = getCartAnimationImage(source);

            item.className = 'cart-fly-item' + (imageUrl ? '' : ' cart-fly-item-icon');
            item.style.left = startX + 'px';
            item.style.top = startY + 'px';

            if (imageUrl) {
                var image = document.createElement('img');
                image.src = imageUrl;
                image.alt = '';
                item.appendChild(image);
            }
            else {
                var icon = document.createElement('i');
                icon.className = 'las la-shopping-bag';
                item.appendChild(icon);
            }

            document.body.appendChild(item);

            var trail = document.createElement('span');
            trail.className = 'cart-fly-trail';
            trail.style.left = startX + (size / 2) + 'px';
            trail.style.top = startY + (size / 2) + 'px';
            document.body.appendChild(trail);

            if (typeof item.animate !== 'function' || typeof trail.animate !== 'function') {
                item.remove();
                trail.remove();
                pulseCartTarget(target);
                return;
            }

            var itemAnimation = item.animate([
                { transform: 'translate3d(0, 0, 0) scale(1) rotate(0deg)', opacity: 1 },
                { transform: 'translate3d(' + (deltaX * 0.5) + 'px, ' + ((deltaY * 0.3) - 62) + 'px, 0) scale(0.9) rotate(14deg)', opacity: 1, offset: 0.5 },
                { transform: 'translate3d(' + (deltaX * 0.98) + 'px, ' + (deltaY * 0.98) + 'px, 0) scale(0.16) rotate(-6deg)', opacity: 0.9, offset: 0.92 },
                { transform: 'translate3d(' + deltaX + 'px, ' + deltaY + 'px, 0) scale(0.1) rotate(-6deg)', opacity: 0 }
            ], {
                duration: 720,
                easing: 'cubic-bezier(0.25, 0.9, 0.35, 1)',
                fill: 'forwards'
            });

            var removeItem = function(){
                item.remove();
            };
            itemAnimation.onfinish = function(){
                removeItem();
                pulseCartTarget(target);
                burstCartSuccess(target);
            };
            window.setTimeout(removeItem, 950);

            var trailAnimation = trail.animate([
                { transform: 'translate(-50%, -50%) scale(1.35)', opacity: 0.6 },
                { transform: 'translate(' + (deltaX * 0.55) + 'px, ' + ((deltaY * 0.45) - 40) + 'px)', opacity: 0.18, offset: 0.6 },
                { transform: 'translate(' + deltaX + 'px, ' + deltaY + 'px) scale(0.2)', opacity: 0 }
            ], {
                duration: 700,
                delay: 110,
                easing: 'cubic-bezier(0.25, 0.9, 0.35, 1)',
                fill: 'forwards'
            });

            var removeTrail = function(){
                trail.remove();
            };
            trailAnimation.onfinish = removeTrail;
            window.setTimeout(removeTrail, 950);

            var ring = document.createElement('span');
            ring.className = 'cart-success-ring';
            ring.style.left = (targetRect.left + (targetRect.width / 2)) + 'px';
            ring.style.top = (targetRect.top + (targetRect.height / 2)) + 'px';
            document.body.appendChild(ring);

            var ringAnimation = ring.animate([
                { transform: 'translate(-50%, -50%) scale(0.35)', opacity: 0.95 },
                { transform: 'translate(-50%, -50%) scale(1.85)', opacity: 0 }
            ], {
                duration: 560,
                easing: 'ease-out'
            });

            var removeRing = function(){
                ring.remove();
            };
            ringAnimation.onfinish = removeRing;
            window.setTimeout(removeRing, 750);
        }

        function addToCartDirect(id, hasOptions){
            if(hasOptions){
                showCartModal(id);
                return;
            }
            var animationSource = takeCartAnimationSource();
            var prev = getCartCount();
            setCartCount(prev + 1);
            $.post('{{ route('cart.addToCart') }}', {
                _token  : AIZ.data.csrf,
                id      : id,
                quantity: 1,
                quick   : 1
            }, function(data){
                if (data && data.status == 1) {
                    setCartCount(data.cart_count);
                    AIZ.plugins.notify('success', "{{ translate('Item has been added to cart') }}");
                    refreshNavCartSoon();
                    playCartAddAnimation(animationSource);
                }
                else {
                    setCartCount(prev);
                    AIZ.plugins.notify('warning', "{{ translate('Product is out of stock') }}");
                }
            }).fail(function(){
                setCartCount(prev);
            });
        }

        function buyNowDirect(id, hasOptions){
            if(hasOptions){
                showCartModal(id, true);
                return;
            }

            var prev = getCartCount();
            setCartCount(prev + 1);
            $.post('{{ route('cart.addToCart') }}', {
                _token  : AIZ.data.csrf,
                id      : id,
                quantity: 1,
                quick   : 1
            }, function(data){
                if (data && data.status == 1) {
                    setCartCount(data.cart_count);
                    @auth
                    window.location.replace("{{ route('checkout.shipping_info') }}");
                    @else
                    guest_card();
                    @endauth
                }
                else {
                    setCartCount(prev);
                    AIZ.plugins.notify('warning', data && data.message ? data.message : "{{ translate('Product is out of stock') }}");
                }
            }).fail(function(){
                setCartCount(prev);
            });
        }

        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        function getVariantPrice(){
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                $.ajax({
                   type:"POST",
                   url: '{{ route('products.variant_price') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){

                        $('.product-gallery-thumb .carousel-box').each(function (i) {
                            if($(this).data('variation') && data.variation == $(this).data('variation')){
                                $('.product-gallery-thumb').slick('slickGoTo', i);
                            }
                        })

                       $('#option-choice-form #chosen_price_div').removeClass('d-none');
                       $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                       $('#available-quantity').html(data.quantity);
                       $('.input-number').prop('max', data.max_limit);
                       if(parseInt(data.in_stock) == 0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                       }
                       else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                       }
                   }
               });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                  names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                  count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }

        function missingOptionMessage(){
            var colorMissing = $('input[name="color"]').length > 0 && !$('input[name="color"]:checked').length;
            var sizeMissing = $('input[name="size"]').length > 0 && !$('input[name="size"]:checked').length;
            if(colorMissing && sizeMissing){
                return "{{ translate('Ã Â¦â€¢Ã Â¦Â¾Ã Â¦Â²Ã Â¦Â¾Ã Â¦Â° Ã Â¦â€œ Ã Â¦Â¸Ã Â¦Â¾Ã Â¦â€¡Ã Â¦Å“ Ã Â¦Â¸Ã Â¦Â¿Ã Â¦Â²Ã Â§â€¡Ã Â¦â€¢Ã Â§ÂÃ Â¦Å¸ Ã Â¦â€¢Ã Â¦Â°Ã Â§ÂÃ Â¦Â¨') }}";
            }
            if(colorMissing){
                return "{{ translate('Ã Â¦â€ Ã Â¦ÂªÃ Â¦Â¨Ã Â¦Â¾Ã Â¦â€¢Ã Â§â€¡ Ã Â¦â€¢Ã Â¦Â¾Ã Â¦Â²Ã Â¦Â¾Ã Â¦Â° Ã Â¦Â¸Ã Â¦Â¿Ã Â¦Â²Ã Â§â€¡Ã Â¦â€¢Ã Â§ÂÃ Â¦Å¸ Ã Â¦â€¢Ã Â¦Â°Ã Â¦Â¤Ã Â§â€¡ Ã Â¦Â¹Ã Â¦Â¬Ã Â§â€¡') }}";
            }
            if(sizeMissing){
                return "{{ translate('Ã Â¦â€ Ã Â¦ÂªÃ Â¦Â¨Ã Â¦Â¾Ã Â¦â€¢Ã Â§â€¡ Ã Â¦Â¸Ã Â¦Â¾Ã Â¦â€¡Ã Â¦Å“ Ã Â¦Â¸Ã Â¦Â¿Ã Â¦Â²Ã Â§â€¡Ã Â¦â€¢Ã Â§ÂÃ Â¦Å¸ Ã Â¦â€¢Ã Â¦Â°Ã Â¦Â¤Ã Â§â€¡ Ã Â¦Â¹Ã Â¦Â¬Ã Â§â€¡') }}";
            }
            return "{{ translate('Please choose all the options') }}";
        }

        function addToCart(){
            if(checkAddToCartValidity()) {
                var animationSource = takeCartAnimationSource();
                // $('#addToCart').modal();
                // $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){

                    //   $('#addToCart-modal-body').html(null);
                    //   $('.c-preloader').hide();
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.modal_view);
                       AIZ.extra.plusMinus();
                       updateNavCart(data.nav_cart_view,data.cart_count);
                       if(data && data.status == 1){
                           playCartAddAnimation(animationSource);
                       }
                    }
                });
            }
            else{
                AIZ.plugins.notify('warning', missingOptionMessage());
            }
        }
        
        function addbuynow(){
            if(checkAddToCartValidity()) {
                // $('#addToCart').modal();
                // $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        if(data && data.status == 1){
                            updateNavCart(data.nav_cart_view, data.cart_count);
                            guest_card();
                        }
                        else{
                            AIZ.plugins.notify('warning', data && data.message ? data.message : "{{ translate('Product is out of stock') }}");
                        }
                    }
                });
            }
            else{
                AIZ.plugins.notify('warning', missingOptionMessage());
            }
        }

        function buyNow(){
            if(checkAddToCartValidity()) {
                // $('#addToCart-modal-body').html(null);
                // $('#addToCart').modal();
                // $('.c-preloader').show();
                $.ajax({
                   type:"POST",
                   url: '{{ route('cart.addToCart') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){
                       if(data && data.status == 1){

                            // $('#addToCart-modal-body').html(data.modal_view);
                            updateNavCart(data.nav_cart_view,data.cart_count);

                            window.location.replace("{{ route('checkout.shipping_info') }}");
                       }
                       else{
                            AIZ.plugins.notify('warning', data && data.message ? data.message : "{{ translate('Product is out of stock') }}");
                       }
                   }
               });
            }
            else{
                AIZ.plugins.notify('warning', missingOptionMessage());
            }
        }

        function show_purchase_history_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('purchase_history.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
            });
        }


    </script>

     <script>
    // Scroll to top button
    (function () {
        var btn = document.getElementById('scrollTopBtn');
        if (!btn) {
            return;
        }
        function scrollFunction() {
            var y = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            if (y > 300) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        }
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        window.addEventListener('scroll', scrollFunction, { passive: true });
        window.addEventListener('resize', scrollFunction);
        scrollFunction();
    })();
</script>
    @yield('script')

    @php
        echo get_setting('footer_script');
    @endphp
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v14.0&appId=4979933988710837&autoLogAppEvents=1" nonce="mvlbA8Xg"></script>
</body>
</html>
