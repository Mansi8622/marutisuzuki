@extends('layouts.admin')
@section('content')

<style>
    .profile-shell .panel{border:0;border-radius:14px;box-shadow:0 8px 24px rgba(16,24,40,.06);overflow:hidden}.profile-shell .panel-heading{background:#fff;border-bottom:1px solid #e8edf4;padding:17px 20px;font:700 15px 'Manrope',sans-serif}.profile-cover{height:150px;background:linear-gradient(120deg,#16243b,#4169e1);background-size:cover;background-position:center;position:relative}.profile-avatar{width:92px;height:92px;border-radius:50%;object-fit:cover;background:#fff;border:4px solid #fff;position:absolute;left:22px;bottom:-42px;box-shadow:0 5px 18px rgba(16,24,40,.2)}.profile-title{padding:55px 22px 12px;background:#fff}.profile-title h3{font:800 20px 'Manrope',sans-serif;margin:0}.profile-title p{color:#7b8798;margin:5px 0 0}.profile-section-title{font:700 12px 'Manrope',sans-serif;text-transform:uppercase;letter-spacing:.08em;color:#4169e1;margin:15px 0}.profile-shell .form-control{border-color:#dce3ed;box-shadow:none;border-radius:7px;height:40px}.profile-shell textarea.form-control{height:auto}.profile-shell .btn-danger{background:#4169e1;border-color:#4169e1;border-radius:7px;font-weight:700;padding:10px 18px}.profile-shell label{font-size:12px;color:#526074}
</style>

<div class="content profile-shell">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Profile & business settings
                </div>
                <div class="panel-body">
                    @php($profilePhoto = auth()->user()->getFirstMediaUrl('profile_photo', 'preview'))
                    @php($coverImage = auth()->user()->getFirstMediaUrl('cover_image'))
                    <div class="profile-cover" @if($coverImage) style="background-image:linear-gradient(rgba(16,24,40,.2),rgba(16,24,40,.45)),url('{{ $coverImage }}')" @endif>
                        @if($profilePhoto)<img class="profile-avatar" src="{{ $profilePhoto }}" alt="Profile photo">@else <span class="profile-avatar profile-fallback">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</span>@endif
                    </div>
                    <div class="profile-title"><h3>{{ auth()->user()->name }}</h3><p>{{ auth()->user()->business_name ?: 'Complete your business profile' }}</p></div>
                    <form method="POST" action="{{ route("profile.password.updateProfile") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-section-title">Personal details</div>
                        <div class="row"><div class="col-sm-6 form-group">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div><div class="col-sm-6 form-group">
                            <label class="required" for="title">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div><div class="col-sm-6 form-group"><label for="phone">Phone</label><input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"></div>
                        <div class="col-sm-6 form-group"><label for="location">Location</label><input class="form-control" type="text" name="location" id="location" value="{{ old('location', auth()->user()->location) }}" placeholder="City, State"></div></div>
                        <div class="profile-section-title">Images</div>
                        <div class="row"><div class="col-sm-6 form-group"><label for="profile_photo">Profile photo</label><input class="form-control" type="file" accept="image/*" name="profile_photo" id="profile_photo"></div><div class="col-sm-6 form-group"><label for="cover_image">Cover image</label><input class="form-control" type="file" accept="image/*" name="cover_image" id="cover_image"></div></div>
                        <div class="profile-section-title">Business & tax details</div>
                        <div class="row"><div class="col-sm-6 form-group"><label for="business_name">Business name</label><input class="form-control" name="business_name" id="business_name" value="{{ old('business_name', auth()->user()->business_name) }}"></div><div class="col-sm-6 form-group"><label for="business_type">Business type</label><input class="form-control" name="business_type" id="business_type" value="{{ old('business_type', auth()->user()->business_type) }}"></div><div class="col-sm-6 form-group"><label for="gst_number">GST number</label><input class="form-control" name="gst_number" id="gst_number" value="{{ old('gst_number', auth()->user()->gst_number) }}"></div><div class="col-sm-6 form-group"><label for="pan_number">PAN number</label><input class="form-control" name="pan_number" id="pan_number" value="{{ old('pan_number', auth()->user()->pan_number) }}"></div><div class="col-sm-12 form-group"><label for="business_address">Business address</label><textarea class="form-control" name="business_address" id="business_address" rows="3">{{ old('business_address', auth()->user()->business_address) }}</textarea></div></div>
                        <div class="profile-section-title">Online presence</div>
                        <div class="row"><div class="col-sm-6 form-group"><label for="website">Website</label><input class="form-control" type="url" name="website" id="website" value="{{ old('website', auth()->user()->website) }}" placeholder="https://"></div><div class="col-sm-6 form-group"><label for="facebook_url">Facebook</label><input class="form-control" type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', auth()->user()->facebook_url) }}"></div><div class="col-sm-6 form-group"><label for="instagram_url">Instagram</label><input class="form-control" type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', auth()->user()->instagram_url) }}"></div><div class="col-sm-6 form-group"><label for="linkedin_url">LinkedIn</label><input class="form-control" type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}"></div></div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.change_password') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("profile.password.update") }}">
                        @csrf
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label class="required" for="password">New {{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password" id="password" required>
                            @if($errors->has('password'))
                                <span class="help-block" role="alert">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="required" for="password_confirmation">Repeat New {{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
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
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.delete_account') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("profile.password.destroyProfile") }}" onsubmit="return prompt('{{ __('global.delete_account_warning') }}') == '{{ auth()->user()->email }}'">
                        @csrf
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.delete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
