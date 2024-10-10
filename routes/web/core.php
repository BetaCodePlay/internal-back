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

    // Change rol admin
    Route::get('change/rol/admin', [
        'as' => 'core.change.rol.admin',
        'uses' => 'CoreController@changeRolAdmin'
    ]);

    // Add rol admin
    Route::post('add/rol/admin', [
        'as' => 'core.add.rol.admin',
        'uses' => 'CoreController@addRolAdmin'
    ]);

    // Change rol admin
    Route::get('view/password/wolf', [
        'as' => 'core.view.update.password.wolf',
        'uses' => 'CoreController@viewPasswordForWolf'
    ]);

    // Add rol admin
    Route::post('update/password/wolf', [
        'as' => 'core.update.password.wolf',
        'uses' => 'CoreController@updatePasswordForWolf'
    ]);

    // Delete rol admin
    Route::get('delete/rol/admin', [
        'as' => 'core.delete.rol.admin',
        'uses' => 'CoreController@deleteRolAdmin'
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
    // Total number of users by role connected
    Route::get('total-users-connected-by-role/start_date?}/{end_date?}',[
        'as'=> 'core.total-users-connected-by-role',
        'uses'=>'CoreController@getAmountUsersConnected'
    ]);
    //Get providers by whitelabels
    Route::get('providers-by-whitelabel', [
        'as' => 'core.providers-by-whitelabel',
        'uses' => 'CoreController@providersByWhitelabels'
    ]);

     //Get providers by makers
     Route::get('providers-by-maker', [
        'as' => 'core.providers-by-maker',
        'uses' => 'CoreController@providersByMaker'
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
    // Get categories by maker
    Route::get('categories-by-maker', [
        'as' => 'core.categories-by-maker',
        'uses' => 'CoreController@categoriesByMaker'
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

Route::get('support', function () {
    return view('back.core.support');
});
