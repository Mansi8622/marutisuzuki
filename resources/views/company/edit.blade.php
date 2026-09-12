@extends('layouts.frontend')

@section('content')
<div class="container">
    <h2>Edit Replacement</h2>
    <form action="{{ route('frontend.replacements.update', $replacement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="Pending" {{ $replacement->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $replacement->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ $replacement->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Notes:</label>
            <textarea name="info" class="form-control">{{ $replacement->info }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
