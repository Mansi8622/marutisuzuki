@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.stockTransfer.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.stock-transfers.update", [$stockTransfer->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="select_products">{{ trans('cruds.stockTransfer.fields.select_product') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="select_products[]" id="select_products" multiple required>
                                @foreach($select_products as $id => $select_product)
                                    <option value="{{ $id }}" {{ (in_array($id, old('select_products', [])) || $stockTransfer->select_products->contains($id)) ? 'selected' : '' }}>{{ $select_product }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_products'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_products') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.stockTransfer.fields.select_product_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="select_user_id">{{ trans('cruds.stockTransfer.fields.select_user') }}</label>
                            <select class="form-control select2" name="select_user_id" id="select_user_id" required>
                                @foreach($select_users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_user_id') ? old('select_user_id') : $stockTransfer->select_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.stockTransfer.fields.select_user_helper') }}</span>
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