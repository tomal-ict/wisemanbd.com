@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Chatbot List')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('chatbot.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('Enable Chatbot')}}</label>
                            </div>
                            <div class="col-md-7">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="chatbot_status" type="checkbox" @if (get_setting('chatbot_status') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('Messenger / Facebook Page Link')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="chatbot_messenger_link" value="{{ get_setting('chatbot_messenger_link') }}" placeholder="{{ translate('Example: https://m.me/yourpagename') }}">
                                <small class="form-text text-muted">{{translate('The link customers will open to contact you directly on Messenger.')}}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('WhatsApp Number')}}</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="chatbot_whatsapp_number" value="{{ get_setting('chatbot_whatsapp_number') }}" placeholder="{{ translate('Example: 8801XXXXXXXXX') }}">
                                <p>
                                    <small class="form-text">{{translate('Include country code. Tapping the icon on your website will open a direct WhatsApp chat with this number.')}}</small>
                                </p>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('How it works') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">1. {{ translate('When enabled, a floating chat widget appears at the bottom-right corner of every page of your website.') }}</li>
                        <li class="list-group-item text-dark">2. {{ translate('Customers clicking the Messenger icon will be taken directly to a chat with your Facebook page.') }}</li>
                        <li class="list-group-item text-dark">3. {{ translate('Customers tapping the WhatsApp icon will open WhatsApp and start a chat with the number you provided.') }}</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-soft-success">
                    <h5 class="mb-0 h6 text-success font-weight-bold">
                        <i class="las la-robot mr-1"></i> {{ translate('Tawk.to AI Chatbot') }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">{{ translate('Configure your Tawk.to AI Chatbot with Property ID, Widget ID, Messenger, and WhatsApp.') }}</p>
                    <a href="{{ route('chatbot.ai') }}" class="btn btn-sm btn-success">
                        <i class="las la-cog mr-1"></i> {{ translate('Configure AI Chatbot') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection