@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('AI Chatbot & Live Chat Settings') }}</h1>
                <p class="text-muted mb-0">{{ translate('Configure Tawk.to AI Chatbot, WhatsApp, and Facebook Messenger for your storefront.') }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('chatbot.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="las la-list"></i> {{ translate('Multi-Channel List') }}
                </a>
                <a href="https://dashboard.tawk.to/" target="_blank" rel="noopener" class="btn btn-success btn-sm">
                    <i class="las la-external-link-alt"></i> {{ translate('Tawk.to Dashboard') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('chatbot_ai.update') }}" method="POST">
                @csrf

                {{-- Tawk.to AI Chatbot Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-xs mr-2 bg-soft-success text-success rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="las la-robot fs-18"></i>
                            </span>
                            <h5 class="mb-0 h6 font-weight-bold">{{ translate('Tawk.to AI Chatbot & Live Chat') }}</h5>
                        </div>
                        <span class="badge {{ get_setting('tawk_status') == 1 ? 'badge-inline badge-success' : 'badge-inline badge-secondary' }}">
                            {{ get_setting('tawk_status') == 1 ? translate('Active') : translate('Inactive') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="form-group row align-items-center">
                            <label class="col-md-3 col-form-label font-weight-600">{{ translate('Enable Tawk.to') }}</label>
                            <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="tawk_status" type="checkbox" @if (get_setting('tawk_status') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                                <span class="d-block text-muted fs-12 mt-1">{{ translate('Turn on to load Tawk.to live chat and AI assistant on your website.') }}</span>
                            </div>
                        </div>

                        <hr class="dashed my-3">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-600">
                                {{ translate('Tawk.to Property ID') }} <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tawk_property_id" value="{{ get_setting('tawk_property_id') }}" placeholder="{{ translate('Example: 64a8bc1234567890abcdef12 or full embed link') }}">
                                <small class="form-text text-muted">
                                    {{ translate('Your unique Tawk.to Property ID. You can also paste the full direct chat link or embed URL here; it will be automatically parsed.') }}
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-600">
                                {{ translate('Widget ID / উইজেট আইডি') }} <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tawk_widget_id" value="{{ get_setting('tawk_widget_id', 'default') }}" placeholder="{{ translate('Example: default or 1h5g8k...') }}">
                                <small class="form-text text-muted">
                                    {{ translate('Default is usually "default" or your custom widget identifier found in the embed code.') }}
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-600">
                                {{ translate('Custom Embed Code') }} <small class="text-muted">({{ translate('Optional') }})</small>
                            </label>
                            <div class="col-md-9">
                                <textarea name="tawk_embed_code" rows="4" class="form-control" placeholder="{{ translate('<!-- Optional: Paste full <script> code directly if provided by Tawk.to -->') }}">{{ get_setting('tawk_embed_code') }}</textarea>
                                <small class="form-text text-muted">
                                    {{ translate('Leave empty if you have provided Property ID and Widget ID above.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Messenger & WhatsApp Card --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-xs mr-2 bg-soft-primary text-primary rounded-circle d-inline-flex align-items-center justify-content-center">
                                <i class="las la-comments fs-18"></i>
                            </span>
                            <h5 class="mb-0 h6 font-weight-bold">{{ translate('Messenger & WhatsApp Quick Options') }}</h5>
                        </div>
                        <span class="badge {{ get_setting('chatbot_status') == 1 ? 'badge-inline badge-primary' : 'badge-inline badge-secondary' }}">
                            {{ get_setting('chatbot_status') == 1 ? translate('Active') : translate('Inactive') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="form-group row align-items-center">
                            <label class="col-md-3 col-form-label font-weight-600">{{ translate('Enable Quick Popup') }}</label>
                            <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-primary mb-0">
                                    <input value="1" name="chatbot_status" type="checkbox" @if (get_setting('chatbot_status') == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                                <span class="d-block text-muted fs-12 mt-1">{{ translate('Show floating multi-channel chat button (Messenger / WhatsApp) at bottom corner.') }}</span>
                            </div>
                        </div>

                        <hr class="dashed my-3">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-600">
                                <i class="lab la-facebook-messenger text-primary mr-1"></i> {{ translate('Messenger Link') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="chatbot_messenger_link" value="{{ get_setting('chatbot_messenger_link') }}" placeholder="{{ translate('Example: https://m.me/yourpagename or https://www.facebook.com/messages/t/...') }}">
                                <small class="form-text text-muted">{{ translate('Direct link to your Facebook page inbox for customers.') }}</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label font-weight-600">
                                <i class="lab la-whatsapp text-success mr-1"></i> {{ translate('WhatsApp Number') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="chatbot_whatsapp_number" value="{{ get_setting('chatbot_whatsapp_number') }}" placeholder="{{ translate('Example: 8801811762186 or 01811762186') }}">
                                <small class="form-text text-muted">{{ translate('Phone number with country code. Tapping opens direct WhatsApp chat with this number.') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-right py-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="las la-save mr-1"></i> {{ translate('Save Settings') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Right Column: Guidance & Help --}}
        <div class="col-lg-4">
            {{-- How to get Tawk.to details --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-soft-success py-3">
                    <h5 class="mb-0 h6 font-weight-bold text-success">
                        <i class="las la-info-circle mr-1"></i> {{ translate('How to get Tawk.to Property & Widget ID') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="pl-3 mb-3 text-muted fs-13 lh-1-6">
                        <li class="mb-2">
                            {!! translate('Visit <strong><a href="https://www.tawk.to/" target="_blank" rel="noopener">tawk.to</a></strong> and create or log in to your free account.') !!}
                        </li>
                        <li class="mb-2">
                            {!! translate('Click the <strong>Administration (⚙️ Settings)</strong> icon at the bottom-left corner.') !!}
                        </li>
                        <li class="mb-2">
                            {!! translate('Select <strong>Channels &gt; Chat Widget</strong>.') !!}
                        </li>
                        <li class="mb-2">
                            {!! translate('Look at the <strong>Direct Chat Link</strong>:') !!}
                            <div class="p-2 bg-light rounded mt-1 border text-monospace fs-11 text-break">
                                https://embed.tawk.to/<span class="text-danger font-weight-bold">PROPERTY_ID</span>/<span class="text-primary font-weight-bold">WIDGET_ID</span>
                            </div>
                        </li>
                        <li class="mb-0">
                            {!! translate('Copy the <strong class="text-danger">Property ID</strong> and <strong class="text-primary">Widget ID</strong> (or the entire link) and paste it into the form on the left.') !!}
                        </li>
                    </ol>
                    <a href="https://dashboard.tawk.to/" target="_blank" rel="noopener" class="btn btn-block btn-soft-success btn-sm font-weight-600">
                        <i class="las la-external-link-alt mr-1"></i> {{ translate('Open Tawk.to Dashboard') }}
                    </a>
                </div>
            </div>

            {{-- AI Chatbot Capabilities --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 h6 font-weight-bold">
                        <i class="las la-magic text-warning mr-1"></i> {{ translate('Tawk.to AI Features (Apollo AI)') }}
                    </h5>
                </div>
                <div class="card-body fs-13 text-muted lh-1-6">
                    <p class="mb-2">{{ translate('Inside your Tawk.to dashboard, you can enable Apollo AI Bot to automatically:') }}</p>
                    <ul class="pl-3 mb-3">
                        <li>{{ translate('Answer customer questions 24/7 in Bengali & English') }}</li>
                        <li>{{ translate('Provide product recommendations & order assistance') }}</li>
                        <li>{{ translate('Collect customer contact details automatically') }}</li>
                    </ul>
                    <small class="text-info d-block">
                        <i class="las la-check-circle"></i> {{ translate('No coding needed — simply train Apollo AI inside Tawk.to with your store information.') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection
