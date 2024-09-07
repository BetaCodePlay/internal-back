<?php

use App\Http\Controllers\AgentsController;
use Illuminate\Support\Facades\Route;


/**
 * Agents routes
 */
Route::group(['prefix' => 'agents', 'middleware' => ['auth']], function () {
    // Show add user
    Route::get('add-users', [
        'as' => 'agents.add-users',
        'uses' => 'AgentsController@addUsers'
    ]);


    // Show security alert
    Route::get('security-alert', [
        'as' => 'agents.security-alert',
        'uses' => 'AgentsController@securityAlert'
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

    Route::get('update-agent-quantities-from-tree', [
        'as' => 'agents.update.quantities.from.tree',
        'uses' => 'AgentsController@updateAgentQuantitiesFromTree'
    ]);

    // Get Tree Josn
    Route::get('get/tree/users', [
        'as' => 'agents.get.tree.users',
        'uses' => 'AgentsController@getTreeUsers'
    ]);

    // Get Tree Json format
    Route::get('get/tree/users/format', [
        'as' => 'agents.get.tree.users.format',
        'uses' => 'AgentsController@getTreeUsers_format'
    ]);

    Route::get('get/direct-children/{username?}', [AgentsController::class, 'getDirectChildren'])
        ->name('agents.get.direct.children');

    Route::get('create-user', [
        'as' => 'agents.create.user',
        'uses' => 'AgentsController@viewCreateUser'
    ]);

    Route::get('create-agent', [
        'as' => 'agents.create.agent',
        'uses' => 'AgentsController@viewCreateAgent'
    ]);

    Route::get('consult-balance-by-type', [
        'as' => 'agents.consult.balance.by.type',
        'uses' => 'AgentsController@consultBalanceByType'
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
   Route::get('find', [AgentsController::class, 'find'])
        ->name('agents.find');

    // Get father and cant
    Route::get('get/father/cant', [
        'as' => 'agents.get.father.cant',
        'uses' => 'AgentsController@getFatherAndCant'
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
    Route::post('perform-transactions', [AgentsController::class, 'performTransactions'])
        ->name('agents.perform-transactions');


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
    Route::post('search-user-by-username', [
        'as' => 'agents.search-username',
        'uses' => 'AgentsController@searchUserByUsername'
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

    Route::get('{agent}/transactions', [
        'as' => 'agents.formatAgentTransactionsPaginated',
        'uses' => 'AgentsController@transactions'
    ]);

    // Agents transactions
    Route::get('transactions/{agent?}', [
        'as' => 'agents.transactions',
        'uses' => 'AgentsController@agentsTransactions'
    ]);

    // Agents transactions paginate
    Route::get('transactions/paginate/{agent?}', [
        'as' => 'agents.transactions.paginate',
        'uses' => 'AgentsController@agentsTransactionsPaginate'
    ]);

    // Agents transactions totals
    Route::get('transactions/totals/{agent?}', [
        'as' => 'agents.transactions.totals',
        'uses' => 'AgentsController@agentsTransactionsTotals'
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

    // Update Action Temp
    Route::get('update/agent/field/action/10', [
        'as' => 'agent.field.action',
        'uses' => 'AgentsController@changeActionByAgent'
    ]);

    // Change Type user in users where type_user in null
    Route::get('change/type/user/in_null/temp', [
        'as' => 'agents.change.type.user.in_null.temp',
        'uses' => 'AgentsController@changeTypeUser'
    ]);

    // update Owner User
    Route::get('update/owner/user/temp', [
        'as' => 'agents.update.owner.user.temp',
        'uses' => 'AgentsController@updateOwnerUser'
    ]);

    // Show role dashboard
    Route::get('role/dashboard', [AgentsController::class, 'dashboard'])
        ->name('agents.role.dashboard');

    // Show role
    Route::get('role/{username?}', [AgentsController::class, 'role'])
        ->name('agents.role');

    // Show dashboard
    Route::get('{token?}', [
        'as' => 'agents.index',
        'uses' => 'AgentsController@index'
    ]);

    //Role routes
    Route::prefix('api-role')
        ->controller(AgentsController::class)->group(function () {
            // Store rol
            Route::post('/store-rol', 'storeRol')->name('agents.role.store-rol');

            // Store user
            Route::post('/store-user', 'storeRolUser')->name('agents.role.store-user');

            // Lock profile
            Route::post('/lock-profile', 'lockProfile')->name('agents.role.lock-profile');

            // Balance adjustment
            Route::post('/balance-adjustment', 'balanceAdjustment')->name('agents.role.balance-adjustment');

            // Update Rol
            Route::post('/update-rol', 'updateRol')->name('agents.role.update-rol');

            // Block user status
            Route::post('/block-agent', 'blockAgent')
                ->name('agents.block');
            //
            Route::get('/user-find/{user?}', 'userFind')
                ->name('agents.role.user-find');
        });

    // Reports routes
    Route::group(['prefix' => 'reports'], function () {

        // Show agents balances report
        Route::get('agents-balances', [
            'as' => 'agents.reports.agents-balances',
            'uses' => 'AgentsController@agentsBalances'
        ]);

        //Show report management
        Route::get('management', [AgentsController::class, 'reportManagement'])
            ->name('agents.reports.management');

        // Get agents transactions data
        Route::get('agents-balances-data', [
            'as' => 'agents.reports.agents-balances-data',
            'uses' => 'AgentsController@agentsBalancesData'
        ]);

        // Show agents payments
        Route::get('agents-payments', [
            'as' => 'agents.reports.agents-payments',
            'uses' => 'AgentsController@agentsPayments'
        ]);

        // Show agents transactions by dates
        Route::get('find-user-payment/{startDate?}/{endDate?}/{user_id?}', [
            'as' => 'agents.reports.find-user-payment',
            'uses' => 'AgentsController@findUserPayment'
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
        // Show financial state details report
        Route::get('financial-state-details', [
            'as' => 'agents.reports.financial-state-details',
            'uses' => 'AgentsController@financialStateDetails'
        ]);

        //Details Financial State
        Route::get('details/financial-state/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.details.financial-state',
            'uses' => 'AgentsController@financialStateDataDetails'
        ]);

        // Show financial State Makers
        Route::get('financial-state-makers', [
            'as' => 'agents.reports.financial-state-makers',
            'uses' => 'AgentsController@financialStateMakers'
        ]);

        //Financial State Makers
        Route::get('financial-state-data-makers/{startDate?}/{endDate?}/{currency_iso?}/{provider_id?}/{whitelabel_id?}', [
            'as' => 'agents.reports.financial-state-data-makers',
            'uses' => 'AgentsController@financialStateDataMakers'
        ]);

        //Financial State Makers Totals
        Route::get('financial-state-data-makers-totals', [
            'as' => 'agents.reports.financial-state-data-makers-totals',
            'uses' => 'AgentsController@financialStateDataMakersTotals'
        ]);

        // Show financial State Makers Details
        Route::get('financial-state-makers-details', [
            'as' => 'agents.reports.financial-state-makers-details',
            'uses' => 'AgentsController@financialStateMakersDetails'
        ]);

        //Financial State Makers Details
        Route::get('financial-state-data-makers-details/{startDate?}/{endDate?}/{currency_iso?}/{provider_id?}/{whitelabel_id?}', [
            'as' => 'agents.reports.financial-state-data-makers-details',
            'uses' => 'AgentsController@financialStateDataMakers'
        ]);

        //Financial State
        Route::get('financial-state/view1', [
            'as' => 'agents.reports.financial-state.new',
            'uses' => 'AgentsController@financialState_view1'
        ]);

        //Financial State By Username
        Route::get('financial-state/username', [
            'as' => 'agents.reports.financial.state.username',
            'uses' => 'AgentsController@financialStateUsername'
        ]);

        //Financial State By Provider
        Route::get('financial-state/provider', [
            'as' => 'agents.reports.financial.state.provider',
            'uses' => 'AgentsController@financialStateProvider'
        ]);

        //Financial State By Provider
        Route::get('financial-state-data/username/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data.username',
            'uses' => 'AgentsController@financialStateData_username'
        ]);

        //Financial State Data By Provider
        Route::get('financial-state-data/provider/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data.provider',
            'uses' => 'AgentsController@financialStateData_provider'
        ]);

        // Get financial state data OLD
        Route::get('financial-state-data/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data',
            'uses' => 'AgentsController@financialStateData'
        ]);

        Route::get('financial-statement/{userId?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-statement',
            'uses' => 'AgentsController@showFinancialStatement'
        ]);

        //Financial State Data Details
        Route::get('financial-state-data-detail/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data-detail',
            'uses' => 'AgentsController@financialStateDataDetails2023'
        ]);

        //Financial State Data
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
        // Get total financial data new
        Route::get('financial-state-summary-data-new/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-summary-data-new',
            'uses' => 'AgentsController@financialStateSummaryDataNew'
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

        // Get Transactions Timeline
        Route::get('transactions-timeline', [
            'as' => 'reports.view.transaction.timeline',
            'uses' => 'AgentsController@viewTransactionTimeline'
        ]);

        // Get Transactions Timeline Data
        Route::get('transactions-timeline-data', [
            'as' => 'reports.data.transaction.timeline',
            'uses' => 'AgentsController@dataTransactionTimeline'
        ]);
        // Get Tmp
        Route::get('tmp', [
            'as' => 'reports.view.tmp',
            'uses' => 'AgentsController@viewTmp'
        ]);

        // Get Data Tmp
        Route::get('data-tmp', [
            'as' => 'reports.data.tmp',
            'uses' => 'AgentsController@dataTmp'
        ]);

        // Get exclude providers agents
        Route::get('exclude-providers-agents', [
            'as' => 'agents.reports.exclude-providers-agents',
            'uses' => 'AgentsController@excludeProvidersAgents'
        ]);

        // Exclude providers users data
        Route::post('exclude-providers-agents-data', [
            'as' => 'agents.reports.exclude-providers-agents-data',
            'uses' => 'AgentsController@excludeProvidersAgentsData'
        ]);

        // Exclude providers users delete
        Route::get('exclude-providers-agents-delete/{user}/{category}/{currency}', [
            'as' => 'agents.reports.exclude-providers-agents.delete',
            'uses' => 'AgentsController@excludeProviderAgentsDelete'
        ]);

        // Exclude providers users list
        Route::get('exclude-providers-agents-list/{start_date?}/{end_date?}/{category?}/{maker?}/{currency?}', [
            'as' => 'agents.reports.exclude-providers-agents.list',
            'uses' => 'AgentsController@excludeProviderAgentsList'
        ]);

    });
});
