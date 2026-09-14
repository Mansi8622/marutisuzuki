@extends('layouts.admin')
@section('styles')
@include('admin.users.partials.wizard-styles')
@endsection
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default user-wizard">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
                </div>
                <div class="panel-body">
                    <form id="userWizard" method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                            @if($errors->has('email'))
                                <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label class="required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                            <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required>
                            @if($errors->has('phone'))
                                <span class="help-block" role="alert">{{ $errors->first('phone') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.phone_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('approved') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="approved" value="0">
                                <input type="checkbox" name="approved" id="approved" value="1" {{ $user->approved || old('approved', 0) === 1 ? 'checked' : '' }}>
                                <label for="approved" style="font-weight: 400">{{ trans('cruds.user.fields.approved') }}</label>
                            </div>
                            @if($errors->has('approved'))
                                <span class="help-block" role="alert">{{ $errors->first('approved') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.approved_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('business_name') ? 'has-error' : '' }}">
                            <label class="required" for="business_name">{{ trans('cruds.user.fields.business_name') }}</label>
                            <input class="form-control" type="text" name="business_name" id="business_name" value="{{ old('business_name', $user->business_name) }}" required>
                            @if($errors->has('business_name'))
                                <span class="help-block" role="alert">{{ $errors->first('business_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.business_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('business_type') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.user.fields.business_type') }}</label>
                            <select class="form-control" name="business_type" id="business_type" required>
                                <option value disabled {{ old('business_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\User::BUSINESS_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('business_type', $user->business_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('business_type'))
                                <span class="help-block" role="alert">{{ $errors->first('business_type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.business_type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('gst_number') ? 'has-error' : '' }}">
                            <label for="gst_number">{{ trans('cruds.user.fields.gst_number') }}</label>
                            <input class="form-control" type="text" name="gst_number" id="gst_number" value="{{ old('gst_number', $user->gst_number) }}">
                            @if($errors->has('gst_number'))
                                <span class="help-block" role="alert">{{ $errors->first('gst_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.gst_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('pan_number') ? 'has-error' : '' }}">
                            <label for="pan_number">{{ trans('cruds.user.fields.pan_number') }}</label>
                            <input class="form-control" type="text" name="pan_number" id="pan_number" value="{{ old('pan_number', $user->pan_number) }}">
                            @if($errors->has('pan_number'))
                                <span class="help-block" role="alert">{{ $errors->first('pan_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.pan_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('business_address') ? 'has-error' : '' }}">
                            <label for="business_address">{{ trans('cruds.user.fields.business_address') }}</label>
                            <textarea class="form-control ckeditor" name="business_address" id="business_address">{!! old('business_address', $user->business_address) !!}</textarea>
                            @if($errors->has('business_address'))
                                <span class="help-block" role="alert">{{ $errors->first('business_address') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.business_address_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
                            <label for="bank_name">{{ trans('cruds.user.fields.bank_name') }}</label>
                            <input class="form-control" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $user->bank_name) }}">
                            @if($errors->has('bank_name'))
                                <span class="help-block" role="alert">{{ $errors->first('bank_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.bank_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('account_number') ? 'has-error' : '' }}">
                            <label for="account_number">{{ trans('cruds.user.fields.account_number') }}</label>
                            <input class="form-control" type="text" name="account_number" id="account_number" value="{{ old('account_number', $user->account_number) }}">
                            @if($errors->has('account_number'))
                                <span class="help-block" role="alert">{{ $errors->first('account_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.account_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('ifsc_code') ? 'has-error' : '' }}">
                            <label for="ifsc_code">{{ trans('cruds.user.fields.ifsc_code') }}</label>
                            <input class="form-control" type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', $user->ifsc_code) }}">
                            @if($errors->has('ifsc_code'))
                                <span class="help-block" role="alert">{{ $errors->first('ifsc_code') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.ifsc_code_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('account_holder_name') ? 'has-error' : '' }}">
                            <label for="account_holder_name">{{ trans('cruds.user.fields.account_holder_name') }}</label>
                            <input class="form-control" type="text" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name', $user->account_holder_name) }}">
                            @if($errors->has('account_holder_name'))
                                <span class="help-block" role="alert">{{ $errors->first('account_holder_name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.account_holder_name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('kyc_documents_front') ? 'has-error' : '' }}">
                            <label for="kyc_documents_front">{{ trans('cruds.user.fields.kyc_documents_front') }}</label>
                            <div class="needsclick dropzone" id="kyc_documents_front-dropzone">
                            </div>
                            @if($errors->has('kyc_documents_front'))
                                <span class="help-block" role="alert">{{ $errors->first('kyc_documents_front') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.kyc_documents_front_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('kyc_documents_back') ? 'has-error' : '' }}">
                            <label for="kyc_documents_back">{{ trans('cruds.user.fields.kyc_documents_back') }}</label>
                            <div class="needsclick dropzone" id="kyc_documents_back-dropzone">
                            </div>
                            @if($errors->has('kyc_documents_back'))
                                <span class="help-block" role="alert">{{ $errors->first('kyc_documents_back') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.kyc_documents_back_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('business_registration_certificate') ? 'has-error' : '' }}">
                            <label for="business_registration_certificate">{{ trans('cruds.user.fields.business_registration_certificate') }}</label>
                            <div class="needsclick dropzone" id="business_registration_certificate-dropzone">
                            </div>
                            @if($errors->has('business_registration_certificate'))
                                <span class="help-block" role="alert">{{ $errors->first('business_registration_certificate') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.business_registration_certificate_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('license_details') ? 'has-error' : '' }}">
                            <label for="license_details">{{ trans('cruds.user.fields.license_details') }}</label>
                            <input class="form-control" type="text" name="license_details" id="license_details" value="{{ old('license_details', $user->license_details) }}">
                            @if($errors->has('license_details'))
                                <span class="help-block" role="alert">{{ $errors->first('license_details') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.license_details_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.user.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\User::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $user->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('vendor') ? 'has-error' : '' }}">
                            <label class="required" for="vendor">{{ trans('cruds.user.fields.vendor') }}</label>
                            <input class="form-control" type="text" name="vendor" id="vendor" value="{{ old('vendor', $user->vendor) }}" required>
                            @if($errors->has('vendor'))
                                <span class="help-block" role="alert">{{ $errors->first('vendor') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.vendor_helper') }}</span>
                        </div>

                        
                        
                        
                        <!-- Button to generate Vendor ID -->
                        <button type="button" class="btn btn-primary" onclick="generateVendorId()">Generate Vendor ID</button>
                        
                        <script>
                            function generateVendorId() {
                                // Generate 4 random capital letters
                                const letters = Array.from({ length: 4 }, () => String.fromCharCode(65 + Math.floor(Math.random() * 26))).join('');
                                // Generate 4 random digits
                                const numbers = Array.from({ length: 4 }, () => Math.floor(Math.random() * 10)).join('');
                                // Combine letters and numbers
                                const vendorId = letters + numbers;
                        
                                // Fill the input field with the generated Vendor ID
                                document.getElementById('vendor').value = vendorId;
                            }
                        </script>
                        

                        <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                            <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="roles[]" id="roles" multiple required>
                                @foreach($roles as $id => $role)
                                    <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('roles'))
                                <span class="help-block" role="alert">{{ $errors->first('roles') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                            <input class="form-control" type="password" name="password" id="password">
                            @if($errors->has('password'))
                                <span class="help-block" role="alert">{{ $errors->first('password') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
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
@include('admin.users.partials.wizard-script')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.users.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $user->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    var uploadedKycDocumentsFrontMap = {}
Dropzone.options.kycDocumentsFrontDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="kyc_documents_front[]" value="' + response.name + '">')
      uploadedKycDocumentsFrontMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedKycDocumentsFrontMap[file.name]
      }
      $('form').find('input[name="kyc_documents_front[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($user) && $user->kyc_documents_front)
      var files = {!! json_encode($user->kyc_documents_front) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="kyc_documents_front[]" value="' + file.file_name + '">')
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
    Dropzone.options.kycDocumentsBackDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
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
      $('form').find('input[name="kyc_documents_back"]').remove()
      $('form').append('<input type="hidden" name="kyc_documents_back" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="kyc_documents_back"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->kyc_documents_back)
      var file = {!! json_encode($user->kyc_documents_back) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="kyc_documents_back" value="' + file.file_name + '">')
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
<script>
    var uploadedBusinessRegistrationCertificateMap = {}
Dropzone.options.businessRegistrationCertificateDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
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
      $('form').append('<input type="hidden" name="business_registration_certificate[]" value="' + response.name + '">')
      uploadedBusinessRegistrationCertificateMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedBusinessRegistrationCertificateMap[file.name]
      }
      $('form').find('input[name="business_registration_certificate[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($user) && $user->business_registration_certificate)
      var files = {!! json_encode($user->business_registration_certificate) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="business_registration_certificate[]" value="' + file.file_name + '">')
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
