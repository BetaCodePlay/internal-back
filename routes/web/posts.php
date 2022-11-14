<?php

/**
 * Posts routes
 */
Route::group(['prefix' => 'posts', 'middleware' => ['auth']], function () {

    // Show posts list
    Route::get('', [
        'as' => 'posts.index',
        'uses' => 'PostsController@index'
    ]);

    // Get all posts
    Route::get('all', [
        'as' => 'posts.all',
        'uses' => 'PostsController@all'
    ]);

    // Delete post
    Route::get('delete/{id}/{file}', [
        'as' => 'posts.delete',
        'uses' => 'PostsController@delete'
    ]);

    // Edit post
    Route::get('edit/{id}', [
        'as' => 'posts.edit',
        'uses' => 'PostsController@edit'
    ]);

    // Create posts
    Route::get('create', [
        'as' => 'posts.create',
        'uses' => 'PostsController@create'
    ]);

    // Store post
    Route::post('Store', [
        'as' => 'posts.store',
        'uses' => 'PostsController@store'
    ]);

    // Update sliders
    Route::post('update', [
        'as' => 'posts.update',
        'uses' => 'PostsController@update'
    ]);
});
