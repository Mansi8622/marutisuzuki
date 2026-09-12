@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Supplier Product History</h4>

    <form method="GET" action="{{ route('admin.suppliers.products.history') }}">
        <div class="row">
            <div class="col-md-6">
                <label>Select Supplier</label>
                <select name="supplier_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if($supplierId)
        <div class="mt-4" style="margin-top:20px;">
            <a href="{{ route('admin.suppliers.products.history.export', $supplierId) }}" class="btn btn-success">Download CSV</a>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplierProducts as $sp)
                    <tr>
                        <td>{{ $sp->product ? $sp->product->name : 'N/A' }}</td>
                        <td>{{ $sp->product ? $sp->product->sku : 'N/A' }}</td>
                        <td>{{ $sp->quantity }}</td>
                        <td>{{ $sp->price }}</td>
                        <td>{{ $sp->discount }}</td>
                        <td>{{ $sp->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No products found for this supplier.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
@endsection
