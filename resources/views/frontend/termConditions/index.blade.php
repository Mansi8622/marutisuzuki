@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('term_condition_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.term-conditions.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.termCondition.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.termCondition.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-TermCondition">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.effective_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.version_number') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_subtitle') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.banner_image') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.termCondition.fields.status') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($termConditions as $key => $termCondition)
                                    <tr data-entry-id="{{ $termCondition->id }}">
                                        <td>
                                            {{ $termCondition->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $termCondition->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $termCondition->effective_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $termCondition->version_number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $termCondition->banner_title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $termCondition->banner_subtitle ?? '' }}
                                        </td>
                                        <td>
                                            @if($termCondition->banner_image)
                                                <a href="{{ $termCondition->banner_image->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $termCondition->banner_image->getUrl('thumb') }}">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ App\Models\TermCondition::STATUS_SELECT[$termCondition->status] ?? '' }}
                                        </td>
                                        <td>
                                            @can('term_condition_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.term-conditions.show', $termCondition->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('term_condition_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.term-conditions.edit', $termCondition->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('term_condition_delete')
                                                <form action="{{ route('frontend.term-conditions.destroy', $termCondition->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('term_condition_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.term-conditions.massDestroy') }}",
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
  let table = $('.datatable-TermCondition:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection