@extends('layouts.admin')
@section('content')
<div class="content">
    @can('support_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.supports.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.support.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.support.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Support">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.support.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.support.fields.live_chat') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.support.fields.support_phone') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.support.fields.toll_free_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.support.fields.support_email') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supports as $key => $support)
                                    <tr data-entry-id="{{ $support->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $support->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Support::LIVE_CHAT_RADIO[$support->live_chat] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $support->support_phone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $support->toll_free_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $support->support_email ?? '' }}
                                        </td>
                                        <td>
                                            @can('support_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.supports.show', $support->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('support_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.supports.edit', $support->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('support_delete')
                                                <form action="{{ route('admin.supports.destroy', $support->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('support_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.supports.massDestroy') }}",
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
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
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
  let table = $('.datatable-Support:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection