<?php

/**
 * Sliders routes
 */
Route::group(['prefix' => 'sliders', 'middleware' => ['auth']], function () {

    // Get all sliders
    Route::get('all/{templateElementType?}/{section?}/{status?}/{device?}/{currency?}/{language?}/{routes?}', [
        'as' => 'sliders.all',
        'uses' => 'SlidersController@all'
    ]);

    // Create sliders
    Route::get('create/{templateElementType?}/{section?}', [
        'as' => 'sliders.create',
        'uses' => 'SlidersController@create'
    ]);

    // Delete sliders
    Route::get('delete/{id}/{file}/{front}', [
        'as' => 'sliders.delete',
        'uses' => 'SlidersController@delete'
    ]);

    // Create sliders
    Route::get('edit/{id}', [
        'as' => 'sliders.edit',
        'uses' => 'SlidersController@edit'
    ]);

    // Store sliders
    Route::post('store', [
        'as' => 'sliders.store',
        'uses' => 'SlidersController@store'
    ]);

    // Update sliders
    Route::post('update', [
        'as' => 'sliders.update',
        'uses' => 'SlidersController@update'
    ]);

    // Show sliders list
    Route::get('{templateElementType?}/{section?}', [
        'as' => 'sliders.index',
        'uses' => 'SlidersController@index'
    ]);
});
