@extends('custom.master')

@section('content')
@php
  $cartLines = collect(session('cart', []));
  $cartMrp = $cartLines->sum(fn($i) => (float)($i['price'] ?? 0) * (int)($i['quantity'] ?? 1));
  $cartPayable = $cartLines->sum(fn($i) => (float)($i['final_price'] ?? $i['price_1'] ?? $i['price'] ?? 0) * (int)($i['quantity'] ?? 1));
@endphp
<style>
.cart-page{background:linear-gradient(135deg,#f7fbff,#f1f7f5);border-radius:24px;padding:28px}.cart-item{border:1px solid #e4ecf3!important;border-radius:18px!important;box-shadow:0 8px 22px rgba(34,65,91,.06);transition:.2s}.cart-item:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(34,65,91,.11)}.cart-item-image{width:150px;height:135px;object-fit:contain;background:#f8fafc;border-radius:14px;padding:10px}.item-code{display:inline-block;background:#e9f3ff;color:#21629a;border-radius:20px;padding:4px 10px;font-size:12px;font-weight:700;letter-spacing:.04em}.qty-control{width:145px;border:1px solid #d9e5ef;border-radius:10px;overflow:hidden}.qty-control button{border:0;background:#eef6fc;color:#1d649c;width:38px;font-weight:800}.qty-control input{border:0;box-shadow:none}.line-total{background:#f0fbf5;border:1px solid #ccefdc;border-radius:12px;padding:11px 14px;color:#176b45}.cart-summary{border:0!important;border-radius:18px!important;background:linear-gradient(135deg,#163350,#244f77)!important;color:#fff;box-shadow:0 16px 35px rgba(18,43,70,.18)}.cart-summary .rowline{display:flex;justify-content:space-between;padding:10px 0;color:#d9e6f4}.cart-summary .payable{border-top:1px solid #ffffff2b;margin-top:7px;padding-top:16px;color:#fff;font-size:1.15rem;font-weight:800}.cart-summary .saved{color:#82f0bf;font-size:.9rem}.cart-checkout{background:#ef6c3c!important;border:0;border-radius:10px;padding:12px 22px;font-weight:700;box-shadow:0 8px 18px #ef6c3c44}@media(max-width:576px){.cart-page{padding:15px}.cart-item-image{width:100%;height:160px}}
</style>
<section class="dashboard py-5">
    <div class="container">
        <div class="row">
            @include('custom.sidebar')

            <div class="col-lg-9 mb-3 cart-page">
                <div class="row">
                    <div class="col-6">
                        <h1>My Cart
                            <span style="font-size: 14px;">
                                ({{ session('cart') ? count(session('cart')) : 0 }} items)
                            </span>
                        </h1>
                    </div>
                    <div class="col-6 text-end">
                        <a href="/product" class="decoration">
                            <button class="btn primary-bg text-white">
                                <i class="fa-solid fa-plus"></i> Add Product
                            </button>
                        </a>
                    </div>
                </div>

                <div class="card mt-3" style="border: 1px solid #FFCDAD;">
                    <div class="card-body">
                        @if(session('cart') && count(session('cart')) > 0)
                            @foreach(session('cart') as $item)
                                @php
                                    $unitPrice = (float) ($item['final_price'] ?? $item['price_1'] ?? $item['price'] ?? 0);
                                @endphp
                                <div class="card mb-3 px-3 py-3 cart-item" data-id="{{ $item['cart_key'] ?? $item['id'] }}" data-price="{{ $unitPrice }}">
                                    <div class="row align-items-center">
                                        <div class="col-lg-3 mb-3 text-center">
                                            <img src="{{ $item['photo'] ?? asset('default.png') }}" alt="{{ $item['name'] }}" class="cart-item-image">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <h4 class="mb-2">{{ $item['name'] }}</h4>@include('custom.partials.selection', ['selection' => $item])
                                            <span class="item-code">ITEM CODE: {{ $item['item_code'] ?? 'N/A' }}</span>
                                            <p style="color: #828282; font-size: 14px;">{{ $item['description'] }}</p>

                                            @php
                                                $discount = $item['discount'] ?? 0;
                                                $discountedPrice = $item['final_price'] ?? ($item['price'] - ($item['price'] * $discount / 100));
                                            @endphp

                                            <p style="font-size: 18px; font-weight: 600;">
                                                <del class="text-muted">MRP :- ₹{{ $item['price'] }}</del>
                                            </p>

                                            <b><p class="text-success">PRICE :- ₹ {{ number_format($unitPrice, 2) }}</p></b>

                                            {{-- Quantity Control --}}
                                            <div class="d-flex qty-control">
                                                <button type="button" class="change-quantity-btn" data-id="{{ $item['cart_key'] ?? $item['id'] }}" data-action="decrease">−</button>
                                                <input type="text" class="quantity-input form-control text-center" name="quantity" value="{{ $item['quantity'] }}" data-id="{{ $item['cart_key'] ?? $item['id'] }}">
                                                <button type="button" class="change-quantity-btn" data-id="{{ $item['cart_key'] ?? $item['id'] }}" data-action="increase">+</button>
                                            </div>

                                            {{-- Remove Item --}}
                                            <div class="text-end mt-2">
                                                <form action="{{ route('cart.delete') }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $item['cart_key'] ?? $item['id'] }}">
                                                    <button class="btn primary-bg text-white">
                                                        <i class="fa-solid fa-trash-can"></i> Remove
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="col-lg-3 mb-3"><div class="line-total"><small class="d-block text-muted">Item total</small><strong class="line-total-value">₹{{ number_format($unitPrice * $item['quantity'],2) }}</strong><small class="d-block mt-1">{{ $item['quantity'] }} × ₹{{ number_format($unitPrice,2) }}</small></div></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Your cart is empty!</p>
                        @endif
                    </div>
                </div>

                @if($cartLines->isNotEmpty())
                <div class="card cart-summary mt-3"><div class="card-body"><h5 class="fw-bold mb-3">Cart summary</h5><div class="rowline"><span>Total MRP</span><span id="cart-mrp-total">₹{{ number_format($cartMrp,2) }}</span></div><div class="rowline"><span>You save</span><span class="saved" id="cart-saving-total">₹{{ number_format(max(0,$cartMrp-$cartPayable),2) }}</span></div><div class="rowline payable"><span>All items total</span><span id="cart-payable-total">₹{{ number_format($cartPayable,2) }}</span></div></div></div>
                @endif

                {{-- Place Order --}}
                <div class="row mt-3">
                    <div class="col-lg-12 text-end">
                        <a href="/delivery" class="btn cart-checkout text-white">
                            <span class="text-white">Place Order</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- SweetAlert + Quantity Ajax --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isWebLoggedIn = @json(Auth::guard('web')->check());
    const isCustomerLoggedIn = @json(Auth::guard('customer')->check());

    // Check if any guard is logged in (either web or customer)
    const isLoggedIn = isWebLoggedIn || isCustomerLoggedIn;

    // 🛒 Increase/Decrease Button
    document.querySelectorAll('.change-quantity-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (!isLoggedIn) {
                showLoginPopup();
                return false;
            }

            const productId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            const quantityInput = document.querySelector(`input[data-id="${productId}"]`);
            let quantity = parseInt(quantityInput.value);

            if (action === 'increase') {
                quantityInput.value = quantity + 1;
                updateQuantityInCart(productId, quantity + 1);
            } else if (action === 'decrease' && quantity > 1) {
                quantityInput.value = quantity - 1;
                updateQuantityInCart(productId, quantity - 1);
            }
        });
    });

    // 📝 Manual Quantity Change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            if (!isLoggedIn) {
                showLoginPopup();
                return false;
            }

            const productId = this.getAttribute('data-id');
            let newQuantity = parseInt(this.value);

            if (isNaN(newQuantity) || newQuantity <= 0) {
                Swal.fire('Invalid Quantity', 'Please enter a valid quantity greater than 0.', 'warning');
                this.value = 1;
                return;
            }

            updateQuantityInCart(productId, newQuantity);
        });
    });

    // 🗑️ Remove Item
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!isLoggedIn) {
                e.preventDefault();
                showLoginPopup();
                return false;
            }
        });
    });

    // 🧠 Function: Update Quantity
    function updateQuantityInCart(productId, newQuantity) {
        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: productId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector('.cart-item[data-id="' + productId + '"]');
                card.querySelector('.line-total-value').textContent = '₹' + Number(data.line_total).toFixed(2);
                card.querySelector('.line-total small:last-child').textContent = data.quantity + ' × ₹' + Number(card.dataset.price).toFixed(2);
                document.getElementById('cart-mrp-total').textContent = '₹' + Number(data.mrp_total).toFixed(2);
                document.getElementById('cart-payable-total').textContent = '₹' + Number(data.payable_total).toFixed(2);
                document.getElementById('cart-saving-total').textContent = '₹' + Math.max(0, Number(data.mrp_total) - Number(data.payable_total)).toFixed(2);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Something went wrong.', 'error');
        });
    }

    // 🧠 Function: Show Login Popup
    function showLoginPopup() {
        Swal.fire({
            icon: 'info',
            title: 'Login Required',
            text: 'Please log in before making a purchase.',
            confirmButtonText: 'Log In'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        });
    }
});

</script>
@endsection
