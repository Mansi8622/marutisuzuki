<div class="content">
    @can('our_stock_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.our-stocks.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.ourStock.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.ourStock.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">

                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-selectProductOurStocks">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.select_product') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.product.fields.sku') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.quantity_available') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.ourStock.fields.sku') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ourStocks as $key => $ourStock)
                                    <tr data-entry-id="{{ $ourStock->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $ourStock->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $ourStock->select_product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $ourStock->select_product->sku ?? '' }}
                                        </td>
                                        <td>
                                            {{ $ourStock->quantity_available ?? '' }}
                                        </td>
                                        <td>
                                            {{ $ourStock->sku ?? '' }}
                                        </td>
                                        <td>
                                            @can('our_stock_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.our-stocks.show', $ourStock->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('our_stock_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.our-stocks.edit', $ourStock->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('our_stock_delete')
                                                <form action="{{ route('admin.our-stocks.destroy', $ourStock->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('our_stock_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.our-stocks.massDestroy') }}",
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
  let table = $('.datatable-selectProductOurStocks:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection