@extends('custom.master')

@section('content')

<section class="dashboard py-5">
    <div class="container">
        <div class="row">
           @include('custom.sidebar')

            <div class="col-lg-9 mb-3">
              <h1 class="mb-4">My Wishlist</h1>

              <!-- laptop size -->
              <div class="card px-5 py-3 d-lg-block d-none">
                <div class="row">
                    <div class="col-12 pb-3">
                        <div class="row">
                            <div class="col-2"> Product Name</div>
                            <div class="col-2">Description</div>
                            <div class="col-2">Price</div>
                            <div class="col-2">Stock</div>
                            <div class="col-2">Images</div>
                            <div class="col-2">Options</div>
                        </div>
                    </div>

                    <!-- Loop through wishlist items -->
                    @foreach($wishlists as $wishlist)
                    <div class="col-12">
                        <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center;">
                            <div class="col-2">
                                {{ $wishlist->product->name ?? '' }}
                            </div>
                            <div class="col-2">
                                {{ $wishlist->product->description ?? '' }}
                            </div>
                            <div class="col-2">
                                @if($wishlist->product)
                                    ₹{{ Auth::guard('web')->check() 
                                        ? $wishlist->product->price_1 ?? '' 
                                        : ($wishlist->product->price - ($wishlist->product->price * $wishlist->product->discount / 100))  
                                    }}
                                @else
                                    <span class="text-danger">Product not found</span>
                                @endif
                            </div>
                            
                            <div class="col-2">
                                In Stock
                            </div>
                            <div class="col-2">
                                @if($wishlist->product && $wishlist->product->photo && $wishlist->product->photo->first())
                                    <img src="{{ $wishlist->product->photo->first()->getUrl() }}" alt="Product Image" class="img-fluid">
                                @else
                                    <img src="{{ asset('images/default-product.png') }}" alt="No Image" class="img-fluid">
                                @endif
                            </div>
                            
                            <div class="col-2 d-flex justify-content-between">
                                <!-- Delete Button -->
                                <button class="btn delete-wishlist" data-id="{{ $wishlist->id }}" style="width: 40px; height: 40px; border-radius: 100%; background-color: #E06563; color: white;">
                                    <i class="fas fa-trash"></i>
                                </button>
                                
                                <!-- Add to Cart Button -->
                                <form action="{{ route('frontend.wishlist.move') }}" method="POST">
                                    @if($wishlist->product)
                                    <input type="hidden" name="id" value="{{ $wishlist->product->id ?? '' }}">
                                    <input type="hidden" name="name" value="{{ $wishlist->product->name ?? '' }}">
                                    <input type="hidden" name="price" value="{{ $wishlist->product->price ?? '' }}">
                                    <input type="hidden" name="discount" value="{{ $wishlist->product->discount ?? '' }}">
                                    <input type="hidden" name="price_1" value="{{ $wishlist->product->price_1 ?? '' }}">
                                    <input type="hidden" name="quantity" value="{{ $wishlist->product->quantity ?? '' }}">
                                    <input type="hidden" name="description" value="{{ $wishlist->product->description ?? '' }}">
                                    <input type="hidden" name="photo" value="{{ $wishlist->product->photo->first()?->getUrl() ?? '' }}">
                                @else
                                    {{-- Optionally show a message or skip rendering --}}
                                @endif
                                
                                
                                    <div class="card">
                       
        
                                        <div class="text-center p-0">
                                            
                                            
                                            <button class="btn add-to-cart" data-id="{{ $wishlist->product->id ?? ''}}" style="width: 40px; height: 40px; border-radius: 100%; background-color: #FF9E66; color: white;">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
              </div>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Wishlist delete function
        document.querySelectorAll('.delete-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                let wishlistId = this.getAttribute('data-id');
                
                if(confirm("Are you sure you want to delete this item from your wishlist?")) {
                    fetch(`/wishlist/${wishlistId}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                      .then(data => {
                          if(data.success) {
                              alert("Wishlist item deleted successfully!");
                              location.reload();
                          } else {
                              alert("Error deleting wishlist item.");
                          }
                      });
                }
            });
        });

        
    });
</script>

@endsection
