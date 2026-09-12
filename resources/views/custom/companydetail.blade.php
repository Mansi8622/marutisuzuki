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
                    <h1 class="fw-bold">Complaint</h1>
               
                    <div class="row  align-items-center">
                        <div class="col-lg-4">
                      <div class="card text-center  py-3 px-4 mb-3">
                        <h3>Total Complaints</h3>
                        <h5 class="fw-bold">5</h5>
                   
                  </div>
                </div>

                <div class="col-lg-4">
                      <div class="card text-center mb-3 py-3 px-4">
                        <h3>Open</h3>
                        <h5 class="fw-bold">2</h5>
                   
                  </div>
                </div>

                <div class="col-lg-4">

                      <div class="card text-center mb-3 py-3 px-4">
                        <h3>In progress</h3>
                        <h5 class="fw-bold">2</h5>
                   
                  </div>
                </div>

                <div class="col-lg-4">
                      <div class="card text-center mb-3 py-3 px-4">
                        <h3>Resolved</h3>
                        <h5 class="fw-bold">1</h5>
                   
                  </div>
                  
                  
                  
                </div>
                
                <div class="input-group mt-3 " >
                    <button class="btn primary-bg text-white input-group-text" ><i class="fa-solid fa-magnifying-glass"></i></button>
                    <input type="text" name="" id="" class="form-control py-2"  placeholder="Search">
                  </div>
              </div>

    


              </div>
          <div class="card py-3 px-5 mt-3 d-lg-block d-none">
              <div class="row " >
                <h1 class="fw-bold mb-3">Oppo</h1>
                <div class="col-12 pb-3" >
                    <div class="row " style="color: #878787;">
          <div class="col-3"> Ticket ID</div>
          <div class="col-3">Customer Name</div>
          <div class="col-2">Project</div>
          <div class="col-2">Options</div>
          <div class="col-2">Status</div>
         
        </div>
        </div>
        <div class="col-12">
                <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center; ">
          <div class="col-3 fw-bold">
            #1000002513
          </div>
          <div class="col-3 fw-bold">
            2022.04.28 03:07:04
          </div>
          <div class="col-2 fw-bold">
            Oppo
          </div>
          <div class="col-2">
          <button class="btn btn-success">Open</button>
          </div>
          <div class="col-2">
            <span class="primary">View detais</span>
          </div>
          
          </div>
        </div>

        
        <div class="col-12">
            <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center; ">
      <div class="col-3 fw-bold">
        #1000002513
      </div>
      <div class="col-3 fw-bold">
        2022.04.28 03:07:04
      </div>
      <div class="col-2 fw-bold">
        Oppo
      </div>
      <div class="col-2">
      <button class="btn btn-success">Open</button>
      </div>
      <div class="col-2">
        <span class="primary">View detais</span>
      </div>
      
      </div>
    </div>

</div>
</div>
        </div>
        </div>
        </div>


        <!-- mobile size   -->
        <div class="row d-lg-none">
            <div class="col-12 mb-3">
                <div class="card px-2 py-1" style="border: 1px solid #D7D7D7; box-shadow: 2px 2px 10px 0px #ccc; font-size: 12px;">
                    <div class="row">
                        <div class="col-6 mb-2 d-flex align-items-center grey">Ticket ID</div>
                        <div class="col-6 mb-2 d-flex align-items-center"> #1000002513</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Customer Name</div>
                        <div class="col-6 mb-2 d-flex align-items-center primary" style="font-size: 12px;"> 2022.04.28 03:07:04</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Project</div>
                        <div class="col-6 mb-2 d-flex align-items-center"> Oppo</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Options</div>
                        <div class="col-6 mb-2 d-flex align-items-center">  
                                <button class="btn btn-success">Open</button>
                        </div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Status</div>     
                        <div class="col-6 mb-2 d-flex align-items-center">
                            <span class="primary">View detais</span>
                        </div>
                       

                    </div>
                </div>
            </div>


            <div class="col-12 mb-3">
                <div class="card px-2 py-1" style="border: 1px solid #D7D7D7; box-shadow: 2px 2px 10px 0px #ccc; font-size: 12px;">
                    <div class="row">
                        <div class="col-6 mb-2 d-flex align-items-center grey">Ticket ID</div>
                        <div class="col-6 mb-2 d-flex align-items-center"> #1000002513</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Customer Name</div>
                        <div class="col-6 mb-2 d-flex align-items-center primary" style="font-size: 12px;"> 2022.04.28 03:07:04</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Project</div>
                        <div class="col-6 mb-2 d-flex align-items-center"> Oppo</div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Options</div>
                        <div class="col-6 mb-2 d-flex align-items-center">  
                                <button class="btn btn-success">Open</button>
                        </div>
                        <div class="col-6 mb-2 d-flex align-items-center grey">Status</div>     
                        <div class="col-6 mb-2 d-flex align-items-center">
                            <span class="primary">View detais</span>
                        </div>
                       

                    </div>
                </div>
            </div>

          

           
        </div>



              
            </div>
        </div>
    </div>
</section>




@extends('custom.master')

@section('content')