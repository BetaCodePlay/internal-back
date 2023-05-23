<?php

Route::group(['middleware' => ['auth']], function () {

    // Change currency
    Route::get('change-currency/{currency}', [
        'as' => 'core.change-currency',
        'uses' => 'CoreController@changeCurrency'
    ]);

    // Get cities by state
    Route::get('city', [
        'as' => 'core.city',
        'uses' => 'CoreController@city'
    ]);

    // Show index
    Route::get('dashboard', [
        'as' => 'core.dashboard',
        'uses' => 'CoreController@dashboard'
    ]);

    // Show exchange rates view
    Route::get('exchange-rates', [
        'as' => 'core.exchange-rates',
        'uses' => 'CoreController@exchangeRates'
    ]);

    // Get states by country
    Route::get('states', [
        'as' => 'core.states',
        'uses' => 'CoreController@states'
    ]);

    // Change timezone
    Route::post('timezone', [
        'as' => 'core.change-timezone',
        'uses' => 'CoreController@changeTimezone'
    ]);

    // Update exchange rates
    Route::post('update-exchange-rates', [
        'as' => 'core.update-exchange-rates',
        'uses' => 'CoreController@updateExchangeRates'
    ]);

    // Number of users connected
    Route::get('number-users-connected-device/{start_date?}/{end_date?}', [
        'as' => 'core.number-users-connected-by-device',
        'uses' => 'CoreController@numberConnectedDivice'
    ]);

    // Get makers by providers
    Route::get('makers-by-provider', [
        'as' => 'core.makers-by-provider',
        'uses' => 'CoreController@makersByProvider'
    ]);

    // Get makers by category
    Route::get('makers-by-category', [
        'as' => 'core.makers-by-category',
        'uses' => 'CoreController@makersByCategory'
    ]);

    // Get makers
    Route::get('makers', [
        'as' => 'core.makers',
        'uses' => 'CoreController@makers'
    ]);
});

// Change language
Route::get('language/{locale}', [
    'as' => 'core.change-language',
    'uses' => 'CoreController@changeLanguage'
]);

// ELB health check
Route::get('elb-health-check', function () {
    $request = new \Illuminate\Http\Request();
    return response('');
});
