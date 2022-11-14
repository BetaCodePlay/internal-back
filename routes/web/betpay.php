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
     *  Abitab routes
     */
    Route::group(['prefix' => 'abitab', 'middleware' => ['auth']], function () {

        // Credit abitab
        Route::get('credit', [
            'as' => 'betpay.abitab.credit',
            'uses' => 'BetPayController@creditAbitab'
        ]);
    });

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
     *  Airtm routes
     */
    Route::group(['prefix' => 'airtm', 'middleware' => ['auth']], function () {

        // Credit airtm
        Route::get('credit', [
            'as' => 'betpay.airtm.credit',
            'uses' => 'BetPayController@creditAirtm'
        ]);

        // Debit airtm
        Route::get('debit', [
            'as' => 'betpay.airtm.debit',
            'uses' => 'BetPayController@debitAirtm'
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
     *  Pronto Paga routes
     */
    Route::group(['prefix' => 'pronto-paga', 'middleware' => ['auth']], function () {
        // Debit Pronto Paga
        Route::get('debit', [
            'as' => 'betpay.pronto-paga.debit',
            'uses' => 'BetPayController@debitProntoPaga'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.pronto-paga.process-debit',
            'uses' => 'BetPayController@processDebitProntoPaga'
        ]);
    });

    /**
     *  PayKu routes
     */
    Route::group(['prefix' => 'payku', 'middleware' => ['auth']], function () {
        // Debit Payku
        Route::get('debit', [
            'as' => 'betpay.payku.debit',
            'uses' => 'BetPayController@debitPayKu'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.payku.process-debit',
            'uses' => 'BetPayController@processDebitPayKu'
        ]);
    });

    /**
     *  Personal routes
     */
    Route::group(['prefix' => 'personal', 'middleware' => ['auth']], function () {

        // Debit Pronto Paga
        Route::get('debit', [
            'as' => 'betpay.personal.debit',
            'uses' => 'BetPayController@debitPersonal'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.personal.process-debit',
            'uses' => 'BetPayController@processDebitPersonal'
        ]);
        // Search payments personal
        Route::get('search', [
            'as' => 'betpay.personal.search',
            'uses' => 'BetPayController@searchPaymentsPersonal'
        ]);

        // Search payments personal data
        Route::get('search-data/{reference?}', [
            'as' => 'betpay.personal.search.data',
            'uses' => 'BetPayController@searchPaymentsPersonalData'
        ]);

        //Cancel payments personal
        Route::get('cancel', [
            'as' => 'betpay.personal.cancel',
            'uses' => 'BetPayController@cancelPaymentsPersonal'
        ]);

        //Cancel payments personal data
        Route::get('cancel-data/{reference?}', [
            'as' => 'betpay.personal.cancel.data',
            'uses' => 'BetPayController@cancelPaymentsPersonalData'
        ]);

         // Process payment
        Route::post('process-payment', [
            'as' => 'betpay.process-payment-personal',
            'uses' => 'BetPayController@processPaymentPersonal'
        ]);
    });


    /**
     *  Zampay routes
     */
    Route::group(['prefix' => 'zampay', 'middleware' => ['auth']], function () {
        // Debit Zampay
        Route::get('debit', [
            BetPayController::class, 'debitZampay'
        ])->name('betpay.zampay.debit');

        // Process debit
        Route::post('process-debit', [
            BetPayController::class, 'processDebitZampay'
        ])->name('betpay.zampay.process-debit');
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
     *  Bizum routes
     */
    Route::group(['prefix' => 'bizum', 'middleware' => ['auth']], function () {

        // Credit bizum
        Route::get('credit', [
            'as' => 'betpay.bizum.credit',
            'uses' => 'BetPayController@creditBizum'
        ]);

        // Debit bizum
        Route::get('debit', [
            'as' => 'betpay.bizum.debit',
            'uses' => 'BetPayController@debitBizum'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.charging-point.process-debit-form',
            'uses' => 'BetPayController@processDebitChargingPointTransactions'
        ]);
    });

    /**
     *  Charginpoint routes
     */
    Route::group(['prefix' => 'charging-point', 'middleware' => ['auth']], function () {

        // Credit Chargingpoint
        Route::get('credit', [
            'as' => 'betpay.charging-point.credit',
            'uses' => 'BetPayController@creditChargingPoint'
        ]);

        // Process credit
        Route::post('process-credit', [
            'as' => 'betpay.charging-point.process-credit-form',
            'uses' => 'BetPayController@processCreditChargingPointTransactions'
        ]);

        // Debit Chargingpoint
        Route::get('debit', [
            'as' => 'betpay.charging-point.debit',
            'uses' => 'BetPayController@debitChargingPoint'
        ]);

        // Search Chargingpoint
        Route::get('search', [
            'as' => 'betpay.charging-point.search',
            'uses' => 'BetPayController@searchChargingPoint'
        ]);
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
     *  Directa24 routes
     */
    Route::group(['prefix' => 'directa24', 'middleware' => ['auth']], function () {

        // Debit directa24
        Route::get('debit', [
            'as' => 'betpay.directa24.debit',
            'uses' => 'BetPayController@debitDirecta24'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.directa24.process-debit',
            'uses' => 'BetPayController@processDebitDirecta24'
        ]);
    });

    /**
     *  JustPay routes
     */
    Route::group(['prefix' => 'justpay', 'middleware' => ['auth']], function () {

        // Debit JustPay
        Route::get('debit', [
            'as' => 'betpay.justpay.debit',
            'uses' => 'BetPayController@debitJustPay'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.justpay.process-debit',
            'uses' => 'BetPayController@processDebitJustPay'
        ]);
    });

    /**
     *  Mobile payment routes
     */
    Route::group(['prefix' => 'mobile-payment', 'middleware' => ['auth']], function () {

        // Credit mobile payment
        Route::get('credit', [
            'as' => 'betpay.mobile-payment.credit',
            'uses' => 'BetPayController@creditMobilePayment'
        ]);
    });

    /**
     *  Neteller routes
     */
    Route::group(['prefix' => 'neteller', 'middleware' => ['auth']], function () {

        // Credit neteller
        Route::get('credit', [
            'as' => 'betpay.neteller.credit',
            'uses' => 'BetPayController@creditNeteller'
        ]);

        // Debit neteller
        Route::get('debit', [
            'as' => 'betpay.neteller.debit',
            'uses' => 'BetPayController@debitNeteller'
        ]);
    });

     /**
     *  Nequi routes
     */
    Route::group(['prefix' => 'nequi', 'middleware' => ['auth']], function () {

        // Credit nequi
        Route::get('credit', [
            'as' => 'betpay.nequi.credit',
            'uses' => 'BetPayController@creditNequi'
        ]);
    });

    /**
     *  Uphold routes
     */
    Route::group(['prefix' => 'uphold', 'middleware' => ['auth']], function () {

        // Credit uphold
        Route::get('credit', [
            'as' => 'betpay.uphold.credit',
            'uses' => 'BetPayController@creditUphold'
        ]);

        // Debit uphold
        Route::get('debit', [
            'as' => 'betpay.uphold.debit',
            'uses' => 'BetPayController@debitUphold'
        ]);
    });

    /**
     *  Reserve routes
     */
    Route::group(['prefix' => 'reserve', 'middleware' => ['auth']], function () {

        // Credit reserve
        Route::get('credit', [
            'as' => 'betpay.reserve.credit',
            'uses' => 'BetPayController@creditReserve'
        ]);

        // Debit reserve
        Route::get('debit', [
            'as' => 'betpay.reserve.debit',
            'uses' => 'BetPayController@debitReserve'
        ]);
    });

    /**
     *  PayPal routes
     */
    Route::group(['prefix' => 'paypal', 'middleware' => ['auth']], function () {

        // Credit paypal
        Route::get('credit', [
            'as' => 'betpay.paypal.credit',
            'uses' => 'BetPayController@creditPayPal'
        ]);

        // Debit paypal
        Route::get('debit', [
            'as' => 'betpay.paypal.debit',
            'uses' => 'BetPayController@debitPayPal'
        ]);
    });
    /**
     *  PayForFun routes
     */
    Route::group(['prefix' => 'pay-for-fun', 'middleware' => ['auth']], function () {

        // Credit payforfun
//        Route::get('credit', [
//            'as' => 'betpay.pay-for-fun.credit',
//            'uses' => 'BetPayController@creditPayForFun'
//        ]);

        // Debit payforfun
        Route::get('debit', [
            'as' => 'betpay.pay-for-fun.debit',
            'uses' => 'BetPayController@debitPayForFun'
        ]);
        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.pay-for-fun.process-debit',
            'uses' => 'BetPayController@processDebitPayForFun'
        ]);
    });

    Route::group(['prefix' => 'pay-for-fun-gateway', 'middleware' => ['auth']], function () {
        // Debit payforfun
        Route::get('debit', [
            'as' => 'betpay.pay-for-fun-gateway.debit',
            'uses' => 'BetPayController@debitPayForFunGateway'
        ]);
        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.pay-for-fun-gateway.process-debit',
            'uses' => 'BetPayController@processDebitPayForFunGateway'
        ]);
    });

    /**
     *  Total pago routes
     */
    Route::group(['prefix' => 'total-pago', 'middleware' => ['auth']], function () {

        // Credit total pago
        Route::get('credit', [
            'as' => 'betpay.total-pago.credit',
            'uses' => 'BetPayController@creditTotalPago'
        ]);
    });

    /**
     *  Pay Retailers routes
     */
    Route::group(['prefix' => 'pay-retailers', 'middleware' => ['auth']], function () {

        // Debit VCreditos Api
        Route::get('debit', [
            'as' => 'betpay.pay-retailers.debit',
            'uses' => 'BetPayController@debitPayRetailers'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.pay-retailers.process-debit',
            'uses' => 'BetPayController@processDebitPayRetailers'
        ]);
    });

    /**
     *  Red Pagos routes
     */
    Route::group(['prefix' => 'red-pagos', 'middleware' => ['auth']], function () {

        // Credit red pagos
        Route::get('credit', [
            'as' => 'betpay.red-pagos.credit',
            'uses' => 'BetPayController@creditRedPagos'
        ]);
    });

    /**
     *  Skrill routes
     */
    Route::group(['prefix' => 'skrill', 'middleware' => ['auth']], function () {

        // Credit skrill
        Route::get('credit', [
            'as' => 'betpay.skrill.credit',
            'uses' => 'BetPayController@creditSkrill'
        ]);

        // Debit skrill
        Route::get('debit', [
            'as' => 'betpay.skrill.debit',
            'uses' => 'BetPayController@debitSkrill'
        ]);
    });

    /**
     *  VCreditos routes
     */
    Route::group(['prefix' => 'vcreditos', 'middleware' => ['auth']], function () {

        // Debit VCreditos
        Route::get('debit', [
            'as' => 'betpay.vcreditos.debit',
            'uses' => 'BetPayController@debitVCreditos'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.vcreditos.process-debit',
            'uses' => 'BetPayController@processDebitVCreditos'
        ]);
    });

    /**
     *  VCreditos Api routes
     */
    Route::group(['prefix' => 'vcreditos-api', 'middleware' => ['auth']], function () {

        // Debit VCreditos Api
        Route::get('debit', [
            'as' => 'betpay.vcreditos-api.debit',
            'uses' => 'BetPayController@debitVCreditosApi'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.vcreditos-api.process-debit',
            'uses' => 'BetPayController@processDebitVCreditosApi'
        ]);
    });

    /**
     *  VES to USD routes
     */
    Route::group(['prefix' => 'ves-to-usd', 'middleware' => ['auth']], function () {

        // Debit VES to USD
        Route::get('credit', [
            'as' => 'betpay.ves-to-usd.credit',
            'uses' => 'BetPayController@creditVesToUsd'
        ]);

        // Process credit
        Route::post('process-credit', [
            'as' => 'betpay.ves-to-usd.process-credit',
            'uses' => 'BetPayController@processCreditVesToUsd'
        ]);
    });

    /**
     * Wire transfers routes
     */
    Route::group(['prefix' => 'wire-transfers', 'middleware' => ['auth']], function () {

        // Credit wire transfers
        Route::get('credit', [
            'as' => 'betpay.wire-transfers.credit',
            'uses' => 'BetPayController@creditWireTransfers'
        ]);

        // Debit wire transfers
        Route::get('debit', [
            'as' => 'betpay.wire-transfers.debit',
            'uses' => 'BetPayController@debitWireTransfers'
        ]);
    });

    /**
     *  Zippy routes
     */
    Route::group(['prefix' => 'zippy', 'middleware' => ['auth']], function () {

        // Debit Zippy
        Route::get('debit', [
            'as' => 'betpay.zippy.debit',
            'uses' => 'BetPayController@debitZippy'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.zippy.process-debit',
            'uses' => 'BetPayController@processDebitZippy'
        ]);
    });

    /**
     *  Zelle routes
     */
    Route::group(['prefix' => 'zelle', 'middleware' => ['auth']], function () {

        // Credit zelle
        Route::get('credit', [
            'as' => 'betpay.zelle.credit',
            'uses' => 'BetPayController@creditZelle'
        ]);

        // Debit zelle
        Route::get('debit', [
            'as' => 'betpay.zelle.debit',
            'uses' => 'BetPayController@debitZelle'
        ]);
    });

    /**
     *  Monnet routes
     */
    Route::group(['prefix' => 'monnet', 'middleware' => ['auth']], function () {

        // Debit directa24
        Route::get('debit', [
            'as' => 'betpay.monnet.debit',
            'uses' => 'BetPayController@debitMonnet'
        ]);

        // Process debit
        Route::post('process-debit', [
            'as' => 'betpay.monnet.process-debit',
            'uses' => 'BetPayController@processDebitMonnet'
        ]);
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
