@extends('layouts.admin')

@section('content')
<div class="content">
    @can('verification_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.verifications.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.verification.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.verification.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Verification">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.verification.fields.id') }}</th>
                                    <th>{{ trans('cruds.verification.fields.name') }}</th>
                                    <th>{{ trans('cruds.verification.fields.email') }}</th>
                                    <th>{{ trans('cruds.verification.fields.number') }}</th>
                                    <th>{{ trans('cruds.verification.fields.verification_status') }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($verifications as $key => $verification)
                                    <tr data-entry-id="{{ $verification->id }}">
                                        <td></td>
                                        <td>{{ $verification->id ?? '' }}</td>
                                        <td>{{ $verification->name ?? '' }}</td>
                                        <td>{{ $verification->email ?? '' }}</td>
                                        <td>{{ $verification->number ?? '' }}</td>
                                        <td>{{ $verification->verification_status ?? '' }}</td>
                                        <td>
                                            @can('verification_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.verifications.show', $verification->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('verification_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.verifications.edit', $verification->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('verification_delete')
                                                <form action="{{ route('admin.verifications.destroy', $verification->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
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
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('verification_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.verifications.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: { 'x-csrf-token': _token },
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        let table = $('.datatable-Verification:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
