@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.termCondition.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.term-conditions.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="title">{{ trans('cruds.termCondition.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="content">{{ trans('cruds.termCondition.fields.content') }}</label>
                            <textarea class="form-control ckeditor" name="content" id="content">{!! old('content') !!}</textarea>
                            @if($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.content_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="effective_date">{{ trans('cruds.termCondition.fields.effective_date') }}</label>
                            <input class="form-control date" type="text" name="effective_date" id="effective_date" value="{{ old('effective_date') }}">
                            @if($errors->has('effective_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('effective_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.effective_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="version_number">{{ trans('cruds.termCondition.fields.version_number') }}</label>
                            <input class="form-control" type="text" name="version_number" id="version_number" value="{{ old('version_number', '') }}">
                            @if($errors->has('version_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('version_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.version_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="banner_title">{{ trans('cruds.termCondition.fields.banner_title') }}</label>
                            <input class="form-control" type="text" name="banner_title" id="banner_title" value="{{ old('banner_title', '') }}">
                            @if($errors->has('banner_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('banner_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.banner_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="banner_subtitle">{{ trans('cruds.termCondition.fields.banner_subtitle') }}</label>
                            <input class="form-control" type="text" name="banner_subtitle" id="banner_subtitle" value="{{ old('banner_subtitle', '') }}">
                            @if($errors->has('banner_subtitle'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('banner_subtitle') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.banner_subtitle_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="banner_image">{{ trans('cruds.termCondition.fields.banner_image') }}</label>
                            <div class="needsclick dropzone" id="banner_image-dropzone">
                            </div>
                            @if($errors->has('banner_image'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('banner_image') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.banner_image_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.termCondition.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\TermCondition::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'Active') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.termCondition.fields.status_helper') }}</span>
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
                xhr.open('POST', '{{ route('frontend.term-conditions.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $termCondition->id ?? 0 }}');
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
    Dropzone.options.bannerImageDropzone = {
    url: '{{ route('frontend.term-conditions.storeMedia') }}',
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
      $('form').find('input[name="banner_image"]').remove()
      $('form').append('<input type="hidden" name="banner_image" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="banner_image"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($termCondition) && $termCondition->banner_image)
      var file = {!! json_encode($termCondition->banner_image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="banner_image" value="' + file.file_name + '">')
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