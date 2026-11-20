@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Size Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="p-4" action="{{ route('sizes.update', $size->id) }}" method="POST">
                <input name="_method" type="hidden" value="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">
                        {{ translate('Size Name')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('e.g. XS, S, M, L, XL, XXL')}}" id="name" name="name" class="form-control" required value="{{ $size->name }}">
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection