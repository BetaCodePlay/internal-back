<?php
/**
 * Segmentation tool routes
 */
/**
Route::group(['prefix' => 'segments', 'middleware' => ['auth']], function () {

    // Add user
    Route::post('add-user', [
        'as' => 'segments.add-user',
        'uses' => 'SegmentsController@addUser',
    ]);

    // Get all segments
    Route::get('all', [
        'as' => 'segments.all',
        'uses' => 'SegmentsController@all'
    ]);

    // Show create view
    Route::get('create', [
        'as' => 'segments.create',
        'uses' => 'SegmentsController@create'
    ]);

    // Delete segment
    Route::get('delete/{id}', [
        'as' => 'segments.delete',
        'uses' => 'SegmentsController@delete'
    ]);

    // Disable segment
    Route::get('disable/{id}/{status}', [
        'as' => 'segments.disable',
        'uses' => 'SegmentsController@disable'
    ]);

    // Edit segment
    Route::get('edit/{id}', [
        'as' => 'segments.edit',
        'uses' => 'SegmentsController@edit'
    ]);

    // Remover user
    Route::get('remover-user/{id}/{user}', [
        'as' => 'segments.remover-user',
        'uses' => 'SegmentsController@removerUser',
    ]);

    // Show list
    Route::get('', [
        'as' => 'segments.index',
        'uses' => 'SegmentsController@index'
    ]);

    // Store segments
    Route::post('store', [
        'as' => 'segments.store',
        'uses' => 'SegmentsController@store'
    ]);

    // Segment update
    Route::post('update', [
        'as' => 'segments.update',
        'uses' => 'SegmentsController@update'
    ]);

    // Get users data
    Route::get('users-data', [
        'as' => 'segments.users-data',
        'uses' => 'SegmentsController@usersData'
    ]);

    // Get users data
    Route::get('users-list/{id}', [
        'as' => 'segments.users-list',
        'uses' => 'SegmentsController@usersList'
    ]);

    // User segment
    Route::get('user-segment/{user}', [
        'as' => 'segments.user-segments',
        'uses' => 'SegmentsController@userSegment',
    ]);
});
*/
