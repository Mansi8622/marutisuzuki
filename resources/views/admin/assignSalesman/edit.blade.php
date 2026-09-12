@extends('layouts.admin')

@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} Salesman
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.assign-salesmen.update', $assignSalesman->id) }}">
                        @method('PUT')
                        @csrf

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name" class="required">{{ trans('cruds.salesman.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $assignSalesman->name) }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email" class="required">{{ trans('cruds.salesman.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $assignSalesman->email) }}" required>
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
                            <label for="number" class="required">{{ trans('cruds.salesman.fields.number') }}</label>
                            <input class="form-control" type="text" name="number" id="number" value="{{ old('number', $assignSalesman->number) }}" required>
                            @if($errors->has('number'))
                                <span class="help-block" role="alert">{{ $errors->first('number') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                            <label for="user_id" class="required">{{ trans('cruds.salesman.fields.user') }}</label>
                            <select class="form-control" name="user_id" id="user_id" required>
                                <option value="">Select Retailer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $assignSalesman->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <span class="help-block" role="alert">{{ $errors->first('user_id') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">{{ trans('global.save') }}</button>
                            <a class="btn btn-secondary" href="{{ route('admin.assign-salesmen.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
