<?php

/**
 * Closure routes
 */
Route::group(['prefix' => 'closure', 'middleware' => ['authenticate-api']], function () {

    // Closure
    Route::get('', [
        'as' => 'closure.api',
        'uses' => 'TransactionsController@closure'
    ]);
});
