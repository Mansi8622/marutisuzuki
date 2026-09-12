<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @page { margin: 10mm; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f4f4;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            width: 100%;
            max-width: 900px;
            margin: auto;
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
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }
        
         @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>
<body>
<div class="invoice-box">

    <!-- Header -->
    <div class="header">
        <img src="{{ asset('asset/img/logo.webp') }}" alt="Company Logo" style="width: 60px; height: 60px;">
        <h2>Order Invoice</h2>
    </div>

    <!-- Company & Invoice Details -->
    <table class="items" style="margin-bottom: 20px;">
        <thead>
        <tr>
            <th colspan="2" style="background: #fff8e1; color: #222; font-size: 16px;">Company & Invoice Details</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 65%; background: #fff8e1;">
                <p><strong>Maruti Suzuki Ventures</strong></p>
                <p><strong>GSTIN:</strong> 10ABZFM8479K1ZC</p>
                <p><strong>Phone:</strong> 9263906099</p>
                <p><strong>Email:</strong> marutisuzukiventures@gmail.com</p>
                <p><strong>Address:</strong> 1st Floor Kamla Market, RK Bhattacharya Road, Patna, Bihar-800001</p>
            </td>
            <td style="width: 35%; text-align: right; background: #fff8e1;">
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->placed_at)->format('M d, Y') }}</p>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- Retailer, Customer, Payment Details -->
    <table class="items">
        <thead>
        <tr>
            <th>Retailer Detail</th>
            <th>Order Quantity</th>
            <th>Payment Detail</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <!-- Retailer -->
            <td>
                <p><strong>Name:</strong> {{ $order->select_user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->select_user->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $order->select_user->phone ?? 'N/A' }}</p>
            </td>
            <!-- Order Quantity -->
            <td>
                <p><strong>Quantity:</strong> {{ $order->select_products->sum('pivot.quantity') }}&nbsp;&nbsp;<strong>Order Date: </strong>{{ \Carbon\Carbon::parse($order->placed_at)->format('M d, Y') }}</p>
                <p><strong>Confirm Quantity:</strong> {{ $order->select_products->sum('pivot.confirm_qty') }} <strong>Confirm Date:</strong> {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}</strong></p>
                <p><strong>Pending Quantity:</strong> {{ $order->select_products->sum('pivot.quantity') - $order->select_products->sum('pivot.confirm_qty') }}</strong></p>
            </td>
           
            <!-- Payment -->
            <td>
                <p><strong>Status:</strong> {{ ucfirst($order->payment_status ?? 'N/A') }}</p>
                <p><strong>Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- Product Table -->
    @php $calculatedTotal = 0; @endphp
    <table class="items">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->select_products as $product)
            @php
                $unitPrice = $order->select_user ? $product->price_1 : $product->rate_2;
                $quantity = $product->pivot->quantity;
                $subtotal = $unitPrice * $quantity;
                $calculatedTotal += $subtotal;
            @endphp
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $quantity }}</td>
                <td><i class="fas fa-rupee-sign"></i> {{ number_format($unitPrice, 2) }}</td>
                <td><i class="fas fa-rupee-sign"></i> {{ number_format($subtotal, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Grand Total</strong></td>
            <td><strong><i class="fas fa-rupee-sign"></i> {{ number_format($calculatedTotal, 2) }}</strong></td>
        </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p>For support, contact: marutisuzukiventures@gmail.com</p>
    </div>
    
     <div class="no-print" style="text-align: center; margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; background-color: #ff6600; color: white; border: none; border-radius: 5px; cursor: pointer;">
        🖨️ Print / Download Invoice
    </button>
    <a href="/" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; margin-left: 10px;">
        🏠 Back to Home
    </a>
  

</div>
</div>
</body>
</html>

<!--{{dd($order)}}-->
