<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|unique:customers,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('customer.login')->with('success', 'Registration successful. Please login.');
    }

    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        Log::info('Login attempt', ['phone' => $request->phone]);
    
        $user = Customer::where('phone', $request->phone)->first();
        if (!$user) {
            Log::warning('User not found', ['phone' => $request->phone]);
            return back()->withErrors(['userid' => 'User ID not found']);
        }
    
        if (!isset($user->password) || !Hash::check($request->password, $user->password)) {
            Log::warning('Invalid password', ['userid' => $user->id]);
            return back()->withErrors(['password' => 'Invalid password']);
        }
    
        // Make sure you're using the 'customer' guard
        Auth::guard('customer')->login($user);
        Log::info('User logged in', ['userid' => $user->id]);
    
        // Log session data to verify authentication
        Log::info('Session data after login', ['session' => session()->all()]);
    
        return redirect()->route('customer.dashboard');
    }
    

    public function dashboard()
    {
        return view('customer.dashboard');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }

    public function showProfileForm()
    {
        $customer = Auth::guard('customer')->user(); // Get the authenticated customer
        return view('customer.profile', compact('customer'));
    }

    // Update profile (photo and address)
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user(); // Get the authenticated customer

        // Validate the input fields
        $request->validate([
            'address' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional image upload
        ]);

        // Update the delivery address
        $customer->address = $request->address;

        // Handle profile photo update (if present)
        if ($request->hasFile('profile_photo')) {
            // Delete the old profile photo if it exists
            if ($customer->profile_photo && Storage::exists($customer->profile_photo)) {
                Storage::delete($customer->profile_photo);
            }

            // Store the new profile photo
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $customer->profile_photo = $path; // Save the path to the database
        }

        // Save updated customer information
        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }
    

    
}