@extends('custom.master')

@section('content')

<section class="login">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login-form">
                    
                    <h2>Customer Login</h2>
                    <p>Let’s build something great</p>
                    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="/login" class="btn btn-outline-secondary" id="resellerTab" onclick="selectLoginType('reseller')">
            <i class="fa fa-user-shield me-1"></i> Login Reseller
        </a>
        <a href="/customer/login" class="btn btn-outline-primary active" id="customerTab" onclick="selectLoginType('customer')">
            <i class="fa fa-user me-1"></i>  Customer Login
        </a>
    </div>
                    <form action="{{ route('customer.log') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="">Phone Number</label>
                                <input type="number" placeholder="Register Phone" name="phone" class="form-control">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
    <label for="password">Password</label>
    <div class="d-flex">
        <input type="password" id="password" placeholder="Password" name="password" class="form-control" style="border-radius:0;">
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()" style="border-radius:0;">
            <i id="eyeIcon" class="fa fa-eye"></i>
        </button>
    </div>
    @error('password')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>

                            <div class="col-12 text-end mb-3">
                                <a href="" class="decoration text-primary">Forgot Password?</a>
                            </div>
                            <div class="col-12">
                               <a href=""><button type="submit" class="btn primary-bg text-white w-100 py-3">Login</button></a>
                            </div>
                            <div class="col-12">
                                <p class="text-center mt-3">Don't have an account? <a href="{{ route('customer.register') }}" class="decoration text-primary">Register Now</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
       
        <div class="col-lg-6">
            <img src="{{ asset('asset/img/login.png') }}" alt="">
        </div>
    </div>
</section>

@endsection
