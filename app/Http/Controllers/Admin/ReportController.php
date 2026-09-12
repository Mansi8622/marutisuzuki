<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WalletRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
  

    public function showReport(Request $request)
    {
        $vendors = WalletRequest::whereIn('status', ['Active', 'Suspended'])
            ->select('vendor_id', 'status')
            ->distinct()
            ->get();
    
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $vendorId = $request->input('vendor_id');
    
        if ($vendorId && $startDate && $endDate) {
            // Convert start and end dates to Carbon instances
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
    
            // Get the wallet for the selected vendor
            $wallet = WalletRequest::where('vendor_id', $vendorId)->first();
    
            // Get transactions for the selected vendor within the date range
            $transactions = Transaction::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
    
            $totalTransactionAmount = $transactions->sum('request_amount');
            $transactionCount = $transactions->count();
    
            return view('admin.reports.index', compact(
                'vendors', 'wallet', 'transactions', 'totalTransactionAmount', 'transactionCount', 'startDate', 'endDate', 'vendorId'
            ));
        }
    
        return view('admin.reports.index', compact('vendors', 'startDate', 'endDate', 'vendorId'));
    }
    
    
}
