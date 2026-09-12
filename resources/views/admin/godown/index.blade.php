@extends('layouts.admin')

@section('content')
<div class="content">
    @can('product_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.godowns.create') }}">
                    Create Godown
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Godown List
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Godown">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>location</th>
                                    <th>capacity</th>
                                    <th>status</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($godowns as $godown)
                                    <tr data-entry-id="{{ $godown->id }}">
                                        <td></td>
                                        <td>{{ $godown->id ?? '' }}</td>
                                        <td>{{ $godown->name ?? '' }}</td>
                                        <td>{{ $godown->location ?? '' }}</td>
                                        <td>{{ $godown->capacity ?? '' }}</td>
                                        <td>{{ $godown->status ?? '' }}</td>
                                        <td>
                                         
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.godowns.show', $godown->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                          
                                           
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.godowns.edit', $godown->id) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            
                                            
                                                <form action="{{ route('admin.godowns.destroy', $godown->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                
                                           
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

        @can('godown_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.godowns.massDestroy') }}",
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
                        data: { ids: ids, _method: 'DELETE' }
                    })
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
        let table = $('.datatable-Godown:not(.ajaxTable)').DataTable({ buttons: dtButtons })

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
