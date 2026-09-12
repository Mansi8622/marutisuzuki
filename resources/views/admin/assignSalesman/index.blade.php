@extends('layouts.admin')

@section('content')
<div class="content">
    @can('assign_salesman_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.assign-salesmen.create') }}">
                    Add Salesman
                </a>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Assigned Salesmen List
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Salesman">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Image</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignSalesmen as $salesman)
                                    <tr data-entry-id="{{ $salesman->id }}">
                                        <td></td>
                                        <td>{{ $salesman->name }}</td>
                                        <td>{{ $salesman->email }}</td>
                                        <td>{{ $salesman->number }}</td>
                                     <td>
    
    @if($salesman->getProfileImageUrl())
        <img src="{{ $salesman->getProfileImageUrl() }}" width="50">
    @else
        N/A
    @endif
</td>


                                        <td>
                                           @can('assign_salesman_show')
    <a href="{{ route('admin.assign-salesmen.show', $salesman->id) }}" class="btn btn-info btn-sm">View</a>
@endcan


                                            @can('assign_salesman_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.assign-salesmen.edit', $salesman->id) }}">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('assign_salesman_delete')
                                                <form action="{{ route('admin.assign-salesmen.destroy', $salesman->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
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

    @can('assign_salesman_delete')
    let deleteButtonTrans = 'Delete selected'
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.assign-salesmen.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });

            if (ids.length === 0) {
                alert('No rows selected')
                return
            }

            if (confirm('Are you sure?')) {
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
        order: [[1, 'asc']],
        pageLength: 50,
    });

    let table = $('.datatable-Salesman:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
})
</script>
@endsection
