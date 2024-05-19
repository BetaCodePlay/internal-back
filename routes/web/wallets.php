<?php

/**
 * Wallets routes
 */
Route::group(['prefix' => 'wallets', 'middleware' => ['auth']], function () {
    Route::get('transactions-assiria', [
        'as' => 'wallets.transactions.assiria',
        'uses' => 'WalletsController@transactionsAssiria'
    ]);

    // Create wallets
    Route::get('create/{user}/{username}/{uuid}/{currency}', [
        'as' => 'wallets.create',
        'uses' => 'WalletsController@create'
    ]);

    // Lock user balance
    Route::post('lock-balance', [
        'as' => 'wallets.lock-balance',
        'uses' => 'WalletsController@lockBalance'
    ]);

    // Get transactions by wallet
    Route::get('transactions/{wallet?}', [
        'as' => 'wallets.transactions',
        'uses' => 'WalletsController@transactions'
    ]);

    // Get transactions by wallet historic
    Route::get('transactions-historic/{wallet?}', [
        'as' => 'wallets.transactions-historic',
        'uses' => 'WalletsController@transactionsHistoric'
    ]);
});
