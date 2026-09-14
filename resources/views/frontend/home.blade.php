


@extends('layouts.frontend')

@section('content')





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
                
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
              <div class="col-12 mt-3 text-md-end ">
              <a href="javascript:void(0)" class="decoration text-white" data-bs-toggle="modal" data-bs-target="#verifyAccountModal">
    <div class="btn primary-bg text-white px-5 py-2 mb-3">
        Verify Account
    </div>
</a>


<!-- Verify Account Modal -->
<div class="modal fade" id="verifyAccountModal" tabindex="-1" aria-labelledby="verifyAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyAccountModalLabel">Verify Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('verifications.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <!-- Optional: Only if not setting customer_id in controller -->
                    <!-- <input type="" name="customer_id" value="{{ auth()->id() }}"> -->
                    <!-- or for custom guard -->
                   <input type="hidden" name="customer_id" value="{{ auth('customer')->id() }}"> 

                    <div class="mb-3 row">
                        <label for="name" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-sm-4 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="number" class="col-sm-4 col-form-label">Mobile Number</label>
                        <div class="col-sm-8">
                            <input type="text" name="number" id="number" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="image" class="col-sm-4 col-form-label">Profile Image</label>
                        <div class="col-sm-8">
                            <input type="file" name="image" id="image" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="aadhar_image" class="col-sm-4 col-form-label">Aadhar Image</label>
                        <div class="col-sm-8">
                            <input type="file" name="aadhar_image" id="aadhar_image" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="pan_image" class="col-sm-4 col-form-label">PAN Image</label>
                        <div class="col-sm-8">
                            <input type="file" name="pan_image" id="pan_image" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="reseller_code" class="col-sm-4 col-form-label">Reseller Code</label>
                        <div class="col-sm-8">
                            <input type="text" name="reseller_code" id="reseller_code" class="form-control" required>
                        </div>
                    </div>

                    <input type="hidden" name="verification_status" value="pending">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>




              <a href="" class="decoration text-white">  <div class="btn primary-bg text-white px-5 py-2 mb-3">
                  Apply for Credit line
                </div></a>



              </div>
            </div>


              </div>

              
          
              <div class="row mt-3">
              
                
                    
                  
      

                
            </div>

              
            </div>
        </div>
    </div>
</section>



@endsection