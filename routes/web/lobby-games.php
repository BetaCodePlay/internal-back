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
        'as' => 'games.update-images',
        'uses' => 'LobbyGamesController@updateGames'
    ]);

    // Delete lobby games
    Route::get('delete/{game}', [
        'as' => 'games.delete',
        'uses' => 'LobbyGamesController@deleteGames'
    ]);

    // Edit lobby games
    Route::get('edit/{id}', [
        'as' => 'games.edit',
        'uses' => 'LobbyGamesController@editLobbyGames'
    ]);

    // Create Lobby Games
    Route::get('create', [
        'as' => 'games.create',
        'uses' => 'LobbyGamesController@createLobbyGames'
    ]);


    //  all games
    Route::get('games/provider', [
        'as' => 'games.game',
        'uses' => 'LobbyGamesController@game'
    ]);

     //  all games 
     Route::get('games/category', [
        'as' => 'games.game-by-categories',
        'uses' => 'LobbyGamesController@gameByCategories'
    ]);

    // Store games
    Route::post('store', [
        'as' => 'games.store',
        'uses' => 'LobbyGamesController@storeGames'
    ]);


});
