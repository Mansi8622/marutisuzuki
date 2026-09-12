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


            <div class="col-lg-9 mb-3 complaint">
              <div class="row">
                <div class="col-12">
                    <h1 class="fw-bold">Complaint</h1>
               <div class="card p-2">
                   
                   <div class="card px-lg-5 px-2 py-3 mb-3">
                            <div class="row ">
                            <div class="col-lg-6 mb-3">
                                <h5>Customer Information</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p>Name:</p>
                                </div>
                                <div class="col-6">
                                    <strong>ABC</strong>
                                </div>

                                <div class="col-6">
                                    <p>Email</p>
                                </div>
                                <div class="col-6">
                                    <strong>abc@gamil.com</strong>
                            </div>

                            <div class="col-6">
                                <p>Phone</p>
                                </div>

                                <div class="col-6">
                                    <strong>01700000000</strong>
                                    </div>
                        </div>
                      
              
          

           
        </div>

        <div class="col-lg-6 mb-3">
            <h5>Product Information</h5>
            <div class="row">
            <div class="col-6"><p>Product Name:</p></div>
            <div class="col-6"><strong>ABC</strong></div>
            <div class="col-6"><p>Serial Number :</p></div>
            <div class="col-6"> <strong> 123456789</strong></div>
            <div class="col-6"><p>Purchase Date:</p></div>
            <div class="col-6"> <strong> 12/12/2021</strong></div>



              
            </div>
        </div>
    </div>
    </div>


    <div class="card px-lg-5 px-2 py-3 mb-3">
    
            <h5>Complaint Details</h5>
            <div class="row">
                <div class="col-lg-2 col-4">
                    <p>Issue:</p>
            </div>
            <div class="col-lg-10 col-8">
                <strong>Screen flickering</strong>
            </div>

            <div class="col-lg-2 col-4">
                <p>Description:</p>
            </div>
            <div class="col-lg-10 col-8">
                <strong>The screen starts flickering intermittently, especially when the brightness is set to low.</strong>
        </div>

        <div class="col-lg-2 col-4 mt-3">
            <p>Status:</p>
            </div>

            <div class="col-lg-10 col-8 mt-3">
                <button class="btn btn-success">Open</button>
                            </div>
    </div>
  




</div>

<div class="card px-lg-5 px-2 py-3 mb-3">
    
    <h5>Resolution</h5>
    <div class="row">
        <div class="col-12">
            <textarea name="" id="" class="form-control" cols="" rows="5"></textarea>
        </div>
       
</div>
</div>

<div class="col-12 text-end">
    <button class="btn btn-outline-danger  px-5 py-2 mb-3">Remove Complaint</button>
    <button class="btn primary-bg text-white px-5 py-2 mb-3">Reject Complaint</button>
    
</div>


    </div>

    </div>
    </div>
    </div>
    </div>
    
</section>




@extends('custom.master')

@section('content')