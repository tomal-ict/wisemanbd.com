<div class="topbar-delivery position-relative text-white font-weight-500 py-2" id="topbarDelivery" style="background:#2a0f45;font-size:12px;letter-spacing:.3px;font-family:'Inter',system-ui,sans-serif;padding-left:36px;padding-right:36px;text-align:center;">
    <div class="topbar-delivery-content d-inline-block">
        <span>Delivery all over Bangladesh</span> <span class="d-inline-block px-1">·</span> <span>Cash on Delivery</span> <span class="d-inline-block px-1">·</span> <span>Easy Exchange</span>
    </div>
    <button type="button" class="topbar-delivery-close" onclick="closeTopbarDelivery()" aria-label="Close" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:transparent;border:0;color:#ffffff;opacity:0.85;cursor:pointer;padding:4px 8px;font-size:16px;line-height:1;display:flex;align-items:center;">
        <i class="las la-times"></i>
    </button>
</div>
<script>
    function closeTopbarDelivery() {
        var el = document.getElementById('topbarDelivery');
        if (el) {
            el.style.display = 'none';
            try { sessionStorage.setItem('topbar_delivery_closed', '1'); } catch(e) {}
        }
    }
    (function(){
        try {
            if (sessionStorage.getItem('topbar_delivery_closed') === '1') {
                var el = document.getElementById('topbarDelivery');
                if (el) el.style.display = 'none';
            }
        } catch(e) {}
    })();
</script>

@if(get_setting('topbar_banner') != null)
<div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner" data-value="removed">
    <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset">
        <img src="{{ uploaded_asset(get_setting('topbar_banner')) }}" class="w-100 mw-100 h-50px h-lg-auto img-fit">
    </a>
    <button class="btn text-white absolute-top-right set-session" data-key="top-banner" data-value="removed" data-toggle="remove-parent" data-parent=".top-banner">
        <i class="la la-close la-2x"></i>
    </button>
