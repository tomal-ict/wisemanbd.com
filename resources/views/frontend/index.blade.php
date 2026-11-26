@extends('frontend.layouts.app')

@section('content')

<style>
:root {
    --p: #6a2c9e;
    --pd: #2a0f45;
    --bg: #ffffff;
    --ink: #1a1a1f;
    --muted: #6b6b75;
    --tint: #f7f4fb;
    --chip: #f4eefa;
    --red: #f0435a;
    --line: #e8e4ee;
    --navy: #2a0f45;
    --navyink: #3b2557;
    --f1: #f3edf9;
    --f2: #ece3f6;
    --f3: #e2d6f0;
}

.wiseman-home {
    color: var(--ink);
    font-size: 14px;
    line-height: 1.4;
    font-family: 'Inter', system-ui, sans-serif;
}

.wiseman-home .wrap {
    width: 100%;
    max-width: 1440px;
    margin-left: auto;
    margin-right: auto;
    padding: 0 clamp(16px, 2.4vw, 40px);
}

/* Sections */
.wiseman-home .sec {
    padding-top: 60px;
}
.wiseman-home .stitle {
    font-family: 'Cormorant Garamond', serif;
    font-weight: 700;
    font-size: 40px;
    line-height: 1.1;
    letter-spacing: .2px;
    color: var(--p);
    margin-bottom: 4px;
}
.wiseman-home .stag {
    font-size: 15px;
    font-weight: 400;
    color: var(--muted);
    margin-top: 4px;
    margin-bottom: 22px;
}
.wiseman-home .sechead {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 22px;
    gap: 16px;
    flex-wrap: wrap;
}
.wiseman-home .vall {
    margin-left: auto;
    font-size: 13px;
    font-weight: 600;
    color: var(--p);
    letter-spacing: .4px;
    border-bottom: 1.5px solid var(--p);
    padding-bottom: 2px;
    text-decoration: none;
    transition: .2s;
}
.wiseman-home .vall:hover {
    color: var(--pd);
    border-color: var(--pd);
}
.wiseman-home .arrows {
    display: flex;
    gap: 8px;
}
.wiseman-home .arrows button {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid #ddd;
    background: #fff;
    font-size: 18px;
    line-height: 1;
    color: #222;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .2s;
}
.wiseman-home .arrows button:hover {
    background: var(--p);
    color: #fff;
    border-color: var(--p);
}
.wiseman-home .arrows.dark button {
    background: transparent;
    border-color: rgba(255,255,255,.45);
    color: #fff;
}
.wiseman-home .arrows.dark button:hover {
    background: #fff;
    color: var(--pd);
}

