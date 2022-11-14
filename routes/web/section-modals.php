<?php

/**
 * Modals routes
 */
Route::group(['prefix' => 'section-modals', 'middleware' => ['auth']], function () {

    // Show modals list
    Route::get('', [
        'as' => 'section-modals.index',
        'uses' => 'SectionModalsController@index'
    ]);

    // Get all modals
    Route::get('all', [
        'as' => 'section-modals.all',
        'uses' => 'SectionModalsController@all'
    ]);

    // Delete modal
    Route::get('delete/{id}/{file}', [
        'as' => 'section-modals.delete',
        'uses' => 'SectionModalsController@delete'
    ]);

    // Edit modal
    Route::get('edit/{id}', [
        'as' => 'section-modals.edit',
        'uses' => 'SectionModalsController@edit'
    ]);

    // Create modals
    Route::get('create', [
        'as' => 'section-modals.create',
        'uses' => 'SectionModalsController@create'
    ]);

    // Store modal
    Route::post('store', [
        'as' => 'section-modals.store',
        'uses' => 'SectionModalsController@store'
    ]);

    // Update sliders
    Route::post('update', [
        'as' => 'section-modals.update',
        'uses' => 'SectionModalsController@update'
    ]);
});
