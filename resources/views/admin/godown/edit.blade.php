@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} Godown
                </div>

                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.godowns.update', [$godown->id]) }}">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="form-group col-lg-6 {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Godown Name</label>
                                <input class="form-control py-3" type="text" name="name" id="name" value="{{ old('name', $godown->name) }}" required>
                                @if($errors->has('name'))
                                    <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group col-lg-6 {{ $errors->has('location') ? 'has-error' : '' }}">
                                <label for="location">Location</label>
                                <input class="form-control py-3" type="text" name="location" id="location" value="{{ old('location', $godown->location) }}" required>
                                @if($errors->has('location'))
                                    <span class="help-block" role="alert">{{ $errors->first('location') }}</span>
                                @endif
                            </div>

                            <div class="form-group col-lg-6 {{ $errors->has('capacity') ? 'has-error' : '' }}">
                                <label for="capacity">Capacity</label>
                                <input class="form-control py-3" type="number" name="capacity" id="capacity" value="{{ old('capacity', $godown->capacity) }}" required>
                                @if($errors->has('capacity'))
                                    <span class="help-block" role="alert">{{ $errors->first('capacity') }}</span>
                                @endif
                            </div>

                            <div class="form-group col-lg-6 {{ $errors->has('status') ? 'has-error' : '' }}">
    <label for="status">Status</label>
    <select class="form-control py-3" name="status" id="status" required>
        <option value="active" {{ old('status', $godown->status) == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $godown->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    @if($errors->has('status'))
        <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
    @endif
</div>

                        </div>

                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                <i class="fas fa-save me-1"></i> {{ trans('global.save') }}
                            </button>
                            <a class="btn btn-default" href="{{ route('admin.godowns.index') }}">
                                <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
