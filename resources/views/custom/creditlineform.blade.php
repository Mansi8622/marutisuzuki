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

                <h3 class="mb-3 fw-bold">Credit line</h3>


            <div class="card py-3 px-lg-5" style="box-shadow: 2px 2px 10px 0px #ccc;">

           
                <h2 class="mb-5">Apply Credit line</h2>

                <form action="">
                    <div class="row">
                        <label for="" class="mb-2">Cardholder’s Name</label>
                        <div class="col-6 mb-4">
                            <input type="text" class="form-control" placeholder="first Name" name="" id="">
                        </div>

                        <div class="col-6 mb-4">
                            <input type="text" class="form-control" placeholder="last Name" name="" id="">
                        </div>

                        <div class="col-12 mb-4">
                            <label for="" class="mb-2"> Current Credit Limit</label>
                            <input type="text" class="form-control" placeholder="Current Credit Limit" name="" id="">
                        </div>

                        <div class="col-12 mb-4">
                            <label for="" class="mb-2">Date</label>
                            <input type="date" class="form-control" name="" id="">
                        </div>

                        <div class="col-12 mb-4">
                            <label for="" class="mb-2">Signature</label>
                            <input type="file" name="" id="" class="form-control" placeholder="Signature">
                        </div>

                        <div class="col-12 mb-4 text-md-end">
                          <div class="btn border px-4 py-2">Cancel</div>
                          <div class="btn primary-bg text-white px-4 py-2">Submit form</div>
                        </div>
                    </div>
                </form>
            </div>

                
              
                
            </div>

              
            </div>
        </div>
    </div>
</section>


@endsection