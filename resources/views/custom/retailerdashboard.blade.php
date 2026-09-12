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
                <div class="col-12">
                  <div class="card p-3 ">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="">
                    <h3>  Dashboard</h3>
                  </div>
                    <div class="input-group mx-auto d-lg-none d-none" style="max-width: 300px;">
                    <input type="text" name="" id="" class="form-control " style="max-width: 300px;" placeholder="Search">
                    <button class="btn primary-bg text-white input-group-text" ><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                  <div class="">
                  <i class="fa-regular fa-bell"></i>
                </div>
                </div>
              </div>

              </div>
              <div class="row">
              <div class="col-12 mt-3 text-md-end ">
                <a href="" class="decoration text-white ">   <div class="btn primary-bg text-white px-5 py-2 mb-3">
                  Verify Account 
                </div></a>
              <a href="" class="decoration text-white">  <div class="btn primary-bg text-white px-5 py-2 mb-3">
                  Apply for Credit line
                </div></a>

              </div>
            </div>


              </div>
          
              <div class="row mt-3">
              <div class="col-lg-3 col-6 mb-3">
                <div class="card border-0" style="background: #FFE9E5;">
                  <div class="card-body text-center py-2">
                    <img src="asset/img/dashboard/product.png" alt="" class="mt-5"> 
                    <h6 class="mt-3">Total Orders</h6>
                    <h5>  25,000</h5>
                  </div>
                </div>
                </div>


                <div class="col-lg-3 col-6 mb-3">
                  <div class="card border-0" style="background: #FFE9E5;">
                    <div class="card-body text-center py-2">
                      <img src="asset/img/dashboard/product.png" alt="" class="mt-5"> 
                      <h6 class="mt-3">Pending Orders</h6>
                      <h5>  5,000</h5>
                    </div>
                  </div>
                  </div>

                  

                  <div class="col-lg-3 col-6 mb-3">
                    <div class="card border-0" style="background: #FFE9E5;">
                      <div class="card-body text-center py-2">
                        <img src="asset/img/dashboard/cart.png" alt="" class="mt-5"> 
                        <h6 class="mt-3">Credit Available</h6>
                        <h5>  25,000</h5>
                      </div>
                    </div>
                    </div>

                    
                    <div class="col-lg-3 col-6 mb-3">
                      <div class="card border-0" style="background: #FFE9E5;">
                        <div class="card-body text-center py-2">
                          <img src="asset/img/dashboard/rupee.png" alt="" class="mt-5"> 
                          <h6 class="mt-3">Dues</h6>
                          <h5>  1,234</h5>
                        </div>
                      </div>
                      </div>
      

                
            </div>

              
            </div>
        </div>
    </div>
</section>


@endsection