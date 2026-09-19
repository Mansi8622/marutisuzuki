@extends('custom.master')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
.dashboard{background:#f5f7fb}.work-card{background:#fff;border:1px solid #e4ebf3;border-radius:14px;box-shadow:0 12px 28px rgba(18,34,56,.07);padding:18px}.page-head h1{font:800 30px 'Barlow Condensed',sans-serif;color:#13243d;margin:0}.page-head p{color:#718096;margin:0 0 16px}.table thead{background:#172b49;color:#fff}.table th{border:0!important;font-size:11px;text-transform:uppercase;letter-spacing:.05em;padding:14px!important}.table td{padding:13px 12px!important;vertical-align:middle;border-color:#edf1f5!important}.prod{display:flex;align-items:center;gap:12px}.prod img{width:58px;height:58px;object-fit:contain;border:1px solid #e5ebf3;border-radius:9px;background:#f8fafc}.prod b{display:block;color:#13243d}.prod small{color:#718096}.stock{background:#e8faf1;color:#087f5b;border-radius:999px;padding:6px 10px;font-weight:800;font-size:.72rem}.action-row{display:flex;gap:7px}.icon-btn{width:34px;height:34px;border-radius:8px;display:inline-grid;place-items:center;border:1px solid #dbe4ef;background:#fff;color:#172b49}.icon-btn:hover{background:#eef4ff;color:#2454b9}.icon-btn.cart{color:#0b7285}.icon-btn.delete{color:#c92a2a}.dataTables_wrapper .dataTables_filter input,.dataTables_wrapper .dataTables_length select{border:1px solid #dbe4ef;border-radius:8px;padding:6px 10px}
</style>
<section class="dashboard py-5">
  <div class="container">
    <div class="row">
      @include('custom.sidebar')
      <div class="col-lg-9 mb-3">
        <div class="work-card">
          <div class="page-head"><h1>My Wishlist</h1><p>Saved products with quick cart and remove actions.</p></div>
          <div class="table-responsive">
            <table class="table table-hover align-middle datatable-wishlist w-100">
              <thead><tr><th>Product</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
              <tbody>
                @foreach($wishlists as $wishlist)
                  @php
                    $product = $wishlist->product;
                    $price = $product ? (Auth::guard('web')->check() ? ($product->price_1 ?? $product->price) : ($product->price - ($product->price * $product->discount / 100))) : 0;
                  @endphp
                  <tr>
                    <td><div class="prod"><img src="{{ $product?->photo?->first()?->getUrl() ?? asset('images/default-product.png') }}" alt="{{ $product->name ?? 'Product' }}"><div><b>{{ $product->name ?? 'Product not found' }}</b><small>{{ $product->item_code ?? '' }}</small></div></div></td>
                    <td>{{ \Illuminate\Support\Str::limit($product->description ?? 'N/A', 80) }}</td>
                    <td>Rs {{ number_format((float) $price, 2) }}</td>
                    <td><span class="stock"><i class="fa-solid fa-box me-1"></i>In stock</span></td>
                    <td>
                      <div class="action-row">
                        @if($product)
                          <form action="{{ route('frontend.wishlist.move') }}" method="POST">@csrf
                            <input type="hidden" name="id" value="{{ $product->id }}"><input type="hidden" name="name" value="{{ $product->name }}"><input type="hidden" name="price" value="{{ $product->price }}"><input type="hidden" name="discount" value="{{ $product->discount }}"><input type="hidden" name="price_1" value="{{ $product->price_1 }}"><input type="hidden" name="quantity" value="1"><input type="hidden" name="description" value="{{ $product->description }}"><input type="hidden" name="photo" value="{{ $product->photo->first()?->getUrl() ?? '' }}">
                            <button class="icon-btn cart" title="Move to cart"><i class="fa-solid fa-cart-plus"></i></button>
                          </form>
                        @endif
                        <button class="icon-btn delete delete-wishlist" data-id="{{ $wishlist->id }}" title="Remove"><i class="fa-regular fa-trash-can"></i></button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){ $('.datatable-wishlist').DataTable({ pageLength:5, lengthMenu:[[5,10,25,50],[5,10,25,50]] }); $('.delete-wishlist').on('click', function(){ if(!confirm('Remove this product from wishlist?')) return; fetch(`/wishlist/${this.dataset.id}/delete`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Content-Type':'application/json' } }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Unable to remove item.'); }); }); });
</script>
@endsection
