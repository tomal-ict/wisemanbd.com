@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Inventory / Stock') }}</h5>
                </div>
                <div class="col-auto">
                    <form action="" method="GET" class="d-flex">
                        <input type="text" class="form-control form-control-sm mr-2" name="q" placeholder="{{ translate('Search product or SKU...') }}" value="{{ request('q') }}">
                        <button class="btn btn-primary btn-sm">{{ translate('Search') }}</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product') }}</th>
                            <th data-breakpoints="md">{{ translate('SKU') }}</th>
                            <th>{{ translate('Stock') }}</th>
                            <th>{{ translate('Alert Qty') }}</th>
                            <th data-breakpoints="md">{{ translate('Cost Price') }}</th>
                            <th data-breakpoints="md">{{ translate('Stock Value (cost)') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            @php
                                $stock = $product->stocks->where('variant', '')->first() ?: $product->stocks->first();
                                $sku = $stock && $stock->sku ? $stock->sku : ($product->barcode ?: 'P-' . $product->id);
                            @endphp
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->getTranslation('name') }}</td>
                                <td>{{ $sku }}</td>
                                <td>
                                    <span class="badge badge-inline {{ $product->alert_qty != null && $product->alert_qty > 0 && $product->total_stock <= $product->alert_qty ? 'badge-soft-danger' : 'badge-soft-success' }}">{{ $product->total_stock }}</span>
                                </td>
                                <td>{{ $product->alert_qty != null ? $product->alert_qty : 0 }}</td>
                                <td>{{ single_price($product->pos_cost_price) }}</td>
                                <td>{{ single_price((float) $product->pos_cost_price * $product->total_stock) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">{{ $products->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Stock Adjustment') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pos.inventory.adjust') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="fs-12 mb-1">{{ translate('Product') }} *</label>
                        <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                            <option value="">{{ translate('Select product') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->getTranslation('name') }} ({{ translate('Stock') }}: {{ $product->total_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="fs-12 mb-1">{{ translate('Quantity') }} * <small class="text-muted">({{ translate('use - for decrease') }})</small></label>
                        <input type="number" class="form-control" name="qty" step="0.01" placeholder="+5 / -3" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fs-12 mb-1">{{ translate('Note / Reason') }} *</label>
                        <input type="text" class="form-control" name="note" required>
                    </div>
                    <button class="btn btn-primary btn-block">{{ translate('Apply Adjustment') }}</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Recent Adjustments') }}</h6>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Product') }}</th>
                            <th class="text-right">{{ translate('Qty') }}</th>
                            <th>{{ translate('Note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($entry->entry_date)) }}</td>
                                <td>{{ $entry->product ? $entry->product->getTranslation('name') : '-' }}</td>
                                <td class="text-right">
                                    <span class="badge badge-inline {{ $entry->qty >= 0 ? 'badge-soft-success' : 'badge-soft-danger' }}">{{ $entry->qty >= 0 ? '+' : '' }}{{ $entry->qty }}</span>
                                </td>
                                <td>{{ $entry->note }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ translate('No adjustments yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
