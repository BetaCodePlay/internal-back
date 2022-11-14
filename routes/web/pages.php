<?php

/**
 * Pages routes
 */
Route::group(['prefix' => 'pages', 'middleware' => ['auth']], function () {

    // Show pages list
    Route::get('', [
        'as' => 'pages.index',
        'uses' => 'PagesController@index'
    ]);

    // Get all pages
    Route::get('all', [
        'as' => 'pages.all',
        'uses' => 'PagesController@all'
    ]);

    // Create pages
    Route::get('edit/{id}', [
        'as' => 'pages.edit',
        'uses' => 'PagesController@edit'
    ]);

    // Update pages
    Route::post('update', [
        'as' => 'pages.update',
        'uses' => 'PagesController@update'
    ]);
});
