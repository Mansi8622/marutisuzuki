@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.configuration.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.configurations.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('alert_quantity') ? 'has-error' : '' }}">
                            <label for="alert_quantity">{{ trans('cruds.configuration.fields.alert_quantity') }}</label>
                            <input class="form-control" type="number" name="alert_quantity" id="alert_quantity" value="{{ old('alert_quantity', '1') }}" step="1">
                            @if($errors->has('alert_quantity'))
                                <span class="help-block" role="alert">{{ $errors->first('alert_quantity') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.alert_quantity_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('defaulat_tax') ? 'has-error' : '' }}">
                            <label for="defaulat_tax_id">{{ trans('cruds.configuration.fields.defaulat_tax') }}</label>
                            <select class="form-control select2" name="defaulat_tax_id" id="defaulat_tax_id">
                                @foreach($defaulat_taxes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('defaulat_tax_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('defaulat_tax'))
                                <span class="help-block" role="alert">{{ $errors->first('defaulat_tax') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.defaulat_tax_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('order_handling') ? 'has-error' : '' }}">
                            <label class="required" for="order_handling">{{ trans('cruds.configuration.fields.order_handling') }}</label>
                            <input class="form-control" type="number" name="order_handling" id="order_handling" value="{{ old('order_handling', '0') }}" step="0.01" required>
                            @if($errors->has('order_handling'))
                                <span class="help-block" role="alert">{{ $errors->first('order_handling') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.configuration.fields.order_handling_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('due_date') ? 'has-error' : '' }}">
                            <label class="required" for="due_date">{{ trans('cruds.configuration.fields.due_date') }}</label>
                            <input class="form-control" type="number" name="due_date" id="due_date" value="{{ old('due_date', '7') }}" step="0.01" required>
                            @if($errors->has('due_date'))
                                <span class="help-block" role="alert">{{ $errors->first('due_date') }}</span>
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