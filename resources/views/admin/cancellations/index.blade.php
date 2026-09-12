@extends('layouts.admin')
@section('content')
<div class="content">
    @can('cancellation_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.cancellations.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.cancellation.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.cancellation.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Cancellation">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.payment') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.order_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.checkOrder.fields.total_amount') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.product') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.product.fields.sku') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.requested_at') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.cancellation.fields.status') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cancellations as $key => $cancellation)
                                    <tr data-entry-id="{{ $cancellation->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $cancellation->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->payment ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->order_number->order_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->order_number->total_amount ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->product->sku ?? '' }}
                                        </td>
                                        <td>
                                            {{ $cancellation->requested_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Cancellation::STATUS_SELECT[$cancellation->status] ?? '' }}
                                        </td>
                                        <td>
                                            @can('cancellation_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.cancellations.show', $cancellation->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('cancellation_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.cancellations.edit', $cancellation->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('cancellation_delete')
                                                <form action="{{ route('admin.cancellations.destroy', $cancellation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('cancellation_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.cancellations.massDestroy') }}",
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
  let table = $('.datatable-Cancellation:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection