@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.checkOrder.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.check-orders.update", [$checkOrder->id]) }}" enctype="multipart/form-data" id="order-form">
                        @method('PUT')
                        @csrf

                        {{-- User selection --}}
                        <div class="form-group {{ $errors->has('select_user') ? 'has-error' : '' }}">
                            <label for="select_user_id">{{ trans('cruds.checkOrder.fields.select_user') }}</label>
                            <select class="form-control select2" name="select_user_id" id="select_user_id">
                                @foreach($select_users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('select_user_id') ? old('select_user_id') : $checkOrder->select_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('select_user'))
                                <span class="help-block" role="alert">{{ $errors->first('select_user') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                          <label>{{ trans('cruds.checkOrder.fields.select_product') }}</label>
                          <table class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th>Select</th>
                                      <th>Product Name</th>
                                      <th>Available Quantity</th>
                                      <th>Ordered Quantity</th>
                                      <th>Confirm Quantity</th>
                                  </tr>
                              </thead>
                              @php
    $productData = [];

    $outer = json_decode($checkOrder->products, true);

    // If first element is a string (nested JSON), decode it
    if (is_array($outer)) {
        if (isset($outer[0]) && is_string($outer[0])) {
            $productData = json_decode($outer[0], true) ?? [];
        } else {
            $productData = $outer; // Already associative array
        }
    }
@endphp

<tbody>
@foreach($productData as $productId => $productInfo)
    @php
        $product = \App\Models\Product::find($productId); // Get product info from DB
        if (!$product) continue;

        $orderedQty = $productInfo['quantity'] ?? 0;
        $availableQty = $product->our_stock->quantity_available ?? 0;
        $qtyClass = $orderedQty > $availableQty ? 'text-danger' : 'text-success';
    @endphp
    <tr>
        <td>
            <input type="checkbox" name="select_products[]" value="{{ $product->id }}"
                checked class="product-checkbox">
        </td>
        <td>{{ $product->name }}</td>
        <td>
            <span class="available-qty" data-product-id="{{ $product->id }}">
                <b>{{ $availableQty }}</b>
            </span>
        </td>
        <td class="{{ $qtyClass }}"><b>{{ $orderedQty }}</b></td>
        <td>
            <input type="number" name="confirm_qty[{{ $product->id }}]"
                value="{{ $orderedQty }}"
                min="1"
                max="{{ $availableQty }}"
                class="form-control confirm-qty-input"
                data-available="{{ $availableQty }}">
        </td>
    </tr>
@endforeach
</tbody>


                            
                          </table>
                      </div>

                        {{-- Placed At --}}
                        <div class="form-group {{ $errors->has('placed_at') ? 'has-error' : '' }}">
                            <label class="required" for="placed_at">{{ trans('cruds.checkOrder.fields.placed_at') }}</label>
                            <input class="form-control datetime" type="text" name="placed_at" id="placed_at" value="{{ old('placed_at', $checkOrder->placed_at) }}" required>
                        </div>

                        {{-- Order Status --}}
                        <div class="form-group {{ $errors->has('order_status') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.checkOrder.fields.order_status') }}</label>
                            <select class="form-control" name="order_status" id="order_status" required>
                                <option value disabled {{ old('order_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\CheckOrder::ORDER_STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('order_status', $checkOrder->order_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="carrier_id">{{ __('Carrier') }}</label>
                            <select name="carrier_id" id="carrier_id" class="form-control">
                                @foreach($carriers as $id => $name)
                                    <option value="{{ $id }}" {{ old('carrier_id', $checkOrder->carrier_id) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Notes --}}
                        <div class="form-group {{ $errors->has('notes') ? 'has-error' : '' }}">
                            <label for="notes">{{ trans('cruds.checkOrder.fields.notes') }}</label>
                            <textarea class="form-control ckeditor" name="notes" id="notes">{!! old('notes', $checkOrder->notes) !!}</textarea>
                        </div>

                        {{-- Attachments --}}
                        <div class="form-group {{ $errors->has('attachment') ? 'has-error' : '' }}">
                            <label for="attachment">{{ trans('cruds.checkOrder.fields.attachment') }}</label>
                            <div class="needsclick dropzone" id="attachment-dropzone"></div>
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
    $(document).ready(function(){

        // Confirm quantity validation before form submit
        $('#order-form').submit(function(e){
            let error = false;
            $('.confirm-quantity').each(function(){
                let available = $(this).data('available');
                let selected = $(this).val();
                if (parseInt(selected) > parseInt(available)) {
                    error = true;
                    alert('Selected quantity cannot be greater than available stock!');
                    $(this).focus();
                    return false; // break loop
                }
            });
            if (error) {
                e.preventDefault();
            }
        });

    });
</script>

{{-- CKEditor Upload --}}
<script>
    $(document).ready(function () {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file.then(function(file) {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route('admin.check-orders.storeCKEditorImages') }}', true);
                                xhr.setRequestHeader('x-csrf-token', window._token);
                                xhr.setRequestHeader('Accept', 'application/json');
                                xhr.responseType = 'json';
                                xhr.addEventListener('error', function() { reject('Error') });
                                xhr.addEventListener('abort', function() { reject('Abort') });
                                xhr.addEventListener('load', function() {
                                    var response = xhr.response;
                                    if (!response || xhr.status !== 201) {
                                        return reject(response && response.message ? response.message : xhr.statusText);
                                    }
                                    $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                                    resolve({ default: response.url });
                                });
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
            ClassicEditor.create(allEditors[i], {
                extraPlugins: [SimpleUploadAdapter]
            });
        }
    });
</script>

{{-- Dropzone --}}
<script>
    var uploadedAttachmentMap = {}
Dropzone.options.attachmentDropzone = {
    url: '{{ route('admin.check-orders.storeMedia') }}',
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
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _ref.forEach(function(node){
             node.textContent = message
         })
     }
}
</script>
<script>
  $(document).ready(function () {
      $('.confirm-qty-input').on('input', function () {
          var available = parseInt($(this).data('available'));
          var entered = parseInt($(this).val());

          if (entered > available) {
              alert('⚠️ Confirm quantity cannot exceed available stock (' + available + ').');
              $(this).val(available);
          }
      });
  });
</script>
@endsection
