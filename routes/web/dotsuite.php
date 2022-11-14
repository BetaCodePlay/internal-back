<?php
/**
 * DotSuite routes
 */
Route::group(['prefix' => 'dot-suite', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'credentials', 'middleware' => ['auth']], function () {

        // Show credentials list
        Route::get('', [
            'as' => 'dot-suite.credentials.index',
            'uses' => 'DotSuiteController@index'
        ]);

        // Get all campaigns
        Route::get('all/{client?}/{provider?}/{currency?}', [
            'as' => 'dot-suite.credentials.all',
            'uses' => 'DotSuiteController@allCredentials'
        ]);

        // Providers
        Route::get('create', [
            'as' => 'dot-suite.credentials.create',
            'uses' => 'DotSuiteController@createCredentials'
        ]);

        // Store credentials data
        Route::post('store-credentials', [
            'as' => 'dot-suite.credentials.store-credentials',
            'uses' => 'DotSuiteController@storeCredentials'
        ]);

        // Providers
        Route::get('providers', [
            'as' => 'dot-suite.credentials.providers',
            'uses' => 'DotSuiteController@providersData'
        ]);

        // Status credentials
        Route::get('credentials-status/{client}/{provider}/{currency}/{status}', [
            'as' => 'dot-suite.credentials.status',
            'uses' => 'DotSuiteController@statusCredentials'
        ]);
    });

    Route::group(['prefix' => 'pragmatic', 'middleware' => ['auth']], function () {

        // Free Bets view
        Route::get('free-spins', [
            'as' => 'dot-suite.free-spins',
            'uses' => 'DotSuiteController@freeSpinsPragmatic'
        ]);

        // Cancel free rounds
        Route::get('cancel-free-rounds', [
            'as' => 'dot-suite.cancel-free-rounds',
            'uses' => 'DotSuiteController@cancelFreeRoundsPragmatic'
        ]);

        // List free rounds
        Route::get('free-rounds-list', [
            'as' => 'dot-suite.cancel-free-list',
            'uses' => 'DotSuiteController@freeRoundsPragmaticList'
        ]);

        // Cancel free rounds
        Route::get('cancel-free-rounds-data/{user}/{bonus_code}', [
            'as' => 'dot-suite.cancel-free-rounds-data',
            'uses' => 'DotSuiteController@cancelFreeRoundsPragmaticData'
        ]);

        // Free Bets
        Route::post('free-spins-data', [
            'as' => 'dot-suite.free-spins-data',
            'uses' => 'DotSuiteController@freeSpinsData'
        ]);
    });

    // Currency users
    Route::get('currency-users', [
        'as' => 'dot-suite.currency-users',
        'uses' => 'DotSuiteController@currencyUsers'
    ]);

    /**
     * Dotsuite reports
     */
    Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {

        //Provider currency
        Route::get('provider-currency', [
            'as' => 'reports.provider-currency',
            'uses' => 'DotSuiteController@providerCurrency'
        ]);

        // View games report
        Route::get('games-totals', [
            'as' => 'reports.dotsuite.games-totals',
            'uses' => 'DotSuiteController@gamesTotals'
        ]);

        // Get games totals data
        Route::get('games-totals-data/{start_date?}/{end_date?}/{provider?}', [
            'as' => 'reports.dotsuite.games-totals-data',
            'uses' => 'DotSuiteController@gamesTotalsData'
        ]);

        // View most played report
        Route::get('most-played-games', [
            'as' => 'reports.dotsuite.most-played-games',
            'uses' => 'DotSuiteController@mostPlayedGames'
        ]);

        // Get users totals data dotsuite
        Route::get('most-played-games-data/{start_date?}/{end_date?}/{currency?}', [
            'as' => 'reports.dotsuite.most-played-games-data',
            'uses' => 'DotSuiteController@mostPlayedGamesData'
        ]);

        // View users
        Route::get('users-totals', [
            'as' => 'reports.dotsuite.users-totals',
            'uses' => 'DotSuiteController@usersTotals'
        ]);

        // Get users totals data dotsuite
        Route::get('users-totals-data/{start_date?}/{end_date?}/{currency?}/{provider?}', [
            'as' => 'reports.dotsuite.users-totals-data',
            'uses' => 'DotSuiteController@usersTotalsData'
        ]);
    });

    // Free spins
    Route::group(['prefix' => 'free-spins', 'middleware' => ['auth']], function () {

        // Free spins cancel data
        Route::get('free-spins-cancel-data/{code_reference}/{provider}/{id}', [
            'as' => 'dot-suite.free-spins.cancel-free-spins-data',
            'uses' => 'DotSuiteController@cancelFreeSpinsData'
        ]);

        // Free spins store data
        Route::post('store', [
            'as' => 'dot-suite.free-spins.store',
            'uses' => 'DotSuiteController@store'
        ]);

        // Caleta gaming
        Route::group(['prefix' => 'caleta-gaming', 'middleware' => ['auth']], function () {

            //show view create free spins
            Route::get('create/{provider}', [
                'as' => 'dot-suite.free-spins.caleta-gaming.create-slot',
                'uses' => 'DotSuiteController@createCaletaGaming'
            ]);

            // Free spins view cancel
            Route::get('cancel/{provider}', [
                'as' => 'dot-suite.free-spins.caleta-gaming.cancel-free-spins',
                'uses' => 'DotSuiteController@cancelCaletaGaming'
            ]);

            // List free rounds
            Route::get('list-free-spins', [
                'as' => 'dot-suite.free-spins.caleta-gaming.free-spins-list',
                'uses' => 'DotSuiteController@freeSpinsListByProviders'
            ]);
        });

        // Evo play
        Route::group(['prefix' => 'evo-play', 'middleware' => ['auth']], function () {

            //show view create free spins
            Route::get('create/{provider}', [
                'as' => 'dot-suite.free-spins.evo-play.create-slot',
                'uses' => 'DotSuiteController@createEvoPlay'
            ]);
        });

        // Triple cherry
        Route::group(['prefix' => 'triple-cherry', 'middleware' => ['auth']], function () {

            //show view create promotion
            Route::get('create/{provider}', [
                'as' => 'dot-suite.free-spins.triple-cherry.create',
                'uses' => 'DotSuiteController@createTripleCherry'
            ]);

            // Free spins view cancel
            Route::get('cancel/{provider}', [
                'as' => 'dot-suite.free-spins.triple-cherry.cancel-free-spins',
                'uses' => 'DotSuiteController@cancelTripleCherry'
            ]);

            // List free rounds
            Route::get('free-spins-list', [
                'as' => 'dot-suite.free-spins.triple-cherry.free-spins-list',
                'uses' => 'DotSuiteController@freeSpinsListByProviders'
            ]);
        });
    });

    // Triple cherry
    Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {
        //Free spins
        Route::get('free-spins', [
            'as' => 'dot-suite.free-spins.reports.free-spins',
            'uses' => 'DotSuiteController@freeSpins'
        ]);

        //Free spins
        Route::get('free-spins-data', [
            'as' => 'dot-suite.free-spins.reports.free-spins-data',
            'uses' => 'DotSuiteController@freeSpinsData'
        ]);
    });

    /**
     * Lobby Games Dotsuite routes
     */
    Route::group(['prefix' => 'lobby-games'],function () {

        // Get all dotsuite lobby games
        Route::get('all/{provider?}/{route?}/{game?}', [
            'as' => 'dot-suite.lobby-games.all',
            'uses' => 'DotSuiteController@allDotSuiteGames'
        ]);

        // Providers Dotsuite
        Route::get('update', [
            'as' => 'dot-suite.lobby-games.update',
            'uses' => 'DotSuiteController@updateProvidersDotsuite'
        ]);

        // Update dotsuite lobby games
        Route::post('update-images', [
            'as' => 'dot-suite.lobby-games.update-images',
            'uses' => 'DotSuiteController@updateGamesDotsuite'
        ]);

        // Delete lobby games
        Route::get('delete/{game}', [
            'as' => 'dot-suite.lobby-games.delete',
            'uses' => 'DotSuiteController@deleteDotsuiteGames'
        ]);

        // Edit dotsuite lobby games
        Route::get('edit/{id}', [
            'as' => 'dot-suite.lobby-games.edit',
            'uses' => 'DotSuiteController@editLobbyDotsuiteGames'
        ]);

        // Create DotSuite Lobby Games
        Route::get('create', [
            'as' => 'dot-suite.lobby-games.create',
            'uses' => 'DotSuiteController@createLobbyGamesDotsuite'
        ]);


        //  all games dotsuite
        Route::get('games/provider', [
            'as' => 'dot-suite.lobby-games.game',
            'uses' => 'DotSuiteController@gameDotsuite'
        ]);

        // Store dotsuite games
        Route::post('store', [
            'as' => 'dot-suite.lobby-games.store',
            'uses' => 'DotSuiteController@storeDotsuiteGames'
        ]);

    });
});
