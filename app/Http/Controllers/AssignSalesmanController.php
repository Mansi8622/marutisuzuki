<?php

namespace App\Http\Controllers;

use App\Models\AssignSalesman;
use App\Models\User;
use Illuminate\Http\Request;

class AssignSalesmanController extends Controller
{
    // Display all salesmen
  public function index()
{
    $assignSalesmen = AssignSalesman::with('user')->get();
    return view('admin.assignSalesman.index', compact('assignSalesmen'));
}


    // Show form to create a new salesman
    public function create()
    {
        // Get all users with 'Retailer' as business type to assign as a retailer
        $users = User::where('business_type', 'Retailer')->get();
        // Return the create view with the users
        return view('admin.assignSalesman.create', compact('users'));
    }

    // Store a new salesman in the database
   public function store(Request $request)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|unique:assign_salesmen,email',
        'number'  => 'required|string|max:20',
        'user_id' => 'required|exists:users,id',  // Ensure the user_id is a valid user
        'image'   => 'nullable|image',   // Validate the image upload (optional)
    ]);

    // Create the AssignSalesman instance with validated data (without the image for now)
    $assignSalesman = AssignSalesman::create($validated);

    // Handle image upload if file is present
    if ($request->hasFile('image')) {
        // Use Spatie Media Library to store the image in the 'profile_image' collection
        $assignSalesman->addMediaFromRequest('image')
                       ->toMediaCollection('profile_image');  // This will save the image to the 'profile_image' collection
    }

    // Redirect to the salesmen list with a success message
    return redirect()->route('admin.assign-salesmen.index')->with('success', 'Salesman assigned successfully!');
}


    // Show form to edit an existing salesman
    public function edit(AssignSalesman $assignSalesman)
    {
        // Get all users with 'Retailer' as business type
        $users = User::where('business_type', 'Retailer')->get();
        // Return the edit view with the existing salesman data and users
        return view('admin.assignSalesman.edit', compact('assignSalesman', 'users'));
    }

    // Update the salesman details in the database
    public function update(Request $request, AssignSalesman $assignSalesman)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:assign_salesmen,email,' . $assignSalesman->id,  // Exclude current email
            'number'  => 'required|string|max:20',
            'user_id' => 'required|exists:users,id',
            'image'   => 'nullable|image|max:2048',   // Validate the image upload (optional)
        ]);

        // Handle image upload if a new file is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('salesman_images', 'public');
            $validated['image'] = $path;  // Save the new image path to validated data
        }

        // Update the existing AssignSalesman record with validated data
        $assignSalesman->update($validated);

        // Redirect to the salesmen list with a success message
        return redirect()->route('admin.assign-salesmen.index')->with('success', 'Salesman updated successfully!');
    }

    // Delete the selected salesman
    public function destroy(AssignSalesman $assignSalesman)
    {
        // Delete the salesman from the database
        $assignSalesman->delete();

        // Redirect to the salesmen list with a success message
        return redirect()->route('admin.assign-salesmen.index')->with('success', 'Salesman deleted successfully!');
    }

    public function massDestroy(Request $request)
{
    // Validate the request to ensure IDs are provided
    $this->authorize('assign_salesman_delete'); // if you have permission checks

    // Get the array of IDs from the request
    $ids = $request->input('ids');

    // Find and delete all salesmen by IDs
    AssignSalesman::whereIn('id', $ids)->delete();

    return response()->json(['message' => 'Salesmen deleted successfully.']);
}


public function show(AssignSalesman $assignSalesman)
{
    return view('admin.assignSalesman.show', compact('assignSalesman'));
}



}
