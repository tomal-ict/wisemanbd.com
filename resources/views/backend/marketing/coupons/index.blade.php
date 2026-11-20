@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Coupons')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('coupon.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Coupon')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6">{{translate('Coupon Information')}}</h5>
  </div>
  <div class="card-body">
      <table class="table aiz-table p-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Code')}}</th>
                    <th>{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Type')}}</th>
                    <th data-breakpoints="lg">{{translate('Start Date')}}</th>
                    <th data-breakpoints="lg">{{translate('End Date')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $key => $coupon)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td style="vertical-align:middle;">{{$coupon->code}}</td>
                        <td>
                            @if ($coupon->type == 'cart_base')
                                <span class="badge badge-inline bg-soft-secondary">{{ translate('All Products') }}</span>
                            @else
                                @foreach (json_decode($coupon->details) as $coupon_detail)
                                    @php
                                        $coupon_product = \App\Models\Product::find($coupon_detail->product_id);
                                    @endphp
                                    @if ($coupon_product != null)
                                        <div class="d-flex flex-column mb-1 mr-xl-3">
                                            <span>
                                                <a href="{{ route('product', $coupon_product->slug) }}" target="_blank" class="text-reset">
                                                    {{ $coupon_product->getTranslation('name') ?? $coupon_product->name }}
                                                </a>
                                            </span>
                                            <span class="fs-13 opacity-70">
                                                {{ translate('Product ID') }}: <span class="badge badge-inline badge-primary">{{ $coupon_product->id }}</span>
                                                |
                                                {{ translate('Price') }}: <span class="badge badge-inline badge-secondary">
                                                    @php
                                                        $coupon_product_price = $coupon_product->stocks()->first() != null ? $coupon_product->stocks()->first()->price : $coupon_product->unit_price;
                                                        $coupon_product_discount_applicable = true;
                                                        if ($coupon_product->discount_start_date != null &&
                                                            (strtotime(date('d-m-Y H:i:s')) < $coupon_product->discount_start_date ||
                                                                strtotime(date('d-m-Y H:i:s')) > $coupon_product->discount_end_date)) {
                                                            $coupon_product_discount_applicable = false;
                                                        }
                                                        if ($coupon_product_discount_applicable) {
                                                            if ($coupon_product->discount_type == 'percent') {
                                                                $coupon_product_price -= ($coupon_product_price * $coupon_product->discount) / 100;
                                                            } elseif ($coupon_product->discount_type == 'amount') {
                                                                $coupon_product_price -= $coupon_product->discount;
                                                            }
                                                        }
                                                    @endphp
                                                    {{ single_price($coupon_product_price) }}
                                                </span>
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>@if ($coupon->type == 'cart_base')
                                {{ translate('Cart Base') }}
                            @elseif ($coupon->type == 'product_base')
                                {{ translate('Product Base') }}
                        @endif</td>
                        <td>{{ date('d-m-Y', $coupon->start_date) }}</td>
                        <td>{{ date('d-m-Y', $coupon->end_date) }}</td>
						<td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('coupon.edit', encrypt($coupon->id) )}}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('coupon.destroy', $coupon->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
