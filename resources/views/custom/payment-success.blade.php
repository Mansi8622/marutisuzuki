@extends('layouts.app')

@section('content')


<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>


<!-- Invoice Section -->
<div class="invoice-box mb-5" style="width: 100%; max-width: 900px; margin: auto; background: #ffffff; padding: 5px; border-radius: 12px; border-top: 5px solid #ffcc00;">
    <div class="header" style="background: #ffcc00; padding: 10px; border-radius: 10px; text-align: center; margin-bottom: 10px;">
        <img src="{{ asset('asset/img/logo.webp') }}" alt="Company Logo" style="width: 60px; height: 60px;">
        <h2 style="margin: 0; color: #222;">Payment Receipt</h2>
    </div>

    <!-- Company & Invoice Details -->
    <table class="items" style="width: 100%;  font-size: 14px; margin-bottom: 20px;">
        <thead>
        <tr>
            <th colspan="2" style="background: #fff8e1; color: #222; font-size: 16px;">Company & Invoice Details</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 65%; background: #fff8e1; padding: 10px;">
                <p><strong>Maruti Suzuki Ventures</strong></p>
                <p><strong>GSTIN:</strong> 10ABZFM8479K1ZC</p>
                <p><strong>Phone:</strong> 9263906099</p>
                <p><strong>Email:</strong> marutisuzukiventures@gmail.com</p>
                <p><strong>Address:</strong> 1st Floor Kamla Market, RK Bhattacharya Road, Patna, Bihar-800001</p>
            </td>
            <td style="width: 35%; text-align: right; background: #fff8e1; padding: 10px;">
                <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->placed_at)->format('M d, Y') }}</p>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- Retailer, Quantity, Payment -->
    <table class="items" style="width: 100%; border:1px solid #e06700; border-spacing: 0; font-size: 14px;">
        <thead>
        <tr style="background: #ff6600; color: white; ">
        <th style="padding: 10px; border-right: 1px solid #e06700;">
    @auth('customer')
        Customer Detail
    @endauth

    @auth('web')
        Retailer Detail
    @endauth

</th>

            <th style="padding: 10px; border-right: 1px solid #e06700;">Shipping Detail</th>
            <th style="padding: 10px; border-right: 1px solid #e06700;">Payment Detail</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="padding: 10px; border-right: 1px solid #e06700;">
            @if($order->select_user)
    <p><strong>Name:</strong> {{ $order->select_user->name }}</p>
    <p><strong>Email:</strong> {{ $order->select_user->email }}</p>
    <p><strong>Phone:</strong> {{ $order->select_user->phone }}</p>
@elseif($order->select_customer)
    <p><strong>Name:</strong> {{ $order->select_customer->name }}</p>
    <p><strong>Email:</strong> {{ $order->select_customer->email }}</p>
    <p><strong>Phone:</strong> {{ $order->select_customer->phone }}</p>
@else
    <p>No user/customer info available.</p>
@endif

            </td>
            <td style="padding: 10px; border-right: 1px solid #e06700;">
    <p><strong>Shipping Address:</strong><br> {{ $order->shipping_address ?? 'N/A' }}</p>
    <p><strong>Billing Address:</strong><br> {{ $order->shipping_address ?? 'N/A' }}</p>

</td>

            <td style="padding: 10px; border-right: 1px solid #e06700;">
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'N/A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                <p><strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
            </td>
        </tr>
        </tbody>
    </table>

    @php
    $calculatedTotal = 0;
    $totalQuantity = 0;
@endphp
<table class="items" style="width: 100%; border-spacing: 0; border: 1px solid #e06700;  font-size: 14px; margin-top: 20px; box-shadow: 0 0 8px rgba(0,0,0,0.05);">
    <thead>
        <tr style="background-color: #ff6600; color: #fff; text-align: left;">
            <th style="padding: 12px; border: 1px solid #e06700;">Product</th>
            <th style="padding: 12px; border: 1px solid #e06700;">Quantity</th>
            <th style="padding: 12px; border: 1px solid #e06700;">Unit Price</th>
            <th style="padding: 12px; border: 1px solid #e06700;">Total</th>
        </tr>
    </thead>
    <tbody>
    @foreach($order->select_products as $product)
        @php
            $unitPrice = $order->select_user ? $product->price_1 : $product->rate_2;
            $quantity = $product->pivot->quantity;
            $subtotal = $unitPrice * $quantity;
            $totalQuantity += $quantity;
            $calculatedTotal += $subtotal;
        @endphp
        <tr style="background-color: rgba(195, 12, 12, 0.1);">
            <td style="padding: 10px; border-right: 1px solid #e06700;">
                {{ $product->name }}<br>
                <small>
                    @foreach($product->companies as $company)
                 ({{ $company->company_name }})@if(!$loop->last), @endif
                    @endforeach
                </small>
            </td>
            <td style="padding: 10px; border-right: 1px solid #e06700;">
                {{ $quantity }}
            </td>
            <td style="padding: 10px; border-right: 1px solid #e06700;">
                <i class="fas fa-rupee-sign"></i> {{ number_format($unitPrice, 2) }}
            </td>
            <td style="padding: 10px; border-right: 1px solid #e06700;">
                <i class="fas fa-rupee-sign"></i> {{ number_format($subtotal, 2) }}
            </td>
        </tr>
    @endforeach
    <tr style="background-color: rgb(254, 252, 250);">
        <td colspan="1" style="text-align: right; padding: 12px; font-weight: bold; border-right: 1px solid #e06700; border-top: 1px solid #e06700;">
            Grand Total
        </td>
        <td style="padding: 12px; font-weight: bold; border-right: 1px solid #e06700; border-top: 1px solid #e06700;">
            {{ $totalQuantity }}
        </td>
        <td colspan="2" style="padding: 12px; font-weight: bold; border-right: 1px solid #e06700; text-align: center; border-top: 1px solid #e06700;">
            <i class="fas fa-rupee-sign"></i> {{ number_format($calculatedTotal, 2) }}
        </td>
    </tr>
</tbody>

</table>



    <div class="footer" style="text-align: center; margin-top: 30px; font-size: 12px; color: #555;">
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
<!-- {{dd($order);}} -->

@endsection
