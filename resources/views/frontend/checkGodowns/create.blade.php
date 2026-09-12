@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.checkGodown.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.check-godowns.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="select_product_id">{{ trans('cruds.checkGodown.fields.select_product') }}</label>
                            <select class="form-control select2" name="select_product_id" id="select_product_id" required>
                                @foreach($select_products as $id => $entry)
                                    <option value="{{ $id }}" {{ old('select_product_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_product') }}
                                </div>
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