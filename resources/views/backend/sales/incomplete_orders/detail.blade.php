<div class="row gutters-5 mb-3">
    <div class="col-md-6">
        <div class="bg-soft-light border rounded-lg p-3 h-100">
            <h6 class="fs-13 fw-600 text-muted mb-3">{{ translate('Customer Information') }}</h6>
            <table class="table table-sm table-borderless mb-0">
                <tbody>
                    <tr>
                        <td class="text-muted w-40">{{ translate('Name') }}</td>
                        <td><strong>{{ $incomplete_order->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Phone') }}</td>
                        <td>
                            <a href="tel:{{ $incomplete_order->phone }}" class="text-reset"><strong>{{ $incomplete_order->phone }}</strong></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Email') }}</td>
                        <td>{{ $incomplete_order->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Address') }}</td>
                        <td>{{ $incomplete_order->address }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Area/City') }}</td>
                        <td>{{ $incomplete_order->city }}</td>
                    </tr>
                    @if($incomplete_order->delivery_area != null)
                        <tr>
                            <td class="text-muted">{{ translate('Delivery Area') }}</td>
                            <td><strong>{{ $incomplete_order->delivery_area }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-muted">{{ translate('Submitted') }}</td>
                        <td>{{ date('d-m-Y h:i A', strtotime($incomplete_order->updated_at)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="bg-soft-primary border rounded-lg p-3 h-100">
            <h6 class="fs-13 fw-600 text-muted mb-3">{{ translate('Order Summary') }}</h6>
            <table class="table table-sm table-borderless mb-0">
                <tbody>
                    @php
                        $io_products = json_decode($incomplete_order->products, true);
                    @endphp
                    <tr>
                        <td class="text-muted">{{ translate('Total Products') }}</td>
                        <td class="text-right"><strong>{{ count($io_products ?: array()) }} {{ translate('items') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Subtotal') }}</td>
                        <td class="text-right">{{ single_price($incomplete_order->grand_total) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{ translate('Shipping') }}</td>
                        <td class="text-right">{{ single_price(0) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted fw-600">{{ translate('Grand Total') }}</td>
                        <td class="text-right"><strong class="fs-16 text-primary">{{ single_price($incomplete_order->grand_total) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<h6 class="fs-13 fw-600 text-muted mb-2">
    {{ translate('Products') }}
    @if($io_products)
        <span class="badge badge-primary ml-1">{{ count($io_products) }}</span>
    @endif
</h6>

@if($io_products)
    @php
        $product_ids = array_column($io_products, 'product_id');
        $product_map = \App\Models\Product::whereIn('id', $product_ids)->get()->keyBy('id');
    @endphp
    <div class="mb-3">
        @foreach($io_products as $p)
            @php
                $product = $product_map->get($p['product_id']);
                $product_slug = $product != null ? $product->slug : null;
                $product_name = $product != null ? $product->getTranslation('name') : $p['name'];
                $product_img = $product != null ? uploaded_asset($product->thumbnail_img) : static_asset('assets/img/placeholder.jpg');
            @endphp
            <div class="d-flex align-items-center border rounded-lg p-2 mb-2 bg-white">
                <a href="{{ $product_slug != null ? route('product', $product_slug) : '#' }}" class="flex-shrink-0 d-block">
                    <img
                        src="{{ $product_img }}"
                        class="img-fit rounded"
                        style="width:64px;height:64px;"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                        alt="{{ $product_name }}"
                    >
                </a>
                <div class="ml-3 flex-grow-1 min-w-0">
                    <a href="{{ $product_slug != null ? route('product', $product_slug) : '#' }}" class="d-block text-reset text-truncate fw-600 fs-13">
                        {{ $product_name }}
                    </a>
                    <div class="fs-12 text-muted">{{ translate('Product ID') }}: {{ $p['product_id'] }}</div>
                    <div class="fs-12 text-muted">{{ translate('Unit Price') }}: {{ single_price($p['price']) }}</div>
                    @if(isset($p['variation']) && $p['variation'] != '')
                        <div class="fs-12 text-muted">{{ translate('Variation') }}: <strong>{{ $p['variation'] }}</strong></div>
                    @endif
                    @if(isset($p['size']) && $p['size'] != '')
                        <div class="fs-12 text-muted">{{ translate('Size') }}: <strong>{{ $p['size'] }}</strong></div>
                    @endif
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <div class="fs-12 text-muted">{{ translate('Qty') }}: <strong>{{ $p['qty'] }}</strong></div>
                    <div class="fw-700 text-primary">{{ single_price($p['total']) }}</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-between align-items-center border-top pt-3">
        <span class="fw-600">{{ translate('Grand Total') }}</span>
        <span class="fw-700 fs-16 text-primary">{{ single_price($incomplete_order->grand_total) }}</span>
    </div>
@else
    <div class="text-center py-3">
        <i class="las la-box la-2x text-muted mb-2 d-block"></i>
        <span class="text-muted">{{ translate('No products found') }}</span>
    </div>
@endif

@php $io_status = $incomplete_order->status; @endphp
<div class="d-flex justify-content-end align-items-center border-top pt-3 mt-3">
    @if($io_status == null)
        <a href="{{ route('incomplete_orders.wait', $incomplete_order->id) }}" class="btn btn-soft-warning mr-2">
            <i class="las la-hourglass-start"></i> {{ translate('Mark as Waiting') }}
        </a>
        <a href="{{ route('incomplete_orders.confirm', $incomplete_order->id) }}" class="btn btn-soft-success">
            <i class="las la-check-circle"></i> {{ translate('Confirm & Move to All Orders') }}
        </a>
    @elseif($io_status == 'waiting')
        <a href="{{ route('incomplete_orders.back', $incomplete_order->id) }}" class="btn btn-soft-secondary mr-2">
            <i class="las la-undo"></i> {{ translate('Back to Incomplete') }}
        </a>
        <a href="{{ route('incomplete_orders.confirm', $incomplete_order->id) }}" class="btn btn-soft-success">
            <i class="las la-check-circle"></i> {{ translate('Confirm & Move to All Orders') }}
        </a>
    @endif
</div>