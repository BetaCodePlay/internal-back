<?php
/**
 * Whitelabels games routes
 */
Route::group(['prefix' => 'whitelabels-games', 'middleware' => ['auth']], function () {

    // Index
    Route::get('', [
        'as' => 'whitelabels-games.index',
        'uses' => 'WhitelabelsGamesControllers@index'
    ]);

    // Get all whitelabels games
    Route::get('all', [
        'as' => 'whitelabels-games.all',
        'uses' => 'WhitelabelsGamesControllers@all'
    ]);

    // Delete lobby games
    Route::get('delete/{game}/{category}', [
        'as' => 'whitelabels-games.delete',
        'uses' => 'WhitelabelsGamesControllers@delete'
    ]);

    //  all providers games
    Route::get('games/provider', [
        'as' => 'whitelabels-games.game',
        'uses' => 'WhitelabelsGamesControllers@game'
    ]);

    // Store whitelabels games
   Route::post('store', [
       'as' => 'whitelabels-games.store',
       'uses' => 'WhitelabelsGamesControllers@store'
   ]);
});
