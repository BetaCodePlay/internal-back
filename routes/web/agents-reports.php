<?php

use Illuminate\Support\Facades\Route;


/**
 * Agents Reports routes
 */
Route::group(['prefix' => 'agents', 'middleware' => ['auth']], function () {

    // Reports routes
    Route::group(['prefix' => 'reports'], function () {
        // Get financial state data NEW
        Route::get('financial-state-data-v2/{userId?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data-new',
            'uses' => 'AgentsReportsController@financialStateData'
        ]);
        // Get financial state data NEW by Category
        Route::get('financial-state-data-v2-category/{user?}/{startDate?}/{endDate?}/{category}', [
            'as' => 'agents.reports.financial-state-data-new-ctageory',
            'uses' => 'AgentsReportsController@financialStateByCategoryData'
        ]);
        // Get Childrens
        Route::get('get-childrens', [
            'as' => 'agents.reports.get.childrens',
            'uses' => 'AgentsReportsController@getChildrens'
        ]);
         // Get Timezones
         Route::get('get-timezones', [
            'as' => 'agents.reports.get.timezones',
            'uses' => 'AgentsReportsController@getTimezones'
        ]);
         // Get Providers
         Route::get('get-providers', [
            'as' => 'agents.reports.get.providers',
            'uses' => 'AgentsReportsController@getProviders'
        ]);
    });
});
