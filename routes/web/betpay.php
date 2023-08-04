<?php

/**
 * BetPay routes
 */

use App\Http\Controllers\BetPayController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'betpay', 'middleware' => ['auth']], function () {

    // Process credit
    Route::post('process-credit', [
        'as' => 'betpay.process-credit',
        'uses' => 'BetPayController@processCreditTransactions'
    ]);

    // Process debit
    Route::post('process-debit', [
        'as' => 'betpay.process-debit',
        'uses' => 'BetPayController@processDebitTransactions'
    ]);

    // Transactions data
    Route::get('transactions-data/{paymentMethod}/{provider}/{transactionType}', [
        'as' => 'betpay.transactions.data',
        'uses' => 'BetPayController@transactionsData'
    ]);

    // Transactions data
    Route::get('transactions-data-by-code/{paymentMethod}/{provider}/{transactionType}/{code?}', [
        'as' => 'betpay.transactions.data.code',
        'uses' => 'BetPayController@transactionsDataByCode'
    ]);

    // Banks data
    Route::get('banks', [
        'as' => 'betpay.banks.data',
        'uses' => 'BetPayController@banksData'
    ]);

    /**
     *  Accounts routes
     */
    Route::group(['prefix' => 'accounts', 'middleware' => ['auth']], function () {

        // Search users
        Route::get('search', [
            'as' => 'betpay.accounts.search',
            'uses' => 'BetPayController@accountsSearch'
        ]);

        // Search data users
        Route::get('search-data', [
            'as' => 'betpay.accounts.search-data',
            'uses' => 'BetPayController@accountsSearchData'
        ]);

        /**
         *  Accounts routes
         */
        Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {


            // Update user accounts
            Route::post('update', [
                'as' => 'betpay.accounts.user.update',
                'uses' => 'BetPayController@updateUserAccounts'
            ]);

            // Disable user account
            Route::get('disable-account/{user_account_id}', [
                'as' => 'betpay.accounts.user.disable',
                'uses' => 'BetPayController@disableUserAccounts'
            ]);
        });
    });

    /**
     *  Cryptocurrencies routes
     */
    Route::group(['prefix' => 'cryptocurrencies', 'middleware' => ['auth']], function () {

        // Credit Cryptocurrencies
        Route::get('credit', [
            'as' => 'betpay.cryptocurrencies.credit',
            'uses' => 'BetPayController@creditCryptocurrencies'
        ]);

        // Debit Cryptocurrencies
        Route::get('debit', [
            'as' => 'betpay.cryptocurrencies.debit',
            'uses' => 'BetPayController@debitCryptocurrencies'
        ]);
    });

    /**
     *  Binance routes
     */
    Route::group(['prefix' => 'binance', 'middleware' => ['auth']], function () {

        // Credit binance
        Route::get('credit', [
            'as' => 'betpay.binance.credit',
            'uses' => 'BetPayController@creditBinance'
        ]);

        // Debit binance
        Route::get('debit', [
            'as' => 'betpay.binance.debit',
            'uses' => 'BetPayController@debitBinance'
        ]);
    });

     /**
     *  PayPal routes
     */
    Route::group(['prefix' => 'paypal', 'middleware' => ['auth']], function () {

        // Debit paypal
        Route::get('debit', [
            'as' => 'betpay.paypal.debit',
            'uses' => 'BetPayController@debitPayPal'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.paypal.process-debit',
            'uses' => 'BetPayController@processDebitPaypal'
        ]);
    });

    /**
     *  MercadoPago routes
     */
    Route::group(['prefix' => 'mercado-pago', 'middleware' => ['auth']], function () {

        // Debit MercadoPago
        Route::get('debit', [
            'as' => 'betpay.mercado-pago.debit',
            'uses' => 'BetPayController@debitMercadoPago'
        ]);
    });

    /**
     * Clients routes
     */
    Route::group(['prefix' => 'clients', 'middleware' => ['auth']], function () {

        // Get all clients
        Route::get('all', [
            'as' => 'betpay.clients.all',
            'uses' => 'BetPayController@allClients'
        ]);

        // Show clients list
        Route::get('', [
            'as' => 'betpay.clients.index',
            'uses' => 'BetPayController@indexClients'
        ]);

        // Create clients
        Route::get('create', [
            'as' => 'betpay.clients.create',
            'uses' => 'BetPayController@createClients'
        ]);

        // Create clients and payment method
        Route::get('create-payment-method', [
            'as' => 'betpay.clients.create-payment-method',
            'uses' => 'BetPayController@createClientsPaymentMethod'
        ]);

        // Delete clients
        Route::get('delete/{id}}', [
            'as' => 'betpay.clients.delete',
            'uses' => 'BetPayController@deleteClients'
        ]);

        // Edit clients
        Route::get('edit/{id}', [
            'as' => 'betpay.clients.edit',
            'uses' => 'BetPayController@editClients'
        ]);

        // Status Clients
        Route::post('status', [
            'as' => 'betpay.clients.status',
            'uses' => 'BetPayController@statusClients'
        ]);

        // Store clients
        Route::post('store', [
            'as' => 'betpay.clients.store',
            'uses' => 'BetPayController@storeClients'
        ]);

        // Store clients and payment method
        Route::post('store-payment-method', [
            'as' => 'betpay.clients.store-payment-method',
            'uses' => 'BetPayController@storeClientsPaymentMethod'
        ]);

        // Update clients
        Route::post('update', [
            'as' => 'betpay.clients.update',
            'uses' => 'BetPayController@updateClients'
        ]);

        Route::group(['prefix' => 'accounts', 'middleware' => ['auth']], function () {

            // Account clients
            Route::get('', [
                'as' => 'betpay.clients.accounts',
                'uses' => 'BetPayController@clientAccountList'
            ]);

            // Account clients create
            Route::get('create', [
                'as' => 'betpay.clients.accounts.create',
                'uses' => 'BetPayController@createClientAccount'
            ]);

            // Account clients store
            Route::post('store-account', [
                'as' => 'betpay.clients.accounts.store',
                'uses' => 'BetPayController@storeClientAccount'
            ]);

            // Account clients data
            Route::get('data', [
                'as' => 'betpay.clients.accounts.data',
                'uses' => 'BetPayController@clientAccountListData'
            ]);

            // Account clients edit
            Route::get('edit/{id}', [
                'as' => 'betpay.clients.accounts.edit',
                'uses' => 'BetPayController@editClientAccount'
            ]);

            //  Clients accounts status
            Route::post('status', [
                'as' => 'betpay.clients.accounts.status',
                'uses' => 'BetPayController@statusClientAccount'
            ]);

            // Update clients accounts data
            Route::post('update-client-account', [
                'as' => 'betpay.clients.accounts.update-client-account',
                'uses' => 'BetPayController@updateClientAccount'
            ]);

            Route::group(['prefix' => 'payment-limits', 'middleware' => ['auth']], function () {

                // Account clients
                Route::get('', [
                    'as' => 'betpay.clients.accounts.payment-limits',
                    'uses' => 'BetPayController@limitClients'
                ]);

                // Account clients create
                Route::get('create', [
                    'as' => 'betpay.clients.accounts.payment-limits.create',
                    'uses' => 'BetPayController@createClientAccount'
                ]);

                // Account clients data
                Route::get('data', [
                    'as' => 'betpay.clients.accounts.payment-limits.data',
                    'uses' => 'BetPayController@clientAccountListData'
                ]);

                // Account clients edit
                Route::get('edit', [
                    'as' => 'betpay.clients.accounts.payment-limits.edit',
                    'uses' => 'BetPayController@editClientAccount'
                ]);

                // limit clients
                Route::get('limit', [
                    'as' => 'betpay.clients.accounts.payment-limits.limit',
                    'uses' => 'BetPayController@limitClients'
                ]);

                // limit clients data
                Route::get('limit-data', [
                    'as' => 'betpay.clients.accounts.payment-limits.limit-data',
                    'uses' => 'BetPayController@limitClientData'
                ]);

                //  Clients accounts status
                Route::post('status', [
                    'as' => 'betpay.clients.accounts.payment-limits.status',
                    'uses' => 'BetPayController@statusClientAccount'
                ]);

                // Update limit payment methods
                Route::post('update-limit', [
                    'as' => 'betpay.clients.accounts.payment-limits.update-limit',
                    'uses' => 'BetPayController@updateLimit'
                ]);
            });
        });
    });

    /**
     *  Reports routes
     */
    Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {

        // Show credit view
        Route::get('credit/{paymentMethod}', [
            'as' => 'betpay.reports.credit',
            'uses' => 'BetPayController@creditReport'
        ]);

        // Credit reports data
        Route::get('credit-data/{start_date?}/{end_date?}/{paymentMethod?}', [
            'as' => 'betpay.reports.credit-data',
            'uses' => 'BetPayController@creditReportData'
        ]);

        // Show debit view
        Route::get('debit/{paymentMethod}', [
            'as' => 'betpay.reports.debit',
            'uses' => 'BetPayController@debitReport'
        ]);

        // Debit reports data
        Route::get('debit-data/{start_date?}/{end_date?}/{paymentMethod?}', [
            'as' => 'betpay.reports.debit-data',
            'uses' => 'BetPayController@debitReportData'
        ]);
    });
});
