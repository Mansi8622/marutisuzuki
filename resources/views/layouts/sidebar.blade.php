<div class="col-lg-3 mb-3">
    <div class="dashboard-menu card">
        <div class="profile text-center pb-5">
            <div>
                <img src="{{ asset('storage/' . (Auth::user()->profile_photo ?? 'images/default-profile.png')) }}" alt="Profile Picture">
                <h6>Welcome, {{ Auth::user()->name }}</h6>
                <span>{{ Auth::user()->phone }}</span>
            </div>
            
        <ul class="list-unstyled">
            <li class="active"><a href="retailerdashboard.html" class="decoration"> <img src="asset/img/dashboard/dashboard.png" alt="" class="me-3 ">Dashboard</a></li>
            <li><a href="retailerprofile.html" class="decoration"><img src="asset/img/dashboard/orderhistory.png" alt="" class="me-3"> Place an Order</a></li>
            <li><a href="{{ route('frontend.orders.index') }}" class="decoration"><img src="asset/img/dashboard/replacement.png" alt="" class="me-3">Order History</a></li>  
            <li><a href="retailerorder.html" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
            <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/message.png" alt="" class="me-3">Support and Help</a></li>    
            <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/notification.png" alt="" class="me-3"> Notifications and Alerts</a></li>
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