<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        return $this->addToWishlist($request->product_id);
    }

    public function index()
    {
        // Check if the user is logged in via either customer or web guard
        if (Auth::guard('customer')->check()) {
            // Fetch the wishlist for the customer
            $wishlists = Wishlist::where('customer_id', Auth::guard('customer')->user()->id)->get();
        } elseif (Auth::guard('web')->check()) {
            // Fetch the wishlist for the web user
            $wishlists = Wishlist::where('user_id', Auth::guard('web')->user()->id)->get();
        } else {
            // No user is logged in, return an empty wishlist
            $wishlists = collect();
        }

        // Pass the wishlist to the view
        return view('custom.wishlist', compact('wishlists'));
    }
    // Function to add product to wishlist
    public function addToWishlist($productId)
        {
            if (!Auth::guard('customer')->check() && !Auth::guard('web')->check()) {
                return response()->json(['login_required' => true], 401);
            }

            $product = Product::find($productId);
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $guardField = Auth::guard('customer')->check() ? 'customer_id' : 'user_id';
            $userId = Auth::guard('customer')->check()
                ? Auth::guard('customer')->user()->id
                : Auth::guard('web')->user()->id;

            try {
                $existing = Wishlist::where($guardField, $userId)
                                ->where('product_id', $product->id)
                                ->first();

                if ($existing) {
                    $existing->delete();
                    return response()->json(['status' => 'removed']);
                }

                Wishlist::create([
                    $guardField => $userId,
                    'product_id' => $product->id,
                ]);

                return response()->json(['status' => 'added']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'Wishlist item not found.'], 404);
        }

        $wishlist->delete();

        return response()->json(['success' => true, 'message' => 'Wishlist item deleted successfully.']);
    }
    public function addToCart(Request $request)
{
    $cart = session()->get('cart', []);

    $productId = $request->id;
    $product = [
        'id' => $productId,
        'name' => $request->name,
        'price' => $request->price,
        'discount' => $request->discount ?? 0, // Ensure discount is always set
        'price_1' => $request->price_1 ?? null,
        'quantity' => 1,
        'description' => $request->description,
        'photo' => $request->photo ?? asset('default.png'),
    ];

    // If the product is already in cart, just update the quantity
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += 1;
    } else {
        $cart[$productId] = $product;
    }

    // Save to session
    session()->put('cart', $cart);

    // **Wishlist se product delete karna**
    if (Auth::guard('customer')->check()) {
        Wishlist::where('customer_id', Auth::guard('customer')->user()->id)
            ->where('product_id', $productId)
            ->delete();
    } elseif (Auth::guard('web')->check()) {
        Wishlist::where('user_id', Auth::guard('web')->user()->id)
            ->where('product_id', $productId)
            ->delete();
    }

    return back()->with('success', 'Item added to cart and removed from wishlist!');
}

}
