@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        Create Add Amount
    </div>

    <div class="card-body">
<form action="{{ route('admin.add-amounts.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="user_id">User <span class="text-danger">*</span></label>
                <select name="user_id" id="user_id" class="form-control select2" required>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approve" {{ old('status') == 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="reject" {{ old('status') == 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
