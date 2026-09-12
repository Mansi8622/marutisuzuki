@extends('custom.master')

@section('content')






<section class="login">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 ">
                <div class="login-form">
                    <h2>Login</h2>
                    <p>Let’s build something great</p>
                    <form action="#">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="">E-mail Address</label>
                        <input type="email" placeholder="Email Address" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="">Password</label>
                        <input type="password" placeholder="Password" class="form-control">
                    </div>
                    <div class="col-12 text-end mb-3">
                        <a href="" class="decoration text-primary">Forgot Password?</a>
                    </div>
                    <div class="col-12">
                       <a href="" class="decoration"> <button type="submit" class="btn primary-bg text-white w-100 py-3">Login</button></a>
                    </div>
                    <div class="col-12">
                        <p class="text-center mt-3">Don't have an account? <a href="" class="decoration text-primary">Register Now</a></p>
                    </div>
                    </form>
                </div>
            </div>
                </div>

                <div class="col-lg-6">
                    <img src="asset/img/login.png" alt="">
                </div>

</section>



@endsection