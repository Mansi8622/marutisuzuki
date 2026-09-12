@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.addCompany.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.add-companies.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="company_name">{{ trans('cruds.addCompany.fields.company_name') }}</label>
                            <input class="form-control" type="text" name="company_name" id="company_name" value="{{ old('company_name', '') }}" required>
                            @if($errors->has('company_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('company_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addCompany.fields.company_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="company_logo">{{ trans('cruds.addCompany.fields.company_logo') }}</label>
                            <div class="needsclick dropzone" id="company_logo-dropzone">
                            </div>
                            @if($errors->has('company_logo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('company_logo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addCompany.fields.company_logo_helper') }}</span>
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
    var uploadedCompanyLogoMap = {}
Dropzone.options.companyLogoDropzone = {
    url: '{{ route('frontend.add-companies.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="company_logo[]" value="' + response.name + '">')
      uploadedCompanyLogoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedCompanyLogoMap[file.name]
      }
      $('form').find('input[name="company_logo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($addCompany) && $addCompany->company_logo)
      var files = {!! json_encode($addCompany->company_logo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="company_logo[]" value="' + file.file_name + '">')
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