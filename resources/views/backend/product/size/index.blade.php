@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ translate('All Sizes') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" id="sort_sizes" action="" method="GET">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Sizes') }}</h5>
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm" id="search" name="search"
                                    @isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="{{ translate('Type size name & Enter') }}">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Size') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sizes as $key => $size)
                                <tr>
                                    <td>{{ ($key+1) + ($sizes->currentPage() - 1)*$sizes->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-inline bg-soft-primary fw-700 px-3 py-2 mr-2 fs-14">{{ $size->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('sizes.edit', $size->id) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('sizes.destroy', $size->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $sizes->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Size') }}</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('sizes.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{ translate('Size Name') }}</label>
                            <input type="text" placeholder="{{ translate('e.g. XS, S, M, L, XL, XXL') }}" id="name" name="name"
                                class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection