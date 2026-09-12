@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.refund.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('frontend.refunds.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <!-- Refund Status Field -->
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.refund.fields.status') }}</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}
                                </option>
                                @foreach(App\Models\Refund::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'Open') === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                        </div>

                        <!-- Note Field -->
                        <div class="form-group">
                            <label for="note">{{ trans('cruds.refund.fields.note') }}</label>
                            <textarea class="form-control ckeditor" name="note" id="note">{!! old('note') !!}</textarea>
                            @if($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                        </div>

                        <!-- Attachment Field -->
                        <div class="form-group">
                            <label for="attachment">{{ trans('cruds.refund.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone"></div>
                            @if($errors->has('attachment'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('attachment') }}
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
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

<!-- CSRF Token for AJAX requests -->
<script>
    document.head.insertAdjacentHTML('beforeend', '<meta name="csrf-token" content="{{ csrf_token() }}">');
</script>

<!-- CKEditor for Note Field -->
<script>
    $(document).ready(function () {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file
                            .then(function (file) {
                                return new Promise(function(resolve, reject) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('frontend.refunds.storeCKEditorImages') }}', true);
                                    xhr.setRequestHeader('x-csrf-token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    xhr.addEventListener('error', function() { reject("Upload failed"); });
                                    xhr.addEventListener('abort', function() { reject("Upload aborted"); });
                                    xhr.addEventListener('load', function() {
                                        var response = xhr.response;
                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response.message ? response.message : 'Upload failed');
                                        }
                                        $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                                        resolve({ default: response.url });
                                    });

                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $refund->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            });
                    }
                };
            };
        }

        var allEditors = document.querySelectorAll('.ckeditor');
        for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(allEditors[i], { extraPlugins: [SimpleUploadAdapter] });
        }
    });
</script>

<!-- Dropzone Configuration -->
<script>
    Dropzone.options.attachmentDropzone = {
        url: '{{ route('frontend.refunds.storeMedia') }}',
        maxFilesize: 10, // 10MB Max File Size
        acceptedFiles: "image/*,application/pdf", // Accept Images & PDFs
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="attachment[]" value="' + response.name + '">');
        },
        removedfile: function (file) {
            file.previewElement.remove();
            $('form').find('input[name="attachment[]"][value="' + file.upload.filename + '"]').remove();
        },
        init: function () {
            @if(isset($refund) && $refund->attachment)
                var files = {!! json_encode($refund->attachment) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="attachment[]" value="' + file.file_name + '">');
                }
            @endif
        },
        error: function (file, response) {
            var message = $.type(response) === 'string' ? response : response.errors.file;
            file.previewElement.classList.add('dz-error');
            $(file.previewElement).find('[data-dz-errormessage]').text(message);
        }
    };
</script>

@endsection
