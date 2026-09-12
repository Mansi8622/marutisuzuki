<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\CheckOrder;
use App\Models\Product;
use App\Models\OurStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController
{
    

    public function index()
    {
        $retailerCount = User::where('business_type', 'Retailer')->count();
        $manufacturerCount = User::where('business_type', 'Manufacturer')->count();
        $customerCount = Customer::count();
        $supplierCount = Supplier::count();
    
        $orderData = CheckOrder::selectRaw('DATE(created_at) as date, order_status as status, COUNT(*) as total')
            ->groupByRaw('DATE(created_at), order_status')
            ->orderBy('date', 'ASC')
            ->get();
    
        $products = Product::all();
    
        $productStocks = Product::with(['our_stocks' => function($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonth())->orderBy('created_at', 'ASC');
        }])->get();
    
        // ✅ Monthly stock data (total across all products)
        $monthlyStockData = OurStock::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(quantity_available) as total_quantity")
        )
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
        $walletRequestData = DB::table('wallet_requests')
        ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, vendor_id, status, SUM(due) as total_due')
        ->groupBy('year', 'month', 'vendor_id', 'status')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $vendors = DB::table('users')
        ->join('wallet_requests', 'users.id', '=', 'wallet_requests.vendor_id')
        ->select('users.id', 'users.name')
        ->distinct()
        ->get();


        $oneMonthAgo = Carbon::now()->subMonth();

        // Fetch income from check_orders
        $checkOrderIncome = DB::table('check_orders')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as amount')
            ->where('payment_method', 'razorpay')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', $oneMonthAgo)
            ->groupByRaw('DATE(created_at)')
            ->get();
    
        // Fetch income from transactions
        $transactionIncome = DB::table('transactions')
            ->selectRaw('DATE(created_at) as date, SUM(paid_amount) as amount')
            ->where('transaction_type', 'payout')
            ->where('created_at', '>=', $oneMonthAgo)
            ->groupByRaw('DATE(created_at)')
            ->get();
    
        $incomeData = collect($checkOrderIncome)
            ->merge($transactionIncome)
            ->groupBy('date')
            ->map(function ($items) {
                return ['date' => $items[0]->date, 'amount' => $items->sum('amount')];
            })->values();

    return view('home', compact(
        'retailerCount',
        'manufacturerCount',
        'customerCount',
        'supplierCount',
        'orderData',
        'products',
        'productStocks',
        'monthlyStockData',
        'walletRequestData',
        'vendors',
        'incomeData'
    ));
    }
}