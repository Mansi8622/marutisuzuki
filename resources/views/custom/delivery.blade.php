@extends('custom.master')

@section('content')
<section class="dashboard py-5">
    <div class="container">
        <div class="row">
         @include('custom.sidebar')

            <!-- Main Content -->
            <div class="col-lg-9 mb-3">
                <h1>Select Delivery Method</h1>
                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                <div class="row mt-4">
                    <!-- Left Section: Delivery Form -->
                    <div class="col-lg-8">
                        <div class="card px-3">
                            <div class="d-flex justify-content-between align-items-center px-3 py-3">
                                <h5>Transport Delivery</h5>
                                <button class="btn" style="border: 1px solid #E82600" data-bs-toggle="modal" data-bs-target="#addressModal">Add / Update Address</button>
                                <!-- Address Modal -->
                                <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addressModalLabel">Add New Address</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('custom.delivery.store') }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="country" class="form-label">Country</label>
                                                        <input type="text" name="country" class="form-control" placeholder="Enter your country" value="{{ old('country', $user->address->country ?? '') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="state" class="form-label">State</label>
                                                        <input type="text" name="state" class="form-control" placeholder="Enter your state" value="{{ old('state', $user->address->state ?? '') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="district" class="form-label">District</label>
                                                        <input type="text" name="district" class="form-control" placeholder="Enter your district" value="{{ old('district', $user->address->district ?? '') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="full_address" class="form-label">Full Address</label>
                                                        <textarea name="full_address" class="form-control" placeholder="Enter full address" required>{{ old('full_address', $user->address->full_address ?? '') }}</textarea>
                                                    </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="pin_code" class="form-label">Pin Code</label>
                                                        <input name="pin_code" class="form-control" placeholder="Enter pin code" required value="{{ old('pin_code', $user->address->pin_code ?? '') }}">
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Address</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3>Contact Informations</h3>
                            <form action="{{ route('custom.delivery') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email ?? '' }}" readonly required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ $user->name ?? '' }}" readonly required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" value="{{ $user->phone ?? '' }}" readonly required>
                                </div>

                                <h3>Shipping Address</h3>
                                <div class="mb-3">
                                    <input type="text" name="country" class="form-control" placeholder="Enter your country" value="{{ old('country', $user->address ? $user->address->country : '') }}" readonly required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="state" class="form-control" placeholder="Enter your state" value="{{ old('state', $user->address ? $user->address->state : '') }}" readonly required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="district" class="form-control" placeholder="Enter your district" value="{{ old('district', $user->address ? $user->address->district : '') }}" readonly required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="full_address" class="form-control" placeholder="Enter full address" required readonly value="{{ old('full_address', $user->address ? $user->address->full_address : '') }}">
                                </div>
                               
                                <div class="mb-3">
                                    <input type="text" name="pin_code" class="form-control" placeholder="Enter pin code" required readonly value="{{ old('pin_code', $user->address ? $user->address->pin_code : '') }}">
                                </div>
                            </form>
                        </div>
                

                                        <!-- Right Section: Order Summary -->
                                        @php
    use App\Models\OurStock;

    $subtotal = 0;
    $totalDiscount = 0;
    $totalGstAmount = 0;
    $finalTotal = 0;
    $stockIssue = false;

    $isCustomer = Auth::guard('customer')->check();
    $isWebUser = Auth::guard('web')->check();

    foreach ($cartItems as $item) {
        $originalPrice = $item['price'];
        $price1 = $item['price_1'];
        $price2 = $item['rate_2'];
        $quantity = $item['quantity'];

        if ($isCustomer) {
            $finalPriceBeforeGst = $price2;
        } elseif ($isWebUser) {
            $finalPriceBeforeGst = $price1;
        } else {
            $finalPriceBeforeGst = $originalPrice;
            $discountAmount = $originalPrice - $price1;
            $totalDiscount += $discountAmount * $quantity;
        }

        $gstAmount = ($finalPriceBeforeGst * $item['gst']) / 100;
        $totalGstAmount += $gstAmount * $quantity;
        $finalTotal += ($finalPriceBeforeGst + $gstAmount) * $quantity;
        $subtotal += $originalPrice * $quantity;

        $stock = OurStock::where('select_product_id', $item['id'])->first();
        if ($stock && $stock->quantity_available < $item['quantity']) {
            $stockIssue = true;
        }
    }

    $deliveryFee = $orderSummary['deliveryFee'] ?? 0;
    if ($deliveryFee > 0) {
        $finalTotal += $deliveryFee;
    }
    $creditAvailable = (float) ($wallet->welcome_amount ?? 0);
    $creditDue = (float) ($wallet->due ?? 0);
    $creditIsUsable = $isWebUser && $wallet && $wallet->status === 'Active' && $creditAvailable >= $finalTotal;
