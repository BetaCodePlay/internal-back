<?php

/**
 * Games routes
 */
Route::group(['prefix' => 'games', 'middleware' => ['auth']], function () {

    // Show games list
    Route::get('{templateElementType}/{section?}', [
        'as' => 'section-games.index',
        'uses' => 'SectionGamesController@index'
    ]);

    // Get all games
    Route::get('all/{section?}', [
        'as' => 'section-games.all',
        'uses' => 'SectionGamesController@all'
    ]);

    // Create games
    Route::get('create/{templateElementType}/{section?}', [
        'as' => 'section-games.create',
        'uses' => 'SectionGamesController@create'
    ]);

    // Delete games
    Route::get('delete/{id}', [
        'as' => 'section-games.delete',
        'uses' => 'SectionGamesController@delete'
    ]);

    // Store games
    Route::post('store', [
        'as' => 'section-games.store',
        'uses' => 'SectionGamesController@store'
    ]);

});
