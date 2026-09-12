<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap 5 E-Commerce</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('asset/css/style.css')}}">

</head>
<body>

    <header>

        <div class="header-banner">
            <img src="{{asset('asset/img/headerbanner/header-banner.png')}}" alt="" class="img-fluid w-100" >

        </div>

    
  <!-- Navbar -->
  <div class="top-bar ">
    <div class="container d-flex justify-content-end align-items-center">
      <div class="me-3">
        <small >
        @if(Auth::check())
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: #343a40;">
            <i class="fa fa-user"></i> &nbsp; {{ Auth::user()->name }}
        </a>

        <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="/home">Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>
@else
    <a href="/login" style="text-decoration: none; color: #343a40;">
        <i class="fa fa-user"></i> &nbsp; Login Retailer
    </a>
@endif


        </small>
      </div>
      <div class="me-3">
        <small >
          <i class="bi bi-geo-alt"></i> Track Your Order
        </small>
      </div>
      |
      <div class="ms-3">
        <small>
          Helping +01 112 352 566
        </small>
      </div>
    </div>
  </div>

  <!-- Main Navbar -->
  <!-- Main Navbar -->
  <nav class="navbar navbar-expand-lg py-3">
    <div class="container">
      <!-- Brand -->  
         <!-- Mobile Menu Toggle -->
      <div class="navbar-toggler d-lg-none border-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
        <i class="fa-solid fa-bars"></i>
      </div>
      <a class="navbar-brand" href="/">
        <img src="asset/img/logo/logo.png" alt="MyShop" class="img-fluid">
      </a>
      
      <!-- Search Bar -->
      <div class="header-search">
        <input type="text" class="form-control" placeholder="Search for...">
      </div>

      <!-- Right Section -->
      <div class="d-flex align-items-center">
        @if(auth('customer')->check())
          @php
              $customer = auth('customer')->user();
          @endphp

          @if($customer->profile_photo)
              <!-- If profile photo exists, display it -->
              <a href="{{ route('customer.dashboard') }}"><img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Photo" class="profile-photo" class="rounded-circle" width="50" height="50"></a>
          @else
              <!-- If profile photo doesn't exist, display customer's name -->
              <span>{{ $customer->name }}</span>
          @endif
          @else
              <!-- If customer is not logged in, show login/register link -->
              <a href="{{ route('customer.login') }}" class="icon-link">
                @if(Auth::check())
                <a href="/home" style="text-decoration: none; color: #343a40;">
                  <i class="fa fa-user"></i> &nbsp; {{ Auth::user()->name }}
                </a>
                @else
                  <i class="fa-regular fa-user"></i><span>Login/Register</span>
                  @endif
              </a>
        @endif

        <a href="#" class="icon-link"><i class="fa-regular fa-heart"></i><span>Wishlist</span></a>
        <a href="/cart" class="icon-link"><i class="fa-solid fa-cart-shopping"></i><span> Cart</span></a>
      </div>
    </div>
  </nav>

  <!-- Off-Canvas Menu for Mobile -->
  <div class="offcanvas offcanvas-start" style="background: #E82600;" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasMenuLabel">Menu</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="list-unstyled" >
        <li><a href="#" class="btn btn-link">Home</a></li>
        <li><a href="/product" class="btn btn-link">Product</a></li>
        <li><a href="#" class="btn btn-link">Offer</a></li>
        <li><a href="#" class="btn btn-link">About</a></li>
        <li><a href="#" class="btn btn-link">Contact</a></li>
      </ul>
    </div>
  </div>


  <div class="header3 py-3 ">
  <div class="container " >
    <ul class="list-unstyled d-flex ">
      <li class="">
        <div class=" dropdown-toggle" style="background-color: #E82600; color: white;" type="button" data-bs-toggle="dropdown">
          Categories (See All)
        </div>
        <ul class="dropdown-menu ">
          <li><a href="#" class="dropdown-item text-dark">Electronics</a></li>      
          <li><a href="#" class="dropdown-item text-dark">Fashion</a></li>
          <li><a href="#" class="dropdown-item text-dark">Home & Kitchen</a></li>
          </ul>
       
        
      </li>
      <li><a href="/" class="decoration">Home</a></li>
      <li><a href="/product" class="decoration">Product</a></li>
      <li><a href="/offer" class="decoration">Offer</a></li>
      <li><a href="/about-us" class="decoration">About</a></li>
    </ul>
  </div>
</div>
  

  


</header>