@endphp

<div class="col-lg-4">
    <div class="card px-3 py-3 mb-3">
        <h4>Your Order ({{ count($cartItems) }} items)</h4>

        @foreach ($cartItems as $item)
            @php
                $stock = OurStock::where('select_product_id', $item['id'])->first();
                $isOutOfStock = $stock && $stock->quantity_available < $item['quantity'];
            @endphp

            <div class="d-flex justify-content-between align-items-center">
                <p>{{ $item['name'] }}</p>
                <p>{{ $item['gst'] }}%</p>
            </div>

            @if ($stock)
                <div class="text-muted mb-2" style="margin-left: 10px;">
                    Stock Available: {{ $stock->quantity_available }}
                    @if ($isOutOfStock)
                        <span class="text-danger"> (Only {{ $stock->quantity_available }} available)</span>
                    @endif
                </div>
            @else
                <div class="text-danger mb-2" style="margin-left: 10px;">
                    Stock info not found
                </div>
            @endif
        @endforeach

        @if ($stockIssue)
            <div class="alert alert-danger text-center">
                One or more items have insufficient stock. Please adjust your cart.
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center">
            <p>MRP Total</p>
            <p>₹ {{ number_format($subtotal, 2) }}</p>
        </div>

        @if (!$isWebUser)
            <div class="d-flex justify-content-between align-items-center">
                <p>Product Discount</p>
                <p>- ₹ {{ number_format($totalDiscount, 2) }}</p>
            </div>
        @endif

        @if ($isWebUser)
            <div class="d-flex justify-content-between align-items-center">
                <p><strong>Customer Price</strong></p>
                <p class="text-primary fw-bold">₹ {{ number_format($orderSummary['price_1'], 2) }}</p>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center">
            <p>Delivery Fee</p>
            <p>{{ $deliveryFee == 0 ? 'Free' : '₹ ' . number_format($deliveryFee, 2) }}</p>
        </div>

        <div class="d-flex justify-content-between align-items-center fw-bold">
            <p>Total GST</p>
            <p>₹ {{ number_format($totalGstAmount, 2) }}</p>
        </div>

        <div class="d-flex justify-content-between align-items-center fw-bold">
            <p>Total (Including Tax)</p>
            <p>₹ {{ number_format($finalTotal, 2) }}</p>
        </div>

        <input type="hidden" id="stock_issue" value="{{ $stockIssue ? '1' : '0' }}">
        <input type="hidden" name="total_amount" value="{{ $finalTotal }}">
    </div>

    <!-- Payment Methods -->
    <div class="card px-3 py-3 mb-3">
        <h4>Payment Method</h4>

        @if (Auth::check() && Auth::guard('web')->user())
            <div class="card p-2 my-3 text-center" style="background:#edf3ff; border:1px solid #cddcff; color:#1f4388;">
                <strong>Credit Line</strong>
                <small class="d-block mt-2">Available: ₹ {{ number_format($creditAvailable, 2) }} · Due: ₹ {{ number_format($creditDue, 2) }}</small>
                <p class="mt-3">₹ {{ $wallet->welcome_amount ?? '' }}</p>
            </div>
        @endif

        <!-- Credit Line Payment -->
        <form action="{{ route('frontend.order.store') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
            <input type="hidden" name="name" value="{{ $user->name ?? '' }}">
            <input type="hidden" name="phone" value="{{ $user->phone ?? '' }}">
            <input type="hidden" name="country" value="{{ $user->address->country ?? '' }}">
            <input type="hidden" name="state" value="{{ $user->address->state ?? '' }}">
            <input type="hidden" name="district" value="{{ $user->address->district ?? '' }}">
            <input type="hidden" name="full_address" value="{{ $user->address->full_address ?? '' }}">
            <input type="hidden" name="products[]" value="{{ json_encode($cartItems) }}">

            @foreach($cartItems as $item)
                <input type="hidden" name="product[]" value="{{ $item['id'] }}">
                <input type="hidden" name="product_quantity[]" value="{{ $item['quantity']}}">
            @endforeach

            <input type="hidden" name="total_amount" value="{{ $finalTotal }}">
            <input type="hidden" name="payment_method" value="Credit Line">

            @if ($creditIsUsable && !$stockIssue)
                <button type="submit" id="creditLineBtn" class="btn primary-bg text-white w-100">Pay To Credit Line</button>
            @else
                <button type="button" class="btn primary-bg text-white w-100" disabled>Credit Line Unavailable</button>
            @endif
        </form>

        <!-- Razorpay Payment -->
        <form id="payment-form" action="{{ route('frontend.customer.payment.process') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
            <input type="hidden" name="name" value="{{ $user->name ?? '' }}">
            <input type="hidden" name="phone" value="{{ $user->phone ?? '' }}">
            <input type="hidden" name="country" value="{{ $user->address->country ?? '' }}">
            <input type="hidden" name="state" value="{{ $user->address->state ?? '' }}">
            <input type="hidden" name="district" value="{{ $user->address->district ?? '' }}">
            <input type="hidden" name="full_address" value="{{ ($user->address->full_address ?? '') . ', ' . ($user->address->district ?? '') . ', ' . ($user->address->state ?? '') . ', ' . ($user->address->country ?? '') }}">
            <input type="hidden" name="products" value="{{ json_encode($cartItems) }}">

            @foreach($cartItems as $item)
                <input type="hidden" name="product_ids[]" value="{{ $item['id'] }}">
                <input type="hidden" name="product_quantities[]" value="{{ $item['quantity'] }}">
            @endforeach

            <input type="hidden" name="total_amount" value="{{ $finalTotal }}">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">

            <button type="button" id="rzp-button" class="btn primary-bg text-white w-100">
                Pay ₹{{ number_format($finalTotal, 2) }} with Razorpay
            </button>
        </form>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let stockIssue = document.getElementById('stock_issue').value === '1';

        if (stockIssue) {
            alert("One or more items have insufficient stock. Please adjust your cart before proceeding.");
            document.getElementById('rzp-button')?.setAttribute('disabled', 'disabled');
            document.getElementById('creditLineBtn')?.setAttribute('disabled', 'disabled');
        }

        document.getElementById('rzp-button')?.addEventListener('click', function (e) {
            e.preventDefault();
            if (stockIssue) return;

            var options = {
"key": "{{ config('services.razorpay.key') }}", // ✅ CORRECT
                "amount": "{{ $finalTotal * 100 }}",
                "currency": "INR",
                "name": "{{ config('app.name') }}",
                "description": "Payment for Order",
                "image": "{{ asset('path/to/your/logo.png') }}",
                "handler": function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('payment-form').submit();
                },
                "prefill": {
                    "name": "{{ $user->name ?? '' }}",
                    "email": "{{ $user->email ?? '' }}",
                    "contact": "{{ $user->phone ?? '' }}"
                },
                "theme": {
                    "color": "#F37254"
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>




                            </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
