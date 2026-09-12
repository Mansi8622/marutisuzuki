@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel-heading">
                {{ trans('global.create') }} {{ trans('cruds.stockTransfer.title_singular') }}
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.stock-transfers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Select Products -->
                    <div class="form-group {{ $errors->has('select_products') ? 'has-error' : '' }}">
                        <label class="required" for="select_products">{{ trans('cruds.stockTransfer.fields.select_product') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2" name="select_products[]" id="select_products" multiple required>
                            @foreach($select_products as $select_product)
                                <option value="{{ $select_product->id }}" data-quantity="{{ $select_product->quantity_available }}" data-sku="{{ $select_product->sku }}">
                                    {{ $select_product->sku }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('select_products'))
                            <span class="help-block" role="alert">{{ $errors->first('select_products') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockTransfer.fields.select_product_helper') }}</span>
                    </div>

                    <!-- Selected Products Table -->
                    <div id="selected_products_table" class="table-responsive">
                        <table class="table table-bordered" id="product_table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Selected Quantity</th>
                                    <th>Available Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamically added rows will appear here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Select User -->
                    <div class="form-group {{ $errors->has('select_user') ? 'has-error' : '' }}">
                        <label class="required" for="select_user_id">{{ trans('cruds.stockTransfer.fields.select_user') }}</label>
                        <select class="form-control select2" name="select_user_id" id="select_user_id" required>
                            @foreach($select_users as $id => $entry)
                                <option value="{{ $id }}" {{ old('select_user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('select_user'))
                            <span class="help-block" role="alert">{{ $errors->first('select_user') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.stockTransfer.fields.select_user_helper') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
        // When a product is selected, show its details in the table
        $('#select_products').on('change', function () {
            var selectedOptions = $(this).val(); // Get selected product IDs
            var availableQuantities = $(this).find('option:selected').map(function () {
                return $(this).data('quantity'); // Get data-quantity for each selected option
            }).get();
            var productSKUs = $(this).find('option:selected').map(function () {
                return $(this).data('sku'); // Get SKU for each selected option
            }).get();

            // Clear the table first to avoid duplicates
            $('#product_table tbody').empty();

            // Generate rows dynamically
            selectedOptions.forEach(function (productId, index) {
                var row = `
                    <tr id="product_row_${productId}">
                        <td>${productSKUs[index]}</td>
                        <td>
                            <input class="form-control product_quantity" type="number" 
                                   name="quantities[${productId}]" 
                                   data-product-id="${productId}" 
                                   value="1" min="1" max="${availableQuantities[index]}" required>
                        </td>
                        <td>${availableQuantities[index]}</td>
                        <td>
                            <button type="button" class="btn btn-danger remove-product" 
                                    data-product-id="${productId}">
                                Remove
                            </button>
                        </td>
                    </tr>
                `;
                $('#product_table tbody').append(row);
            });
        });

        // Remove a product row from the table
        $(document).on('click', '.remove-product', function () {
            var productId = $(this).data('product-id');
            $(`#product_row_${productId}`).remove(); // Remove the row
            $('#select_products option[value="' + productId + '"]').prop('selected', false); // Deselect the product
        });

        // Validate quantities dynamically
        $(document).on('input', '.product_quantity', function () {
            var enteredQuantity = parseInt($(this).val());
            var availableQuantity = parseInt($(this).attr('max'));

            if (enteredQuantity > availableQuantity) {
                alert('The entered quantity exceeds the available stock.');
                $(this).val(availableQuantity); // Reset to max available
            } else if (enteredQuantity < 1) {
                alert('The quantity must be at least 1.');
                $(this).val(1); // Reset to minimum value
            }
        });
    });
</script>
@endsection
@endsection
