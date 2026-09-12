@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.support.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.supports.update", [$support->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('live_chat') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.support.fields.live_chat') }}</label>
                            @foreach(App\Models\Support::LIVE_CHAT_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="live_chat_{{ $key }}" name="live_chat" value="{{ $key }}" {{ old('live_chat', $support->live_chat) === (string) $key ? 'checked' : '' }}>
                                    <label for="live_chat_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('live_chat'))
                                <span class="help-block" role="alert">{{ $errors->first('live_chat') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.support.fields.live_chat_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('support_phone') ? 'has-error' : '' }}">
                            <label for="support_phone">{{ trans('cruds.support.fields.support_phone') }}</label>
                            <input class="form-control" type="text" name="support_phone" id="support_phone" value="{{ old('support_phone', $support->support_phone) }}">
                            @if($errors->has('support_phone'))
                                <span class="help-block" role="alert">{{ $errors->first('support_phone') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.support.fields.support_phone_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('toll_free_number') ? 'has-error' : '' }}">
                            <label for="toll_free_number">{{ trans('cruds.support.fields.toll_free_number') }}</label>
                            <input class="form-control" type="text" name="toll_free_number" id="toll_free_number" value="{{ old('toll_free_number', $support->toll_free_number) }}">
                            @if($errors->has('toll_free_number'))
                                <span class="help-block" role="alert">{{ $errors->first('toll_free_number') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.support.fields.toll_free_number_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('support_email') ? 'has-error' : '' }}">
                            <label for="support_email">{{ trans('cruds.support.fields.support_email') }}</label>
                            <input class="form-control" type="email" name="support_email" id="support_email" value="{{ old('support_email', $support->support_email) }}">
                            @if($errors->has('support_email'))
                                <span class="help-block" role="alert">{{ $errors->first('support_email') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.support.fields.support_email_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('refund_ploicy') ? 'has-error' : '' }}">
                            <label for="refund_ploicy">{{ trans('cruds.support.fields.refund_ploicy') }}</label>
                            <textarea class="form-control ckeditor" name="refund_ploicy" id="refund_ploicy">{!! old('refund_ploicy', $support->refund_ploicy) !!}</textarea>
                            @if($errors->has('refund_ploicy'))
                                <span class="help-block" role="alert">{{ $errors->first('refund_ploicy') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.support.fields.refund_ploicy_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.supports.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $support->id ?? 0 }}');
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

@endsection