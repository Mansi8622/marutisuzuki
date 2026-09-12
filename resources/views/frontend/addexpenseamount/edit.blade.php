@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Edit Add Amount
    </div>

    <div class="card-body">
        <form action="{{ route('admin.add-amounts.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $data->amount) }}" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ old('description', $data->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ $data->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approve" {{ $data->status == 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="reject" {{ $data->status == 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
