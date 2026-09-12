@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.addCompany.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.add-companies.update", [$addCompany->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
                            <label class="required" for="company_name">{{ trans('cruds.addCompany.fields.company_name') }}</label>
                            <input class="form-control" type="text" name="company_name" id="company_name" value="{{ old('company_name', $addCompany->company_name) }}" required>
                            @if($errors->has('company_name'))
                                <span class="help-block" role="alert">{{ $errors->first('company_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.addCompany.fields.company_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('company_logo') ? 'has-error' : '' }}">
                            <label for="company_logo">{{ trans('cruds.addCompany.fields.company_logo') }}</label>
                            <div class="needsclick dropzone" id="company_logo-dropzone">
                            </div>
                            @if($errors->has('company_logo'))
                                <span class="help-block" role="alert">{{ $errors->first('company_logo') }}</span>
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
    url: '{{ route('admin.add-companies.storeMedia') }}',
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