@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.ourStock.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.our-stocks.update", [$ourStock->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="select_product_id">{{ trans('cruds.ourStock.fields.select_product') }}</label>
                            <select class="form-control select2" name="select_product_id" id="select_product_id" required>
                                @foreach($select_products as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_product_id') ? old('select_product_id') : $ourStock->select_product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_product') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ourStock.fields.select_product_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="quantity_available">{{ trans('cruds.ourStock.fields.quantity_available') }}</label>
                            <input class="form-control" type="number" name="quantity_available" id="quantity_available" value="{{ old('quantity_available', $ourStock->quantity_available) }}" step="1">
                            @if($errors->has('quantity_available'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('quantity_available') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.ourStock.fields.quantity_available_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sku">{{ trans('cruds.ourStock.fields.sku') }}</label>
                            <input class="form-control" type="text" name="sku" id="sku" value="{{ old('sku', $ourStock->sku) }}">
                            @if($errors->has('sku'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sku') }}
                                </div>
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