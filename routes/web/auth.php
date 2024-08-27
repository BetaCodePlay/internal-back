<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/**
 * Auth routes
 */
Route::group(['middleware' => ['guest']], function() {

    // Show login
    Route::get('', [
        'as' => 'auth.login',
        'uses' => 'AuthController@login'
    ]);

    Route::get('password/reset/notification', [
        'as' => 'auth.password.reset.notification',
        'uses' => 'AuthController@passwordResetNotification'
    ]);

    Route::get('reset-password', [
        'as' => 'auth.reset.password',
        'uses' => 'AuthController@resetPassword'
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
    Route::post('change-password', [
        'as' => 'auth.change-password',
        'uses' => 'AuthController@changePassword'
    ]);
    Route::post('change-password', [
        'as' => 'auth.change-password',
        'uses' => 'AuthController@changePassword'
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

    Route::post('request-password', [AuthController::class, 'requestReset'])
        ->name('request.password');

    Route::get('update-quantities', [
        'as' => 'auth.agent.update-quantities',
        'uses' => 'AuthController@updateAgentQuantities'
    ]);
});
