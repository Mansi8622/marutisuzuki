@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.verification.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.verifications.update", [$verification->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.verification.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $verification->name) }}">
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">{{ trans('cruds.verification.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $verification->email) }}">
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('number') ? 'has-error' : '' }}">
                            <label for="number">{{ trans('cruds.verification.fields.number') }}</label>
                            <input class="form-control" type="text" name="number" id="number" value="{{ old('number', $verification->number) }}">
                            @if($errors->has('number'))
                                <span class="help-block" role="alert">{{ $errors->first('number') }}</span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                            <label for="image">{{ trans('cruds.verification.fields.image') }}</label>
                            <input class="form-control" type="file" name="image" id="image">
                            @if($errors->has('image'))
                                <span class="help-block" role="alert">{{ $errors->first('image') }}</span>
                            @endif
                            @if($verification->image)
                                <a href="{{ Storage::url($verification->image) }}" target="_blank">
                                    <img src="{{ Storage::url($verification->image) }}" width="100" height="100">
                                </a>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('aadhar_image') ? 'has-error' : '' }}">
                            <label for="aadhar_image">{{ trans('cruds.verification.fields.aadhar_image') }}</label>
                            <input class="form-control" type="file" name="aadhar_image" id="aadhar_image">
                            @if($errors->has('aadhar_image'))
                                <span class="help-block" role="alert">{{ $errors->first('aadhar_image') }}</span>
                            @endif
                            @if($verification->aadhar_image)
                                <a href="{{ Storage::url($verification->aadhar_image) }}" target="_blank">
                                    <img src="{{ Storage::url($verification->aadhar_image) }}" width="100" height="100">
                                </a>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('pan_image') ? 'has-error' : '' }}">
                            <label for="pan_image">{{ trans('cruds.verification.fields.pan_image') }}</label>
                            <input class="form-control" type="file" name="pan_image" id="pan_image">
                            @if($errors->has('pan_image'))
                                <span class="help-block" role="alert">{{ $errors->first('pan_image') }}</span>
                            @endif
                            @if($verification->pan_image)
                                <a href="{{ Storage::url($verification->pan_image) }}" target="_blank">
                                    <img src="{{ Storage::url($verification->pan_image) }}" width="100" height="100">
                                </a>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('reseller_code') ? 'has-error' : '' }}">
                            <label for="reseller_code">{{ trans('cruds.verification.fields.reseller_code') }}</label>
                            <input class="form-control" type="text" name="reseller_code" id="reseller_code" value="{{ old('reseller_code', $verification->reseller_code) }}">
                            @if($errors->has('reseller_code'))
                                <span class="help-block" role="alert">{{ $errors->first('reseller_code') }}</span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('verification_status') ? 'has-error' : '' }}">
                            <label for="verification_status">{{ trans('cruds.verification.fields.verification_status') }}</label>
                            <select class="form-control" name="verification_status" id="verification_status">
                                <option value="verified" {{ old('verification_status', $verification->verification_status) === 'verified' ? 'selected' : '' }}>
                                    Verified
                                </option>
                                <option value="pending" {{ old('verification_status', $verification->verification_status) === 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="rejected" {{ old('verification_status', $verification->verification_status) === 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>
                            </select>
                            @if($errors->has('verification_status'))
                                <span class="help-block" role="alert">{{ $errors->first('verification_status') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
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
        // Here you can add any custom JS if needed
    });
</script>
@endsection
