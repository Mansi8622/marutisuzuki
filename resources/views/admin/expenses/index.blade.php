@extends('layouts.admin')
@section('content')
<div class="content">
    @can('expense_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.expenses.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.expense.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.expense.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-Expense">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.expense.fields.id') }}</th>
                                    <th>User</th>
                                    <th>{{ trans('cruds.expense.fields.expense_category') }}</th>
                                    <th>{{ trans('cruds.expense.fields.entry_date') }}</th>
                                    <th>{{ trans('cruds.expense.fields.amount') }}</th>
                                    <th>Status</th> <!-- Added column for Status -->

                                    <th>{{ trans('cruds.expense.fields.description') }}</th>
                                    <th>Image</th> <!-- Added column for Image -->
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $key => $expense)
                                    <tr data-entry-id="{{ $expense->id }}">
                                        <td></td>
                                        <td>{{ $expense->id ?? '' }}</td>
                                        <td>{{ $expense->user->name ?? '' }}</td>
                                        <td>{{ $expense->expense_category->name ?? '' }}</td>
                                        <td>{{ $expense->entry_date ?? '' }}</td>
                                        <td>{{ $expense->amount ?? '' }}</td>

                                        <td>{{ $expense->status ?? 'N/A' }}</td> <!-- Display status of the expense -->

                                        <td>{{ $expense->description ?? '' }}</td>
                                        <td>
                                            @if($expense->upload_image) <!-- Check if there is an uploaded image -->
                                                <img src="{{ asset($expense->upload_image) }}" alt="Expense Image" width="50"> <!-- Display the image -->
                                            @else
                                                {{ trans('global.no_image') }} <!-- In case there's no image -->
                                            @endif
                                        </td>
                                        <td>
                                            @can('expense_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.expenses.show', $expense->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan
                                            @can('expense_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.expenses.edit', $expense->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan
                                            @can('expense_delete')
                                                <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    @method('DELETE')
                                                    @csrf
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
        @can('expense_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.expenses.massDestroy') }}",
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
                    })
                    .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
        });

        let table = $('.datatable-Expense:not(.ajaxTable)').DataTable({ buttons: dtButtons })

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
