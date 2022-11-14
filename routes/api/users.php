<?php

/**
 * Closure routes
 */
Route::group(['prefix' => 'users'], function () {

    // Update users
    Route::post('update', 'Api\UsersController@update');
});
