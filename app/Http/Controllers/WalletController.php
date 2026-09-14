<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletRequest;
use App\Models\CheckOrder;
use App\Models\Transaction;
use Auth;
use Illuminate\Support\Str;


class WalletController extends Controller
{
    // Apply for Wallet
    public function applyWallet(Request $request)
    {
        $user = Auth::user();

        // Check if wallet request already exists
        $existingWallet = WalletRequest::where('vendor_id', $user->id)->first();

        if ($existingWallet && $existingWallet->status === 'pending') {
            return response()->json(['error' => 'You have already applied for a wallet. Please wait for approval.']);
        }

        if ($existingWallet && $existingWallet->status === 'active') {
            return response()->json(['error' => 'Your wallet is already active.']);
        }


        // Create new wallet request
        WalletRequest::create([
            'vendor_id' => $user->id,
            'created_by_id' => $user->id,
            'status' => 'pending',
            'welcome_amount' => 0,
            'due' => 0,
        ]);

        return response()->json(['success' => true]);
    }
    
    public function requestAmount(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'request_amount'      => 'required|numeric|min:1',
            'orders'              => 'required|array|min:1',
        ]);
    
        $user = Auth::user();
    
        $wallet = WalletRequest::where('vendor_id', $user->id)->first();
    
        if (!$wallet || $wallet->status !== 'Active') {
            return redirect()->back()->with('error', 'Your wallet is not active.');
        }
    
        $totalPaid = 0;
        $welcomeCredit = 0;
    
        foreach ($request->orders as $orderNumber => $data) {
            $amount = floatval($data['amount'] ?? 0);
            $transactionId = $data['transaction_id'] !== "null" ? $data['transaction_id'] : null;
    
            if (!$orderNumber || $amount <= 0) {
                continue;
            }
    
            $internalTransactionId = strtoupper(Str::random(10));
    
            // Only this transaction type and status will be counted for welcome_amount
            $transaction = Transaction::create([
                'order_number'       => $orderNumber,
                'order_id'           => $orderNumber,
                'transaction_id'     => $internalTransactionId,
                'payment_gateway_id' => $request->razorpay_payment_id,
                'request_amount'     => $amount,
                'vendor_id'          => $user->id,
                'status'             => 'success',
                'transaction_type'   => 'payout',
                'paid_amount'        => $amount,
                'total_amount'       => $amount,
                'created_by_id'      => $user->id,
            ]);
    
            $totalPaid += $amount;
    
            // Only add to welcome_amount if status is success and type is payout
            if ($transaction->status === 'success' && $transaction->transaction_type === 'payout') {
                $welcomeCredit += $amount;
            }
        }
    
        // Update wallet fields
        $wallet->due = max(0, $wallet->due - $totalPaid);
        $wallet->welcome_amount += $welcomeCredit;
        $wallet->save();
    
        return redirect()->route('frontend.home')->with('success', 'Payout request submitted successfully.');
    }
        

    // Add Amount Popup
    public function getAddAmountPopup()
    {
        return view('wallet.add-amount-popup');
    }
    public function getPayOutPopup()
    {
        $userId = Auth::id();
    
        // Wallet data fetch
        $wallet = WalletRequest::where('vendor_id', $userId)->first();
       
        // Transactions table se total purchase amount fetch karna
        $totalPurchaseAmount = Transaction::where('vendor_id', $userId)
            ->where('transaction_type', 'purchase')
            ->sum('request_amount');
    
        // Due amount calculate karna
        $dueAmount = $wallet->due;
    
        // Approved payouts fetch karna
        $payouts = Transaction::where('vendor_id', $userId)
            ->where('transaction_type', 'payout')
            ->where('status', 'Approved')
            ->sum('request_amount');
    
        // Payout amount deduct karna
        $dueAmount -= $payouts;

        $order = \App\Models\CheckOrder::find($request->order_number);

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $transactions = \App\Models\Transaction::where('order_id', $order->id)->where('transaction_type', 'payout')->get();
    $paidAmount = $transactions->sum('request_amount');
    $remaining = $order->total_amount - $paidAmount;

    return response()->json([
        'total_amount' => $order->total_amount,
        'paid_amount' => $paidAmount,
        'remaining_amount' => $remaining,
        'created_at' => $order->created_at->format('Y-m-d'),
        'transaction_id' => $transactions->last()->id ?? 'N/A',
    ]);
    
        return view('wallet.request-payout-model', compact('wallet', 'dueAmount'));
    }
   


    
    public function getCreditLineOrders()
    {
        $userId = auth()->id();
    
        $orders = CheckOrder::where('select_user_id', $userId)
            ->where('payment_method', 'Credit Line')
            ->get(['id', 'order_number', 'total_amount', 'order_status', 'created_at']);
    
        foreach ($orders as $order) {
            // Get all transactions related to this order_number
            $transactions = Transaction::where('order_number', $order->order_number)->get();
    
            $totalPaid = $transactions->sum('paid_amount'); // Sum of all paid amounts
    
            $order->transaction_exists = $transactions->isNotEmpty();
            $order->paid_amount = $totalPaid;
            $order->remaining_amount = max(0, $order->total_amount - $totalPaid);
    
            // Optionally return latest transaction_id
            $order->transaction_id = $transactions->last()->id ?? null;
        }
    
        return response()->json([
            'orders' => $orders
        ]);
    }
    






    

    public function index()
    {
    $userId = Auth::id();

    // Wallet data fetch
    $wallet = WalletRequest::where('vendor_id', $userId)->first();

    // Transactions table se total purchase amount fetch karna
    $totalPurchaseAmount = Transaction::where('vendor_id', $userId)
        ->where('transaction_type', 'purchase')
        ->sum('request_amount');

    // Due amount calculate karna
    $dueAmount = $wallet ? ($wallet->due + $totalPurchaseAmount) : $totalPurchaseAmount;

    // Approved payouts fetch karna
    $payouts = Transaction::where('vendor_id', $userId)
        ->where('transaction_type', 'payout')
        ->where('status', 'Approved')
        ->sum('request_amount');

    // Payout amount deduct karna
    $dueAmount -= $payouts;

    return view('layouts.frontend', compact('wallet', 'dueAmount'));
    }

  
    public function storePayoutRequest(Request $request)
    {
        // Validate form data
        $request->validate([
            'order_id' => 'required|exists:check_orders,id',
            'request_amount' => 'required|numeric|min:1',
        ]);
    
        $user = Auth::user(); // Get logged-in user
        $order = CheckOrder::findOrFail($request->order_id); // Find selected order
    
        // Generate a random transaction ID
        $transactionId = strtoupper(Str::random(10));
    
        // Create the transaction record
        $transaction = Transaction::create([
            'order_number'     => $order->order_number, // Assuming `order_number` exists in CheckOrder model
            'order_id'         => $order->id,
            'transaction_id'   => $transactionId,
            'request_amount'   => $request->request_amount,
            'vendor_id'        => $user->id, // Logged-in user as vendor
            'status'           => 'pending', // Default status is 'pending'
            'transaction_type' => 'payout', // Transaction type as 'payout'
            'paid_amount'      => $request->request_amount, // Amount user is paying
            'total_amount'     => $order->total_amount, // Total amount of the order
            'created_by_id'    => $user->id, // Creator of the transaction (can be same as vendor)
        ]);
    
        // Optionally, you can redirect back with a success message
        return redirect()->back()->with('success', 'Payout request submitted successfully.');
    }

    public function showPayoutForm(Request $request)
    {
        $orders = CheckOrder::where('select_user_id', Auth::id())->where('payment_method', 'Credit Line')->latest()->get();
        $orders->each(function ($order) {
            $order->paid = Transaction::where('vendor_id', Auth::id())->where('order_id', $order->id)
                ->whereIn('transaction_type', ['payout','cash','cheque','bank_transfer','upi','other'])->where('status', 'success')->sum('request_amount');
            $order->remaining = max(0, $order->total_amount - $order->paid);
        });
        return view('wallet.repay-credit', compact('orders'));
    }
    
}
