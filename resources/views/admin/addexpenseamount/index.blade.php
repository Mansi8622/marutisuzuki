@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} Add Amounts
            @can('add_amount_create')
                <a class="btn btn-success btn-sm float-right" href="{{ route('admin.add-amounts.create') }}">
                    <i class="fa fa-plus"></i> Add New
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addAmounts as $addAmount)
                            <tr>
                                <td>{{ $addAmount->id }}</td>
                                <td>{{ $addAmount->user->name ?? 'N/A' }}</td>
                                <td>₹{{ number_format($addAmount->amount, 2) }}</td>
                                <td>{{ $addAmount->description }}</td>
                                <td>
                                    @if($addAmount->status == 'approve')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($addAmount->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @can('add_amount_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.add-amounts.show', $addAmount->id) }}">
                                            View
                                        </a>
                                    @endcan

                                    @can('add_amount_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.add-amounts.edit', $addAmount->id) }}">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('add_amount_delete')
                                        <form action="{{ route('admin.add-amounts.destroy', $addAmount->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
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
@endsection
