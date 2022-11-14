<?php

/**
 * Products limits routes
 */
Route::group(['prefix' => 'providers-limits', 'middleware' => ['auth']], function () {

    // Show products limits list
    Route::get('{provider}', [
        'as' => 'providers-limits.index',
        'uses' => 'ProvidersLimitsController@index'
    ]);

    // Get all products limits
    Route::get('all/{provider}', [
        'as' => 'providers-limits.all',
        'uses' => 'ProvidersLimitsController@all'
    ]);

    // Create limits
    Route::get('create/{provider}', [
        'as' => 'providers-limits.create',
        'uses' => 'ProvidersLimitsController@create'
    ]);

    // Create products limits
    Route::get('edit/{whitelabel}/{currency}/{provider}', [
        'as' => 'providers-limits.edit',
        'uses' => 'ProvidersLimitsController@edit'
    ]);

    // Store products limits
    Route::post('store', [
        'as' => 'providers-limits.store',
        'uses' => 'ProvidersLimitsController@store'
    ]);

    // Update products limits
    Route::post('update', [
        'as' => 'providers-limits.update',
        'uses' => 'ProvidersLimitsController@update'
    ]);
});
