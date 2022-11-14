<?php

/**
 *  Featured sliders routes
 */
Route::group(['prefix' => 'featured-sliders', 'middleware' => ['auth']], function () {

    // Get all featured sliders
    Route::get('all/{templateElementType}', [
        'as' => 'featured-sliders.all',
        'uses' => 'FeaturedSlidersController@all'
    ]);

    // Create featured sliders
    Route::get('create/{templateElementType}', [
        'as' => 'featured-sliders.create',
        'uses' => 'FeaturedSlidersController@create'
    ]);

    // Delete featured sliders
    Route::get('delete/{id}/{file}', [
        'as' => 'featured-sliders.delete',
        'uses' => 'FeaturedSlidersController@delete'
    ]);

    // Edit featured sliders
    Route::get('edit/{id}', [
        'as' => 'featured-sliders.edit',
        'uses' => 'FeaturedSlidersController@edit'
    ]);

    // Store featured sliders
    Route::post('store', [
        'as' => 'featured-sliders.store',
        'uses' => 'FeaturedSlidersController@store'
    ]);

    // Update featured sliders
    Route::post('update', [
        'as' => 'featured-sliders.update',
        'uses' => 'FeaturedSlidersController@update'
    ]);

    // Show featured sliders list
    Route::get('{templateElementType}', [
        'as' => 'featured-sliders.index',
        'uses' => 'FeaturedSlidersController@index'
    ]);

});
