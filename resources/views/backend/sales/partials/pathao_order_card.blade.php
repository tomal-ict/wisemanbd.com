@php
    $pathao_activated = get_setting('pathao_courier_activation') == 1;
    $shipping = json_decode($order->shipping_address);
    $pathaoService = app(\App\Services\PathaoService::class);
    $defaultStoreId = $pathaoService->getDefaultStoreId();
    $cities = $pathao_activated ? $pathaoService->getCities() : [];
    $resolvedCityZone = $pathao_activated ? $pathaoService->resolveCityAndZone($shipping->city ?? '', $shipping->address ?? '') : ['city_id' => 1, 'zone_id' => 303];
@endphp

@if($pathao_activated)
<div class="card my-3 border border-danger-subtle bg-soft-light shadow-sm" style="border-left: 4px solid #ea1d24 !important;">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-md-7 mb-2 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="mr-3 bg-white p-2 rounded shadow-sm border border-danger" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <span class="text-danger font-weight-bolder" style="font-size: 20px; line-height: 1;">P</span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap">
                            <h6 class="mb-0 font-weight-bold text-dark mr-2">Pathao Courier Service</h6>
                            @if($order->pathao_consignment_id)
                                <span class="badge badge-inline badge-success">{{ $order->pathao_order_status ?? 'Sent to Pathao' }}</span>
                            @else
                                <span class="badge badge-inline badge-secondary">Not Dispatched</span>
                            @endif
                        </div>
                        @if($order->pathao_consignment_id)
                            <div class="text-muted fs-12 mt-1">
                                Consignment ID: <strong class="text-danger font-weight-bold">{{ $order->pathao_consignment_id }}</strong>
                                @if($order->pathao_delivery_fee)
                                    | Delivery Fee: <strong class="text-dark">৳{{ $order->pathao_delivery_fee }}</strong>
                                @endif
                                | COD: <strong class="text-dark">৳{{ $order->payment_status == 'paid' ? 0 : round($order->grand_total) }}</strong>
                            </div>
                        @else
                            <div class="text-muted fs-12 mt-1">
                                Instant automated consignment dispatch directly to Pathao Courier.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-md-right">
                @if($order->pathao_consignment_id)
                    <button type="button" class="btn btn-sm btn-outline-info mr-1" onclick="trackPathaoConsignment('{{ $order->pathao_consignment_id }}')">
                        <i class="las la-search-location"></i> {{ translate('Track Order') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#sendPathaoModal">
                        <i class="las la-sync"></i> {{ translate('Re-send') }}
                    </button>
                @else
                    <button type="button" class="btn btn-sm btn-danger px-3 font-weight-bold shadow-sm" data-toggle="modal" data-target="#sendPathaoModal">
                        <i class="las la-paper-plane mr-1"></i> {{ translate('Send to Pathao Courier') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: Send to Pathao -->
<div class="modal fade" id="sendPathaoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="las la-shipping-fast mr-1"></i> {{ translate('Dispatch Order to Pathao Courier') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pathaoDispatchForm">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 fs-13 mb-3">
                        <i class="las la-info-circle mr-1"></i> Order <strong>#{{ $order->code }}</strong> will be registered with Pathao Courier. A tracking consignment ID will be generated automatically.
                    </div>

                    <div class="row gutters-10">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Recipient Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recipient_name" value="{{ $shipping->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Recipient Phone (11 digits)') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="recipient_phone" value="{{ $pathaoService->formatPhone($shipping->phone ?? '') }}" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Delivery Address') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="recipient_address" rows="2" required>{{ $shipping->address ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Pathao City') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="pathao_recipient_city" name="recipient_city" required>
                                @foreach($cities as $c)
                                    <option value="{{ $c['city_id'] }}" @if($c['city_id'] == $resolvedCityZone['city_id']) selected @endif>
                                        {{ $c['city_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Pathao Zone') }} <span class="text-danger">*</span></label>
                            <select class="form-control" id="pathao_recipient_zone" name="recipient_zone" required>
                                <option value="{{ $resolvedCityZone['zone_id'] }}" selected>Selected Zone (ID: {{ $resolvedCityZone['zone_id'] }})</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Amount to Collect (COD)') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                <input type="number" step="1" class="form-control" name="amount_to_collect" value="{{ $order->payment_status == 'paid' ? 0 : round($order->grand_total) }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Item Weight (kg)') }}</label>
                            <input type="number" step="0.1" min="0.5" class="form-control" name="item_weight" value="0.5">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold fs-12">{{ translate('Delivery Type') }}</label>
                            <select class="form-control" name="delivery_type">
                                <option value="48" selected>Normal Delivery (48 hrs)</option>
                                <option value="12">On Demand (12 hrs)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="font-weight-bold fs-12">{{ translate('Special Instructions') }}</label>
                            <input type="text" class="form-control" name="special_instruction" value="Handle with care - Wiseman BD">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger font-weight-bold" id="btnSubmitPathao">
                        <span class="spinner-border spinner-border-sm d-none mr-1" id="pathaoSpinner" role="status"></span>
                        <i class="las la-paper-plane mr-1" id="pathaoIcon"></i> {{ translate('Confirm & Send to Pathao') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Track Pathao -->
<div class="modal fade" id="trackPathaoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold text-white">
                    <i class="las la-map-marker-alt mr-1"></i> Pathao Courier Tracking
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="trackPathaoBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2 text-muted">Fetching latest tracking info from Pathao...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function loadPathaoZones(cityId, selectedZoneId) {
        if (!cityId) return;
        $.get('{{ route("pathao.zones") }}', { city_id: cityId }, function(zones) {
            var $zoneSelect = $('#pathao_recipient_zone');
            $zoneSelect.empty();
            if (zones && zones.length > 0) {
                $.each(zones, function(i, zone) {
                    var selected = (zone.zone_id == selectedZoneId) ? 'selected' : '';
                    $zoneSelect.append('<option value="' + zone.zone_id + '" ' + selected + '>' + zone.zone_name + '</option>');
                });
            } else {
                $zoneSelect.append('<option value="">No zones available</option>');
            }
        });
    }

    $(document).ready(function() {
        var initialCityId = $('#pathao_recipient_city').val();
        var initialZoneId = '{{ $resolvedCityZone["zone_id"] }}';
        if (initialCityId) {
            loadPathaoZones(initialCityId, initialZoneId);
        }

        $('#pathao_recipient_city').on('change', function() {
            loadPathaoZones($(this).val(), null);
        });

        $('#pathaoDispatchForm').on('submit', function(e) {
            e.preventDefault();
            $('#btnSubmitPathao').prop('disabled', true);
            $('#pathaoSpinner').removeClass('d-none');
            $('#pathaoIcon').addClass('d-none');

            $.ajax({
                url: '{{ route("orders.send_to_pathao") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#btnSubmitPathao').prop('disabled', false);
                    $('#pathaoSpinner').addClass('d-none');
                    $('#pathaoIcon').removeClass('d-none');
                    if (res.success) {
                        AIZ.plugins.notify('success', res.message);
                        $('#sendPathaoModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1200);
                    } else {
                        AIZ.plugins.notify('danger', res.message || 'Failed to dispatch order');
                    }
                },
                error: function(xhr) {
                    $('#btnSubmitPathao').prop('disabled', false);
                    $('#pathaoSpinner').addClass('d-none');
                    $('#pathaoIcon').removeClass('d-none');
                    var msg = 'An error occurred while communicating with Pathao API.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    AIZ.plugins.notify('danger', msg);
                }
            });
        });
    });

    function trackPathaoConsignment(consignmentId) {
        $('#trackPathaoModal').modal('show');
        $('#trackPathaoBody').html('<div class="text-center py-4"><div class="spinner-border text-danger" role="status"></div><p class="mt-2 text-muted">Fetching latest tracking info from Pathao...</p></div>');

        $.post('{{ route("pathao.track") }}', {
            _token: '{{ csrf_token() }}',
            consignment_id: consignmentId
        }, function(res) {
            if (res.success && res.data) {
                var d = res.data;
                var html = '<div class="p-2">' +
                    '<h6 class="font-weight-bold text-danger mb-3"><i class="las la-box"></i> Consignment: ' + (d.consignment_id || consignmentId) + '</h6>' +
                    '<table class="table table-bordered fs-13 mb-0">' +
                    '<tr><th class="w-40 bg-light">Status</th><td><span class="badge badge-inline badge-info font-weight-bold">' + (d.order_status || 'N/A') + '</span></td></tr>' +
                    '<tr><th class="bg-light">Recipient</th><td>' + (d.recipient_name || '-') + ' (' + (d.recipient_phone || '-') + ')</td></tr>' +
                    '<tr><th class="bg-light">Address</th><td>' + (d.recipient_address || '-') + '</td></tr>' +
                    '<tr><th class="bg-light">COD Amount</th><td>৳' + (d.amount_to_collect || 0) + '</td></tr>' +
                    '<tr><th class="bg-light">Delivery Fee</th><td>৳' + (d.delivery_fee || 0) + '</td></tr>' +
                    '<tr><th class="bg-light">Created At</th><td>' + (d.created_at || '-') + '</td></tr>' +
                    '</table></div>';
                $('#trackPathaoBody').html(html);
            } else {
                $('#trackPathaoBody').html('<div class="alert alert-warning mb-0">' + (res.message || 'Tracking information not available at this moment.') + '</div>');
            }
        }).fail(function() {
            $('#trackPathaoBody').html('<div class="alert alert-danger mb-0">Failed to communicate with Pathao tracking API.</div>');
        });
    }
</script>
@endif
