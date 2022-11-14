<?php

/**
 * Push notifications routes
 */
Route::group(['prefix' => 'push-notifications', 'middleware' => ['auth']], function () {

    // Update section images
    Route::post('store', [
        'as' => 'push-notifications.store',
        'uses' => 'PushNotificationsController@store'
    ]);
});
