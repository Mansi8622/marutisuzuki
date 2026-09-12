<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddAmount; // Or the model you're using for adding amounts
use Illuminate\Http\Request;

use App\Models\User; // Assuming you have a User model
class AddExpenseAmountController extends Controller
{
    // Ensure that the user has the required permission
   

    // Display a listing of the resource
   public function index()
{
    $addAmounts = AddAmount::with('user')->get(); // Make sure to eager load the user
    return view('admin.addexpenseamount.index', compact('addAmounts'));
}


    // Show the form for creating a new resource
  public function create()
{
    // Only users with business_type = 'Sells'
    $users = User::where('business_type', 'Sells Man')->pluck('name', 'id');

    return view('admin.addexpenseamount.create', compact('users'));
}



    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:approve,pending,reject',
        ]);

        // Create a new AddAmount record
        AddAmount::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.add-amounts.index');
    }

    // Display the specified resource
   public function show($id)
{
    $addAmount = AddAmount::with('user')->findOrFail($id);
    return view('admin.addexpenseamount.show', compact('addAmount'));
}


    // Show the form for editing the specified resource
    public function edit($id)
    {
        $data = AddAmount::findOrFail($id);
        return view('admin.addexpenseamount.edit', compact('data'));
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

        return redirect()->route('admin.add-amounts.index');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $data = AddAmount::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.add-amounts.index');
    }
}
