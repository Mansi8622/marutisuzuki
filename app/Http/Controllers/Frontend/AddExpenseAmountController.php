<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AddAmount;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // To use Auth facade
use App\Models\User;

class AddExpenseAmountController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get the AddAmount entries for the logged-in user with business_type 'Sells Man'
        $addAmounts = AddAmount::where('user_id', $user->id) // Filter by the logged-in user
            ->get();

        // Get the total approved amount for the logged-in user
        $totalApprovedAmount = $addAmounts->where('status', 'approve')->sum('amount');

        // Get the total expenses for the logged-in user
        $totalUsedAmount = Expense::where('user_id', $user->id)->sum('amount');

        // Calculate the remaining amount (approved amount - used amount)
        $remainingAmount = $totalApprovedAmount - $totalUsedAmount;

        // Pass the data to the view
        return view('frontend.addexpenseamount.index', compact('addAmounts', 'totalApprovedAmount', 'remainingAmount'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        $users = User::where('business_type', 'Sells Man')->pluck('name', 'id');
        return view('frontend.addexpenseamount.create', compact('users'));
    }

    // Store a newly created resource in storage
   public function store(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric',
        'description' => 'nullable|string|max:255',
    ]);

    AddAmount::create([
        'user_id' => auth()->id(),
        'amount' => $request->amount,
        'description' => $request->description,
        'status' => 'pending', // Default to 'pending' without showing in the form
    ]);

    return redirect()->route('frontend.add-amounts.index');
}


    // Show the specified resource
    public function show($id)
    {
        $addAmount = AddAmount::with('user')->findOrFail($id);
        return view('frontend.addexpenseamount.show', compact('addAmount'));
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        $data = AddAmount::findOrFail($id);
        return view('frontend.addexpenseamount.edit', compact('data'));
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:approve,pending,reject',
        ]);

        $data = AddAmount::findOrFail($id);
        $data->update([
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('frontend.add-amounts.index');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $data = AddAmount::findOrFail($id);
        $data->delete();

        return redirect()->route('frontend.add-amounts.index');
    }
}

