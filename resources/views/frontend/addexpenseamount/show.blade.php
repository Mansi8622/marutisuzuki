@extends('layouts.frontend')

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} Add Amount
        </div>

        <div class="card-body">
            <a class="btn btn-default" href="{{ route('admin.add-amounts.index') }}">
                {{ trans('global.back_to_list') }}
            </a>

            <table class="table table-bordered table-striped mt-3">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $addAmount->id }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $addAmount->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>₹{{ number_format($addAmount->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $addAmount->description }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($addAmount->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($addAmount->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <a class="btn btn-default mt-3" href="{{ route('admin.add-amounts.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection

