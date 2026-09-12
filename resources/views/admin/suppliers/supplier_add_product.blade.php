@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Add Products for Supplier</h2>

    <form action="{{ route('admin.suppliers.storeProduct') }}" method="POST">
        @csrf

        <!-- Supplier Dropdown -->
        <div class="form-group col-lg-12">
            <label>Select Supplier</label>
            <select name="supplier_id" id="supplierSelect" class="form-control" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Company Dropdown -->
        <div class="form-group col-lg-12">
            <label>Select Company</label>
            <select name="company_id" id="companySelect" class="form-control" required disabled>
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" data-products='@json($company->products)'>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Product Dropdown -->
        <div class="form-group col-lg-4">
            <label>Select Product</label>
            <select id="productSelect" class="form-control" disabled>
                <option value="">-- Select Product --</option>
            </select>
        </div>

        <!-- Purchase Date & Bill Number -->
        <div class="col-lg-4 form-group">
            <label>Purchase Date</label>
            <input type="date" name="purchasing_date" class="form-control">
        </div>
        <div class="col-lg-4 form-group">
            <label>Invoice Number</label>
            <input type="text" name="bill_number" class="form-control" placeholder="Enter Invoice Number">
        </div>

        <!-- Product Table -->
        <div class="table-responsive mt-4 col-lg-12">
            <table class="table table-bordered" id="productTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>HSN Code</th>
                        <th>Price (With GST)</th>
                        <th>Quantity</th>
                        <th>Discount</th>
                        <th>Price 1</th>
                        <th>Price 2</th>
                        <th>Price 3</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>
        </div>

        <!-- Grand Total -->
        <div class="form-group col-lg-12">
            <label>Grand Total Bill Amount</label>
            <input type="number" name="bill_amount" id="grand_total" class="form-control" readonly>
        </div>

        <!-- Submit Button -->
        <div class="col-lg-6">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>

<script>
    let productIndex = 0;
    let selectedProducts = [];

    document.getElementById('supplierSelect').addEventListener('change', function () {
        document.getElementById('companySelect').disabled = false;
        document.getElementById('productSelect').disabled = true;
        document.getElementById('productSelect').innerHTML = '<option value="">-- Select Product --</option>';
    });

    document.getElementById('companySelect').addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        const products = JSON.parse(option.getAttribute('data-products') || '[]');

        const productSelect = document.getElementById('productSelect');
        productSelect.innerHTML = '<option value="">-- Select Product --</option>';

        products.forEach(product => {
            productSelect.innerHTML += `
                <option value="${product.id}" data-product='${JSON.stringify(product)}'>
                    ${product.sku} - ${product.name}
                </option>`;
        });

        productSelect.disabled = false;
    });

    document.getElementById('productSelect').addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        const productData = JSON.parse(option.getAttribute('data-product'));

        if (!productData || selectedProducts.includes(productData.id)) {
            alert("This product is already added or invalid.");
            return;
        }

        selectedProducts.push(productData.id);

        const row = `
            <tr data-id="${productData.id}">
                <td>
                    ${productData.name}
                    <input type="hidden" name="products[${productIndex}][product_id]" value="${productData.id}">
                    <input type="hidden" name="products[${productIndex}][name]" value="${productData.name}">
                    <input type="hidden" name="products[${productIndex}][sku]" value="${productData.sku}">
                </td>
                <td>
                    ${productData.hsn_code || ''}
                    <input type="hidden" name="products[${productIndex}][hsn_code]" value="${productData.hsn_code || ''}">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control purchasing_amount" name="products[${productIndex}][price]" data-index="${productIndex}" required>
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="products[${productIndex}][quantity]" data-index="${productIndex}" required>
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="products[${productIndex}][discount]">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="products[${productIndex}][price_1]">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="products[${productIndex}][price_2]">
                </td>
                <td>
                    <input type="number" step="0.01" class="form-control" name="products[${productIndex}][price_3]">
                </td>
                <td>
                    <input type="text" class="form-control total" name="products[${productIndex}][total]" data-index="${productIndex}" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${productData.id}, this)">Remove</button>
                </td>
            </tr>
        `;

        document.getElementById('productTableBody').insertAdjacentHTML('beforeend', row);
        productIndex++;
        this.value = "";
    });

    function removeRow(productId, btn) {
        selectedProducts = selectedProducts.filter(id => id !== productId);
        btn.closest('tr').remove();
        calculateGrandTotal();
    }

    document.getElementById('productTable').addEventListener('input', function (e) {
        if (e.target.classList.contains('purchasing_amount') || e.target.classList.contains('quantity')) {
            const index = e.target.getAttribute('data-index');
            const amountInput = document.querySelector(`[name="products[${index}][price]"]`);
            const quantityInput = document.querySelector(`[name="products[${index}][quantity]"]`);
            const totalInput = document.querySelector(`[name="products[${index}][total]"]`);

            const amount = parseFloat(amountInput.value) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;

            const total = amount * quantity;
            totalInput.value = amount === 0 ? "Free" : total.toFixed(2);
            calculateGrandTotal();
        }
    });

    function calculateGrandTotal() {
        let sum = 0;
        document.querySelectorAll('.total').forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) sum += val;
        });
        document.getElementById('grand_total').value = sum.toFixed(2);
    }
</script>


@endsection