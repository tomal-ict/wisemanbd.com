@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col"><h5 class="mb-md-0 h6">{{ translate('Sales Returns') }}</h5></div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#pos-return-modal"><i class="las la-undo-alt"></i> {{ translate('New Return') }}</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Return No') }}</th>
                            <th>{{ translate('Against') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Refunded') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                            <tr>
                                <td>{{ $return->id }}</td>
                                <td><strong>{{ $return->return_no }}</strong></td>
                                <td>{{ $return->sale ? $return->sale->slip_no : '-' }}</td>
                                <td>{{ date('d-m-Y', strtotime($return->return_date)) }}</td>
                                <td>{{ $return->customer ? $return->customer->name : '' }}</td>
                                <td><strong>{{ single_price($return->grand_total) }}</strong></td>
                                <td>{{ single_price($return->paid_amount) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('pos.returns.destroy', $return->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">{{ $returns->links() }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-600">{{ translate('Summary') }}</h6></div>
            <div class="card-body fs-14">
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ translate('Total returns') }}</span>
                    <strong id="pos-return-summary-total">{{ single_price(\App\Models\PosReturn::sum('grand_total')) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ translate('Total refunded') }}</span>
                    <strong>{{ single_price(\App\Models\PosReturn::sum('paid_amount')) }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>{{ translate('Total due credited') }}</span>
                    <strong>{{ single_price(\App\Models\PosReturn::sum(DB::raw('grand_total - paid_amount'))) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Return Modal -->
<div class="modal fade" id="pos-return-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Process a Return') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <form action="{{ route('pos.returns.store') }}" method="POST" id="pos-return-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="fs-12 mb-1">{{ translate('Select Sale') }}</label>
                        <select class="form-control" name="sale_id" id="pos-return-sale" required>
                            <option value="">{{ translate('Select a sale slip...') }}</option>
                            @foreach(\App\Models\PosSale::with('customer')->orderBy('id', 'desc')->limit(200)->get() as $sale)
                                <option value="{{ $sale->id }}">#{{ $sale->id }} - {{ $sale->slip_no }} ({{ $sale->customer ? $sale->customer->name : '' }}) - {{ single_price($sale->grand_total) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="table-responsive d-none" id="pos-return-items-wrap">
                        <table class="table table-sm">
                            <thead>
                                <tr class="fs-12">
                                    <th>{{ translate('Item') }}</th>
                                    <th>{{ translate('Sold') }}</th>
                                    <th>{{ translate('Returned') }}</th>
                                    <th style="width:120px">{{ translate('Return Qty') }}</th>
                                </tr>
                            </thead>
                            <tbody id="pos-return-items"></tbody>
                        </table>
                    </div>
                    <div class="row gutters-5">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Refund Amount') }}</label>
                                <input type="number" class="form-control" name="paid_amount" id="pos-return-paid" min="0" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="fs-12 mb-1">{{ translate('Refund Method') }}</label>
                                <select class="form-control" name="payment_method">
                                    @foreach(\App\Http\Controllers\PosController::PAYMENT_METHODS as $method)
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
                    <p class="fs-12 text-muted mb-0">{{ translate('Stock will be restored automatically. If the refund is less than the return amount, the difference is credited to the customer account.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Process Return') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
(function () {
    var saleSelect = document.getElementById('pos-return-sale');
    var itemsWrap = document.getElementById('pos-return-items-wrap');
    var itemsBody = document.getElementById('pos-return-items');
    var refundInput = document.getElementById('pos-return-paid');

    saleSelect.addEventListener('change', function () {
        if (!saleSelect.value) {
            itemsWrap.classList.add('d-none');
            return;
        }
        var body = new FormData();
        body.append('_token', '{{ csrf_token() }}');
        body.append('sale_id', saleSelect.value);
        fetch('{{ route('pos.returns.items') }}', { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                itemsBody.innerHTML = '';
                var possible = 0;
                (data.items || []).forEach(function (item) {
                    var maxQty = Math.max(0, item.qty - item.returned);
                    if (maxQty <= 0) return;
                    possible += maxQty * item.price;
                    var tr = document.createElement('tr');
                    tr.innerHTML =
                        '<td class="fs-12">' + item.name + '</td>' +
                        '<td class="fs-12">' + item.qty + '</td>' +
                        '<td class="fs-12">' + item.returned + '</td>' +
                        '<td><input type="number" class="form-control form-control-sm pos-return-qty" name="items[' + item.id + '][qty]" data-sale-item-id="' + item.id + '" min="0" max="' + maxQty + '" step="0.01" value="0"></td>';
                    itemsBody.appendChild(tr);
                });
                if (possible <= 0) {
                    itemsBody.innerHTML = '<tr><td colspan="4" class="text-center fs-13 text-muted">{{ translate('Nothing left to return for this sale') }}</td></tr>';
                } else {
                    refundInput.value = possible.toFixed(2);
                    itemsWrap.classList.remove('d-none');
                }
            });
    });
})();
</script>
@endsection