@extends('custom.master')

@section('content')

@php
    // Self-contained inline placeholder (no external dependency) if an image is missing
    $noImagePlaceholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500"><rect width="500" height="500" fill="#F5F7FA"/><g fill="#C7CFD6"><rect x="160" y="175" width="180" height="140" rx="8" fill="none" stroke="#C7CFD6" stroke-width="7"/><circle cx="205" cy="220" r="17"/><path d="M160 295 L225 235 L270 275 L305 245 L340 295 Z"/></g><text x="250" y="355" font-family="Arial, sans-serif" font-size="18" fill="#9AA7B3" text-anchor="middle">No Image</text></svg>');

    $mainImgUrl = optional($products->photo->first())->getUrl() ?? $noImagePlaceholder;
    $img1Url    = optional($products->product_photo_2->first())->getUrl() ?? $noImagePlaceholder;
    $img2Url    = optional($products->product_photo_3)->getUrl() ?? $noImagePlaceholder;

    $finalPrice = $products->sellingPrice();
    $savings    = $products->mrp() - $finalPrice;
@endphp

<style>
/* ============================================================
   Product Detail — Flipkart style layout
   ============================================================ */
:root{
  --pd-blue:      #2874F0;
  --pd-yellow:    #FFD814;
  --pd-yellow-dk: #F7CA00;
  --pd-orange:    #FF9F00;
  --pd-orange-dk: #E88F00;
  --pd-green:     #388E3C;
  --pd-badge:     #26A541;
  --pd-text:      #212121;
  --pd-muted:     #878787;
  --pd-border:    #e6edf3;
}

.pd-section{ background:#fff; }
.pd-card{ border:1px solid var(--pd-border); border-radius:6px; background:#fff; }

/* ---------------- Gallery ---------------- */
.pd-gallery{ display:flex; gap:.75rem; }
.pd-thumbs{
  display:flex; flex-direction:column; gap:.5rem; width:64px; flex:0 0 64px;
}
.pd-thumb{
  width:64px; height:64px; border:1px solid var(--pd-border); border-radius:4px;
  overflow:hidden; cursor:pointer; background:#fff; padding:4px;
  transition:border-color .15s;
}
.pd-thumb img{ width:100%; height:100%; object-fit:contain; }
.pd-thumb.active{ border-color:var(--pd-blue); border-width:2px; }

.pd-main-img-wrap{
  flex:1; border:1px solid var(--pd-border); border-radius:6px; position:relative;
  display:flex; align-items:center; justify-content:center; background:#fff;
  min-height:360px; overflow:hidden;
}
.pd-main-img-wrap img{ width:100%; height:100%; object-fit:contain; padding:1rem; max-height:420px; }

.pd-wish-btn{
  position:absolute; top:12px; right:12px; width:34px; height:34px; border-radius:50%;
  background:#fff; border:1px solid var(--pd-border); display:flex; align-items:center; justify-content:center;
  color:var(--pd-muted); box-shadow:0 1px 4px rgba(16,32,48,.08); z-index:2;
}

.pd-gallery-actions{ display:flex; gap:.75rem; margin-top:1rem; }
.pd-add-cart, .pd-buy-now{
  flex:1; border-radius:4px; font-weight:700; font-size:1rem; padding:.85rem 0;
  text-transform:uppercase; letter-spacing:.02em; border:none;
  display:flex; align-items:center; justify-content:center; gap:.5rem;
}
.pd-add-cart{ background:var(--pd-orange); color:#fff; }
.pd-add-cart:hover{ background:var(--pd-orange-dk); color:#fff; }
.pd-buy-now{ background:var(--pd-yellow); color:var(--pd-text); }
.pd-buy-now:hover{ background:var(--pd-yellow-dk); color:var(--pd-text); }

/* mobile sticky action bar */
.pd-sticky-actions{ display:none; }

/* ---------------- Right column / details ---------------- */
.pd-title{ font-size:1.35rem; font-weight:500; color:var(--pd-text); }

.pd-rating-row{ display:flex; align-items:center; gap:.6rem; margin:.5rem 0 1rem; }
.pd-rating-row .stars{
  background:var(--pd-badge); color:#fff; font-size:.8rem; font-weight:600;
  padding:.15rem .5rem; border-radius:3px; display:inline-flex; align-items:center; gap:.25rem;
}
.pd-rating-row .count{ color:var(--pd-muted); font-size:.85rem; }

.pd-price-block{ margin-bottom:1rem; }
.pd-price-block .savings-tag{
  display:inline-block; background:var(--pd-green); color:#fff; font-size:.72rem;
  font-weight:700; padding:.15rem .5rem; border-radius:3px; margin-bottom:.4rem;
}
.pd-price-row{ display:flex; align-items:baseline; gap:.6rem; flex-wrap:wrap; }
.pd-price-row .cur{ font-size:1.85rem; font-weight:700; color:var(--pd-text); }
.pd-price-row .off{ color:var(--pd-green); font-size:1rem; font-weight:600; }
.pd-price-row del{ color:var(--pd-muted); font-size:1rem; font-weight:400; }

/* ---------------- Offers / expandable info blocks ---------------- */
.pd-info-block{ border-top:1px solid var(--pd-border); padding:.9rem 0; }
.pd-info-block:first-of-type{ border-top:1px solid var(--pd-border); }
.pd-info-block h6{
  font-weight:700; font-size:.92rem; color:var(--pd-text); margin-bottom:.6rem;
  display:flex; align-items:center; justify-content:space-between; cursor:pointer;
}
.pd-info-block h6 i.chev{ color:var(--pd-muted); font-size:.8rem; }

.pd-offer-item{ display:flex; align-items:flex-start; gap:.5rem; font-size:.85rem; margin-bottom:.55rem; }
.pd-offer-item:last-child{ margin-bottom:0; }
.pd-offer-item i{ color:var(--pd-green); margin-top:.2rem; font-size:.8rem; }
.pd-offer-item .apply-link{ color:var(--pd-blue); font-weight:600; font-size:.8rem; }

/* delivery */
.pd-pincode-row{ display:flex; gap:.5rem; max-width:340px; }
.pd-pincode-row input{
  flex:1; border:1px solid var(--pd-border); border-radius:4px; padding:.45rem .6rem; font-size:.85rem;
}
.pd-pincode-row button{
  border:none; background:none; color:var(--pd-blue); font-weight:700; font-size:.85rem;
  padding:.45rem .6rem;
}
.pd-delivery-line{ font-size:.85rem; color:var(--pd-text); margin-top:.6rem; display:flex; gap:.5rem; align-items:flex-start; }
.pd-delivery-line i{ color:var(--pd-muted); margin-top:.2rem; }
.pd-seller-line{ font-size:.85rem; color:var(--pd-text); margin-top:.5rem; }
.pd-seller-line a{ color:var(--pd-blue); font-weight:600; text-decoration:none; }

/* trust badges */
.pd-trust-row{ display:flex; justify-content:space-between; text-align:center; margin-top:1rem; gap:.5rem; flex-wrap:wrap; }
.pd-trust-item{ flex:1; min-width:70px; font-size:.72rem; color:var(--pd-text); }
.pd-trust-item i{ font-size:1.3rem; color:var(--pd-blue); display:block; margin-bottom:.35rem; }

/* specs mini cards (item / HSN) */
.pd-mini-card{ border:1px solid var(--pd-border); border-radius:4px; padding:.6rem 1rem; text-align:center; min-width:110px; }
.pd-mini-card h6{ font-size:.72rem; color:var(--pd-muted); text-transform:uppercase; margin-bottom:.2rem; }
.pd-mini-card p{ font-size:.9rem; font-weight:600; margin:0; color:var(--pd-text); }

/* description card */
.pd-desc-card h5{ font-size:1rem; font-weight:700; margin-top:1rem; }

/* ---------------- Recently viewed ---------------- */
.pd-rv-card{
  border:1px solid var(--pd-border); border-radius:4px; overflow:hidden; background:#fff;
  transition: box-shadow .2s ease, transform .2s ease;
}
.pd-rv-card:hover{ box-shadow:0 8px 20px rgba(16,32,48,.10); transform: translateY(-2px); }
.pd-rv-img-wrap{ background:#fff; height:200px; display:flex; align-items:center; justify-content:center; overflow:hidden; }
.pd-rv-img-wrap img{ width:100%; height:100%; object-fit:contain; padding:.75rem; }
.pd-rv-body{ padding:.85rem; text-align:center; }
.pd-rv-body h5{ font-size:.88rem; color:var(--pd-text); font-weight:500; min-height:2.3em; overflow:hidden; }
.pd-rv-price .cur{ font-weight:700; color:var(--pd-text); }
.pd-rv-price del{ color:var(--pd-muted); font-size:.82rem; margin-left:.35rem; }

/* ---------------- Mobile ---------------- */
@media (max-width: 991.98px){
  .pd-main-img-wrap{ min-height:280px; }
  .pd-gallery{ flex-direction:column-reverse; }
  .pd-thumbs{ flex-direction:row; width:100%; overflow-x:auto; }
  .pd-thumb{ flex:0 0 56px; width:56px; height:56px; }
}

@media (max-width: 767.98px){
  .pd-title{ font-size:1.1rem; }
  .pd-price-row .cur{ font-size:1.4rem; }

  /* hide the inline action buttons under the gallery on mobile, use sticky bar instead */
  .pd-gallery-actions{ display:none; }
  .pd-sticky-actions{
    display:flex; gap:.6rem; position:fixed; bottom:0; left:0; right:0; z-index:1000;
    background:#fff; padding:.55rem .75rem; box-shadow:0 -2px 10px rgba(16,32,48,.12);
  }
  .pd-sticky-actions .pd-add-cart, .pd-sticky-actions .pd-buy-now{ padding:.7rem 0; font-size:.9rem; border-radius:0; }
  /* leave room so content isn't hidden behind the sticky bar */
  .pd-detail-section{ padding-bottom:70px; }
}
</style>

<section class="product-detail pd-section py-3 pd-detail-section">
    <div class="container">
        <div class="row">
            <!-- Product Images Section -->
            <div class="col-lg-6 mb-4">
                <div class="pd-gallery">
                    <div class="pd-thumbs">
                        <div class="pd-thumb active" data-full="{{ $mainImgUrl }}" onclick="pdSwapImage(this)">
                            <img src="{{ $mainImgUrl }}" alt="thumb-main">
                        </div>
                        <div class="pd-thumb" data-full="{{ $img1Url }}" onclick="pdSwapImage(this)">
                            <img src="{{ $img1Url }}" alt="thumb-1">
                        </div>
                        <div class="pd-thumb" data-full="{{ $img2Url }}" onclick="pdSwapImage(this)">
                            <img src="{{ $img2Url }}" alt="thumb-2">
                        </div>
                    </div>

                    <div class="pd-main-img-wrap zoom">
                        <span class="pd-wish-btn"><i class="fa-regular fa-heart"></i></span>
                        <img id="pdMainImage" src="{{ $mainImgUrl }}" alt="{{ $products->name }}"
                             onerror="this.onerror=null;this.src='{{ $noImagePlaceholder }}';">
                    </div>
                </div>

                <!-- Desktop Add to Cart / Buy Now (mirrors the form further down so it works from the gallery too) -->
                <div class="pd-gallery-actions">
                    <button type="submit" form="pdAddToCartForm" class="pd-add-cart">
                        <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                    </button>
                    <button type="submit" form="pdAddToCartForm" name="buy_now" value="1" class="pd-buy-now">
                        Buy Now
                    </button>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="col-lg-6 ps-lg-5">
                <h3 class="pd-title text-capitalize">{{ $products->name }}</h3>

                {{-- Rating badge: only shows if your Product model has rating / reviews_count fields --}}
                @if(isset($products->rating) && $products->rating)
                    <div class="pd-rating-row">
                        <span class="stars">{{ number_format($products->rating,1) }} <i class="fa-solid fa-star"></i></span>
                        @if(isset($products->reviews_count))
                            <span class="count">{{ number_format($products->reviews_count) }} Ratings</span>
                        @endif
                    </div>
                @endif

                <div class="pd-price-block">
                    @if($products->discount)
                        <span class="savings-tag">Save ₹{{ number_format($savings, 0) }}</span>
                    @endif

                    @if (Auth::guard('web')->check())
                        <div class="pd-price-row">
                            <span class="cur">₹{{ number_format($products->price_1, 0) }}</span>
                            <del>MRP ₹{{ number_format($products->mrp(), 0) }}</del>
                        </div>
                    @elseif (Auth::guard('customer')->check())
                        <div class="pd-price-row">
                            <span class="cur">₹{{ number_format($products->rate_2, 0) }}</span>
                            <del>MRP ₹{{ number_format($products->mrp(), 0) }}</del>
                        </div>
                    @else
                        <div class="pd-price-row">
                            <span class="cur">₹{{ number_format($finalPrice, 0) }}</span>
                            @if($products->discount)
                                <span class="off">{{ $products->discount }}% off</span>
                            @endif
                            <del>₹{{ number_format($products->price, 0) }}</del>
                        </div>
                    @endif
                </div>

                {{-- ================= Offers block =================
                     Placeholder marketing copy — replace with your real bank/coupon
                     offers or loop over an $offers collection if you have one. --}}
                <div class="pd-info-block">
                    <h6>Available Offers <i class="fa-solid fa-chevron-down chev"></i></h6>
                    <div class="pd-offer-item">
                        <i class="fa-solid fa-tag"></i>
                        <span>Bank Offer: 5% instant discount on eligible cards <span class="apply-link">T&amp;C</span></span>
                    </div>
                    <div class="pd-offer-item">
                        <i class="fa-solid fa-tag"></i>
                        <span>No cost EMI available on select cards <span class="apply-link">View Plans</span></span>
                    </div>
                    <div class="pd-offer-item">
                        <i class="fa-solid fa-tag"></i>
                        <span>Coupon: Extra 10% off on first order</span>
                    </div>
                </div>

                {{-- ================= Delivery block =================
                     Pincode check is a UI placeholder — wire it to your own
                     serviceability/ETA endpoint. --}}
                <div class="pd-info-block">
                    <h6>Delivery Details</h6>
                    <div class="pd-pincode-row">
                        <input type="text" id="pdPincode" maxlength="6" placeholder="Enter delivery pincode">
                        <button type="button" onclick="pdCheckPincode()">Check</button>
                    </div>
                    <div id="pdDeliveryResult" class="pd-delivery-line" style="display:none;">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Delivery available. Usually ships in 2–5 business days.</span>
                    </div>

                    @if($products->select_companies->isNotEmpty())
                        <div class="pd-seller-line">
                            <i class="fa-solid fa-store text-muted me-1"></i>
                            Sold by:
                            @foreach($products->select_companies as $company)
                                <a href="#">{{ $company->company_name }}</a>@if(!$loop->last), @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ================= Trust badges =================
                     Generic policy icons — edit the text to match your actual
                     warranty / return / payment policies. --}}
                <div class="pd-trust-row">
                    <div class="pd-trust-item"><i class="fa-solid fa-rotate-left"></i>7 Day<br>Replacement</div>
                    <div class="pd-trust-item"><i class="fa-solid fa-shield-halved"></i>Warranty<br>Assured</div>
                    <div class="pd-trust-item"><i class="fa-solid fa-money-bill-wave"></i>Cash on<br>Delivery</div>
                    <div class="pd-trust-item"><i class="fa-solid fa-certificate"></i>Genuine<br>Product</div>
                </div>

                <!-- Item / HSN specs -->
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <div class="pd-mini-card">
                        <h6>Item</h6>
                        <p>{{ $products->item_code }}</p>
                    </div>
                    <div class="pd-mini-card">
                        <h6>HSN</h6>
                        <p>{{ $products->hsn_code }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="pd-card pd-desc-card px-3 py-3 mt-3">
                    <h5>How it Works</h5>
                    <p class="mb-0">{{ $products->description }}</p>
                </div>

                <!-- Hidden form used by both the gallery buttons and (on mobile) the sticky bar -->
                <form id="pdAddToCartForm" action="{{ route('cart.add') }}" method="POST" class="d-none">
                    @csrf
                    <input type="hidden" name="id" value="{{ $products->id }}">
                    <input type="hidden" name="name" value="{{ $products->name }}">
                    <input type="hidden" name="price" value="{{ $products->price }}">
                    <input type="hidden" name="discount" value="{{ $products->discount }}">
                    <input type="hidden" name="price_1" value="{{ $products->price_1 }}">
                    <input type="hidden" name="rate_2" value="{{ $products->rate_2 }}">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="description" value="{{ $products->description }}">
                    <input type="hidden" name="photo" value="{{ $mainImgUrl }}">
                </form>

                <!-- Add to Favorites stays as its own separate action -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button onclick="addToWishlist({{ $products->id }})" class="btn w-100 py-2"
                                style="border: 1px solid #50c7ee; color: #50c7ee; background: none !important; border-radius:4px;">
                            <i class="fa-regular fa-heart me-1"></i> Add To Favorites
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile sticky Add to Cart / Buy Now bar -->
<div class="pd-sticky-actions">
    <button type="submit" form="pdAddToCartForm" class="pd-add-cart">
        <i class="fa-solid fa-cart-shopping"></i> Add to Cart
    </button>
    <button type="submit" form="pdAddToCartForm" name="buy_now" value="1" class="pd-buy-now">
        Buy Now
    </button>
</div>

<!-- Recently Viewed Products -->
<section class="section-2 py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Recently Viewed</h2>
            <a href="/product" class="decoration fw-bold text-danger">View All</a>
        </div>

        <div class="row">
            @foreach($productss as $product)
            @php
                $rvFinal = $product->price - ($product->price * $product->discount / 100);
            @endphp
            <div class="col-6 col-md-4 col-lg-4 mb-3">
                <div class="pd-rv-card h-100">
                    <a href="{{ url('product-detail', $product->id) }}" class="decoration">
                        <div class="pd-rv-img-wrap">
                            <img src="{{ $product->photo->first()?->getUrl() ?? $noImagePlaceholder }}"
                                 alt="{{ $product->name }}"
                                 onerror="this.onerror=null;this.src='{{ $noImagePlaceholder }}';">
                        </div>
                        <div class="pd-rv-body">
                            <h5>{{ $product->name }}</h5>
                            <p class="pd-rv-price mb-0">
                                @if (Auth::guard('web')->check())
                                    <span class="cur">₹{{ number_format($rvFinal,0) }}</span>
                                    <del>₹{{ number_format($product->price,0) }}</del>
                                @elseif (Auth::guard('customer')->check())
                                    <span class="cur">₹{{ number_format($product->rate_2 ?? 0,0) }}</span>
                                    <del>₹{{ number_format($product->price,0) }}</del>
                                @else
                                    <span class="cur">₹{{ number_format($rvFinal,0) }}</span>
                                    <del>₹{{ number_format($product->price,0) }}</del>
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script>
function pdSwapImage(el){
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('pdMainImage').src = el.getAttribute('data-full');
}

// Placeholder pincode check — replace with a real serviceability API call
function pdCheckPincode(){
    const val = document.getElementById('pdPincode').value.trim();
    const result = document.getElementById('pdDeliveryResult');
    if(val.length === 6 && /^[0-9]+$/.test(val)){
        result.style.display = 'flex';
    } else {
        alert('Please enter a valid 6-digit pincode.');
    }
}

function addToWishlist(productId) {
    if (!productId) {
        alert('Invalid product.');
        return;
    }

    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    fetch(`/add-to-wishlist/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.login_required) {
            alert('You need to log in first.');
            window.location.href = '/login';
        } else if (data.error) {
            alert(data.error);
        } else {
            alert(data.success || 'Product added to wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

@endsection
