@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.aboutUs.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.about-uss.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="required" for="title">{{ trans('cruds.aboutUs.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('contant') ? 'has-error' : '' }}">
                            <label for="contant">{{ trans('cruds.aboutUs.fields.contant') }}</label>
                            <textarea class="form-control ckeditor" name="contant" id="contant">{!! old('contant') !!}</textarea>
                            @if($errors->has('contant'))
                                <span class="help-block" role="alert">{{ $errors->first('contant') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.contant_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('mission_statement') ? 'has-error' : '' }}">
                            <label for="mission_statement">{{ trans('cruds.aboutUs.fields.mission_statement') }}</label>
                            <textarea class="form-control ckeditor" name="mission_statement" id="mission_statement">{!! old('mission_statement') !!}</textarea>
                            @if($errors->has('mission_statement'))
                                <span class="help-block" role="alert">{{ $errors->first('mission_statement') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.mission_statement_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('vision_statement') ? 'has-error' : '' }}">
                            <label for="vision_statement">{{ trans('cruds.aboutUs.fields.vision_statement') }}</label>
                            <textarea class="form-control ckeditor" name="vision_statement" id="vision_statement">{!! old('vision_statement') !!}</textarea>
                            @if($errors->has('vision_statement'))
                                <span class="help-block" role="alert">{{ $errors->first('vision_statement') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.vision_statement_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('team_section_content') ? 'has-error' : '' }}">
                            <label for="team_section_content">{{ trans('cruds.aboutUs.fields.team_section_content') }}</label>
                            <textarea class="form-control ckeditor" name="team_section_content" id="team_section_content">{!! old('team_section_content') !!}</textarea>
                            @if($errors->has('team_section_content'))
                                <span class="help-block" role="alert">{{ $errors->first('team_section_content') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.team_section_content_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('achievements_content') ? 'has-error' : '' }}">
                            <label for="achievements_content">{{ trans('cruds.aboutUs.fields.achievements_content') }}</label>
                            <textarea class="form-control ckeditor" name="achievements_content" id="achievements_content">{!! old('achievements_content') !!}</textarea>
                            @if($errors->has('achievements_content'))
                                <span class="help-block" role="alert">{{ $errors->first('achievements_content') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.achievements_content_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('banner_title') ? 'has-error' : '' }}">
                            <label for="banner_title">{{ trans('cruds.aboutUs.fields.banner_title') }}</label>
                            <input class="form-control" type="text" name="banner_title" id="banner_title" value="{{ old('banner_title', '') }}">
                            @if($errors->has('banner_title'))
                                <span class="help-block" role="alert">{{ $errors->first('banner_title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.banner_title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('banner_subtitle') ? 'has-error' : '' }}">
                            <label for="banner_subtitle">{{ trans('cruds.aboutUs.fields.banner_subtitle') }}</label>
                            <input class="form-control" type="text" name="banner_subtitle" id="banner_subtitle" value="{{ old('banner_subtitle', '') }}">
                            @if($errors->has('banner_subtitle'))
                                <span class="help-block" role="alert">{{ $errors->first('banner_subtitle') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.banner_subtitle_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('banner_image') ? 'has-error' : '' }}">
                            <label for="banner_image">{{ trans('cruds.aboutUs.fields.banner_image') }}</label>
                            <div class="needsclick dropzone" id="banner_image-dropzone">
                            </div>
                            @if($errors->has('banner_image'))
                                <span class="help-block" role="alert">{{ $errors->first('banner_image') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.banner_image_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.aboutUs.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\AboutUs::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'Active') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.aboutUs.fields.status_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.about-uss.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $aboutUs->id ?? 0 }}');
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
    url: '{{ route('admin.about-uss.storeMedia') }}',
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
@if(isset($aboutUs) && $aboutUs->banner_image)
      var file = {!! json_encode($aboutUs->banner_image) !!}
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