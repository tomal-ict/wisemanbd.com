@extends('backend.layouts.app')

@section('content')

@php
    $saved_start_time = $landing_page->start_date != null && strpos((string) $landing_page->start_date, '0000-00-00') !== 0 ? strtotime($landing_page->start_date) : false;
    $saved_end_time = $landing_page->end_date != null && strpos((string) $landing_page->end_date, '0000-00-00') !== 0 ? strtotime($landing_page->end_date) : false;
    $default_start_date = $saved_start_time !== false ? date('d-m-Y H:i:s', $saved_start_time) : now()->format('d-m-Y H:i:s');
    $default_end_date = $saved_end_time !== false ? date('d-m-Y H:i:s', $saved_end_time) : now()->addDay()->format('d-m-Y H:i:s');
    $date_range_value = old('date_range', $default_start_date . ' to ' . $default_end_date);
@endphp

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Landing Page') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('landing_pages.update', $landing_page->id) }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="product_id">{{ translate('Select Product') }} <span class="text-danger fs-12">*</span></label>
                        <div class="col-sm-9">
                            <select name="product_id" id="product_id" class="form-control aiz-selectpicker" data-live-search="true" required>
                                <option value="">{{ translate('Choose Product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-img="{{ uploaded_asset($product->thumbnail_img) }}" data-price="{{ $product->unit_price }}" @if($landing_page->product_id == $product->id) selected @endif>
                                        {{ $product->getTranslation('name') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ translate('Select a product and its image & price will appear below.') }}</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">{{ translate('Product Preview') }}</label>
                        <div class="col-sm-9">
                            <div id="product_preview">
                                <div class="d-inline-block border rounded-lg p-2 bg-white">
                                    <img src="@if($landing_page->product != null){{ uploaded_asset($landing_page->product->thumbnail_img) }}@endif" id="preview_img" class="img-fit rounded d-block" style="width:120px;height:120px;object-fit:cover;">
                                    <div class="fs-12 text-muted mt-1" id="preview_price"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="title">{{ translate('Campaign Title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" id="title" name="title" class="form-control" value="{{ $landing_page->title }}" placeholder="{{ translate('Landing Page Title') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="discount">{{ translate('Discount Percentage') }} <span class="text-danger fs-12">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" id="discount" name="discount" class="form-control" min="0" max="100" step="0.01" value="{{ $landing_page->discount }}" placeholder="{{ translate('e.g. 20') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small class="form-text text-muted" id="discount_preview"></small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="date_range">{{ translate('Campaign Duration') }} <span class="text-danger fs-12">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" id="date_range" class="form-control aiz-date-range" name="date_range" value="{{ $date_range_value }}" placeholder="Select Date" data-time-picker="true" data-time-gap="5" data-advanced-range="true" data-show-dropdown="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" required>
                            <small class="form-text text-muted">{{ translate('Use Today for a quick start date, then choose the end date and time.') }}</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="header_text">{{ translate('Header Text') }}</label>
                        <div class="col-sm-9">
                            <textarea id="header_text" name="header_text" rows="3" class="form-control" placeholder="{{ translate('Text shown at the top of the landing page') }}">{{ $landing_page->header_text }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="description">{{ translate('Description') }}</label>
                        <div class="col-sm-9">
                            <textarea id="description" name="description" rows="5" class="form-control" placeholder="{{ translate('Write about the product / offer here') }}">{{ $landing_page->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="footer_text">{{ translate('Footer Text') }}</label>
                        <div class="col-sm-9">
                            <textarea id="footer_text" name="footer_text" rows="3" class="form-control" placeholder="{{ translate('Text shown at the bottom of the landing page') }}">{{ $landing_page->footer_text }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        function refresh_preview(){
            var opt = $('#product_id option:selected');
            var img = opt.data('img');
            var price = parseFloat(opt.data('price'));
            if(img){
                $('#product_preview').removeClass('d-none');
                $('#preview_img').attr('src', img);
            }
            if(!isNaN(price)){
                $('#preview_price').text('Price: ৳' + price.toFixed(2));
            }
            var discount = parseFloat($('#discount').val());
            if(!isNaN(price) && !isNaN(discount) && discount >= 0 && discount <= 100){
                var after = price - (price * discount / 100);
                $('#discount_preview').text('After ' + discount + '% discount: ৳' + after.toFixed(2));
            }
            else{
                $('#discount_preview').text('');
            }
        }
        $('#product_id').on('change', refresh_preview);
        $('#discount').on('input', refresh_preview);
        $('#product_id').trigger('change');
    });
</script>
@endsection