/* Category Grid */
.wiseman-home .sgrid {
    display: grid;
    gap: 18px;
}
.wiseman-home .sgrid.c3 { grid-template-columns: repeat(3, 1fr); }
.wiseman-home .sgrid.c4 { grid-template-columns: repeat(4, 1fr); }
.wiseman-home .scard {
    color: var(--ink);
    background: #fff;
    border-radius: 6px;
    overflow: hidden;
    display: block;
    position: relative;
    box-shadow: 0 2px 12px rgba(40,20,70,.08);
    transition: transform .3s, box-shadow .3s;
    text-decoration: none;
}
.wiseman-home .scard:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(42,15,69,.15);
}
.wiseman-home .simg {
    aspect-ratio: 4/5;
    overflow: hidden;
    background: #f7f7f9;
}
.wiseman-home .simg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.wiseman-home .scard:hover .simg img {
    transform: scale(1.04);
}
.wiseman-home .sfoot {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 60px 20px 18px;
    background: linear-gradient(transparent, rgba(26,8,44,.85));
    color: #fff;
    font-family: 'Cormorant Garamond', serif;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
.wiseman-home .sfoot em {
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    font-weight: 500;
    font-style: normal;
    letter-spacing: .6px;
    border-bottom: 1px solid rgba(255,255,255,.7);
    padding-bottom: 2px;
}

/* Product Cards & Track */
.wiseman-home .track {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    padding-bottom: 6px;
    max-width: 100%;
}
.wiseman-home .track::-webkit-scrollbar { display: none; }
.wiseman-home .pcard {
    color: var(--ink);
    flex: 0 0 max(210px, calc((100% - (var(--n,5) - 1)*16px)/var(--n,5)));
    background: #fff;
    border-radius: 4px;
    border: 1px solid var(--line);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: .3s;
}
.wiseman-home .pcard:hover {
    border-color: var(--p);
    box-shadow: 0 10px 28px rgba(42,15,69,.12);
}
.wiseman-home .pimg {
    position: relative;
    aspect-ratio: 1/1;
    background: #fdfdfd;
    overflow: hidden;
}
.wiseman-home .pimg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s;
}
.wiseman-home .pcard:hover .pimg img {
    transform: scale(1.03);
}
.wiseman-home .badge-disc {
    position: absolute;
    top: 10px;
    right: 0;
    background: var(--red);
    color: #fff;
    font-size: 10px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 3px 0 0 3px;
    z-index: 2;
}
.wiseman-home .wish {
    position: absolute;
    right: 10px;
    bottom: 10px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 0;
    background: #fff;
    color: var(--p);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 6px rgba(0,0,0,.2);
    cursor: pointer;
    z-index: 2;
    transition: .2s;
}
.wiseman-home .wish:hover {
    background: var(--p);
    color: #fff;
}
.wiseman-home .pbody {
    padding: 12px 12px 6px;
    flex: 1;
}
.wiseman-home .pbody h3 {
    font-size: 13.5px;
    font-weight: 500;
    line-height: 1.35;
    min-height: 36px;
    color: #1a1a1f;
    margin: 0;
}
.wiseman-home .pbody h3 a {
    color: inherit;
    text-decoration: none;
}
.wiseman-home .pbody h3 a:hover {
    color: var(--p);
}
.wiseman-home .price {
    margin-top: 6px;
    display: flex;
    align-items: baseline;
    gap: 6px;
}
.wiseman-home .price b {
    font-size: 16px;
    font-weight: 600;
    color: var(--pd);
}
.wiseman-home .price s {
    font-size: 12px;
    color: #999;
}
.wiseman-home .sizes {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 8px;
    min-height: 22px;
}
.wiseman-home .sizes span {
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 9.5px;
    padding: 2px 6px;
    color: #444;
}
.wiseman-home .actions {
    display: flex;
    gap: 8px;
    padding: 12px;
}
.wiseman-home .cartbtn {
    width: 38px;
    height: 38px;
    border: 1.3px solid var(--p);
    background: #fff;
    border-radius: 3px;
    color: var(--p);
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
    cursor: pointer;
    transition: .2s;
}
.wiseman-home .cartbtn:hover {
    background: var(--p);
    color: #fff;
}
.wiseman-home .buy {
    flex: 1;
    height: 38px;
    border: 1.3px solid var(--pd);
    background: var(--pd);
    color: #fff;
    border-radius: 3px;
    font-weight: 600;
    font-size: 12px;
    letter-spacing: .6px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    transition: .2s;
}
.wiseman-home .buy:hover {
    background: var(--p);
    border-color: var(--p);
    color: #fff;
}
.wiseman-home .bgrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

