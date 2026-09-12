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
                        <li ><a href="retailerorder.html" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
                        <li class="active"><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/message.png" alt="" class="me-3">Support and Help</a></li>    
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/notification.png" alt="" class="me-3"> Notifications and Alerts</a></li>
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/profilesetting.png" alt="" class="me-3"> Profile Settings</a></li>
                        <li class=" text-white primary-bg"><a href="retailerreview.html" class="decoration text-white">Logout</a></li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-9 mb-3 ">
              <div class="row">
                <div class="col-12">
                    <div class=" px-3 py-2">
                <h4>Support and Help </h4>
                
           
              </div>
            </div>

            <div class="col-12 justify-content-center mt-3">
                <div class="card py-3" style="background: #FFCDAD;;">
                    <div class="col-12 text-center">
                        <img src="asset/img/dashboard/sopprt.png" alt="" class="mb-3"><br>
                        <a href="#" class="decoration"><button class=" btn primary-bg text-white">Contact Admin</button></a>
                    
                </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <h2>FAQ Section</h2>

                <div class="card border-0 py-3 px-3" >
                    <div class="mb-3">
                        <div class="card-header d-flex justify-content-between">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio placeat consequatur incidunt facere voluptatibus sapiente ut, ratione rerum, dolorem unde eius quibusdam non. Necessitatibus esse quo, dolorem aperiam sed labore?
                            <button class="btn primary-bg text-white " style="width: 50px; height: 50px; border-radius: 100%;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="collapse" id="collapseExample1">
                            <div class="card card-body">
                                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="card-header d-flex justify-content-between">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio placeat consequatur incidunt facere voluptatibus sapiente ut, ratione rerum, dolorem unde eius quibusdam non. Necessitatibus esse quo, dolorem aperiam sed labore?
                            <button class="btn primary-bg text-white " style="width: 50px; height: 50px; border-radius: 100%;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="collapse" id="collapseExample2">
                            <div class="card card-body">
                                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="card-header d-flex justify-content-between">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio placeat consequatur incidunt facere voluptatibus sapiente ut, ratione rerum, dolorem unde eius quibusdam non. Necessitatibus esse quo, dolorem aperiam sed labore?
                            <button class="btn primary-bg text-white " style="width: 50px; height: 50px; border-radius: 100%;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample3">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="collapse" id="collapseExample3">
                            <div class="card card-body">
                                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the user activates the relevant trigger.
                            </div>
                        </div>
                    </div>
                    
              
            </div>
</div>
</section>


@endsection