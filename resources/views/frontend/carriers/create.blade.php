@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.carrier.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.carriers.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="carrier_name">{{ trans('cruds.carrier.fields.carrier_name') }}</label>
                            <input class="form-control" type="text" name="carrier_name" id="carrier_name" value="{{ old('carrier_name', '') }}" required>
                            @if($errors->has('carrier_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('carrier_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.carrier_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.carrier.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Carrier::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'Active') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="tracking_url">{{ trans('cruds.carrier.fields.tracking_url') }}</label>
                            <input class="form-control" type="text" name="tracking_url" id="tracking_url" value="{{ old('tracking_url', '') }}" required>
                            @if($errors->has('tracking_url'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tracking_url') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.tracking_url_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="phone">{{ trans('cruds.carrier.fields.phone') }}</label>
                            <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone', '') }}" required>
                            @if($errors->has('phone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phone') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.phone_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="email">{{ trans('cruds.carrier.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="brand_logo">{{ trans('cruds.carrier.fields.brand_logo') }}</label>
                            <div class="needsclick dropzone" id="brand_logo-dropzone">
                            </div>
                            @if($errors->has('brand_logo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('brand_logo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.carrier.fields.brand_logo_helper') }}</span>
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

@section('scripts')
<script>
    var uploadedBrandLogoMap = {}
Dropzone.options.brandLogoDropzone = {
    url: '{{ route('frontend.carriers.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="brand_logo[]" value="' + response.name + '">')
      uploadedBrandLogoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedBrandLogoMap[file.name]
      }
      $('form').find('input[name="brand_logo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($carrier) && $carrier->brand_logo)
      var files = {!! json_encode($carrier->brand_logo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="brand_logo[]" value="' + file.file_name + '">')
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
@endsection