@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Supplier Payments') }}</h5>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#payment-create-modal"><i class="las la-plus"></i> {{ translate('Add Payment') }}</button>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="row gutters-5 mb-3">
                    <div class="col-md-4">
                        <select name="supplier_id" class="form-control form-control-sm aiz-selectpicker" data-live-search="true">
                            <option value="">{{ translate('All Suppliers') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @if(request('supplier_id') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm">{{ translate('Filter') }}</button>
                    </div>
                </form>
                <table class="table aiz-table mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Code') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Supplier') }}</th>
                            <th>{{ translate('Payment Method') }}</th>
                            <th data-breakpoints="md">{{ translate('Note') }}</th>
                            <th class="text-right">{{ translate('Amount') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>{{ $payment->payment_no }}</td>
                                <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                                <td>{{ $payment->supplier ? $payment->supplier->name : '-' }}</td>
                                <td>{{ $payment->payment_method }}</td>
                                <td>{{ $payment->note }}</td>
                                <td class="text-right fw-600">{{ single_price($payment->amount) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('pos.supplier_payments.destroy', $payment->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end fs-15 mb-3">
                    <span class="text-muted mr-2">{{ translate('Total Filtered') }}:</span>
                    <strong>{{ single_price($total) }}</strong>
                </div>
                <div class="aiz-pagination">{{ $payments->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Supplier Balance') }}</h6>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($suppliers as $supplier)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $supplier->name }}</span>
                            <span class="fw-600 {{ ($supplierDues[$supplier->id] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">{{ single_price($supplierDues[$supplier->id] ?? 0) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Create modal -->
<div class="modal fade" id="payment-create-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pos.supplier_payments.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Supplier Payment') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Supplier') }} *</label>
                    <select name="supplier_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                        <option value="">{{ translate('Select Supplier') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ translate('Due') }}: {{ single_price($supplierDues[$supplier->id] ?? 0) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Date') }}</label>
                            <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Amount') }} *</label>
                            <input type="number" class="form-control" name="amount" min="1" step="0.01" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Payment Method') }}</label>
                    <select name="payment_method" class="form-control">
                        @foreach(\App\Http\Controllers\PosSupplierPaymentController::PAYMENT_METHODS as $method)
                            <option value="{{ $method }}">{{ translate($method) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="fs-12 mb-1">{{ translate('Note') }}</label>
                    <textarea class="form-control" name="note" rows="2" placeholder="{{ translate('কিসে দিলাম (চেক/ক্যাশ/অনলাইন) বা যেকোনো নোট') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
