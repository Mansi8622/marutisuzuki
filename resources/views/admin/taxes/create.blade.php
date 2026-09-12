@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.tax.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.taxes.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.tax.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tax.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tax_rate') ? 'has-error' : '' }}">
                            <label class="required" for="tax_rate">{{ trans('cruds.tax.fields.tax_rate') }}</label>
                            <input class="form-control" type="text" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', '') }}" required>
                            @if($errors->has('tax_rate'))
                                <span class="help-block" role="alert">{{ $errors->first('tax_rate') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tax.fields.tax_rate_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.tax.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Tax::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'Inactive') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tax.fields.status_helper') }}</span>
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