<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CheckOrder;
use App\Models\Product;
use App\Models\User;
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
            'total_amount' => 'required|numeric',
            'razorpay_payment_id' => 'required_if:payment_method,razorpay',
            'full_address' => 'required|string',
        ]);
    
        DB::beginTransaction();
    
        try {
            $user = auth()->guard('web')->user();       // admin/user guard
            $customer = auth()->guard('customer')->user(); // customer guard
            $products = json_decode($request->products, true);
        
            $order = new CheckOrder();
            $order->order_number = 'ORD-' . strtoupper(uniqid());
            $order->total_amount = $request->total_amount;
            $order->payment_method = 'razorpay';
            $order->payment_status = 'pending';
            $order->shipping_address = $request->full_address;
            $order->billing_address = $request->full_address;
            // dd($request->full_address);
            $order->placed_at = now();
            $order->order_status = 'Processing';
            $order->transaction_id = $request->razorpay_payment_id;
            $order->products = $request->products;
        
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