<?php

/**
 * lobby-games routes
 */
Route::group(['prefix' => 'lobby-games', 'middleware' => ['auth']], function () {

// Get all lobby games
    Route::get('all/{provider?}/{route?}/{game?}', [
        'as' => 'games.all',
        'uses' => 'LobbyGamesController@allGames'
    ]);

    // Update lobby games images
    Route::post('update-images', [
        'as' => 'lobby-games.update-images',
        'uses' => 'LobbyGamesController@updateGames'
    ]);

    // Delete lobby games
    Route::get('delete/{game}', [
        'as' => 'lobby-games.delete',
        'uses' => 'LobbyGamesController@deleteGames'
    ]);

    // Edit lobby games
    Route::get('edit/{id}', [
        'as' => 'lobby-games.edit',
        'uses' => 'LobbyGamesController@editLobbyGames'
    ]);

    // Create Lobby Games
    Route::get('create', [
        'as' => 'lobby-games.create',
        'uses' => 'LobbyGamesController@createLobbyGames'
    ]);


    //  all games
    Route::get('games/provider', [
        'as' => 'lobby-games.game',
        'uses' => 'LobbyGamesController@gameDotsuite'
    ]);

    // Store games
    Route::post('store', [
        'as' => 'lobby-games.store',
        'uses' => 'LobbyGamesController@storeGames'
    ]);


});
