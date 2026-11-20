@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('POS Sales') }}</h5>
        </div>
        <div class="col-auto">
            <form action="" method="GET" class="d-flex align-items-center">
                <input type="date" name="date" class="form-control form-control-sm mr-2" value="{{ request('date') }}">
                <button class="btn btn-primary btn-sm">{{ translate('Filter') }}</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Slip No') }}</th>
                    <th>{{ translate('Date') }}</th>
                    <th>{{ translate('Customer') }}</th>
                    <th data-breakpoints="md">{{ translate('Items') }}</th>
                    <th>{{ translate('Total') }}</th>
                    <th>{{ translate('Paid') }}</th>
                    <th>{{ translate('Due') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $key => $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td><a href="{{ route('pos.invoice', $sale->id) }}" target="_blank" class="text-reset"><strong>{{ $sale->slip_no }}</strong></a></td>
                        <td>{{ date('d-m-Y', strtotime($sale->sale_date)) }}</td>
                        <td>{{ $sale->customer ? $sale->customer->name : '' }} {{ $sale->customer && $sale->customer->mobile ? '(' . $sale->customer->mobile . ')' : '' }}</td>
                        <td>{{ $sale->items->count() }}</td>
                        <td><strong>{{ single_price($sale->grand_total) }}</strong></td>
                        <td>{{ single_price($sale->paid_amount) }}</td>
                        <td>{{ single_price(max(0, $sale->grand_total - $sale->paid_amount)) }}</td>
                        <td class="text-right">
                            <a href="{{ route('pos.invoice', $sale->id) }}" target="_blank" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Receipt') }}"><i class="las la-receipt"></i></a>
                            <a href="{{ route('pos.sales.destroy', $sale->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">{{ $sales->links() }}</div>
    </div>
</div>
@endsection