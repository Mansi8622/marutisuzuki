@extends('custom.master')

    @section('content')

    <style>
            .dashboard{background:#f5f7fb}.replacement{background:#fff;border:1px solid #e5ebf3;border-radius:15px;box-shadow:0 10px 26px rgba(18,34,56,.06);padding:14px}.replacement h4{font:800 25px 'Barlow Condensed',sans-serif;color:#13243d}.replacement .table{border-radius:10px;overflow:hidden}.replacement .table thead{background:#172b49;color:#fff}.replacement .table th{border:0!important;font-size:11px;text-transform:uppercase;letter-spacing:.05em;padding:14px}.replacement .table td{vertical-align:middle;padding:13px;border-color:#edf1f5}.replacement .table tbody tr:hover{background:#f7f9ff}.replacement .primary-bg{background:#3566e8!important;border-radius:7px;font-weight:700}
            /* Custom Modal Width */
            @media (min-width: 768px) {
                .modal-dialog {
                    max-width: 75%;
                }
            }

            /* Scrollable Modal Header */

            /* Scrollable Modal Body */
            .modal-content {
                max-height: 90vh; /* Modal max height */
                overflow: hidden;
            }

            .modal-body {
                max-height: 80vh; /* Keep content inside screen */
                overflow-y: auto; /* Enable scrolling */
            }
        </style>

    <section class="dashboard py-5">
        <div class="container">
            <div class="row">
                            @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


                        
                        @include('custom.sidebar')

                <div class="col-lg-9 mb-3 replacement">
                    <div class="row">
                        <div class="col-12">
                            <div class="px-3 py-2 d-flex justify-content-between">
                                <h4 class="fw-bold">Replacement</h4>
                                <a href="#" class="decoration">
                                    <button class="btn primary-bg text-white px-4 py-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fa-solid fa-plus"></i> File Replacement
                                    </button>
                                </a>
                            </div>
                        </div>

                        @if (session('success'))
                        <div class="alert alert-success">
                            {!! session('success') !!}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {!! session('error') !!}
                        </div>
                    @endif
                    
                       <table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>Company</th>
            <th>Order #</th>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Issues</th>
            <th>Status</th>
            <th>Info</th>
            <th>Invoice</th>
        </tr>
    </thead>
    <tbody>
        @forelse($replacements as $order)
            <tr>
                <td>{{ $order->company->company_name ?? 'N/A' }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->issues }}</td>
                <td>
                    <span class="badge bg-info text-dark">
                        {{ ucfirst($order->status ?? 'Pending') }}
                    </span>
                </td>
                <td>{{ $order->info ?? 'No additional info' }}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="downloadInvoice({{ $order->id }})">
                        Download
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No replacements found.</td>
            </tr>
        @endforelse
    </tbody>
</table>



<script>
function downloadInvoice(orderId) {
    window.location.href = `/download-invoice/${orderId}`;

}
</script>





                        <!-- Order Details Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Order Number</th>
                        <td id="orderNumber1"></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td id="totalAmount1"></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td id="paymentMethod1"></td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td id="paymentStatus1"></td>
                    </tr>
                    <tr>
                        <th>Shipping Address</th>
                        <td id="shippingAddress1"></td>
                    </tr>
                    <tr>
                        <th>Billing Address</th>
                        <td id="billingAddress1"></td>
                    </tr>
                    <tr>
                        <th>Order Status</th>
                        <td id="orderStatus1"></td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td id="orderDate1"></td>
                    </tr>
                </table>

                <h4>Products</h4>
                <ul id="productList1" class="list-group"></ul>

                <div class="modal-footer">
                    <button id="downloadPdfBtn1" class="btn btn-primary">Download PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function () {
            let orderId = this.getAttribute('data-id');

            fetch(`/order-details/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Order not found');
                        return;
                    }

                    document.getElementById('orderNumber1').textContent = data.order.order_number ?? 'N/A';
                    document.getElementById('totalAmount1').textContent = data.order.total_amount ?? 'N/A';
                    document.getElementById('paymentMethod1').textContent = data.order.payment_method ?? 'N/A';
                    document.getElementById('paymentStatus1').textContent = data.order.payment_status ?? 'N/A';
                    document.getElementById('shippingAddress1').textContent = data.order.shipping_address ?? 'N/A';
                    document.getElementById('billingAddress1').textContent = data.order.billing_address ?? 'N/A';
                    document.getElementById('orderStatus1').textContent = data.order.order_status ?? 'N/A';
                    document.getElementById('orderDate1').textContent = data.order.created_at ?? 'N/A';

                    let productDetails = '';
                    if (data.products && data.products.length > 0) {
                        data.products.forEach(item => {
                            let companyNames = item.companies.length > 0 ? item.companies.join(', ') : 'N/A';
                            productDetails += `<li class="list-group-item">
                                <strong>${item.name}</strong> (Qty: ${item.quantity}) <br>
                                <em>Company: ${companyNames}</em>
                            </li>`;
                        });
                    } else {
                        productDetails = '<li class="list-group-item">No products found</li>';
                    }
                    document.getElementById('productList1').innerHTML = productDetails;

                    // Set order ID for download button
                    let downloadBtn = document.getElementById('downloadPdfBtn1');
                    downloadBtn.setAttribute('data-id', orderId);

                    // Ensure the event listener is attached only once
                    if (!downloadBtn.hasAttribute('data-listener')) {
                        downloadBtn.setAttribute('data-listener', 'true');
                        downloadBtn.addEventListener('click', function () {
                            let orderId = this.getAttribute('data-id');
                            if (!orderId) {
                                alert('No order selected');
                                return;
                            }

                            fetch(`/order-pdf/${orderId}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/pdf'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.blob();
                            })
                            .then(blob => {
                                const url = window.URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = url;
                                a.download = `order_${orderId}.pdf`;

                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                window.URL.revokeObjectURL(url);
                            })
                            .catch(error => {
                                console.error('Error downloading PDF:', error);
                                alert('Failed to download PDF');
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    alert('Failed to fetch order details');
                });
        });
    });
});
</script>

                        
                    </div>
                </div>

 <!-- Bootstrap 5 Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Replacement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('frontend.replacement.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Order Selection -->
                    <div class="mb-3">
                        <label for="orderSelect" class="form-label">Select Order:</label>
                        <select id="orderSelect" class="form-control" name="order_id">
                            <option value="">-- Select Order --</option>
                            @foreach ($orders as $order)
                                <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order Details -->
                    <div id="orderDetailsSection" style="display: none;">
                        <h3 class="mt-3">Order Details</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order Number</th>
                                    <td id="orderNumberDisplay"></td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td id="totalAmount"></td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td id="paymentMethod"></td>
                                </tr>
                                <tr>
                                    <th>Payment Status</th>
                                    <td id="paymentStatus"></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address</th>
                                    <td id="shippingAddress"></td>
                                </tr>
                                <tr>
                                    <th>Billing Address</th>
                                    <td id="billingAddress"></td>
                                </tr>
                                <tr>
                                    <th>Order Status</th>
                                    <td id="orderStatus"></td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td id="orderDate"></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Products -->
                        <h4 class="mt-3">Products</h4>
                        <div id="productList" class="list-group"></div>

                        <!-- Customer Info -->
                        <div class="card p-3 mt-3">
                            <h3>Customer Details</h3>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="customer_name" class="form-control" placeholder="Customer Name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="customer_email" class="form-control" placeholder="Customer Email">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="customer_phone" class="form-control" placeholder="Customer Phone">
                                </div>
                                <div class="col-12 mb-3">
                                    <textarea name="customer_notes" rows="3" class="form-control" placeholder="Customer Notes"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Issues & Notes -->
                        <div class="row mt-3">
                            <div class="col-12 mb-3">
                                <label>Issue</label>
                                <textarea name="issues" class="form-control" placeholder="Enter Issues"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Note</label>
                                <textarea name="notes" class="form-control"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Attachment</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                    <!-- Hidden fields -->
                    <input type="hidden" name="check_order_id" value="{{ old('check_order_id') }}" id="checkOrderId">
                    <input type="hidden" name="order_number" value="{{ old('order_number') }}" id="orderNumberHidden">
                    <input type="hidden" name="company_id" value="{{ old('company_id') }}" id="companyIdHidden">

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.getElementById('orderSelect').addEventListener('change', function () {
    let orderId = this.value;

    if (!orderId) {
        document.getElementById('orderDetailsSection').style.display = 'none';
        return;
    }

    fetch(`/order-details/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Order not found');
                return;
            }

            // Order details
            document.getElementById('orderNumberDisplay').textContent = data.order.order_number ?? 'N/A';
            document.getElementById('totalAmount').textContent = data.order.total_amount ?? 'N/A';
            document.getElementById('paymentMethod').textContent = data.order.payment_method ?? 'N/A';
            document.getElementById('paymentStatus').textContent = data.order.payment_status ?? 'N/A';
            document.getElementById('shippingAddress').textContent = data.order.shipping_address ?? 'N/A';
            document.getElementById('billingAddress').textContent = data.order.billing_address ?? 'N/A';
            document.getElementById('orderStatus').textContent = data.order.order_status ?? 'N/A';
            document.getElementById('orderDate').textContent = data.order.created_at ?? 'N/A';
            document.getElementById('checkOrderId').value = data.order.id ?? '';
            document.getElementById('orderNumberHidden').value = data.order.order_number ?? '';
            document.getElementById('companyIdHidden').value = data.order.company_id ?? '';

            // Product list with checkbox & quantity
            let productDetails = '';
            if (data.products && data.products.length > 0) {
                data.products.forEach((item, index) => {
                    let companyNames = item.companies.length > 0 ? item.companies.join(', ') : 'N/A';
                    productDetails += `
                        <div class="list-group-item">
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" name="selected_products[${index}][product_id]" value="${item.id}" id="productCheck${index}">
                                <label class="form-check-label" for="productCheck${index}">
                                    <strong>${item.name}</strong> — Qty Ordered: ${item.quantity} <br>
                                    <input type="hidden" name="selected_products[${index}][company_id]" value="${item.company_id}">
                                    <em>Company: ${companyNames}</em>
                                </label>
                            </div>
                            <div class="mt-2">
                                <label>Replacement Quantity:</label>
                                <input type="number" name="selected_products[${index}][quantity]" class="form-control quantity-input" min="1" max="${item.quantity}" disabled>
                            </div>
                        </div>`;
                });
            } else {
                productDetails = '<div class="list-group-item">No products found</div>';
            }
            document.getElementById('productList').innerHTML = productDetails;

            // Enable/Disable quantity input based on checkbox
            setTimeout(() => {
                document.querySelectorAll('.product-checkbox').forEach((checkbox, i) => {
                    checkbox.addEventListener('change', function () {
                        const quantityInput = this.closest('.list-group-item').querySelector('.quantity-input');
                        quantityInput.disabled = !this.checked;
                        if (!this.checked) quantityInput.value = ''; // Reset value if unchecked
                    });
                });
            }, 200);

            // Show order section
            document.getElementById('orderDetailsSection').style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching order details:', error);
            alert('Failed to fetch order details');
        });
});

</script>

@endsection
