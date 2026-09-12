<?php

// Route::view('/', 'welcome');

use App\Http\Controllers\Admin\CheckGodownController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockTransferController;
use App\Http\Controllers\Admin\WalletRequestController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Custom\CheckOrderController;
use App\Http\Controllers\Custom\DeliveryController;
use App\Http\Controllers\CustomerAuthController;
use App\Models\StockTransfer;
use App\Http\Controllers\ReplacementController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WishlistController;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use App\Models\CheckOrder;
use App\Models\Replacement;
use App\Http\Controllers\Admin\GodownController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Frontend\CustomerPaymentController;
use App\Http\Controllers\AssignSalesmanController;
use App\Http\Controllers\Admin\AddExpenseAmountController;







Auth::routes();


Route::prefix('customer')->group(function () {
    // Registration Routes
    Route::get('register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('register', [CustomerAuthController::class, 'register']);
    
    // Login Routes
    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [CustomerAuthController::class, 'login'])->name('customer.log');

    // Grouping routes that require customer authentication
    Route::middleware(['customer'])->group(function () {
        Route::get('dashboard', [CustomerAuthController::class, 'dashboard'])->name('customer.dashboard');
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

        Route::get('profile', [CustomerAuthController::class, 'showProfileForm'])->name('customer.profile');
        Route::get('/profile', [DeliveryController::class, 'profile'])->name('customer.profile');
        Route::get('pending-orders', [CheckOrderController::class, 'customerpendingOrders'])->name('check-orders.pending');


    });
});
Route::middleware('customer')->group(function () {
    Route::post('/verifications/store', [App\Http\Controllers\VerificationController::class, 'store'])->name('verifications.store');
});




Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
});



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    // custom route
    Route::get('/stock-transfers/{id}/download-pdf', [StockTransferController::class, 'downloadPdf'])->name('stock-transfers.download-pdf');
    Route::resource('transactions', TransactionController::class)->names([
        'index'   => 'transactions.index',
        'create'  => 'transactions.create',
        'store'   => 'transactions.store',
        'show'    => 'transactions.show',
        'edit'    => 'transactions.edit',
        'update'  => 'transactions.update',
        'destroy' => 'transactions.destroy',
    ]);
    Route::get('admin/reports', [ReportController::class, 'showReport'])->name('report.show');
    Route::resource('suppliers', SupplierController::class);
    Route::get('/add-product', [SupplierController::class, 'Products'])->name('suppliers.Product'); // Show add product form
    Route::post('/store-product', [SupplierController::class, 'StoreProduct'])->name('suppliers.storeProduct'); // Save product
    Route::get('/List-products', [SupplierController::class, 'List'])->name('suppliers.listProduct'); // List products
    Route::get('/view-product/{id}', [SupplierController::class, 'showProduct'])->name('suppliers.viewProduct'); // View product
    Route::get('/edit-product/{id}/edit', [SupplierController::class, 'Productedit'])->name('suppliers.editProduct'); // Edit product form
    Route::post('/update-product/{id}', [SupplierController::class, 'Productupdate'])->name('suppliers.updateProduct'); // Update product
    Route::delete('/product/{id}', [SupplierController::class, 'destroy'])->name('suppliers.deleteProduct'); // Delete product
    Route::get('/history', [SupplierController::class, 'history'])->name('suppliers.products.history');
    Route::get('/history/export/{supplier_id}', [SupplierController::class, 'exportHistory'])->name('suppliers.products.history.export');
    // customer admin 
    Route::delete('customers/destroy', [CustomerController::class, 'massDestroy'])->name('customers.massDestroy');
    Route::resource('customers', CustomerController::class);
    
    
    // customer verification
    Route::delete('verifications/destroy', [VerificationController::class, 'massDestroy'])->name('verifications.massDestroy');
    Route::resource('verifications', VerificationController::class);
    

    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');


    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Product Category
    Route::delete('product-categories/destroy', 'ProductCategoryController@massDestroy')->name('product-categories.massDestroy');
    Route::post('product-categories/media', 'ProductCategoryController@storeMedia')->name('product-categories.storeMedia');
    Route::post('product-categories/ckmedia', 'ProductCategoryController@storeCKEditorImages')->name('product-categories.storeCKEditorImages');
    Route::resource('product-categories', 'ProductCategoryController');

    // Product Tag
    Route::delete('product-tags/destroy', 'ProductTagController@massDestroy')->name('product-tags.massDestroy');
    Route::resource('product-tags', 'ProductTagController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductController');

    // Add Company
    Route::delete('add-companies/destroy', 'AddCompanyController@massDestroy')->name('add-companies.massDestroy');
    Route::post('add-companies/media', 'AddCompanyController@storeMedia')->name('add-companies.storeMedia');
    Route::post('add-companies/ckmedia', 'AddCompanyController@storeCKEditorImages')->name('add-companies.storeCKEditorImages');
    Route::resource('add-companies', 'AddCompanyController');

    // Our Stock
    Route::delete('our-stocks/destroy', 'OurStockController@massDestroy')->name('our-stocks.massDestroy');
    Route::resource('our-stocks', 'OurStockController');

    // Check Godown
    Route::delete('check-godowns/destroy', 'CheckGodownController@massDestroy')->name('check-godowns.massDestroy');
    Route::resource('check-godowns', 'CheckGodownController');

    // Stock Transfer
    Route::delete('stock-transfers/destroy', 'StockTransferController@massDestroy')->name('stock-transfers.massDestroy');
    Route::resource('stock-transfers', 'StockTransferController');

    // Check Order
    Route::delete('check-orders/destroy', 'CheckOrderController@massDestroy')->name('check-orders.massDestroy');
    Route::post('check-orders/media', 'CheckOrderController@storeMedia')->name('check-orders.storeMedia');
    Route::post('check-orders/ckmedia', 'CheckOrderController@storeCKEditorImages')->name('check-orders.storeCKEditorImages');
    Route::resource('check-orders', 'CheckOrderController');

    // Carrier
    Route::delete('carriers/destroy', 'CarrierController@massDestroy')->name('carriers.massDestroy');
    Route::post('carriers/media', 'CarrierController@storeMedia')->name('carriers.storeMedia');
    Route::post('carriers/ckmedia', 'CarrierController@storeCKEditorImages')->name('carriers.storeCKEditorImages');
    Route::resource('carriers', 'CarrierController');

    Route::get('pending-orders', [CheckOrderController::class, 'pendingOrders'])->name('check-orders.pending');

    // Cancellation
    Route::delete('cancellations/destroy', 'CancellationController@massDestroy')->name('cancellations.massDestroy');
    Route::resource('cancellations', 'CancellationController');

    // Disputes
    Route::delete('disputes/destroy', 'DisputesController@massDestroy')->name('disputes.massDestroy');
    Route::resource('disputes', 'DisputesController');

    // Refunds
    Route::delete('refunds/destroy', 'RefundsController@massDestroy')->name('refunds.massDestroy');
    Route::post('refunds/media', 'RefundsController@storeMedia')->name('refunds.storeMedia');
    Route::post('refunds/ckmedia', 'RefundsController@storeCKEditorImages')->name('refunds.storeCKEditorImages');
    Route::resource('refunds', 'RefundsController');

    // Taxes
    Route::delete('taxes/destroy', 'TaxesController@massDestroy')->name('taxes.massDestroy');
    Route::resource('taxes', 'TaxesController');

    // Shop Setting
    Route::delete('shop-settings/destroy', 'ShopSettingController@massDestroy')->name('shop-settings.massDestroy');
    Route::post('shop-settings/media', 'ShopSettingController@storeMedia')->name('shop-settings.storeMedia');
    Route::post('shop-settings/ckmedia', 'ShopSettingController@storeCKEditorImages')->name('shop-settings.storeCKEditorImages');
    Route::resource('shop-settings', 'ShopSettingController');

    // Configurations
    Route::delete('configurations/destroy', 'ConfigurationsController@massDestroy')->name('configurations.massDestroy');
    Route::resource('configurations', 'ConfigurationsController');

    // Support
    Route::delete('supports/destroy', 'SupportController@massDestroy')->name('supports.massDestroy');
    Route::post('supports/media', 'SupportController@storeMedia')->name('supports.storeMedia');
    Route::post('supports/ckmedia', 'SupportController@storeCKEditorImages')->name('supports.storeCKEditorImages');
    Route::resource('supports', 'SupportController');

    // Privacy Policy
    Route::delete('privacy-policies/destroy', 'PrivacyPolicyController@massDestroy')->name('privacy-policies.massDestroy');
    Route::post('privacy-policies/media', 'PrivacyPolicyController@storeMedia')->name('privacy-policies.storeMedia');
    Route::post('privacy-policies/ckmedia', 'PrivacyPolicyController@storeCKEditorImages')->name('privacy-policies.storeCKEditorImages');
    Route::resource('privacy-policies', 'PrivacyPolicyController');

    // Term Condition
    Route::delete('term-conditions/destroy', 'TermConditionController@massDestroy')->name('term-conditions.massDestroy');
    Route::post('term-conditions/media', 'TermConditionController@storeMedia')->name('term-conditions.storeMedia');
    Route::post('term-conditions/ckmedia', 'TermConditionController@storeCKEditorImages')->name('term-conditions.storeCKEditorImages');
    Route::resource('term-conditions', 'TermConditionController');

    // About Us
    Route::delete('about-uss/destroy', 'AboutUsController@massDestroy')->name('about-uss.massDestroy');
    Route::post('about-uss/media', 'AboutUsController@storeMedia')->name('about-uss.storeMedia');
    Route::post('about-uss/ckmedia', 'AboutUsController@storeCKEditorImages')->name('about-uss.storeCKEditorImages');
    Route::resource('about-uss', 'AboutUsController');

    // Wallet Request
    Route::delete('wallet-requests/destroy', 'WalletRequestController@massDestroy')->name('wallet-requests.massDestroy');
    Route::resource('wallet-requests', 'WalletRequestController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Asset Category
    Route::delete('asset-categories/destroy', 'AssetCategoryController@massDestroy')->name('asset-categories.massDestroy');
    Route::resource('asset-categories', 'AssetCategoryController');

    // Asset Location
    Route::delete('asset-locations/destroy', 'AssetLocationController@massDestroy')->name('asset-locations.massDestroy');
    Route::resource('asset-locations', 'AssetLocationController');

    // Asset Status
    Route::delete('asset-statuses/destroy', 'AssetStatusController@massDestroy')->name('asset-statuses.massDestroy');
    Route::resource('asset-statuses', 'AssetStatusController');

    // Asset
    Route::delete('assets/destroy', 'AssetController@massDestroy')->name('assets.massDestroy');
    Route::post('assets/media', 'AssetController@storeMedia')->name('assets.storeMedia');
    Route::post('assets/ckmedia', 'AssetController@storeCKEditorImages')->name('assets.storeCKEditorImages');
    Route::resource('assets', 'AssetController');

    // Assets History
    Route::resource('assets-histories', 'AssetsHistoryController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::resource('incomes', 'IncomeController');

    // Expense Report
    Route::delete('expense-reports/destroy', 'ExpenseReportController@massDestroy')->name('expense-reports.massDestroy');
    Route::resource('expense-reports', 'ExpenseReportController');

    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');

    // godown model
    // Assuming the controller is in App\Http\Controllers\Admin folder
    Route::resource('godowns', GodownController::class);
    // web.php check godown new version
    Route::get('/godown/info', [GodownController::class, 'infoPage'])->name('godowns.info');
    Route::get('/godowns/details/{id}', [GodownController::class, 'getDetails'])->name('godowns.details');
    Route::get('/admin/company-products/{id}', [GodownController::class, 'getCompanyProducts']);
    Route::post('/admin/godowns/transfer', [GodownController::class, 'transfer'])->name('godowns.transfer');
    
      // 🏢 Company Replacement Management
     Route::get('/company/replacements', [ReplacementController::class, 'company'])->name('replacements.company');
     Route::get('/company/replacements/{id}', [ReplacementController::class, 'show'])->name('replacements.show');
     Route::get('/company/replacements/{id}/edit', [ReplacementController::class, 'edit'])->name('replacements.edit');
     Route::put('/company/replacements/{id}', [ReplacementController::class, 'update'])->name('replacements.update');
     Route::delete('/company/replacements/{id}', [ReplacementController::class, 'destroy'])->name('replacements.destroy');
     Route::get('/company/replacements/{id}/download', [ReplacementController::class, 'downloadInvoice'])->name('replacements.download');
 
    Route::get('/manual-payment', [TransactionController::class, 'index'])->name('manual.payment.index');
Route::get('/manual-payment/create', [TransactionController::class, 'create'])->name('manual.payment.create');
Route::post('/manual-payment', [TransactionController::class, 'store'])->name('manual.payment.store');
Route::get('/vendor/{vendor_id}/due-orders', [TransactionController::class, 'getDueOrders']);


Route::get('assign-salesmen', [AssignSalesmanController::class, 'index'])->name('assign-salesmen.index');
Route::get('assign-salesmen/create', [AssignSalesmanController::class, 'create'])->name('assign-salesmen.create');
Route::post('assign-salesmen', [AssignSalesmanController::class, 'store'])->name('assign-salesmen.store');

// 🟩 Move 'show' route ABOVE 'edit'
Route::get('assign-salesmen/{assignSalesman}', [AssignSalesmanController::class, 'show'])->name('assign-salesmen.show');

Route::get('assign-salesmen/{assignSalesman}/edit', [AssignSalesmanController::class, 'edit'])->name('assign-salesmen.edit');
Route::put('assign-salesmen/{assignSalesman}', [AssignSalesmanController::class, 'update'])->name('assign-salesmen.update');
Route::delete('assign-salesmen/{assignSalesman}', [AssignSalesmanController::class, 'destroy'])->name('assign-salesmen.destroy');

Route::post('assign-salesmen/massDestroy', [AssignSalesmanController::class, 'massDestroy'])->name('assign-salesmen.massDestroy');



 Route::get('add-amounts', [AddExpenseAmountController::class, 'index'])
        ->name('add-amounts.index');

    // Route for creating a new add amount
    Route::get('add-amounts/create', [AddExpenseAmountController::class, 'create'])
        ->name('add-amounts.create');

    // Route to store a newly created add amount
    Route::post('add-amounts', [AddExpenseAmountController::class, 'store'])
        ->name('add-amounts.store');

    // Route for showing a specific add amount
    Route::get('add-amounts/{id}', [AddExpenseAmountController::class, 'show'])
        ->name('add-amounts.show');

    // Route for editing a specific add amount
    Route::get('add-amounts/{id}/edit', [AddExpenseAmountController::class, 'edit'])
        ->name('add-amounts.edit');

    // Route to update a specific add amount
    Route::put('add-amounts/{id}', [AddExpenseAmountController::class, 'update'])
        ->name('add-amounts.update');

    // Route to delete a specific add amount
    Route::delete('add-amounts/{id}', [AddExpenseAmountController::class, 'destroy'])
        ->name('add-amounts.destroy');


});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    Route::get('pending-orders', [CheckOrderController::class, 'RetailerpendingOrders'])->name('check-orders.pending');


    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Product Category
    Route::delete('product-categories/destroy', 'ProductCategoryController@massDestroy')->name('product-categories.massDestroy');
    Route::post('product-categories/media', 'ProductCategoryController@storeMedia')->name('product-categories.storeMedia');
    Route::post('product-categories/ckmedia', 'ProductCategoryController@storeCKEditorImages')->name('product-categories.storeCKEditorImages');
    Route::resource('product-categories', 'ProductCategoryController');

    // Product Tag
    Route::delete('product-tags/destroy', 'ProductTagController@massDestroy')->name('product-tags.massDestroy');
    Route::resource('product-tags', 'ProductTagController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductController');

    // Add Company
    Route::delete('add-companies/destroy', 'AddCompanyController@massDestroy')->name('add-companies.massDestroy');
    Route::post('add-companies/media', 'AddCompanyController@storeMedia')->name('add-companies.storeMedia');
    Route::post('add-companies/ckmedia', 'AddCompanyController@storeCKEditorImages')->name('add-companies.storeCKEditorImages');
    Route::resource('add-companies', 'AddCompanyController');

    // Our Stock
    Route::delete('our-stocks/destroy', 'OurStockController@massDestroy')->name('our-stocks.massDestroy');
    Route::resource('our-stocks', 'OurStockController');

    // Check Godown
    Route::delete('check-godowns/destroy', 'CheckGodownController@massDestroy')->name('check-godowns.massDestroy');
    Route::resource('check-godowns', 'CheckGodownController');

    // Stock Transfer
    Route::delete('stock-transfers/destroy', 'StockTransferController@massDestroy')->name('stock-transfers.massDestroy');
    Route::resource('stock-transfers', 'StockTransferController');

    // Check Order
    Route::delete('check-orders/destroy', 'CheckOrderController@massDestroy')->name('check-orders.massDestroy');
    Route::post('check-orders/media', 'CheckOrderController@storeMedia')->name('check-orders.storeMedia');
    Route::post('check-orders/ckmedia', 'CheckOrderController@storeCKEditorImages')->name('check-orders.storeCKEditorImages');
    Route::resource('check-orders', 'CheckOrderController');

    // Carrier
    Route::delete('carriers/destroy', 'CarrierController@massDestroy')->name('carriers.massDestroy');
    Route::post('carriers/media', 'CarrierController@storeMedia')->name('carriers.storeMedia');
    Route::post('carriers/ckmedia', 'CarrierController@storeCKEditorImages')->name('carriers.storeCKEditorImages');
    Route::resource('carriers', 'CarrierController');

    // Cancellation
    Route::delete('cancellations/destroy', 'CancellationController@massDestroy')->name('cancellations.massDestroy');
    Route::resource('cancellations', 'CancellationController');

    // Disputes
    Route::delete('disputes/destroy', 'DisputesController@massDestroy')->name('disputes.massDestroy');
    Route::resource('disputes', 'DisputesController');

    // Refunds
    Route::delete('refunds/destroy', 'RefundsController@massDestroy')->name('refunds.massDestroy');
    Route::post('refunds/media', 'RefundsController@storeMedia')->name('refunds.storeMedia');
    Route::post('refunds/ckmedia', 'RefundsController@storeCKEditorImages')->name('refunds.storeCKEditorImages');
    Route::resource('refunds', 'RefundsController');

    // Taxes
    Route::delete('taxes/destroy', 'TaxesController@massDestroy')->name('taxes.massDestroy');
    Route::resource('taxes', 'TaxesController');

    // Shop Setting
    Route::delete('shop-settings/destroy', 'ShopSettingController@massDestroy')->name('shop-settings.massDestroy');
    Route::post('shop-settings/media', 'ShopSettingController@storeMedia')->name('shop-settings.storeMedia');
    Route::post('shop-settings/ckmedia', 'ShopSettingController@storeCKEditorImages')->name('shop-settings.storeCKEditorImages');
    Route::resource('shop-settings', 'ShopSettingController');

    // Configurations
    Route::delete('configurations/destroy', 'ConfigurationsController@massDestroy')->name('configurations.massDestroy');
    Route::resource('configurations', 'ConfigurationsController');

    // Support
    Route::delete('supports/destroy', 'SupportController@massDestroy')->name('supports.massDestroy');
    Route::post('supports/media', 'SupportController@storeMedia')->name('supports.storeMedia');
    Route::post('supports/ckmedia', 'SupportController@storeCKEditorImages')->name('supports.storeCKEditorImages');
    Route::resource('supports', 'SupportController');

    // Privacy Policy
    Route::delete('privacy-policies/destroy', 'PrivacyPolicyController@massDestroy')->name('privacy-policies.massDestroy');
    Route::post('privacy-policies/media', 'PrivacyPolicyController@storeMedia')->name('privacy-policies.storeMedia');
    Route::post('privacy-policies/ckmedia', 'PrivacyPolicyController@storeCKEditorImages')->name('privacy-policies.storeCKEditorImages');
    Route::resource('privacy-policies', 'PrivacyPolicyController');

    // Term Condition
    Route::delete('term-conditions/destroy', 'TermConditionController@massDestroy')->name('term-conditions.massDestroy');
    Route::post('term-conditions/media', 'TermConditionController@storeMedia')->name('term-conditions.storeMedia');
    Route::post('term-conditions/ckmedia', 'TermConditionController@storeCKEditorImages')->name('term-conditions.storeCKEditorImages');
    Route::resource('term-conditions', 'TermConditionController');

    // About Us
    Route::delete('about-uss/destroy', 'AboutUsController@massDestroy')->name('about-uss.massDestroy');
    Route::post('about-uss/media', 'AboutUsController@storeMedia')->name('about-uss.storeMedia');
    Route::post('about-uss/ckmedia', 'AboutUsController@storeCKEditorImages')->name('about-uss.storeCKEditorImages');
    Route::resource('about-uss', 'AboutUsController');

    // Wallet Request
    Route::delete('wallet-requests/destroy', 'WalletRequestController@massDestroy')->name('wallet-requests.massDestroy');
    Route::resource('wallet-requests', 'WalletRequestController');

    // Asset Category
    Route::delete('asset-categories/destroy', 'AssetCategoryController@massDestroy')->name('asset-categories.massDestroy');
    Route::resource('asset-categories', 'AssetCategoryController');

    // Asset Location
    Route::delete('asset-locations/destroy', 'AssetLocationController@massDestroy')->name('asset-locations.massDestroy');
    Route::resource('asset-locations', 'AssetLocationController');

    // Asset Status
    Route::delete('asset-statuses/destroy', 'AssetStatusController@massDestroy')->name('asset-statuses.massDestroy');
    Route::resource('asset-statuses', 'AssetStatusController');

    // Asset
    Route::delete('assets/destroy', 'AssetController@massDestroy')->name('assets.massDestroy');
    Route::post('assets/media', 'AssetController@storeMedia')->name('assets.storeMedia');
    Route::post('assets/ckmedia', 'AssetController@storeCKEditorImages')->name('assets.storeCKEditorImages');
    Route::resource('assets', 'AssetController');

    // Assets History
    Route::resource('assets-histories', 'AssetsHistoryController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);

    // Expense Category
    Route::delete('expense-categories/destroy', 'ExpenseCategoryController@massDestroy')->name('expense-categories.massDestroy');
    Route::resource('expense-categories', 'ExpenseCategoryController');

    // Income Category
    Route::delete('income-categories/destroy', 'IncomeCategoryController@massDestroy')->name('income-categories.massDestroy');
    Route::resource('income-categories', 'IncomeCategoryController');

    // Expense
    Route::delete('expenses/destroy', 'ExpenseController@massDestroy')->name('expenses.massDestroy');
    Route::resource('expenses', 'ExpenseController');

    // Income
    Route::delete('incomes/destroy', 'IncomeController@massDestroy')->name('incomes.massDestroy');
    Route::resource('incomes', 'IncomeController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
    // Wallet routes
    Route::post('/apply-wallet', [WalletRequestController::class, 'applyWallet'])->name('apply.wallet');
    Route::post('/wallet/apply', [WalletController::class, 'applyWallet'])->name('wallet.apply');
    Route::post('/wallet/request-amount', [WalletController::class, 'requestAmount'])->name('wallet.requestAmount');
    Route::get('/wallet/popup', [WalletController::class, 'getAddAmountPopup'])->name('wallet.addAmountPopup');

    // end wallet

    // tranjuction user
    Route::get('/transaction/pdf', [TransactionController::class, 'downloadPDF'])->name('transaction.pdf');
    // end tranjuction

    // order blance check of wallet
    Route::post('/check-wallet-balance', [CheckOrderController::class, 'checkWalletBalance'])
     ->name('check.wallet.balances');
    //  order end

    // wishlist
    Route::get('frontend/wishlist',[WishlistController::class,'index'])->name('wishlist');
    Route::post('/add-to-wishlist/{productId}', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/{id}/delete', [WishlistController::class, 'destroy'])->name('wishlist.delete');
    Route::post('/wishlist/add', [WishlistController::class, 'addToCart'])->name('wishlist.move');
    // end wishlist

    // user order
    Route::get('/orders', [CheckOrderController::class, 'index'])->name('orders.index');
    Route::post('/store/orders', [CheckOrderController::class, 'store'])->name('order.store');
    Route::patch('/orders/cancel/{order}', [CheckOrderController::class, 'cancelOrder'])->name('orders.cancel');
    
  
    

    Route::post('/replacement/store', [ReplacementController::class, 'store'])->name('replacement.store');

    Route::get('/replacements', [ReplacementController::class, 'index'])->name('replacements.index');
    Route::get('/order-details/{id}', [ReplacementController::class, 'getOrderDetails']);

    // company replacement
    Route::get('/company/replacements', [ReplacementController::class, 'company'])->name('replacements.company');


    Route::get('/company/replacements/{id}', [ReplacementController::class, 'show'])->name('replacements.show');
    Route::get('/company/replacements/{id}/edit', [ReplacementController::class, 'edit'])->name('replacements.edit');
    Route::put('/company/replacements/{id}', [ReplacementController::class, 'update'])->name('replacements.update');
    Route::delete('/company/replacements/{id}', [ReplacementController::class, 'destroy'])->name('replacements.destroy');
    Route::get('/company/replacements/{id}/download', [ReplacementController::class, 'downloadInvoice'])->name('replacements.download');
    
    
    Route::get('add-amounts', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'index'])
        ->name('add-amounts.index');

    // Route for creating a new add amount
    Route::get('add-amounts/create', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'create'])
        ->name('add-amounts.create');

    // Route to store a newly created add amount
    Route::post('add-amounts', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'store'])
        ->name('add-amounts.store');

    // Route for showing a specific add amount
    Route::get('add-amounts/{id}', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'show'])
        ->name('add-amounts.show');

    // Route for editing a specific add amount
    Route::get('add-amounts/{id}/edit', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'edit'])
        ->name('add-amounts.edit');

    // Route to update a specific add amount
    Route::put('add-amounts/{id}', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'update'])
        ->name('add-amounts.update');

    // Route to delete a specific add amount
    Route::delete('add-amounts/{id}', [App\Http\Controllers\Frontend\AddExpenseAmountController::class, 'destroy'])
        ->name('add-amounts.destroy');
    


});






Route::prefix('frontend')->name('frontend.')->group(function () {

    // 🛒 User Orders
    Route::get('/customer-orders', [CheckOrderController::class, 'index'])->name('customer-orders.index');
    Route::post('/store/orders', [CheckOrderController::class, 'store'])->name('order.store');
    Route::patch('/orders/cancel/{order}', [CheckOrderController::class, 'cancelOrder'])->name('orders.cancel');

    // 🔁 Replacement Requests
    Route::post('/replacement/store', [ReplacementController::class, 'store'])->name('replacement.store');
    Route::get('/customer-replacements', [ReplacementController::class, 'index'])->name('customer-replacements.index');
    Route::get('/order-details/{id}', [ReplacementController::class, 'getOrderDetails'])->name('order.details');

    // 🏢 Company Replacement Management
    Route::get('/company/replacements', [ReplacementController::class, 'company'])->name('replacements.company');
    Route::get('/company/replacements/{id}', [ReplacementController::class, 'show'])->name('replacements.show');
    Route::get('/company/replacements/{id}/edit', [ReplacementController::class, 'edit'])->name('replacements.edit');
    Route::put('/company/replacements/{id}', [ReplacementController::class, 'update'])->name('replacements.update');
    Route::delete('/company/replacements/{id}', [ReplacementController::class, 'destroy'])->name('replacements.destroy');
    Route::get('/company/replacements/{id}/download', [ReplacementController::class, 'downloadInvoice'])->name('replacements.download');


});

