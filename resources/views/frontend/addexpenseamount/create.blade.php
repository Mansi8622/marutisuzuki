@extends('layouts.frontend')

@section('content')
<div class="card">
    <div class="card-header">
        Create Add Amount
    </div>

    <div class="card-body">
        <form action="{{ route('frontend.add-amounts.store') }}" method="POST">
            @csrf

            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
