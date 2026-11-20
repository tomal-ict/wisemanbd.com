@extends('backend.layouts.app')

@section('content')

@php
    $delivery_areas = json_decode(get_setting('delivery_areas'), true) ?: [
        ['label' => 'ঢাকার ভিতরে', 'charge' => 60],
        ['label' => 'ঢাকা উপশহর', 'charge' => 100],
        ['label' => 'ঢাকা শহরের বাইরে', 'charge' => 120],
    ];
    $free_delivery_condition = json_decode(get_setting('free_delivery_condition'), true) ?: [
        'enabled' => 0,
        'min_amount' => 0,
    ];
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Delivery Area Charge Condition') }}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="free_delivery_condition">
                <div class="card-body">
                    <p class="text-muted fs-12 mb-4">{{ translate('When a customer\'s order total reaches or exceeds the minimum amount, delivery charge becomes FREE for all delivery areas.') }}</p>

                    <div class="form-group row">
                        <label class="col-md-5 col-from-label">{{ translate('Enable Free Delivery') }}</label>
                        <div class="col-md-7">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="free_delivery_enabled" <?php if(($free_delivery_condition['enabled'] ?? 0) == 1) echo "checked";?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-5 col-from-label">{{ translate('Minimum Order Amount') }}</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">৳</span>
                                </div>
                                <input type="number" min="0" step="0.01" class="form-control" name="free_delivery_min_amount" value="{{ $free_delivery_condition['min_amount'] }}" placeholder="{{ translate('Minimum order amount for free delivery') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Guest Checkout Delivery Areas') }}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="delivery_areas">
                <div class="card-body">
                    <p class="text-muted fs-12">{{ translate('Add the area name in Bangla or English and its delivery charge. These options appear in guest checkout when Delivery Area Activation is enabled.') }}</p>
                    <div id="delivery-area-rows">
                        @foreach($delivery_areas as $delivery_area)
                            <div class="form-row align-items-center mb-2 delivery-area-row">
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="delivery_area_label[]" value="{{ $delivery_area['label'] }}" placeholder="{{ translate('Area name') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" min="0" step="0.01" class="form-control" name="delivery_area_charge[]" value="{{ $delivery_area['charge'] }}" placeholder="{{ translate('Charge') }}" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-soft-danger btn-block remove-delivery-area">{{ translate('Remove') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-soft-primary btn-sm mt-2" id="add-delivery-area">{{ translate('Add Area') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm mt-2 float-right">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Select Shipping Method')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="shipping_type">
                    <div class="radio mar-btm">
                        <input id="product-shipping" class="magic-radio" type="radio" name="shipping_type" value="product_wise_shipping" <?php if(get_setting('shipping_type') == 'product_wise_shipping') echo "checked";?>>
                        <label for="product-shipping">
                            <span>{{translate('Product Wise Shipping Cost')}}</span>
                            <span></span>
                        </label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="flat-shipping" class="magic-radio" type="radio" name="shipping_type" value="flat_rate" <?php if(get_setting('shipping_type') == 'flat_rate') echo "checked";?>>
                        <label for="flat-shipping">{{translate('Flat Rate Shipping Cost')}}</label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="seller-shipping" class="magic-radio" type="radio" name="shipping_type" value="seller_wise_shipping" <?php if(get_setting('shipping_type') == 'seller_wise_shipping') echo "checked";?>>
                        <label for="seller-shipping">{{translate('Seller Wise Flat Shipping Cost')}}</label>
                    </div>
                    <div class="radio mar-btm">
                        <input id="area-shipping" class="magic-radio" type="radio" name="shipping_type" value="area_wise_shipping" <?php if(get_setting('shipping_type') == 'area_wise_shipping') echo "checked";?>>
                        <label for="area-shipping">{{translate('Area Wise Flat Shipping Cost')}}</label>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        1. {{ translate('Product Wise Shipping Cost calulation: Shipping cost is calculate by addition of each product shipping cost') }}.
                    </li>
                    <li class="list-group-item">
                        2. {{ translate('Flat Rate Shipping Cost calulation: How many products a customer purchase, doesn\'t matter. Shipping cost is fixed') }}.
                    </li>
                    <li class="list-group-item">
                        3. {{ translate('Seller Wise Flat Shipping Cost calulation: Fixed rate for each seller. If customers purchase 2 product from two seller shipping cost is calculated by addition of each seller flat shipping cost') }}.
                    </li>
                    <li class="list-group-item">
                        4. {{ translate('Area Wise Flat Shipping Cost calulation: Fixed rate for each area. If customers purchase multiple products from one seller shipping cost is calculated by the customer shipping area. To configure area wise shipping cost go to ') }} <a href="{{ route('cities.index') }}">{{ translate('Shipping Cities') }}</a>.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Flat Rate Cost')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="flat_rate_shipping_cost">
                  <div class="form-group">
                      <div class="col-lg-12">
                          <input class="form-control" type="text" name="flat_rate_shipping_cost" value="{{ get_setting('flat_rate_shipping_cost') }}">
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Flat rate shipping cost is applicable if Flat rate shipping is enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Shipping Cost for Admin Products')}}</h5>
            </div>
            <form action="{{ route('shipping_configuration.update') }}" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                  @csrf
                  <input type="hidden" name="type" value="shipping_cost_admin">
                  <div class="form-group">
                      <div class="col-lg-12">
                          <input class="form-control" type="text" name="shipping_cost_admin" value="{{ get_setting('shipping_cost_admin') }}">
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                  </div>
              </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Note')}}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        {{ translate('1. Shipping cost for admin is applicable if Seller wise shipping cost is enabled.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $('#add-delivery-area').on('click', function () {
        $('#delivery-area-rows').append('<div class="form-row align-items-center mb-2 delivery-area-row"><div class="col-md-7"><input type="text" class="form-control" name="delivery_area_label[]" placeholder="{{ translate('Area name') }}" required></div><div class="col-md-3"><input type="number" min="0" step="0.01" class="form-control" name="delivery_area_charge[]" placeholder="{{ translate('Charge') }}" required></div><div class="col-md-2"><button type="button" class="btn btn-soft-danger btn-block remove-delivery-area">{{ translate('Remove') }}</button></div></div>');
    });

    $(document).on('click', '.remove-delivery-area', function () {
        if ($('.delivery-area-row').length > 1) {
            $(this).closest('.delivery-area-row').remove();
        }
    });
</script>
@endsection
