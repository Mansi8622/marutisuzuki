@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="my-4">Supplier Products</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Supplier</th>
                        <th>Billing Information</th>
                        <th>Product Details</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplierProducts as $billingId => $group)
                        <tr>
                            <td class="align-top">
                                <strong>{{ $group->first()->supplier->name }}</strong>
                            </td>
                            <td class="align-top" style="min-width: 220px;">
                                <div><strong>Billing ID:</strong> {{ $billingId }}</div>
                                <div><strong>Date:</strong> {{ $group->first()->purchasing_date }}</div>
                                <div><strong>Bill No.:</strong> {{ $group->first()->bill_number }}</div>
                                <div><strong>Total:</strong> ₹{{ number_format($group->first()->bill_amount, 2) }}</div>
                            </td>
                            <td class="align-top">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>SKU</th>
                                                <th>Product Name</th>
                                                <th>Qty</th>
                                                <th>Price / pc</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($group as $product)
                                                @php
                                                    $details = json_decode($product->products, true);
                                                @endphp
                                                <tr>
                                                    <td>{{ $details['sku'] ?? '-' }}</td>
                                                    <td>{{ $details['name'] ?? '-' }}</td>
                                                    <td>{{ $details['quantity'] ?? '-' }}</td>
                                                    <td>₹{{ number_format($details['price'] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td class="align-top text-center">
                                <a href="{{ route('admin.suppliers.viewProduct', $group->first()->id) }}" class="btn btn-info btn-sm w-100 mb-1">View</a>
                                <a href="{{ route('admin.suppliers.editProduct', $group->first()->id) }}" class="btn btn-warning btn-sm w-100 mb-1">Edit</a>
                                <form action="{{ route('admin.suppliers.deleteProduct', $group->first()->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
