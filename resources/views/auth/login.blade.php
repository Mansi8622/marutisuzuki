@extends('custom.master')

@section('content')
<section class="login">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

        
                @if(session('message'))
                    <p class="alert alert-info">
                        {{ session('message') }}
                    </p>
                @endif
                <div class="login-form">
                    <h2>Login</h2>
                    <p>Let’s build something great For Admin & Retailer</p>
                      <!-- Button Tabs with <a> tag -->
    <div class="d-flex justify-content-center gap-3 mb-4">
        <a href="/login" class="btn btn-outline-primary active" id="resellerTab" onclick="selectLoginType('reseller')">
            <i class="fa fa-user-shield me-1"></i> Login Reseller
        </a>
        <a href="/customer/login" class="btn btn-outline-secondary" id="customerTab" onclick="selectLoginType('customer')">
            <i class="fa fa-user me-1"></i> Login Customer
        </a>
    </div>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="">User Id & Email</label>
                                <input type="email" placeholder="Register Email" name="email" class="form-control" autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">
                                @if($errors->has('email'))
                                    <p class="help-block">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Password</label>
                                <input type="password" placeholder="Password" name="password" class="form-control">
                                @if($errors->has('password'))
                                    <p class="help-block">
                                        {{ $errors->first('password') }}
                                    </p>
                                @endif
                            </div>
                            <div class="row">

                                <div class="col-xs-4">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                                        {{ trans('global.login') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 text-end mb-3">
                                @if(Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        {{ trans('global.forgot_password') }}
                                    </a><br>
                                @endif
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
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
@endsection
