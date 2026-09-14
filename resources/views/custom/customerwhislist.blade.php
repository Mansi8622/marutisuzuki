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
                        <li class="active"><a href="retailerprofile.html" class="decoration"><img src="asset/img/dashboard/orderhistory.png" alt="" class="me-3"> Place an Order</a></li>
                        <li><a href="{{ route('frontend.orders.index') }}" class="decoration"><img src="asset/img/dashboard/replacement.png" alt="" class="me-3">Order History</a></li>  
                        <li><a href="retailerorder.html" class="decoration"><img src="asset/img/dashboard/support.png" alt="" class="me-3">Replacement</a></li>  
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/message.png" alt="" class="me-3">Support and Help</a></li>    
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/notification.png" alt="" class="me-3"> Notifications and Alerts</a></li>
                        <li><a href="retailerreview.html" class="decoration"><img src="asset/img/dashboard/profilesetting.png" alt="" class="me-3"> Profile Settings</a></li>
                        <li class=" text-white primary-bg"><a href="retailerreview.html" class="decoration text-white">Logout</a></li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-9 mb-3 ">
              <div class="row">

              <h1 class="mb-4">My Wishlist </h1> 
              
              <!-- laptop size  -->
              <div class="card px-5 py-3 d-lg-block d-none">
                <div class="row " >
                    <div class="col-12 pb-3" >
                        <div class="row">
              <div class="col-2"> Product Name</div>
              <div class="col-2">Description</div>
              <div class="col-2">Price</div>
              <div class="col-2">Stock</div>
              <div class="col-2">Images</div>
              <div class="col-2">Options</div>
            </div>
            </div>
            <div class="col-12">
                    <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center; ">
              <div class="col-2">
                ET-ID CARD
              </div>
              <div class="col-2 ">
                ET-ID CARD is suitable for Student, employees, kids, patients, livestock protecting, and others.
              </div>
              <div class="col-2">
                ₹29,999.00
              </div>
              <div class="col-2">
                In Stock
              </div>
              <div class="col-2">
                <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" >
              </div>
              <div class="col-2">   
                <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#E06563;color: white;"><i class="fas fa-trash"></i></button>
                <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#FF9E66;color: white;"><i class="fa-regular fa-file"></i></button>
              </div>
              
              </div>
            </div>


            <div class="col-12">
                <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center;">
          <div class="col-2">
            ET-ID CARD
          </div>
          <div class="col-2">
            ET-ID CARD is suitable for Student, employees, kids, patients, livestock protecting, and others.
          </div>
          <div class="col-2">
            ₹29,999.00
          </div>
          <div class="col-2">
            In Stock
          </div>
          <div class="col-2">
            <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" >
          </div>
          <div class="col-2">   
            <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#E06563;color: white;"><i class="fas fa-trash"></i></button>
            <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#FF9E66;color: white;"><i class="fa-regular fa-file"></i></button>
          </div>
          
          </div>
        </div>

        <div class="col-12">
            <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center;">
      <div class="col-2">
        ET-ID CARD
      </div>
      <div class="col-2">
        ET-ID CARD is suitable for Student, employees, kids, patients, livestock protecting, and others.
      </div>
      <div class="col-2">
        ₹29,999.00
      </div>
      <div class="col-2">
        In Stock
      </div>
      <div class="col-2">
        <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" >
      </div>
      <div class="col-2">   
        <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#E06563;color: white;"><i class="fas fa-trash"></i></button>
        <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#FF9E66;color: white;"><i class="fa-regular fa-file"></i></button>
      </div>
      
      </div>
    </div>


    <div class="col-12">
        <div class="row py-3" style="border-top: 1px solid #D7D7D7; display: flex; align-items: center;">
  <div class="col-2">
    ET-ID CARD
  </div>
  <div class="col-2">
    ET-ID CARD is suitable for Student, employees, kids, patients, livestock protecting, and others.
  </div>
  <div class="col-2">
    ₹29,999.00
  </div>
  <div class="col-2">
    In Stock
  </div>
  <div class="col-2">
    <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" >
  </div>
  <div class="col-2">   
    <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#E06563;color: white;"><i class="fas fa-trash"></i></button>
    <button class="btn " style="width: 40px; height: 40px;border-radius: 100%; background-color:#FF9E66;color: white;"><i class="fa-regular fa-file"></i></button>
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
                            <div class="col-6 mb-2 d-flex align-items-center grey">Product Name</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ET-ID CARD</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Descriotion</div>
                            <div class="col-6 mb-2 d-flex align-items-center primary" style="font-size: 12px;"> ET-ID CARD is suitable for Student,employees,kids, patients, livestock protecting, and others.</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Price</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ₹29,999.00</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center">In Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Images</div>     
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" style="width: 60px; height: 60px;" >
                            </div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Options</div>
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <button class="btn me-3 d-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#E06563;color: white; font-size: 12px;"><i class="fas fa-trash"></i></button>
                                <button class="btnd-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#FF9E66;color: white; font-size: 12px;"><i class="fa-regular fa-file"></i></button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card px-2 py-1" style="border: 1px solid #D7D7D7; box-shadow: 2px 2px 10px 0px #ccc; font-size: 12px;">
                        <div class="row">
                            <div class="col-6 mb-2 d-flex align-items-center grey">Product Name</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ET-ID CARD</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Descriotion</div>
                            <div class="col-6 mb-2 d-flex align-items-center primary" style="font-size: 12px;"> ET-ID CARD is suitable for Student,employees,kids, patients, livestock protecting, and others.</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Price</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ₹29,999.00</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center">In Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Images</div>     
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" style="width: 60px; height: 60px;" >
                            </div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Options</div>
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <button class="btn me-3 d-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#E06563;color: white; font-size: 12px;"><i class="fas fa-trash"></i></button>
                                <button class="btnd-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#FF9E66;color: white; font-size: 12px;"><i class="fa-regular fa-file"></i></button>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-12 mb-3">
                    <div class="card px-2 py-1" style="border: 1px solid #D7D7D7; box-shadow: 2px 2px 10px 0px #ccc; font-size: 12px;">
                        <div class="row">
                            <div class="col-6 mb-2 d-flex align-items-center grey">Product Name</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ET-ID CARD</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Descriotion</div>
                            <div class="col-6 mb-2 d-flex align-items-center primary" style="font-size: 12px;"> ET-ID CARD is suitable for Student,employees,kids, patients, livestock protecting, and others.</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Price</div>
                            <div class="col-6 mb-2 d-flex align-items-center"> ₹29,999.00</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center">In Stock</div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Images</div>     
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <img src="asset/img/product/pr1.png" alt="Product Image" class="img-fluid" style="width: 60px; height: 60px;" >
                            </div>
                            <div class="col-6 mb-2 d-flex align-items-center grey">Options</div>
                            <div class="col-6 mb-2 d-flex align-items-center">
                                <button class="btn me-3 d-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#E06563;color: white; font-size: 12px;"><i class="fas fa-trash"></i></button>
                                <button class="btnd-flex align-items-center justify-content-center border-0" style="width: 30px; height: 30px;border-radius: 100%; background-color:#FF9E66;color: white; font-size: 12px;"><i class="fa-regular fa-file"></i></button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


            


            </div>


           

</section>



@endsection