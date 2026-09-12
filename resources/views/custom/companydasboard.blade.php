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
               
                    <div class="d-lg-flex  align-items-center">
                      <div class="card text-center me-3 py-3 px-4 mb-3">
                        <h3>Total Complaints</h3>
                        <h5 class="fw-bold">854</h5>
                   
                  </div>
                      <div class="card text-center me-3 py-3 px-4">
                        <h3>New Complaints</h3>
                        <h5 class="fw-bold">22</h5>
                   
                  </div>
                  
                  
                </div>
                
                <div class="input-group mt-3 " >
                    <button class="btn primary-bg text-white input-group-text" ><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="text" name="" id="" class="form-control py-2"  placeholder="Search">
                  </div>
              </div>

    


              </div>
          
              <div class="row mt-3 company">


              <div class="col-lg-4  mb-3">
                <div class="card " >
                  <div class="card-body d-flex justify-content-between  py-4">
                    <div class="">
                    <h4>Oppo</h4>
                    <p>Total Complaints: 120</p>
                    <a href="" class="decoration"><button class="btn primary-bg text-white px-4 py-1 mt-4">View Dashboard</button></a>
                </div>
                <div class="">
                <span class="rounded-pill   py-1 px-2">5 new</span>
                   </div>
                </div>
                </div>
                </div>


                
              <div class="col-lg-4  mb-3">
                <div class="card " >
                  <div class="card-body d-flex justify-content-between  py-4">
                    <div class="">
                    <h4>Samsung</h4>
                    <p>Total Complaints: 220</p>
                    <a href="" class="decoration"><button class="btn primary-bg text-white px-4 py-1 mt-4">View Dashboard</button></a>
                </div>
                <div class="">
                <span class="rounded-pill   py-1 px-2">5 new</span>
                   </div>
                </div>
                </div>
                </div>



                
              <div class="col-lg-4  mb-3">
                <div class="card " >
                  <div class="card-body d-flex justify-content-between  py-4">
                    <div class="">
                    <h4>Apple</h4>
                    <p>Total Complaints: 180</p>
                    <a href="" class="decoration"><button class="btn primary-bg text-white px-4 py-1 mt-4">View Dashboard</button></a>
                </div>
                <div class="">
                <span class="rounded-pill   py-1 px-2">5 new</span>
                   </div>
                </div>
                </div>
                </div>

                
             
                
              <div class="col-lg-4  mb-3">
                <div class="card " >
                  <div class="card-body d-flex justify-content-between  py-4">
                    <div class="">
                    <h4>Nokia</h4>
                    <p>Total Complaints: 120</p>
                    <a href="" class="decoration"><button class="btn primary-bg text-white px-4 py-1 mt-4">View Dashboard</button></a>
                </div>
                <div class="">
                <span class="rounded-pill   py-1 px-2">5 new</span>
                   </div>
                </div>
                </div>
                </div>




                    
                  
      

                
            </div>

              
            </div>
        </div>
    </div>
</section>


@endsection