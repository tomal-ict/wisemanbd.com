@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Landing Pages') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('landing_pages.create') }}" class="btn btn-circle btn-info">
                <span>{{ translate('Create New Landing Page') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('All Landing Pages') }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{ translate('Product') }}</th>
                    <th data-breakpoints="lg">{{ translate('Title') }}</th>
                    <th>{{ translate('Discount') }}</th>
                    <th data-breakpoints="lg">{{ translate('Start Date') }}</th>
                    <th data-breakpoints="lg">{{ translate('End Date') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($landing_pages as $key => $landing_page)
                    <tr>
                        <td>{{ ($key + 1) + ($landing_pages->currentPage() - 1) * $landing_pages->perPage() }}</td>
                        <td>
                            @if($landing_page->product != null)
                                <div class="d-flex align-items-center">
                                    <img src="{{ uploaded_asset($landing_page->product->thumbnail_img) }}" alt="" class="img-fit h-50px mr-2 rounded">
                                    <span>{{ $landing_page->product->getTranslation('name') }}</span>
                                </div>
                            @else
                                <span class="text-muted">{{ translate('Product deleted') }}</span>
                            @endif
                        </td>
                        <td>{{ $landing_page->title }}</td>
                        <td><strong class="text-danger">-{{ $landing_page->discount }}%</strong></td>
                        <td>{{ $landing_page->start_date != null ? date('d-m-Y H:i', strtotime($landing_page->start_date)) : '' }}</td>
                        <td>{{ $landing_page->end_date != null ? date('d-m-Y H:i', strtotime($landing_page->end_date)) : '' }}</td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_landing_page_status(this)" value="{{ $landing_page->id }}" type="checkbox" @if($landing_page->status == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">
                            @if($landing_page->product != null)
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('landing_page.show', $landing_page->id) }}" target="_blank" title="{{ translate('View Page') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endif
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('landing_pages.edit', $landing_page->id) }}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('landing_pages.destroy', $landing_page->id) }}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $landing_pages->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_landing_page_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('landing_pages.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection