<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;

class VerificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'number' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'aadhar_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'pan_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'reseller_code' => 'required|string',
            'verification_status' => 'required|in:verified,pending,rejected',
        ]);

        try {
            // Use custom guard 'customer'
            $customer = auth('customer')->user();

            if (!$customer) {
                return back()->with('error', 'You must be logged in to verify your account.');
            }

            $verification = new Verification();
            $verification->customer_id = $customer->id;
            $verification->name = $request->name;
            $verification->email = $request->email;
            $verification->number = $request->number;
            $verification->image = $request->file('image')->store('uploads/profile', 'public');
            $verification->aadhar_image = $request->file('aadhar_image')->store('uploads/aadhar', 'public');
            $verification->pan_image = $request->file('pan_image')->store('uploads/pan', 'public');
            $verification->reseller_code = $request->reseller_code;
            $verification->verification_status = $request->verification_status ?? 'pending';
            $verification->save();

            return back()->with('success', 'Verification submitted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
}
