@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Chatbot AI')}}</h5>
                </div>
                <div class="card-body text-center py-5">
                    <i class="las la-robot la-4x opacity-60 mb-3 d-inline-block"></i>
                    <h3 class="h5">{{translate('Chatbot AI coming soon')}}</h3>
                    <p class="opacity-60">{{translate('The AI chatbot settings will be added here in the next update.')}}</p>
                    <a href="{{ route('chatbot.index') }}" class="btn btn-sm btn-primary">{{translate('Go back to Chatbot List')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection