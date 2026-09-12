<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CheckOrder;
use App\Models\WalletRequest;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\OurStock;

use Barryvdh\DomPDF\Facade\Pdf;

class CheckOrderController extends Controller
{
    

// CheckOrderController.php
public function index()
{
    if (Auth::guard('customer')->check()) {
        // If the logged-in user is a customer
        $userId = Auth::guard('customer')->id();
        // Retrieve orders for the customer
        $orders = CheckOrder::where('select_customer_id', $userId) // Use select_customer_id for customers
            ->with([
                'select_products' => function ($query) {
                    $query->select('products.id', 'products.name', 'products.price', 'check_order_product.quantity')
                          ->with(['select_companies' => function ($query) {
                              $query->select('add_companies.id', 'add_companies.company_name');
                          }]);
                }
            ])
            ->get();

    } elseif (Auth::guard('web')->check()) {
        // If the logged-in user is a web user (admin)
        $userId = Auth::guard('web')->id();
        // Retrieve orders for the user (admin or any user in this guard)
        $orders = CheckOrder::where('select_user_id', $userId) // Use select_user_id for users
            ->with([
                'select_products' => function ($query) {
                    $query->select('products.id', 'products.name', 'products.price', 'check_order_product.quantity')
                          ->with(['select_companies' => function ($query) {
                              $query->select('add_companies.id', 'add_companies.company_name');
                          }]);
                }
            ])
            ->get();
    } else {
        // If no user is logged in
        return redirect()->route('customer.login')->with('error', 'You need to login first.');
    }
    // dd($orders);

    return view('custom.order', compact('orders'));
}



public function store(Request $request)
{
 
    // Validate the incoming request
    $validated = $request->validate([
        'email' => 'required|email',
        'name' => 'required|string',
        'phone' => 'required|string',
        'country' => 'required|string',
        'state' => 'required|string',
        'district' => 'required|string',
        'full_address' => 'required|string',
        'product' => 'required|array',
        'total_amount' => 'required|numeric',
        'payment_method' => 'required|string',
        'product' => 'required|array',
        'product.*' => 'exists:products,id',
        'product_quantity' => 'required|array',
        'product_quantity.*' => 'numeric|min:1',
        'products' => 'required|array',
        'products.*' => 'required|string',
    ]);
    
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in first.');
    }

    $user = Auth::guard('web')->user();
    $walletRequest = WalletRequest::where('vendor_id', $user->id)->first();

    if (!$walletRequest) {
        return redirect()->back()->with('error', 'No wallet found for user.');
    }

    // CHECK IF USER HAS USED WALLET BEFORE
    $hasUsedWallet = Transaction::where('vendor_id', $user->id)
        ->where('transaction_type', 'purchase')
        ->exists();



    // PROCESS ORDER PLACEMENT
    DB::beginTransaction();
    try {
        $totalAmount = $validated['total_amount'];

        // CREATE ORDER
        $orderNumber = 'ORD' . strtoupper(substr(uniqid(), 0, 5)) . rand(10000, 99999);
        while (CheckOrder::where('order_number', $orderNumber)->exists()) {
            $orderNumber = 'ORD' . strtoupper(substr(uniqid(), 0, 5)) . rand(10000, 99999);
        }

        $checkOrder = new CheckOrder();
        $checkOrder->select_user_id = $user->id;
        $checkOrder->order_number = $orderNumber;
        $checkOrder->total_amount = $totalAmount;
        $checkOrder->payment_method = $validated['payment_method'];
        $checkOrder->products = json_encode($validated['products']);
        $checkOrder->payment_status = 'confirm';
        $checkOrder->shipping_address = json_encode([
            'country' => $validated['country'],
            'state' => $validated['state'],
            'district' => $validated['district'],
            'full_address' => $validated['full_address']
        ]);
        $checkOrder->billing_address = json_encode([
            'country' => $validated['country'],
            'state' => $validated['state'],
            'district' => $validated['district'],
            'full_address' => $validated['full_address']
        ]);
        $checkOrder->placed_at = now()->toDateTimeString();
        $checkOrder->order_status = 'Pending';
        $checkOrder->created_by_id = $user->id;
        $checkOrder->transaction_id = uniqid();
       
        $checkOrder->save();

        // ATTACH PRODUCTS
        foreach ($validated['product'] as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $quantity = $validated['product_quantity'][$index];
                $checkOrder->select_products()->attach($product, ['quantity' => $quantity]);
            }
        }

        // DEDUCT FROM WALLET
        $walletRequest->welcome_amount -= $totalAmount;
        $walletRequest->save();

        // ADD TRANSACTION RECORD
        Transaction::create([
            'vendor_id' => $user->id,
            'transaction_type' => 'purchase',
            'request_amount' => $totalAmount,
            'created_by_id' => $user->id,
            'status' => 'success',
            'order_number'=>$orderNumber,
        ]);

