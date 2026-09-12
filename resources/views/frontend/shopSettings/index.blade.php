@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('shop_setting_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.shop-settings.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.shopSetting.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.shopSetting.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ShopSetting">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.shopSetting.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shopSetting.fields.shop_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shopSetting.fields.legal_name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shopSetting.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.shopSetting.fields.logo') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shopSettings as $key => $shopSetting)
                                    <tr data-entry-id="{{ $shopSetting->id }}">
                                        <td>
                                            {{ $shopSetting->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shopSetting->shop_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shopSetting->legal_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $shopSetting->email ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($shopSetting->logo as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $media->getUrl('thumb') }}">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('shop_setting_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.shop-settings.show', $shopSetting->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('shop_setting_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.shop-settings.edit', $shopSetting->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('shop_setting_delete')
                                                <form action="{{ route('frontend.shop-settings.destroy', $shopSetting->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('shop_setting_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.shop-settings.massDestroy') }}",
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
  let table = $('.datatable-ShopSetting:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection