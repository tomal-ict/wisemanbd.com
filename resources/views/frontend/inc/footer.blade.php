<!-- WISEMAN LUXURY FOOTER -->
<style>
.wiseman-footer {
    --navy: #2a0f45;
    --navyink: #3b2557;
    --f2: #ece3f6;
    --f3: #e2d6f0;
    --p: #6a2c9e;
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--navyink);
}
.wiseman-footer .foot {
    background: var(--f2);
    color: var(--navyink);
    padding: 48px 0 42px;
}
.wiseman-footer .wrap {
    width: 100%;
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 clamp(16px, 2.4vw, 40px);
}
.wiseman-footer .fgrid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.25fr;
    gap: 40px;
}
.wiseman-footer .fgrid h4 {
    font-size: 17px;
    letter-spacing: .4px;
    color: var(--navy);
    font-weight: 700;
    margin-bottom: 22px;
    padding-bottom: 6px;
    position: relative;
}
.wiseman-footer .fgrid h4:after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 34px;
    height: 2px;
    background: var(--navy);
}
.wiseman-footer .fgrid ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.wiseman-footer .fgrid li {
    font-size: 14.5px;
    margin-bottom: 12px;
    color: var(--navyink);
}
.wiseman-footer .fgrid li a {
    color: inherit;
    text-decoration: none;
    transition: .2s;
}
.wiseman-footer .fgrid li a:hover {
    color: var(--p);
    text-decoration: underline;
}
.wiseman-footer .brand .logo img {
    height: 42px;
    width: auto;
    mix-blend-mode: multiply;
}
.wiseman-footer .brand .tag {
    font-family: 'Cormorant Garamond', serif;
    font-style: italic;
    color: var(--navyink);
    font-size: 24px;
    margin: 12px 0 10px;
    font-weight: 600;
}
.wiseman-footer .brand p {
    color: var(--navyink);
    font-size: 14px;
    max-width: 380px;
    line-height: 1.5;
    margin-bottom: 16px;
}
.wiseman-footer .social {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
    font-size: 14.5px;
    color: var(--navy);
}
.wiseman-footer .social b {
    font-weight: 600;
    margin-right: 2px;
}
.wiseman-footer .social a {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid var(--navy);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--navy);
    transition: .2s;
}
.wiseman-footer .social a:hover {
    background: var(--navy);
    color: #fff;
}
.wiseman-footer .crow {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    margin-bottom: 18px;
    font-size: 14px;
    color: var(--navyink);
}
.wiseman-footer .crow .ci {
    color: var(--navy);
    margin-top: 2px;
    flex: none;
}
.wiseman-footer .crow b {
    display: block;
    font-weight: 700;
    color: var(--navy);
    font-size: 14.5px;
}
.wiseman-footer .crow div span {
    display: block;
}
.wiseman-footer .accept {
    background: var(--f3);
    border-top: 1px solid #d3c5e6;
    padding: 16px 0;
}
.wiseman-footer .acin {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.wiseman-footer .pay {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.wiseman-footer .pay > span {
    font-size: 13.5px;
    letter-spacing: 1.5px;
    color: var(--navy);
    margin-right: 8px;
    font-weight: 600;
}
.wiseman-footer .pay img {
    height: 36px;
    width: auto;
    border-radius: 6px;
    display: block;
    background: #fff;
    padding: 3px 6px;
    border: 1px solid #e0d5ec;
}
.wiseman-footer .plinks {
    display: flex;
    align-items: center;
    gap: 0;
    flex-wrap: wrap;
    justify-content: center;
    row-gap: 6px;
}
.wiseman-footer .plinks a {
    border: 0;
    padding: 0 16px;
    color: var(--navyink);
    font-size: 13.5px;
    border-left: 1.5px solid #a392b8;
    text-decoration: none;
    font-weight: 500;
}
.wiseman-footer .plinks a:first-child {
    border-left: 0;
}
.wiseman-footer .plinks a:last-child {
    padding-right: 0;
}
.wiseman-footer .copy-bottom {
    background: var(--navy);
    color: #fff;
    font-size: 13.5px;
    padding: 18px 0;
}
.wiseman-footer .copy-bottom .wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

@media (max-width: 820px) {
    .wiseman-footer .fgrid {
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .wiseman-footer .acin, .wiseman-footer .copy-bottom .wrap {
        flex-direction: column;
        text-align: center;
        justify-content: center;
    }
}
@media (max-width: 560px) {
    .wiseman-footer .fgrid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}
</style>

<div class="wiseman-footer">
    <footer class="foot">
        <div class="wrap">
            <div class="fgrid">
                <div class="brand">
                    <a class="logo" href="{{ route('home') }}">
                        @if(get_setting('footer_logo') != null)
                            <img src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}">
                        @elseif(get_setting('header_logo') != null)
                            <img src="{{ uploaded_asset(get_setting('header_logo')) }}" alt="{{ env('APP_NAME') }}">
                        @else
                            <img src="{{ static_asset('assets/img/wiseman/logo.png') }}" alt="{{ env('APP_NAME') }}">
                        @endif
                    </a>
                    <div class="tag">Crafted for the Wise.</div>
                    @if(get_setting('about_us_description', null, App::getLocale()) != null)
                        <div class="about-text" style="color:var(--navyink); font-size:14px; max-width:380px; line-height:1.5; margin-bottom:16px;">
                            {!! get_setting('about_us_description', null, App::getLocale()) !!}
                        </div>
                    @else
                        <p>High-end menswear made with better fabrics and finer finishing. Quality you can feel in every detail.</p>
                    @endif
                    @if(get_setting('show_social_links', 'on') == 'on')
                    <div class="social">
                        <b>Get in touch:</b>
                        @if(get_setting('facebook_link') != null)
                            <a href="{{ get_setting('facebook_link') }}" target="_blank" rel="noopener" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M13.5 21v-7.6h2.6l.4-3h-3V8.5c0-.9.3-1.5 1.5-1.5h1.6V4.3c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 3.9v2.3H8v3h2.5V21h3z"/></svg>
                            </a>
                        @endif
                        @if(get_setting('instagram_link') != null)
                            <a href="{{ get_setting('instagram_link') }}" target="_blank" rel="noopener" aria-label="Instagram">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.4" cy="6.6" r="1" fill="currentColor" stroke="none"/></svg>
                            </a>
                        @endif
                        @if(get_setting('youtube_link') != null)
                            <a href="{{ get_setting('youtube_link') }}" target="_blank" rel="noopener" aria-label="YouTube">
                                <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5.2 3-5.2 3z"/></svg>
                            </a>
                        @endif
                        @if(get_setting('twitter_link') != null)
                            <a href="{{ get_setting('twitter_link') }}" target="_blank" rel="noopener" aria-label="Twitter">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                            </a>
                        @endif
                        @if(get_setting('linkedin_link') != null)
                            <a href="{{ get_setting('linkedin_link') }}" target="_blank" rel="noopener" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                            </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Link Widget One --}}
                <div>
                    <h4>{{ get_setting('widget_one', translate('Company'), App::getLocale()) }}</h4>
                    @php
                        $w1_raw = get_setting('widget_one_labels', null, App::getLocale());
                        if (empty($w1_raw)) {
                            $w1_raw = get_setting('widget_one_labels');
                        }
                        $w1_labels = !empty($w1_raw) ? json_decode($w1_raw, true) : [];
                        $w1_links = get_setting('widget_one_links') ? json_decode(get_setting('widget_one_links'), true) : [];
                    @endphp
                    <ul>
                        @if(!empty($w1_labels) && is_array($w1_labels))
                            @foreach($w1_labels as $idx => $label)
                                @php
                                    $link = isset($w1_links[$idx]) ? $w1_links[$idx] : '#';
                                    if (!empty($link) && !Str::startsWith($link, ['http://', 'https://', 'tel:', 'mailto:', '#']) && !Str::startsWith($link, '/')) {
                                        $link = '/' . $link;
                                    }
                                @endphp
                                <li><a href="{{ $link }}">{{ $label }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/about-us') }}">About WISEMAN</a></li>
                            <li><a href="{{ url('/our-showroom') }}">Our Showroom</a></li>
                            <li><a href="{{ url('/become-a-reseller') }}">Become a Reseller</a></li>
                            <li><a href="{{ url('/careers') }}">Careers</a></li>
                            <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                        @endif
                    </ul>
                </div>

                {{-- Link Widget Two --}}
                <div>
                    <h4>{{ get_setting('widget_two', translate('Help & Support'), App::getLocale()) }}</h4>
                    @php
                        $w2_raw = get_setting('widget_two_labels', null, App::getLocale());
                        if (empty($w2_raw)) {
                            $w2_raw = get_setting('widget_two_labels');
                        }
                        $w2_labels = !empty($w2_raw) ? json_decode($w2_raw, true) : [];
                        $w2_links = get_setting('widget_two_links') ? json_decode(get_setting('widget_two_links'), true) : [];
                    @endphp
                    <ul>
                        @if(!empty($w2_labels) && is_array($w2_labels))
                            @foreach($w2_labels as $idx => $label)
                                @php
                                    $link = isset($w2_links[$idx]) ? $w2_links[$idx] : '#';
                                    if (!empty($link) && !Str::startsWith($link, ['http://', 'https://', 'tel:', 'mailto:', '#']) && !Str::startsWith($link, '/')) {
                                        $link = '/' . $link;
                                    }
                                @endphp
                                <li><a href="{{ $link }}">{{ $label }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('orders.track') }}">Track Your Order</a></li>
                            <li><a href="{{ url('/delivery-info') }}">Delivery Information</a></li>
                            <li><a href="{{ url('/returnpolicy') }}">Exchange &amp; Return</a></li>
                            <li><a href="{{ url('/size-guide') }}">Size Guide</a></li>
                            <li><a href="{{ url('/faq') }}">FAQ</a></li>
                        @endif
                    </ul>
                </div>

                {{-- Contact Info Widget --}}
                <div class="contact">
                    <h4>{{ translate('CONTACT') }}</h4>
                    @if(get_setting('contact_address', null, App::getLocale()) != null)
                        <div class="crow">
                            <span class="ci"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/><circle cx="12" cy="10" r="3"/></svg></span>
                            <div>Address<span>{{ get_setting('contact_address', null, App::getLocale()) }}</span></div>
                        </div>
                    @endif
                    <div class="crow">
                        <span class="ci"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.6a1 1 0 0 1-.25 1z"/></svg></span>
                        <div>Call / WhatsApp<b><a href="tel:{{ get_setting('contact_phone', '+8801811762186') }}" style="color:inherit;text-decoration:none;">{{ get_setting('contact_phone', '+8801811762186') }}</a></b></div>
                    </div>
                    <div class="crow">
                        <span class="ci"><svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M3 5h18a1 1 0 0 1 1 1v.5l-10 6.5L2 6.5V6a1 1 0 0 1 1-1zm19 3.9V18a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V8.9l10 6.5z"/></svg></span>
                        <div>Email<b><a href="mailto:{{ get_setting('contact_email', 'info@wisemanbd.com') }}" style="color:inherit;text-decoration:none;">{{ get_setting('contact_email', 'info@wisemanbd.com') }}</a></b></div>
                    </div>
                    <div class="crow">
                        <span class="ci"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                        <div>Open Hours<span>10:00 AM – 8:00 PM</span></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="accept">
        <div class="wrap acin">
            <div class="pay">
                <span>WE ACCEPT</span>
                @if(get_setting('payment_method_images') != null)
                    @foreach (explode(',', get_setting('payment_method_images')) as $key => $element)
                        <img src="{{ uploaded_asset($element) }}" alt="Payment method">
                    @endforeach
                @else
                    <img src="{{ static_asset('assets/img/wiseman/bkash.png') }}" alt="bKash">
                    <img src="{{ static_asset('assets/img/wiseman/nagad.png') }}" alt="Nagad">
                    <img src="{{ static_asset('assets/img/wiseman/visa.png') }}" alt="Visa">
                    <img src="{{ static_asset('assets/img/wiseman/mastercard.png') }}" alt="Mastercard">
                @endif
            </div>
            <nav class="plinks">
                <a href="{{ url('/privacypolicy') }}">Privacy Policy</a>
                <a href="{{ url('/terms') }}">Terms &amp; Conditions</a>
                <a href="{{ url('/returnpolicy') }}">Refund Policy</a>
            </nav>
        </div>
    </div>

    <!-- BOTTOM COPYRIGHT & DEVELOPER BAR -->
    <div class="copy-bottom">
        <div class="wrap">
            <div class="text-left" current-verison="{{ get_setting('current_version') }}">
                @if(get_setting('frontend_copyright_text', null, App::getLocale()) != null)
                    {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                @else
                    All Rights Reserved By WISEMAN &amp; A1systems BD
                @endif
            </div>
            <div class="text-right">
                <span>Website Designed By: <a href="https://tomal-ict.com" target="_blank" rel="noopener" style="color: #fff; text-decoration: underline; font-weight: 600;">Tomal Krishna Howlader</a></span>
            </div>
        </div>
    </div>
</div>

<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom bg-white shadow-lg border-top rounded-top" style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important; max-width: 100vw; overflow: hidden;">
    <div class="row align-items-center gutters-5 mx-0">
        <div class="col">
            <a href="{{ route('home') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-home fs-20 opacity-60 {{ areActiveRoutes(['home'],'opacity-100 text-primary')}}"></i>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['home'],'opacity-100 fw-600')}}">{{ translate('Home') }}</span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('categories.all') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="las la-list-ul fs-20 opacity-60 {{ areActiveRoutes(['categories.all'],'opacity-100 text-primary')}}"></i>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['categories.all'],'opacity-100 fw-600')}}">{{ translate('Categories') }}</span>
            </a>
        </div>
        @php
            if(auth()->user() != null) {
                $user_id = Auth::user()->id;
                $cart = \App\Models\Cart::where('user_id', $user_id)->get();
            } else {
                $temp_user_id = Session()->get('temp_user_id');
                if($temp_user_id) {
                    $cart = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
                }
            }
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="align-items-center bg-primary border border-white border-width-4 d-flex justify-content-center position-relative rounded-circle size-50px" style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                    <i class="las la-shopping-bag la-2x text-white"></i>
                </span>
                <span class="d-block mt-1 fs-10 fw-600 opacity-60 {{ areActiveRoutes(['cart'],'opacity-100 fw-600')}}">
                    {{ translate('Cart') }}
                    @php
                        $count = (isset($cart) && count($cart)) ? count($cart) : 0;
                    @endphp
                    (<span class="cart-count">{{$count}}</span>)
                </span>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('all-notifications') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-inline-block position-relative px-2">
                    <i class="las la-bell fs-20 opacity-60 {{ areActiveRoutes(['all-notifications'],'opacity-100 text-primary')}}"></i>
                    @if(Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 7px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block fs-10 fw-600 opacity-60 {{ areActiveRoutes(['all-notifications'],'opacity-100 fw-600')}}">{{ translate('Notifications') }}</span>
            </a>
        </div>
        <div class="col">
        @if (Auth::check())
            @if(isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        @if(Auth::user()->photo != null)
                            <img src="{{ custom_asset(Auth::user()->avatar_original)}}" class="rounded-circle size-20px">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle size-20px">
                        @endif
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                </a>
            @else
                <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3 mobile-side-nav-thumb" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                    <span class="d-block mx-auto">
                        @if(Auth::user()->photo != null)
                            <img src="{{ custom_asset(Auth::user()->avatar_original)}}" class="rounded-circle size-20px">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle size-20px">
                        @endif
                    </span>
                    <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
                </a>
            @endif
        @else
            <a href="{{ route('user.login') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-block mx-auto">
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="rounded-circle size-20px">
                </span>
                <span class="d-block fs-10 fw-600 opacity-60">{{ translate('Account') }}</span>
            </a>
        @endif
        </div>
    </div>
</div>
@if (Auth::check() && !isAdmin())
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif

    <div class="modal fade" id="guest-modal">
        <div class="modal-dialog modal-lg modal-dialog-zoom order-confirmation-dialog">
            <div class="modal-content">


                <div class="modal-header">
                    <h6 class="modal-title fw-600">Order confirmation</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>

        <form action="{{ route('guest.payment.checkout') }}" method="post">
            @csrf
            <input type="hidden" name="buy_now_cart_id" id="guest_buy_now_cart_id" value="">
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

    @php
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
    @endphp
    @if ($guest_carts->count() > 0)
        @include('frontend.partials.cart_coupon_box', ['carts' => $guest_carts, 'prefix' => 'guest'])
    @endif
    
        <div class="form-group">
            <label><strong>Your Name*</strong></label>
            <input class="form-control" type="text" placeholder="Your Name*" name="name" value="" required>
            
            <input class="form-control" type="hidden" name="email" value="@if(Auth()->check()){{Auth()->user()->email}} @else customer@gmail.com @endif">
        </div>
        
        <div class="form-group">
            <label><strong>Full Address*</strong></label>
            <input class="form-control" type="text" placeholder="Full Address*" name="address" value="" required>
        </div>
        
        <div class="form-group">
            <label><strong>Mobile Number * </strong></label>
            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Mobile Number *" pattern="[0-9]{11}" required title="Example: 01xxxxxxxxxx">
            
        </div>
        
        @if($guest_delivery_area_enabled)
        <div class="guest-checkout-section">
            <h6 class="guest-checkout-section-title">Select Delivery Area</h6>
            <div class="guest-choice-list">
                @foreach($guest_delivery_areas as $guest_delivery_area)
                    <label class="guest-choice-card">
                        <input type="radio" name="city" value="{{ $guest_delivery_area['label'] }}" @if($guest_delivery_area['label'] === $guest_default_area) checked @endif required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-truck guest-choice-icon"></i>
                        <span class="guest-choice-label">{{ $guest_delivery_area['label'] }}</span>
                        <strong class="guest-choice-price">৳{{ $guest_delivery_area['charge'] }}</strong>
                    </label>
                @endforeach
            </div>
            <small class="d-none text-success fw-600 mt-2" id="guest-free-delivery-note"></small>
        </div>
        @else
            <input type="hidden" name="city" value="">
        @endif

        <div class="guest-checkout-section mb-0">
            <h6 class="guest-checkout-section-title">Payment Options</h6>
            <input type="hidden" name="payment_option" id="guest-payment-option">
            <div class="guest-choice-list">
                @if($guest_cod_available)
                    <label class="guest-choice-card">
                        <input type="radio" name="guest_payment_type" value="cash_on_delivery" required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-money-bill-wave guest-choice-icon"></i>
                        <span class="guest-choice-label">Cash on Delivery</span>
                        <strong class="guest-choice-price guest-payment-total">৳0</strong>
                    </label>
                @endif
                @if(!empty($guest_online_payment_options))
                    <label class="guest-choice-card">
                        <input type="radio" name="guest_payment_type" value="online" required>
                        <span class="guest-choice-radio"></span>
                        <i class="las la-credit-card guest-choice-icon"></i>
                        <span class="guest-choice-label">Pay Total Amount</span>
                        <strong class="guest-choice-price guest-payment-total">৳0</strong>
                    </label>
                @endif
            </div>
            <div class="guest-online-payment-options d-none" id="guest-online-payment-options">
                <p class="mb-2 fw-600 fs-13">Select Payment Method</p>
                <div class="guest-online-grid">
                    @foreach($guest_online_payment_options as $payment_option => $payment_label)
                        <label class="guest-online-payment-choice">
                            <input type="radio" name="guest_online_payment_option" value="{{ $payment_option }}">
                            <span class="guest-online-box">
                                @if(isset($guest_payment_icons[$payment_option]) && file_exists(public_path('assets/img/' . $guest_payment_icons[$payment_option])))
                                    <img src="{{ static_asset('assets/img/' . $guest_payment_icons[$payment_option]) }}" alt="{{ $payment_label }}" class="guest-online-icon">
                                @endif
                                <span class="guest-online-name">{{ $payment_label }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
            @if(empty($guest_payment_options))
                <small class="text-danger">No payment method is currently active.</small>
            @endif
        </div>
        
        </div>
        
        <div class="modal-footer">
            <input type="submit" id="guest-payment-submit" class="btn btn-danger btn-block btn-sm" value="Confirm Order" @if(empty($guest_payment_options)) disabled @endif>
        </div>
        
    </form>
            </div>
        </div>
    </div>

    
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ static_asset('assets/js/jquryui.js') }}"></script>
<script type="text/javascript">

    function guest_cart_update(cardid, qty){

        event.preventDefault();

        if(qty > 0){
            $.ajax({
                url : "{{Route('cartupdate.cart_update')}}",
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

    var guestDeliveryAreas = @json($guest_delivery_areas);
    var guestDefaultArea = @json($guest_default_area ?? null);
    var guestFreeDelivery = @json($guest_free_delivery);

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
                url : "{{Route('cartdelete.cart_delete')}}",
                method : "get",
                data : {
                    id : id
                }
        });

        $("#guest_remove_"+id).remove();
        $(".cart-count").text($(".addItem").length);
        guest_refresh_order_summary();
        if($(".addItem").length == 0){
            window.location = "{{url('/')}}";
        }
    }

    function guest_card(e, buyNowCartId){
        if (e && e.preventDefault) { e.preventDefault(); }
        else if (typeof event !== 'undefined' && event && event.preventDefault) { event.preventDefault(); }

        if (buyNowCartId) {
            $('#guest_buy_now_cart_id').val(buyNowCartId);
        } else {
            $('#guest_buy_now_cart_id').val('');
        }

        $('#guest-modal').modal();

        var getCardUrl = "{{Route('getguestcard.getguest_card')}}";
        if (buyNowCartId) {
            getCardUrl += (getCardUrl.indexOf('?') >= 0 ? '&' : '?') + 'buy_now_cart_id=' + encodeURIComponent(buyNowCartId);
        }

        $(document).off('click', '#guest-coupon-apply').on('click', '#guest-coupon-apply', function(){
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                code: $('#apply-coupon-form-guest input[name="code"]').val(),
                owner_id: $('#apply-coupon-form-guest input[name="owner_id"]').val()
            };
            $.ajax({
                type: 'POST',
                url: '{{ route('checkout.apply_coupon_code') }}',
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
                url: '{{ route('checkout.remove_coupon_code') }}',
                data: data,
                success: function(d){
                    if (d.guest_box_html) { $('#coupon_box_guest').replaceWith(d.guest_box_html); }
                    if (d.response_message) { AIZ.plugins.notify('success', d.response_message.message); }
                    guest_refresh_order_summary();
                }
            });
        });

        $.ajax({
                url : getCardUrl,
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
                url: '{{ route('incomplete_order.store') }}',
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