        // UPDATE DUE AMOUNT IN WALLET
        $walletRequest->update([
            'due' => $walletRequest->due + $totalAmount
        ]);

        session()->forget('cart');
        DB::commit();

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('frontend.orders.index')->with('error', 'Failed to save the order. Please try again.');
    }
    
    
}

    public function cancelOrder(Request $request, $orderId)
    {
    // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You need to login first.');
    }

    // Identify the logged-in user and guard
    if (Auth::guard('customer')->check()) {
        $userId = Auth::guard('customer')->id();
    } elseif (Auth::guard('web')->check()) {
        $userId = Auth::guard('web')->id();
    } else {
        return redirect()->back()->with('error', 'Unauthorized access.');
    }

    // Fetch the order
    $order = CheckOrder::where('id', $orderId)
        ->where('select_user_id', $userId)
        ->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Order not found.');
    }

    // Check if order status is pending
    if ($order->order_status !== 'Pending') {
        return redirect()->back()->with('error', 'Only pending orders can be canceled.');
    }

    // Begin database transaction
    DB::beginTransaction();
    try {
        // Update order status to canceled
        $order->order_status = 'Canceled';
        $order->save();

        // If the user is authenticated through the 'web' guard, process the refund
        if (Auth::guard('web')->check()) {
            // Fetch user's wallet
            $walletRequest = WalletRequest::where('vendor_id', $userId)->first();

            if ($walletRequest) {
                // Add the refunded amount back to the wallet
                $walletRequest->welcome_amount += $order->total_amount;
                $walletRequest->save();
            } else {
                throw new \Exception('Wallet not found.');
            }

            // Record the refund transaction
            Transaction::create([
                'vendor_id' => $userId,
                'created_by_id' => $userId,
                'request_amount' => $order->total_amount,
                'transaction_type' => 'Refund',
                'status' => 'Approved',
            ]);
        }

        // Commit the transaction
        DB::commit();

        return redirect()->back()->with('success', 'Order has been canceled and refunded successfully.');
    } catch (\Exception $e) {
        // Rollback transaction in case of error
        DB::rollBack();
        Log::error('Order cancellation error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to cancel the order. Please try again.');
    }
}



    public function searchOrder(Request $request)
{
    // Validate the input
    $request->validate([
        'order_number' => 'required|string'
    ]);

    // Check user authentication
    if (Auth::guard('customer')->check()) {
        $userId = Auth::guard('customer')->id();
    } elseif (Auth::guard('web')->check()) {
        $userId = Auth::guard('web')->id();
    } else {
        return redirect()->route('login')->with('error', 'You need to login first.');
    }

    // Fetch order based on the input order number and logged-in user ID
    $orders = CheckOrder::where('select_user_id', $userId)
                ->where('order_number', 'LIKE', '%' . $request->order_number . '%')
                ->with('select_products')
                ->get();

    // Return the view with filtered orders
    return view('custom.order', compact('orders'));
}
public function downloadInvoice($orderNumber)
{
    // Load the order using order_number
    $order = CheckOrder::with(['select_customer', 'select_user', 'select_products'])
                ->where('order_number', $orderNumber)
                ->firstOrFail();
               

    // Rest of the code remains the same
    $customerName = $order->select_customer->name ?? 'N/A';
    $userName = $order->select_user->name ?? 'N/A';

    $pdf = PDF::loadView('custom.invoiceorder', compact('order', 'customerName', 'userName'));

    return $pdf->download('invoice_' . $order->order_number . '.pdf');
}

public function pendingOrders()
{
    $orders = CheckOrder::with(['select_user', 'select_products', 'select_customer'])
        ->get()
        ->map(function ($order) {
            $productsJson = json_decode($order->products, true);
            $productData = [];

            if ($productsJson) {
                if (isset($productsJson[0]) && is_string($productsJson[0])) {
                    $decoded = json_decode($productsJson[0], true);
                    $productData = is_array($decoded) ? $decoded : [];
                } elseif (is_array($productsJson)) {
                    $productData = $productsJson;
                }
            }

            $confirmedQuantities = json_decode($order->confirm_qty, true) ?? [];

            $pending = [];
            $userName = $order->select_user->name ?? $order->select_customer->name ?? '';

            foreach ($productData as $productId => $productInfo) {
                $orderedQty = $productInfo['quantity'] ?? 0;
                $confirmedQty = $confirmedQuantities[$productId] ?? 0;

                if ($confirmedQty < $orderedQty) {
                    $product = Product::find($productId);
                    $stockQty = OurStock::where('select_product_id', $productId)->sum('quantity_available');

                    $pending[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'user' => $userName,
                        'product_id' => $productId,
                        'product_name' => $product->name ?? '',
                        'ordered_quantity' => $orderedQty,
                        'confirmed_quantity' => $confirmedQty,
                        'pending_quantity' => $orderedQty - $confirmedQty,
                        'stock_quantity' => $stockQty,
                    ];
                }
            }

            return $pending;
        })
        ->filter()
        ->flatten(1);

    return view('admin.checkOrders.pending', compact('orders'));
}

