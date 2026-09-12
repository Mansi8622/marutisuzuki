@extends('custom.master')

@section('content')



<section class="login">
    <div class="container">
        <div class="row">
            <!-- Form Column -->
            <div class="col-lg-6 mb-3">
                <div class="login-form">
                    <h2>Create an account</h2>
                    <p>Enter details to create your account</p>
                    <form action="{{ route('customer.register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label>Your Name</label>
                                <input type="text" name="name" placeholder="Your Name" class="form-control" value="{{ old('name') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label>E-mail Address</label>
                                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label>Phone Number</label>
                                <input type="text" name="phone" placeholder="Number" class="form-control" value="{{ old('phone') }}">
                            </div>

                            <div class="col-12 mb-3">
    <label for="password" class="font-weight-bold">Password</label>
    <div class="input-group" style="flex-wrap: nowrap;">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" aria-describedby="password-addon">
       
            <span class="input-group-text toggle-password" toggle="#password">
                <i class="fas fa-eye"></i>
            </span>
        
    </div>
</div>

<div class="col-12 mb-3">
    <label for="password_confirmation" class="font-weight-bold">Confirm Password</label>
    <div class="input-group" style="flex-wrap: nowrap;">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" aria-describedby="password-confirm-addon">
        
            <span class="input-group-text toggle-password" toggle="#password_confirmation">
                <i class="fas fa-eye"></i>
            </span>
        </div>
   
</div>


                            <div class="col-12 mb-3">
                                <button type="submit" class="btn primary-bg text-white w-100 py-3">Register now</button>
                            </div>

                            <div class="col-12">
                                <p class="text-center mt-3">Already have an account?
                                    <a href="{{ route('customer.login') }}" class="decoration">Login</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-lg-6 mb-3">
                <img src="{{ asset('asset/img/register.png') }}" alt="Register" class="img-fluid w-100">
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Laravel Validation Error Alerts -->
@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        html: {!! implode('<br>', $errors->all()) !!},
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK'
    });
</script>
@endif

<!-- Client-side password match validation -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let password = document.querySelector('input[name="password"]');
        let confirmPassword = document.querySelector('input[name="password_confirmation"]');

        let errorDiv = document.createElement("div");
        errorDiv.style.color = "red";
        confirmPassword.parentNode.appendChild(errorDiv);

        confirmPassword.addEventListener("input", function () {
            if (password.value !== confirmPassword.value) {
                errorDiv.textContent = "Passwords do not match!";
            } else {
                errorDiv.textContent = "";
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(function (eye) {
        eye.addEventListener("click", function () {
            const input = document.querySelector(eye.getAttribute("toggle"));
            const isPassword = input.getAttribute("type") === "password";
            input.setAttribute("type", isPassword ? "text" : "password");
            
            // Toggle between eye and eye-slash icons
            const icon = eye.querySelector("i");
            if (isPassword) {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    });
});
</script>

@endsection