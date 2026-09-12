@extends('custom.master')

@section('content')


<section class="dashboard py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-3">
                <div class="dashboard-menu card">
                    <div class="profile text-center pb-5">
                        <img src="asset/img/dashboard/profile.png" alt="">
                        <h6>Paul K. Jeneen</h6>
                        <span >208-295-8053</span>
                    </div>
                    <ul class="list-unstyled">
                        <li class="active"><a href="retailerdashboard.html" class="decoration"> <img src="asset/img/dashboard/dashboard.png" alt="" class="me-3 ">Dashboard</a></li>
                        <li><a href="retailerprofile.html" class="decoration"><img src="asset/img/dashboard/orderhistory.png" alt="" class="me-3"> Place an Order</a></li>
                        <li><a href="{{ route('frontend.orders.index') }}" class="decoration"><img src="asset/img/dashboard/replacement.png" alt="" class="me-3">Order History</a></li>  
                        <li><a href="retailerorder.html" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/message.png" alt="" class="me-3">Support and Help</a></li>    
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/notification.png" alt="" class="me-3"> Notifications and Alerts</a></li>
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/profilesetting.png" alt="" class="me-3"> Profile Settings</a></li>
                        <li class=" text-white primary-bg"><a href="retailerreview.html" class="decoration text-white">Logout</a></li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-9 mb-3">
              <div class="row">

                <h3>Check your Credit line history</h3>
                <div class="input-group mt-3 " style="width: 90%;">
                    <input type="text" name="" id="" class="form-control " style="max-width: 90%;" placeholder="Search">
                    <button class="btn primary-bg text-white input-group-text" ><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>

                  <div class=" d-flex justify-content-between mt-2 px-4 py-2" style="background: #F9F0F3;">
                    <p style="font-weight: 600;">Current Credit Line balance</p>
                    <p style="font-weight: 700;" class="primary">₹ 50000</p>
                  </div>

                  <div class="history">
                    <ul>
                        <li class="d-flex justify-content-between align-items-center py-3 pe-3">
                            

                            <div class="d-flex align-items-center">
                                <img src="asset/img/dashboard/profile.png" alt="">
                                <div class="ps-5">
                                <span>Product Name: Asfhfa</span><br>
                                <span>20 October</span>
                            </div>
                            </div>

                            <div class="">
                                <p class="primary">₹ 5000</p>
                            </div>
                        </li>

                        <li class="d-flex justify-content-between align-items-center py-3 pe-3">
                            

                            <div class="d-flex align-items-center">
                                <img src="asset/img/dashboard/profile.png" alt="">
                                <div class="ps-5">
                                <span>Product Name: Asfhfa</span><br>
                                <span>20 October</span>
                            </div>
                            </div>

                            <div class="">
                                <p class="primary">₹ 5000</p>
                            </div>
                        </li>


                        <li class="d-flex justify-content-between align-items-center py-3 pe-3">
                            

                            <div class="d-flex align-items-center">
                                <img src="asset/img/dashboard/profile.png" alt="">
                                <div class="ps-5">
                                <span>Product Name: Asfhfa</span><br>
                                <span>20 October</span>
                            </div>
                            </div>

                            <div class="">
                                <p class="primary">₹ 5000</p>
                            </div>
                        </li>


                        <li class="d-flex justify-content-between align-items-center py-3 pe-3">
                            

                            <div class="d-flex align-items-center">
                                <img src="asset/img/dashboard/profile.png" alt="">
                                <div class="ps-5">
                                <span>Product Name: Asfhfa</span><br>
                                <span>20 October</span>
                            </div>
                            </div>

                            <div class="">
                                <p class="primary">₹ 5000</p>
                            </div>
                        </li>


                    </ul>
                  </div>


              
                
            </div>

              
            </div>
        </div>
    </div>
</section>



@endsection