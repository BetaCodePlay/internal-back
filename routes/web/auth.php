<?php

/**
 * Auth routes
 */
Route::group(['middleware' => ['guest']], function() {

    // Show login
    Route::get('', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login'
    ]);
});

/**
 * Auth routes
 */
Route::group(['prefix' => 'auth', 'middleware' => ['guest']], function () {

    // Authenticate users
    Route::post('authenticate', [
        'as' => 'auth.authenticate',
        'uses' => 'AuthController@authenticate'
    ]);
});

/**
 * Auth routes
 */
Route::group(['prefix' => 'auth', 'middleware' => ['auth']], function () {

    // Show sign in view
    Route::get('logout', [
        'as' => 'auth.logout',
        'uses' => 'AuthController@logout'
    ]);
});
