@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.configuration.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.configurations.update", [$configuration->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="alert_quantity">{{ trans('cruds.configuration.fields.alert_quantity') }}</label>
                            <input class="form-control" type="number" name="alert_quantity" id="alert_quantity" value="{{ old('alert_quantity', $configuration->alert_quantity) }}" step="1">
                            @if($errors->has('alert_quantity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('alert_quantity') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.alert_quantity_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="defaulat_tax_id">{{ trans('cruds.configuration.fields.defaulat_tax') }}</label>
                            <select class="form-control select2" name="defaulat_tax_id" id="defaulat_tax_id">
                                @foreach($defaulat_taxes as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('defaulat_tax_id') ? old('defaulat_tax_id') : $configuration->defaulat_tax->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('defaulat_tax'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('defaulat_tax') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.defaulat_tax_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="order_handling">{{ trans('cruds.configuration.fields.order_handling') }}</label>
                            <input class="form-control" type="number" name="order_handling" id="order_handling" value="{{ old('order_handling', $configuration->order_handling) }}" step="0.01" required>
                            @if($errors->has('order_handling'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_handling') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.order_handling_helper') }}</span>
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