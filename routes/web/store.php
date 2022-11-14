<?php

/**
 * Store routes
 */
Route::group(['prefix' => 'store', 'middleware' => ['auth']], function () {

    // Get transactions by user
    Route::get('transactions/{user?}', [
        'as' => 'store.transactions',
        'uses' => 'StoreController@transactions'
    ]);

    /**
     * Rewards routes
     */
    Route::group(['prefix' => 'categories'], function () {

        // Show rewards list
        Route::get('all', [
            'as' => 'store.categories.data',
            'uses' => 'StoreController@allCategories'
        ]);

        // Create categories
        Route::get('create', [
            'as' => 'store.categories.create',
            'uses' => 'StoreController@category'
        ]);

        // category details
        Route::get('categories-details/{id}', [
            'as' => 'store.categories.details',
            'uses' => 'StoreController@categoryDetails'
        ]);

        // Delete category
        Route::get('delete/{id}', [
            'as' => 'store.categories.delete',
            'uses' => 'StoreController@deleteCategory'
        ]);

        // Store categories
        Route::post('store', [
            'as' => 'store.categories.store',
            'uses' => 'StoreController@storeCategories'
        ]);

        // Update categories
        Route::post('update', [
            'as' => 'store.categories.update',
            'uses' => 'StoreController@updateCategory'
        ]);

    });

    /**
     * Rewards routes
     */
    Route::group(['prefix' => 'rewards'], function () {

        // Show rewards list
        Route::get('', [
            'as' => 'store.rewards.index',
            'uses' => 'StoreController@rewards'
        ]);

        // Get all rewards
        Route::get('all', [
            'as' => 'store.rewards.all',
            'uses' => 'StoreController@allRewards'
        ]);

        // Get claims by user
        Route::get('claims/{user?}', [
            'as' => 'store.rewards.claims',
            'uses' => 'StoreController@claims'
        ]);

        // Create rewards
        Route::get('create', [
            'as' => 'store.rewards.create',
            'uses' => 'StoreController@createRewards'
        ]);

        // Delete reward
        Route::get('delete/{id}/{file}', [
            'as' => 'store.rewards.delete',
            'uses' => 'StoreController@deleteRewards'
        ]);

        // Edit reward
        Route::get('edit/{id}', [
            'as' => 'store.rewards.edit',
            'uses' => 'StoreController@editRewards'
        ]);


        // Store rewards
        Route::post('store', [
            'as' => 'store.rewards.store',
            'uses' => 'StoreController@storeRewards'
        ]);

        // Update rewards
        Route::post('update', [
            'as' => 'store.rewards.update',
            'uses' => 'StoreController@updateRewards'
        ]);

    });

    /**
     * Actions routes
     */
    Route::group(['prefix' => 'actions'], function () {

        // Show Configurations actions list
        Route::get('', [
            'as' => 'store.actions.index',
            'uses' => 'StoreController@actions'
        ]);

        // Get all actions configurations
        Route::get('all', [
            'as' => 'store.actions.all',
            'uses' => 'StoreController@allActions'
        ]);

        // Create action configurations
        Route::get('create', [
            'as' => 'store.actions.create',
            'uses' => 'StoreController@createActions'
        ]);


        // Delete action
        Route::get('delete/{id}', [
            'as' => 'store.actions.delete',
            'uses' => 'StoreController@deleteAction'
        ]);

        // Exclude providers data
        Route::get('exclude-providers', [
            'as' => 'store.actions.exclude-providers',
            'uses' => 'StoreController@excludeProviders'
        ]);

        // Edit actions configurations
        Route::get('edit/{currency}/{id}', [
            'as' => 'store.actions.edit',
            'uses' => 'StoreController@editActions'
        ]);

        // Exclude providers data
        Route::get('type-providers', [
            'as' => 'store.actions.type-providers',
            'uses' => 'StoreController@typeProviders'
        ]);

        // Store actions configurations
        Route::post('store', [
            'as' => 'store.actions.store',
            'uses' => 'StoreController@storeActions'
        ]);

        // Update actions configurations
        Route::post('update', [
            'as' => 'store.actions.update',
            'uses' => 'StoreController@updateActions'
        ]);
    });

    /**
     * Actions routes
     */
    Route::group(['prefix' => 'reports'], function () {

        // View redeemed rewards
        Route::get('redeemed-rewards', [
            'as' => 'store.reports.redeemed-rewards',
            'uses' => 'StoreController@redeemedRewards'
        ]);

        // Redeemed rewards data
        Route::get('redeemed-rewards-data', [
            'as' => 'store.reports.redeemed-rewards-data',
            'uses' => 'StoreController@redeemedRewardsData'
        ]);
    });
});
