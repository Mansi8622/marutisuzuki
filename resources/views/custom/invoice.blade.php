<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #222;
        }

        .invoice-box {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 5px;
            border-radius: 12px;
            border-top: 5px solid #ffcc00;
        }

        .header {
            background: #ffcc00;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #222;
        }

        .company-card {
            border: 1px solid #e1e1e1;
            padding: 15px;
            border-radius: 10px;
            background: #fff8e1;
            margin-bottom: 20px;
        }

        .company-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .company-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        table.items {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 14px;
        }

        .items th {
            background: #ff6600;
            color: white;
            padding: 12px;
            border: 1px solid #e06700;
            text-align: left;
        }

        .items td {
            padding: 10px;
            border: 1px solid #eee;
            vertical-align: top;
        }

        .items tr:nth-child(even) {
            background: #fffaf0;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }

    </style>
</head>
<body>

<div class="invoice-box">

    <!-- Header -->
    <div class="header">
    <img src="{{ public_path('asset/img/msv-logo.png') }}" alt="Maruti Suzuki Ventures" style="width: 60px; height: 60px; object-fit: contain;">
    <h2>Invoice</h2>
    </div>

    <!-- Company Details -->
    <table class="items" style="margin-bottom: 20px;">
    <thead>
        <tr>
            <th colspan="2" style="background: #fff8e1; color: #222; font-size: 16px;">Company & Invoice Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 65%; vertical-align: top; background: #fff8e1;">
                <p><strong>Maruti Suzuki Ventures</strong></p>
                <p><strong>GSTIN:</strong> 10ABZFM8479K1ZC</p>
                <p><strong>Phone:</strong> 9263906099</p>
                <p><strong>Email:</strong> marutisuzukiventures@gmail.com</p>
                <p><strong>Address:</strong> 1st Floor Kamla Market, RK Bhattacharya Road, Patna, Bihar-800001</p>
            </td>
            <td style="width: 35%; text-align: right; background: #fff8e1;">
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            </td>
        </tr>
    </tbody>
</table>


    <!-- Retailer, Customer, Payment Details Table -->
    <table class="items">
        <thead>
            <tr>
                <th>Retailer Detail</th>
                <th>Customer Detail</th>
                <th>Payment Detail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Retailer -->
                <td>
                    <p><strong>Name:</strong> {{ $order->company ? $order->user->name : 'Company Name' }}</p>
                    <p><strong>Email:</strong> {{ $order->company ? $order->user->email : 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $order->company ? $order->user->phone : 'N/A' }}</p>
                </td>

                <!-- Customer -->
                <td>
                    <p><strong>Name:</strong> {{ $order->customer_name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                    <p><strong>Note:</strong> {{ $order->customer_notes ?? 'N/A' }}</p>
                    <p><strong>Info:</strong> {{ $order->info ?? 'N/A' }}</p>
                </td>

                <!-- Payment -->
                <td>
                    <p><strong>Status:</strong> {{ ucfirst($order->checkOrder->payment_status ?? 'N/A') }}</p>
                    <p><strong>Method:</strong> {{ ucfirst($order->checkOrder->payment_method ?? 'N/A') }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Product Table -->
    @php
        $qty = (int) ($order->quantity ?? 1);
        $unitPrice = (float) ($order->product->price_1 ?? 0);
        $price = (float) ($order->product->price ?? 0);
        $total = $qty * $unitPrice;
    @endphp

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $order->product->name ?? 'N/A' }}</strong><br>
                    <small>({{ $order->company->company_name ?? 'Company Name' }})</small>
                </td>
                <td><i class="fas fa-rupee-sign"></i>{{ number_format($price, 2) }}</td>
                <td>{{ $qty }}</td>
                <td><i class="fas fa-rupee-sign"></i>{{ number_format($unitPrice, 2) }}</td>
                <td>{{ $order->status ?? 'N/A' }}</td>
                <td><i class="fas fa-rupee-sign"></i>{{ number_format($total, 2) }}</td>
            </tr>

            <!-- Summary -->
            <tr>
                <td colspan="2"><strong>Total Quantity</strong></td>
                <td><strong>{{ $qty }}</strong></td>
                <td colspan="2" style="text-align: right;"><strong>Grand Total</strong></td>
                <td><strong><i class="fas fa-rupee-sign"></i>  {{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>For support, contact: {{ $order->company ? $order->company->support_email : 'su' }}</p>
    </div>

</div>
</body>
</html>