</div>
@endif
<!-- Top Bar -->
<div class="top-navbar bg-white border-bottom border-soft-secondary z-1035">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col">
                <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">
                    @if(get_setting('show_language_switcher') == 'on')
                    <li class="list-inline-item dropdown mr-3" id="lang-change">
                        @php
                            if(Session::has('locale')){
                                $locale = Session::get('locale', Config::get('app.locale'));
                            }
                            else{
                                $locale = 'en';
                            }
                        @endphp
                        <a href="javascript:void(0)" class="dropdown-toggle text-reset py-2" data-toggle="dropdown" data-display="static">
                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" class="mr-2 lazyload" alt="{{ \App\Models\Language::where('code', $locale)->first()->name }}" height="11">
                            <span class="opacity-60">{{ \App\Models\Language::where('code', $locale)->first()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            @foreach (\App\Models\Language::all() as $key => $language)
                                <li>
                                    <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language) active @endif">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                        <span class="language">{{ $language->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                    @if(get_setting('show_currency_switcher') == 'on')
                    <li class="list-inline-item dropdown ml-auto ml-lg-0 mr-0" id="currency-change">
                        @php
                            if(Session::has('currency_code')){
                                $currency_code = Session::get('currency_code');
                            }
                            else{
                                $currency_code = \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
                            }
                        @endphp
                        <a href="javascript:void(0)" class="dropdown-toggle text-reset py-2 opacity-60" data-toggle="dropdown" data-display="static">
                            {{ \App\Models\Currency::where('code', $currency_code)->first()->name }} {{ (\App\Models\Currency::where('code', $currency_code)->first()->symbol) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                            @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)
                                <li>
                                    <a class="dropdown-item @if($currency_code == $currency->code) active @endif" href="javascript:void(0)" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                       @if (get_setting('show_help_line') == 'on' && get_setting('helpline_number'))
                        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                            <a href="tel:{{ get_setting('helpline_number') }}" class="text-reset d-inline-block opacity-60 py-2">
                                <i class="la la-phone"></i>
                                <span>{{ translate('Help line')}}</span>  
                                <span>{{ get_setting('helpline_number') }}</span>    
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="col-5 text-right d-none d-lg-block">
                <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                    <!--@if (get_setting('helpline_number'))-->
                    <!--    <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">-->
                    <!--        <a href="tel:{{ get_setting('helpline_number') }}" class="text-reset d-inline-block opacity-60 py-2">-->
                    <!--            <i class="la la-phone"></i>-->
                    <!--            <span>{{ translate('Help line')}}</span>  -->
                    <!--            <span>{{ get_setting('helpline_number') }}</span>    -->
                    <!--        </a>-->
                    <!--    </li>-->
                    <!--@endif-->
                    @auth
                        @if(isAdmin())
                            @if(get_setting('show_my_panel') == 'on')
                            <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                <a href="{{ route('admin.dashboard') }}" class="text-reset d-inline-block opacity-60 py-2">{{ translate('My Panel')}}</a>
                            </li>
                            @endif
                        @else

                            <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0 dropdown">
                                <a class="dropdown-toggle no-arrow text-reset" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <span class="">
                                        <span class="position-relative d-inline-block">
                                            <i class="las la-bell fs-18"></i>
                                            @if(count(Auth::user()->unreadNotifications) > 0)
                                                <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"></span>
                                            @endif
                                        </span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                        <ul class="list-group list-group-flush" >
                                            @forelse(Auth::user()->unreadNotifications as $notification)
                                                <li class="list-group-item">
                                                    @if($notification->type == 'App\Notifications\OrderNotification')
                                                        @if(Auth::user()->user_type == 'customer')
                                                        <a href="javascript:void(0)" onclick="show_purchase_history_details({{ $notification->data['order_id'] }})" class="text-reset">
                                                            <span class="ml-2">
                                                                {{translate('Order code: ')}} {{$notification->data['order_code']}} {{ translate('has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}
                                                            </span>
                                                        </a>
                                                        @elseif (Auth::user()->user_type == 'seller')
                                                            @if(Auth::user()->id == $notification->data['user_id'])
                                                                <a href="javascript:void(0)" onclick="show_purchase_history_details({{ $notification->data['order_id'] }})" class="text-reset">
                                                                    <span class="ml-2">
                                                                        {{translate('Order code: ')}} {{$notification->data['order_code']}} {{ translate('has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}
                                                                    </span>
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)" onclick="show_order_details({{ $notification->data['order_id'] }})" class="text-reset">
                                                                    <span class="ml-2">
                                                                        {{translate('Order code: ')}} {{$notification->data['order_code']}} {{ translate('has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="py-4 text-center fs-16">
                                                        {{ translate('No notification found') }}
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="text-center border-top">
                                        <a href="{{ route('all-notifications') }}" class="text-reset d-block py-2">
                                            {{translate('View All Notifications')}}
                                        </a>
                                    </div>
                                </div>
                            </li>

                            @if(get_setting('show_my_panel') == 'on')
                            <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                                <a href="{{ route('dashboard') }}" class="text-reset d-inline-block opacity-60 py-2">{{ translate('My Panel')}}</a>
                            </li>
                            @endif
                        @endif
                        @if(get_setting('show_logout') == 'on')
                        <li class="list-inline-item">
                            <a href="{{ route('logout') }}" class="text-reset d-inline-block opacity-60 py-2">{{ translate('Logout')}}</a>
                        </li>
                        @endif
                    @else
                        @if(get_setting('show_my_panel') == 'on')
                        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                            <a href="{{ route('user.login') }}" class="text-reset d-inline-block opacity-60 py-2">{{ translate('Login')}}</a>
                        </li>
                        @endif
                        @if(get_setting('show_logout') == 'on')
                        <li class="list-inline-item mr-3 border-right border-left-0 pr-3 pl-0">
                            <a href="{{ route('user.registration') }}" class="text-reset d-inline-block opacity-60 py-2">{{ translate('Sign Up')}}</a>
                        </li>
                        @endif
                        <!--<li class="list-inline-item">-->
                        <!--    <a href="https://orababa.com/shops/create" class="text-reset d-inline-block opacity-60 py-2">{{ translate('Create Seller Account')}}</a>-->
                        <!--</li>-->
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END Top Bar -->
<header class="@if(get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-white border-bottom shadow-sm">
    <div class="position-relative logo-bar-area z-1" >
        <div class="container">
<div class="d-flex align-items-center mobile-logo-search-row">

                <button type="button" class="d-lg-none mobile-menu-trigger" aria-label="{{ translate('Menu') }}">
                    <i class="las la-bars"></i>
                </button>

                <div class="col-auto col-xl-3 pl-0 pr-3 d-flex align-items-center mobile-logo-wrap">
                    <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-40px h-md-60px" height="40">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="mw-100 h-40px h-md-60px" height="40">
                        @endif
                    </a>

                </div>
                <div class="d-lg-none ml-auto mr-0 mobile-search-trigger">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle" data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>

                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white">
                    <div class="position-relative flex-grow-1">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="border-0 border-lg form-control" id="search" name="keyword" @isset($query)
                                        value="{{ $query }}"
                                    @endisset placeholder="{{translate('I am shopping for...')}}" autocomplete="off">
                                    <div class="input-group-append d-none d-lg-block">
                                        <button class="btn btn-brand front-search-btn" type="submit">
                                            <i class="la la-search la-flip-horizontal fs-18"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100" style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader"><div></div><div></div><div></div></div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>

                <div class="d-none d-lg-block ml-3 mr-0">
                    <div class="" id="wishlist">
                        @include('frontend.partials.wishlist')
                    </div>
                </div>

                <div class="d-none d-lg-block  align-self-stretch ml-3 mr-0" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        @include('frontend.partials.cart')
                    </div>
                </div>

                @if(get_setting('show_header_login_icon') == 'on')
                <div class="d-none d-lg-flex align-items-center ml-3 mr-0">
                    <a href="{{ route('user.login') }}" class="btn btn-brand px-3 header-login-btn font-weight-normal" style="font-weight:400 !important;" title="{{ translate('Login') }}">
                        <i class="las la-sign-in-alt"></i>
                        <span class="ml-1" style="font-weight:400 !important;">{{ translate('Login') }}</span>
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
    @if ( get_setting('header_menu_links') !=  null )
        <div class="border-top border-gray-200 py-1 front-cat-menu-bar d-none d-lg-block">
            <div class="container frontend-menu-container">
                <div class="front-cat-scroll">
                    <ul class="list-inline mb-0 mobile-hor-swipe text-center front-cat-menu" id="frontCategoryMenu">
                        @foreach (json_decode( get_setting('header_menu_labels'), true) as $key => $value)
                        <li class="list-inline-item mr-0">
                            <a href="{{ url(json_decode( get_setting('header_menu_links'), true)[$key]) }}" class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                                {{ translate($value) }}
                            </a>
                        </li>
                        @endforeach
                        @foreach (\App\Models\Category::where('level', 0)->orderBy('order_level', 'desc')->get() as $category)
                        <li class="list-inline-item mr-0 category-nav-element" data-id="{{ $category->id }}">
                            <a href="{{ route('products.category', $category->slug) }}" class="opacity-100 fs-14 px-3 py-2 d-inline-block fw-600 hov-opacity-100 text-reset">
                                {{ $category->getTranslation('name') }}
                                <i class="las la-angle-down fs-11 ml-1 opacity-60"></i>
                            </a>
                            @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0)
                                <div class="sub-cat-menu c-scrollbar-light rounded shadow-lg p-4">
                                    <div class="c-preloader text-center absolute-center">
                                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                                    </div>
                                </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    <button type="button" class="front-cat-arrow front-cat-left" aria-label="Scroll left">
                        <i class="las la-angle-left"></i>
                    </button>
                    <button type="button" class="front-cat-arrow front-cat-right" aria-label="Scroll right">
                        <i class="las la-angle-right"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
</header>

@if ( get_setting('header_menu_links') !=  null )
<div class="d-lg-none mobile-menu-overlay" id="mobileMenuOverlay"></div>
<nav class="d-lg-none mobile-menu-drawer" id="mobileMenuDrawer" aria-label="{{ translate('Mobile menu') }}">
    <div class="mobile-menu-drawer-head d-flex align-items-center justify-content-between">
        <span class="fw-700 fs-16 text-reset">{{ translate('Menu') }}</span>
        <button type="button" class="mobile-menu-close" aria-label="{{ translate('Close') }}">
            <i class="las la-times"></i>
        </button>
    </div>
    <ul class="mobile-menu-list list-unstyled mb-0">
        @foreach (json_decode( get_setting('header_menu_labels'), true) as $key => $value)
        <li class="border-bottom">
            <a href="{{ url(json_decode( get_setting('header_menu_links'), true)[$key]) }}" class="d-flex align-items-center py-3 px-3 text-reset fs-14 fw-600">{{ translate($value) }}</a>
        </li>
        @endforeach
        @foreach (\App\Models\Category::where('level', 0)->orderBy('order_level', 'desc')->get() as $category)
        <li class="border-bottom mobile-menu-has-children" data-id="{{ $category->id }}">
            <a href="{{ route('products.category', $category->slug) }}" class="d-flex align-items-center justify-content-between py-3 px-3 text-reset fs-14 fw-600">
                {{ $category->getTranslation('name') }}
                @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0)
                <i class="las la-angle-down mobile-menu-toggle"></i>
                @endif
            </a>
            @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($category->id))>0)
            <div class="mobile-sub-menu" style="display:none;">
                <div class="p-3 text-center">
                    <i class="las la-spinner la-spin la-2x opacity-60"></i>
                </div>
            </div>
            @endif
        </li>
        @endforeach
    </ul>
</nav>
@endif

<script>
    (function() {
        function initFrontCategoryMenu() {
            if (typeof jQuery === 'undefined') {
                setTimeout(initFrontCategoryMenu, 100);
                return;
            }
            $(document).ready(function() {
                var $menu = $('#frontCategoryMenu');
                if (!$menu.length) {
                    return;
                }
                var $scroll = $menu.closest('.front-cat-scroll');
                var $left = $('.front-cat-left', $scroll);
                var $right = $('.front-cat-right', $scroll);
                var hoverSupported = window.matchMedia ? window.matchMedia('(hover: hover)').matches : ($(window).width() > 991.98);
                var closeTimer = null;

                function updateArrows() {
                    var el = $menu[0];
                    var overflow = el.scrollWidth > el.clientWidth + 1;
                    $scroll.toggleClass('has-overflow', overflow);
                    var max = el.scrollWidth - el.clientWidth;
                    var atLeft = el.scrollLeft <= 0;
                    var atRight = el.scrollLeft >= max - 1;
                    $left.css('opacity', (overflow && !atLeft) ? 1 : 0.35);
                    $right.css('opacity', (overflow && !atRight) ? 1 : 0.35);
                }

                $left.on('click', function() {
                    $menu.stop().animate({ scrollLeft: '-=' + Math.round($menu[0].clientWidth * 0.75) }, 220);
                });
                $right.on('click', function() {
                    $menu.stop().animate({ scrollLeft: '+=' + Math.round($menu[0].clientWidth * 0.75) }, 220);
                });

                $menu.on('scroll', updateArrows);
                $(window).on('resize', updateArrows);
                setTimeout(updateArrows, 100);

                function positionPanel($li, $panel) {
                    var r = $li[0].getBoundingClientRect();
                    var pw = $panel[0].getBoundingClientRect().width || 780;
                    var ph = $panel[0].offsetHeight;
                    var left = Math.max(8, Math.min(r.left, window.innerWidth - pw - 8));
                    var top = r.bottom;
                    if (top + ph > window.innerHeight - 8) {
                        top = Math.max(8, window.innerHeight - ph - 8);
                    }
                    $panel.css({ top: top + 'px', left: left + 'px' });
                }

                function loadPanelContent($li, $panel) {
                    if ($panel.hasClass('loaded')) {
                        return;
                    }
                    $panel.addClass('loaded');
                    var csrf = ($('meta[name="csrf-token"]').length) ? $('meta[name="csrf-token"]').attr('content') : (AIZ.data ? AIZ.data.csrf : '');
                    $.post('{{ route('category.elements') }}', { _token: csrf, id: $li.data('id') }, function(data) {
                        $panel.html(data);
                        startProductSlider($panel);
                        positionPanel($li, $panel);
                    });
                }

                function startProductSlider($panel) {
                    var $slides = $panel.find('.category-product-slide');
                    if ($slides.length < 2) {
                        return;
                    }
                    var interval = Number($panel.find('.category-product-slides').data('interval')) || 5000;
                    var current = 0;
                    setInterval(function() {
                        $slides.eq(current).removeClass('active');
                        current = (current + 1) % $slides.length;
                        $slides.eq(current).addClass('active');
                    }, interval);
                }

                function openPanel($li) {
                    var $panel = $li.find('.sub-cat-menu');
                    if (!$panel.length) {
                        return;
                    }
                    clearTimeout(closeTimer);
                    loadPanelContent($li, $panel);
                    $menu.find('li.open').removeClass('open');
                    $li.addClass('open');
                    positionPanel($li, $panel);
                    if (!$panel.data('bound-obs')) {
                        if (window.MutationObserver && $panel[0]) {
                            var obs = new MutationObserver(function() {
                                positionPanel($li, $panel);
                            });
                            obs.observe($panel[0], { childList: true, subtree: true });
                        }
                        $panel.data('bound-obs', 1);
                    }
                }

                function closePanels() {
                    $menu.find('li.open').removeClass('open');
                }

                function scheduleClose() {
                    clearTimeout(closeTimer);
                    closeTimer = setTimeout(function() {
                        closePanels();
                    }, 150);
                }

                if (hoverSupported) {
                    $menu.find('> li.category-nav-element').on('mouseenter', function() {
                        openPanel($(this));
                    }).on('mouseleave', function() {
                        scheduleClose();
                    });
                } else {
                    $menu.find('> li.category-nav-element > a').on('click', function(e) {
                        e.preventDefault();
                        var $li = $(this).closest('li.category-nav-element');
                        if ($li.hasClass('open')) {
                            closePanels();
                            return;
                        }
                        openPanel($li);
                    });
                }

                $(document).on('click touchstart', function(e) {
                    if (!$(e.target).closest('.front-cat-scroll').length) {
                        closePanels();
                    }
                });
            });
        }
        initFrontCategoryMenu();
    })();

    (function() {
        function initMobileMenuDrawer() {
            if (typeof jQuery === 'undefined') {
                setTimeout(initMobileMenuDrawer, 100);
                return;
            }
            $(document).ready(function() {
                var $drawer = $('#mobileMenuDrawer');
                var $overlay = $('#mobileMenuOverlay');
                if (!$drawer.length || !$overlay.length) {
                    return;
                }

                function openDrawer() {
                    $drawer.addClass('open');
                    $overlay.addClass('open');
                    $('body').css('overflow', 'hidden');
                }

                function closeDrawer() {
                    $drawer.removeClass('open');
                    $overlay.removeClass('open');
                    $('body').css('overflow', '');
                }

                $('.mobile-menu-trigger').on('click', openDrawer);
                $overlay.on('click', closeDrawer);
                $('.mobile-menu-close').on('click', closeDrawer);
                $(document).on('keyup', function(e) {
                    if (e.key === 'Escape') {
                        closeDrawer();
                    }
                });

                function loadMobileSubMenu($li) {
                    var $sub = $li.find('.mobile-sub-menu').first();
                    if (!$sub.length || $sub.hasClass('loaded')) {
                        return;
                    }
                    $sub.addClass('loaded');
                    var csrf = ($('meta[name="csrf-token"]').length) ? $('meta[name="csrf-token"]').attr('content') : (AIZ.data ? AIZ.data.csrf : '');
                    $.post('{{ route('category.elements') }}', { _token: csrf, id: $li.data('id') }, function(data) {
                        $sub.html(data);
                    });
                }

                $drawer.find('.mobile-menu-has-children > a').on('click', function(e) {
                    var $li = $(this).closest('.mobile-menu-has-children');
                    if (!$li.length || !$li.find('.mobile-sub-menu').length) {
                        return;
                    }
                    e.preventDefault();
                    var wasOpen = $li.hasClass('open');
                    $drawer.find('.mobile-menu-has-children.open').each(function() {
                        $(this).removeClass('open');
                        $(this).find('.mobile-sub-menu').slideUp(200);
                    });
                    if (!wasOpen) {
                        loadMobileSubMenu($li);
                        $li.addClass('open');
                        $li.find('.mobile-sub-menu').slideDown(220);
                    }
                });
            });
        }
        initMobileMenuDrawer();
    })();
</script>
