<?php

/**
 * Users routes
 */
Route::group(['prefix' => 'users', 'middleware' => ['auth']], function () {

    // Activate-temp
    Route::post('activate-temp', [
        'as' => 'users.activate-temp',
        'uses' => 'UsersController@activationTemp'
    ]);

    // Show advanced search view
    Route::get('advanced-search', [
        'as' => 'users.advanced-search',
        'uses' => 'UsersController@advancedSearch'
    ]);

    // Advanced search data
    Route::get('advanced-search-data', [
        'middleware' => ['clean-gmail-address'],
        'as' => 'users.advanced-search-data',
        'uses' => 'UsersController@advancedSearchData'
    ]);

    // Audit users
    Route::post('audit-users', [
        'as' => 'users.audit-users',
        'uses' => 'UsersController@auditUsers'
    ]);

    // Get autolocked users
    Route::get('autolocked-users', [
        'as' => 'users.autolocked-users',
        'uses' => 'UsersController@autoLockedUsers'
    ]);

    // Autolocked users Data
    Route::get('autolocked-users-data/{start_date?}/{end_date?}', [
        'as' => 'users.autolocked-users-data',
        'uses' => 'UsersController@autoLockedUsersData'
    ]);

    // Bonus transactions
    Route::post('bonus-transactions', [
        'as' => 'users.bonus-transactions',
        'uses' => 'UsersController@bonusTransactions'
    ]);

    // Change user status
    Route::get('change-status/{user}/{status}/{type}/{description?}', [
        'as' => 'users.change-status',
        'uses' => 'UsersController@changeStatus'
    ]);

    // Block user status
    Route::get('block-user-status/{user}/{status}/{type}/{description?}', [
        'as' => 'users.block.status',
        'uses' => 'UsersController@blockAgent'
    ]);

    // Get completed profiles
    Route::get('completed-profiles', [
        'as' => 'users.completed-profiles',
        'uses' => 'UsersController@completedProfiles'
    ]);

    // Show create users view
    Route::get('create', [
        'as' => 'users.create',
        'uses' => 'UsersController@create'
    ]);

    // Dashboard graphic
    Route::get('dashboard-graphic', [
        'as' => 'users.dashboard-graphic',
        'uses' => 'UsersController@dashboardGraphic'
    ]);

    // Dashboard graphic desktop mobil
    Route::get('dashboard-graphic-desktop-mobil', [
        'as' => 'users.dashboard-graphic-desktop-mobile',
        'uses' => 'UsersController@dashboardGraphicDesktopMovil'
    ]);

    // Dashboard graphic gender
    Route::get('dashboard-graphic-gender', [
        'as' => 'users.dashboard-graphic-gender',
        'uses' => 'UsersController@dashboardGraphicGender'
    ]);

    // Get users audit data
    Route::get('users-audit-data/{user?}', [
        'as' => 'users.users-audit-data',
        'uses' => 'UsersController@usersAuditData'
    ]);

    // Get users ips data
    Route::get('users-ips-data/{user?}', [
        'as' => 'users.users-ips-data',
        'uses' => 'UsersController@usersIpsData'
    ]);

    // Delete user temp
    Route::get('delete/{username}', [
        'as' => 'users.delete',
        'uses' => 'UsersController@deleteTemp'
    ]);

    // Show user details
    Route::get('details/{id}/{currency?}', [
        'as' => 'users.details',
        'uses' => 'UsersController@details'
    ]);

    // Get exclude providers users
    Route::get('exclude-providers-users', [
        'as' => 'users.exclude-providers-users',
        'uses' => 'UsersController@excludeProvidersUsers'
    ]);

    // Get username
    Route::post('search-username', [
        'as' => 'users.search-username',
        'uses' => 'UsersController@getUsersByUsername'
    ]);

    // Exclude providers users data
    Route::post('exclude-providers-users-data', [
        'as' => 'users.exclude-providers-users-data',
        'uses' => 'UsersController@excludeProvidersUsersData'
    ]);

    // Exclude providers users delete
    Route::get('exclude-providers-users-delete/{user}/{category}/{currency}', [
        'as' => 'users.exclude-providers-users.delete',
        'uses' => 'UsersController@excludeProviderUserDelete'
    ]);

    // Exclude providers users list
    Route::get('exclude-providers-users-list/{start_date?}/{end_date?}/{category?}/{maker?}/{currency?}', [
        'as' => 'users.exclude-providers-users.list',
        'uses' => 'UsersController@excludeProviderUserList'
    ]);

    // Get incomplete profiles
    Route::get('incomplete-profiles', [
        'as' => 'users.incomplete-profiles',
        'uses' => 'UsersController@incompleteProfiles'
    ]);

    // Show main users
    Route::get('main-users', [
        'as' => 'users.main-users',
        'uses' => 'UsersController@mainUsers'
    ]);

    // Manual adjustments
    Route::post('manual-adjustments', [
        'as' => 'users.manual-adjustments',
        'uses' => 'UsersController@manualAdjustments'
    ]);

    // Manual transactions
    Route::post('manual-transactions', [
        'as' => 'users.manual-transactions',
        'uses' => 'UsersController@manualTransactions'
    ]);

    // Get new users
    Route::get('new', [
        'as' => 'users.new',
        'uses' => 'UsersController@newUsers'
    ]);

    // Points transactions
    Route::post('points-transactions', [
        'as' => 'users.points-transactions',
        'uses' => 'UsersController@pointsTransactions'
    ]);

    // Reset user email
    Route::post('reset-email', [
        'as' => 'users.reset-email',
        'uses' => 'UsersController@resetEmail'
    ]);

    // Reset user password
    Route::post('reset-password', [
        'as' => 'users.reset-password',
        'uses' => 'UsersController@resetPassword'
    ]);

    // Show search results
    Route::get('search', [
        'as' => 'users.search',
        'uses' => 'UsersController@search'
    ]);

    // Show status users
    Route::get('users-status', [
        'as' => 'users.users-status',
        'uses' => 'UsersController@usersStatus'
    ]);

    // Show status users data
    Route::get('users-status-data/{whitelabel?}/{status?}', [
        'as' => 'users.users-status.data',
        'uses' => 'UsersController@usersStatusData'
    ]);

    // Store users
    Route::post('store', [
        'middleware' => ['clean-gmail-address'],
        'as' => 'users.store',
        'uses' => 'UsersController@store'
    ]);

    // Store main users
    Route::post('store-main-users', [
        'as' => 'users.store-main-users',
        'uses' => 'UsersController@storeMainUsers'
    ]);

    // Show temp users
    Route::get('temp', [
        'as' => 'users.temp',
        'uses' => 'UsersController@usersTemp'
    ]);

    // Show temp users
    Route::get('temp-data', [
        'as' => 'users.temp-data',
        'uses' => 'UsersController@usersTempData'
    ]);

    // Get total users
    Route::get('total', [
        'as' => 'users.total',
        'uses' => 'UsersController@totalUsers'
    ]);

    // Show transactions by lot
    Route::get('transactions-by-lot', [
        'as' => 'users.transactions-by-lot',
        'uses' => 'UsersController@transactionsByLot'
    ]);

    // Transactions by lot data
    Route::post('transactions-by-lot', [
        'as' => 'users.transactions-by-lot-file',
        'uses' => 'UsersController@transactionsByLotFile'
    ]);

    // Get by date products users totals data
    Route::get('products-users-totals-data/{user}/{start_date?}/{end_date?}', [
        'as' => 'users.products-users-totals-data',
        'uses' => 'UsersController@productsUsersTotalsDate'
    ]);

    // Resend-activate-email
    Route::post('resend-activate-email', [
        'as' => 'users.resend-activate-email',
        'uses' => 'UsersController@resendActivationEmail'
    ]);

    // Get documents in verification
    Route::get('documents-verifications', [
        'as' => 'users.documents-verifications',
        'uses' => 'UsersController@documentsVerifications'
    ]);

    // Get documents in verification
    Route::get('documents-verifications-data', [
        'as' => 'users.documents-verifications-data',
        'uses' => 'UsersController@documentsVerificationsData'
    ]);

    // Documents action
    Route::post('documents-action', [
        'as' => 'users.documents-action',
        'uses' => 'UsersController@documentAction'
    ]);

    // Get transactions by user
    Route::get('documents-user/{user?}', [
        'as' => 'store.documents-user',
        'uses' => 'UsersController@documentsUser'
    ]);

    // Documents action
    Route::post('documents-edit', [
        'as' => 'users.documents-edit',
        'uses' => 'UsersController@documentEdit'
    ]);

    // Unlock balance
    Route::post('unlock-balance', [
        'as' => 'users.unlock-balance',
        'uses' => 'UsersController@unlockBalance'
    ]);
    // User list charging-points
    Route::get('user-list-charging-points', [
        'as' => 'users.user-list-charging-points',
        'uses' => 'UsersController@userListCreditChargingPoint'
    ]);


    // Unlock balance
    Route::post('unlock-balance', [
        'as' => 'users.unlock-balance',
        'uses' => 'UsersController@unlockBalance'
    ]);

    // Validate agents email
    Route::get('validate/{token?}/{email?}', [
        'as' => 'users.validate',
        'uses' => 'UsersController@validateEmailByAgent'
    ]);

    Route::group(['prefix' => 'profiles'], function () {

        // Show search results
        Route::post('update', [
            'middleware' => ['clean-gmail-address'],
            'as' => 'users.profiles.update',
            'uses' => 'UsersController@updateProfile'
        ]);
    });
});
