@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Assign Salesman
                    <a class="btn btn-secondary float-end" href="{{ route('admin.assign-salesmen.index') }}">Back</a>
                </div>

                <div class="panel-body">
                    {{-- Show success or error messages --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.assign-salesmen.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="required" for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                            @if($errors->has('email'))
                                <span class="help-block text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
                            <label class="required" for="number">Phone Number</label>
                            <input class="form-control" type="text" name="number" id="number" value="{{ old('number') }}" required>
                            @if($errors->has('number'))
                                <span class="help-block text-danger">{{ $errors->first('number') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                            <label class="required" for="user_id">Assign to Retailer</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                <option value="">Select Retailer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <span class="help-block text-danger">{{ $errors->first('user_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                            <label for="image">Salesman Image</label>
                            <input class="form-control" type="file" name="image" id="image">
                            @if($errors->has('image'))
                                <span class="help-block text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                Assign Salesman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@endsection
