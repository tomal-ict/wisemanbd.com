@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Incomplete Orders') }} <span class="badge badge-primary ml-2">{{ $incomplete_orders->total() }}</span></h5>
        </div>
        <div class="col-auto">
            <span class="text-muted">{{ translate('Orders that were not paid or confirmed') }}</span>
        </div>
    </div>
    <div class="card-body">
        @if(count($incomplete_orders) > 0)
            <table class="table aiz-table mb-4">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Mobile') }}</th>
                        <th>{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Address') }}</th>
                        <th data-breakpoints="md">{{ translate('Area/City') }}</th>
                        <th data-breakpoints="md">{{ translate('Products') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Submitted') }}</th>
                        <th class="text-right" width="20%">{{ translate('options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomplete_orders as $key => $io)
                        @php
                            $io_products = json_decode($io->products, true);
                        @endphp
                        <tr class="{{ $io->viewed ? '' : 'bg-soft-warning' }}">
                            <td>{{ ($key + 1) + ($incomplete_orders->currentPage() - 1) * $incomplete_orders->perPage() }}</td>
                            <td>
                                <a href="tel:{{ $io->phone }}" class="text-reset"><strong>{{ $io->phone }}</strong></a>
                                @if(!$io->viewed)
                                    <span class="badge badge-danger ml-1">{{ translate('New') }}</span>
                                @endif
                            </td>
                            <td>{{ $io->name }}</td>
                            <td>{{ $io->address }}</td>
                            <td>{{ $io->city }}</td>
                            <td>
                                @if($io_products)
                                    @foreach($io_products as $p)
                                        @php
                                            $io_parts = [$p['name']];
                                            if (isset($p['variation']) && $p['variation'] != '') { $io_parts[] = $p['variation']; }
                                            if (isset($p['size']) && $p['size'] != '') { $io_parts[] = $p['size']; }
                                        @endphp
                                        {{ implode(' - ', $io_parts) }} x {{ $p['qty'] }} ({{ single_price($p['total']) }})<br>
                                    @endforeach
                                @endif
                            </td>
                            <td><strong>{{ single_price($io->grand_total) }}</strong></td>
                            <td>{{ date('d-m-Y h:i A', strtotime($io->updated_at)) }}</td>
                            <td class="text-right">
                                <a href="javascript:void(0)" onclick="show_incomplete_order_details({{ $io->id }})" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="{{ route('incomplete_orders.wait', $io->id) }}" class="btn btn-soft-warning btn-icon btn-circle btn-sm" title="{{ translate('Mark as Waiting') }}" onclick="return confirm('{{ translate('Move this order to waiting list?') }}')">
                                    <i class="las la-hourglass-start"></i>
                                </a>
                                <a href="{{ route('incomplete_orders.confirm', $io->id) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Confirm & Move to All Orders') }}">
                                    <i class="las la-check-circle"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('incomplete_orders.destroy', $io->id) }}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $incomplete_orders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="las la-check-circle la-3x text-success mb-3 d-block"></i>
                <div class="fs-14">{{ translate('কোনো অসম্পূর্ণ অর্ডার নেই') }}</div>
            </div>
        @endif
    </div>
</div>

<!-- Incomplete Order Details Modal -->
<div class="modal fade" id="incomplete_order_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Incomplete Order Details') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="incomplete_order_details_body"></div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function show_incomplete_order_details(id){
        $('#incomplete_order_details_body').html('<div class="text-center py-4"><i class="las la-spinner la-spin la-2x text-primary"></i></div>');
        $.post('{{ route('incomplete_orders.detail') }}', {
            _token : AIZ.data.csrf,
            id     : id
        }, function(data){
            $('#incomplete_order_details_body').html(data);
        });
        $('#incomplete_order_details').modal();
    }
</script>
@endsection