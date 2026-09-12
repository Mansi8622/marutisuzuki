@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Supplier Product</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.suppliers.updateProduct', $supplierProduct->id) }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6 form-group">
                <label>Supplier</label>
                <select name="supplier_id" class="form-control" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplierProduct->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label>Product SKU</label>
                <select name="product_id" id="product_sku" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-details="{{ json_encode($product) }}"
                            {{ $supplierProduct->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->sku }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4 form-group">
                <label>Price</label>
                <input type="number" name="price" value="{{ $supplierProduct->price }}" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" value="{{ $supplierProduct->quantity }}" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Discount (%)</label>
                <input type="number" name="discount" value="{{ $supplierProduct->discount }}" class="form-control">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4 form-group">
                <label>Price 1</label>
                <input type="number" name="price_1" value="{{ $supplierProduct->price_1 }}" class="form-control">
            </div>
            <div class="col-md-4 form-group">
                <label>Price 2</label>
                <input type="number" name="price_2" value="{{ $supplierProduct->price_2 }}" class="form-control">
            </div>
            <div class="col-md-4 form-group">
                <label>Price 3</label>
                <input type="number" name="price_3" value="{{ $supplierProduct->price_3 }}" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Product</button>
    </form>
</div>
@endsection
