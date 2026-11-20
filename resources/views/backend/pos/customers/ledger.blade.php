@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ $customer->name }}</h6>
            </div>
            <div class="card-body fs-13">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ translate('Code') }}</span>
                    <span>{{ $customer->customer_code }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ translate('Mobile') }}</span>
                    <span>{{ $customer->mobile ?: '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ translate('Address') }}</span>
                    <span>{{ $customer->address ?: '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ translate('Opening Balance') }}</span>
                    <span>{{ single_price($customer->opening_balance) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ translate('Total Paid') }}</span>
                    <span class="text-success fw-600">{{ single_price($total_paid) }}</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top">
                    <span class="fw-600">{{ translate('Current Due') }}</span>
                    <span class="fw-600 {{ $due > 0 ? 'text-danger' : 'text-success' }}">{{ single_price(max(0, $due)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Sales Ledger') }}</h6>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Slip No') }}</th>
                            <th class="text-right">{{ translate('Total') }}</th>
                            <th class="text-right">{{ translate('Paid') }}</th>
                            <th class="text-right">{{ translate('Due') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($sale->sale_date)) }}</td>
                                <td><a href="{{ route('pos.invoice', $sale->id) }}" target="_blank">{{ $sale->slip_no }}</a></td>
                                <td class="text-right">{{ single_price($sale->grand_total) }}</td>
                                <td class="text-right text-success">{{ single_price($sale->paid_amount) }}</td>
                                <td class="text-right {{ $sale->grand_total - $sale->paid_amount > 0 ? 'text-danger fw-600' : '' }}">{{ single_price(max(0, $sale->grand_total - $sale->paid_amount)) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">{{ translate('No sales yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Returns') }}</h6>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Return No') }}</th>
                            <th class="text-right">{{ translate('Amount') }}</th>
                            <th class="text-right">{{ translate('Refunded') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($return->return_date)) }}</td>
                                <td>{{ $return->return_no }}</td>
                                <td class="text-right">{{ single_price($return->grand_total) }}</td>
                                <td class="text-right text-success">{{ single_price($return->paid_amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ translate('No returns') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection