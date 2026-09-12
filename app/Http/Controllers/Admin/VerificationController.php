<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Verification;

class VerificationController extends Controller
{
   
    
    

    public function massDestroy(Request $request)
{
    Verification::whereIn('id', $request->ids)->delete();

    return response()->json(['success' => "Selected verifications deleted successfully."]);
}
public function index()
{
    $verifications = \App\Models\Verification::latest()->get();
    return view('admin.verifications.index', compact('verifications'));
}

public function show($id)
{
    $verification = Verification::findOrFail($id);
    return view('admin.verifications.show', compact('verification'));
}
public function edit($id)
{
    $verification = Verification::findOrFail($id);
    return view('admin.verifications.edit', compact('verification'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'number' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'aadhar_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'pan_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'reseller_code' => 'required|string',
        'verification_status' => 'required|in:verified,pending,rejected',
    ]);

    $verification = Verification::findOrFail($id);
    $verification->name = $request->name;
    $verification->email = $request->email;
    $verification->number = $request->number;

    if ($request->hasFile('image')) {
        $verification->image = $request->file('image')->store('uploads/profile', 'public');
    }
    if ($request->hasFile('aadhar_image')) {
        $verification->aadhar_image = $request->file('aadhar_image')->store('uploads/aadhar', 'public');
    }
    if ($request->hasFile('pan_image')) {
        $verification->pan_image = $request->file('pan_image')->store('uploads/pan', 'public');
    }

    $verification->reseller_code = $request->reseller_code;
    $verification->verification_status = $request->verification_status;
    $verification->save();

    return redirect()->route('admin.verifications.index')->with('success', 'Verification updated successfully!');
}

public function destroy($id)
{
    $verification = Verification::findOrFail($id);
    $verification->delete();

    return redirect()->route('admin.verifications.index')->with('success', 'Verification record deleted successfully.');
}



}
