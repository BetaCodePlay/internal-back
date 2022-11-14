<?php

/**
 * Section images routes
 */
Route::group(['prefix' => 'section-images', 'middleware' => ['auth']], function () {

    // Get all section images
    Route::get('all/{templateElementType}/{section?}', [
        'as' => 'section-images.all',
        'uses' => 'SectionImagesController@all'
    ]);

    // Create section images
    Route::get('create/{templateElementType}/{section?}', [
        'as' => 'section-images.create',
        'uses' => 'SectionImagesController@create'
    ]);

    // Create section images
    Route::get('edit/{templateElementType}', [
        'as' => 'section-images.edit',
        'uses' => 'SectionImagesController@edit'
    ]);

    // Store section images
    Route::post('store', [
        'as' => 'section-images.store',
        'uses' => 'SectionImagesController@store'
    ]);

    // Update section images
    Route::post('update', [
        'as' => 'section-images.update',
        'uses' => 'SectionImagesController@update'
    ]);

    // Show section images list
    Route::get('{templateElementType}/{section?}', [
        'as' => 'section-images.index',
        'uses' => 'SectionImagesController@index'
    ]);
});
