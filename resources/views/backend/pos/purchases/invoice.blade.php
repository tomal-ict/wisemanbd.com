@extends('backend.layouts.app')

@section('content')
<!-- Purchase Invoice -->
<div class="card mb-3">
    <div class="card-header row gutters-5 align-items-center">
        <div class="col">
            <h5 class="mb-0 fw-600">{{ translate('Purchase Invoice') }} - {{ $purchase->purchase_no }}</h5>
        </div>
        <div class="col-auto">
            <span class="badge badge-inline {{ $purchase->status == 'Received' ? 'badge-success' : ($purchase->status == 'Pending' ? 'badge-warning' : 'badge-secondary') }}">{{ $purchase->status }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6 fs-13">
                <div><strong>{{ translate('Supplier') }}:</strong> {{ $purchase->supplier ? $purchase->supplier->name : '' }}</div>
                @if($purchase->supplier && $purchase->supplier->mobile)
                    <div><strong>{{ translate('Mobile') }}:</strong> {{ $purchase->supplier->mobile }}</div>
                @endif
                @if($purchase->supplier && $purchase->supplier->address)
                    <div><strong>{{ translate('Address') }}:</strong> {{ $purchase->supplier->address }}</div>
                @endif
            </div>
            <div class="col-md-6 fs-13 text-md-right">
                <div><strong>{{ translate('Date') }}:</strong> {{ date('d-m-Y', strtotime($purchase->purchase_date)) }}</div>
                <div><strong>{{ translate('Supplier Due') }}:</strong> <span class="text-danger fw-600">{{ single_price(max(0, $supplier_due)) }}</span></div>
            </div>
        </div>
        <table class="table aiz-table mb-3">
            <thead>
                <tr>
                    <th>{{ translate('Product') }}</th>
                    <th class="text-center">{{ translate('Qty') }}</th>
                    <th class="text-right">{{ translate('Unit Cost') }}</th>
                    <th class="text-right">{{ translate('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $item)
                    <tr>
                        <td>
                            {{ $item->name }}
                            @if(($item->color ?? '') !== '' || ($item->size ?? '') !== '')
                                <div class="fs-12 text-muted">
                                    @php $variants = collect([$item->color ?? '', $item->size ?? ''])->filter()->implode(' - '); @endphp
                                    {{ $variants }}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">{{ single_price($item->unit_cost) }}</td>
                        <td class="text-right fw-600">{{ single_price($item->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <table class="table table-sm fs-13">
                    <tr><td>{{ translate('Subtotal') }}</td><td class="text-right">{{ single_price($purchase->subtotal) }}</td></tr>
                    @if($purchase->discount_amount > 0)
                        <tr><td>{{ translate('Discount') }}</td><td class="text-right">-{{ single_price($purchase->discount_amount) }}</td></tr>
                    @endif
                    @if($purchase->other_charges > 0)
                        <tr><td>{{ translate('Other charges') }}</td><td class="text-right">{{ single_price($purchase->other_charges) }}</td></tr>
                    @endif
                    @if($purchase->round_off != 0)
                        <tr><td>{{ translate('Round off') }}</td><td class="text-right">{{ $purchase->round_off }}</td></tr>
                    @endif
                    <tr class="fw-600"><td>{{ translate('Grand Total') }}</td><td class="text-right">{{ single_price($purchase->grand_total) }}</td></tr>
                    <tr><td>{{ translate('Paid') }}</td><td class="text-right">{{ single_price($purchase->paid_amount) }}</td></tr>
                    <tr class="text-danger fw-600"><td>{{ translate('Due') }}</td><td class="text-right">{{ single_price(max(0, $purchase->grand_total - $purchase->paid_amount)) }}</td></tr>
                </table>
            </div>
        </div>
        @if($purchase->payments->count() > 0)
            <h6 class="fw-600 mt-3">{{ translate('Payments') }}</h6>
            <table class="table table-sm">
                <thead><tr class="fs-12"><th>{{ translate('Date') }}</th><th>{{ translate('Method') }}</th><th class="text-right">{{ translate('Amount') }}</th><th>{{ translate('Note') }}</th></tr></thead>
                <tbody>
                    @foreach($purchase->payments as $payment)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td class="text-right">{{ single_price($payment->amount) }}</td>
                            <td>{{ $payment->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <button class="btn btn-light btn-sm" onclick="window.print()"><i class="las la-print"></i> {{ translate('Print') }}</button>
    </div>
</div>

<div class="row">
    <!-- Add payment -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-600">{{ translate('Record Payment') }}</h6></div>
            <div class="card-body">
                @if($purchase->grand_total - $purchase->paid_amount > 0)
                    <form action="{{ route('pos.purchases.payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="fs-12 mb-1">{{ translate('Amount') }}</label>
                                    <input type="number" class="form-control" name="amount" min="0.01" step="0.01" max="{{ $purchase->grand_total - $purchase->paid_amount }}" value="{{ $purchase->grand_total - $purchase->paid_amount }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="fs-12 mb-1">{{ translate('Method') }}</label>
                                    <select name="payment_method" class="form-control">
                                        @foreach(\App\Http\Controllers\PosPurchaseController::PAYMENT_METHODS as $method)
                                            <option value="{{ $method }}">{{ $method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label class="fs-12 mb-1">{{ translate('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                        <button class="btn btn-primary btn-sm">{{ translate('Add Payment') }}</button>
                    </form>
                @else
                    <div class="text-success fs-14"><i class="las la-check-circle"></i> {{ translate('This purchase is fully paid') }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Purchase return -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-600">{{ translate('Return to Supplier') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('pos.purchases.return') }}" method="POST">
                    @csrf
                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                    <div class="table-responsive mb-2">
                        <table class="table table-sm aiz-table">
                            <thead><tr class="fs-12"><th>{{ translate('Item') }}</th><th style="width:90px">{{ translate('Qty') }}</th></tr></thead>
                            <tbody>
                                @foreach($purchase->items as $purchaseItem)
                                    @php
                                        $maxRet = $purchaseItem->qty - (float) \App\Models\PosPurchaseReturnItem::where('purchase_item_id', $purchaseItem->id)->sum('qty');
                                    @endphp
                                    @if($maxRet > 0)
                                        <tr>
                                            <td class="fs-13">{{ $purchaseItem->name }}</td>
                                            <td>
                                                <input type="hidden" name="items[{{ $loop->index }}][purchase_item_id]" value="{{ $purchaseItem->id }}">
                                                <input type="number" class="form-control form-control-sm" name="items[{{ $loop->index }}][qty]" min="0" max="{{ $maxRet }}" step="0.01" value="0">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Refund Received') }}</label>
                                <input type="number" class="form-control form-control-sm" name="paid_amount" min="0" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Method') }}</label>
                                <select name="payment_method" class="form-control form-control-sm">
                                    @foreach(\App\Http\Controllers\PosPurchaseController::PAYMENT_METHODS as $method)
                                        <option value="{{ $method }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-soft-danger btn-sm">{{ translate('Process Return') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection