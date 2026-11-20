@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Purchases') }}</h5>
        </div>
        <div class="col-auto">
            <form action="" method="GET" class="d-flex align-items-center">
                <select name="status" class="form-control form-control-sm mr-2" style="width:150px">
                    <option value="">{{ translate('All Status') }}</option>
                    <option value="Pending" @if(request('status') == 'Pending') selected @endif>{{ translate('Pending') }}</option>
                    <option value="Received" @if(request('status') == 'Received') selected @endif>{{ translate('Received') }}</option>
                    <option value="Cancelled" @if(request('status') == 'Cancelled') selected @endif>{{ translate('Cancelled') }}</option>
                </select>
                <input type="date" name="date" class="form-control form-control-sm mr-2" value="{{ request('date') }}">
                <button class="btn btn-primary btn-sm mr-2">{{ translate('Filter') }}</button>
                <a href="{{ route('pos.purchases.create') }}" class="btn btn-soft-primary btn-sm text-nowrap"><i class="las la-plus"></i> {{ translate('Purchase') }}</a>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Purchase No') }}</th>
                    <th>{{ translate('Date') }}</th>
                    <th>{{ translate('Supplier') }}</th>
                    <th data-breakpoints="md">{{ translate('Items') }}</th>
                    <th>{{ translate('Total') }}</th>
                    <th>{{ translate('Paid') }}</th>
                    <th>{{ translate('Due') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td><a href="{{ route('pos.purchases.show', $purchase->id) }}" class="text-reset"><strong>{{ $purchase->purchase_no }}</strong></a></td>
                        <td>{{ date('d-m-Y', strtotime($purchase->purchase_date)) }}</td>
                        <td>{{ $purchase->supplier ? $purchase->supplier->name : '' }}</td>
                        <td>{{ $purchase->items->count() }}</td>
                        <td><strong>{{ single_price($purchase->grand_total) }}</strong></td>
                        <td>{{ single_price($purchase->paid_amount) }}</td>
                        <td>{{ single_price(max(0, $purchase->grand_total - $purchase->paid_amount)) }}</td>
                        <td>
                            @if($purchase->status == 'Received')
                                <span class="badge badge-inline badge-success">{{ translate('Received') }}</span>
                            @elseif($purchase->status == 'Pending')
                                <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                            @else
                                <span class="badge badge-inline badge-secondary">{{ translate('Cancelled') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('pos.purchases.show', $purchase->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Invoice') }}"><i class="las la-file-invoice"></i></a>
                            <a href="{{ route('pos.purchases.destroy', $purchase->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">{{ $purchases->links() }}</div>
    </div>
</div>
@endsection
