@extends('custom.master')

@section('content')
@php
  $cartLines = collect(session('cart', []));
  $cartMrp = $cartLines->sum(fn($i) => (float)($i['price'] ?? 0) * (int)($i['quantity'] ?? 1));
  $cartPayable = $cartLines->sum(fn($i) => (float)($i['final_price'] ?? $i['price_1'] ?? $i['price'] ?? 0) * (int)($i['quantity'] ?? 1));
@endphp
<style>.cart-summary{border:0!important;border-radius:14px!important;background:#172b49!important;color:#fff;box-shadow:0 12px 26px rgba(18,34,56,.15)}.cart-summary .rowline{display:flex;justify-content:space-between;padding:9px 0;color:#c8d5e7}.cart-summary .payable{border-top:1px solid #ffffff2b;margin-top:7px;padding-top:14px;color:#fff;font-size:1.05rem;font-weight:800}.cart-summary .saved{color:#6ee7b7;font-size:.82rem}</style>
<section class="dashboard py-5">
    <div class="container">
        <div class="row">
            @include('custom.sidebar')

            <div class="col-lg-9 mb-3">
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
                                <div class="card mb-3 px-3 py-2">
                                    <div class="row">
                                        <div class="col-lg-4 mb-3 text-center">
                                            <img src="{{ $item['photo'] ?? asset('default.png') }}" alt="" style="width: 100%">
                                        </div>
                                        <div class="col-lg-8 mb-3">
                                            <h3>{{ $item['name'] }}</h3>
                                            <p style="color: #828282; font-size: 14px;">{{ $item['description'] }}</p>

                                            @php
                                                $discount = $item['discount'] ?? 0;
                                                $discountedPrice = $item['final_price'] ?? ($item['price'] - ($item['price'] * $discount / 100));
                                            @endphp

                                            <p style="font-size: 18px; font-weight: 600;">
                                                <del class="text-muted">MRP :- ₹{{ $item['price'] }}</del>
                                            </p>

                                            <b><p class="text-success">PRICE :- ₹ {{ number_format($discountedPrice, 2) }}</p></b>

                                            {{-- Quantity Control --}}
                                            <div class="d-flex">
                                                <button type="button" class="change-quantity-btn input-group-text rounded-0" data-id="{{ $item['id'] }}" data-action="decrease">-</button>
                                                <input type="text" class="quantity-input form-control text-center" name="quantity" value="{{ $item['quantity'] }}" data-id="{{ $item['id'] }}">
                                                <button type="button" class="change-quantity-btn input-group-text rounded-0" data-id="{{ $item['id'] }}" data-action="increase">+</button>
                                            </div>

                                            {{-- Remove Item --}}
                                            <div class="text-end mt-2">
                                                <form action="{{ route('cart.delete') }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                    <button class="btn primary-bg text-white">
                                                        <i class="fa-solid fa-trash-can"></i> Remove
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Your cart is empty!</p>
                        @endif
                    </div>
                </div>

                @if($cartLines->isNotEmpty())
                <div class="card cart-summary mt-3"><div class="card-body"><h5 class="fw-bold mb-3">Cart summary</h5><div class="rowline"><span>Total MRP</span><span>₹{{ number_format($cartMrp,2) }}</span></div><div class="rowline"><span>You save</span><span class="saved">₹{{ number_format(max(0,$cartMrp-$cartPayable),2) }}</span></div><div class="rowline payable"><span>Total payable</span><span>₹{{ number_format($cartPayable,2) }}</span></div></div></div>
                @endif

                {{-- Place Order --}}
                <div class="row mt-3">
                    <div class="col-lg-12 text-end">
                        <a href="/delivery" class="decoration text-center primary-bg px-3 py-2">
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
                console.log('Quantity updated');
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
