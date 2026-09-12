@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Select Godown to View Details</h3>
    </div>
    <div class="card-body">
        <select class="form-control" id="godownDropdown">
            <option value="">-- Select Godown --</option>
            @foreach($godowns as $godown)
                <option value="{{ $godown->id }}">{{ $godown->name }}</option>
            @endforeach
        </select>

        <div id="godownDetails" class="mt-4" style="display:none;">
            <h4>Godown Info</h4>
            <p><strong>Location:</strong> <span id="location"></span></p>
            <p><strong>Capacity:</strong> <span id="capacity"></span></p>
            <p><strong>Total Products:</strong> <span id="productCount"></span></p>

            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th>P. ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Company</th>
                            <th>Item Code</th>
                            <th>HSN Code</th>
                            <th>MRP</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <hr>
            <div class="mt-4">
                <h4>Transfer Product</h4>
                <form action="{{ route('admin.godowns.transfer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from_godown" id="fromGodownId">

                    <div class="form-group">
                        <label for="product_id">Product</label>
                        <select name="product_id" id="productSelect" class="form-control">
                            <!-- Products will be populated dynamically here -->
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="to_godown">To Godown</label>
                        <select name="to_godown" id="toGodown" class="form-control">
                            @foreach($godowns as $godown)
                                <option value="{{ $godown->id }}">{{ $godown->name }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#godownDropdown').on('change', function () {
        let id = $(this).val();
        if (!id) {
            $('#godownDetails').hide();
            return;
        }

        $.ajax({
            url: `/admin/godowns/details/${id}`,
            method: 'GET',
            success: function (data) {
                $('#location').text(data.location);
                $('#capacity').text(data.capacity);
                $('#productCount').text(data.product_count);
                $('#fromGodownId').val(id);
                $('#godownDetails').show();

                let tableBody = '';
                let productSelect = $('#productSelect');
                productSelect.empty().append(`<option value="">-- Select Product --</option>`);

                data.products.forEach(product => {
                    tableBody += `<tr>
                         <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${product.company_name}</td>
                        <td>${product.item_code}</td>
                        <td>${product.hsn_code}</td>
                        <td>${product.price}</td>
                    </tr>`;
                    productSelect.append(`<option value="${product.id}">${product.name} (${product.item_code})</option>`);
                });

                $('#productTable tbody').html(tableBody);
            }
        });
    });
</script>
@endsection
