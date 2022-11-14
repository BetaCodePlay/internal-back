<?php

/**
 * Landing Pages routes
 */
Route::group(['prefix' => 'landing-pages', 'middleware' => ['auth']], function () {

    // Get all Landing Pages
    Route::get('', [
        'as' => 'landing-pages.index',
        'uses' => 'LandingPagesController@index'
    ]);

    // Get all Landing Pages
    Route::get('all', [
        'as' => 'landing-pages.all',
        'uses' => 'LandingPagesController@all'
    ]);

    // Create Landing Pages
    Route::get('create', [
        'as' => 'landing-pages.create',
        'uses' => 'LandingPagesController@create'
    ]);

    // Store Landing Pages
    Route::post('store', [
        'as' => 'landing-pages.store',
        'uses' => 'LandingPagesController@store'
    ]);

    // Delete Landing Pages
    Route::get('delete/{id}', [
        'as' => 'landing-pages.delete',
        'uses' => 'LandingPagesController@delete'
    ]);

    // Create Landing Pages
    Route::get('edit/{id}', [
        'as' => 'landing-pages.edit',
        'uses' => 'LandingPagesController@edit'
    ]);

    // Update Landing Pages
    Route::post('update', [
        'as' => 'landing-pages.update',
        'uses' => 'LandingPagesController@update'
    ]);
});
