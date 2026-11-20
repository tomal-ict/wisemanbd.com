@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header row gutters-5 align-items-center">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Profit / Loss Report') }}</h5>
        </div>
        <div class="col-auto">
            <form action="" method="GET" class="d-flex align-items-center">
                <input type="date" name="from" class="form-control form-control-sm mr-2" value="{{ $from }}">
                <input type="date" name="to" class="form-control form-control-sm mr-2" value="{{ $to }}">
                <button class="btn btn-primary btn-sm">{{ translate('Apply') }}</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row gutters-5 mb-4">
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-13 text-muted">{{ translate('Net Sales') }}</div>
                    <div class="fs-18 fw-700">{{ single_price($sales_total - $returns_total) }}</div>
                    <div class="fs-12 text-muted">{{ $sales_count }} {{ translate('invoices') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-13 text-muted">{{ translate('COGS') }}</div>
                    <div class="fs-18 fw-700">{{ single_price($cogs - $returns_cogs) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-13 text-muted">{{ translate('Gross Profit') }}</div>
                    <div class="fs-18 fw-700 {{ $gross_profit >= 0 ? 'text-success' : 'text-danger' }}">{{ single_price($gross_profit) }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-13 text-muted">{{ translate('Net Profit') }}</div>
                    <div class="fs-18 fw-700 {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">{{ single_price($net_profit) }}</div>
                </div>
            </div>
        </div>

        <div class="row gutters-5">
            <div class="col-md-8">
                <h6 class="fw-600 mb-3">{{ translate('Summary') }} ({{ $from }} — {{ $to }})</h6>
                <table class="table table-bordered table-sm fs-13">
                    <tbody>
                        <tr><td>{{ translate('Total Sales') }}</td><td class="text-right fw-600">{{ single_price($sales_total) }}</td></tr>
                        <tr><td>{{ translate('Sales Discount') }}</td><td class="text-right">-{{ single_price($discounts) }}</td></tr>
                        <tr><td>{{ translate('Sales Returns') }}</td><td class="text-right">-{{ single_price($returns_total) }}</td></tr>
                        <tr><td>{{ translate('Cost of Goods Sold') }}</td><td class="text-right">-{{ single_price($cogs) }}</td></tr>
                        <tr><td>{{ translate('COGS of Returned Items (added back)') }}</td><td class="text-right">+{{ single_price($returns_cogs) }}</td></tr>
                        <tr class="bg-soft-secondary"><td class="fw-600">{{ translate('Gross Profit') }}</td><td class="text-right fw-600">{{ single_price($gross_profit) }}</td></tr>
                        <tr><td>{{ translate('Expenses') }}</td><td class="text-right">-{{ single_price($expenses) }}</td></tr>
                        <tr class="bg-soft-secondary {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}"><td class="fw-600">{{ translate('Net Profit / Loss') }}</td><td class="text-right fw-600">{{ single_price($net_profit) }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h6 class="fw-600 mb-3">{{ translate('Other Figures') }}</h6>
                <table class="table table-bordered table-sm fs-13">
                    <tbody>
                        <tr><td>{{ translate('Purchases (period)') }}</td><td class="text-right">{{ single_price($purchases_total) }}</td></tr>
                        <tr><td>{{ translate('Purchase Returns (period)') }}</td><td class="text-right">{{ single_price($purchase_returns_total) }}</td></tr>
                        <tr><td>{{ translate('Inventory Units') }}</td><td class="text-right">{{ $items_qty }}</td></tr>
                        <tr><td>{{ translate('Inventory Lines') }}</td><td class="text-right">{{ $items_count }}</td></tr>
                        <tr><td>{{ translate('Stock Value (retail)') }}</td><td class="text-right">{{ single_price($stock_value) }}</td></tr>
                        <tr><td>{{ translate('Stock Value (cost)') }}</td><td class="text-right">{{ single_price($stock_cost_value) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection