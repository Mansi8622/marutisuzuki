@php
    use App\Models\Verification;
    use Illuminate\Support\Facades\Auth;

    // Use custom guard if you have one, otherwise default
    $customer = Auth::guard('customer')->user(); // use 'customer' guard

    $verification = null;

    if ($customer) {
        $verification = Verification::where('customer_id', $customer->id)->first();
    }
@endphp


   


<div class="col-lg-3 mb-3">
    <div class="dashboard-menu card">
        <div class="profile text-center pb-5">
            <img src="{{ asset('storage/' . Auth::guard('customer')->user()->profile_photo ) }}" alt="">
            <h6>Welcome, {{ Auth::guard('customer')->user()->name }}</h6>
            <span >{{ Auth::guard('customer')->user()->phone }}</span><br>
            @if(!$customer)
        <div class="alert alert-warning">
            Please log in to see your verification details.
        </div>

    @elseif($verification)
        <p class="badge bg-success text-white"> {{ $verification->verification_status }}</p>

        

    @else
        <div class="alert alert-info">
            No verification data found.
        </div>
    @endif
        </div>
        <ul class="list-unstyled">
            <li class="active"><a href="{{ route('customer.dashboard') }}" class="decoration"> <img src="asset/img/dashboard/dashboard.png" alt="" class="me-3 ">Dashboard</a></li>
            <li><a href="{{ route('frontend.customer-orders.index') }}" class="decoration"><img src="asset/img/dashboard/replacement.png" alt="" class="me-3">Order History</a></li>  
            <li><a href="{{ route('frontend.customer-replacements.index') }}" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
            <li><a href="{{ route('customer.profile') }}" class="decoration"><img src="asset/img/dashboard/profilesetting.png" alt="" class="me-3"> Profile Settings</a></li>
            <li class=" text-white primary-bg">    
                <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>