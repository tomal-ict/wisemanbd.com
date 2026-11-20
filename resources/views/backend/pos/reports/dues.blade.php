@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5 mb-4">
    <div class="col-md-4">
        <div class="border rounded p-3 text-center">
            <div class="fs-13 text-muted">{{ translate('Total Customer Due') }}</div>
            <div class="fs-22 fw-700 text-danger">{{ single_price($total_customer_due) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3 text-center">
            <div class="fs-13 text-muted">{{ translate('Total Supplier Due') }}</div>
            <div class="fs-22 fw-700 text-warning">{{ single_price($total_supplier_due) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="border rounded p-3 text-center">
            <div class="fs-13 text-muted">{{ translate('Net Receivable') }}</div>
            <div class="fs-22 fw-700 {{ ($total_customer_due - $total_supplier_due) >= 0 ? 'text-success' : 'text-danger' }}">{{ single_price($total_customer_due - $total_supplier_due) }}</div>
        </div>
    </div>
</div>

<div class="row gutters-5">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Customers with Due') }}</h6>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Mobile') }}</th>
                            <th class="text-right">{{ translate('Total Sales') }}</th>
                            <th class="text-right">{{ translate('Due') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers->where('due', '>', 0) as $customer)
                            <tr>
                                <td><a href="{{ route('pos.customers.ledger', $customer->id) }}">{{ $customer->name }}</a></td>
                                <td>{{ $customer->mobile }}</td>
                                <td class="text-right">{{ single_price($customer->total_sales) }}</td>
                                <td class="text-right"><span class="badge badge-inline badge-soft-danger">{{ single_price($customer->due) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ translate('No customer dues') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Suppliers with Due') }}</h6>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Supplier') }}</th>
                            <th>{{ translate('Mobile') }}</th>
                            <th class="text-right">{{ translate('Total Purchases') }}</th>
                            <th class="text-right">{{ translate('Due') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers->where('due', '>', 0) as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->mobile }}</td>
                                <td class="text-right">{{ single_price($supplier->total_purchases) }}</td>
                                <td class="text-right"><span class="badge badge-inline badge-soft-warning">{{ single_price($supplier->due) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ translate('No supplier dues') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection