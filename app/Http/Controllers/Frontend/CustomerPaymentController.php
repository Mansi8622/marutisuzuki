<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CheckOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\Frontend\CustomerPaymentRequest;

class CustomerPaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $request->validate([
            'products' => 'required|json',
            'offer_id' => 'nullable|integer',
            'razorpay_payment_id' => 'required|string|max:255',
            'full_address' => 'required|string',
        ]);
    
        DB::beginTransaction();
    
        try {
            $user = auth()->guard('web')->user();       // admin/user guard
            $customer = auth()->guard('customer')->user(); // customer guard
            $requestedProducts = json_decode($request->products, true, 512, JSON_THROW_ON_ERROR);
            $quantities = [];
            foreach ($requestedProducts as $key => $line) {
                if (! is_array($line)) continue;
                $id = $line['id'] ?? (is_numeric($key) ? $key : null);
                $quantity = max(0, (int) ($line['quantity'] ?? 0));
                if ($id && $quantity) $quantities[$id] = ($quantities[$id] ?? 0) + $quantity;
            }
            if (empty($quantities)) throw ValidationException::withMessages(['products' => 'Your cart is empty.']);

            $catalogue = Product::whereIn('id', array_keys($quantities))->get()->keyBy('id');
            if ($catalogue->count() !== count($quantities)) {
                throw ValidationException::withMessages(['products' => 'A cart item is no longer available.']);
            }

            // Never trust browser-provided prices, taxes or discounts.
            $products = [];
            $subtotal = 0;
            $tax = 0;
            foreach ($quantities as $id => $quantity) {
                $product = $catalogue[$id];
                $unitPrice = $product->sellingPrice();
                $lineSubtotal = $unitPrice * $quantity;
                $subtotal += $lineSubtotal;
                $tax += $lineSubtotal * ((float) ($product->gst ?? 0) / 100);
                $products[$id] = ['id' => $id, 'name' => $product->name, 'selections' => \App\Services\CatalogFitments::orderSelections($product, $quantity), 'item_code' => $product->item_code, 'quantity' => $quantity, 'unit_price' => $unitPrice, 'gst' => (float) ($product->gst ?? 0)];
            }
            $deliveryFee = auth('web')->check() ? 0 : ($subtotal > 500 ? 0 : 50);
            $invoiceTotal = round($subtotal + $tax + $deliveryFee, 2);
            $offer = $request->filled('offer_id')
                ? Offer::available()->whereKey($request->offer_id)->where('minimum_order_amount', '<=', $invoiceTotal)->first()
                : null;
            $discount = $offer ? round($invoiceTotal * ((float) $offer->discount_percent / 100), 2) : 0;
            $payable = max(0, $invoiceTotal - $discount);

            $order = new CheckOrder();
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $order->total_amount = $payable;
            $order->offer_id = optional($offer)->id;
            $order->offer_discount_amount = $discount;
            $order->payment_method = 'razorpay';
            $order->payment_status = 'pending';
            $order->shipping_address = $request->full_address;
            $order->billing_address = $request->full_address;
            // dd($request->full_address);
            $order->placed_at = now();
            $order->order_status = 'Processing';
            $order->transaction_id = $request->razorpay_payment_id;
            $order->products = json_encode($products);
        
            // ✅ Correct user/customer assignment
            if ($user) {
                $order->select_user_id = $user->id;
                $order->created_by_id = $user->id;
                $order->select_customer_id = null;
            } elseif ($customer) {
                $order->select_customer_id = $customer->id;
                $order->select_user_id = null;
                $order->created_by_id = null; // customer users table me nahi hai
            } else {
                // No logged-in user or customer
                $order->select_user_id = null;
                $order->select_customer_id = null;
                $order->created_by_id = null;
            }
        
            $order->save();
            // Attach products
           // Attach products to the order with quantities
foreach ($products as $item) {
    $order->products()->attach($item['id'], ['quantity' => $item['quantity']]);
}

    
            $order->payment_status = 'paid';
            $order->save();

            session()->forget('cart'); // or whatever key you're using for cart data

            DB::commit();
    
            return redirect()->route('payment.success', $order->id)
            ->with('success', 'Payment successful and order placed!');
        
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order processing failed: ' . $e->getMessage());
    
            return back()->with('error', 'There was an error processing your order: ' . $e->getMessage());
        }
    }

    public function paymentSuccess($orderId)
{
    
    $order = CheckOrder::findOrFail($orderId);
    // dd($order);
    return view('custom.payment-success', compact('order'));
}

    

}
