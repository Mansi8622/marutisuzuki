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
    // Price, GST and invoice total are calculated from the catalogue here. The
    // browser fields are only a product/quantity selection and are never used
    // as a source of money values.
    $requestedQuantities = [];
    foreach ($validated['product'] as $index => $productId) {
        $requestedQuantities[$productId] = ($requestedQuantities[$productId] ?? 0)
            + (int) ($validated['product_quantity'][$index] ?? 0);
    }

    $catalogue = Product::whereIn('id', array_keys($requestedQuantities))->get()->keyBy('id');
    if ($catalogue->count() !== count($requestedQuantities)) {
        return back()->withErrors(['product' => 'One or more products are no longer available.']);
    }

    $orderLines = [];
    $totalAmount = 0;
    foreach ($requestedQuantities as $productId => $quantity) {
        $product = $catalogue[$productId];
        $unitPrice = $product->sellingPrice();
        $lineTotal = round($unitPrice * $quantity * (1 + ((float) ($product->gst ?? 0) / 100)), 2);
        $totalAmount += $lineTotal;
        $orderLines[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'selections' => \App\Services\CatalogFitments::orderSelections($product, $quantity),
            'item_code' => $product->item_code,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'gst' => (float) ($product->gst ?? 0),
            'line_total' => $lineTotal,
        ];
    }
    $totalAmount = round($totalAmount, 2);

    $walletRequest = WalletRequest::where('vendor_id', $user->id)->first();

    if ($validated['payment_method'] === 'Credit Line') {
        if (!$walletRequest || $walletRequest->status !== 'Active') {
            return redirect()->back()->with('error', 'Your credit line is not active. Please apply and wait for admin approval.');
        }
        if ((float) $walletRequest->welcome_amount < $totalAmount) {
            return redirect()->back()->with('error', 'Insufficient available credit for this order.');
        }
    }

    // CHECK IF USER HAS USED WALLET BEFORE
    $hasUsedWallet = Transaction::where('vendor_id', $user->id)
        ->where('transaction_type', 'purchase')
        ->exists();



    // PROCESS ORDER PLACEMENT
    DB::beginTransaction();
    try {
        if ($validated['payment_method'] === 'Credit Line') {
            // Re-read under a row lock so simultaneous checkouts cannot spend
            // the same available credit twice.
            $walletRequest = WalletRequest::where('vendor_id', $user->id)->lockForUpdate()->first();
            if (! $walletRequest || $walletRequest->status !== 'Active' || (float) $walletRequest->welcome_amount < $totalAmount) {
                throw new \RuntimeException('Credit line balance changed. Please review the payment method and try again.');
            }
        }

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
        $checkOrder->products = json_encode($orderLines);
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
        foreach ($requestedQuantities as $productId => $quantity) {
            $checkOrder->select_products()->attach($productId, ['quantity' => $quantity]);
        }

        if ($validated['payment_method'] === 'Credit Line') {
            // A credit purchase creates an immutable invoice-linked debit in the ledger.
            $walletRequest->decrement('welcome_amount', $totalAmount);
            $walletRequest->increment('due', $totalAmount);
            Transaction::create([
                'vendor_id' => $user->id, 'order_id' => $checkOrder->id, 'order_number' => $orderNumber,
                'transaction_id' => 'CREDIT-' . strtoupper(uniqid()), 'transaction_type' => 'purchase',
                'request_amount' => $totalAmount, 'paid_amount' => 0, 'total_amount' => $totalAmount,
                'created_by_id' => $user->id, 'status' => 'success',
            ]);
        }

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
    $this->ensureOwner($order);
               

    // Rest of the code remains the same
    $customerName = $order->select_customer->name ?? 'N/A';
    $userName = $order->select_user->name ?? 'N/A';

    $pdf = PDF::loadView('custom.invoiceorder', compact('order', 'customerName', 'userName'));

    return $pdf->download('invoice_' . $order->order_number . '.pdf');
}

public function show(CheckOrder $order)
{
    $this->ensureOwner($order);
    $order->load('select_products', 'carrier');
    return view('custom.order-show', compact('order'));
}

public function edit(CheckOrder $order)
{
    $this->ensureOwner($order);
    abort_unless(strtolower($order->order_status) === 'pending', 403, 'Only pending orders can be updated.');
    return view('custom.order-edit', compact('order'));
}

public function updateDelivery(Request $request, CheckOrder $order)
{
    $this->ensureOwner($order);
    abort_unless(strtolower($order->order_status) === 'pending', 403, 'Only pending orders can be updated.');
    $data = $request->validate([
        'contact_name' => ['required', 'string', 'max:120'],
        'contact_phone' => ['required', 'string', 'max:30'],
        'contact_email' => ['nullable', 'email', 'max:160'],
        'country' => ['nullable', 'string', 'max:100'], 'state' => ['nullable', 'string', 'max:100'],
        'district' => ['nullable', 'string', 'max:100'], 'full_address' => ['required', 'string', 'max:1000'],
    ]);
    $address = json_decode($order->shipping_address, true) ?: [];
    $order->shipping_address = json_encode(array_merge($address, $data));
    $order->save();
    return redirect()->route('frontend.orders.show', $order)->with('success', 'Delivery address and contact details updated. Items and payment were not changed.');
}

private function ensureOwner(CheckOrder $order): void
{
    $allowed = (Auth::guard('web')->check() && (int) $order->select_user_id === (int) Auth::guard('web')->id())
        || (Auth::guard('customer')->check() && (int) $order->select_customer_id === (int) Auth::guard('customer')->id());
    abort_unless($allowed, 403);
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
