<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Hash;

class UsersApiController extends Controller
{
    use MediaUploadingTrait;

    
    
    
    // ============================================
    // ✅ 1. User Registration API
    // ============================================
    public function UserRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "phone" => "required|unique:users,phone",
            "password" => "required|min:6",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => Hash::make($request->password),
            "business_name" => $request->business_name,
            "business_type" => $request->business_type,
            "gst_number" => $request->gst_number,
            "pan_number" => $request->pan_number,
            "business_address" => $request->business_address,
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered successfully",
            "data" => $user,
        ], 201);
    }

    // ============================================
    // ✅ 2. User Login API
    // ============================================
    
    public function UserLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "login_id" => "required", 
            "password" => "required"
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ], 422);
        }
    
        $loginId = $request->login_id;
    
        // Load roles relation
        $userQuery = User::with("roles");
    
        // Email or Phone login
        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            $userQuery->where("email", $loginId);
        } else {
            $userQuery->where("phone", $loginId);
        }
    
        $user = $userQuery->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ], 401);
        }
    
        $token = $user->createToken("api_token")->plainTextToken;
    
        return response()->json([
            "status" => true,
            "message" => "Login successful",
            "token" => $token,
    
            "user" => [
                "id" => $user->id,   // 👈 user id added here
    
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
    
                "approved" => $user->approved,
    
                "business_name" => $user->business_name,
                "business_type" => $user->business_type,
                "gst_number" => $user->gst_number,
                "pan_number" => $user->pan_number,
                "business_address" => $user->business_address,
    
                "bank_name" => $user->bank_name,
                "account_number" => $user->account_number,
                "ifsc_code" => $user->ifsc_code,
                "account_holder_name" => $user->account_holder_name,
    
                "license_details" => $user->license_details,
    
                "status" => $user->status,
                "vendor" => $user->vendor,
    
                // Roles from pivot table
                "roles" => $user->roles->pluck("title"),
            ]
        ]);
    }






    // ============================================
    // ✅ 3. Get User by ID API
    // ============================================
    public function getUserById($id)
    {
        $user = User::with('roles')->find($id);
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        return new UserResource($user);
    }



    // ============================================
    // ✅ 4. Upload Profile Photo (Without Auth)
    // ============================================
    public function uploadProfilePhoto(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ], 404);
        }

        if (!$request->hasFile("photo")) {
            return response()->json([
                "status" => false,
                "message" => "No file uploaded"
            ], 400);
        }

        $user->clearMediaCollection("profile_photo");
        $media = $user->addMedia($request->file("photo"))
                      ->toMediaCollection("profile_photo");

        return response()->json([
            "status" => true,
            "message" => "Profile photo uploaded",
            "photo_url" => $media->getUrl()
        ]);
    }

    // ============================================
    // ✅ 5. Password Reset Link (Fake for Mobile)
    // ============================================
    public function sendPasswordResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ], 422);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Email not registered"
            ], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Password reset link sent to email (dummy)"
        ]);
    }
    
    
    
}
