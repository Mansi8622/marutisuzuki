@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.checkOrder.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.check-orders.update", [$checkOrder->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="select_user_id">{{ trans('cruds.checkOrder.fields.select_user') }}</label>
                            <select class="form-control select2" name="select_user_id" id="select_user_id">
                                @foreach($select_users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_user_id') ? old('select_user_id') : $checkOrder->select_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.select_user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="select_products">{{ trans('cruds.checkOrder.fields.select_product') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="select_products[]" id="select_products" multiple>
                                @foreach($select_products as $id => $select_product)
                                    <option value="{{ $id }}" {{ (in_array($id, old('select_products', [])) || $checkOrder->select_products->contains($id)) ? 'selected' : '' }}>{{ $select_product }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_products'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('select_products') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.select_product_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="placed_at">{{ trans('cruds.checkOrder.fields.placed_at') }}</label>
                            <input class="form-control datetime" type="text" name="placed_at" id="placed_at" value="{{ old('placed_at', $checkOrder->placed_at) }}" required>
                            @if($errors->has('placed_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('placed_at') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.placed_at_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.checkOrder.fields.order_status') }}</label>
                            <select class="form-control" name="order_status" id="order_status" required>
                                <option value disabled {{ old('order_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\CheckOrder::ORDER_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('order_status', $checkOrder->order_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('order_status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.order_status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="notes">{{ trans('cruds.checkOrder.fields.notes') }}</label>
                            <textarea class="form-control ckeditor" name="notes" id="notes">{!! old('notes', $checkOrder->notes) !!}</textarea>
                            @if($errors->has('notes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('notes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.notes_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="attachment">{{ trans('cruds.checkOrder.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone">
                            </div>
                            @if($errors->has('attachment'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('attachment') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.checkOrder.fields.attachment_helper') }}</span>
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
                xhr.open('POST', '{{ route('frontend.check-orders.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $checkOrder->id ?? 0 }}');
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
    var uploadedAttachmentMap = {}
Dropzone.options.attachmentDropzone = {
    url: '{{ route('frontend.check-orders.storeMedia') }}',
    maxFilesize: 100, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 100
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachment[]" value="' + response.name + '">')
      uploadedAttachmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentMap[file.name]
      }
      $('form').find('input[name="attachment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($checkOrder) && $checkOrder->attachment)
          var files =
            {!! json_encode($checkOrder->attachment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachment[]" value="' + file.file_name + '">')
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