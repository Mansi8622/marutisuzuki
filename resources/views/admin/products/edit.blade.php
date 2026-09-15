@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.products.update", [$product->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('categories') ? 'has-error' : '' }}">
                            <label for="categories">{{ trans('cruds.product.fields.category') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="categories[]" id="categories" multiple>
                                @foreach($categories as $id => $category)
                                    <option value="{{ $id }}" {{ (in_array($id, old('categories', [])) || $product->categories->contains($id)) ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('categories'))
                                <span class="help-block" role="alert">{{ $errors->first('categories') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
                            <label for="tags">{{ trans('cruds.product.fields.tag') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="tags[]" id="tags" multiple>
                                @foreach($tags as $id => $tag)
                                    <option value="{{ $id }}" {{ (in_array($id, old('tags', [])) || $product->tags->contains($id)) ? 'selected' : '' }}>{{ $tag }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('tags'))
                                <span class="help-block" role="alert">{{ $errors->first('tags') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.tag_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('select_companies') ? 'has-error' : '' }}">
                            <label class="required" for="select_companies">{{ trans('cruds.product.fields.select_company') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="select_companies[]" id="select_companies" multiple required>
                                @foreach($select_companies as $id => $select_company)
                                    <option value="{{ $id }}" {{ (in_array($id, old('select_companies', [])) || $product->select_companies->contains($id)) ? 'selected' : '' }}>{{ $select_company }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_companies'))
                                <span class="help-block" role="alert">{{ $errors->first('select_companies') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.select_company_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('item_code') ? 'has-error' : '' }}">
                            <label class="required" for="item_code">{{ trans('cruds.product.fields.item_code') }}</label>
                            <input class="form-control" type="text" name="item_code" id="item_code" value="{{ old('item_code', $product->item_code) }}" required>
                            @if($errors->has('item_code'))
                                <span class="help-block" role="alert">{{ $errors->first('item_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.item_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('hsn_code') ? 'has-error' : '' }}">
                            <label class="required" for="hsn_code">{{ trans('cruds.product.fields.hsn_code') }}</label>
                            <input class="form-control" type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code', $product->hsn_code) }}" required>
                            @if($errors->has('hsn_code'))
                                <span class="help-block" role="alert">{{ $errors->first('hsn_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.hsn_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('godown') ? 'has-error' : '' }}">
                            <label class="required" for="godown">{{ trans('cruds.product.fields.godown') }}</label>
                            <input class="form-control" type="text" name="godown" id="godown" value="{{ old('godown', $product->godown) }}" required>
                            @if($errors->has('godown'))
                                <span class="help-block" role="alert">{{ $errors->first('godown') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.godown_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                            <label class="required" for="quantity">{{ trans('cruds.product.fields.quantity') }}</label>
                            <input class="form-control" type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                            @if($errors->has('quantity'))
                                <span class="help-block" role="alert">{{ $errors->first('quantity') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.godown_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                            <textarea class="form-control" name="description" id="description">{{ old('description', $product->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block" role="alert">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                            <label class="required" for="price">{{ trans('cruds.product.fields.price') }}</label>
                            <input class="form-control" type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                            @if($errors->has('price'))
                                <span class="help-block" role="alert">{{ $errors->first('price') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.price_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('discount') ? 'has-error' : '' }}">
                            <label for="discount">{{ trans('cruds.product.fields.discount') }}</label>
                            <input class="form-control" type="number" name="discount" id="discount" value="{{ old('discount', $product->discount) }}" step="1">
                            @if($errors->has('discount'))
                                <span class="help-block" role="alert">{{ $errors->first('discount') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.discount_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('price_1') ? 'has-error' : '' }}">
                            <label class="required" for="price_1">{{ trans('cruds.product.fields.price_1') }}</label>
                            <input class="form-control" type="number" name="price_1" id="price_1" value="{{ old('price_1', $product->price_1) }}" step="0.01" required>
                            @if($errors->has('price_1'))
                                <span class="help-block" role="alert">{{ $errors->first('price_1') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.price_1_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('rate_2') ? 'has-error' : '' }}">
                            <label for="rate_2">{{ trans('cruds.product.fields.rate_2') }}</label>
                            <input class="form-control" type="number" name="rate_2" id="rate_2" value="{{ old('rate_2', $product->rate_2) }}" step="0.01">
                            @if($errors->has('rate_2'))
                                <span class="help-block" role="alert">{{ $errors->first('rate_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.rate_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('rate_3') ? 'has-error' : '' }}">
                            <label for="rate_3">{{ trans('cruds.product.fields.rate_3') }}</label>
                            <input class="form-control" type="number" name="rate_3" id="rate_3" value="{{ old('rate_3', $product->rate_3) }}" step="0.01">
                            @if($errors->has('rate_3'))
                                <span class="help-block" role="alert">{{ $errors->first('rate_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.rate_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                            <label class="required" for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                            <div class="needsclick dropzone" id="photo-dropzone">
                            </div>
                            @if($errors->has('photo'))
                                <span class="help-block" role="alert">{{ $errors->first('photo') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.photo_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('product_photo_2') ? 'has-error' : '' }}">
                            <label for="product_photo_2">{{ trans('cruds.product.fields.product_photo_2') }}</label>
                            <div class="needsclick dropzone" id="product_photo_2-dropzone">
                            </div>
                            @if($errors->has('product_photo_2'))
                                <span class="help-block" role="alert">{{ $errors->first('product_photo_2') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.product_photo_2_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('product_photo_3') ? 'has-error' : '' }}">
                            <label for="product_photo_3">{{ trans('cruds.product.fields.product_photo_3') }}</label>
                            <div class="needsclick dropzone" id="product_photo_3-dropzone">
                            </div>
                            @if($errors->has('product_photo_3'))
                                <span class="help-block" role="alert">{{ $errors->first('product_photo_3') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.product_photo_3_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sku') ? 'has-error' : '' }}">
                            <label class="required" for="sku">{{ trans('cruds.product.fields.sku') }}</label>
                            <input class="form-control" type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required>
                            @if($errors->has('sku'))
                                <span class="help-block" role="alert">{{ $errors->first('sku') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.sku_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    @include('admin.products.fitments')
</form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedPhotoMap = {}
Dropzone.options.photoDropzone = {
    url: '{{ route('admin.products.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="photo[]" value="' + response.name + '">')
      uploadedPhotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPhotoMap[file.name]
      }
      $('form').find('input[name="photo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($product) && $product->photo)
      var files = {!! json_encode($product->photo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photo[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

</script>
<script>
    var uploadedProductPhoto2Map = {}
Dropzone.options.productPhoto2Dropzone = {
    url: '{{ route('admin.products.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="product_photo_2[]" value="' + response.name + '">')
      uploadedProductPhoto2Map[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedProductPhoto2Map[file.name]
      }
      $('form').find('input[name="product_photo_2[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($product) && $product->product_photo_2)
      var files = {!! json_encode($product->product_photo_2) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="product_photo_2[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

</script>
<script>
    Dropzone.options.productPhoto3Dropzone = {
    url: '{{ route('admin.products.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="product_photo_3"]').remove()
      $('form').append('<input type="hidden" name="product_photo_3" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="product_photo_3"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($product) && $product->product_photo_3)
      var file = {!! json_encode($product->product_photo_3) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="product_photo_3" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
@endsection