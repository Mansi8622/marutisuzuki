@extends('layouts.admin')
@section('content')
<div class="content">
    @can('carrier_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.carriers.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.carrier.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.carrier.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Carrier">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.carrier_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.tracking_url') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.phone') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.carrier.fields.brand_logo') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carriers as $key => $carrier)
                                    <tr data-entry-id="{{ $carrier->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $carrier->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $carrier->carrier_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Carrier::STATUS_SELECT[$carrier->status] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $carrier->tracking_url ?? '' }}
                                        </td>
                                        <td>
                                            {{ $carrier->phone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $carrier->email ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($carrier->brand_logo as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $media->getUrl('thumb') }}">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('carrier_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.carriers.show', $carrier->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('carrier_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.carriers.edit', $carrier->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('carrier_delete')
                                                <form action="{{ route('admin.carriers.destroy', $carrier->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('carrier_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.carriers.massDestroy') }}",
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
  let table = $('.datatable-Carrier:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection