@extends('layouts.admin')

@section('content')
<div class="content">
    @can('customer_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.customers.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.customer.title_singular') }}
                </a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.customer.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Customer">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.customer.fields.id') }}</th>
                                    <th>{{ trans('cruds.customer.fields.name') }}</th>
                                    <th>{{ trans('cruds.customer.fields.email') }}</th>
                                    <th>{{ trans('cruds.customer.fields.phone') }}</th>
                                    <th>{{ trans('cruds.customer.fields.profile_photo') }}</th>
                                    <th>{{ trans('cruds.customer.fields.address') }}</th>  <!-- Added column for Address -->
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $key => $customer)
                                    <tr data-entry-id="{{ $customer->id }}">
                                        <td></td>
                                        <td>{{ $customer->id ?? '' }}</td>
                                        <td>{{ $customer->name ?? '' }}</td>
                                        <td>{{ $customer->email ?? '' }}</td>
                                        <td>{{ $customer->phone ?? '' }}</td>
                                        <td>
                                            @if($customer->profile_photo)
                                                <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Photo" width="50" height="50">
                                            @else
                                                {{ trans('global.no_image') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer->address)
                                                {{ $customer->address->address ?? '' }},  <!-- Adjust according to your Address fields -->
                                                {{ $customer->address->city ?? '' }},
                                                {{ $customer->address->state ?? '' }},
                                                {{ $customer->address->country ?? '' }}
                                            @else
                                                {{ trans('global.no_address') }}
                                            @endif
                                        </td>
                                        <td>
                                            @can('customer_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.customers.show', $customer->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('customer_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.customers.edit', $customer->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('customer_delete')
                                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        @can('customer_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.customers.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}');
                    return;
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    })
                    .done(function () { location.reload(); });
                }
            }
        };
        dtButtons.push(deleteButton);
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
        });

        let table = $('.datatable-Customer:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    });
</script>
@endsection
