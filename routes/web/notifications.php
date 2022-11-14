<?php

/**
 * Notifications routes
 */
Route::group(['prefix' => 'notifications', 'middleware' => ['auth']], function () {

    // Show notifications list
    Route::get('', [
        'as' => 'notifications.index',
        'uses' => 'NotificationsController@index'
    ]);

    // Get all notifications
    Route::get('all', [
        'as' => 'notifications.all',
        'uses' => 'NotificationsController@all'
    ]);

    // Delete notifications
    Route::get('delete/{id}/{file?}', [
        'as' => 'notifications.delete',
        'uses' => 'NotificationsController@delete'
    ]);

    // Edit notifications
    Route::get('edit/{id}', [
        'as' => 'notifications.edit',
        'uses' => 'NotificationsController@edit'
    ]);

    // Create notifications
    Route::get('create', [
        'as' => 'notifications.create',
        'uses' => 'NotificationsController@create'
    ]);

    // Delete user
    Route::get('notificacion-users/{id}/{type}', [
        'as' => 'notifications.list-user',
        'uses' => 'NotificationsController@listUser'
    ]);

    // Delete user
    Route::get('remove-user/{id}/{user}', [
        'as' => 'notifications.remove-user',
        'uses' => 'NotificationsController@removeUser'
    ]);

    // Store notifications
    Route::post('Store', [
        'as' => 'notifications.store',
        'uses' => 'NotificationsController@store'
    ]);

    // Update sliders
    Route::post('update', [
        'as' => 'notifications.update',
        'uses' => 'NotificationsController@update'
    ]);

    Route::group(['prefix' => 'groups'], function () {

        // Show notifications groups list
        Route::get('', [
            'as' => 'notifications.groups.index',
            'uses' => 'NotificationsController@indexGroups'
        ]);

        // Get all notifications groups
        Route::get('all', [
            'as' => 'notifications.groups.all',
            'uses' => 'NotificationsController@allGroups'
        ]);

        // Assign user to notifications groups
        Route::get('assign/{id}', [
            'as' => 'notifications.groups.assign',
            'uses' => 'NotificationsController@assignGroup'
        ]);

        // Assign user to notifications groups data
        Route::post('assign-data', [
            'as' => 'notifications.groups.assign.data',
            'uses' => 'NotificationsController@assignGroupUser'
        ]);

        // Edit notifications groups
        Route::get('edit/{id}', [
            'as' => 'notifications.groups.edit',
            'uses' => 'NotificationsController@editGroups'
        ]);

        // Create notifications groups
        Route::get('create', [
            'as' => 'notifications.groups.create',
            'uses' => 'NotificationsController@createGroups'
        ]);

        // Delete notifications groups
        Route::get('delete/{id}', [
            'as' => 'notifications.groups.delete',
            'uses' => 'NotificationsController@deleteGroups'
        ]);

        // Delete user for notification group
        Route::get('delete-user/{id}/{user}', [
            'as' => 'notifications.groups.delete.user',
            'uses' => 'NotificationsController@deleteUserForGroup'
        ]);

        // Get all notifications groups
        Route::get('users/{group}', [
            'as' => 'notifications.groups.users',
            'uses' => 'NotificationsController@groupUsers'
        ]);

        // Store notifications groups
        Route::post('store', [
            'as' => 'notifications.groups.store',
            'uses' => 'NotificationsController@storeGroups'
        ]);

        // Update notifications groups
        Route::post('update', [
            'as' => 'notifications.groups.update',
            'uses' => 'NotificationsController@updateGroups'
        ]);

    });
});
