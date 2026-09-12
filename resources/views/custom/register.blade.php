@extends('custom.master')

@section('content')






<section class="login">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="login-form">
                    <h2>Create an account</h2>
                    <p>Enter details to create your account</p>
                    <form action="#">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="">Your Name</label>
                        <input type="text" placeholder="Your Name" class="form-control">
                    </div>

                            <div class="col-12 mb-3">
                                <label for="">E-mail Address</label>
                        <input type="email" placeholder="Email Address" class="form-control">
                    </div>

                    <div class="col-12 mb-3">
                        <label for="">Phone Number</label>
                <input type="text" placeholder="Number" class="form-control">
            </div>
                    <div class="col-6 mb-3">
                        <label for="">Password</label>
                        <input type="password" placeholder="Password" class="form-control">
                    </div>

                    <div class="col-6 mb-3">
                        <label for="">Confirm Password</label>
                        <input type="password" placeholder="Confirm Password" class="form-control">
                    </div>

                  
                    <div class="col-12">
                       <a href="" class="decoration"> <button type="submit" class="btn primary-bg text-white w-100 py-3">Register now</button></a>
                    </div>
                    <div class="col-12">
                        <p class="text-center mt-3">Already have an account? <a href="login.html" class="decoration">Login</a></p>
                    </div>
                    </form>
                </div>
            </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <img src="asset/img/register.png" alt="" class="img-fluid w-100">
                </div>

</section>


@endsection