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
                        <li ><a href="retailerdashboard.html" class="decoration"> <img src="asset/img/dashboard/dashboard.png" alt="" class="me-3 ">Dashboard</a></li>
                        <li ><a href="retailerprofile.html" class="decoration"><img src="asset/img/dashboard/orderhistory.png" alt="" class="me-3"> Place an Order</a></li>
                        <li><a href="{{ route('frontend.orders.index') }}" class="decoration"><img src="asset/img/dashboard/replacement.png" alt="" class="me-3">Order History</a></li>  
                        <li class="active"><a href="retailerorder.html" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
                        <li ><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/message.png" alt="" class="me-3">Support and Help</a></li>    
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/notification.png" alt="" class="me-3"> Notifications and Alerts</a></li>
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/profilesetting.png" alt="" class="me-3"> Profile Settings</a></li>
                        <li class=" text-white primary-bg"><a href="retailerreview.html" class="decoration text-white">Logout</a></li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-9 mb-3 complain ">
              <div class="row">
                <div class="col-12">
                    <div class=" px-3 py-2 ">
                <h4 class="fw-bold">Manage Profile</h4>

                
           
              </div>
            </div>

            <div class="col-12 mt-3">
              <div class="card py-3 px-lg-5 px-2" style="border: 1px solid #FFCDAD;">
                <div class="row">
                    <h3 class="mb-3 fw-bold">Basic Info</h3>
                    <div class="col-12 mb-3 " >
                      <img src="asset/img/dashboard/profile.png" alt="" class="img-fluid">
                      <p>Paul K. Jeneen</p>
                       
   </div>
    <div class="col-lg-2 mb-3 ">
        <span>Your Name</span>
    </div>
    <div class="col-lg-10 mb-3">
        <input type="text" class="form-control" placeholder="Paul K. Jeneen" name="" id="" value="Paul K. Jeneen">
    </div>

    <div class="col-lg-2 mb-3 ">
        <span>Phone Number</span>
    </div>

    <div class="col-lg-10 mb-3">
        <input type="text" class="form-control" placeholder="208-295-8053" name="" id="" value="208-295-8053">
    </div>
    <div class="col-lg-2 mb-3">
        <span>Photo</span>
    </div>
    <div class="col-10 mb-3">
        <input type="file" class="form-control" name="" id="" >

    </div>

    <div class="col-12 text-end">
        <button class="btn primary-bg text-white px-3 py-2">Update Profile</button>
    </div>

    </div>
    </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h4>Address</h4>
        </div>
      <div class="card py-3">
        <div class="col-12 mb-3">
            <div class="card py-3 px-lg-5 px-2" style="border: 1px solid #FFCDAD;">
                <div class="row">
                <div class="col-lg-8">
                        <div class="row">
                    <div class="col-lg-2 col-4 mb-3">
                        <span>Address</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                        <strong>3947 West Side Avenue Hackensack, NJ 07601</strong>
                    </div>
                    <div class="col-lg-2 col-4 mb-3">
                        <span>Pin</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                        <strong>1254</strong>
                    </div>
                    <div class="col-lg-2 col-4 mb-3">
                        <span>State</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                       <strong>Alaska</strong> 
                    </div>
                    <div class="col-lg-2 col-4 mb-3">
                        <span>City</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                        <strong>Anchorage</strong>
                    </div>
                    <div class="col-lg-2 col-4 mb-3">
                        <span>Country</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                      <strong> United States</strong> 
                    </div>
                    <div class="col-lg-2 col-4 mb-3">
                        <span>Phone</span>
                    </div>
                    <div class="col-lg-10 col-8 mb-3">
                        <strong>201-287-7714</strong>
                    </div>
                </div>
                </div>
                <div class="col-4">
                    <button class="btn primary-bg text-white px-3 py-2">Default</button>
                    
                </div>
            </div>
            </div>
        </div>



        <div class="col-12 mb-3">
          <div class="card py-3 px-lg-5 px-2" style="border: 1px solid #FFCDAD;">
              <div class="row">
              <div class="col-lg-8">
                      <div class="row">
                  <div class="col-lg-2 col-4 mb-3">
                      <span>Address</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                      <strong>3947 West Side Avenue Hackensack, NJ 07601</strong>
                  </div>
                  <div class="col-lg-2 col-4 mb-3">
                      <span>Pin</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                      <strong>1254</strong>
                  </div>
                  <div class="col-lg-2 col-4 mb-3">
                      <span>State</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                     <strong>Alaska</strong> 
                  </div>
                  <div class="col-lg-2 col-4 mb-3">
                      <span>City</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                      <strong>Anchorage</strong>
                  </div>
                  <div class="col-lg-2 col-4 mb-3">
                      <span>Country</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                    <strong> United States</strong> 
                  </div>
                  <div class="col-lg-2 col-4 mb-3">
                      <span>Phone</span>
                  </div>
                  <div class="col-lg-10 col-8 mb-3">
                      <strong>201-287-7714</strong>
                  </div>
              </div>
              </div>
              <div class="col-4">
                  <button class="btn primary-bg text-white px-3 py-2">Default</button>
                  
              </div>
          </div>
          </div>
      </div>

      <div class="col-12">
        <div class="card ">
                <button class="btn  text-dark fw-bold px-3 py-3" style="background-color:#D7D7D7;">
                  <i class="fa-solid fa-plus fs-4"></i><br>
                  Add New Address</button>
        </div>
      </div>
      </div>
      <div class="col-12 mt-3 text-end">
        <button class="btn primary-bg text-white px-5 py-3">save</button>
      </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    
   
      
</section>




@endsection