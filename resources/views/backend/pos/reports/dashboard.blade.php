@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5">
    <div class="col-md-3">
        <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden text-center p-4">
            <div class="fs-14 text-white mb-2">{{ translate('Today Sales') }}</div>
            <div class="fs-20 fw-700">{{ single_price($today_sales) }}</div>
            <div class="fs-12 opacity-60">{{ $today_sales_count }} {{ translate('invoices') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden text-center p-4">
            <div class="fs-14 text-white mb-2">{{ translate('Today Profit') }}</div>
            <div class="fs-20 fw-700">{{ single_price($today_profit) }}</div>
            <div class="fs-12 opacity-60">{{ translate('after expenses') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden text-center p-4">
            <div class="fs-14 text-white mb-2">{{ translate('Today Expenses') }}</div>
            <div class="fs-20 fw-700">{{ single_price($today_expenses) }}</div>
            <div class="fs-12 opacity-60">{{ translate('total expense entries') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden text-center p-4">
            <div class="fs-14 text-white mb-2">{{ translate('Low Stock Items') }}</div>
            <div class="fs-20 fw-700">{{ $low_stock->count() }}</div>
            <div class="fs-12 opacity-60">{{ translate('below alert qty') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-600">{{ translate('Low Stock Alert') }}</h6>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Product') }}</th>
                    <th data-breakpoints="md">{{ translate('SKU') }}</th>
                    <th>{{ translate('Available Stock') }}</th>
                    <th>{{ translate('Alert Qty') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($low_stock as $product)
                    @php
                        $stock = $product->stocks->where('variant', '')->first() ?: $product->stocks->first();
                        $sku = $stock && $stock->sku ? $stock->sku : ($product->barcode ?: 'P-' . $product->id);
                    @endphp
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->getTranslation('name') }}</td>
                        <td>{{ $sku }}</td>
                        <td><span class="badge badge-inline badge-soft-danger">{{ \App\Services\PosStockService::stock($product->id) }}</span></td>
                        <td>{{ $product->alert_qty }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">{{ translate('No low stock items — all good') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex">
            <a href="{{ route('pos.reports.profit_loss') }}" class="btn btn-soft-primary btn-sm mr-2"><i class="las la-chart-line"></i> {{ translate('Profit / Loss Report') }}</a>
            <a href="{{ route('pos.reports.dues') }}" class="btn btn-soft-primary btn-sm mr-2"><i class="las la-file-invoice-dollar"></i> {{ translate('Due Report') }}</a>
            <a href="{{ route('pos.inventory.index') }}" class="btn btn-soft-primary btn-sm"><i class="las la-boxes"></i> {{ translate('Inventory') }}</a>
        </div>
    </div>
</div>
@endsection
