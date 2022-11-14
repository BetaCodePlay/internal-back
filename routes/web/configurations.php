<?php

/**
 * Configurations routes
 */
Route::group(['prefix' => 'configurations', 'middleware' => ['auth']], function () {

    /**
     * Credentials routes
     */
    Route::group(['prefix' => 'credentials'], function () {

        // Show credentials
        Route::get('provider/{provider}', [
            'as' => 'configurations.credentials',
            'uses' => 'ConfigurationsController@credentials'
        ]);

        // Show credentials (new methods)
        Route::get('provider-credentials', [
            'as' => 'configurations.credentials.view',
            'uses' => 'ConfigurationsController@viewCredentials'
        ]);

        // Search providers by type
           Route::get('type-providers', [
            'as' => 'configurations.credentials.type-providers',
            'uses' => 'ConfigurationsController@providerTypes'
        ]);

        // Exclude providers
        Route::get('exclude-providers', [
            'as' => 'configurations.credentials.exclude-providers',
            'uses' => 'ConfigurationsController@excludeProviders'
        ]);

         // Store credentials data (new methods)
         Route::post('store-credentials', [
            'as' => 'configurations.credentials.store.credentials',
            'uses' => 'ConfigurationsController@store'
        ]);

        // Store credentials data
        Route::post('store', [
            'as' => 'configurations.credentials.store',
            'uses' => 'ConfigurationsController@storeCredentials'
        ]);

        // status providers
        Route::post('status', [
            'as' => 'configurations.credentials.status',
            'uses' => 'ConfigurationsController@statusCredentials'
        ]);

        // Update credentials data
        Route::post('update', [
            'as' => 'configurations.credentials.update',
            'uses' => 'ConfigurationsController@updateCredentials'
        ]);

        // credentials data
        Route::get('credentials-data/{provider?}', [
            'as' => 'configurations.credentials.data',
            'uses' => 'ConfigurationsController@credentialsData'
        ]);

        // credentials data
        Route::get('credentials-providers-details', [
            'as' => 'configurations.credentials.providers.details',
           'uses' => 'ConfigurationsController@credentialsProvidersDetails'
        ]);

        // credentials data
          Route::get('providers-credentials-data/{client?}/{type?}/{currency?}/{provider?}', [
            'as' => 'configurations.providers.credentials.data',
            'uses' => 'ConfigurationsController@providersCredentialsData'
          ]);

        // credentials delete
        Route::get('credentials-delete/{client}/{provider}/{currency}', [
            'as' => 'configurations.credentials.delete',
            'uses' => 'ConfigurationsController@credentialsDelete'
        ]);

        // credentials details
        Route::get('credentials-details/{client}/{provider}/{currency}', [
            'as' => 'configurations.credentials.details',
            'uses' => 'ConfigurationsController@credentialsDetails'
        ]);

        // Update percentage
        Route::post('update-percentage', [
            'as' => 'configurations.credentials.update-percentage',
            'uses' => 'ConfigurationsController@updatePercentage'
        ]);

    });

    /**
     * Levels routes
     */
    Route::group(['prefix' => 'levels'], function () {

        // Show levels configuration
        Route::get('', [
            'as' => 'configurations.levels.index',
            'uses' => 'ConfigurationsController@levels'
        ]);

        // Get levels data
        Route::get('data', [
            'as' => 'configurations.levels.data',
            'uses' => 'ConfigurationsController@levelsData'
        ]);

        // Update levels data
        Route::post('update', [
            'as' => 'configurations.levels.update',
            'uses' => 'ConfigurationsController@updateLevels'
        ]);
    });

    /**
     * Main route routes
     */
    Route::group(['prefix' => 'main-route'], function () {

        // Show main route configuration
        Route::get('', [
            'as' => 'configurations.main-route.index',
            'uses' => 'ConfigurationsController@mainRoute'
        ]);

        // Get main route data
        Route::get('data', [
            'as' => 'configurations.main-route.data',
            'uses' => 'ConfigurationsController@mainRouteData'
        ]);

        // Update main route data
        Route::post('update', [
            'as' => 'configurations.main-route.update',
            'uses' => 'ConfigurationsController@updateMainRoute'
        ]);
    });

    /**
     * Providers routes
     */
    Route::group(['prefix' => 'providers'], function () {

        // Show providers
        Route::get('', [
            'as' => 'configurations.providers.index',
            'uses' => 'ConfigurationsController@providersList'
        ]);

        // Get providers route data
        Route::get('data', [
            'as' => 'configurations.providers.data',
            'uses' => 'ConfigurationsController@providersListData'
        ]);

        // status providers
        Route::post('status-providers', [
            'as' => 'configurations.providers.status',
            'uses' => 'ConfigurationsController@statusProviders'
        ]);

    });

    /**
     * Registration login routes
     */
    Route::group(['prefix' => 'registration-login'], function () {

        // Show registration login configuration
        Route::get('', [
            'as' => 'configurations.registration-login.index',
            'uses' => 'ConfigurationsController@registrationLogin'
        ]);

        // Get registration login data
        Route::get('data', [
            'as' => 'configurations.registration-login.data',
            'uses' => 'ConfigurationsController@registrationLoginData'
        ]);

        // Update registration login data
        Route::post('update', [
            'as' => 'configurations.registration-login.update',
            'uses' => 'ConfigurationsController@updateRegistrationLogin'
        ]);
    });

    /**
     * Template routes
     */
    Route::group(['prefix' => 'template'], function () {

        // Show template configuration
        Route::get('', [
            'as' => 'configurations.template.index',
            'uses' => 'ConfigurationsController@template'
        ]);

        // Get template themes
        Route::get('themes', [
            'as' => 'configurations.template.themes',
            'uses' => 'ConfigurationsController@themesData'
        ]);

        // Update template data
        Route::post('update', [
            'as' => 'configurations.template.update',
            'uses' => 'ConfigurationsController@updateTemplate'
        ]);
    });
});
