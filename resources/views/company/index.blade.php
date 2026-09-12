@extends('layouts.frontend')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Replacements</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover border shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Order Number</th>
                    <th>Company Name</th>
                    <th>User / Customer</th>
                    <th>Order Details</th>
                    <th>Product Details</th>
                    <th>Notes</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($replacements as $replacement)
                    <tr>
                        <td>{{ $replacement->order_number }}</td>
                        <td>{{ $replacement->company->company_name ?? 'N/A' }}</td>

                        <!-- User or Customer Details -->
                        <td>
                            @if($replacement->user)
                                <span class="badge bg-primary">{{ $replacement->user->name }} (User)</span>
                            @elseif($replacement->customer)
                                <span class="badge bg-secondary">{{ $replacement->customer->name }} (Customer)</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <!-- Order Details -->
                        <td>
                            <strong>Order ID:</strong> {{ $replacement->checkOrder->id }} <br>
                            <strong>Date:</strong> {{ $replacement->checkOrder->created_at->format('d-m-Y') }}
                        </td>

                        <!-- Product Details -->
                        <td>
                            @if(isset($replacement->checkOrder) && is_iterable($replacement->checkOrder->product) && $replacement->checkOrder->product->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Item Code</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($replacement->checkOrder->product as $products)
                                        <tr>
                                            <td>{{ $products->name }}</td>
                                            <td>{{ $products->item_code }}</td>
                                            <td>{{ $products->pivot->quantity ?? 'N/A' }}</td>
                                            <td>{{ number_format($products->price, 2) }}</td>
                                            <td>{{ number_format($products->price * ($product->pivot->quantity ?? 1), 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>No products found for this replacement.</p>
                            @endif
                        </td>

                        <td>{{ $replacement->notes ?? 'N/A' }}</td>

                        <!-- Attachment -->
                        <td>
                            @if($replacement->attachment)
                                <a href="{{ asset('storage/' . $replacement->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                            @else
                                <span class="text-muted">No Attachment</span>
                            @endif
                        </td>
                        <td>{{ $replacement->status ?? 'N/A' }}</td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('frontend.replacements.show', $replacement->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('frontend.replacements.edit', $replacement->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('frontend.replacements.download', $replacement->id) }}" class="btn btn-success btn-sm">Download</a>
                                <form action="{{ route('frontend.replacements.destroy', $replacement->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
