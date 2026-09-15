<?php
namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\Address;
use App\Models\User;
use App\Models\Customer;
use App\Models\WalletRequest;
use App\Models\Offer;

class DeliveryController extends Controller
{

    public function index()
    {
        $cartItems = session('cart', []);
        $totalPrice = 0;
        $totalDiscount = 0;
        $finalPrice = 0;
        $price_1 = 0; // Initialize price_1
        
        foreach ($cartItems as &$item) {
            $item['discount'] = $item['discount'] ?? 0;
            $item['price_1'] = $item['price_1'] ?? $item['price']; // Default to MRP if not set
        
            // Calculate discounted price
            $item['discountedPrice'] = $item['price'] - ($item['price'] * $item['discount'] / 100);
        
            if (Auth::check() && Auth::guard('web')->user()) {
                // If logged-in as 'web' user, use price_1
                $item['finalPrice'] = $item['price_1'];
                $price_1 += $item['price_1'] * $item['quantity'];
            } else {
                // For guests, use discounted price
                $item['finalPrice'] = $item['discountedPrice'];
            }
        
            // Update totals
            $totalPrice += $item['price'] * $item['quantity'];
            $totalDiscount += ($item['price'] * $item['discount'] / 100) * $item['quantity'];
        }
        
        // Calculate delivery fee
        $deliveryFee = Auth::check() ? 0 : ($totalPrice > 500 ? 0 : 50);
        
        // Calculate final price
        $finalPrice = $price_1 > 0 ? $price_1 + $deliveryFee : ($totalPrice - $totalDiscount + $deliveryFee);
        
        $orderSummary = [
            'totalPrice' => $totalPrice,
            'discount' => $totalDiscount,
            'deliveryFee' => $deliveryFee,
            'finalPrice' => $finalPrice,
            'price_1' => $price_1, // Add price_1 to orderSummary
        ];
        
        // Fetching the current logged-in user with their address
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user()->load('address'); // Load user data
        } elseif (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user()->load('address'); // Load customer data
        } else {
            $user = null; // No authenticated user
        }
    
        // Credit line belongs to the user, not to their address.  Address may be
        // incomplete during checkout, but an approved credit line must still work.
        $wallet = $user && Auth::guard('web')->check()
            ? WalletRequest::where('vendor_id', $user->id)->latest()->first()
            : null;

        if ($user && $user->address) {
            // Now you can safely use pluck() if the address exists
            $addressIds = $user->address->pluck('id');
        } else {
            // Handle case where there is no address
            $addressIds = null;
        }
    
        // Offers are deliberately sent only to the online-payment flow. Credit
        // line orders must always use their full invoice amount.
        $offers = Offer::available()
            ->where('minimum_order_amount', '<=', $finalPrice)
            ->orderByDesc('discount_percent')
            ->get();

        return view('custom.delivery', compact('user', 'cartItems', 'orderSummary', 'wallet', 'offers'));
    }
    
   
    public function store(Request $request)
{
    // Validate the input fields
    $request->validate([
        'country' => 'required|string',
        'pin_code' => 'nullable',
        'state' => 'required|string',
        'district' => 'required|string',
        'full_address' => 'required|string',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check if a user or customer is authenticated
    $user = auth('web')->user();
  
    $customer = auth('customer')->user();
    
    if (!$user && !$customer) {
        return redirect()->back()->with('error', 'Unauthorized access.');
    }

    // Prepare address data
    $addressData = [
        'country' => $request->country,
        'state' => $request->state,
        'district' => $request->district,
        'full_address' => $request->full_address,
        'pin_code' => $request->pin_code,
    ];

    if ($user) {
        $addressData['user_id'] = $user->id;
        $addressData['customer_id'] = null; // Ensure no conflict
        $existingAddress = Address::where('user_id', $user->id)->first();
    } elseif ($customer) {
        $addressData['customer_id'] = $customer->id;
        $addressData['user_id'] = null; // Fix: avoid foreign key error
        $existingAddress = Address::where('customer_id', $customer->id)->first();
    }

    // Update or create the address
    if ($existingAddress) {
        $existingAddress->update($addressData);
    } else {
        Address::create($addressData);
    }

    // Handle profile photo upload for customer
    if ($customer && $request->hasFile('profile_photo')) {
        // Store the profile photo
        $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');

        // Update customer profile photo
        $customer->profile_photo = $profilePhotoPath;
        $customer->save();
    }

    return redirect()->back()->with('success', 'Address and profile photo updated successfully!');
}

    

    public function profile()
    {
        $customer = auth('customer')->user();
    
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please login first.');
        }
    
        $address = Address::where('customer_id', $customer->id)->first();
    
        return view('customer.profile', compact('customer', 'address'));
    }
    

    
    

}
