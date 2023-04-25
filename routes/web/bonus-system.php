<?php

/**
 * Bonus system routes
 */


Route::group(['prefix' => 'bonus-system', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'campaigns'], function () {

        // Show campaigns list
        Route::get('', [
            'as' => 'bonus-system.campaigns.index',
            'uses' => 'BonusSystemController@index'
        ]);

        // Get all campaigns
        Route::get('all', [
            'as' => 'bonus-system.campaigns.all',
            'uses' => 'BonusSystemController@all'
        ]);

        // Create campaigns
        Route::get('create', [
            'as' => 'bonus-system.campaigns.create',
            'uses' => 'BonusSystemController@create'
        ]);

        // Delete campaign
        Route::get('delete/{id}', [
            'as' => 'bonus-system.campaigns.delete',
            'uses' => 'BonusSystemController@delete'
        ]);

        // Store campaigns
        Route::post('store', [
            'as' => 'bonus-system.campaigns.store',
            'uses' => 'BonusSystemController@store'
        ]);

        // Create campaigns
        Route::get('edit/{id}/{versions?}', [
            'as' => 'bonus-system.campaigns.edit',
            'uses' => 'BonusSystemController@edit'
        ]);

        // Update campaigns
        Route::post('update', [
            'as' => 'bonus-system.campaigns.update',
            'uses' => 'BonusSystemController@update'
        ]);

        // Exclude providers
        Route::get('exclude-providers', [
            'as' => 'bonus-system.campaigns.exclude-providers',
            'uses' => 'BonusSystemController@excludeProviders'
        ]);

        // Provider types
        Route::get('provider-types', [
            'as' => 'bonus-system.campaigns.provider-types',
            'uses' => 'BonusSystemController@providerTypes'
        ]);

        // Edit users search
        Route::get('edit-users-search/{campaign}', [
            'as' => 'bonus-system.campaigns.edit-users-search',
            'uses' => 'BonusSystemController@editUsersSearch'
        ]);

        // Edit users search
        Route::get('remove-users/{campaign}', [
            'as' => 'bonus-system.campaigns.remove-users',
            'uses' => 'BonusSystemController@removeUsersOfCampaign'
        ]);

        // Edit users search
        Route::get('add-users/{campaign}', [
            'as' => 'bonus-system.campaigns.add-users',
            'uses' => 'BonusSystemController@addUsersSearch'
        ]);

        // Include segments
        Route::get('include-users/{campaign}', [
            'as' => 'bonus-system.campaigns.include-users',
            'uses' => 'BonusSystemController@getIncludeUsers'
        ]);

        // Include segments
        Route::get('include-segments/{campaign}', [
            'as' => 'bonus-system.campaigns.include-segments',
            'uses' => 'BonusSystemController@getIncludeSegments'
        ]);

        // Type providers
        Route::get('allocation-criteria-types', [
            'as' => 'bonus-system.campaigns.allocation-criteria-types',
            'uses' => 'BonusSystemController@allocationCriteriaTypes'
        ]);

        // Payment methods
        Route::get('payment-methods', [
            'as' => 'bonus-system.campaigns.payment-methods',
            'uses' => 'BonusSystemController@paymentMethds'
        ]);

        Route::group(['prefix' => 'users'], function () {

            // Update campaigns
            Route::post('add', [
                'as' => 'bonus-system.campaigns.users.add',
                'uses' => 'BonusSystemController@addCamapignToUser'
            ]);

            // Remover user
            Route::get('remover-user/{user}/{wallet}', [
                'as' => 'bonus-system.campaigns.users.remover-user',
                'uses' => 'BonusSystemController@removerUser',
            ]);

            // Remover user
            Route::get('remover-user-data/{id}/{user}/{wallet}', [
                'as' => 'bonus-system.campaigns.users.remover-user-data',
                'uses' => 'BonusSystemController@removerUserData',
            ]);

            // Manual adjustments
            Route::post('manual-adjustments', [
                'as' => 'bonus-system.campaigns.users.manual-adjustments',
                'uses' => 'BonusSystemController@manualAdjustments'
            ]);

        });


        Route::group(['prefix' => 'reports'], function () {

            // Campaigns
            Route::get('campaigns-overview', [
                'as' => 'bonus-system.reports.campaigns-overview',
                'uses' => 'BonusSystemController@campaignsOverview'
            ]);

            // Campaigns data
            Route::get('campaigns-overview-data', [
                'as' => 'bonus-system.reports.campaigns-overview-data',
                'uses' => 'BonusSystemController@campaignsOverviewData',
            ]);

            // Campaign user participation
            Route::get('participation-by-users', [
                'as' => 'bonus-system.reports.participation-by-users',
                'uses' => 'BonusSystemController@campaignUserParticipation',
            ]);
            // Campaign user participation data
            Route::get('participation-by-users-data/{start_date?}/{end_date?}/{allocation_criteria?}/{currency?}/{convert?}/{status?}/{max?}/{campaign?}', [
                'as' => 'bonus-system.reports.participation-by-users-data',
                'uses' => 'BonusSystemController@campaignUserParticipationData'
            ]);

        });
    });
});

