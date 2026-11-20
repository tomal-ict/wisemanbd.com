@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('POS Customers') }}</h5>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customer-create-modal"><i class="las la-plus"></i> {{ translate('Add Customer') }}</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Code') }}</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Mobile') }}</th>
                    <th data-breakpoints="md">{{ translate('Email') }}</th>
                    <th data-breakpoints="md">{{ translate('Address') }}</th>
                    <th>{{ translate('Opening') }}</th>
                    <th>{{ translate('Due') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->customer_code }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->mobile }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ single_price($customer->opening_balance) }}</td>
                        <td>
                            <span class="fw-600 {{ $customer->due > 0 ? 'text-danger' : 'text-success' }}">{{ single_price(max(0, $customer->due)) }}</span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('pos.customers.ledger', $customer->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Ledger') }}"><i class="las la-book"></i></a>
                            <a href="javascript:void(0)" class="btn btn-soft-secondary btn-icon btn-circle btn-sm"
                               data-toggle="modal" data-target="#customer-edit-modal-{{ $customer->id }}" title="{{ translate('Edit') }}"><i class="las la-pen"></i></a>
                            <a href="{{ route('pos.customers.destroy', $customer->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                        </td>
                    </tr>

                    <div class="modal fade" id="customer-edit-modal-{{ $customer->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form action="{{ route('pos.customers.update', $customer->id) }}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ translate('Edit Customer') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="fs-12 mb-1">{{ translate('Name') }} *</label>
                                        <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="fs-12 mb-1">{{ translate('Mobile') }}</label>
                                                <input type="text" class="form-control" name="mobile" value="{{ $customer->mobile }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="fs-12 mb-1">{{ translate('Email') }}</label>
                                                <input type="email" class="form-control" name="email" value="{{ $customer->email }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="fs-12 mb-1">{{ translate('Address') }}</label>
                                        <input type="text" class="form-control" name="address" value="{{ $customer->address }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="fs-12 mb-1">{{ translate('Opening Balance') }}</label>
                                                <input type="number" class="form-control" name="opening_balance" min="0" step="0.01" value="{{ $customer->opening_balance }}">
                                            </div>
                                        </div>
                                        <div class="col-6 pt-4">
                                            <label class="aiz-checkbox mb-0">
                                                <input type="checkbox" name="status" @if($customer->status) checked @endif>
                                                <span>{{ translate('Active') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">{{ $customers->links() }}</div>
    </div>
</div>

<!-- Create modal -->
<div class="modal fade" id="customer-create-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pos.customers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Name') }} *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Mobile') }}</label>
                            <input type="text" class="form-control" name="mobile">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Email') }}</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Address') }}</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="form-group mb-0">
                    <label class="fs-12 mb-1">{{ translate('Opening Balance') }}</label>
                    <input type="number" class="form-control" name="opening_balance" min="0" step="0.01" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection