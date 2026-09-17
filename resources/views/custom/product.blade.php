@extends('custom.master')

@section('content')

@php
    use App\Models\ProductCategory;
    $categories = ProductCategory::orderBy('name')->get(); // Sorted alphabetically

    // Self-contained inline placeholder (no external dependency) shown when a product has no photo
    $noImagePlaceholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400"><rect width="400" height="400" fill="#F5F7FA"/><g fill="#C7CFD6"><rect x="130" y="140" width="140" height="110" rx="6" fill="none" stroke="#C7CFD6" stroke-width="6"/><circle cx="165" cy="175" r="14"/><path d="M130 235 L180 190 L215 220 L245 195 L270 235 Z"/></g><text x="200" y="285" font-family="Arial, sans-serif" font-size="16" fill="#9AA7B3" text-anchor="middle">No Image</text></svg>');
@endphp

<style>
/* ============================================================
   Product listing — Flipkart / Amazon style product card
   ============================================================ */
:root{
  --navy:        #0B1622;
  --navy-2:      #101F30;
  --navy-3:      #16283C;
  --line:        rgba(111,168,220,0.14);
  --blueprint:   #2F6FA8;
  --orange:      #FF5A1F;
  --orange-dk:   #D9450F;
  --paper:       #EBF1F6;
  --steel:       #6b7d8f;

  /* Flipkart / Amazon accent colors for cart actions */
  --fk-yellow:   #FFD814;   /* Amazon-style Add to Cart */
  --fk-yellow-dk:#F7CA00;
  --fk-orange:   #FF9F00;   /* Flipkart-style Buy Now */
  --fk-orange-dk:#E88F00;
  --fk-green:    #388E3C;   /* rating badge green */
  --fk-badge:    #26A541;
}

@media (prefers-reduced-motion: reduce){
  *{ animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important; }
}

.fit-frame{ position:relative; }
.fit-frame::before,
.fit-frame::after{
  content:""; position:absolute; width:14px; height:14px;
  border:2px solid var(--orange); opacity:0;
  transition:opacity .2s ease, transform .2s ease; pointer-events:none; z-index:2;
}
.fit-frame::before{ top:-7px; left:-7px; border-right:0; border-bottom:0; transform:scale(.6); }
.fit-frame::after{ bottom:-7px; right:-7px; border-left:0; border-top:0; transform:scale(.6); }
.fit-frame:hover::before, .fit-frame:hover::after,
.fit-frame:focus-within::before, .fit-frame:focus-within::after{ opacity:1; transform:scale(1); }

/* breadcrumb */
.msv-breadcrumb{
  font-family:'IBM Plex Mono', monospace; font-size:.82rem;
  color: var(--steel); letter-spacing:.02em;
}
.msv-breadcrumb a.primary{ color: var(--blueprint); text-decoration:none; font-weight:600; }
.msv-breadcrumb a.primary:hover{ color: var(--orange-dk); }
.msv-breadcrumb i{ font-size:.7rem; color: var(--steel); }

/* section heading, blueprint-drawing style label */
.msv-heading{
  font-family:'Barlow Condensed', sans-serif; font-weight:800; text-transform:uppercase;
  letter-spacing:.03em; color:#16283C; position:relative; padding-bottom:.5rem;
  display:inline-block;
}
.msv-heading::after{
  content:""; position:absolute; left:0; bottom:0; height:3px; width:56px; background:var(--orange);
}

/* filter card */
.msv-filter-card{
  background:#fff; border:1px solid #e1e8ef; border-radius:4px;
  box-shadow:0 2px 10px rgba(16,32,48,.04);
}
.msv-filter-card h4{
  font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase;
  letter-spacing:.03em; font-size:1.05rem; color:#16283C; margin-bottom:.9rem;
  display:flex; align-items:center; gap:.5rem;
}
.msv-filter-card h4 i{ color:var(--orange); }
.progress{ height:8px; background:#e6edf3; border-radius:2px; }
.progress-bar{ background:var(--orange); }
.msv-btn{
  background:var(--orange); border:none; color:#fff; font-weight:600;
  font-family:'Inter'; border-radius:2px; letter-spacing:.02em;
  transition: background .15s, transform .15s;
}
.msv-btn:hover{ background:var(--orange-dk); color:#fff; transform:translateY(-1px); }

.msv-cat-list li{ border-bottom:1px dashed #e1e8ef; }
.msv-cat-list li:last-child{ border-bottom:0; }
.msv-cat-list .dropdown-item{
  padding:.6rem .3rem; font-size:.92rem; color:#26333f !important;
  display:flex; align-items:center; border-radius:2px; transition:color .15s, padding-left .15s;
}
.msv-cat-list .dropdown-item:hover{ background:transparent; color:var(--orange-dk) !important; padding-left:.5rem; }
.msv-cat-list .dropdown-item i.fallback{ color:var(--orange); width:25px; text-align:center; }

/* ---------------------------------------------------------
   Product card — Flipkart / Amazon look
--------------------------------------------------------- */
.msv-product-card{
  border:1px solid #e6edf3; border-radius:4px; overflow:hidden;
  background:#fff;
  transition: box-shadow .2s ease, transform .2s ease;
  opacity:0; transform: translateY(14px);
  animation: cardIn .5s ease forwards;
}
@keyframes cardIn{ to{ opacity:1; transform:translateY(0); } }
.msv-product-card:hover{ box-shadow:0 8px 20px rgba(16,32,48,.10); transform: translateY(-2px); }

.msv-img-wrap{ position:relative; overflow:hidden; background:#f5f7fa; }
.msv-img-wrap img{
  width:100%; height:220px; object-fit:contain; display:block;
  padding:.75rem; transition: transform .3s ease;
  background:#fff; color:transparent; font-size:0; /* hide alt text if image fails before JS fallback kicks in */
}
.msv-product-card:hover .msv-img-wrap img{ transform: scale(1.04); }

/* wishlist heart, top-right, like Flipkart/Amazon listing */
.msv-wish{
  position:absolute; top:8px; right:8px; z-index:3;
  width:28px; height:28px; border-radius:50%; background:#fff;
  border:1px solid #e6edf3; display:flex; align-items:center; justify-content:center;
  color:#9aa7b3; font-size:.85rem; box-shadow:0 1px 4px rgba(16,32,48,.08);
}

.msv-tag{
  position:absolute; top:10px; left:10px; z-index:2;
  background:var(--orange) !important; color:#fff !important;
  font-family:'IBM Plex Mono', monospace; font-size:.65rem; font-weight:600;
  letter-spacing:.03em; text-transform:uppercase;
  padding:.28rem .5rem !important; border-radius:2px;
}

.msv-card-body{ padding:.85rem .9rem 1rem; text-align:left; }

.msv-product-name{
  font-size:.85rem; color:#212121; font-weight:400; min-height:2.3em;
  overflow:hidden; text-overflow:ellipsis; display:-webkit-box;
  -webkit-line-clamp:2; -webkit-box-orient:vertical; line-height:1.3;
}

/* rating pill, Flipkart-style green badge — only shows if rating data exists */
.msv-rating{
  display:inline-flex; align-items:center; gap:.3rem; margin:.35rem 0;
}
.msv-rating .stars{
  background:var(--fk-badge); color:#fff; font-size:.72rem; font-weight:600;
  padding:.08rem .38rem; border-radius:3px; display:inline-flex; align-items:center; gap:.2rem;
}
.msv-rating .stars i{ font-size:.62rem; }
.msv-rating .count{ font-size:.75rem; color:#878787; }

/* item code line */
.msv-item-code{
  font-size:.72rem; color:var(--steel); font-family:'IBM Plex Mono', monospace;
  margin:.15rem 0 .3rem;
}
.msv-item-code span{ color:#26333f; font-weight:600; }

/* Assured-style badge */
.msv-assured{
  display:inline-flex; align-items:center; gap:.25rem;
  font-size:.72rem; color:var(--blueprint); font-weight:600;
}
.msv-assured i{ color:var(--blueprint); }

/* price row, Flipkart-style: bold price + strike MRP + green off% */
.msv-price-row{ margin:.35rem 0 .5rem; display:flex; align-items:baseline; gap:.45rem; flex-wrap:wrap; }
.msv-price-row .cur{ font-size:1.05rem; font-weight:700; color:#212121; font-family:'Inter'; }
.msv-price-row del{ color:#878787; font-size:.82rem; font-weight:400; }
.msv-price-row .off{ color:var(--fk-green); font-size:.82rem; font-weight:600; }
.msv-price-row .gst-note{ color:var(--steel); font-size:.72rem; font-weight:400; width:100%; }

/* action buttons row — Amazon "Add to Cart" (yellow) style, flat, no slide animation */
.msv-actions{ display:flex; gap:.5rem; margin-top:.6rem; }
.msv-add-cart{
  background:var(--fk-yellow); color:#0F1111; border:1px solid #FCD200;
  width:100%; font-family:'Inter'; font-weight:600; font-size:.85rem;
  letter-spacing:.01em; padding:.5rem 0; border-radius:20px;
  display:flex; align-items:center; justify-content:center; gap:.4rem;
  box-shadow:0 1px 0 rgba(0,0,0,.05);
  transition: background .15s ease, box-shadow .15s ease;
}
.msv-add-cart:hover{ background:var(--fk-yellow-dk); color:#0F1111; box-shadow:0 2px 6px rgba(0,0,0,.12); }
.msv-add-cart:active{ background:var(--fk-yellow-dk); transform: translateY(1px); }
.msv-add-cart i{ font-size:.82rem; }

/* optional secondary Buy Now button, Flipkart-style orange — wire up to your own route if needed */
.msv-buy-now{
  background:var(--fk-orange); color:#fff; border:1px solid var(--fk-orange-dk);
  width:100%; font-family:'Inter'; font-weight:600; font-size:.85rem;
  padding:.5rem 0; border-radius:20px; text-align:center;
  transition: background .15s ease;
}
.msv-buy-now:hover{ background:var(--fk-orange-dk); color:#fff; }

/* ---------------------------------------------------------
   Scheme / FOC button + modal
--------------------------------------------------------- */
.msv-scheme-btn{
  display:inline-flex; align-items:center; gap:.3rem;
  background:#FFF4E9; border:1px dashed var(--orange); color:var(--orange-dk);
  font-size:.7rem; font-weight:700; letter-spacing:.01em;
  padding:.22rem .5rem; border-radius:3px; margin:.2rem 0 .35rem;
  text-transform:uppercase; cursor:pointer;
}
.msv-scheme-btn i{ font-size:.72rem; }
.msv-scheme-btn:hover{ background:#FFE9D6; }

.msv-foc-modal .modal-content{ border-radius:8px; border:none; overflow:hidden; }
.msv-foc-modal .modal-header{
  background:var(--navy); color:#fff; border-bottom:3px solid var(--orange); padding:1rem 1.25rem;
}
.msv-foc-modal .modal-header .btn-close{ filter:invert(1); }
.msv-foc-modal .modal-title{ font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase; letter-spacing:.02em; font-size:1.15rem; }
.msv-foc-modal .modal-body{ padding:1.25rem; }

.msv-foc-table{ width:100%; border-collapse:collapse; font-size:.85rem; }
.msv-foc-table th{
  background:var(--navy-3); color:#fff; text-align:left; padding:.55rem .7rem;
  font-family:'Barlow Condensed', sans-serif; text-transform:uppercase; letter-spacing:.02em; font-size:.78rem;
}
.msv-foc-table td{ padding:.55rem .7rem; border-bottom:1px dashed #e1e8ef; color:#26333f; }
.msv-foc-table tr:last-child td{ border-bottom:0; }
.msv-foc-table td.slab-name{ font-weight:700; color:var(--navy-3); }
.msv-foc-table td.foc-val{ white-space:nowrap; }
.msv-foc-ratio{
  display:inline-flex; align-items:center; gap:.3rem;
  background:#FFF4E9; border:1px solid var(--orange); color:var(--orange-dk);
  font-weight:800; font-size:.95rem; padding:.2rem .55rem; border-radius:4px;
}
.msv-foc-ratio small{ font-weight:600; font-size:.65rem; text-transform:uppercase; color:var(--orange-dk); margin-left:.15rem; }
.msv-foc-modal .modal-body p.msv-foc-item-name{ font-size:.85rem; color:#26333f; margin-top:.9rem; margin-bottom:0; }

/* ---------------------------------------------------------
   Mobile / responsive rules
   - Tablet (<=991px): filter sidebar moves below product grid
   - Mobile (<=576px): 2 products per row (Flipkart-app style),
     tighter spacing, smaller type, thumb-friendly button
--------------------------------------------------------- */
@media (max-width: 991.98px){
  .msv-sidebar-col{ order: 2; }
  .msv-products-col{ order: 1; }

  .msv-filter-card{ margin-bottom: 1rem; }
}

@media (max-width: 767.98px){
  .msv-heading{ font-size: 1.25rem; }
  .msv-img-wrap img{ height: 160px; padding:.5rem; }
}

@media (max-width: 575.98px){
  .container{ padding-left:.5rem; padding-right:.5rem; }

  /* tighter gutters so 2-up grid doesn't feel cramped */
  .msv-products-col .row{ margin-left:-.35rem; margin-right:-.35rem; }
  .msv-products-col .row > [class^="col-"],
  .msv-products-col .row > [class*=" col-"]{ padding-left:.35rem; padding-right:.35rem; }

  .msv-breadcrumb{ font-size:.72rem; }

  .msv-filter-card{ padding:.85rem !important; }
  .msv-filter-card h4{ font-size:.92rem; }

  .msv-heading{ font-size:1.05rem; padding-bottom:.35rem; }
  .msv-heading::after{ width:40px; height:2px; }

  .msv-img-wrap img{ height: 120px; padding:.4rem; }
  .msv-wish{ width:24px; height:24px; font-size:.72rem; top:6px; right:6px; }
  .msv-tag{ font-size:.58rem; padding:.2rem .4rem !important; top:6px; left:6px; }

  .msv-card-body{ padding:.55rem .55rem .7rem; }
  .msv-product-name{ font-size:.76rem; min-height:2.1em; }

  .msv-item-code{ font-size:.64rem; }

  .msv-rating .stars{ font-size:.66rem; padding:.05rem .32rem; }
  .msv-rating .count{ font-size:.68rem; }
  .msv-assured{ font-size:.66rem; }

  .msv-scheme-btn{ font-size:.62rem; padding:.18rem .4rem; }

  .msv-price-row{ gap:.3rem; margin:.25rem 0 .4rem; }
  .msv-price-row .cur{ font-size:.92rem; }
  .msv-price-row del{ font-size:.72rem; }
  .msv-price-row .off{ font-size:.7rem; }
  .msv-price-row .gst-note{ font-size:.62rem; }

  .msv-actions{ margin-top:.45rem; }
  .msv-add-cart{ font-size:.76rem; padding:.55rem 0; border-radius:16px; }
  .msv-add-cart i{ font-size:.72rem; }

  .msv-cat-list .dropdown-item{ font-size:.85rem; padding:.5rem .2rem; }
}

/* extra-small phones */
@media (max-width: 380px){
  .msv-img-wrap img{ height: 105px; }
  .msv-product-name{ font-size:.72rem; }
  .msv-price-row .cur{ font-size:.85rem; }
}
</style>

<section class="product py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 py-1 msv-breadcrumb">
                <a href="/" class="decoration primary">Home</a>
                &nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;
                All Products
            </div>
        </div>

        <div class="row mt-3">
            <!-- Sidebar -->
            <div class="col-12 col-lg-4 mb-3 msv-sidebar-col">
                <div class="msv-filter-card px-3 py-3 fit-frame">
                    <h4><i class="fa-solid fa-sliders"></i> Filter By Price</h4>
                    <div class="progress mb-3">
                        <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div>
                        <button class="btn msv-btn px-3 py-2">Apply Filters</button>
                    </div>
                </div>

                <h2 class="msv-heading py-3 fs-3">All Categories</h2>

                <div class="msv-filter-card px-3 py-3 fit-frame">
                    <ul class="list-unstyled msv-cat-list">
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('category.products', $category->id) }}"
                                   class="dropdown-item text-dark d-flex align-items-center">
                                    @if($category->photo)
                                        <img src="{{ $category->photo->preview }}" alt="{{ $category->name }}"
                                             style="width: 25px; height: 25px; object-fit: cover;" class="me-2 rounded">
                                    @else
                                        <i class="fa-solid fa-gear me-2 fallback"></i>
                                    @endif
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Products -->
            <div class="col-12 col-lg-8 mb-3 msv-products-col">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="msv-heading py-3">Products</h2>
                    </div>

                    @foreach($products as $product)
                        @php
                            $finalPrice = $product->price - ($product->price * $product->discount / 100);
                        @endphp
                        <div class="col-6 col-md-4 col-lg-4 mb-3">
                            <div class="card border-0 msv-product-card fit-frame position-relative"
                                 style="animation-delay: {{ ($loop->index % 6) * 0.08 }}s;">

                                <span class="msv-wish"><i class="fa-regular fa-heart"></i></span>

                                <a href="/product-detail/{{ $product->id }}" class="decoration">
                                    <div class="msv-img-wrap">
                                        @if($product->tags->isNotEmpty())
                                            @foreach($product->tags as $tag)
                                                <span class="msv-tag">{{ $tag->name }}</span>
                                            @endforeach
                                        @endif
                                        <img src="{{ $product->photo->first()?->getUrl() ?? $noImagePlaceholder }}"
                                             alt="{{ $product->name }}"
                                             onerror="this.onerror=null;this.src='{{ $noImagePlaceholder }}';">
                                    </div>

                                    <div class="msv-card-body">
                                        <h5 class="msv-product-name">{{ $product->name }}</h5>

                                        @if($product->item_code)
                                            <p class="msv-item-code mb-0">Item: <span>{{ $product->item_code }}</span></p>
                                        @endif

                                        {{-- Rating badge: only renders if your Product model has a rating/reviews field.
                                             Wire $product->rating / $product->reviews_count to enable it. --}}
                                        @if(isset($product->rating) && $product->rating)
                                            <div class="msv-rating">
                                                <span class="stars">{{ number_format($product->rating,1) }} <i class="fa-solid fa-star"></i></span>
                                                @if(isset($product->reviews_count))
                                                    <span class="count">({{ number_format($product->reviews_count) }})</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="msv-assured mb-1">
                                            <i class="fa-solid fa-shield-halved"></i> Assured
                                        </div>

                                        {{-- Scheme / FOC button — only shows if this product has slab-wise FOC data
                                             (relation: $product->focSlabs). Add the migration + relation to enable. --}}
                                        @if(isset($product->focSlabs) && $product->focSlabs->isNotEmpty())
                                            <button type="button" class="msv-scheme-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#msvFocModal{{ $product->id }}"
                                                    onclick="event.preventDefault(); event.stopPropagation();">
                                                <i class="fa-solid fa-gift"></i>
                                                @if($product->focSlabs->count() === 1)
                                                    {{ $product->focSlabs->first()->buy_qty }}+{{ $product->focSlabs->first()->free_qty }} Free
                                                @else
                                                    Scheme / Offer
                                                @endif
                                            </button>
                                        @endif

                                        <div class="msv-price-row">
                                            @if (Auth::guard('web')->check())
                                                <span class="cur">₹{{ number_format($finalPrice, 0) }}</span>
                                                <del>₹{{ number_format($product->price_1, 0) }}</del>
                                                <span class="gst-note">(Incl. GST)</span>
                                            @elseif (Auth::guard('customer')->check())
                                                <span class="cur">₹{{ number_format($finalPrice, 0) }}</span>
                                                <span class="off">Price: ₹{{ number_format($product->rate_2, 0) }}</span>
                                                <span class="gst-note">(Incl. GST)</span>
                                            @else
                                                <span class="cur">₹{{ number_format($finalPrice, 0) }}</span>
                                                <del>₹{{ number_format($product->price, 0) }}</del>
                                                @if($product->discount)
                                                    <span class="off">{{ $product->discount }}% off</span>
                                                @endif
                                                <span class="gst-note">(Incl. GST)</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                <div class="px-3 pb-3">
                                    <form action="{{ route('cart.add') }}" method="POST" class="msv-actions">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <input type="hidden" name="name" value="{{ $product->name }}">
                                        <input type="hidden" name="price" value="{{ $product->price }}">
                                        <input type="hidden" name="discount" value="{{ $product->discount }}">
                                        <input type="hidden" name="price_1" value="{{ $product->price_1 }}">
                                        <input type="hidden" name="rate_2" value="{{ $product->rate_2 }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="description" value="{{ $product->description }}">
                                        <input type="hidden" name="photo" value="{{ $product->photo->first()?->getUrl() ?? 'default.png' }}">

                                        <button type="submit" class="msv-add-cart">
                                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- FOC scheme modal for this product — lists every slab (Slab 1, Slab 2, ...)
                             Moved OUTSIDE .msv-product-card (but still inside the column) so it is not
                             trapped by the card's `transform` / `overflow:hidden`, which was making the
                             Bootstrap modal (position:fixed) break on close/click when the card had
                             hover/animation transforms applied. --}}
                        @if(isset($product->focSlabs) && $product->focSlabs->isNotEmpty())
                            <div class="modal fade msv-foc-modal" id="msvFocModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Scheme / Offer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="msv-foc-table">
                                                <thead>
                                                    <tr>
                                                        <th>FOC</th>
                                                        <th>Scheme</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($product->focSlabs as $slab)
                                                        <tr>
                                                            <td class="slab-name">{{ $slab->slab_name }}</td>
                                                            <td class="foc-val">
                                                                <span class="msv-foc-ratio">
                                                                    {{ $slab->buy_qty }} + {{ $slab->free_qty }}
                                                                    <small>FREE</small>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <p class="msv-foc-item-name">Applicable on: <strong>{{ $product->name }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

@endsection