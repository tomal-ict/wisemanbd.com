@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Pathao Courier API Configuration') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="https://merchant.pathao.com/courier/developer-api" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="las la-external-link-alt"></i> {{ translate('Pathao Developer Panel') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    {{-- Pathao API Credentials Form --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 h6">{{ translate('Pathao Courier Credentials') }}</h5>
                <img src="https://pathao.com/wp-content/uploads/2018/12/avatar.png" alt="Pathao" height="24" onerror="this.style.display='none'">
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST">
                    @csrf
                    
                    {{-- Activation Toggle --}}
                    <div class="form-group row align-items-center">
                        <input type="hidden" name="types[]" value="pathao_courier_activation">
                        <label class="col-md-4 col-from-label">{{ translate('Pathao Activation') }}</label>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="pathao_courier_activation" value="1" @if(get_setting('pathao_courier_activation') == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            <small class="form-text text-muted">{{ translate('Enable or disable Pathao Courier delivery') }}</small>
                        </div>
                    </div>

                    {{-- Sandbox / Production Mode --}}
                    <div class="form-group row align-items-center">
                        <input type="hidden" name="types[]" value="pathao_sandbox">
                        <label class="col-md-4 col-from-label">{{ translate('Sandbox (Test) Mode') }}</label>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-warning mb-0">
                                <input type="checkbox" name="pathao_sandbox" value="1" @if(get_setting('pathao_sandbox') == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                            <small class="form-text text-muted">{{ translate('Enable Sandbox for testing, disable for Live/Production environment') }}</small>
                        </div>
                    </div>

                    <hr>

                    {{-- Client ID --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_client_id">
                        <label class="col-md-4 col-from-label">{{ translate('Client ID') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="pathao_client_id" value="{{ get_setting('pathao_client_id', 'Jrb2lkKeWL') }}" placeholder="{{ translate('Enter Pathao Client ID') }}" required>
                        </div>
                    </div>

                    {{-- Client Secret --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_client_secret">
                        <label class="col-md-4 col-from-label">{{ translate('Client Secret') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="pathao_client_secret" value="{{ get_setting('pathao_client_secret', 'bZEdrMGOFUqH1VFKwEfw25iiHXnrdt9JEtmJfYDK') }}" placeholder="{{ translate('Enter Pathao Client Secret') }}" required>
                        </div>
                    </div>

                    {{-- Merchant Email / Username --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_username">
                        <label class="col-md-4 col-from-label">{{ translate('Merchant Email / Username') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="email" class="form-control" name="pathao_username" value="{{ get_setting('pathao_username') }}" placeholder="{{ translate('merchant@example.com') }}" required>
                            <small class="form-text text-muted">{{ translate('Pathao Merchant panel login email address') }}</small>
                        </div>
                    </div>

                    {{-- Merchant Password --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_password">
                        <label class="col-md-4 col-from-label">{{ translate('Merchant Password') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="password" class="form-control" id="pathao_password_input" name="pathao_password" value="{{ get_setting('pathao_password') }}" placeholder="{{ translate('Pathao login password') }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                        <i class="las la-eye" id="toggle_pwd_icon"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">{{ translate('Used to obtain access token securely via Pathao OAuth 2.0') }}</small>
                        </div>
                    </div>

                    {{-- Store ID (Optional) --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_store_id">
                        <label class="col-md-4 col-from-label">{{ translate('Default Store ID') }} <span class="badge badge-inline badge-secondary text-muted">{{ translate('Optional') }}</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="pathao_store_id" value="{{ get_setting('pathao_store_id') }}" placeholder="{{ translate('Leave empty to auto-detect from Pathao') }}">
                            <small class="form-text text-muted">{{ translate('খালি রাখলে সিস্টেম স্বয়ংক্রিয়ভাবে পাঠাও অ্যাকাউন্ট থেকে আপনার ডিফল্ট পিকআপ স্টোর নির্বাচন করে নেবে') }}</small>
                        </div>
                    </div>

                    {{-- Webhook Secret (Optional) --}}
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="pathao_webhook_secret">
                        <label class="col-md-4 col-from-label">{{ translate('Webhook Secret') }} <span class="badge badge-inline badge-secondary text-muted">{{ translate('Optional') }}</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="pathao_webhook_secret" value="{{ get_setting('pathao_webhook_secret') }}" placeholder="{{ translate('Optional webhook secret') }}">
                            <small class="form-text text-muted">{{ translate('পাঠাও প্যানেলে ওয়েব হুক সেট করার সময় দেওয়া সিক্রেট (ঐচ্ছিক)') }}</small>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary px-4">{{ translate('Save Configuration') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Webhook & Instructions Sidebar --}}
    <div class="col-lg-5">
        {{-- Webhook Integration Box --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Pathao Webhook Settings') }}</h5>
            </div>
            <div class="card-body">
                <p class="fs-13 text-muted">
                    {{ translate('Copy this Callback URL and paste it into the Webhook Integration section in Pathao Merchant Panel.') }}
                </p>
                <div class="form-group">
                    <label class="col-from-label fw-600">{{ translate('Callback URL') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="webhook_url" value="{{ url('/api/pathao/webhook') }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyWebhookUrl()">
                                <i class="las la-copy"></i> {{ translate('Copy') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-3 fs-12 mb-0">
                    <i class="las la-info-circle mr-1"></i>
                    {{ translate('Pathao will send real-time delivery status updates (Picked up, In Transit, Delivered, Returned) to this URL.') }}
                </div>
            </div>
        </div>

        {{-- Guide & Information Box --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Quick Information') }}</h5>
            </div>
            <div class="card-body fs-13">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <b>{{ translate('Live Base URL') }}:</b><br>
                        <code>https://api-hermes.pathao.com</code>
                    </li>
                    <li class="list-group-item px-0">
                        <b>{{ translate('Sandbox Base URL') }}:</b><br>
                        <code>https://courier-api-sandbox.pathao.com</code>
                    </li>
                    <li class="list-group-item px-0">
                        <b>{{ translate('How to get Store ID?') }}:</b><br>
                        <span class="text-muted">{{ translate('In Pathao Merchant Panel, go to Settings -> Stores to view your Store IDs.') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function togglePasswordVisibility() {
        var input = document.getElementById('pathao_password_input');
        var icon = document.getElementById('toggle_pwd_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('la-eye');
            icon.classList.add('la-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('la-eye-slash');
            icon.classList.add('la-eye');
        }
    }

    function copyWebhookUrl() {
        var copyText = document.getElementById('webhook_url');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        AIZ.plugins.notify('success', '{{ translate('Callback URL copied to clipboard!') }}');
    }
</script>
@endsection
