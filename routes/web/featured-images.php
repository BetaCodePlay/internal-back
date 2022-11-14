<?php

/**
 * Featured images routes
 */
Route::group(['prefix' => 'featured-images', 'middleware' => ['auth']], function () {

    // Get all featured images
    Route::get('all/{templateElementType}', [
        'as' => 'featured-images.all',
        'uses' => 'FeaturedImagesController@all'
    ]);

    // Create featured images
    Route::get('create/{templateElementType}', [
        'as' => 'featured-images.create',
        'uses' => 'FeaturedImagesController@create'
    ]);

    // Edit featured images
    Route::get('edit/{id}', [
        'as' => 'featured-images.edit',
        'uses' => 'FeaturedImagesController@edit'
    ]);

    // Store featured images
    Route::post('store', [
        'as' => 'featured-images.store',
        'uses' => 'FeaturedImagesController@store'
    ]);

    // Update featured images
    Route::post('update', [
        'as' => 'featured-images.update',
        'uses' => 'FeaturedImagesController@update'
    ]);

    // Show featured images list
    Route::get('{templateElementType}', [
        'as' => 'featured-images.index',
        'uses' => 'FeaturedImagesController@index'
    ]);
});

