<style>
.dashboard-menu{border:0!important;border-radius:15px!important;background:#172b49!important;box-shadow:0 12px 28px rgba(18,34,56,.16);padding:16px!important;overflow:hidden}.dashboard-menu .profile{border-bottom:1px solid rgba(255,255,255,.12);color:#fff}.dashboard-menu .profile>img{width:62px;height:62px;object-fit:cover;border:3px solid rgba(255,255,255,.24)}.dashboard-menu .profile h6{font-weight:800}.dashboard-menu .profile h3{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:#a8c1f2;margin:12px 0 2px}.dashboard-menu .profile p{color:#a8b8d0;font-size:.75rem}.dashboard-menu .list-unstyled>li>a{display:flex;padding:10px 11px;margin:3px 0;border-radius:8px;color:#c8d5e7!important;font-size:.83rem;font-weight:700;transition:.2s}.dashboard-menu .list-unstyled>li>a:before{content:'›';color:#82a6ff;font-size:18px;line-height:12px;margin-right:8px}.dashboard-menu .list-unstyled>li>a:hover{background:#29466e;color:#fff!important;transform:translateX(3px)}.dashboard-menu .accordion-item{background:transparent;border:0}.dashboard-menu .accordion-button{background:#233c60;color:#fff;border-radius:8px!important;font-size:.8rem;font-weight:700;box-shadow:none}.dashboard-menu .accordion-body{background:#122238;padding:8px;border-radius:0 0 8px 8px}.dashboard-menu .dropdown-item{color:#c8d5e7!important;font-size:.78rem;border-radius:6px}.dashboard-menu .dropdown-item:hover{background:#29466e;color:#fff!important}
</style>
<div class="col-lg-3 mb-3">
    <div class="dashboard-menu card">
        <div class="profile text-center pb-3">
            <img src="{{ asset('asset/img/dashboard/profile.png') }}" alt="Profile Picture" class="img-fluid rounded-circle mb-2">
        
            @guest('web')
                @guest('customer')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if(Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <h6>{{ Auth::guard('customer')->user()->name }}</h6>
                @endguest
            @else
                <h6>{{ Auth::guard('web')->user()->name }}</h6>
            @endguest
            
              @php
    // Fetch AssignSalesman for the logged-in user (using user_id)
    $assignSalesman = \App\Models\AssignSalesman::where('user_id', Auth::id())->first();
@endphp

@if($assignSalesman)
    <div class="row">
        <div class="col-md-12">
            <h3>Our Manager</h3>
            <ul>
                <strong> {{ $assignSalesman->name }}</strong>
                
            </ul>
            <!-- Button to trigger the modal -->
            <button type="button" class="btn " data-bs-toggle="modal" data-bs-target="#assignSalesmanModal">
                More Details
            </button>
        </div>
    </div>

    <!-- Modal for More Details -->
    <div class="modal fade" id="assignSalesmanModal"  aria-labelledby="assignSalesmanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignSalesmanModalLabel">Assign Salesman Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><strong>Name:</strong> {{ $assignSalesman->name }}</li>
                        <li><strong>Email:</strong> {{ $assignSalesman->email }}</li>
                        <li><strong>Phone Number:</strong> {{ $assignSalesman->number }}</li>
                        <li><strong>Assigned User Name:</strong> {{ $assignSalesman->user->name }}</li> <!-- Show the User's name -->
                        <li><strong>Profile Image:</strong> 
                            @if($assignSalesman->getProfileImageUrl())
                                <img src="{{ $assignSalesman->getProfileImageUrl() }}" alt="Profile Image" width="100">
                            @else
                                {{ trans('global.no_image') }}
                            @endif
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@else
    <p>No Assign Salesman found for this user.</p>
@endif
        
          
        </div>
        

        <ul class="list-unstyled mt-3">
           

           

            @php
                $user = Auth::guard('web')->user();
                $customer = Auth::guard('customer')->user();
            @endphp

            @if($user)
                @php
                    $businessType = $user->business_type ?? null;
                @endphp

                @if($businessType === 'Retailer')
                <li>
                <a href="{{ route('frontend.home') }}" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
                <li>
                <a class="dropdown-item" href="{{ route('frontend.profile.index') }}">{{ __('My Profile') }}</a>
            </li>
                    <li>
                        <a href="{{ route('frontend.replacements.index') }}" class="text-decoration-none">
                            Replacement
                        </a>
                    </li>
                @elseif($businessType === 'Manufacturer')
                    <li>
                        <a href="{{ route('frontend.replacements.company') }}" class="text-decoration-none">
                            Replacement
                        </a>
                    </li>
                
                
                 @elseif($businessType === 'Sells Man')
                    <li>
                        <a href="{{ route('frontend.home') }}" class="text-decoration-none">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('frontend.profile.index') }}">{{ __('My Profile') }}</a>    
                    </li>
                    <li>
                        <a href="{{ route('frontend.add-amounts.index') }}" class="text-decoration-none">
                            Add Amount
                        </a>
                    </li>
                @endif
            @elseif($customer)
            <li>
                <a href="{{ route('customer.dashboard') }}" class="text-decoration-none">
                    Dashboard
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('customer.profile') }}">{{ __('My Profile') }}</a>
            </li>
                <li>
                    <a href="{{ route('frontend.customer-replacements.index') }}" class="text-decoration-none">
                        Customer Replacement
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.customer-orders.index') }}" class="text-decoration-none">
                        Customer Order
                    </a>
                </li>

                <li>
                    <a href="{{ route('check-orders.pending') }}" class="text-decoration-none">
                        Pending Order
                    </a>
                </li>
            @endif
           
            @php
             $businessType = $user->business_type ?? null;
            @endphp
             @if($businessType === 'Retailer')
            <li>
                <a href="{{ route('frontend.orders.index') }}" class="text-decoration-none">
                    Order
                </a>
            </li>

            <li>
                <a href="{{ route('frontend.check-orders.pending') }}" class="text-decoration-none">
                    Pending Order
                </a>
            </li>
            @endif

            <div class="accordion" id="menuAccordion">
                <!-- Asset Management -->
                @can('asset_management_access')
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="assetManagementHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#assetManagementCollapse" aria-expanded="false" aria-controls="assetManagementCollapse">
                                {{ trans('cruds.assetManagement.title') }}
                            </button>
                        </h2>
                        <div id="assetManagementCollapse" class="accordion-collapse collapse" aria-labelledby="assetManagementHeading" data-bs-parent="#menuAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    @can('asset_access')
                                        <li><a class="dropdown-item" href="{{ route('frontend.assets.index') }}">{{ trans('cruds.asset.title') }}</a></li>
                                    @endcan

                                    @can('asset_category_access')
                                        <li><a class="dropdown-item ml-3" href="{{ route('frontend.asset-categories.index') }}">{{ trans('cruds.assetCategory.title') }}</a></li>
                                    @endcan

                                    @can('asset_location_access')
                                        <li><a class="dropdown-item ml-3" href="{{ route('frontend.asset-locations.index') }}">{{ trans('cruds.assetLocation.title') }}</a></li>
                                    @endcan

                                    @can('asset_status_access')
                                        <li><a class="dropdown-item ml-3" href="{{ route('frontend.asset-statuses.index') }}">{{ trans('cruds.assetStatus.title') }}</a></li>
                                    @endcan

                                    @can('assets_history_access')
                                        <li><a class="dropdown-item ml-3" href="{{ route('frontend.assets-histories.index') }}">{{ trans('cruds.assetsHistory.title') }}</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                @endcan

                <!-- Expense Management -->
                @can('expense_management_access')
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="expenseManagementHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expenseManagementCollapse" aria-expanded="false" aria-controls="expenseManagementCollapse">
                                {{ trans('cruds.expenseManagement.title') }}
                            </button>
                        </h2>
                        <div id="expenseManagementCollapse" class="accordion-collapse collapse" aria-labelledby="expenseManagementHeading" data-bs-parent="#menuAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                   @can('expense_category_access')
    <li><a class="dropdown-item ml-3" href="{{ route('frontend.expense-categories.index') }}">{{ trans('cruds.expenseCategory.title') }}</a></li>
@endcan

@can('income_category_access')
    <li><a class="dropdown-item ml-3" href="{{ route('frontend.income-categories.index') }}">{{ trans('cruds.incomeCategory.title') }}</a></li>
@endcan

@can('expense_access')
    <li><a class="dropdown-item ml-3" href="{{ route('frontend.expenses.index') }}">{{ trans('cruds.expense.title') }}</a></li>
@endcan

@can('income_access')
    <li><a class="dropdown-item ml-3" href="{{ route('frontend.incomes.index') }}">{{ trans('cruds.income.title') }}</a></li>
@endcan



                                </ul>
                            </div>
                        </div>
                    </div>
                @endcan
                
               @php($unread = \App\Models\QaTopic::unreadCount())
<li class="{{ request()->is('admin/messenger') || request()->is('admin/messenger/*') ? 'active' : '' }}">
    <a href="{{ route('admin.messenger.index') }}">
        <i class="fa-fw fa fa-envelope"></i>
        <span>{{ trans('global.messages') }}</span>
        @if($unread > 0)
            <strong>( {{ $unread }} )</strong>
        @endif
    </a>
</li>


            </div>

            @auth
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        </ul>
    </div>
</div>
