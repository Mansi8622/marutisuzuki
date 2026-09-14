<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Display the cart page
    public function index(Request $request)
    {
        $cart = session()->get('cart', []); // Retrieve cart from session
        return view('custom.cart', compact('cart'));
    }

    // Add product to the cart
    public function addToCart(Request $request)
{
  
    $cart = session()->get('cart', []);

    $productId = $request->id;
    $catalogProduct = Product::findOrFail($productId);
    $rolePrice = $catalogProduct->sellingPrice();
    $product = [
        'id' => $productId,
        'name' => $request->name,
        'price' => $catalogProduct->mrp(),
        'final_price' => $rolePrice,
        'discount' => $catalogProduct->discount ?? 0,
        'price_1' => $catalogProduct->price_1,
        'quantity' => 1,
        'description' => $request->description,
        'photo' => $request->photo ?? asset('default.png'),
        'gst' => $request->gst,
        'rate_2' => $catalogProduct->rate_2,
    ];

    // If the product is already in cart, just update the quantity
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += 1;
    } else {
        $cart[$productId] = $product;
    }

    session()->put('cart', $cart);
    return back()->with('success', 'Item added to cart!');
}

    
public function updateQuantity(Request $request)
{
    $cart = session('cart', []);
    $productId = $request->input('id');
    $updatedQuantity = $request->input('quantity');

    if (isset($cart[$productId])) {
        if ($updatedQuantity > 0) {
            $cart[$productId]['quantity'] = $updatedQuantity;
            session(['cart' => $cart]);
            return response()->json(['success' => true, 'quantity' => $updatedQuantity]);
        } else {
            return response()->json(['success' => false, 'message' => 'Quantity must be greater than zero.']);
        }
    }

    return response()->json(['success' => false, 'message' => 'Product not found in the cart.']);
}



    public function delete(Request $request)
    {
        $cart = session('cart', []); // Retrieve the cart from session
        $productId = $request->input('id'); // Get the product ID to delete

        if (isset($cart[$productId])) {
            unset($cart[$productId]); // Remove the item from the cart
            session(['cart' => $cart]); // Update the session
            return redirect()->back()->with('success', 'Item removed from the cart.');
        }

        return redirect()->back()->with('error', 'Item not found in the cart.');
    }
    

}
