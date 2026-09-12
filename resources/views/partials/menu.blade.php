<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <ul class="sidebar-menu tree" data-widget="tree">
            <li>
                <a href="{{ route("admin.home") }}">
                    <i class="fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            @can('user_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-users">

                        </i>
                        <span>{{ trans('cruds.userManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('permission_access')
                            <li class="{{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "active" : "" }}">
                                <a href="{{ route("admin.permissions.index") }}">
                                    <i class="fa-fw fas fa-unlock-alt">

                                    </i>
                                    <span>{{ trans('cruds.permission.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="{{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "active" : "" }}">
                                <a href="{{ route("admin.roles.index") }}">
                                    <i class="fa-fw fas fa-briefcase">

                                    </i>
                                    <span>{{ trans('cruds.role.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="{{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                <a href="{{ route("admin.users.index") }}">
                                    <i class="fa-fw fas fa-user">

                                    </i>
                                    <span>{{ trans('cruds.user.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('audit_log_access')
                            <li class="{{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                <a href="{{ route("admin.audit-logs.index") }}">
                                    <i class="fa-fw fas fa-file-alt">

                                    </i>
                                    <span>{{ trans('cruds.auditLog.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('product_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-shopping-cart">

                        </i>
                        <span>{{ trans('cruds.productManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @php
                        $user = auth()->user();
                        @endphp
                         @if($user && $user->roles()->where('title', 'Admin'))
                        @can('add_company_access')
                        <li class="">
                            <a href="{{ route('admin.godowns.index') }}">
                                <i class="fas fa-warehouse fa-fw"></i>
                                <span>Godown</span>
                            </a>
                            
                        </li>
                        @endcan
                        @endif
                        @can('add_company_access')
                            <li class="{{ request()->is("admin/add-companies") || request()->is("admin/add-companies/*") ? "active" : "" }}">
                                <a href="{{ route("admin.add-companies.index") }}">
                                    <i class="fa-fw fas fa-building">

                                    </i>
                                    <span>{{ trans('cruds.addCompany.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('product_category_access')
                            <li class="{{ request()->is("admin/product-categories") || request()->is("admin/product-categories/*") ? "active" : "" }}">
                                <a href="{{ route("admin.product-categories.index") }}">
                                    <i class="fa-fw fas fa-folder">

                                    </i>
                                    <span>{{ trans('cruds.productCategory.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('product_tag_access')
                            <li class="{{ request()->is("admin/product-tags") || request()->is("admin/product-tags/*") ? "active" : "" }}">
                                <a href="{{ route("admin.product-tags.index") }}">
                                    <i class="fa-fw fas fa-folder">

                                    </i>
                                    <span>{{ trans('cruds.productTag.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('product_access')
                            <li class="{{ request()->is("admin/products") || request()->is("admin/products/*") ? "active" : "" }}">
                                <a href="{{ route("admin.products.index") }}">
                                    <i class="fa-fw fas fa-shopping-cart">

                                    </i>
                                    <span>{{ trans('cruds.product.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @php
            $user = auth()->user();
            @endphp

            @if($user && $user->roles()->where('title', 'Admin'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-user"></i>
                        <span>Supplier</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('asset_category_access')
                            <li class="">
                                <a href="{{ route('admin.suppliers.index') }}">
                                    <i class="fa-fw fas fa-tags"></i>
                                    <span>Create Supplier</span>
                                </a>
                            </li>
                        @endcan
                        @can('asset_location_access')
                            <li class="">
                                <a href="{{ route('admin.suppliers.Product') }}">
                                    <i class="fa-fw fas fa-shopping-cart"></i>
                                    <span>Add Product</span>
                                </a>
                            </li>
                        @endcan
                        @can('asset_status_access')
                            <li class="">
                                <a href="{{ route('admin.suppliers.products.history') }}">
                                    <i class="fa-fw fas fa-server"></i>
                                    <span>History</span>
                                </a>
                            </li>
                        @endcan
                        @can('asset_access')
                            <li class="">
                                <a href="{{ route('admin.suppliers.listProduct') }}">
                                    <i class="fa-fw fas fa-book"></i>
                                    <span>List Product</span>
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endif

            @can('stocks_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-store-alt">

                        </i>
                        <span>{{ trans('cruds.stocksManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('our_stock_access')
                            <li class="{{ request()->is("admin/our-stocks") || request()->is("admin/our-stocks/*") ? "active" : "" }}">
                                <a href="{{ route("admin.our-stocks.index") }}">
                                    <i class="fa-fw fas fa-luggage-cart">

                                    </i>
                                    <span>{{ trans('cruds.ourStock.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('check_godown_access')
                            <li class="">
                                <a href="{{ route("admin.godowns.info") }}">
                                    <i class="fa-fw far fa-arrow-alt-circle-right">

                                    </i>
                                    <span>{{ trans('cruds.checkGodown.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('stock_transfer_access')
                            <li class="{{ request()->is("admin/stock-transfers") || request()->is("admin/stock-transfers/*") ? "active" : "" }}">
                                <a href="{{ route("admin.stock-transfers.index") }}">
                                    <i class="fa-fw fas fa-exchange-alt">

                                    </i>
                                    <span>{{ trans('cruds.stockTransfer.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('order_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-sitemap">

                        </i>
                        <span>{{ trans('cruds.order.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('check_order_access')
                            <li class="{{ request()->is("admin/check-orders") || request()->is("admin/check-orders/*") ? "active" : "" }}">
                                <a href="{{ route("admin.check-orders.index") }}">
                                    <i class="fa-fw fas fa-shopping-cart">

                                    </i>
                                    <span>{{ trans('cruds.checkOrder.title') }}</span>

                                </a>
                            </li>
                        @endcan

                        @can('check_order_access')
                        <li class="{{ request()->is("admin/check-orders") || request()->is("admin/check-orders/*") ? "active" : "" }}">
                            <a href="{{ route("admin.check-orders.pending") }}">
                                <i class="fa-fw fas fa-shopping-cart">

                                </i>
                                <span>Pending Order (Stock Out)</span>

                            </a>
                        </li>
                    @endcan
                    
                        @can('cancellation_access')
                            <li class="{{ request()->is("admin/cancellations") || request()->is("admin/cancellations/*") ? "active" : "" }}">
                                <a href="{{ route("admin.cancellations.index") }}">
                                    <i class="fa-fw fas fa-ban">

                                    </i>
                                    <span>{{ trans('cruds.cancellation.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('wallet_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-wallet">

                        </i>
                        <span>{{ trans('cruds.walletManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('wallet_request_access')
                            <li class="{{ request()->is("admin/wallet-requests") || request()->is("admin/wallet-requests/*") ? "active" : "" }}">
                                <a href="{{ route("admin.wallet-requests.index") }}">
                                    <i class="fa-fw far fa-bell">

                                    </i>
                                    <span>{{ trans('cruds.walletRequest.title') }}</span>

                                </a>
                            </li>

                        @endcan
                     
                        <!-- Transactions Section -->
                        <li class="">
                            <a href="{{ route('admin.transactions.index') }}">
                                <i class="fa-fw fas fa-exchange-alt"></i>
                                <span>Transaction</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('admin.report.show') }}">
                                <i class="fa-fw fas fa-exchange-alt"></i>
                                <span>Report Statement</span>
                            </a>
                        </li>
                    
                    </ul>
                </li>
            @endcan
            @can('shipping_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-shipping-fast">

                        </i>
                        <span>{{ trans('cruds.shipping.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('carrier_access')
                            <li class="{{ request()->is("admin/carriers") || request()->is("admin/carriers/*") ? "active" : "" }}">
                                <a href="{{ route("admin.carriers.index") }}">
                                    <i class="fa-fw fas fa-people-carry">

                                    </i>
                                    <span>{{ trans('cruds.carrier.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('support_desk_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-desktop">

                        </i>
                        <span>{{ trans('cruds.supportDesk.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('dispute_access')
                            <li class="{{ request()->is("admin/disputes") || request()->is("admin/disputes/*") ? "active" : "" }}">
                                <a href="{{ route("admin.disputes.index") }}">
                                    <i class="fa-fw fab fa-discourse">

                                    </i>
                                    <span>{{ trans('cruds.dispute.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('refund_access')
                            <li class="{{ request()->is("admin/refunds") || request()->is("admin/refunds/*") ? "active" : "" }}">
                                <a href="{{ route("admin.refunds.index") }}">
                                    <i class="fa-fw fas fa-retweet">

                                    </i>
                                    <span>{{ trans('cruds.refund.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('setting_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-cogs">

                        </i>
                        <span>{{ trans('cruds.setting.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('tax_access')
                            <li class="{{ request()->is("admin/taxes") || request()->is("admin/taxes/*") ? "active" : "" }}">
                                <a href="{{ route("admin.taxes.index") }}">
                                    <i class="fa-fw fas fa-file-invoice-dollar">

                                    </i>
                                    <span>{{ trans('cruds.tax.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('shop_setting_access')
                            <li class="{{ request()->is("admin/shop-settings") || request()->is("admin/shop-settings/*") ? "active" : "" }}">
                                <a href="{{ route("admin.shop-settings.index") }}">
                                    <i class="fa-fw fas fa-shopping-bag">

                                    </i>
                                    <span>{{ trans('cruds.shopSetting.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('configuration_access')
                            <li class="{{ request()->is("admin/configurations") || request()->is("admin/configurations/*") ? "active" : "" }}">
                                <a href="{{ route("admin.configurations.index") }}">
                                    <i class="fa-fw fas fa-check">

                                    </i>
                                    <span>{{ trans('cruds.configuration.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('support_access')
                            <li class="{{ request()->is("admin/supports") || request()->is("admin/supports/*") ? "active" : "" }}">
                                <a href="{{ route("admin.supports.index") }}">
                                    <i class="fa-fw fas fa-headset">

                                    </i>
                                    <span>{{ trans('cruds.support.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('web_setting_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-globe-americas">

                        </i>
                        <span>{{ trans('cruds.webSetting.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('privacy_policy_access')
                            <li class="{{ request()->is("admin/privacy-policies") || request()->is("admin/privacy-policies/*") ? "active" : "" }}">
                                <a href="{{ route("admin.privacy-policies.index") }}">
                                    <i class="fa-fw fas fa-user-secret">

                                    </i>
                                    <span>{{ trans('cruds.privacyPolicy.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('term_condition_access')
                            <li class="{{ request()->is("admin/term-conditions") || request()->is("admin/term-conditions/*") ? "active" : "" }}">
                                <a href="{{ route("admin.term-conditions.index") }}">
                                    <i class="fa-fw fas fa-terminal">

                                    </i>
                                    <span>{{ trans('cruds.termCondition.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('about_us_access')
                            <li class="{{ request()->is("admin/about-uss") || request()->is("admin/about-uss/*") ? "active" : "" }}">
                                <a href="{{ route("admin.about-uss.index") }}">
                                    <i class="fa-fw fab fa-accusoft">

                                    </i>
                                    <span>{{ trans('cruds.aboutUs.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('user_alert_access')
                <li class="{{ request()->is("admin/user-alerts") || request()->is("admin/user-alerts/*") ? "active" : "" }}">
                    <a href="{{ route("admin.user-alerts.index") }}">
                        <i class="fa-fw fas fa-bell">

                        </i>
                        <span>{{ trans('cruds.userAlert.title') }}</span>

                    </a>
                </li>
            @endcan

            @can('customer_access')
    <li class="{{ request()->is('admin/customers') || request()->is('admin/customers/*') ? 'active' : '' }}">
        <a href="{{ route('admin.customers.index') }}">
            <i class="fa-fw fas fa-users"></i>
            <span>Customers</span>
        </a>
    </li>
@endcan

@can('verification_access')
    <li class="{{ request()->is('admin/verifications') || request()->is('admin/verifications/*') ? 'active' : '' }}">
        <a href="{{ route('admin.verifications.index') }}">
            <i class="fa-fw fas fa-id-badge"></i>
            <span>Verifications</span>
        </a>
    </li>
@endcan



            @can('asset_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-book">

                        </i>
                        <span>{{ trans('cruds.assetManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('asset_category_access')
                            <li class="{{ request()->is("admin/asset-categories") || request()->is("admin/asset-categories/*") ? "active" : "" }}">
                                <a href="{{ route("admin.asset-categories.index") }}">
                                    <i class="fa-fw fas fa-tags">

                                    </i>
                                    <span>{{ trans('cruds.assetCategory.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('asset_location_access')
                            <li class="{{ request()->is("admin/asset-locations") || request()->is("admin/asset-locations/*") ? "active" : "" }}">
                                <a href="{{ route("admin.asset-locations.index") }}">
                                    <i class="fa-fw fas fa-map-marker">

                                    </i>
                                    <span>{{ trans('cruds.assetLocation.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('asset_status_access')
                            <li class="{{ request()->is("admin/asset-statuses") || request()->is("admin/asset-statuses/*") ? "active" : "" }}">
                                <a href="{{ route("admin.asset-statuses.index") }}">
                                    <i class="fa-fw fas fa-server">

                                    </i>
                                    <span>{{ trans('cruds.assetStatus.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('asset_access')
                            <li class="{{ request()->is("admin/assets") || request()->is("admin/assets/*") ? "active" : "" }}">
                                <a href="{{ route("admin.assets.index") }}">
                                    <i class="fa-fw fas fa-book">

                                    </i>
                                    <span>{{ trans('cruds.asset.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('assets_history_access')
                            <li class="{{ request()->is("admin/assets-histories") || request()->is("admin/assets-histories/*") ? "active" : "" }}">
                                <a href="{{ route("admin.assets-histories.index") }}">
                                    <i class="fa-fw fas fa-th-list">

                                    </i>
                                    <span>{{ trans('cruds.assetsHistory.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            
            @can('expense_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-money-bill">

                        </i>
                        <span>{{ trans('cruds.expenseManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('expense_category_access')
                            <li class="{{ request()->is("admin/expense-categories") || request()->is("admin/expense-categories/*") ? "active" : "" }}">
                                <a href="{{ route("admin.expense-categories.index") }}">
                                    <i class="fa-fw fas fa-list">

                                    </i>
                                    <span>{{ trans('cruds.expenseCategory.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('income_category_access')
                            <li class="{{ request()->is("admin/income-categories") || request()->is("admin/income-categories/*") ? "active" : "" }}">
                                <a href="{{ route("admin.income-categories.index") }}">
                                    <i class="fa-fw fas fa-list">

                                    </i>
                                    <span>{{ trans('cruds.incomeCategory.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('expense_access')
                            <li class="{{ request()->is("admin/expenses") || request()->is("admin/expenses/*") ? "active" : "" }}">
                                <a href="{{ route("admin.expenses.index") }}">
                                    <i class="fa-fw fas fa-arrow-circle-right">

                                    </i>
                                    <span>{{ trans('cruds.expense.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('income_access')
                            <li class="{{ request()->is("admin/incomes") || request()->is("admin/incomes/*") ? "active" : "" }}">
                                <a href="{{ route("admin.incomes.index") }}">
                                    <i class="fa-fw fas fa-arrow-circle-right">

                                    </i>
                                    <span>{{ trans('cruds.income.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('expense_report_access')
                            <li class="{{ request()->is("admin/expense-reports") || request()->is("admin/expense-reports/*") ? "active" : "" }}">
                                <a href="{{ route("admin.expense-reports.index") }}">
                                    <i class="fa-fw fas fa-chart-line">

                                    </i>
                                    <span>{{ trans('cruds.expenseReport.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            
                 @can('replacement_access')
    <li class="{{ request()->is("admin/company/replacements") || request()->is("admin/company/replacements/*") ? "active" : "" }}">
        <a href="{{ route("admin.replacements.company") }}">
            <i class="fa-fw fas fa-sync-alt"></i>
            <span>Company Replacements</span>
        </a>
    </li>
@endcan

@can('transaction_access') {{-- You can use appropriate permission --}}
    <li class="{{ request()->is('admin/manual-payment') || request()->is('admin/manual-payment/*') ? 'active' : '' }}">
        <a href="{{ route('admin.manual.payment.index') }}">
            <i class="fa-fw fas fa-money-check-alt"></i>
            <span>Manual Payments</span>
        </a>
    </li>
@endcan

@can('assign_salesman_access') {{-- You can use appropriate permission --}}
    <li class="{{ request()->is('admin/assign-salesmen') || request()->is('admin/assign-salesmen/*') ? 'active' : '' }}">
<a href="{{ route('admin.assign-salesmen.index') }}">
            <i class="fa-fw fas fa-user-tie"></i>
            <span>Assign Salesmen</span>
        </a>
    </li>
@endcan

@can('add_amount_access')
    <li class="{{ request()->is('admin/add-amounts') || request()->is('admin/add-amounts/*') ? 'active' : '' }}">
        <a href="{{ route('admin.add-amounts.index') }}">
            <i class="fa-fw fas fa-money-check-alt"></i>
            <span>Add Amount</span>
        </a>
    </li>
@endcan
            
            
            
            
            @php($unread = \App\Models\QaTopic::unreadCount())
                <li class="{{ request()->is("admin/messenger") || request()->is("admin/messenger/*") ? "active" : "" }}">
                    <a href="{{ route("admin.messenger.index") }}">
                        <i class="fa-fw fa fa-envelope">

                        </i>
                        <span>{{ trans('global.messages') }}</span>
                        @if($unread > 0)
                            <strong>( {{ $unread }} )</strong>
                        @endif

                    </a>
                </li>
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="{{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}">
                            <a href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key">
                                </i>
                                {{ trans('global.change_password') }}
                            </a>
                        </li>
                    @endcan
                @endif
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt">

                        </i>
                        {{ trans('global.logout') }}
                    </a>
                </li>
        </ul>
    </section>
</aside>