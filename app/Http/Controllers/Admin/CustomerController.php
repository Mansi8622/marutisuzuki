<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\MassDestroyCustomerRequest;


class CustomerController extends Controller
{
    public function index()
{
    // Fetch all customers with their associated address
    $customers = Customer::with('address')->get();

    return view('admin.customers.index', compact('customers'));
}


    public function show(Customer $customer)
    {
        // Fetch a single customer's details and pass it to the view
        return view('admin.customers.show', compact('customer'));
    }

    public function create()
    {
        // Return the view to create a new customer
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        // Validate the request data for creating a new customer
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|unique:customers,phone',
            'password' => 'required|min:6',
        ]);

        // Create the new customer
        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Encrypt password
        ]);

        // Redirect to customers index page with success message
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        // Return the view to edit an existing customer
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Validate the request data for updating the customer
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|unique:customers,phone,' . $customer->id,
        ]);

        // Update the customer with the provided data
        $customer->update($request->only(['name', 'email', 'phone']));

        // Redirect to customers index page with success message
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        // Delete the customer
        $customer->delete();

        // Redirect to customers index page with success message
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
{
    // Get the customer IDs from the request
    $customerIds = $request->input('ids');

    // Find the customers by their IDs
    $customers = Customer::find($customerIds);

    // Loop through and delete each customer
    foreach ($customers as $customer) {
        $customer->delete();
    }

    // Return a successful response with no content
    return response(null, Response::HTTP_NO_CONTENT);
}
}
