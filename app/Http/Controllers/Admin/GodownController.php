<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Godown;
use App\Models\ProductStockTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Import the base Controller class

class GodownController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $godowns = Godown::all(); // Fetch all godowns
        return view('admin.godown.index', compact('godowns'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('admin.godown.create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'integer',
            'status' => 'required|string'
        ]);

        Godown::create($request->all());

        return redirect()->route('admin.godowns.index')->with('success', 'Godown created successfully.');
    }

    // Display the specified resource
    public function show(Godown $godown)
    {
        return view('admin.godown.show', compact('godown'));
    }

    // Show the form for editing the specified resource
    public function edit(Godown $godown)
    {
        return view('admin.godown.edit', compact('godown'));
    }

    // Update the specified resource in storage
    public function update(Request $request, Godown $godown)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'integer',
            'status' => 'required|string'
        ]);

        $godown->update($request->all());

        return redirect()->route('admin.godowns.index')->with('success', 'Godown updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(Godown $godown)
    {
        $godown->delete();
        return redirect()->route('admin.godowns.index')->with('success', 'Godown deleted successfully.');
    }

    // GodownController.php


    public function infoPage()
    {
        $godowns = Godown::all();
        return view('admin.godown.info', compact('godowns'));
    }
    
    public function getDetails($id)
    {
        $godown = Godown::with(['products.ourStock', 'products.companies'])->findOrFail($id);
    
        $products = $godown->products->map(function ($product) {
            $companyNames = $product->companies->pluck('company_name')->join(', ');
            $ourStockQty = optional($product->ourStock)->quantity_available ?? 0;
  
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $ourStockQty,
                'company_name' => $companyNames ?: 'N/A',
                'item_code' => $product->item_code,
                'hsn_code' => $product->hsn_code,
                'price' => $product->price,
            ];
        });
    
        return response()->json([
            'location' => $godown->location,
            'capacity' => $godown->capacity,
            'product_count' => $products->count(),
            'products' => $products,
        ]);
    }
    
    

public function transfer(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id', // Ensure product exists in the products table
        'from_godown' => 'required|exists:godowns,id', // Ensure the from_godown exists
        'to_godown' => 'required|exists:godowns,id|different:from_godown', // Ensure the to_godown exists and is different from the from_godown
        'quantity' => 'required|integer|min:1', // Ensure quantity is a valid number
    ]);

    $product = Product::findOrFail($request->product_id);

    // Check quantity in source godown
    $from = $product->godowns()->where('godown_id', $request->from_godown)->first();

    if (!$from || $from->pivot->quantity < $request->quantity) {
        return back()->with('error', 'Insufficient quantity in the selected godown.');
    }

    // Deduct from source
    $product->godowns()->updateExistingPivot($request->from_godown, [
        'quantity' => $from->pivot->quantity - $request->quantity
    ]);

    // Add to destination
    $to = $product->godowns()->where('godown_id', $request->to_godown)->first();

    if ($to) {
        $product->godowns()->updateExistingPivot($request->to_godown, [
            'quantity' => $to->pivot->quantity + $request->quantity
        ]);
    } else {
        $product->godowns()->attach($request->to_godown, ['quantity' => $request->quantity]);
    }

    // Log the transfer in the stock transfer table
    \App\Models\ProductStockTransfer::create([
        'product_id' => $request->product_id,
        'from_godown_id' => $request->from_godown,
        'to_godown_id' => $request->to_godown,
        'quantity' => $request->quantity,
        'transfer_date' => now(),
    ]);

    return back()->with('success', 'Product transferred successfully.');
}

    
    
    
    
    
    
    

}
