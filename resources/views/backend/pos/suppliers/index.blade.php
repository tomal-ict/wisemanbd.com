@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Suppliers') }}</h5>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supplier-create-modal"><i class="las la-plus"></i> {{ translate('Add Supplier') }}</button>
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
                    <th>{{ translate('Purchases') }}</th>
                    <th>{{ translate('Paid') }}</th>
                    <th>{{ translate('Due') }}</th>
                    <th class="text-center">{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->id }}</td>
                        <td>{{ $supplier->supplier_code }}</td>
                        <td><strong>{{ $supplier->name }}</strong></td>
                        <td>{{ $supplier->mobile }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>{{ single_price($supplier->opening_balance) }}</td>
                        <td>{{ single_price(\App\Models\PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum('grand_total')) }}</td>
                        <td>{{ single_price(\App\Models\PosPurchase::where('supplier_id', $supplier->id)->whereIn('status', ['Pending', 'Received'])->sum('paid_amount')) }}</td>
                        <td>
                            <span class="fw-600 {{ $supplier->due > 0 ? 'text-danger' : 'text-success' }}">{{ single_price(max(0, $supplier->due)) }}</span>
                        </td>
                        <td class="text-center">
                            @if($supplier->status)
                                <span class="badge badge-soft-success">{{ translate('Active') }}</span>
                            @else
                                <span class="badge badge-soft-secondary">{{ translate('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="javascript:void(0)" class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                               data-toggle="modal" data-target="#supplier-edit-modal-{{ $supplier->id }}" title="{{ translate('Edit') }}"><i class="las la-pen"></i></a>
                            <a href="{{ route('pos.suppliers.destroy', $supplier->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">{{ $suppliers->links() }}</div>
    </div>
</div>

<!-- Edit modals (kept outside the table for correct rendering) -->
@foreach($suppliers as $supplier)
    <div class="modal fade" id="supplier-edit-modal-{{ $supplier->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('pos.suppliers.update', $supplier->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Supplier') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fs-12 mb-1">{{ translate('Name') }} *</label>
                        <input type="text" class="form-control" name="name" value="{{ $supplier->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="fs-12 mb-1">{{ translate('Mobile') }}</label>
                                <input type="text" class="form-control" name="mobile" value="{{ $supplier->mobile }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="fs-12 mb-1">{{ translate('Email') }}</label>
                                <input type="email" class="form-control" name="email" value="{{ $supplier->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fs-12 mb-1">{{ translate('Address') }}</label>
                        <input type="text" class="form-control" name="address" value="{{ $supplier->address }}">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="fs-12 mb-1">{{ translate('Opening Balance') }}</label>
                                <input type="number" class="form-control" name="opening_balance" min="0" step="0.01" value="{{ $supplier->opening_balance }}">
                            </div>
                        </div>
                        <div class="col-6 pt-4">
                            <label class="aiz-checkbox mb-0">
                                <input type="checkbox" name="status" @if($supplier->status) checked @endif>
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

<!-- Create modal -->
<div class="modal fade" id="supplier-create-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pos.suppliers.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Supplier') }}</h5>
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