/* Flash Sale / Mega Deals */
.wiseman-home .flash {
    margin-top: 70px;
    background: linear-gradient(120deg, #2e114b 0%, #682b9b 100%);
    padding: 50px 0 46px;
    color: #fff;
}
.wiseman-home .flabel {
    display: inline-block;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 500;
    color: #fff;
    opacity: .85;
    margin-bottom: 6px;
}
.wiseman-home .flash .stitle { color: #fff; }
.wiseman-home .flash .stag { color: rgba(255,255,255,.85); }
.wiseman-home .fhead {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.wiseman-home .fleft {
    display: flex;
    align-items: center;
    gap: 36px;
    flex-wrap: wrap;
}
.wiseman-home .cd {
    display: flex;
    gap: 8px;
}
.wiseman-home .cd div {
    background: rgba(255,255,255,.18);
    border-radius: 6px;
    width: 48px;
    padding: 7px 0 6px;
    text-align: center;
}
.wiseman-home .cd b {
    display: block;
    font-size: 19px;
    font-weight: 600;
    line-height: 1;
}
.wiseman-home .cd small {
    font-size: 8px;
    letter-spacing: 1px;
    opacity: .85;
}

/* Delivery Banner */
.wiseman-home .dbanner {
    margin-top: 60px;
    border-radius: 4px;
    overflow: hidden;
    line-height: 0;
}
.wiseman-home .dbanner img {
    width: 100%;
    height: auto;
    aspect-ratio: 1920/540;
    object-fit: cover;
}

/* Reviews */
.wiseman-home .reviews {
    background: var(--tint);
    box-shadow: 0 0 0 100vmax var(--tint);
    clip-path: inset(0 -100vmax);
    margin-top: 70px;
    padding-top: 60px;
    padding-bottom: 60px;
}
.wiseman-home .rgrid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.wiseman-home .rcard {
    background: #fff;
    border-radius: 4px;
    border: 1px solid var(--line);
    padding: 22px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.wiseman-home .stars {
    color: #f2a516;
    font-size: 16px;
    letter-spacing: 2px;
    line-height: 1;
}
.wiseman-home .rcard p {
    flex: 1;
    font-size: 13.5px;
    line-height: 1.6;
    color: #33323a;
    margin: 0;
}
.wiseman-home .rwho {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid var(--line);
}
.wiseman-home .av {
    flex: none;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--chip);
    color: var(--p);
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wiseman-home .rwho b {
    display: block;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ink);
}
.wiseman-home .rwho small {
    display: block;
    font-size: 12px;
    color: var(--muted);
}

/* Trust + Newsletter */
.wiseman-home .lightwrap {
    background: #fff;
    margin-top: 0;
}
.wiseman-home .trust {
    background: var(--f1);
    color: var(--navy);
    border-top: 1px solid var(--line);
}
.wiseman-home .trustin {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    align-items: center;
    padding-top: 32px;
    padding-bottom: 32px;
}
.wiseman-home .ti {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 24px;
    border-left: 1.5px solid #d6c8e8;
}
.wiseman-home .ti:first-child {
    border-left: 0;
    padding-left: 0;
}
.wiseman-home .ti .ic {
    flex: none;
    display: flex;
    color: var(--navy);
}
.wiseman-home .ti b {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: var(--navy);
}
.wiseman-home .ti small {
    color: #5d4b73;
    font-size: 13px;
    line-height: 1.35;
    display: block;
}
.wiseman-home .news {
    background: #fff;
}
.wiseman-home .newsin {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding-top: 38px;
    padding-bottom: 38px;
    flex-wrap: wrap;
}
.wiseman-home .news h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 38px;
    font-weight: 600;
    color: var(--navy);
    line-height: 1.1;
    margin: 0;
}
.wiseman-home .news p {
    color: #5d4b73;
    font-size: 15px;
    margin-top: 4px;
    margin-bottom: 0;
}
.wiseman-home .nform {
    display: flex;
    gap: 14px;
    flex: 1;
    max-width: 600px;
    min-width: 280px;
}
.wiseman-home .nform input {
    flex: 1;
    border: 1px solid #d9cce9;
    border-radius: 4px;
    padding: 0 20px;
    height: 52px;
    font: inherit;
    font-size: 15px;
    background: #fff;
    color: var(--navy);
    outline: 0;
}
.wiseman-home .nform button {
    background: var(--navy);
    color: #fff;
    border: 0;
    border-radius: 4px;
    padding: 0 32px;
    height: 52px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: .2s;
}
.wiseman-home .nform button:hover {
    background: var(--p);
}

/* Floating WhatsApp */
.wa-floating {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 999;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #25d366;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 18px rgba(0,0,0,.28);
    transition: transform .2s;
}
.wa-floating:hover {
    transform: scale(1.08);
}

@media (max-width: 820px) {
    .wiseman-home .sgrid.c4, .wiseman-home .bgrid, .wiseman-home .rgrid {
        grid-template-columns: repeat(2, 1fr);
    }
    .wiseman-home .trustin {
        grid-template-columns: repeat(2, 1fr);
        row-gap: 22px;
    }
    .wiseman-home .ti:nth-child(odd) {
        border-left: 0;
        padding-left: 0;
    }
    .wiseman-home .stitle { font-size: 32px; }
    .wiseman-home .news h3 { font-size: 30px; }
}
@media (max-width: 560px) {
    .wiseman-home .wrap { padding: 0 12px; }
    .wiseman-home .sgrid.c3,
    .wiseman-home .sgrid.c4,
    .wiseman-home .bgrid,
    .wiseman-home .rgrid { grid-template-columns: 1fr; }
    .wiseman-home .trustin { grid-template-columns: 1fr; }
    .wiseman-home .ti, .wiseman-home .ti:nth-child(odd) {
        border-left: 0;
        padding-left: 0;
    }
    .wiseman-home .pcard { flex-basis: 165px !important; }
    .wiseman-home .stitle { font-size: 24px; word-break: break-word; }
    .wiseman-home .news h3 { font-size: 24px; word-break: break-word; }
    .wiseman-home .newsin {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
        padding-top: 24px;
        padding-bottom: 24px;
    }
    .wiseman-home .nform {
        flex-direction: column;
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }
    .wiseman-home .nform input,
    .wiseman-home .nform button {
        width: 100%;
    }
}
</style>

<div class="wiseman-home">

    {{-- 1. Dynamic Hero Slider Banner --}}
    <div class="home-banner-area mb-3">
        @if (get_setting('home_slider_images') != null)
            <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height full-width-slider" data-arrows="true" data-dots="true" data-autoplay="true" data-infinite="true">
                @php $slider_images = json_decode(get_setting('home_slider_images'), true); @endphp
                @foreach ($slider_images as $key => $value)
                    <div class="carousel-box">
                        <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] ?? '#' }}" class="hero-slide-link d-block">
                            <img
                                class="d-block w-100 hero-slide-img"
                                src="{{ uploaded_asset($slider_images[$key]) }}"
                                alt="{{ env('APP_NAME') }} promo"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';"
                            >
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="wrap">
                <div class="hero position-relative">
                    <img src="{{ static_asset('assets/img/wiseman/hero-banner.jpg') }}" alt="WISEMAN Banner" class="w-100">
                    <a class="shopnow" href="{{ route('search') }}" style="position:absolute;left:6.6%;bottom:6.2%;background:var(--p);color:#fff;border-radius:4px;padding:12px 26px;font-weight:600;text-transform:uppercase;text-decoration:none;">Shop Now &rarr;</a>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. Shop by Category (Only Backend Enabled/Featured Categories) --}}
    @php
        $featured_categories = \App\Models\Category::where('featured', 1)->get();
    @endphp
    @if($featured_categories->count() > 0)
    <div class="wrap">
        <section class="sec">
            <h2 class="stitle">Shop by Category</h2>
            <p class="stag">Curated collections for everyday distinction</p>
            <div class="sgrid {{ $featured_categories->count() <= 3 ? 'c3' : 'c4' }}">
                @foreach($featured_categories as $cat)
                    <a class="scard" href="{{ route('products.category', $cat->slug) }}">
                        <div class="simg">
                            @if($cat->banner)
                                <img src="{{ uploaded_asset($cat->banner) }}" alt="{{ $cat->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            @elseif($cat->icon)
                                <img src="{{ uploaded_asset($cat->icon) }}" alt="{{ $cat->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            @else
                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $cat->getTranslation('name') }}">
                            @endif
                        </div>
                        <div class="sfoot">
                            <span>{{ $cat->getTranslation('name') }}</span>
                            <em>Shop now &rarr;</em>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- 3. New Arrivals (Dynamic Products + Arrow Carousel Scroll) --}}
    @php
        $new_products = filter_products(\App\Models\Product::where('published', 1)->latest())->take(15)->get();
    @endphp
    @if($new_products->count() > 0)
    <div class="wrap">
        <section class="sec">
            <div class="sechead">
                <div>
                    <h2 class="stitle">New Arrivals</h2>
                    <p class="stag">Fresh cuts just added to the collection</p>
                </div>
                <a class="vall" href="{{ route('search') }}">View all &rarr;</a>
                <div class="arrows" data-target="t-new">
                    <button type="button" aria-label="Previous">&lsaquo;</button>
                    <button type="button" aria-label="Next">&rsaquo;</button>
                </div>
            </div>
            <div class="track" id="t-new">
                @foreach($new_products as $product)
                    @php
                        $has_discount = discount_in_percentage($product) > 0;
                        $pb_has_options = (count(json_decode($product->colors)) > 0)
                            || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                            || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
                        $product_sizes = ($product->sizes != null && is_array(json_decode($product->sizes, true))) ? json_decode($product->sizes, true) : [];
                    @endphp
                    <article class="pcard">
                        <div class="pimg">
                            <a href="{{ route('product', $product->slug) }}">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            @if($has_discount)
                                <span class="badge-disc">{{ discount_in_percentage($product) }}% OFF</span>
                            @endif
                            <button type="button" class="wish" onclick="addToWishList({{ $product->id }})" aria-label="Wishlist" title="{{ translate('Add to wishlist') }}">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                            </button>
                        </div>
                        <div class="pbody">
                            <h3>
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    {{ Str::limit($product->getTranslation('name'), 45) }}
                                </a>
                            </h3>
                            <div class="price">
                                <b>{{ home_discounted_base_price($product) }}</b>
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <s>{{ home_base_price($product) }}</s>
                                @endif
                            </div>
                            <div class="sizes">
                                @if(!empty($product_sizes))
                                    @foreach(array_slice($product_sizes, 0, 5) as $size)
                                        <span>{{ $size }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="actions">
                            <button type="button" class="cartbtn" onclick="addToCartDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})" aria-label="Add to cart" title="{{ translate('Add to cart') }}">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.6 12.2a1.6 1.6 0 0 0 1.6 1.3h8.2a1.6 1.6 0 0 0 1.6-1.2L21 8H6"/></svg>
                            </button>
                            <button type="button" class="buy" onclick="buyNowDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})">Buy Now &rarr;</button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- 4. Mega Deals / Flash Sale Section (With Real-Time Countdown Timer) --}}
    @php
        $flash_deal = \App\Models\FlashDeal::where('status', 1)->where('featured', 1)->first();
        $flash_active = ($flash_deal != null && strtotime(date('Y-m-d H:i:s')) >= $flash_deal->start_date && strtotime(date('Y-m-d H:i:s')) <= $flash_deal->end_date);
        if ($flash_active) {
            $flash_products = $flash_deal->flash_deal_products->map(function($fp) {
                return \App\Models\Product::find($fp->product_id);
            })->filter(function($p) { return $p != null && $p->published == 1; });
            $remaining_seconds = max(0, $flash_deal->end_date - time());
        } else {
            $flash_products = filter_products(\App\Models\Product::where('published', 1)->where('discount', '>', 0))->latest()->take(10)->get();
            if ($flash_products->isEmpty()) {
                $flash_products = filter_products(\App\Models\Product::where('published', 1))->latest()->take(10)->get();
            }
            $remaining_seconds = 7 * 86400;
        }
    @endphp
    @if($flash_products->count() > 0)
    <section class="flash" id="mega">
        <div class="wrap">
            <div class="fhead">
                <div class="fleft">
                    <div>
                        <span class="flabel">Limited Time Offer</span>
                        <h2 class="stitle">{{ $flash_deal ? $flash_deal->title : 'Mega Deals' }}</h2>
                        <p class="stag">Up to 40% off on hand-picked essentials</p>
                    </div>
                    <div class="cd" id="cd" data-remaining="{{ $remaining_seconds }}">
                        <div><b id="d">00</b><small>DAYS</small></div>
                        <div><b id="h">00</b><small>HOURS</small></div>
                        <div><b id="m">00</b><small>MIN</small></div>
                        <div><b id="s">00</b><small>SEC</small></div>
                    </div>
                </div>
                <div class="arrows dark" data-target="t-flash">
                    <button type="button" aria-label="Previous">&lsaquo;</button>
                    <button type="button" aria-label="Next">&rsaquo;</button>
                </div>
            </div>
            <div class="track" id="t-flash" style="--n:4">
                @foreach($flash_products as $product)
                    @php
                        $has_discount = discount_in_percentage($product) > 0;
                        $pb_has_options = (count(json_decode($product->colors)) > 0)
                            || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                            || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
                        $product_sizes = ($product->sizes != null && is_array(json_decode($product->sizes, true))) ? json_decode($product->sizes, true) : [];
                    @endphp
                    <article class="pcard">
                        <div class="pimg">
                            <a href="{{ route('product', $product->slug) }}">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            @if($has_discount)
                                <span class="badge-disc">{{ discount_in_percentage($product) }}% OFF</span>
                            @endif
                            <button type="button" class="wish" onclick="addToWishList({{ $product->id }})" aria-label="Wishlist">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 0L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                            </button>
                        </div>
                        <div class="pbody">
                            <h3>
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    {{ Str::limit($product->getTranslation('name'), 45) }}
                                </a>
                            </h3>
                            <div class="price">
                                <b>{{ home_discounted_base_price($product) }}</b>
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <s>{{ home_base_price($product) }}</s>
                                @endif
                            </div>
                            <div class="sizes">
                                @if(!empty($product_sizes))
                                    @foreach(array_slice($product_sizes, 0, 5) as $size)
                                        <span>{{ $size }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="actions">
                            <button type="button" class="cartbtn" onclick="addToCartDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})" aria-label="Add to cart">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.6 12.2a1.6 1.6 0 0 0 1.6 1.3h8.2a1.6 1.6 0 0 0 1.6-1.2L21 8H6"/></svg>
                            </button>
                            <button type="button" class="buy" onclick="buyNowDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})">Buy Now &rarr;</button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- 5. Featured Products Grid (With View All) --}}
    @php
        $featured_products = filter_products(\App\Models\Product::where('published', 1)->where('featured', 1))->latest()->take(8)->get();
        if($featured_products->isEmpty()){
            $featured_products = filter_products(\App\Models\Product::where('published', 1))->latest()->take(8)->get();
        }
    @endphp
    @if($featured_products->count() > 0)
    <div class="wrap">
        <section class="sec">
            <div class="sechead">
                <div>
                    <h2 class="stitle">Featured Products</h2>
                    <p class="stag">Handpicked styles, just for you</p>
                </div>
                <a class="vall" href="{{ route('search') }}">View all &rarr;</a>
            </div>
            <div class="bgrid">
                @foreach($featured_products as $product)
                    @php
                        $has_discount = discount_in_percentage($product) > 0;
                        $pb_has_options = (count(json_decode($product->colors)) > 0)
                            || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                            || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
                    @endphp
                    <article class="pcard">
                        <div class="pimg">
                            <a href="{{ route('product', $product->slug) }}">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            @if($has_discount)
                                <span class="badge-disc">{{ discount_in_percentage($product) }}% OFF</span>
                            @endif
                            <button type="button" class="wish" onclick="addToWishList({{ $product->id }})" aria-label="Wishlist">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 0L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                            </button>
                        </div>
                        <div class="pbody">
                            <h3>
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    {{ Str::limit($product->getTranslation('name'), 45) }}
                                </a>
                            </h3>
                            <div class="price">
                                <b>{{ home_discounted_base_price($product) }}</b>
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <s>{{ home_base_price($product) }}</s>
                                @endif
                            </div>
                        </div>
                        <div class="actions">
                            <button type="button" class="cartbtn" onclick="addToCartDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})" aria-label="Add to cart">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.6 12.2a1.6 1.6 0 0 0 1.6 1.3h8.2a1.6 1.6 0 0 0 1.6-1.2L21 8H6"/></svg>
                            </button>
                            <button type="button" class="buy" onclick="buyNowDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})">Buy Now &rarr;</button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- 6. Delivery Banner Section --}}
    <div class="wrap">
        <div class="dbanner">
            <img src="{{ static_asset('assets/img/wiseman/delivery-banner.jpg') }}" alt="Wiseman — Within 48 hours delivery all over Bangladesh. Cash on Delivery, 7 days easy return. Call +8801811762186" width="1920" height="540" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
        </div>
    </div>

    {{-- 7. Best Selling Section (With View All) --}}
    @php
        $best_selling_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->take(8)->get();
        if($best_selling_products->isEmpty()){
            $best_selling_products = filter_products(\App\Models\Product::where('published', 1))->take(8)->get();
        }
    @endphp
    @if($best_selling_products->count() > 0)
    <div class="wrap">
        <section class="sec best">
            <div class="sechead">
                <div>
                    <h2 class="stitle">Best Selling</h2>
                    <p class="stag">Loved by many, chosen by you</p>
                </div>
                <a class="vall" href="{{ route('search') }}?sort_by=num_of_sale">View all &rarr;</a>
            </div>
            <div class="bgrid">
                @foreach($best_selling_products as $product)
                    @php
                        $has_discount = discount_in_percentage($product) > 0;
                        $pb_has_options = (count(json_decode($product->colors)) > 0)
                            || ($product->choice_options != null && count(json_decode($product->choice_options)) > 0)
                            || ($product->sizes != null && is_array(json_decode($product->sizes, true)) && count(json_decode($product->sizes, true)) > 0);
                        $product_sizes = ($product->sizes != null && is_array(json_decode($product->sizes, true))) ? json_decode($product->sizes, true) : [];
                    @endphp
                    <article class="pcard">
                        <div class="pimg">
                            <a href="{{ route('product', $product->slug) }}">
                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            @if($has_discount)
                                <span class="badge-disc">{{ discount_in_percentage($product) }}% OFF</span>
                            @endif
                            <button type="button" class="wish" onclick="addToWishList({{ $product->id }})" aria-label="Wishlist">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 0L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                            </button>
                        </div>
                        <div class="pbody">
                            <h3>
                                <a href="{{ route('product', $product->slug) }}" title="{{ $product->getTranslation('name') }}">
                                    {{ Str::limit($product->getTranslation('name'), 45) }}
                                </a>
                            </h3>
                            <div class="price">
                                <b>{{ home_discounted_base_price($product) }}</b>
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <s>{{ home_base_price($product) }}</s>
                                @endif
                            </div>
                            <div class="sizes">
                                @if(!empty($product_sizes))
                                    @foreach(array_slice($product_sizes, 0, 5) as $size)
                                        <span>{{ $size }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="actions">
                            <button type="button" class="cartbtn" onclick="addToCartDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})" aria-label="Add to cart">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.6 12.2a1.6 1.6 0 0 0 1.6 1.3h8.2a1.6 1.6 0 0 0 1.6-1.2L21 8H6"/></svg>
                            </button>
                            <button type="button" class="buy" onclick="buyNowDirect({{ $product->id }}, {{ $pb_has_options ? 'true' : 'false' }})">Buy Now &rarr;</button>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
    @endif

    {{-- 8. Customer Reviews --}}
    <div class="wrap">
        <section class="sec reviews">
            <h2 class="stitle">Reviews</h2>
            <p class="stag">What our customers say</p>
            <div class="rgrid">
                <article class="rcard">
                    <div class="stars" aria-label="5 out of 5">★★★★★</div>
                    <p>The slim fit denim sits perfectly and the stretch is comfortable all day. Fabric quality feels much better than the price suggests.</p>
                    <div class="rwho">
                        <span class="av">R</span>
                        <div><b>Rafiq Ahmed</b><small>Verified buyer · Dhaka</small></div>
                    </div>
                </article>
                <article class="rcard">
                    <div class="stars" aria-label="5 out of 5">★★★★★</div>
                    <p>Ordered on Sunday and it arrived in two days. Paid cash on delivery and the packaging was neat.</p>
                    <div class="rwho">
                        <span class="av">T</span>
                        <div><b>Tanvir Hasan</b><small>Verified buyer · Chattogram</small></div>
                    </div>
                </article>
                <article class="rcard">
                    <div class="stars" aria-label="5 out of 5">★★★★★</div>
                    <p>Bought a few pieces for my son. The kids’ fabric is soft and the sizing was accurate. Will order again.</p>
                    <div class="rwho">
                        <span class="av">S</span>
                        <div><b>Sadia Rahman</b><small>Verified buyer · Sylhet</small></div>
                    </div>
                </article>
                <article class="rcard">
                    <div class="stars" aria-label="4 out of 5">★★★★☆</div>
                    <p>The belt and wallet combo looks premium and makes a great gift. Exchange was easy when I needed a different size.</p>
                    <div class="rwho">
                        <span class="av">I</span>
                        <div><b>Imran Hossain</b><small>Verified buyer · Khulna</small></div>
                    </div>
                </article>
            </div>
        </section>
    </div>

    {{-- 9. Trust Features & Newsletter --}}
    <div class="lightwrap">
        <div class="trust">
            <div class="wrap trustin">
                <div class="ti">
                    <span class="ic">
                        <svg viewBox="0 0 48 32" width="38" height="26" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5h25v18H3zM28 11h9l6 7v5H28"/><circle cx="12" cy="25" r="3.5" fill="#f3edf9"/><circle cx="35" cy="25" r="3.5" fill="#f3edf9"/></svg>
                    </span>
                    <div>
                        <b>Delivery All Over Bangladesh</b>
                        <small>Home delivery across 64 districts</small>
                    </div>
                </div>
                <div class="ti">
                    <span class="ic">
                        <svg viewBox="0 0 48 32" width="38" height="26" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="6" width="40" height="20" rx="4"/><circle cx="24" cy="16" r="5"/><circle cx="24" cy="16" r="1.4" fill="currentColor"/></svg>
                    </span>
                    <div>
                        <b>Cash on Delivery</b>
                        <small>Pay when you receive at doorstep</small>
                    </div>
                </div>
                <div class="ti">
                    <span class="ic">
                        <svg viewBox="0 0 48 32" width="38" height="26" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 10h34M33 3l7 7-7 7M42 23H8M15 16l-7 7 7 7"/></svg>
                    </span>
                    <div>
                        <b>Easy Exchange</b>
                        <small>Hassle-free 7-day exchange</small>
                    </div>
                </div>
                <div class="ti">
                    <span class="ic">
                        <svg viewBox="0 0 32 32" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3l11 4v8c0 7-5 12-11 14C10 27 5 22 5 15V7z"/><path d="M11 16l4 4 7-8"/></svg>
                    </span>
                    <div>
                        <b>100% Secure Payment</b>
                        <small>bKash, Nagad, Cards & Net Banking</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="news">
            <div class="wrap newsin">
                <div>
                    <h3>Join the Wise Circle</h3>
                    <p>Be the first to hear new collections and exclusive offers.</p>
                </div>
                <form class="nform" action="{{ route('subscribers.store') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Your email address" required>
                    <button type="submit">SUBSCRIBE</button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Floating WhatsApp --}}
