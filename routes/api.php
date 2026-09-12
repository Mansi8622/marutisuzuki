<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

    // Product
    Route::post('products/media', 'ProductApiController@storeMedia')->name('products.storeMedia');
    Route::apiResource('products', 'ProductApiController');

    // Add Company
    Route::post('add-companies/media', 'AddCompanyApiController@storeMedia')->name('add-companies.storeMedia');
    Route::apiResource('add-companies', 'AddCompanyApiController');

    // Our Stock
    Route::apiResource('our-stocks', 'OurStockApiController');

    // Check Godown
    Route::apiResource('check-godowns', 'CheckGodownApiController');

    // Stock Transfer
    Route::apiResource('stock-transfers', 'StockTransferApiController');

    // Check Order
    Route::post('check-orders/media', 'CheckOrderApiController@storeMedia')->name('check-orders.storeMedia');
    Route::apiResource('check-orders', 'CheckOrderApiController');

    // Carrier
    Route::post('carriers/media', 'CarrierApiController@storeMedia')->name('carriers.storeMedia');
    Route::apiResource('carriers', 'CarrierApiController');

    // Cancellation
    Route::apiResource('cancellations', 'CancellationApiController');

    // Disputes
    Route::apiResource('disputes', 'DisputesApiController');

    // Refunds
    Route::post('refunds/media', 'RefundsApiController@storeMedia')->name('refunds.storeMedia');
    Route::apiResource('refunds', 'RefundsApiController');

    // Taxes
    Route::apiResource('taxes', 'TaxesApiController');

    // Shop Setting
    Route::post('shop-settings/media', 'ShopSettingApiController@storeMedia')->name('shop-settings.storeMedia');
    Route::apiResource('shop-settings', 'ShopSettingApiController');

    // Configurations
    Route::apiResource('configurations', 'ConfigurationsApiController');

    // Support
    Route::post('supports/media', 'SupportApiController@storeMedia')->name('supports.storeMedia');
    Route::apiResource('supports', 'SupportApiController');

    // Privacy Policy
    Route::post('privacy-policies/media', 'PrivacyPolicyApiController@storeMedia')->name('privacy-policies.storeMedia');
    Route::apiResource('privacy-policies', 'PrivacyPolicyApiController');

    // Term Condition
    Route::post('term-conditions/media', 'TermConditionApiController@storeMedia')->name('term-conditions.storeMedia');
    Route::apiResource('term-conditions', 'TermConditionApiController');

    // About Us
    Route::post('about-uss/media', 'AboutUsApiController@storeMedia')->name('about-uss.storeMedia');
    Route::apiResource('about-uss', 'AboutUsApiController');

    // Wallet Request
    Route::apiResource('wallet-requests', 'WalletRequestApiController');

    // Assets History
    Route::apiResource('assets-histories', 'AssetsHistoryApiController', ['except' => ['store', 'show', 'update', 'destroy']]);
});


Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin'], function () {
    
    // 🧾 User Registration API (with address & location)
    Route::post('user-registration', 'UsersApiController@UserRegistration')->name('user.registration');

    // 🔐 User Login
    Route::post('user-login', 'UsersApiController@UserLogin')->name('user.login');
     
    // 📄 Get User Details by ID
    Route::get('user-details/{id}', 'UsersApiController@getUserById')->name('user.details');
    
    // ➕ Upload Profile Photo (no auth)
    Route::post('user/{user_id}/upload-profile-photo', 'UsersApiController@uploadProfilePhoto')->name('user.upload-profile-photo');

    // ➕ Password Reset Request
    Route::post('password-reset', 'UsersApiController@sendPasswordResetLink')->name('password.reset');

    // 🧾 User Registration API (with address & location)
    Route::post('user-registration', 'UsersApiController@UserRegistration')->name('user.registration');
    
    // ⭐ Product API – All OR Single
    Route::get('get-products/{id?}', 'ProductApiController@getProducts')
        ->name('products.get');





   
    
    
});
