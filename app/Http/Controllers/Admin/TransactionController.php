<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WalletRequest;
use Illuminate\Http\Request;
use PDF;
use App\Models\User;
use App\Models\CheckOrder;


class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('vendor')->orderBy('created_at', 'desc')->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Check if status is 'approved'
        if ($request->status === 'approved') {
            // Update transaction status
            $transaction->status = 'approved';
            $transaction->save();

            // Get the wallet request for the vendor
            $wallet = WalletRequest::where('vendor_id', $transaction->vendor_id)->first();

            if ($transaction->transaction_type === 'request-amount') {
                // If transaction type is request_amount, increase welcome_amount and add to due_amount
                $wallet->welcome_amount += $transaction->request_amount;
                $wallet->due += $transaction->request_amount; // Add to due_amount as well
                $wallet->save();

                return redirect()->route('admin.transactions.index')->with('success', 'Transaction approved successfully and amount added to wallet!');
            } elseif ($transaction->transaction_type === 'payout') {
                // If transaction type is payout, decrease due_amount
                if ($wallet->due >= $transaction->request_amount) {
                    $wallet->due -= $transaction->request_amount; // Subtract from due_amount
                    $wallet->save();

                    return redirect()->route('admin.transactions.index')->with('success', 'Payout transaction approved successfully, and due amount reduced!');
                } else {
                    // If due_amount is less than the payout amount, return an error
                    return redirect()->route('admin.transactions.index')->with('error', 'Insufficient due amount for payout.');
                }
            }
        } elseif ($request->status === 'rejected') {
            $transaction->status = 'rejected';
            $transaction->save();

            return redirect()->route('admin.transactions.index')->with('success', 'Transaction rejected successfully!');
        }
    }
    public function downloadPDF()
    {
        $wallet = WalletRequest::where('vendor_id', auth()->id())->first();
        $transactions = Transaction::where('vendor_id', auth()->id())->orderBy('created_at', 'desc')->get();

        return view('wallet.pdf', compact('wallet', 'transactions'));
    }
    
    public function create()
    {
        $vendors = User::all(); // all users are considered vendors
        return view('admin.manualPayment.create', compact('vendors'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:cash,cheque,bank_transfer,upi,other,payout',
            'order_id' => 'required|array',
            'order_id.*' => 'exists:check_orders,id',
            'paid_amount' => 'required|array',
            'paid_amount.*' => 'required|numeric|min:1', // Ensure each amount is numeric and > 0
        ]);
    
        $vendorId = $request->vendor_id;
        $orderIds = $request->order_id;
        $paidAmounts = $request->paid_amount; // This is now an array of amounts
        
        // Fetch the wallet
        $walletRequest = WalletRequest::where('vendor_id', $vendorId)->latest()->first();
    
        if (!$walletRequest) {
            return back()->withErrors(['wallet' => 'Vendor does not have a wallet record.']);
        }
    
        $totalPaidAmount = 0;
    
        // Loop over the orders and amounts
        foreach ($orderIds as $index => $orderId) {
            $order = CheckOrder::find($orderId);
            if (!$order) continue;
    
            $paidAmount = $paidAmounts[$index]; // Get the corresponding paid amount for the order
    
            // Create a transaction for each order with the individual paid amount
            Transaction::create([
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'transaction_id' => 'MANUAL-' . strtoupper(uniqid()),
                'request_amount' => $paidAmount,
                'paid_amount' => $paidAmount,
                'total_amount' => $paidAmount,
                'vendor_id' => $vendorId,
                'transaction_type' => $request->transaction_type,
                'status' => 'success',
                'created_by_id' => auth()->id(),
                'notes' => $request->notes,
            ]);
    
            // Update the total paid amount
            $totalPaidAmount += $paidAmount;
        }
    
        // Update the wallet only once with the total paid amount
        $walletRequest->welcome_amount += $totalPaidAmount;
        $walletRequest->due = max(0, $walletRequest->due - $totalPaidAmount);
        $walletRequest->save();
    
        return redirect()->route('admin.transactions.index')->with('success', 'Manual payment added successfully.');
    }
    
    
    

    public function getDueOrders($vendorId)
    {
        $orders = CheckOrder::where('select_user_id', $vendorId)->get();
    
        $ordersWithRemaining = $orders->map(function ($order) {
            $paidAmount = Transaction::whereIn('transaction_type', ['cash', 'cheque', 'bank_transfer', 'upi', 'other', 'payout'])
            ->where(function ($query) use ($order) {
                $query->where('order_id', $order->id)
                      ->orWhere('order_number', $order->order_number);
            })
            ->sum('request_amount');
        
    
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'paid_amount' => $paidAmount,
                    'remaining_amount' => $order->total_amount - $paidAmount,
                    'created_at' => $order->created_at->toDateTimeString(), // Include formatted created_at
                ];
        });
    
        return response()->json($ordersWithRemaining);
    }
    

}