<a class="wa-floating" href="https://wa.me/8801811762186" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <svg viewBox="0 0 24 24" width="28" height="28" fill="#fff"><path d="M12 3a9 9 0 0 0-7.8 13.4L3 21l4.7-1.2A9 9 0 1 0 12 3z"/></svg>
</a>

@endsection

@section('script')
<script>
    // Smooth scroll for arrow carousels
    document.querySelectorAll('.wiseman-home .arrows').forEach(function(a){
        var t = document.getElementById(a.dataset.target);
        if (!t) return;
        var b = a.querySelectorAll('button');
        b[0].onclick = function(){ t.scrollBy({left: -t.clientWidth * 0.8, behavior: 'smooth'}); };
        b[1].onclick = function(){ t.scrollBy({left: t.clientWidth * 0.8, behavior: 'smooth'}); };
    });

    // Real-time Countdown timer for Mega Deals
    (function(){
        var cd = document.getElementById('cd');
        if (!cd) return;
        var left = parseInt(cd.dataset.remaining || 604800);
        var D = document.getElementById('d'),
            H = document.getElementById('h'),
            M = document.getElementById('m'),
            S = document.getElementById('s');
        function p(n){ return String(n).padStart(2, '0'); }
        function tick(){
            if(left > 0) left--;
            if (D) D.textContent = p(Math.floor(left / 86400));
            if (H) H.textContent = p(Math.floor((left % 86400) / 3600));
            if (M) M.textContent = p(Math.floor((left % 3600) / 60));
            if (S) S.textContent = p(left % 60);
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>
@endsection