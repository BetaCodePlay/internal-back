<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;
/**
 * Transactions routes
 */
Route::group(['prefix' => 'transactions', 'middleware' => ['auth']], function () {

    // Get count by type
    Route::get('county-by-type/{transactionType}/{status}/{startDate}/{endDate}', [
        'as' => 'transactions.count-by-type',
        'uses' => 'TransactionsController@countByType'
    ]);

    // Get transactions graphic data
    Route::get('dashboard-graphic', [
        'as' => 'transactions.dashboard-graphic',
        'uses' => 'TransactionsController@dashboardGraphicData'
    ]);

    // Get totals by type
    Route::get('totals-by-type/{transactionType}/{startDate}/{endDate}', [
        'as' => 'transactions.totals-by-type',
        'uses' => 'TransactionsController@totalsByType'
    ]);

    // Get user transactions
    Route::get('user/{user?}/{currency?}', [
        'as' => 'transactions.user',
        'uses' => 'TransactionsController@userTransactions'
    ]);
});

// Role routes
Route::prefix('api-transactions')
    ->controller(TransactionsController::class)->group(function () {
        Route::get('/agents', 'agentsTransactions')
            ->name('transactions.agents');

        Route::get('/players', 'playersTransactions')
            ->name('transactions.players');

       /* Route::get('transactions/{wallet?}', [
            'as' => 'wallets.transactions',
            'uses' => 'WalletsController@transactions'
        ]);*/
    });
