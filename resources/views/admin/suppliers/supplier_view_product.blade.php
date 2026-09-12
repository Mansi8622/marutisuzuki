@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>View Supplier Product</h2>

    <table class="table table-bordered">
        <tr>
            <th>Supplier Name</th>
            <td>{{ $supplierProduct->supplier->name }}</td>
        </tr>
        <tr>
            <th>Product Name</th>
            <td>{{ $supplierProduct->product->name }}</td>
        </tr>
        <tr>
            <th>SKU</th>
            <td>{{ $supplierProduct->product->sku }}</td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td>{{ $supplierProduct->quantity }}</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>{{ $supplierProduct->price }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td>{{ $supplierProduct->discount }}</td>
        </tr>
    </table>

    <a href="{{ route('admin.suppliers.listProduct') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection
