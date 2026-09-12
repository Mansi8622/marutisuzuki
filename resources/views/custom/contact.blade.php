@extends('custom.master')

@section('content')


<!-- about us start -->
<section class="contact">
<div class="container">
  <div class="row">
    <div class="col-lg-12 py-1">
      <p><a href="index.html" class="decoration primary">Home</a> &nbsp; <i class="fa-solid fa-chevron-right"></i>&nbsp; Contact Us </p>
    
    </div>
</div>
<div class="row">
  <div class="col-lg-12 text-center">
    <h1 class="fw-bold mb-5">Get In Touch</h1>
</div>
<div class="col-lg-5 mb-3 p-0">
    <div class="card card-1 border-0 rounded-0  px-5 py-5 text-white mt-lg-5" >
    <h2 class="fw-bold">Contact us</h2>
    <div class="d-flex align-items-center mb-3">
        <i class="fa-solid fa-phone fs-5 pe-3"></i>
        <div class="">
            <p>Call Us Toll Free</p>
            <p>+91 78578 68055</p>
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <i class="fa-solid fa-envelope fs-5 pe-3"></i>
        <div class="">
            <p>Email Address</p>
            <p>support@marutisuzukiventures.online</p>
        </div>
    </div>

    <div class="d-flex align-items-center mb-3">
        <i class="fa-solid fa-building fs-5 pe-3"></i>
        <div class="">
            <p>Office Location</p>
            <p>Kamala Market, RK Bhattacharya Road, Pirmuhani, Salimpur Ahra, Golambar, Patna, Bihar-800001</p>
        </div>

</div>
</div>


   
</div>


<div class="col-lg-7 mb-3 p-0 ">
    <div class="card border-0 py-5 px-lg-5 px-2" style="border: 1px solid #FFCDAD; border-radius: 0; box-shadow: 2px 2px 3px 3px #00000014;">
      <form action="">
        <div class="row">
          <div class="col-12">
            <h3 class="fw-bold mb-3">Contact Form</h3>
          </div>
          <div class="col-lg-6 mb-3">
            <label for="" class="mb-3">First Name</label>
            <input type="text" class="form-control py-3" placeholder="First Name" name="" id="">
          </div>
          <div class="col-lg-6 mb-3">
            <label for="" class="mb-3">Last Name</label>
            <input type="text" class="form-control py-3" placeholder="Last Name" name="" id="">
          </div>
          <div class="col-lg-6 mb-3">
            <label for="" class="mb-3">Email</label>
            <input type="email" class="form-control py-3" placeholder="Email Address" name="" id="">
          </div>
          <div class="col-lg-6 mb-3">
            <label for="" class="mb-3">Number</label>
            <input type="text" class="form-control py-3" placeholder="Phone Number" name="" id="">
          </div>
          <div class="col-lg-12 mb-3">
            <label for="" class="mb-3">Message</label>
            <textarea name="" id="" cols="30" rows="5" class="form-control" placeholder="Your Message"></textarea>
          </div>
          <div class="col-lg-12 text-end">
            <button class="btn primary-bg text-white px-3 py-2">Send Message</button>
          </div>

        </div>
      </form>

</div>

</section>

@endsection