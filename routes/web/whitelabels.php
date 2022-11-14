<?php

/**
 * Whitelabels status routes
 */
Route::group(['prefix' => 'whitelabels', 'middleware' => ['auth']], function () {

    // Change status
    Route::post('change-status', [
        'as' => 'whitelabels.change-status',
        'uses' => 'WhitelabelsController@changeStatus'
    ]);

    // Show create view
    Route::get('create', [
        'as' => 'whitelabels.create',
        'uses' => 'WhitelabelsController@create'
    ]);

    // Show create view
    Route::get('currency-by-whitelabel', [
        'as' => 'whitelabels.currency-by-whitelabel',
        'uses' => 'WhitelabelsController@currencyByWhitelabel'
    ]);

    // Show edit view
    Route::get('edit/{id}', [
        'as' => 'whitelabels.edit',
        'uses' => 'WhitelabelsController@edit'
    ]);

    // Show whitelabels operational balance
    Route::get('operational-balances', [
        'as' => 'whitelabels.operational-balances',
        'uses' => 'WhitelabelsController@operationalBalances'
    ]);

    // Get whitelabels status data
    Route::get('operational-balances-data', [
        'as' => 'whitelabels.operational-balances-data',
        'uses' => 'WhitelabelsController@operationalBalancesData'
    ]);

    // Show whitelabels status
    Route::get('status', [
        'as' => 'whitelabels.status',
        'uses' => 'WhitelabelsController@whitelabelsStatus'
    ]);

    // Get whitelabels status data
    Route::get('status-data', [
        'as' => 'whitelabels.status-data',
        'uses' => 'WhitelabelsController@whitelabelsStatusData'
    ]);

    // Store
    Route::post('store', [
        'as' => 'whitelabels.store',
        'uses' => 'WhitelabelsController@store'
    ]);

    // Update operational balance
    Route::post('update-operational-balance', [
        'as' => 'whitelabels.update-operational-balance',
        'uses' => 'WhitelabelsController@updateOperationalBalance'
    ]);
});
