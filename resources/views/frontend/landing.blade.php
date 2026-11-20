@php
    $campaign_title = trim($landing_page->title ?: $product->getTranslation('name'));
    $description_lines = array_values(array_filter(preg_split('/\r\n|\r|\n/', strip_tags((string) $landing_page->description)), function ($line) {
        return trim($line) !== '';
    }));
    $start_timestamp = strtotime($landing_page->start_date);
    $end_timestamp = strtotime($landing_page->end_date);
    $product_photos = is_array($product->photos) ? $product->photos : json_decode((string) $product->photos, true);
    $product_image_id = is_array($product_photos) && count($product_photos) > 0 ? $product_photos[0] : $product->thumbnail_img;
    $landing_product_image = uploaded_asset($product_image_id);
    $delivery_areas = array(
        'In Dhaka City' => 'ঢাকার ভিতরে (৬০ টাকা)',
        'Sub City Of Dhaka' => 'ঢাকা উপশহর (১০০ টাকা)',
        'Out Of Dhaka City' => 'ঢাকার বাইরে (১২০ টাকা)',
    );
@endphp
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $campaign_title }}</title>
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">
    <style>
        @font-face {
            font-family: 'Hind Siliguri';
            src: url('{{ static_asset('assets/fonts/HindSiliguri-Regular.ttf') }}') format('truetype');
            font-display: swap;
        }

        :root {
            --offer-green: #08ab10;
            --offer-green-dark: #078a0e;
            --offer-red: #f31313;
            --offer-text: #171717;
            --offer-line: #dedede;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: #ededed;
            color: var(--offer-text);
            font-family: 'Hind Siliguri', Arial, sans-serif;
            font-size: 16px;
            line-height: 1.45;
        }

        button,
        input {
            font: inherit;
        }

        .landing-page {
            width: 100%;
            max-width: 790px;
            min-height: 100vh;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 28px rgba(0, 0, 0, .10);
            overflow: hidden;
        }

        .landing-inner {
            padding: 0 32px;
        }

        .campaign-notice {
            margin: 16px 24px 0;
            padding: 10px 14px;
            border-radius: 4px;
            background: #fff3cd;
            color: #856404;
            font-size: 14px;
            text-align: center;
        }

        .campaign-alert {
            margin: 16px 24px 0;
            padding: 12px 14px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
        }

        .campaign-alert ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .campaign-alert.success {
            background: #e3f8e5;
            color: #146c1b;
            border: 1px solid #bde8c1;
        }

        .campaign-alert.error {
            background: #fff0f0;
            color: #a41717;
            border: 1px solid #f1bcbc;
        }

        .offer-header {
            padding-top: 8px;
            text-align: center;
        }

        .offer-title {
            margin: 0 0 8px;
            color: var(--offer-green);
            font-size: 29px;
            font-weight: 800;
            line-height: 1.22;
            overflow-wrap: anywhere;
        }

        .product-hero-frame {
            display: block;
            width: 100%;
            max-width: 600px;
            aspect-ratio: 16 / 9;
            margin: 0 auto;
            padding: 2px;
            border: 2px solid var(--offer-green);
            background: #fff;
        }

        .product-hero-frame img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f5f5f5;
        }

        .countdown {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            width: min(100%, 390px);
            margin: 14px auto 22px;
        }

        .countdown-box {
            padding: 10px 3px 9px;
            border-radius: 5px;
            background: var(--offer-green);
            color: #fff;
            text-align: center;
        }

        .countdown-value {
            display: block;
            font-family: Arial, sans-serif;
            font-size: 34px;
            font-weight: 400;
            line-height: 1;
        }

        .countdown-label {
            display: block;
            margin-top: 4px;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 500;
        }

        .price-line {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 4px 12px;
            margin: 0 0 16px;
            text-align: center;
            font-size: 19px;
            font-weight: 700;
        }

        .price-line del {
            color: #111;
            text-decoration-color: var(--offer-red);
            text-decoration-thickness: 2px;
        }

        .price-line .discount-label,
        .price-line .offer-price {
            color: var(--offer-green);
        }

        .price-line .offer-price {
            position: relative;
            padding: 0 5px;
            font-size: 21px;
        }

        .cta-wrap {
            padding-bottom: 14px;
            text-align: center;
        }

        .offer-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
            padding: 9px 20px 8px;
            border: 0;
            border-radius: 3px;
            background: var(--offer-red);
            color: #fff;
            box-shadow: 0 2px 0 #b60d0d;
            cursor: pointer;
            font-family: inherit;
            font-size: 17px;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: transform .16s ease, background .16s ease;
        }

        .offer-cta:hover {
            background: #d90d0d;
            color: #fff;
            transform: translateY(-1px);
        }

        .offer-cta:active {
            transform: translateY(1px);
        }

        .section-bar {
            padding: 7px 18px 6px;
            background: var(--offer-green);
            color: #fff;
            text-align: center;
            font-size: 23px;
            font-weight: 800;
            line-height: 1.22;
            overflow-wrap: anywhere;
        }

        .benefit-section {
            padding: 13px 0 17px;
        }

        .campaign-lead {
            margin: 0 0 10px;
            color: #e41414;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.55;
            text-align: center;
            white-space: pre-line;
        }

        .benefit-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .benefit-list li {
            position: relative;
            padding: 4px 8px 5px 23px;
            border-bottom: 1px solid var(--offer-line);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .benefit-list li::before {
            position: absolute;
            top: 6px;
            left: 3px;
            color: var(--offer-green);
            content: '\2713';
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: 900;
        }

        .offer-price-callout {
            margin: 15px 0 8px;
            color: var(--offer-green);
            font-size: 24px;
            font-weight: 800;
            line-height: 1.15;
            text-align: center;
        }

        .trust-section {
            padding: 14px 0 13px;
        }

        .trust-list {
            padding: 0;
            margin: 0 0 15px;
            list-style: none;
        }

        .trust-list li {
            position: relative;
            padding: 4px 7px 5px 25px;
            border-bottom: 1px solid var(--offer-line);
            font-size: 14px;
            font-weight: 600;
            line-height: 1.38;
        }

        .trust-list li::before {
            position: absolute;
            top: 5px;
            left: 3px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: var(--offer-green);
            color: #fff;
            content: '\2713';
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 900;
            line-height: 15px;
            text-align: center;
        }

        .repeat-image {
            display: block;
            width: 100%;
            max-width: 600px;
            aspect-ratio: 16 / 9;
            margin: 10px auto 0;
            padding: 2px;
            border: 2px solid var(--offer-green);
            background: #fff;
        }

        .repeat-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #f5f5f5;
        }

        .contact-bar {
            padding: 8px 18px 7px;
            background: var(--offer-green);
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.25;
            text-align: center;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .checkout-section {
            padding: 18px 0 25px;
        }

        .checkout-title {
            margin: 0 0 11px;
            color: var(--offer-green);
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            text-align: center;
        }

        .checkout-panel {
            overflow: hidden;
            border: 2px solid var(--offer-green);
            border-radius: 15px;
            background: #fff;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.12fr) minmax(0, .88fr);
        }

        .checkout-column {
            padding: 22px 18px 20px;
        }

        .checkout-summary {
            border-left: 1px solid #e6e6e6;
            background: #fcfcfc;
        }

        .checkout-heading {
            margin: 0 0 14px;
            font-family: Arial, sans-serif;
            font-size: 15px;
            font-weight: 700;
        }

        .field-group {
            margin-bottom: 14px;
        }

        .field-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 700;
        }

        .field-required {
            color: #f00;
        }

        .variant-group {
            margin: 10px 0;
        }

        .variant-label {
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6px;
        }

        .variant-options {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .variant-option {
            position: relative;
            display: inline-flex;
            margin: 0;
        }

        .variant-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .variant-option .variant-swatch {
            display: inline-block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #d9d9d9;
            cursor: pointer;
            transition: all .15s ease;
        }

        .variant-option.size-option span {
            display: inline-block;
            min-width: 42px;
            text-align: center;
            padding: 6px 12px;
            border: 2px solid #d9d9d9;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            background: #fff;
            color: #334155;
            transition: all .15s ease;
        }

        .variant-option input:checked + span {
            border-color: var(--offer-green);
            color: var(--offer-green);
            background: #f0fdf4;
        }

        .variant-option input:checked + .variant-swatch {
            transform: scale(1.1);
            border-color: var(--offer-green);
        }

        .variant-error {
            margin-top: 6px;
            color: #f00;
            font-size: 13px;
            font-weight: 600;
            display: none;
        }

        .field-input {
            display: block;
            width: 100%;
            height: 40px;
            padding: 8px 10px;
            border: 1px solid #d9d9d9;
            border-radius: 2px;
            background: #fff;
            color: #222;
            outline: 0;
            transition: border-color .16s ease, box-shadow .16s ease;
        }

        .field-input:focus {
            border-color: var(--offer-green);
            box-shadow: 0 0 0 2px rgba(8, 171, 16, .12);
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 0;
            border-bottom: 1px solid #dedede;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .summary-row strong {
            font-weight: 700;
        }

        .checkout-product {
            display: grid;
            grid-template-columns: 46px minmax(0, 1fr);
            gap: 9px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dedede;
        }

        .checkout-product img {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border: 1px solid #e1e1e1;
        }

        .checkout-product-name {
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.25;
        }

        .product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .quantity-picker {
            display: inline-flex;
            align-items: center;
            border: 1px solid #d9d9d9;
            border-radius: 2px;
            overflow: hidden;
            background: #fff;
        }

        .quantity-picker button {
            width: 23px;
            height: 26px;
            padding: 0;
            border: 0;
            background: #f5f5f5;
            color: #333;
            cursor: pointer;
            font-size: 15px;
            line-height: 1;
        }

        .quantity-picker input {
            width: 30px;
            height: 26px;
            padding: 0;
            border: 0;
            border-right: 1px solid #d9d9d9;
            border-left: 1px solid #d9d9d9;
            color: #222;
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
            outline: 0;
        }

        .payment-card {
            margin-top: 14px;
            padding: 12px;
            background: #f3f3f3;
        }

        .payment-option {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .payment-option input {
            accent-color: var(--offer-red);
        }

        .payment-note {
            margin: 8px 0 0 23px;
            color: #777;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.45;
        }

        .place-order {
            display: flex;
            width: 100%;
            min-height: 50px;
            align-items: center;
            justify-content: center;
            margin-top: 14px;
            padding: 12px;
            border: 0;
            border-radius: 2px;
            background: #ff5b2e;
            color: #fff;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: 700;
            transition: background .16s ease, transform .16s ease;
        }

        .place-order:hover {
            background: #e94c24;
            transform: translateY(-1px);
        }

        .place-order:disabled {
            background: #a5a5a5;
            cursor: not-allowed;
            transform: none;
        }

        .checkout-privacy {
            margin: 10px 0 0;
            color: #777;
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.45;
        }

        .landing-footer {
            padding: 15px 18px;
            background: #f3f4f5;
            color: #575757;
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 640px) {
            body {
                background: #fff;
            }

            .landing-page {
                box-shadow: none;
            }

            .landing-inner {
                padding-right: 16px;
                padding-left: 16px;
            }

            .offer-title {
                font-size: 23px;
            }

            .product-hero-frame,
            .repeat-image {
                max-width: 100%;
            }

            .countdown {
                gap: 5px;
                margin-bottom: 17px;
            }

            .countdown-box {
                padding-top: 8px;
            }

            .countdown-value {
                font-size: 28px;
            }

            .countdown-label {
                font-size: 9px;
            }

            .price-line {
                gap: 2px 8px;
                font-size: 17px;
            }

            .price-line .offer-price {
                font-size: 19px;
            }

            .section-bar,
            .contact-bar {
                font-size: 20px;
            }

            .benefit-list li,
            .trust-list li {
                font-size: 13px;
            }

            .checkout-grid {
                grid-template-columns: 1fr;
            }

            .checkout-summary {
                border-top: 1px solid #e6e6e6;
                border-left: 0;
            }

            .checkout-column {
                padding: 18px 14px;
            }
        }
    </style>
</head>
<body>
    <main class="landing-page">
        @if(session('success'))
            <div class="campaign-alert success">{{ session('success') }}</div>
        @endif

        @if(session('error') || (isset($errors) && $errors->any()))
            <div class="campaign-alert error">
                @if(session('error'))
                    {{ session('error') }}
                @elseif(isset($errors) && $errors->any())
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        @if(!$campaign_open)
            <div class="campaign-notice">এই অফারটি বর্তমানে সক্রিয় নেই।</div>
        @endif

        <header class="offer-header landing-inner">
            <h1 class="offer-title">{{ $campaign_title }}</h1>
            <figure class="product-hero-frame">
                <img src="{{ $landing_product_image }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </figure>
        </header>

        <section class="landing-inner">
            <div class="countdown" data-countdown-end="{{ $end_timestamp ? $end_timestamp * 1000 : 0 }}" aria-label="Offer countdown">
                <div class="countdown-box">
                    <span class="countdown-value" data-countdown="days">00</span>
                    <span class="countdown-label">Days</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-value" data-countdown="hours">00</span>
                    <span class="countdown-label">Hours</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-value" data-countdown="minutes">00</span>
                    <span class="countdown-label">Minutes</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-value" data-countdown="seconds">00</span>
                    <span class="countdown-label">Seconds</span>
                </div>
            </div>

            <p class="price-line">
                <span>রেগুলার মূল্য</span>
                <del>{{ single_price($base_price) }}</del>
                <span class="discount-label">বর্তমান ডিসকাউন্ট মূল্য</span>
                <span class="offer-price">{{ single_price($discounted_price) }}</span>
            </p>

            <div class="cta-wrap">
                <a class="offer-cta" href="#landing-order" data-scroll-order>অর্ডার করতে চাই</a>
            </div>
        </section>

        <section>
            <div class="section-bar">{{ $product->getTranslation('name') }} কেন নিবেন?</div>
            <div class="landing-inner benefit-section">
                @if($landing_page->header_text)
                    <p class="campaign-lead">{{ $landing_page->header_text }}</p>
                @endif

                @if(count($description_lines) > 0)
                    <ul class="benefit-list">
                        @foreach($description_lines as $line)
                            <li>{{ trim($line) }}</li>
                        @endforeach
                    </ul>
                @else
                    <ul class="benefit-list">
                        <li>নির্বাচিত পণ্যটি বিশেষ অফার মূল্যে অর্ডার করার সুযোগ।</li>
                        <li>ঘরে বসে ক্যাশ অন ডেলিভারিতে অর্ডার কনফার্ম করুন।</li>
                        <li>সীমিত সময়ের জন্য এই অফারটি চালু আছে।</li>
                    </ul>
                @endif

                <div class="offer-price-callout">আজকের অফার মূল্য {{ single_price($discounted_price) }} মাত্র</div>
                <div class="cta-wrap">
                    <a class="offer-cta" href="#landing-order" data-scroll-order>অর্ডার করতে চাই</a>
                </div>
            </div>
        </section>

        <section>
            <div class="section-bar">আমাদের কাছ থেকে কেন কিনবেন?</div>
            <div class="landing-inner trust-section">
                <ul class="trust-list">
                    <li>আমরা নির্বাচিত ও মানসম্মত পণ্য সরবরাহ করি।</li>
                    <li>ক্যাশ অন ডেলিভারিতে পণ্য হাতে পেয়ে পেমেন্ট করার সুবিধা।</li>
                    <li>সহজ অর্ডার প্রক্রিয়া ও দ্রুত কাস্টমার সাপোর্ট।</li>
                    <li>অর্ডারের তথ্য যাচাই করে ডেলিভারি নিশ্চিত করা হয়।</li>
                    <li>যে কোনো প্রয়োজনে আমাদের সঙ্গে যোগাযোগ করার সুযোগ আছে।</li>
                </ul>

                <figure class="repeat-image">
                    <img src="{{ $landing_product_image }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </figure>
            </div>
        </section>

        <div class="contact-bar">{{ $landing_page->footer_text ?: 'যে কোন প্রয়োজনে যোগাযোগ করুন' }}</div>

        <section id="landing-order" class="landing-inner checkout-section">
            <h2 class="checkout-title">অর্ডার করতে নিচের ফর্মটি পূরণ করুন</h2>

            <form action="{{ route('landing_page.order', $landing_page->id) }}" method="POST" class="checkout-panel" data-unit-price="{{ (float) $discounted_price }}" data-landing-id="{{ $landing_page->id }}" data-partial-url="{{ route('landing_page.partial_order', $landing_page->id) }}">
                @csrf
                <input type="hidden" name="partial_id" id="landing-partial-id">
                <div class="checkout-grid">
                    <section class="checkout-column">
                        <h3 class="checkout-heading">বিলিং তথ্য</h3>

                        <div class="field-group">
                            <label for="landing-name">আপনার নাম <span class="field-required">*</span></label>
                            <input id="landing-name" class="field-input" type="text" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="field-group">
                            <label for="landing-phone">আপনার ফোন নাম্বার <span class="field-required">*</span></label>
                            <input id="landing-phone" class="field-input" type="tel" name="phone" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" pattern="[0-9]{11}" title="Example: 01xxxxxxxxxx" required>
                        </div>

                        <div class="field-group">
                            <label for="landing-address">আপনার সম্পূর্ণ ঠিকানা <span class="field-required">*</span></label>
                            <input id="landing-address" class="field-input" type="text" name="address" value="{{ old('address') }}" placeholder="বাসা/বিল্ডিং/রাস্তা/এলাকা" required>
                        </div>

                        <div class="field-group">
                            <label for="landing-delivery-area">ডেলিভারি এরিয়া <span class="field-required">*</span></label>
                            <select id="landing-delivery-area" class="field-input" name="delivery_area" required>
                                <option value="">ডেলিভারি এরিয়া নির্বাচন করুন</option>
                                @foreach($delivery_areas as $value => $label)
                                    <option value="{{ $value }}" @if(old('delivery_area') == $value) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </section>

                    <aside class="checkout-column checkout-summary">
                        <h3 class="checkout-heading">আপনার অর্ডার</h3>
                        <div class="summary-row">
                            <span>প্রোডাক্ট</span>
                            <span>সাবটোটাল</span>
                        </div>

                        <div class="checkout-product">
                            <img src="{{ $landing_product_image }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            <div>
                                <div class="checkout-product-name">{{ $product->getTranslation('name') }}</div>

                                @php
                                    $landing_colors = json_decode($product->colors);
                                    $landing_sizes = ($product->sizes != null && is_array(json_decode($product->sizes, true))) ? json_decode($product->sizes, true) : array();
                                @endphp

                                @if(is_array($landing_colors) && count($landing_colors) > 0)
                                    <div class="variant-group">
                                        <div class="variant-label">কালার (Color) <span class="field-required">*</span></div>
                                        <div class="variant-options color-options">
                                            @foreach ($landing_colors as $landing_color)
                                                @php $landing_color_name = \App\Models\Color::where('code', $landing_color)->first()->name; @endphp
                                                <label class="variant-option" data-toggle="tooltip" data-title="{{ $landing_color_name }}">
                                                    <input type="radio" name="color" value="{{ $landing_color_name }}" @if(old('color') == $landing_color_name) checked @endif>
                                                    <span class="variant-swatch" style="background: {{ $landing_color }};" title="{{ $landing_color_name }}"></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(count($landing_sizes) > 0)
                                    <div class="variant-group">
                                        <div class="variant-label">সাইজ (Size) <span class="field-required">*</span></div>
                                        <div class="variant-options size-options">
                                            @foreach ($landing_sizes as $landing_size)
                                                <label class="variant-option size-option">
                                                    <input type="radio" name="size" value="{{ $landing_size }}" @if(old('size') == $landing_size) checked @endif>
                                                    <span>{{ $landing_size }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="variant-error" id="landing-variant-error"></div>

                                <div class="product-meta">
                                    <div class="quantity-picker">
                                        <button type="button" data-quantity-change="-1" aria-label="কমিয়ে দিন">-</button>
                                        <input id="landing-quantity" type="number" name="quantity" min="1" max="99" value="{{ old('quantity', 1) }}" aria-label="পরিমাণ">
                                        <button type="button" data-quantity-change="1" aria-label="বাড়িয়ে দিন">+</button>
                                    </div>
                                    <strong id="order-product-total">{{ single_price($discounted_price) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="summary-row">
                            <span>সাবটোটাল</span>
                            <strong id="order-subtotal">{{ single_price($discounted_price) }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>মোট</span>
                            <strong id="order-total">{{ single_price($discounted_price) }}</strong>
                        </div>

                        <div class="payment-card">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                <span>Cash on delivery</span>
                            </label>
                            <p class="payment-note">পণ্য হাতে পেয়ে ক্যাশে পেমেন্ট করুন।</p>
                        </div>

                        <p class="checkout-privacy">আপনার দেওয়া তথ্য শুধু অর্ডার কনফার্ম ও ডেলিভারির জন্য ব্যবহার করা হবে।</p>
                        <button type="submit" class="place-order" @if(!$campaign_open) disabled @endif><span id="order-button-text">অর্ডার কনফার্ম করুন - {{ single_price($discounted_price) }}</span></button>
                    </aside>
                </div>
            </form>
        </section>

        <footer class="landing-footer">
            Copyright &copy; {{ date('Y') }} {{ get_setting('website_name') ?: config('app.name') }}. All rights reserved.
        </footer>
    </main>

    <script>
        (function () {
            var countdown = document.querySelector('[data-countdown-end]');
            if (countdown) {
                var endTime = Number(countdown.getAttribute('data-countdown-end')) || 0;
                var units = {
                    days: countdown.querySelector('[data-countdown="days"]'),
                    hours: countdown.querySelector('[data-countdown="hours"]'),
                    minutes: countdown.querySelector('[data-countdown="minutes"]'),
                    seconds: countdown.querySelector('[data-countdown="seconds"]')
                };

                function pad(value) {
                    return String(value).padStart(2, '0');
                }

                function updateCountdown() {
                    var remaining = Math.max(0, endTime - Date.now());
                    var totalSeconds = Math.floor(remaining / 1000);
                    var days = Math.floor(totalSeconds / 86400);
                    var hours = Math.floor((totalSeconds % 86400) / 3600);
                    var minutes = Math.floor((totalSeconds % 3600) / 60);
                    var seconds = totalSeconds % 60;

                    units.days.textContent = pad(days);
                    units.hours.textContent = pad(hours);
                    units.minutes.textContent = pad(minutes);
                    units.seconds.textContent = pad(seconds);
                }

                updateCountdown();
                window.setInterval(updateCountdown, 1000);
            }

            var quantityInput = document.getElementById('landing-quantity');
            var checkoutForm = document.querySelector('[data-unit-price]');
            if (quantityInput && checkoutForm) {
                var unitPrice = Number(checkoutForm.getAttribute('data-unit-price')) || 0;
                var currency = {!! json_encode(currency_symbol()) !!};
                var totalTargets = [
                    document.getElementById('order-product-total'),
                    document.getElementById('order-subtotal'),
                    document.getElementById('order-total')
                ];
                var buttonText = document.getElementById('order-button-text');

                function normalizeQuantity() {
                    var quantity = parseInt(quantityInput.value, 10) || 1;
                    quantity = Math.max(1, Math.min(99, quantity));
                    quantityInput.value = quantity;
                    return quantity;
                }

                function formatPrice(amount) {
                    return currency + Number(amount).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function updateOrderTotal() {
                    var total = unitPrice * normalizeQuantity();
                    totalTargets.forEach(function (target) {
                        if (target) {
                            target.textContent = formatPrice(total);
                        }
                    });
                    if (buttonText) {
                        buttonText.textContent = 'অর্ডার কনফার্ম করুন - ' + formatPrice(total);
                    }
                }

                document.querySelectorAll('[data-quantity-change]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        quantityInput.value = normalizeQuantity() + Number(button.getAttribute('data-quantity-change'));
                        updateOrderTotal();
                    });
                });
                quantityInput.addEventListener('input', updateOrderTotal);
                quantityInput.addEventListener('change', updateOrderTotal);
                updateOrderTotal();
            }
        })();

        (function () {
            var form = document.querySelector('form[data-partial-url]');
            if (!form) return;

            var storageKey = 'landing_partial_' + form.getAttribute('data-landing-id');
            var partialUrl = form.getAttribute('data-partial-url');
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            var partialIdInput = document.getElementById('landing-partial-id');
            var nameInput = document.getElementById('landing-name');
            var phoneInput = document.getElementById('landing-phone');
            var addressInput = document.getElementById('landing-address');
            var quantityInput = document.getElementById('landing-quantity');
            var areaSelect = document.getElementById('landing-delivery-area');
            var successShown = !!document.querySelector('.campaign-alert.success');

            function readStored() {
                try {
                    return JSON.parse(localStorage.getItem(storageKey)) || null;
                } catch (e) {
                    return null;
                }
            }

            function stash(values) {
                try {
                    localStorage.setItem(storageKey, JSON.stringify(values));
                } catch (e) {}
            }

            function readValues() {
                var colorChecked = form.querySelector('input[name="color"]:checked');
                var sizeChecked = form.querySelector('input[name="size"]:checked');
                var values = {
                    name: nameInput ? nameInput.value : '',
                    phone: phoneInput ? phoneInput.value : '',
                    address: addressInput ? addressInput.value : '',
                    delivery_area: areaSelect ? areaSelect.value : '',
                    quantity: quantityInput ? quantityInput.value : '1',
                    color: colorChecked ? colorChecked.value : '',
                    size: sizeChecked ? sizeChecked.value : ''
                };
                var stored = readStored();
                if (stored && stored.partial_id) {
                    values.partial_id = stored.partial_id;
                }
                return values;
            }

            function hasValidPhone() {
                var phone = phoneInput ? phoneInput.value : '';
                return phone.replace(/[^0-9]/g, '').length >= 11;
            }

            function buildBody(values) {
                var body = new FormData();
                body.append('name', values.name);
                body.append('phone', values.phone);
                body.append('address', values.address);
                body.append('delivery_area', values.delivery_area);
                body.append('quantity', values.quantity);
                if (values.color) {
                    body.append('color', values.color);
                }
                if (values.size) {
                    body.append('size', values.size);
                }
                if (values.partial_id) {
                    body.append('partial_id', values.partial_id);
                }
                return body;
            }

            function persistPartial(keepalive) {
                if (!hasValidPhone()) return;
                var values = readValues();
                fetch(partialUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    keepalive: !!keepalive,
                    body: buildBody(values),
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                }).then(function (response) {
                    return response.json();
                }).then(function (data) {
                    if (data && data.ok && data.id) {
                        values.partial_id = String(data.id);
                        if (partialIdInput) {
                            partialIdInput.value = values.partial_id;
                        }
                        stash(values);
                    }
                }).catch(function () {});
            }

            var saveTimer = null;
            function scheduleSave() {
                if (saveTimer) {
                    window.clearTimeout(saveTimer);
                }
                saveTimer = window.setTimeout(function () {
                    persistPartial(false);
                }, 1500);
            }

            if (successShown) {
                try {
                    localStorage.removeItem(storageKey);
                } catch (e) {}
                if (partialIdInput) {
                    partialIdInput.value = '';
                }
            }

            var stored = readStored();
            if (stored && !successShown) {
                if (nameInput && !nameInput.value && stored.name) {
                    nameInput.value = stored.name;
                }
                if (phoneInput && !phoneInput.value && stored.phone) {
                    phoneInput.value = stored.phone;
                }
                if (addressInput && !addressInput.value && stored.address) {
                    addressInput.value = stored.address;
                }
                if (quantityInput && !quantityInput.value && stored.quantity) {
                    quantityInput.value = stored.quantity;
                }
                if (stored.delivery_area && areaSelect) {
                    areaSelect.value = stored.delivery_area;
                }
                if (stored.partial_id && partialIdInput) {
                    partialIdInput.value = stored.partial_id;
                }
            }

            function bindField(field, handler) {
                if (field) {
                    field.addEventListener('input', handler);
                    field.addEventListener('change', handler);
                }
            }

            bindField(nameInput, function () {
                stash(readValues());
                scheduleSave();
            });
            bindField(phoneInput, function () {
                stash(readValues());
                scheduleSave();
            });
            bindField(addressInput, function () {
                stash(readValues());
                scheduleSave();
            });
            bindField(quantityInput, function () {
                stash(readValues());
                scheduleSave();
            });
            if (areaSelect) {
                areaSelect.addEventListener('change', function () {
                    stash(readValues());
                    scheduleSave();
                });
            }

            window.addEventListener('pagehide', function () {
                if (hasValidPhone()) {
                    persistPartial(true);
                }
            });

            if (!successShown && hasValidPhone() && !(stored && stored.partial_id)) {
                window.setTimeout(function () {
                    if (hasValidPhone()) {
                        scheduleSave();
                    }
                }, 2000);
            }

            form.addEventListener('submit', function (event) {
                var colorInputs = form.querySelectorAll('input[name="color"]');
                var sizeInputs = form.querySelectorAll('input[name="size"]');
                var missing = [];
                if (colorInputs.length && !form.querySelector('input[name="color"]:checked')) {
                    missing.push('কালার');
                }
                if (sizeInputs.length && !form.querySelector('input[name="size"]:checked')) {
                    missing.push('সাইজ');
                }
                if (missing.length) {
                    event.preventDefault();
                    var errorBox = document.getElementById('landing-variant-error');
                    if (errorBox) {
                        errorBox.textContent = 'আপনাকে ' + missing.join(' ও ') + ' সিলেক্ট করতে হবে।';
                        errorBox.style.display = 'block';
                    }
                }
            });
        })();
    </script>
</body>
</html>
