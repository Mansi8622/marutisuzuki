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
                <h4 class="fw-bold">Replacement</h4>

                
           
              </div>
            </div>

            <div class="col-12 mt-3">
              <div class="card py-3 px-lg-5 px-2" style="border: 1px solid #FFCDAD;">
                <div class="row">
                    <h3 class="mb-3 fw-bold">Submit a Complaint</h3>
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">    
                            <label for="" class="m">Select Order</label>
                            <select name="" class="form-control " id="">
                                <option value="">Select Order ID</option>
                                <option value="">Order ID 1</option>
                                <option value="">Order ID 2</option>
                                <option value="">Order ID 3</option>
                                <option value="">Order ID 4</option>
                            </select>
  
  
                  </div>

                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="form-group">    
                            <label for="" class="m">Issue Type</label>
                            <select name="" class="form-control " id="">
                                <option value="">Select Product Name</option>
                                <option value="">Product Name 1</option>
                                <option value="">Product Name 2</option>
                                <option value="">Product Name 3</option>
                                <option value="">Product Name 4</option>
                            </select>
              </div>
            </div>


            <div class="col-lg-12 mb-3">
                <div class="form-group">    
                    <label for="" class="m">Company name </label>
                  <select name="" class="form-control" id="">
                        <option value="">Select Company Name</option>
                        <option value="">Company Name 1</option>
                        <option value="">Company Name 2</option>
                        <option value="">Company Name 3</option>
                        <option value="">Company Name 4</option>
                    </select>

                </div>
            </div>

            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="" class="m">Description</label>
                   
                    <textarea name="" id="" class="form-control" cols="30" rows="5"></textarea>
                </div>
            </div>

            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label for="" class="m">Attachments</label>
                    <input type="file" class="form-control" name="" id="">
          </div>   
        </div>

        <div class="col-lg-12 mb-3">
            <div class="form-group d-flex justify-content-end">
                <button class="btn primary-bg text-white px-lg-5 px-4 py-2 me-3">Cancel</button>
                <button class="btn primary-bg text-white px-lg-5 px-4 py-2">Submit</button>
            </div>
        </div>

                </div>
              </div>    
            </div> 


      
</section>


@endsection