@extends('layouts.admin')
@section('content')
<div class="content">
    @can('dispute_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.disputes.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.dispute.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.dispute.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Dispute">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.customer') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.user.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.refund_request') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.response') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.dispute.fields.last_update') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disputes as $key => $dispute)
                                    <tr data-entry-id="{{ $dispute->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $dispute->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dispute->customer->name ?? $dispute->customers->name }}
                                        </td>
                                        <td>
                                            {{ $dispute->customer->email ?? $dispute->customers->email }}
                                        </td>
                                        <td>
                                            {{ $dispute->type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dispute->refund_request ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dispute->response ?? '' }}
                                        </td>
                                        <td>
                                            {{ $dispute->last_update ?? '' }}
                                        </td>
                                        <td>
                                            @can('dispute_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.disputes.show', $dispute->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('dispute_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.disputes.edit', $dispute->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('dispute_delete')
                                                <form action="{{ route('admin.disputes.destroy', $dispute->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('dispute_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.disputes.massDestroy') }}",
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
  let table = $('.datatable-Dispute:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection