@extends('custom.master')

@section('content')

<style>
.dashboard{background:#f5f7fb}.dashboard h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d;margin:0 0 18px}.dashboard .table-responsive{background:#fff;border:1px solid #e5ebf3;border-radius:14px;box-shadow:0 10px 24px rgba(18,34,56,.06);overflow:hidden}.dashboard .table{margin:0}.dashboard .table thead{background:#172b49;color:#fff}.dashboard .table th{border:0!important;font-size:11px;text-transform:uppercase;letter-spacing:.05em;padding:15px}.dashboard .table td{padding:15px 12px;vertical-align:middle;border-color:#eef2f6}.dashboard .table tbody tr:hover{background:#f7f9ff}.dashboard .btn{border-radius:7px;font-weight:700}.dashboard .btn-primary{background:#3566e8;border-color:#3566e8}
</style>

@php
    $tax = \App\Models\Tax::where('status', 'Active')->first();
@endphp

<section class="dashboard py-5">
    <div class="container">
        <div class="row">
@include('custom.sidebar')
            <div class="col-lg-9 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2"><div><h1>My Orders</h1><p class="text-muted mb-0">Track orders, shipping updates and invoices.</p></div><a class="btn btn-primary" href="/product"><i class="fa-solid fa-plus me-1"></i> New order</a></div>

    

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Serial No</th>
                        <th>Date</th>
                        <th>Order No</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Tracking</th>
                        <th>Note / Docket No</th>
                        <th>Attachment</th>
                        <th>Download</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $order->order_number }}</td>
                            <td>₹ {{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ ucfirst($order->order_status) }}</td>
                            <td>
                                @if($order->carrier)
                                    <a href="{{ $order->carrier->tracking_url }}" target="_blank" class="btn btn-outline-success">Track Order</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td onclick="copyToClipboard(this)" style="cursor: pointer;" title="Click to copy">
    {!! $order->notes ?? '' !!}
</td>
<script>
function copyToClipboard(element) {
    const text = element.innerText || element.textContent;
    navigator.clipboard.writeText(text).then(function() {
        alert('Copied: ' + text);
    }).catch(function(err) {
        alert('Failed to copy text');
    });
}
</script>

                            <td>
                                @if($order->attachment && count($order->attachment) > 0)
                                    @foreach($order->attachment as $url)
                                        <a href="{{ $url }}" target="_blank">View</a><br>
                                    @endforeach
                                @else
                                    No Attachment
                                @endif
                            </td>
                            

                            
                            <td>
                            <a href="{{ route('invoice.download', $order->order_number) }}" class="btn btn-sm btn-primary">
    Download
</a>




                            </td>
                            
                        </tr>

                       

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Order #{{ $order->order_number }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Please contact admin.</p>
                                        <p>Email: {{ $order->company ? $order->company->email : 'N/A' }}</p>
                                        <p>Phone: {{ $order->company ? $order->company->phone : 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script>
function generateInvoice(button) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Get order data from button attributes
    let orderNumber = button.getAttribute("data-order-number");
    let totalAmount = parseFloat(button.getAttribute("data-total-amount"));
    let productsJson = button.getAttribute("data-products");
    let taxRate = parseFloat(button.getAttribute("data-tax-rate")) || 0; // Get Tax Rate from DB, default 0 if not found

    // Default Company Information
    let companyName = "Your Company Pvt. Ltd.";
    let companyAddress = "123, Business Street, City, India - 110001";
    let companyPhone = "+91 98765 43210";
    let gstNumber = "GSTIN: 22AAAAA0000A1Z5";
    let invoiceDate = new Date().toLocaleDateString();

    // Invoice Header - Company Details
    doc.setFontSize(18);
    doc.text(companyName, 60, 15);
    doc.setFontSize(10);
    doc.text(companyAddress, 60, 22);
    doc.text(Phone: ${companyPhone}, 60, 28);
    doc.text(gstNumber, 60, 34);

    // Invoice Details
    doc.setFontSize(12);
    doc.text(Invoice Date: ${invoiceDate}, 10, 50);
    doc.text(Order Number: ${orderNumber}, 10, 60);
    doc.text(Tax Rate: ${taxRate}%, 10, 70);

    try {
        let products = JSON.parse(productsJson);

        let headers = [["Product Name", "Price (₹)", "Quantity", "Subtotal (₹)", GST (${taxRate}%), "Total (₹)"]];
        let data = [];
        let subtotal = 0, gstTotal = 0, grandTotal = 0;

        products.forEach(p => {
            let price = parseFloat(p.price);
            let quantity = parseInt(p.pivot.quantity);
            let productSubtotal = price * quantity;
            let gstAmount = (productSubtotal * taxRate) / 100;
            let totalWithGST = productSubtotal + gstAmount;

            subtotal += productSubtotal;
            gstTotal += gstAmount;
            grandTotal += totalWithGST;

            data.push([p.name, ₹${price.toFixed(2)}, quantity, ₹${productSubtotal.toFixed(2)}, ₹${gstAmount.toFixed(2)}, ₹${totalWithGST.toFixed(2)}]);
        });

        // Add Table
        doc.autoTable({
            startY: 80,
            head: headers,
            body: data,
            theme: 'grid',
            styles: { fontSize: 10 },
            headStyles: { fillColor: [0, 122, 255] }
        });

        // Tax Summary
        let finalY = doc.lastAutoTable.finalY + 10;
        doc.setFontSize(12);
        doc.text(Subtotal: ₹${subtotal.toFixed(2)}, 120, finalY);
        doc.text(GST (${taxRate}%): ₹${gstTotal.toFixed(2)}, 120, finalY + 7);
        doc.setFontSize(14);
        doc.text(Grand Total: ₹${grandTotal.toFixed(2)}, 120, finalY + 14);

        // Footer
        doc.setFontSize(12);
        doc.text("Thank you for your purchase!", 70, doc.internal.pageSize.height - 20);

        // Add Company Stamp (Optional)
        let stampImg = new Image();
        stampImg.src = "https://yourwebsite.com/stamp.png"; // Change this to actual stamp image URL

        stampImg.onload = function () {
            doc.addImage(stampImg, "PNG", 140, doc.internal.pageSize.height - 40, 40, 40);
            doc.save(Invoice_${orderNumber}.pdf);
        };

        // If stamp image is not available, directly download
        stampImg.onerror = function () {
            doc.save(Invoice_${orderNumber}.pdf);
        };

    } catch (error) {
        console.error("Error parsing products JSON:", error);
        alert("Error generating invoice. Check console for details.");
    }
}



    
</script>


<!-- Include jQuery & Bootstrap Only Once -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



@endsection
