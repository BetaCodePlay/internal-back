<?php

/**
 * Reports routes
 */
Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {

    // View games report
    Route::get('games-totals/{provider}', [
        'as' => 'reports.games-totals',
        'uses' => 'ReportsController@gamesTotals'
    ]);

    // Get games totals data
    Route::get('games-totals-data/{start_date?}/{end_date?}/{provider?}', [
        'as' => 'reports.games-totals-data',
        'uses' => 'ReportsController@gamesTotalsData'
    ]);

    // View most played report
    Route::get('most-played-games/{provider}', [
        'as' => 'reports.most-played-games',
        'uses' => 'ReportsController@mostPlayedGames'
    ]);

    // Get most played games data
    Route::get('most-played-games-data/{start_date?}/{end_date?}/{provider?}', [
        'as' => 'reports.most-played-games-data',
        'uses' => 'ReportsController@mostPlayedGamesData'
    ]);

    // View most played by providers report
    Route::get('most-played-by-providers', [
        'as' => 'reports.most-played-by-providers',
        'uses' => 'ReportsController@mostPlayedByProviders'
    ]);

    // Get most played games data
    Route::get('most-played-by-providers-data/{start_date?}/{end_date?}/{currency?}', [
        'as' => 'reports.most-played-by-providers-data',
        'uses' => 'ReportsController@mostPlayedByProvidersData'
    ]);

    // View users
    Route::get('users-totals/{provider}', [
        'as' => 'reports.users-totals',
        'uses' => 'ReportsController@usersTotals'
    ]);

    // Get users totals data
    Route::get('users-totals-data/{start_date?}/{end_date?}/{provider?}', [
        'as' => 'reports.users-totals-data',
        'uses' => 'ReportsController@usersTotalsData'
    ]);

    // View products totals
    Route::get('products-totals', [
        'as' => 'reports.products-totals',
        'uses' => 'ReportsController@productsTotals'
    ]);

    // Get products totals data
    Route::get('products-totals-data/{start_date?}/{end_date?}/{currency?}/{convert?}/{provider?}/{type?}', [
        'as' => 'reports.products-totals-data',
        'uses' => 'ReportsController@productsTotalsData'
    ]);

    // View products totals
    Route::get('products-totals-overview', [
        'as' => 'reports.products-totals-overview',
        'uses' => 'ReportsController@productsTotalsOverview'
    ]);

    // Get products totals data
    Route::get('products-totals-overview-data/{start_date?}/{end_date?}/{currency?}/{convert?}/{provider?}/{type?}', [
        'as' => 'reports.products-totals-overview-data',
        'uses' => 'ReportsController@productsTotalsOverviewData'
    ]);

    // Get transaction data
    Route::get('transactions-data/{paymentMethod?}/{transactionType?}/{start_date?}/{end_date?}', [
        'as' => 'reports.transactions-data',
        'uses' => 'ReportsController@transactionsData'
    ]);

    // View users played
    Route::get('games-played-by-user/{provider}', [
        'as' => 'reports.games-played-by-user',
        'uses' => 'ReportsController@gamesPlayedByUser'
    ]);

    // Get users played
    Route::get('users-played-games-data/{start_date?}/{end_date?}/{provider?}', [
        'as' => 'reports.games-played-by-user-data',
        'uses' => 'ReportsController@gamesPlayedByUserData'
    ]);

    // View whitelabels totals
    Route::get('whitelabels-totals', [
        'as' => 'reports.whitelabels-totals',
        'uses' => 'ReportsController@whitelabelsTotals'
    ]);

    // Get whitelabels totals data
    Route::get('whitelabels-totals-data/{start_date?}/{end_date?}/{currency?}/{provider?}/{whitelabel?}', [
        'as' => 'reports.whitelabels-totals-data',
        'uses' => 'ReportsController@whitelabelsTotalsData'
    ]);

    // View whitelabels totals
    Route::get('whitelabels-totals-new', [
        'as' => 'reports.whitelabels-totals-new',
        'uses' => 'ReportsController@whitelabelsTotalsNew'
    ]);

    // Get whitelabels totals data
    Route::get('whitelabels-totals-data-new/{start_date?}/{end_date?}/{currency?}/{convert?}/{provider?}/{whitelabel?}', [
        'as' => 'reports.whitelabels-totals-data-new',
        'uses' => 'ReportsController@whitelabelsTotalsDataNew'
    ]);

    // View whitelabels active providers
    Route::get('whitelabels-active-providers', [
        'as' => 'reports.whitelabels-active-providers',
        'uses' => 'ReportsController@whitelabelsActiveProviders'
    ]);

    // Get wwhitelabels active providers data
    Route::get('whitelabels-active-providers-data/{currency?}/{provider?}/{whitelabel?}', [
        'as' => 'reports.whitelabels-active-providers-data',
        'uses' => 'ReportsController@whitelabelsActiveProvidersData'
    ]);

    /**
     * Financial routes
     */
    Route::group(['prefix' => 'financial', 'middleware' => ['auth']], function () {

        // Bonus transactions view
        Route::get('bonus-transactions', [
            'as' => 'reports.financial.bonus-transactions',
            'uses' => 'ReportsController@bonusTransactions'
        ]);

        // Bonus transactions data
        Route::get('bonus-transactions-data/{start_date?}/{end_date?}', [
            'as' => 'reports.financial.bonus-transactions-data',
            'uses' => 'ReportsController@bonusTransactionsData'
        ]);

        // Deposits view
        Route::get('deposits', [
            'as' => 'reports.financial.deposits',
            'uses' => 'ReportsController@deposits'
        ]);

        // Deposits and withdrawals data
        Route::get('deposits-withdrawals-data/{start_date?}/{end_date?}/{transaction_type?}', [
            'as' => 'reports.financial.deposits-withdrawals-data',
            'uses' => 'ReportsController@depositsWithdrawalsData'
        ]);

        // Manual adjustments view
        Route::get('manual-adjustments', [
            'as' => 'reports.financial.manual-adjustments',
            'uses' => 'ReportsController@manualAdjustments'
        ]);

        // Manual transactions by whitelabels data
        Route::get('manual-adjustments-data', [
            'as' => 'reports.financial.manual-adjustments-data',
            'uses' => 'ReportsController@manualAdjustmentsData'
        ]);

        // Manual transactions view
        Route::get('manual-transactions', [
            'as' => 'reports.financial.manual-transactions',
            'uses' => 'ReportsController@manualTransactions'
        ]);

        // Manual transactions data
        Route::get('manual-transactions-data/{start_date?}/{end_date?}/{transactionType?}', [
            'as' => 'reports.financial.manual-transactions-data',
            'uses' => 'ReportsController@manualTransactionsData'
        ]);

        //Monthly sales view
        Route::get('monthly-sales', [
            'as' => 'reports.financial.monthly-sales',
            'uses' => 'ReportsController@monthlySales'
        ]);

        // Sales daily data
        Route::get('monthly-sales-data', [
            'as' => 'reports.financial.monthly-sales-data',
            'uses' => 'ReportsController@monthlySalesData'
        ]);

        // Sales daily view
        Route::get('daily-sales', [
            'as' => 'reports.financial.daily-sales',
            'uses' => 'ReportsController@dailySales'
        ]);

        // Sales daily data
        Route::get('daily-sales-data', [
            'as' => 'reports.financial.daily-sales-data',
            'uses' => 'ReportsController@dailySalesData'
        ]);

        // profit by user View
        Route::get('profit-by-user', [
            'as' => 'reports.financial.profit-by-user',
            'uses' => 'ReportsController@profitByUserView'
        ]);

        // Get profit by user data
        Route::get('profit-by-user-data', [
            'as' => 'reports.financial.profit-by-user-data',
            'uses' => 'ReportsController@profitByUserData'
        ]);

        // Withdrawals view
        Route::get('withdrawals', [
            'as' => 'reports.financial.withdrawals',
            'uses' => 'ReportsController@withdrawals'
        ]);

        // Totals view
        Route::get('totals', [
            'as' => 'reports.financial.totals',
            'uses' => 'ReportsController@totals'
        ]);

        // Totals data
        Route::get('totals-data/{start_date?}/{end_date?}', [
            'as' => 'reports.financial.totals-data',
            'uses' => 'ReportsController@totalsData'
        ]);

        // Whitelabels by sales view
        Route::get('whitelabels-sales', [
            'as' => 'reports.financial.whitelabels-sales',
            'uses' => 'ReportsController@whitelabelsSales'
        ]);

        // Whitelabels daily data
        Route::get('whitelabels-sales-data', [
            'as' => 'reports.financial.whitelabels-sales-data',
            'uses' => 'ReportsController@whitelabelsSalesData'
        ]);

        // Manual adjustments view
        Route::get('manual-adjustments-users', [
            'as' => 'reports.financial.manual-adjustments-users',
            'uses' => 'ReportsController@manualAdjustmentsWhitelabel'
        ]);

        // Manual transactions by whitelabels data
        Route::get('manual-adjustments-whitelabel-data/{start_date?}/{end_date?}', [
            'as' => 'reports.financial.manual-adjustments-whitelabel-data',
            'uses' => 'ReportsController@manualAdjustmentsWhitelabelData'
        ]);
    });

    /**
     * Hour closures routes
     */
    Route::group(['prefix' => 'hour-closure', 'middleware' => ['auth']], function () {

        // View users
        Route::get('profit', [
            'as' => 'reports.hour-closure.profit',
            'uses' => 'ReportsController@profitHourClosure'
        ]);
        Route::get('client-profit-data/{start_date?}/{end_date?}/{currency?}', [
            'as' => 'reports.hour-closure.profit-data-hour-closure',
            'uses' => 'ReportsController@profitDataHourClosure'
        ]);
    });

    /**
     * IQ Soft routes
     */
    Route::group(['prefix' => 'iq-soft', 'middleware' => ['auth']], function () {

        // Credit
        Route::get('tickets', [
            'as' => 'reports.iq-soft.tickets',
            'uses' => 'ReportsController@iqSoftTickets'
        ]);

        // Credit data
        Route::get('tickets-data/{start_date?}/{end_date?}', [
            'as' => 'reports.iq-soft.tickets-data',
            'uses' => 'ReportsController@iqSoftTicketsData'
        ]);
    });

    /**
     * JustPay routes
     */
    Route::group(['prefix' => 'just-pay', 'middleware' => ['auth']], function () {

        // Credit
        Route::get('credit', [
            'as' => 'reports.just-pay.credit',
            'uses' => 'ReportsController@justPayCredit'
        ]);

        // Credit data
        Route::get('credit-data/{start_date?}/{end_date?}', [
            'as' => 'reports.just-pay.credit-data',
            'uses' => 'ReportsController@justPayCreditData'
        ]);

        // Debit
        Route::get('debit', [
            'as' => 'reports.just-pay.debit',
            'uses' => 'ReportsController@justPayDebit'
        ]);

        // Debit data
        Route::get('debit-data/{start_date?}/{end_date?}', [
            'as' => 'reports.just-pay.debit-data',
            'uses' => 'ReportsController@justPayDebitData'
        ]);
    });

    /**
     * Users payment methods
     */
    Route::group(['prefix' => 'payments-methods', 'middleware' => ['auth']], function () {

        // view payment totals
        Route::get('totals', [
            'as' => 'reports.payment-methods.totals',
            'uses' => 'ReportsController@paymentMethodsTotals'
        ]);

        // get payment totals data
        Route::get('totals-data/{startDate?}/{endDate?}/{currency?}/{payment?}/{transaction?}/{convert?}', [
            'as' => 'reports.payment-methods.totals-data',
            'uses' => 'ReportsController@paymentMethodsTotalsData'
        ]);
    });

    /**
     * Users routes
     */
    Route::group(['prefix' => 'users', 'middleware' => ['auth']], function () {

        // View users actives
        Route::get('active-users-platforms', [
            'as' => 'reports.users.active-users-platforms',
            'uses' => 'ReportsController@usersActives'
        ]);

        // Get actives users data
        Route::get('active-users-platforms-data/{start_date?}/{end_date?}', [
            'as' => 'reports.users.active-users-platforms-data',
            'uses' => 'ReportsController@usersActivesData'
        ]);

        // View balances users
        Route::get('balances', [
            'as' => 'reports.users.balances',
            'uses' => 'ReportsController@usersBalances'
        ]);

        // Get balances users data
        Route::get('balances-data/{currency?}', [
            'as' => 'reports.users.balances-data',
            'uses' => 'ReportsController@usersBalancesData'
        ]);

        // View user registration through the web
        Route::get('registered-users', [
            'as' => 'reports.users.registered-users',
            'uses' => 'ReportsController@registersUsers'
        ]);

        // Get user registration through Dotpanel
        Route::get('registered-users-data/{start_date?}/{end_date?}', [
            'as' => 'reports.users.registered-users-data',
            'uses' => 'ReportsController@registeredUsersData'
        ]);

        // View users segmentations
        Route::get('segmentation', [
            'as' => 'reports.users.segmentation',
            'uses' => 'ReportsController@segmentationTool'
        ]);

        Route::get('segmentations-data', [
            'as' => 'reports.users.segmentations-data',
            'uses' => 'ReportsController@segmentationToolData'
        ]);

        // View totals logins
        Route::get('total-logins', [
            'as' => 'reports.users.total-logins',
            'uses' => 'ReportsController@totalLogins'
        ]);

        // Get totals logins data
        Route::get('total-logins-data/{start_date?}/{end_date?}', [
            'as' => 'reports.users.total-logins-data',
            'uses' => 'ReportsController@totalLoginsData'
        ]);

        // View users referred
        Route::get('users-referred', [
            'as' => 'reports.users.users-referred',
            'uses' => 'ReportsController@referredUsers'
        ]);

        // Get users referred data
        Route::get('users-referred-data/{start_date?}/{end_date?}', [
            'as' => 'reports.users.users-referred-data',
            'uses' => 'ReportsController@referredUsersData'
        ]);

        // Users birthdays view
        Route::get('users-birthdays', [
            'as' => 'reports.users.users-birthdays',
            'uses' => 'ReportsController@usersBirthdays'
        ]);

        // Users birthdays data
        Route::get('users-birthdays-data/{date?}', [
            'as' => 'reports.users.users-birthdays-data',
            'uses' => 'ReportsController@usersBirthdaysData'
        ]);

        // Users conversion view
        Route::get('users-conversion', [
            'as' => 'reports.users.users-conversion',
            'uses' => 'ReportsController@usersConversion'
        ]);

        // Users conversion data
        Route::get('users-conversion-data/{start_date?}/{end_date?}', [
            'as' => 'reports.users.users-conversion-data',
            'uses' => 'ReportsController@usersConversionData'
        ]);
    });

    /**
     * Zippy routes
     */
    Route::group(['prefix' => 'zippy', 'middleware' => ['auth']], function () {

        // Credit
        Route::get('credit', [
            'as' => 'reports.zippy.credit',
            'uses' => 'ReportsController@zippyCredit'
        ]);

        // Debit
        Route::get('debit', [
            'as' => 'reports.zippy.debit',
            'uses' => 'ReportsController@zippyDebit'
        ]);
    });
});
