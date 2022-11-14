<?php

/**
 * lobby-games routes
 */
Route::group(['prefix' => 'lobby-games', 'middleware' => ['auth']], function () {

    // Show lobby games
    Route::get('', [
        'as' => 'lobby-games.index',
        'uses' => 'LobbyGamesController@index'
    ]);

    // Get all lobby games
    Route::get('all', [
        'as' => 'lobby-games.all',
        'uses' => 'LobbyGamesController@all'
    ]);

    // Delete lobby games
    Route::get('delete/{game}/{whitelabel}', [
        'as' => 'lobby-games.delete',
        'uses' => 'LobbyGamesController@delete'
    ]);

    //  all lobby games
    Route::post('games/provider', [
        'as' => 'lobby-games.game',
        'uses' => 'LobbyGamesController@game'
    ]);

    /*// Store lobby games
    Route::post('store', [
        'as' => 'lobby-games.store',
        'uses' => 'LobbyGamesController@store'
    ]);*/

});
