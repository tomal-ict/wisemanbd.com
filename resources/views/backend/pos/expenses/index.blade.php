@extends('backend.layouts.app')

@section('content')
<div class="row gutters-5">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Expenses') }}</h5>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#expense-create-modal"><i class="las la-plus"></i> {{ translate('Add Expense') }}</button>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="row gutters-5 mb-3">
                    <div class="col-md-4">
                        <select name="category_id" class="form-control form-control-sm aiz-selectpicker" data-live-search="true">
                            <option value="">{{ translate('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm">{{ translate('Filter') }}</button>
                    </div>
                </form>
                <table class="table aiz-table mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Code') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Category') }}</th>
                            <th>{{ translate('Expense For') }}</th>
                            <th data-breakpoints="md">{{ translate('Reference') }}</th>
                            <th class="text-right">{{ translate('Amount') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>{{ $expense->expense_code }}</td>
                                <td>{{ date('d-m-Y', strtotime($expense->expense_date)) }}</td>
                                <td>{{ $expense->category ? $expense->category->name : '-' }}</td>
                                <td>{{ $expense->expense_for }}</td>
                                <td>{{ $expense->reference_no }}</td>
                                <td class="text-right fw-600">{{ single_price($expense->amount) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('pos.expenses.destroy', $expense->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}"><i class="las la-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end fs-15 mb-3">
                    <span class="text-muted mr-2">{{ translate('Total Filtered') }}:</span>
                    <strong>{{ single_price($expenses->sum('amount')) }}</strong>
                </div>
                <div class="aiz-pagination">{{ $expenses->appends(request()->query())->links() }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-600">{{ translate('Expense Categories') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pos.expense_categories.store') }}" method="POST" class="row gutters-5 mb-3">
                    @csrf
                    <div class="col-8">
                        <input type="text" class="form-control form-control-sm" name="name" placeholder="{{ translate('Category name') }}" required>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary btn-sm btn-block">{{ translate('Add') }}</button>
                    </div>
                    <div class="col-12 mt-2">
                        <input type="text" class="form-control form-control-sm" name="description" placeholder="{{ translate('Description (optional)') }}">
                    </div>
                </form>
                <ul class="list-group">
                    @foreach($categories as $category)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $category->name }}</span>
                            <a href="{{ route('pos.expense_categories.destroy', $category->id) }}" class="btn btn-soft-danger btn-icon btn-xs confirm-delete"><i class="las la-trash"></i></a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Create modal -->
<div class="modal fade" id="expense-create-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('pos.expenses.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Expense') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Date') }}</label>
                            <input type="date" class="form-control" name="expense_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Category') }}</label>
                            <select name="category_id" class="form-control aiz-selectpicker" data-live-search="true">
                                <option value="0">{{ translate('Uncategorized') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Expense For') }} *</label>
                    <input type="text" class="form-control" name="expense_for" required>
                </div>
                <div class="form-group">
                    <label class="fs-12 mb-1">{{ translate('Amount') }} *</label>
                    <input type="number" class="form-control" name="amount" min="0" step="0.01" required>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="fs-12 mb-1">{{ translate('Reference No') }}</label>
                            <input type="text" class="form-control" name="reference_no">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group mb-0">
                            <label class="fs-12 mb-1">{{ translate('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                    </div>
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