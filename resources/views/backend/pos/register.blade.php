@extends('backend.layouts.app')

@section('content')
<div class="pos-register-page">
    <div class="row gutters-15 pos-register-layout">
        <div class="col-xl-8">
            <section class="pos-product-panel">
                <div class="pos-search-panel">
                    <div class="pos-search-field">
                        <i class="las la-search"></i>
                        <input type="search" id="pos-product-search" autocomplete="off" placeholder="{{ translate('Search by product name, SKU or barcode') }}">
                        <kbd>/</kbd>
                    </div>
                    <div class="pos-filter-selects">
                        <select id="pos-category-filter" class="form-control">
                            <option value="">{{ translate('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                        <select id="pos-brand-filter" class="form-control">
                            <option value="">{{ translate('All Brands') }}</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pos-category-chips" id="pos-category-chips">
                    <button type="button" class="pos-category-chip is-active" data-category="">{{ translate('All Products') }}</button>
                    @foreach($categories->take(9) as $category)
                        <button type="button" class="pos-category-chip" data-category="{{ $category->id }}">{{ $category->getTranslation('name') }}</button>
                    @endforeach
                </div>

                <div class="pos-panel-heading">
                    <div>
                        <h2>{{ translate('Products') }}</h2>
                        <span id="pos-product-count">{{ $stocks->count() }} {{ translate('shown') }}</span>
                    </div>
                    <span class="pos-keyboard-tip"><i class="las la-mouse-pointer"></i> {{ translate('Select a product to add it') }}</span>
                </div>

                <div class="pos-product-grid" id="pos-product-grid" data-next-page="{{ $stocks->hasMorePages() ? $stocks->currentPage() + 1 : '' }}">
                    @foreach($stocks as $stock)
                        @php
                            $product = $stock->product;
                            $price = $stock->price !== null ? (float) $stock->price : (float) $product->unit_price;
                            $sku = $stock->sku ?: ($product->barcode ?: 'P-' . $product->id);
                            $image = uploaded_asset($stock->image ?: $product->thumbnail_img);
                            $inStock = (float) $stock->qty > 0;
                        @endphp
                        <article class="pos-product-tile {{ !$inStock ? 'is-out-of-stock' : '' }}">
                            <button type="button"
                                class="pos-product-button js-add-product"
                                data-stock-id="{{ $stock->id }}"
                                data-product-id="{{ $product->id }}"
                                data-name="{{ $product->getTranslation('name') }}"
                                data-variant="{{ $stock->variant }}"
                                data-sku="{{ $sku }}"
                                data-price="{{ $price }}"
                                data-stock="{{ (float) $stock->qty }}"
                                data-image="{{ $image }}"
                                {{ !$inStock ? 'disabled' : '' }}>
                                <div class="pos-product-image-wrap">
                                    <img src="{{ $image }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <span class="pos-stock-pill {{ $inStock ? 'is-available' : 'is-unavailable' }}">
                                        {{ $inStock ? translate('Stock') . ': ' . rtrim(rtrim(number_format($stock->qty, 2, '.', ''), '0'), '.') : translate('Out of stock') }}
                                    </span>
                                    <span class="pos-add-mark"><i class="las la-plus"></i></span>
                                </div>
                                <div class="pos-product-meta">
                                    <div class="pos-product-name">{{ $product->getTranslation('name') }}</div>
                                    <div class="pos-product-subline">
                                        <span>{{ $sku }}</span>
                                        @if($stock->variant)
                                            <span>{{ $stock->variant }}</span>
                                        @endif
                                    </div>
                                    <div class="pos-product-price">{{ single_price($price) }}</div>
                                </div>
                            </button>
                        </article>
                    @endforeach
                </div>

                <div class="pos-grid-empty d-none" id="pos-product-empty">
                    <i class="las la-search"></i>
                    <strong>{{ translate('No products found') }}</strong>
                    <span>{{ translate('Try another product name, SKU, barcode or filter.') }}</span>
                </div>

                <div class="text-center pt-3" id="pos-load-more-wrap" {{ !$stocks->hasMorePages() ? 'style=display:none' : '' }}>
                    <button type="button" class="btn btn-soft-primary px-4" id="pos-load-more"><i class="las la-plus-circle"></i> {{ translate('Load More Products') }}</button>
                </div>
            </section>
        </div>

        <div class="col-xl-4 mt-3 mt-xl-0">
            <aside class="pos-cart-panel">
                <div class="pos-cart-header">
                    <div>
                        <span class="pos-eyebrow"><i class="las la-shopping-bag"></i> {{ translate('Current Sale') }}</span>
                        <h2>{{ translate('Cart') }} <span id="pos-cart-count">0</span></h2>
                    </div>
                    <button type="button" class="btn btn-icon btn-soft-danger btn-sm" id="pos-clear-cart" title="{{ translate('Clear cart') }}" aria-label="{{ translate('Clear cart') }}"><i class="las la-trash-alt"></i></button>
                </div>

                <div class="pos-customer-row">
                    <div class="pos-field-label">{{ translate('Customer') }}</div>
                    <div class="d-flex align-items-center">
                        <select class="form-control" id="pos-customer">
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $customer->id == $walkInCustomer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}{{ $customer->mobile ? ' · ' . $customer->mobile : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-soft-primary btn-icon ml-2" id="pos-new-customer" title="{{ translate('Add customer') }}" aria-label="{{ translate('Add customer') }}"><i class="las la-user-plus"></i></button>
                    </div>
                </div>

                <div class="pos-hold-row">
                    <select class="form-control form-control-sm" id="pos-hold-select">
                        <option value="">{{ translate('Resume held sale') }}{{ $holds->count() ? ' (' . $holds->count() . ')' : '' }}</option>
                        @foreach($holds as $hold)
                            <option value="{{ $hold->id }}">{{ $hold->hold_no }} · {{ $hold->customer ? $hold->customer->name : translate('Walk-in Customer') }} · {{ single_price($hold->grand_total) }}</option>
                        @endforeach
                    </select>
                    <span id="pos-active-hold"></span>
                </div>

                <div class="pos-cart-items c-scrollbar-light" id="pos-cart-items"></div>

                <div class="pos-cart-summary">
                    <div class="pos-summary-line"><span>{{ translate('Subtotal') }}</span><strong id="pos-subtotal">0.00</strong></div>
                    <div class="pos-summary-control">
                        <label for="pos-discount">{{ translate('Discount') }}</label>
                        <div class="input-group input-group-sm">
                            <input type="number" id="pos-discount" class="form-control text-right" min="0" step="0.01" value="0">
                            <div class="input-group-append">
                                <select id="pos-discount-type" class="form-control form-control-sm">
                                    <option value="flat">{{ currency_symbol() }}</option>
                                    <option value="percent">%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pos-summary-control">
                        <label for="pos-other-charges">{{ translate('Other charges') }}</label>
                        <input type="number" id="pos-other-charges" class="form-control form-control-sm text-right" min="0" step="0.01" value="0">
                    </div>
                    <div class="pos-grand-total">
                        <span>{{ translate('Grand Total') }}</span>
                        <strong id="pos-grand-total">0.00</strong>
                    </div>
                </div>

                <div class="pos-cart-actions">
                    <button type="button" class="btn btn-soft-warning" id="pos-hold-btn" disabled><i class="las la-pause-circle"></i> {{ translate('Hold') }}</button>
                    <button type="button" class="btn btn-primary" id="pos-pay-btn" disabled><i class="las la-arrow-right"></i> {{ translate('Checkout') }}</button>
                </div>
            </aside>
        </div>
    </div>
</div>

<div class="modal fade" id="pos-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content pos-payment-modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <div class="pos-eyebrow"><i class="las la-wallet"></i> {{ translate('Checkout') }}</div>
                    <h5 class="modal-title">{{ translate('Receive Payment') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span>&times;</span></button>
            </div>
            <div class="modal-body pt-3">
                <div class="pos-payment-total-card">
                    <span>{{ translate('Amount due') }}</span>
                    <strong id="pos-modal-total">0.00</strong>
                </div>

                <div class="form-group">
                    <label class="pos-field-label">{{ translate('Payment method') }}</label>
                    <div class="pos-payment-methods" id="pos-payment-methods">
                        @foreach(\App\Http\Controllers\PosController::PAYMENT_METHODS as $method)
                            <button type="button" class="pos-payment-method {{ $method == 'Cash' ? 'is-selected' : '' }}" data-method="{{ $method }}">{{ $method }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="row gutters-10">
                    <div class="col-sm-7">
                        <div class="form-group mb-2">
                            <label class="pos-field-label" for="pos-paid">{{ translate('Amount received') }}</label>
                            <input type="number" class="form-control form-control-lg text-right" id="pos-paid" min="0" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="pos-payment-balance">
                            <span id="pos-balance-label">{{ translate('Change') }}</span>
                            <strong id="pos-change">0.00</strong>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="pos-field-label" for="pos-note">{{ translate('Note') }} <small>{{ translate('(optional)') }}</small></label>
                    <input type="text" class="form-control" id="pos-note" maxlength="500" placeholder="{{ translate('Add a short note for this sale') }}">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="button" class="btn btn-primary px-4" id="pos-confirm-btn"><i class="las la-check-circle"></i> {{ translate('Complete Sale') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pos-customer-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" id="pos-customer-form">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ translate('Name') }} *</label>
                    <input type="text" class="form-control" name="name" required autocomplete="name">
                </div>
                <div class="row gutters-10">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ translate('Mobile') }}</label>
                            <input type="text" class="form-control" name="mobile" autocomplete="tel">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>{{ translate('Email') }}</label>
                            <input type="email" class="form-control" name="email" autocomplete="email">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label>{{ translate('Address') }}</label>
                    <input type="text" class="form-control" name="address" autocomplete="street-address">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="submit" class="btn btn-primary" id="pos-save-customer"><i class="las la-user-plus"></i> {{ translate('Add Customer') }}</button>
            </div>
        </form>
    </div>
</div>

<style>
    .pos-register-page {
        --pos-ink: #102a43;
        --pos-muted: #667085;
        --pos-line: #e7edf3;
        --pos-canvas: #f5f8fb;
        --pos-accent: #0f766e;
        --pos-accent-dark: #115e59;
        --pos-gold: #f59e0b;
        --pos-danger: #dc2626;
        color: var(--pos-ink);
    }
    .pos-eyebrow {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: inherit;
        opacity: .72;
    }
    .pos-product-panel,
    .pos-cart-panel {
        border: 1px solid var(--pos-line);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(16, 42, 67, .05);
    }
    .pos-product-panel { min-height: 670px; padding: 16px; }
    .pos-search-panel {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--pos-line);
    }
    .pos-search-field { position: relative; flex: 1 1 300px; }
    .pos-search-field > i {
        position: absolute;
        top: 50%;
        left: 13px;
        z-index: 1;
        color: var(--pos-muted);
        font-size: 18px;
        transform: translateY(-50%);
    }
    .pos-search-field input {
        width: 100%;
        height: 43px;
        padding: 0 44px 0 39px;
        border: 1px solid #cfd9e4;
        border-radius: 8px;
        background: #fbfdff;
        color: var(--pos-ink);
        outline: 0;
        transition: border-color .2s, box-shadow .2s;
    }
    .pos-search-field input:focus { border-color: var(--pos-accent); box-shadow: 0 0 0 3px rgba(15, 118, 110, .12); }
    .pos-search-field kbd {
        position: absolute;
        top: 50%;
        right: 10px;
        padding: 1px 6px;
        border: 1px solid #d8e0e9;
        border-radius: 4px;
        color: #7c8a9a;
        background: #fff;
        font-size: 11px;
        transform: translateY(-50%);
    }
    .pos-filter-selects { display: flex; gap: 8px; }
    .pos-filter-selects select { width: 160px; height: 43px; border-color: #cfd9e4; color: #415264; }
    .pos-category-chips { display: flex; gap: 8px; overflow-x: auto; padding: 14px 0 12px; scrollbar-width: thin; }
    .pos-category-chip {
        flex: 0 0 auto;
        padding: 7px 12px;
        border: 1px solid #dce5ed;
        border-radius: 999px;
        color: #516274;
        background: #fff;
        font-size: 12px;
        font-weight: 600;
        transition: .2s ease;
    }
    .pos-category-chip:hover,
    .pos-category-chip.is-active { border-color: var(--pos-accent); color: #fff; background: var(--pos-accent); }
    .pos-panel-heading { display: flex; justify-content: space-between; align-items: baseline; padding: 2px 0 13px; }
    .pos-panel-heading h2,
    .pos-cart-header h2 { margin: 0; color: var(--pos-ink); font-size: 17px; font-weight: 700; }
    .pos-panel-heading span { color: var(--pos-muted); font-size: 12px; }
    .pos-keyboard-tip { display: inline-flex; align-items: center; gap: 4px; }
    .pos-product-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 11px; }
    .pos-product-tile { min-width: 0; }
    .pos-product-button {
        position: relative;
        display: block;
        width: 100%;
        min-height: 226px;
        overflow: hidden;
        border: 1px solid #e2eaf1;
        border-radius: 9px;
        color: inherit;
        background: #fff;
        text-align: left;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }
    .pos-product-button:hover:not(:disabled) { border-color: rgba(15, 118, 110, .52); box-shadow: 0 8px 18px rgba(15, 118, 110, .11); transform: translateY(-2px); }
    .pos-product-button:disabled { cursor: not-allowed; opacity: .58; }
    .pos-product-image-wrap { position: relative; height: 125px; overflow: hidden; background: var(--pos-canvas); }
    .pos-product-image-wrap img { display: block; width: 100%; height: 100%; object-fit: contain; mix-blend-mode: multiply; transition: transform .25s ease; }
    .pos-product-button:hover:not(:disabled) .pos-product-image-wrap img { transform: scale(1.06); }
    .pos-stock-pill {
        position: absolute;
        top: 8px;
        left: 8px;
        max-width: calc(100% - 16px);
        padding: 3px 7px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
    }
    .pos-stock-pill.is-available { color: #166534; background: #dcfce7; }
    .pos-stock-pill.is-unavailable { color: #991b1b; background: #fee2e2; }
    .pos-add-mark {
        position: absolute;
        right: 8px;
        bottom: 8px;
        display: grid;
        width: 28px;
        height: 28px;
        place-items: center;
        border-radius: 50%;
        color: #fff;
        background: var(--pos-accent);
        box-shadow: 0 4px 10px rgba(15, 118, 110, .3);
        opacity: 0;
        transform: scale(.75);
        transition: .18s ease;
    }
    .pos-product-button:hover:not(:disabled) .pos-add-mark { opacity: 1; transform: scale(1); }
    .pos-product-meta { padding: 10px; }
    .pos-product-name { display: -webkit-box; overflow: hidden; min-height: 35px; -webkit-box-orient: vertical; -webkit-line-clamp: 2; color: #20364d; font-size: 12px; font-weight: 700; line-height: 1.45; }
    .pos-product-subline { display: flex; gap: 5px; overflow: hidden; margin-top: 5px; color: #718096; font-size: 10px; white-space: nowrap; text-overflow: ellipsis; }
    .pos-product-subline span + span::before { content: '•'; padding-right: 5px; }
    .pos-product-price { margin-top: 8px; color: var(--pos-accent-dark); font-size: 14px; font-weight: 800; }
    .pos-grid-empty { min-height: 290px; place-content: center; justify-items: center; gap: 7px; color: var(--pos-muted); text-align: center; }
    .pos-grid-empty:not(.d-none) { display: grid; }
    .pos-grid-empty i { color: #9aa9b8; font-size: 38px; }
    .pos-grid-empty strong { color: var(--pos-ink); }
    .pos-grid-empty span { font-size: 12px; }
    .pos-cart-panel { position: sticky; top: 76px; overflow: hidden; }
    .pos-cart-header { display: flex; align-items: center; justify-content: space-between; padding: 17px 17px 12px; border-bottom: 1px solid var(--pos-line); }
    .pos-cart-header h2 { margin-top: 4px; }
    .pos-cart-header h2 span { display: inline-grid; min-width: 21px; min-height: 21px; place-items: center; margin-left: 5px; padding: 0 5px; border-radius: 999px; color: #fff; background: var(--pos-accent); font-size: 11px; vertical-align: 2px; }
    .pos-customer-row { padding: 13px 17px 10px; }
    .pos-field-label { display: block; margin-bottom: 6px; color: #63758a; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .pos-customer-row select { height: 38px; border-color: #d5e0e9; font-size: 13px; }
    .pos-hold-row { display: flex; align-items: center; gap: 8px; padding: 0 17px 10px; }
    .pos-hold-row select { flex: 1; color: #66778a; border-color: #d5e0e9; }
    #pos-active-hold { color: #a16207; font-size: 11px; font-weight: 700; white-space: nowrap; }
    .pos-cart-items { min-height: 185px; max-height: 360px; overflow-y: auto; border-top: 1px solid var(--pos-line); border-bottom: 1px solid var(--pos-line); }
    .pos-cart-empty { display: grid; min-height: 185px; place-content: center; justify-items: center; gap: 6px; color: #8a99a8; text-align: center; }
    .pos-cart-empty i { font-size: 35px; color: #b5c2cf; }
    .pos-cart-empty strong { color: #53677a; font-size: 13px; }
    .pos-cart-empty span { max-width: 210px; font-size: 11px; }
    .pos-cart-item { display: grid; grid-template-columns: 42px minmax(0, 1fr) auto; gap: 9px; align-items: center; padding: 10px 17px; border-bottom: 1px solid #edf2f6; }
    .pos-cart-item:last-child { border-bottom: 0; }
    .pos-cart-item img { width: 42px; height: 42px; object-fit: contain; border-radius: 6px; background: var(--pos-canvas); }
    .pos-cart-item-name { overflow: hidden; color: #1f344a; font-size: 12px; font-weight: 700; white-space: nowrap; text-overflow: ellipsis; }
    .pos-cart-item-meta { margin-top: 2px; overflow: hidden; color: #77899a; font-size: 10px; white-space: nowrap; text-overflow: ellipsis; }
    .pos-cart-item-price { margin-top: 3px; color: #516274; font-size: 11px; }
    .pos-cart-side { min-width: 89px; text-align: right; }
    .pos-cart-line-total { color: #17344d; font-size: 12px; font-weight: 800; }
    .pos-qty-control { display: flex; justify-content: flex-end; align-items: center; gap: 3px; margin-top: 6px; }
    .pos-qty-control button { width: 22px; height: 22px; padding: 0; border: 1px solid #d9e3eb; border-radius: 4px; color: #456077; background: #fff; line-height: 1; }
    .pos-qty-control input { width: 30px; height: 22px; padding: 0; border: 0; color: #1f344a; background: transparent; font-size: 11px; font-weight: 700; text-align: center; outline: 0; }
    .pos-cart-remove { margin-left: 5px; border: 0; color: #c2410c; background: transparent; font-size: 14px; }
    .pos-cart-summary { padding: 13px 17px 8px; }
    .pos-summary-line, .pos-summary-control { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 9px; color: #64778a; font-size: 12px; }
    .pos-summary-line strong { color: #344a60; }
    .pos-summary-control label { margin: 0; white-space: nowrap; }
    .pos-summary-control .input-group { width: 148px; }
    .pos-summary-control input { min-width: 0; border-color: #d6e0e8; }
    .pos-summary-control select { width: 43px; padding: 0 2px; border-color: #d6e0e8; font-size: 11px; }
    .pos-grand-total { display: flex; justify-content: space-between; align-items: baseline; margin-top: 12px; padding-top: 12px; border-top: 1px dashed #cbd8e3; color: var(--pos-ink); font-size: 14px; font-weight: 700; }
    .pos-grand-total strong { color: var(--pos-accent-dark); font-size: 22px; letter-spacing: -.03em; }
    .pos-cart-actions { display: grid; grid-template-columns: .9fr 1.3fr; gap: 8px; padding: 10px 17px 17px; }
    .pos-cart-actions .btn { min-height: 42px; font-weight: 700; }
    .pos-payment-modal-content { overflow: hidden; border: 0; border-radius: 13px; }
    .pos-payment-modal-content .modal-header, .pos-payment-modal-content .modal-body, .pos-payment-modal-content .modal-footer { padding-left: 24px; padding-right: 24px; }
    .pos-payment-total-card { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding: 16px; border-radius: 9px; color: #e6fffb; background: linear-gradient(120deg, #0f766e, #115e59); }
    .pos-payment-total-card span { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; opacity: .8; }
    .pos-payment-total-card strong { font-size: 26px; letter-spacing: -.03em; }
    .pos-payment-methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; }
    .pos-payment-method { padding: 9px 4px; border: 1px solid #d9e4ec; border-radius: 6px; color: #5e7184; background: #fff; font-size: 12px; font-weight: 700; transition: .15s ease; }
    .pos-payment-method:hover, .pos-payment-method.is-selected { border-color: var(--pos-accent); color: #fff; background: var(--pos-accent); }
    .pos-payment-balance { display: flex; min-height: 74px; flex-direction: column; justify-content: center; padding-left: 15px; border-left: 1px solid var(--pos-line); }
    .pos-payment-balance span { color: #718196; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
    .pos-payment-balance strong { margin-top: 4px; color: #15803d; font-size: 20px; }
    @media (max-width: 1199px) { .pos-cart-panel { position: static; } }
    @media (max-width: 991px) { .pos-product-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
    @media (max-width: 767px) {
        .pos-search-panel { align-items: stretch; flex-direction: column; }
        .pos-filter-selects { width: 100%; }
        .pos-filter-selects select { flex: 1; width: auto; }
        .pos-product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 9px; }
        .pos-product-button { min-height: 214px; }
        .pos-keyboard-tip { display: none; }
        .pos-cart-items { max-height: none; }
    }
    @media (max-width: 420px) {
        .pos-product-panel { padding: 11px; }
        .pos-product-image-wrap { height: 110px; }
        .pos-product-meta { padding: 8px; }
        .pos-cart-item { padding-left: 12px; padding-right: 12px; }
        .pos-summary-control .input-group { width: 132px; }
    }
</style>
@endsection

@section('script')
<script type="text/javascript">
(function () {
    var storageKey = 'pos_register_cart_v2_{{ auth()->id() }}';
    var csrf = '{{ csrf_token() }}';
    var currency = {!! json_encode(currency_symbol()) !!};
    var routes = {
        products: {!! json_encode(route('pos.products.search')) !!},
        hold: {!! json_encode(route('pos.holds.store')) !!},
        holds: {!! json_encode(route('pos.holds.list')) !!},
        sale: {!! json_encode(route('pos.store')) !!},
        customer: {!! json_encode(route('pos.customers.store')) !!}
    };
    var grid = document.getElementById('pos-product-grid');
    var productSearch = document.getElementById('pos-product-search');
    var categoryFilter = document.getElementById('pos-category-filter');
    var brandFilter = document.getElementById('pos-brand-filter');
    var customerSelect = document.getElementById('pos-customer');
    var holdSelect = document.getElementById('pos-hold-select');
    var cartItems = document.getElementById('pos-cart-items');
    var cartCount = document.getElementById('pos-cart-count');
    var activeHoldLabel = document.getElementById('pos-active-hold');
    var loadMoreWrap = document.getElementById('pos-load-more-wrap');
    var loadMoreButton = document.getElementById('pos-load-more');
    var emptyProducts = document.getElementById('pos-product-empty');
    var searchTimer = null;
    var loadingProducts = false;
    var paymentMethod = 'Cash';

    function loadState() {
        try {
            var saved = JSON.parse(localStorage.getItem(storageKey));
            if (Array.isArray(saved)) {
                return { items: saved, holdId: null };
            }
            return {
                items: saved && Array.isArray(saved.items) ? saved.items : [],
                holdId: saved && saved.holdId ? parseInt(saved.holdId, 10) : null
            };
        } catch (error) {
            return { items: [], holdId: null };
        }
    }

    var savedState = loadState();
    var cart = savedState.items;
    var activeHoldId = savedState.holdId;
    var nextPage = grid.getAttribute('data-next-page') ? parseInt(grid.getAttribute('data-next-page'), 10) : null;

    function saveState() {
        try {
            localStorage.setItem(storageKey, JSON.stringify({ items: cart, holdId: activeHoldId }));
        } catch (error) {
            // Local storage is optional. The active checkout remains usable without it.
        }
    }

    function clearSavedState() {
        try { localStorage.removeItem(storageKey); } catch (error) {}
    }

    function money(amount) {
        return currency + Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function notify(type, message) {
        if (window.AIZ && AIZ.plugins && AIZ.plugins.notify) {
            AIZ.plugins.notify(type, message);
            return;
        }
        window.alert(message);
    }

    function productKey(product) {
        return String(product.stock_id || ('product-' + product.product_id));
    }

    function totals() {
        var subtotal = cart.reduce(function (sum, item) {
            return sum + (Number(item.price) * Number(item.qty));
        }, 0);
        var discountInput = Math.max(0, parseFloat(document.getElementById('pos-discount').value) || 0);
        var discountType = document.getElementById('pos-discount-type').value;
        var discount = discountType === 'percent'
            ? Math.min(subtotal, subtotal * discountInput / 100)
            : Math.min(subtotal, discountInput);
        var charges = Math.max(0, parseFloat(document.getElementById('pos-other-charges').value) || 0);
        var beforeRound = Math.max(0, subtotal - discount + charges);
        var grand = Math.round(beforeRound);

        document.getElementById('pos-subtotal').textContent = money(subtotal);
        document.getElementById('pos-grand-total').textContent = money(grand);
        document.getElementById('pos-pay-btn').disabled = cart.length === 0;
        document.getElementById('pos-hold-btn').disabled = cart.length === 0;

        return {
            subtotal: subtotal,
            discountInput: discountInput,
            discountType: discountType,
            discount: discount,
            charges: charges,
            grand: grand
        };
    }

    function renderCart() {
        var count = cart.reduce(function (sum, item) { return sum + Number(item.qty); }, 0);
        cartCount.textContent = count;

        if (!cart.length) {
            cartItems.innerHTML = '<div class="pos-cart-empty"><i class="las la-shopping-basket"></i><strong>{{ translate('Your cart is empty') }}</strong><span>{{ translate('Choose a product from the left to begin a sale.') }}</span></div>';
        } else {
            cartItems.innerHTML = cart.map(function (item, index) {
                var descriptor = [item.sku, item.variant].filter(Boolean).map(escapeHtml).join(' · ');
                return '<div class="pos-cart-item">' +
                    '<img src="' + escapeHtml(item.image) + '" alt="" onerror="this.onerror=null;this.src=\'' + escapeHtml('{{ static_asset('assets/img/placeholder.jpg') }}') + '\';">' +
                    '<div class="overflow-hidden">' +
                        '<div class="pos-cart-item-name">' + escapeHtml(item.name) + '</div>' +
                        '<div class="pos-cart-item-meta">' + descriptor + '</div>' +
                        '<div class="pos-cart-item-price">' + money(item.price) + '</div>' +
                    '</div>' +
                    '<div class="pos-cart-side">' +
                        '<div class="pos-cart-line-total">' + money(item.price * item.qty) + '</div>' +
                        '<div class="pos-qty-control">' +
                            '<button type="button" class="js-qty-down" data-index="' + index + '" aria-label="{{ translate('Decrease quantity') }}">-</button>' +
                            '<input type="number" class="js-qty-input" data-index="' + index + '" min="1" max="' + Number(item.stock || 999999) + '" step="1" value="' + Number(item.qty) + '">' +
                            '<button type="button" class="js-qty-up" data-index="' + index + '" aria-label="{{ translate('Increase quantity') }}">+</button>' +
                            '<button type="button" class="pos-cart-remove js-cart-remove" data-index="' + index + '" aria-label="{{ translate('Remove item') }}"><i class="las la-times"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            }).join('');
        }

        activeHoldLabel.textContent = activeHoldId ? '{{ translate('Editing held sale') }}' : '';
        totals();
        saveState();
    }

    function addProduct(product) {
        var available = Number(product.stock || 0);
        if (available <= 0) {
            notify('warning', '{{ translate('This product is out of stock') }}');
            return;
        }

        var key = productKey(product);
        var existing = cart.find(function (item) { return productKey(item) === key; });
        if (existing) {
            if (Number(existing.qty) >= available) {
                notify('warning', '{{ translate('Maximum available stock reached') }}');
                return;
            }
            existing.qty = Number(existing.qty) + 1;
        } else {
            cart.push({
                stock_id: Number(product.stock_id) || null,
                product_id: Number(product.product_id),
                name: product.name,
                variant: product.variant || '',
                sku: product.sku || '',
                price: Number(product.price),
                qty: 1,
                stock: available,
                image: product.image
            });
        }
        renderCart();
    }

    function productFromButton(button) {
        return {
            stock_id: button.getAttribute('data-stock-id'),
            product_id: button.getAttribute('data-product-id'),
            name: button.getAttribute('data-name'),
            variant: button.getAttribute('data-variant'),
            sku: button.getAttribute('data-sku'),
            price: button.getAttribute('data-price'),
            stock: button.getAttribute('data-stock'),
            image: button.getAttribute('data-image')
        };
    }

    function productTile(product) {
        var stock = Number(product.stock || 0);
        var out = stock <= 0;
        var sku = product.sku || '';
        var variant = product.variant || '';
        return '<article class="pos-product-tile ' + (out ? 'is-out-of-stock' : '') + '">' +
            '<button type="button" class="pos-product-button js-add-product"' +
                ' data-stock-id="' + escapeHtml(product.stock_id) + '"' +
                ' data-product-id="' + escapeHtml(product.product_id) + '"' +
                ' data-name="' + escapeHtml(product.name) + '"' +
                ' data-variant="' + escapeHtml(variant) + '"' +
                ' data-sku="' + escapeHtml(sku) + '"' +
                ' data-price="' + Number(product.price || 0) + '"' +
                ' data-stock="' + stock + '"' +
                ' data-image="' + escapeHtml(product.image) + '"' + (out ? ' disabled' : '') + '>' +
                '<div class="pos-product-image-wrap">' +
                    '<img src="' + escapeHtml(product.image) + '" alt="" onerror="this.onerror=null;this.src=\'' + escapeHtml('{{ static_asset('assets/img/placeholder.jpg') }}') + '\';">' +
                    '<span class="pos-stock-pill ' + (out ? 'is-unavailable' : 'is-available') + '">' + (out ? '{{ translate('Out of stock') }}' : '{{ translate('Stock') }}: ' + stock) + '</span>' +
                    '<span class="pos-add-mark"><i class="las la-plus"></i></span>' +
                '</div>' +
                '<div class="pos-product-meta">' +
                    '<div class="pos-product-name">' + escapeHtml(product.name) + '</div>' +
                    '<div class="pos-product-subline"><span>' + escapeHtml(sku) + '</span>' + (variant ? '<span>' + escapeHtml(variant) + '</span>' : '') + '</div>' +
                    '<div class="pos-product-price">' + money(product.price) + '</div>' +
                '</div>' +
            '</button>' +
        '</article>';
    }

    function renderProducts(products, append) {
        if (!append) {
            grid.innerHTML = '';
        }
        if (products.length) {
            grid.insertAdjacentHTML('beforeend', products.map(productTile).join(''));
        }
        var total = grid.querySelectorAll('.pos-product-tile').length;
        document.getElementById('pos-product-count').textContent = total + ' {{ translate('shown') }}';
        emptyProducts.classList.toggle('d-none', total !== 0);
    }

    function fetchProducts(reset) {
        if (loadingProducts || (!reset && !nextPage)) {
            return;
        }

        loadingProducts = true;
        loadMoreButton.disabled = true;
        loadMoreButton.innerHTML = '<i class="las la-spinner la-spin"></i> {{ translate('Loading') }}';
        var form = new FormData();
        form.append('_token', csrf);
        form.append('q', productSearch.value.trim());
        form.append('category_id', categoryFilter.value);
        form.append('brand_id', brandFilter.value);
        form.append('page', reset ? 1 : nextPage);

        fetch(routes.products, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form })
            .then(function (response) {
                if (!response.ok) { throw new Error('{{ translate('Could not load products') }}'); }
                return response.json();
            })
            .then(function (data) {
                renderProducts(data.products || [], !reset);
                nextPage = data.next_page || null;
                loadMoreWrap.style.display = nextPage ? '' : 'none';
            })
            .catch(function (error) {
                notify('danger', error.message || '{{ translate('Could not load products') }}');
            })
            .finally(function () {
                loadingProducts = false;
                loadMoreButton.disabled = false;
                loadMoreButton.innerHTML = '<i class="las la-plus-circle"></i> {{ translate('Load More Products') }}';
            });
    }

    function refreshPaymentBalance() {
        var total = totals().grand;
        var paid = Math.max(0, parseFloat(document.getElementById('pos-paid').value) || 0);
        var difference = paid - total;
        var label = difference >= 0 ? '{{ translate('Change') }}' : '{{ translate('Due') }}';
        var display = Math.abs(difference);
        document.getElementById('pos-balance-label').textContent = label;
        document.getElementById('pos-change').textContent = money(display);
        document.getElementById('pos-change').style.color = difference >= 0 ? '#15803d' : '#b91c1c';
    }

    function resumeHold(holdId) {
        if (!holdId) { return; }
        fetch(routes.holds, { headers: { 'Accept': 'application/json' } })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                var hold = (data.holds || []).find(function (item) { return Number(item.id) === Number(holdId); });
                if (!hold) {
                    notify('warning', '{{ translate('Held sale was not found') }}');
                    return;
                }
                cart = (hold.items || []).map(function (item) {
                    return {
                        stock_id: item.stock_id || null,
                        product_id: Number(item.product_id),
                        name: item.name,
                        variant: item.variant || '',
                        sku: '',
                        price: Number(item.price),
                        qty: Number(item.qty),
                        stock: Math.max(Number(item.qty), Number(item.available_stock || 0)),
                        image: '{{ static_asset('assets/img/placeholder.jpg') }}'
                    };
                });
                if (hold.customer_id && customerSelect.querySelector('option[value="' + hold.customer_id + '"]')) {
                    customerSelect.value = hold.customer_id;
                }
                document.getElementById('pos-discount').value = Number(hold.discount_amount || 0);
                document.getElementById('pos-discount-type').value = 'flat';
                activeHoldId = Number(hold.id);
                holdSelect.value = '';
                renderCart();
                notify('success', '{{ translate('Held sale resumed') }}');
            })
            .catch(function () { notify('danger', '{{ translate('Could not resume held sale') }}'); });
    }

    document.addEventListener('click', function (event) {
        var addButton = event.target.closest('.js-add-product');
        if (addButton) {
            addProduct(productFromButton(addButton));
            return;
        }

        var decreaseButton = event.target.closest('.js-qty-down');
        if (decreaseButton) {
            var decreaseIndex = Number(decreaseButton.getAttribute('data-index'));
            if (cart[decreaseIndex]) {
                cart[decreaseIndex].qty = Math.max(1, Number(cart[decreaseIndex].qty) - 1);
                renderCart();
            }
            return;
        }

        var increaseButton = event.target.closest('.js-qty-up');
        if (increaseButton) {
            var increaseIndex = Number(increaseButton.getAttribute('data-index'));
            if (cart[increaseIndex]) {
                var maximum = Number(cart[increaseIndex].stock || 999999);
                if (Number(cart[increaseIndex].qty) < maximum) {
                    cart[increaseIndex].qty = Number(cart[increaseIndex].qty) + 1;
                    renderCart();
                } else {
                    notify('warning', '{{ translate('Maximum available stock reached') }}');
                }
            }
            return;
        }

        var removeButton = event.target.closest('.js-cart-remove');
        if (removeButton) {
            cart.splice(Number(removeButton.getAttribute('data-index')), 1);
            renderCart();
        }
    });

    cartItems.addEventListener('change', function (event) {
        if (!event.target.classList.contains('js-qty-input')) { return; }
        var index = Number(event.target.getAttribute('data-index'));
        var item = cart[index];
        if (!item) { return; }
        var quantity = Math.max(1, parseFloat(event.target.value) || 1);
        item.qty = Math.min(quantity, Number(item.stock || quantity));
        renderCart();
    });

    productSearch.addEventListener('input', function () {
        window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(function () { fetchProducts(true); }, 260);
    });
    categoryFilter.addEventListener('change', function () { fetchProducts(true); });
    brandFilter.addEventListener('change', function () { fetchProducts(true); });
    loadMoreButton.addEventListener('click', function () { fetchProducts(false); });
    document.getElementById('pos-category-chips').addEventListener('click', function (event) {
        var chip = event.target.closest('.pos-category-chip');
        if (!chip) { return; }
        categoryFilter.value = chip.getAttribute('data-category');
        document.querySelectorAll('.pos-category-chip').forEach(function (button) {
            button.classList.toggle('is-active', button === chip);
        });
        fetchProducts(true);
    });
    document.getElementById('pos-discount').addEventListener('input', renderCart);
    document.getElementById('pos-discount-type').addEventListener('change', renderCart);
    document.getElementById('pos-other-charges').addEventListener('input', renderCart);
    holdSelect.addEventListener('change', function () { resumeHold(holdSelect.value); });

    document.getElementById('pos-clear-cart').addEventListener('click', function () {
        if (!cart.length) { return; }
        cart = [];
        activeHoldId = null;
        clearSavedState();
        renderCart();
    });

    document.getElementById('pos-hold-btn').addEventListener('click', function () {
        if (!cart.length) { return; }
        var button = this;
        button.disabled = true;
        var form = new FormData();
        form.append('_token', csrf);
        form.append('customer_id', customerSelect.value);
        form.append('discount', totals().discount);
        form.append('items', JSON.stringify(cart));
        fetch(routes.hold, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form })
            .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
            .then(function (result) {
                if (!result.ok || !result.data.ok) { throw new Error(result.data.message || '{{ translate('Could not hold sale') }}'); }
                cart = [];
                activeHoldId = null;
                clearSavedState();
                renderCart();
                window.location.reload();
            })
            .catch(function (error) { notify('danger', error.message || '{{ translate('Could not hold sale') }}'); })
            .finally(function () { button.disabled = cart.length === 0; });
    });

    document.getElementById('pos-pay-btn').addEventListener('click', function () {
        if (!cart.length) { return; }
        var currentTotals = totals();
        document.getElementById('pos-modal-total').textContent = money(currentTotals.grand);
        document.getElementById('pos-paid').value = currentTotals.grand;
        paymentMethod = 'Cash';
        document.querySelectorAll('.pos-payment-method').forEach(function (button) {
            button.classList.toggle('is-selected', button.getAttribute('data-method') === paymentMethod);
        });
        refreshPaymentBalance();
        $('#pos-payment-modal').modal('show');
    });

    document.getElementById('pos-payment-methods').addEventListener('click', function (event) {
        var methodButton = event.target.closest('.pos-payment-method');
        if (!methodButton) { return; }
        paymentMethod = methodButton.getAttribute('data-method');
        document.querySelectorAll('.pos-payment-method').forEach(function (button) {
            button.classList.toggle('is-selected', button === methodButton);
        });
    });
    document.getElementById('pos-paid').addEventListener('input', refreshPaymentBalance);

    document.getElementById('pos-confirm-btn').addEventListener('click', function () {
        if (!cart.length) { return; }
        var button = this;
        var currentTotals = totals();
        button.disabled = true;
        var form = new FormData();
        form.append('_token', csrf);
        form.append('customer_id', customerSelect.value);
        form.append('hold_id', activeHoldId || '');
        form.append('discount', currentTotals.discountInput);
        form.append('discount_type', currentTotals.discountType);
        form.append('other_charges', currentTotals.charges);
        form.append('paid_amount', Math.max(0, parseFloat(document.getElementById('pos-paid').value) || 0));
        form.append('payment_method', paymentMethod);
        form.append('note', document.getElementById('pos-note').value);
        form.append('items', JSON.stringify(cart));

        fetch(routes.sale, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form })
            .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
            .then(function (result) {
                if (!result.ok || !result.data.ok) { throw new Error(result.data.message || '{{ translate('Sale could not be completed') }}'); }
                cart = [];
                activeHoldId = null;
                clearSavedState();
                window.location.href = result.data.invoice_url;
            })
            .catch(function (error) {
                notify('danger', error.message || '{{ translate('Sale could not be completed') }}');
                button.disabled = false;
            });
    });

    document.getElementById('pos-new-customer').addEventListener('click', function () {
        $('#pos-customer-modal').modal('show');
    });

    document.getElementById('pos-customer-form').addEventListener('submit', function (event) {
        event.preventDefault();
        var formElement = this;
        var button = document.getElementById('pos-save-customer');
        var form = new FormData(formElement);
        form.append('_token', csrf);
        button.disabled = true;
        fetch(routes.customer, { method: 'POST', headers: { 'Accept': 'application/json' }, body: form })
            .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
            .then(function (result) {
                if (!result.ok || !result.data.ok) { throw new Error('{{ translate('Could not add customer') }}'); }
                var customer = result.data.customer;
                var option = new Option(customer.name + (customer.mobile ? ' · ' + customer.mobile : ''), customer.id, true, true);
                customerSelect.add(option);
                formElement.reset();
                $('#pos-customer-modal').modal('hide');
                notify('success', '{{ translate('Customer added successfully') }}');
            })
            .catch(function (error) { notify('danger', error.message || '{{ translate('Could not add customer') }}'); })
            .finally(function () { button.disabled = false; });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === '/' && document.activeElement !== productSearch && !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
            event.preventDefault();
            productSearch.focus();
        }
    });

    renderCart();
})();
</script>
@endsection
