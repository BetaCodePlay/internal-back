<?php

/**
 * Agents routes
 */
Route::group(['prefix' => 'agents', 'middleware' => ['auth']], function () {

    // Show add user
    Route::get('add-users', [
        'as' => 'agents.add-users',
        'uses' => 'AgentsController@addUsers'
    ]);

    // Add users
    Route::post('add-users-data', [
        'as' => 'agents.add-users-data',
        'uses' => 'AgentsController@addUsersData'
    ]);

    // Show add user
    Route::get('block-agent-and-user', [
        'as' => 'agents.block-agents',
        'uses' => 'AgentsController@blockAgent'
    ]);

    // Add users
    Route::post('block-agent-and-user-data', [
        'as' => 'agents.block-agent-data',
        'uses' => 'AgentsController@blockAgentsData'
    ]);

    // Show dashboard
    Route::get('', [
        'as' => 'agents.index',
        'uses' => 'AgentsController@index'
    ]);

    // Agents sub agents
    Route::get('agents/{user?}', [
        'as' => 'agents.agents',
        'uses' => 'AgentsController@agents'
    ]);

    // Change agent type
    Route::get('change-agent-type/{agent}', [
        'as' => 'agents.change-agent-type',
        'uses' => 'AgentsController@changeAgentType'
    ]);

    // Find agent
    Route::get('find', [
        'as' => 'agents.find',
        'uses' => 'AgentsController@find'
    ]);

    // Find agent
    Route::get('find-user', [
        'as' => 'agents.find-user',
        'uses' => 'AgentsController@findUser'
    ]);

    // Show main agents
    Route::get('main-agents', [
        'as' => 'agents.main-agents',
        'uses' => 'AgentsController@mainAgents'
    ]);

    //move agent
    Route::post('move-agent', [
        'as' => 'agents.move-agent',
        'uses' => 'AgentsController@moveAgent'
    ]);

    //move agent user
    Route::post('move-agent-user', [
        'as' => 'agents.move-agent-user',
        'uses' => 'AgentsController@moveAgentUser'
    ]);

    // Transactions
    Route::post('perform-transactions', [
        'as' => 'agents.perform-transactions',
        'uses' => 'AgentsController@performTransactions'
    ]);

    // Relocation agents
    Route::get('relocation-agents/{agent?}', [
        'as' => 'agents.relocation-agents-data',
        'uses' => 'AgentsController@relocationAgentsData'
    ]);

    // Agents players
    Route::get('users/{user?}', [
        'as' => 'agents.users',
        'uses' => 'AgentsController@users'
    ]);

    // Search username agent
    Route::post('search-username', [
        'as' => 'agents.search-username',
        'uses' => 'AgentsController@searchUsername'
    ]);

    // Store agents
    Route::post('store', [
        'middleware' => ['clean-gmail-address'],
        'as' => 'agents.store',
        'uses' => 'AgentsController@store'
    ]);

    // Store main agents
    Route::post('store-main-agents', [
        'as' => 'agents.store-main-agents',
        'uses' => 'AgentsController@storeMainAgents'
    ]);

    // Store users
    Route::post('store-user', [
        'middleware' => ['clean-gmail-address'],
        'as' => 'agents.store-user',
        'uses' => 'AgentsController@storeUser'
    ]);

    // Agents transactions
    Route::get('transactions/{agent?}', [
        'as' => 'agents.transactions',
        'uses' => 'AgentsController@agentsTransactions'
    ]);

    // Agents transactions
    Route::get('ticket/{id?}', [
        'as' => 'agents.ticket',
        'uses' => 'AgentsController@agentsTransactionsTicket'
    ]);

    // Agents tree
    Route::get('agents-tree/{status}', [
        'as' => 'agents.tree-filter',
        'uses' => 'AgentsController@agentsTreeFilter'
    ]);

    // Update percentage
    Route::post('update-percentage', [
        'as' => 'whitelabels.update-percentage',
        'uses' => 'AgentsController@updatePercentage'
    ]);

    // Reports routes
    Route::group(['prefix' => 'reports'], function () {

        // Show agents balances report
        Route::get('agents-balances', [
            'as' => 'agents.reports.agents-balances',
            'uses' => 'AgentsController@agentsBalances'
        ]);

        // Get agents transactions data
        Route::get('agents-balances-data', [
            'as' => 'agents.reports.agents-balances-data',
            'uses' => 'AgentsController@agentsBalancesData'
        ]);

        // Show agents transactions report
        Route::get('agents-transactions', [
            'as' => 'agents.reports.agents-transactions',
            'uses' => 'AgentsController@agentsTransactionsByDates'
        ]);

        // Get agents transactions data
        Route::get('agents-transactions-data/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.agents-transactions-data',
            'uses' => 'AgentsController@agentsTransactionsByDatesData'
        ]);

        // Show cash flow transactions report
        Route::get('cash-flow', [
            'as' => 'agents.reports.cash-flow',
            'uses' => 'AgentsController@cashFlowByDates'
        ]);

        // Get cash flow transactions data
        Route::get('cash-flow-data/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.cash-flow-data',
            'uses' => 'AgentsController@cashFlowByDatesData'
        ]);

        // Show deposits withdrawals provider report
        Route::get('deposits-withdrawals-provider', [
            'as' => 'agents.reports.deposits-withdrawals-provider',
            'uses' => 'AgentsController@depositsWithdrawalsProvider'
        ]);

        // Get deposits withdrawals provider data
        Route::get('deposits-withdrawals-provider-data', [
            'as' => 'agents.reports.deposits-withdrawals-provider-data',
            'uses' => 'AgentsController@depositsWithdrawalsProviderData'
        ]);

        // Show financial state report
        Route::get('financial-state', [
            'as' => 'agents.reports.financial-state',
            'uses' => 'AgentsController@financialState'
        ]);

        // Get financial state data
        Route::get('financial-state-data/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data',
            'uses' => 'AgentsController@financialStateData'
        ]);
        Route::get('financial-state-data/row2/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data.row2',
            'uses' => 'AgentsController@financialStateDataRow2'
        ]);

        // Show total financial report summary
        Route::get('financial-state-summary', [
            'as' => 'agents.reports.financial-state-summary',
            'uses' => 'AgentsController@financialStateSummary'
        ]);

        // Get total financial data
        Route::get('financial-state-summary-data/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-summary-data',
            'uses' => 'AgentsController@financialStateSummaryData'
        ]);

        // Show total financial report summary
        Route::get('financial-state-summary-bonus', [
            'as' => 'agents.reports.financial-state-summary-bonus',
            'uses' => 'AgentsController@financialStateSummaryBonus'
        ]);

        // Get total financial data
        Route::get('financial-state-summary-bonus-data/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-summary-bonus-data',
            'uses' => 'AgentsController@financialStateSummaryBonusData'
        ]);

        // Show locked providers report
        Route::get('locked-providers', [
            'as' => 'agents.reports.locked-providers',
            'uses' => 'AgentsController@lockedProviders'
        ]);

        // Get locked providers data
        Route::get('locked-providers-data/{currency?}/{provider?}', [
            'as' => 'agents.reports.locked-providers-data',
            'uses' => 'AgentsController@lockedProvidersData'
        ]);

        // Show manual transactions report
        Route::get('manual-transaction', [
            'as' => 'agents.reports.manual-transactions',
            'uses' => 'AgentsController@manualTransactions'
        ]);

        // Get manual transactions data
        Route::get('manual-transaction-data/{start_date?}/{end_date?}/{currency?}', [
            'as' => 'agents.reports.manual-transactions-data',
            'uses' => 'AgentsController@manualTransactionsData'
        ]);

        //Provider currency
        Route::get('provider-currency/{currency}', [
            'as' => 'provider-currency',
            'uses' => 'AgentsController@providerCurrency'
        ]);

        // Show users balances report
        Route::get('users-balances', [
            'as' => 'agents.reports.users-balances',
            'uses' => 'AgentsController@usersBalances'
        ]);

        // Get balances users data
        Route::get('users-balances-data', [
            'as' => 'reports.users.users-balances-data',
            'uses' => 'AgentsController@usersBalancesData'
        ]);
    });
});