public function RetailerpendingOrders()
{
    // Get the logged-in user or customer
    $user = auth('web')->user();
    $customer = auth('customer')->user();
    
    $orders = CheckOrder::with(['select_user', 'select_products', 'select_customer'])
        ->when($user, function ($query) use ($user) {
            $query->where('select_user_id', $user->id);
        })
        ->when($customer, function ($query) use ($customer) {
            $query->where('select_customer_id', $customer->id);
        })
        ->get()
        ->map(function ($order) {
            // Safely decode products JSON
            $productsJson = $order->products;
            $productData = [];

            if (is_string($productsJson)) {
                $decodedProducts = json_decode($productsJson, true);
                if (isset($decodedProducts[0]) && is_string($decodedProducts[0])) {
                    $nested = json_decode($decodedProducts[0], true);
                    $productData = is_array($nested) ? $nested : [];
                } elseif (is_array($decodedProducts)) {
                    $productData = $decodedProducts;
                }
            }

            // Decode confirmed quantities
            $confirmedQuantities = is_string($order->confirm_qty)
                ? json_decode($order->confirm_qty, true) ?? []
                : [];

            $pending = [];
            $userName = $order->select_user->name ?? $order->select_customer->name ?? '';

            foreach ($productData as $productId => $productInfo) {
                $orderedQty = $productInfo['quantity'] ?? 0;
                $confirmedQty = $confirmedQuantities[$productId] ?? 0;

                if ($confirmedQty < $orderedQty) {
                    $product = Product::find($productId);
                    $stockQty = OurStock::where('select_product_id', $productId)->sum('quantity_available');

                    $price = $product->price_1 ?? 0;
                    $pendingQty = $orderedQty - $confirmedQty;
                    $pendingPrice = $pendingQty * $price;
                    // dd($price);
                    
                    $pending[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'user' => $userName,
                        'product_id' => $productId,
                        'product_name' => $product->name ?? '',
                        'ordered_quantity' => $orderedQty,
                        'confirmed_quantity' => $confirmedQty,
                        'pending_quantity' => $pendingQty,
                        'stock_quantity' => $stockQty,
                        'total_amount' => $order->total_amount ?? 0,
                        'pending_price' => $pendingPrice,
                    ];
                }
            }

            return $pending;
        })
        ->filter()
        ->flatten(1);

    return view('custom.pending', compact('orders'));
}


public function CustomerpendingOrders()
{
    // Get the logged-in user or customer
    $user = auth('web')->user();
    $customer = auth('customer')->user();
    
    $orders = CheckOrder::with(['select_user', 'select_products', 'select_customer'])
        ->when($user, function ($query) use ($user) {
            $query->where('select_user_id', $user->id);
        })
        ->when($customer, function ($query) use ($customer) {
            $query->where('select_customer_id', $customer->id);
        })
        ->get()
        ->map(function ($order) {
            // Safely decode products JSON
            $productsJson = $order->products;
            $productData = [];

            if (is_string($productsJson)) {
                $decodedProducts = json_decode($productsJson, true);
                if (isset($decodedProducts[0]) && is_string($decodedProducts[0])) {
                    $nested = json_decode($decodedProducts[0], true);
                    $productData = is_array($nested) ? $nested : [];
                } elseif (is_array($decodedProducts)) {
                    $productData = $decodedProducts;
                }
            }

            // Decode confirmed quantities
            $confirmedQuantities = is_string($order->confirm_qty)
                ? json_decode($order->confirm_qty, true) ?? []
                : [];

            $pending = [];
            $userName = $order->select_user->name ?? $order->select_customer->name ?? '';

            foreach ($productData as $productId => $productInfo) {
                $orderedQty = $productInfo['quantity'] ?? 0;
                $confirmedQty = $confirmedQuantities[$productId] ?? 0;

                if ($confirmedQty < $orderedQty) {
                    $product = Product::find($productId);
                    $stockQty = OurStock::where('select_product_id', $productId)->sum('quantity_available');

                    $price = $product->rate_2 ?? 0;
                    $pendingQty = $orderedQty - $confirmedQty;
                    $pendingPrice = $pendingQty * $price;
                    // dd($price);
                    
                    $pending[] = [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'user' => $userName,
                        'product_id' => $productId,
                        'product_name' => $product->name ?? '',
                        'ordered_quantity' => $orderedQty,
                        'confirmed_quantity' => $confirmedQty,
                        'pending_quantity' => $pendingQty,
                        'stock_quantity' => $stockQty,
                        'total_amount' => $order->total_amount ?? 0,
                        'pending_price' => $pendingPrice,
                    ];
                    
                }
            }

            return $pending;
        })
        ->filter()
        ->flatten(1);

    return view('customer.pending', compact('orders'));
}

}