Route::post('payment/process', [CustomerPaymentController::class, 'processPayment'])->name('frontend.customer.payment.process');

Route::get('/payment-success/{order}', [CustomerPaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/wallet/payout-popup', [WalletController::class, 'getPayOutPopup'])->name('frontend.wallet.payoutPopup');
// routes/web.php
Route::get('/wallet/credit-line-orders', [WalletController::class, 'getCreditLineOrders'])->name('wallet.credit.line.orders');
Route::get('/wallet/payout', [WalletController::class, 'showPayoutForm'])->name('wallet.payout.form');
Route::post('/wallet/payout/submit', [WalletController::class, 'storePayoutRequest'])->name('wallet.payout.submit');


Route::get('/download/{orderNumber}', [App\Http\Controllers\Custom\CheckOrderController::class, 'downloadInvoice'])->name('invoice.download');

// custom routes 
Route::get('/', [App\Http\Controllers\Custom\HomeController::class, 'index'])->name('custom.index');
Route::view('/about-us', 'custom.about-us');
Route::view('/contact', 'custom.contact');
Route::view('/offer', 'custom.offer');
Route::view('/privacy', 'custom.privacy');
Route::view('/term-condition', 'custom.term-condition');
Route::view('/refund', 'custom.refund');
Route::view('/contact', 'custom.contact');
Route::view('/shipping', 'custom.shipping');


Route::get('/product',[App\Http\Controllers\Custom\ProductController::class, 'index'])->name('custom.product');
Route::get('/product-detail/{id}',[App\Http\Controllers\Custom\ProductDetailController::class, 'index'])->name('custom.product-detail');
Route::get('/company-products/{id}', [App\Http\Controllers\Custom\ProductController::class, 'companyProducts'])->name('company.products');
Route::get('/category-products/{id}', [App\Http\Controllers\Custom\ProductController::class, 'categoryProducts'])->name('category.products');


Route::get('/cart',[App\Http\Controllers\Custom\CartController::class, 'index'])->name('custom.cart');
Route::post('/cart/add', [App\Http\Controllers\Custom\CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\Custom\CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/delete', [App\Http\Controllers\Custom\CartController::class, 'delete'])->name('cart.delete');

Route::group(['middleware' => ['auth:web,customer']], function () {
    Route::get('/delivery', [App\Http\Controllers\Custom\DeliveryController::class, 'index'])->name('custom.delivery');
    Route::post('/delivery/store', [App\Http\Controllers\Custom\DeliveryController::class, 'store'])->name('custom.delivery.store');
});


Route::get('/wishlist',[App\Http\Controllers\Custom\wishlistController::class, 'index'])->name('custom.wishlist');

Route::post('/wishlist/store', [App\Http\Controllers\Custom\wishlistController::class, 'store'])->name('wishlist.store');

Route::get('/orders/search', [App\Http\Controllers\Custom\CheckOrderController::class, 'searchOrder'])->name('orders.search');

Route::get('/replacement/invoice/{id}', [App\Http\Controllers\ReplacementController::class, 'generateReplacementInvoice'])->name('replacement.invoice');



Route::get('/download-invoice/{id}', function ($id) {
    $order = Replacement::findOrFail($id);
    $pdf = Pdf::loadView('custom.invoice', compact('order'));
    return $pdf->download('custom.invoice-' . $order->order_number . '.pdf');
});
