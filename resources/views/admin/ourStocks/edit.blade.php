@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.ourStock.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.our-stocks.update", [$ourStock->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('select_product') ? 'has-error' : '' }}">
                            <label class="required" for="select_product_id">{{ trans('cruds.ourStock.fields.select_product') }}</label>
                            <select class="form-control select2" name="select_product_id" id="select_product_id" required>
                                @foreach($select_products as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_product_id') ? old('select_product_id') : $ourStock->select_product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_product'))
                                <span class="help-block" role="alert">{{ $errors->first('select_product') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ourStock.fields.select_product_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('quantity_available') ? 'has-error' : '' }}">
                            <label for="quantity_available">{{ trans('cruds.ourStock.fields.quantity_available') }}</label>
                            <input class="form-control" type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', $ourStock->quantity_available) }}" step="1">
                            @if($errors->has('quantity_available'))
                                <span class="help-block" role="alert">{{ $errors->first('quantity_available') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ourStock.fields.quantity_available_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sku') ? 'has-error' : '' }}">
                            <label for="sku">{{ trans('cruds.ourStock.fields.sku') }}</label>
                            <input class="form-control" type="text" name="sku" id="sku" value="{{ old('sku', $ourStock->sku) }}">
                            @if($errors->has('sku'))
                                <span class="help-block" role="alert">{{ $errors->first('sku') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.ourStock.fields.sku_helper') }}</span>
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