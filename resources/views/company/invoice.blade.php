@extends('layouts.frontend')

@section('content')
<div class="container" id="invoice">
    <h2 class="text-center">Replacement Invoice</h2>

    <div class="card p-4 mb-4">
        <h4>Order Information</h4>
        <p><strong>Order Number:</strong> {{ $replacement->order_number }}</p>
        <p><strong>Company Name:</strong> {{ $replacement->company->company_name ?? 'N/A' }}</p>

        <h4>User / Customer</h4>
        <p>
            @if($replacement->user)
                <strong>User:</strong> {{ $replacement->user->name }} (User)
            @elseif($replacement->customer)
                <strong>Customer:</strong> {{ $replacement->customer->name }} (Customer)
            @else
                <strong>User:</strong> N/A
            @endif
        </p>

        <h4>Order Details</h4>
        <p><strong>Order ID:</strong> {{ $replacement->checkOrder->id }}</p>
        <p><strong>Order Date:</strong> {{ $replacement->checkOrder->created_at->format('d M Y') }}</p>
    </div>

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



    <div class="card p-4 mb-4">
        <h4>Notes</h4>
        <p>{{ $replacement->notes ?? 'No notes available.' }}</p>
    </div>

    <div class="card p-4 mb-4">
        <h4>Attachment</h4>
        @if($replacement->attachment)
            <a href="{{ asset('storage/' . $replacement->attachment) }}" class="btn btn-primary" target="_blank">View Attachment</a>
        @else
            <p>No Attachment Available</p>
        @endif
    </div>

    <div class="text-center">
        <button onclick="downloadInvoice()" class="btn btn-success">Download Invoice</button>
        <a href="{{ route('frontend.replacements.company') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>

{{-- JavaScript for Downloading Invoice --}}
<script>
    function downloadInvoice() {
        const element = document.getElementById('invoice');
        const opt = {
            margin: 10,
            filename: 'replacement_invoice.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().from(element).set(opt).save();
    }
</script>

{{-- Include html2pdf.js for JS-based PDF generation --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@endsection
