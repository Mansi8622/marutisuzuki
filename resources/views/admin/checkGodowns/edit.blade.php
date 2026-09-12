@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.checkGodown.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.check-godowns.update", [$checkGodown->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('select_product') ? 'has-error' : '' }}">
                            <label class="required" for="select_product_id">{{ trans('cruds.checkGodown.fields.select_product') }}</label>
                            <select class="form-control select2" name="select_product_id" id="select_product_id" required>
                                @foreach($select_products as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_product_id') ? old('select_product_id') : $checkGodown->select_product->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_product'))
                                <span class="help-block" role="alert">{{ $errors->first('select_product') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkGodown.fields.select_product_helper') }}</span>
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