@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.stockTransfer.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.stock-transfers.update", [$stockTransfer->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('select_products') ? 'has-error' : '' }}">
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
                                <span class="help-block" role="alert">{{ $errors->first('select_products') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.stockTransfer.fields.select_product_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('select_user') ? 'has-error' : '' }}">
                            <label class="required" for="select_user_id">{{ trans('cruds.stockTransfer.fields.select_user') }}</label>
                            <select class="form-control select2" name="select_user_id" id="select_user_id" required>
                                @foreach($select_users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_user_id') ? old('select_user_id') : $stockTransfer->select_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_user'))
                                <span class="help-block" role="alert">{{ $errors->first('select_user') }}</span>
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