<!-- header end  -->

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
                    <div class="sidebar">
            <div class="sidebar-header">
                <h4>{{ Auth::user()->name }}</h4>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('frontend.profile.index') }}">{{ __('My profile') }}</a>
                </li>
                
                @can('user_management_access')
                    <li class="disabled">{{ trans('cruds.userManagement.title') }}</li>
                @endcan
        
                @can('permission_access')
                    <li>
                        <a href="{{ route('frontend.permissions.index') }}">{{ trans('cruds.permission.title') }}</a>
                    </li>
                @endcan
        
                @can('role_access')
                    <li>
                        <a href="{{ route('frontend.roles.index') }}">{{ trans('cruds.role.title') }}</a>
                    </li>
                @endcan
        
                @can('user_access')
                    <li>
                        <a href="{{ route('frontend.users.index') }}">{{ trans('cruds.user.title') }}</a>
                    </li>
                @endcan
        
                @can('product_management_access')
                    <li class="disabled">{{ trans('cruds.productManagement.title') }}</li>
                @endcan
        
                @can('add_company_access')
                    <li>
                        <a href="{{ route('frontend.add-companies.index') }}">{{ trans('cruds.addCompany.title') }}</a>
                    </li>
                @endcan
        
                @can('product_category_access')
                    <li>
                        <a href="{{ route('frontend.product-categories.index') }}">{{ trans('cruds.productCategory.title') }}</a>
                    </li>
                @endcan
        
                @can('product_tag_access')
                    <li>
                        <a href="{{ route('frontend.product-tags.index') }}">{{ trans('cruds.productTag.title') }}</a>
                    </li>
                @endcan
                @can('user_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.userManagement.title') }}
                        </a>
                    @endcan
                    @can('permission_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.permissions.index') }}">
                            {{ trans('cruds.permission.title') }}
                        </a>
                    @endcan
                    @can('role_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.roles.index') }}">
                            {{ trans('cruds.role.title') }}
                        </a>
                    @endcan
                    @can('user_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.users.index') }}">
                            {{ trans('cruds.user.title') }}
                        </a>
                    @endcan
                    @can('product_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.productManagement.title') }}
                        </a>
                    @endcan
                    @can('add_company_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.add-companies.index') }}">
                            {{ trans('cruds.addCompany.title') }}
                        </a>
                    @endcan
                    @can('product_category_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.product-categories.index') }}">
                            {{ trans('cruds.productCategory.title') }}
                        </a>
                    @endcan
                    @can('product_tag_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.product-tags.index') }}">
                            {{ trans('cruds.productTag.title') }}
                        </a>
                    @endcan
                    @can('product_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.products.index') }}">
                            {{ trans('cruds.product.title') }}
                        </a>
                    @endcan
                    @can('stocks_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.stocksManagement.title') }}
                        </a>
                    @endcan
                    @can('our_stock_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.our-stocks.index') }}">
                            {{ trans('cruds.ourStock.title') }}
                        </a>
                    @endcan
                    @can('check_godown_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.check-godowns.index') }}">
                            {{ trans('cruds.checkGodown.title') }}
                        </a>
                    @endcan
                    @can('stock_transfer_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.stock-transfers.index') }}">
                            {{ trans('cruds.stockTransfer.title') }}
                        </a>
                    @endcan
                    @can('order_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.order.title') }}
                        </a>
                    @endcan
                    @can('check_order_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.check-orders.index') }}">
                            {{ trans('cruds.checkOrder.title') }}
                        </a>
                    @endcan
                    @can('cancellation_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.cancellations.index') }}">
                            {{ trans('cruds.cancellation.title') }}
                        </a>
                    @endcan
                    @can('wallet_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.walletManagement.title') }}
                        </a>
                    @endcan
                    @can('wallet_request_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.wallet-requests.index') }}">
                            {{ trans('cruds.walletRequest.title') }}
                        </a>
                    @endcan
                    @can('shipping_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.shipping.title') }}
                        </a>
                    @endcan
                    @can('carrier_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.carriers.index') }}">
                            {{ trans('cruds.carrier.title') }}
                        </a>
                    @endcan
                    @can('support_desk_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.supportDesk.title') }}
                        </a>
                    @endcan
                    @can('dispute_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.disputes.index') }}">
                            {{ trans('cruds.dispute.title') }}
                        </a>
                    @endcan
                    @can('refund_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.refunds.index') }}">
                            {{ trans('cruds.refund.title') }}
                        </a>
                    @endcan
                    @can('setting_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.setting.title') }}
                        </a>
                    @endcan
                    @can('tax_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.taxes.index') }}">
                            {{ trans('cruds.tax.title') }}
                        </a>
                    @endcan
                    @can('shop_setting_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.shop-settings.index') }}">
                            {{ trans('cruds.shopSetting.title') }}
                        </a>
                    @endcan
                    @can('configuration_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.configurations.index') }}">
                            {{ trans('cruds.configuration.title') }}
                        </a>
                    @endcan
                    @can('support_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.supports.index') }}">
                            {{ trans('cruds.support.title') }}
                        </a>
                    @endcan
                    @can('web_setting_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.webSetting.title') }}
                        </a>
                    @endcan
                    @can('privacy_policy_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.privacy-policies.index') }}">
                            {{ trans('cruds.privacyPolicy.title') }}
                        </a>
                    @endcan
                    @can('term_condition_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.term-conditions.index') }}">
                            {{ trans('cruds.termCondition.title') }}
                        </a>
                    @endcan
                    @can('about_us_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.about-uss.index') }}">
                            {{ trans('cruds.aboutUs.title') }}
                        </a>
                    @endcan
                    @can('user_alert_access')
                        <a class="dropdown-item" href="{{ route('frontend.user-alerts.index') }}">
                            {{ trans('cruds.userAlert.title') }}
                        </a>
                    @endcan
                    @can('asset_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.assetManagement.title') }}
                        </a>
                    @endcan
                    @can('asset_category_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-categories.index') }}">
                            {{ trans('cruds.assetCategory.title') }}
                        </a>
                    @endcan
                    @can('asset_location_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-locations.index') }}">
                            {{ trans('cruds.assetLocation.title') }}
                        </a>
                    @endcan
                    @can('asset_status_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.asset-statuses.index') }}">
                            {{ trans('cruds.assetStatus.title') }}
                        </a>
                    @endcan
                    @can('asset_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.assets.index') }}">
                            {{ trans('cruds.asset.title') }}
                        </a>
                    @endcan
                    @can('assets_history_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.assets-histories.index') }}">
                            {{ trans('cruds.assetsHistory.title') }}
                        </a>
                    @endcan
                    @can('expense_management_access')
                        <a class="dropdown-item disabled" href="#">
                            {{ trans('cruds.expenseManagement.title') }}
                        </a>
                    @endcan
                    @can('expense_category_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.expense-categories.index') }}">
                            {{ trans('cruds.expenseCategory.title') }}
                        </a>
                    @endcan
                    @can('income_category_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.income-categories.index') }}">
                            {{ trans('cruds.incomeCategory.title') }}
                        </a>
                    @endcan
                    @can('expense_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.expenses.index') }}">
                            {{ trans('cruds.expense.title') }}
                        </a>
                    @endcan
                    @can('income_access')
                        <a class="dropdown-item ml-3" href="{{ route('frontend.incomes.index') }}">
                            {{ trans('cruds.income.title') }}
                        </a>
                    @endcan

                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
            </ul>
        </div>
        <main class="py-4">
            @if(session('message'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        
        </ul>
                </div>
            </div>


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
              <div class="col-12 mt-3 text-md-end ">
                <a href="" class="decoration text-white ">   <div class="btn primary-bg text-white px-5 py-2 mb-3">
                  Verify Account 
                </div></a>
              <a href="" class="decoration text-white">  <div class="btn primary-bg text-white px-5 py-2 mb-3">
                  Apply for Credit line
                </div></a>

              </div>
            </div>


              </div>
          
              <div class="row mt-3">

              <ul class=" ">
                        @guest
                        @else
                            <!-- Main view (e.g., dashboard.blade.php) -->

                            <li class="nav-item ml-3">
                                @if(Auth::check() && in_array(Auth::user()->business_type, ['Retailer', 'Wholesaler']))
                                    @php
                                        $wallet = \App\Models\WalletRequest::where('vendor_id', Auth::id())->first();
                                    @endphp
                            
                                    @if($wallet && $wallet->status === 'Active')
                                        <span class="badge badge-success text-dark" style="padding: 15px;">
                                            Balance: ₹{{ number_format($wallet->welcome_amount, 2) }}
                                        </span>
                                        <span class="badge badge-warning text-dark" style="padding: 15px;">
                                            Due: ₹{{ number_format($wallet->due, 2) }}
                                        </span>
                                        <button class="btn btn-outline-primary ml-2" data-toggle="modal" data-target="#addAmountModal">
                                            Add Amount
                                        </button>
                                        <button class="btn btn-outline-warning ml-2" data-toggle="modal" data-target="#payout">
                                            Request Payout
                                        </button>
                                        <!-- History Button -->
                                        <button class="btn btn-outline-info ml-2" data-toggle="modal" data-target="#transactionHistoryModal">
                                            History
                                        </button>
                                    @elseif(!$wallet || $wallet->status === 'pending')
                                        <a class="btn btn-outline-primary" href="javascript:void(0);" onclick="applyWallet()">
                                            Apply Wallet
                                        </a>
                                    @endif
                                @endif
                            </li>
                            
                            <!-- Transaction History Modal -->

                            @include('wallet.transactionHistoryModal')
                            

                            <!-- Include the Request Payout Modal -->
                            @include('wallet.request-payout-modal')

                            <!-- Add Amount Modal -->
                            @include('wallet.add-amount-popup')

                            
                            
                            
                            <script>
                                function applyWallet() {
                                    // AJAX request to apply wallet
                                    fetch("{{ route('frontend.apply.wallet') }}", {
                                        method: "POST",
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify({})
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log(data); // Log the entire response for debugging
                                        if (data.success) {
                                            alert("Wallet request submitted successfully. Please wait for approval.");
                                        } else if (data.error) {
                                            alert(data.error);
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error:", error);
                                        alert("Something went wrong. Please try again.");
                                    });
                                }
                            </script>
                            
                        @endguest
                    </ul>



              <div class="col-lg-3 col-6 mb-3">
                <div class="card border-0" style="background: #FFE9E5;">
                  <div class="card-body text-center py-2">
                    <img src="asset/img/dashboard/product.png" alt="" class="mt-5"> 
                    <h6 class="mt-3">Active Orders</h6>
                    <h5>  25,000</h5>
                  </div>
                </div>
                </div>


                <div class="col-lg-3 col-6 mb-3">
                  <div class="card border-0" style="background: #FFE9E5;">
                    <div class="card-body text-center py-2">
                      <img src="asset/img/dashboard/product.png" alt="" class="mt-5"> 
                      <h6 class="mt-3">Pending Deliveries</h6>
                      <h5>  50</h5>
                    </div>
                  </div>
                  </div>

                  

                  <div class="col-lg-3 col-6 mb-3">
                    <div class="card border-0" style="background: #FFE9E5;">
                      <div class="card-body text-center py-2">
                        <img src="asset/img/dashboard/cart.png" alt="" class="mt-5"> 
                        <h6 class="mt-3">Open Complaints</h6>
                        <h5>2</h5>
                      </div>
                    </div>
                    </div>

                    
                  
      

                
            </div>

              
            </div>
        </div>
    </div>
</section>




 <!-- footer start -->
 <footer class="footer py-5 bg-dark text-white">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-3">
        <h5>MyShop</h5>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.
         
        </p>
        <div class="detail">
          <p><i class="fa-solid fa-phone"></i> +01 112 352 566</p> 
         <p> <i class="fa-solid fa-location-dot"></i> Mymensingh , Bangladesh </p> 

         <p> <i class="fa-regular fa-envelope"></i> devrahatkhan@gmail.com </p> 
        </div>
      </div>
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-4 col-6 mb-3">
            <h1>Quick Links</h1>
            <ul class="list-unstyled">
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Update News</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Testiomonials</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Privacy & Policy</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Our Dealler </a></li>
              </ul>
          </div>

          <div class="col-lg-4 col-6 mb-3">
            <h1>Support Center</h1>
            <ul class="list-unstyled">
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> FAQ  </a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Update News</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Affilites</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Privacy & Policy</a></li>
              <li><a href="" class="decoration text-white"><i class="fa-solid fa-chevron-right"></i> Contact Us </a></li>
              </ul>
          </div>

          <div class="col-lg-4 mb-3">
            <h1>Newsletter</h1>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Unde repellat quidem similique tempore laboriosam neque ex, alias error nihil sapiente?</p>
            <input type="text" class="form-control" placeholder="Your Email" name="" id="">
            <button class="btn primary-bg text-white px-3 py-2 mt-3">Subscribe Now <i class="fa-solid fa-paper-plane"></i></button>
          </div>
          </div>
          </div>
<div class="row mt-3">
          <div class="col-lg-6 mb-3">
            <p>© Copyright 2023 MOTEX All Rights Reserved. Design & Coustomize  : Rahat Khan</p>
          </div>

          <div class="col-lg-4 d-flex justify-content-end">
            <ul class="d-flex  ">
              <li><i class="fa-brands fa-facebook-f fs-2 pe-5"></i></li>
              <li><i class="fa-brands fa-twitter fs-2 pe-5"></i></li>
              <li><i class="fa-brands fa-instagram fs-2 pe-5"></i></li>
            </ul>
          </div>
        </div>

        </div>
      </div>
    </div>
  </div>
</footer>
<!-- footer end -->

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



