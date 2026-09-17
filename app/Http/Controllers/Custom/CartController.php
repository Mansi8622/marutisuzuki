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
    $selection = \App\Services\CatalogFitments::selection($catalogProduct, $request->input('fitment_id'), $request->input('category_id'));
    $cartKey = $productId . ':' . ($selection['fitment_id'] ?? 'c'.$selection['category_id']);
    $rolePrice = $catalogProduct->sellingPrice();
    $product = [
        'id' => $productId,
        'name' => $catalogProduct->name,
        'item_code' => $catalogProduct->item_code,
        'price' => $catalogProduct->mrp(),
        'final_price' => $rolePrice,
        'discount' => $catalogProduct->discount ?? 0,
        'price_1' => $catalogProduct->price_1,
        'quantity' => 1,
        'description' => $catalogProduct->description,
        'photo' => $request->photo ?? asset('default.png'),
        'gst' => $catalogProduct->gst,
        'rate_2' => $catalogProduct->rate_2,
    ];

    $product = array_merge($product, $selection, ['cart_key' => $cartKey]);

    // If the product is already in cart, just update the quantity
    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += 1;
    } else {
        $cart[$cartKey] = $product;
    }

    session()->put('cart', $cart);
    return back()->with('success', 'Item added to cart!');
}

    
public function updateQuantity(Request $request)
{
    $request->validate(['id' => ['required'], 'quantity' => ['required', 'integer', 'min:1', 'max:999']]);
    $cart = session('cart', []);
    $productId = $request->input('id');
    $updatedQuantity = $request->input('quantity');

    if (isset($cart[$productId])) {
        if ($updatedQuantity > 0) {
            $cart[$productId]['quantity'] = $updatedQuantity;
            session(['cart' => $cart]);
            $mrpTotal = collect($cart)->sum(fn ($item) => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1));
            $payableTotal = collect($cart)->sum(fn ($item) => (float) ($item['final_price'] ?? $item['price_1'] ?? $item['price'] ?? 0) * (int) ($item['quantity'] ?? 1));
            $linePrice = (float) ($cart[$productId]['final_price'] ?? $cart[$productId]['price_1'] ?? $cart[$productId]['price'] ?? 0);
            return response()->json(['success' => true, 'quantity' => $updatedQuantity, 'line_total' => round($linePrice * $updatedQuantity, 2), 'mrp_total' => round($mrpTotal, 2), 'payable_total' => round($payableTotal, 2)]);
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
