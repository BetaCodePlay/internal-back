<?php

use Illuminate\Support\Facades\Route;


/**
 * Agents Reports routes
 */
Route::group(['prefix' => 'agents', 'middleware' => ['auth']], function () {

    // Reports routes
    Route::group(['prefix' => 'reports'], function () {
        // Get financial state data NEW 
        Route::get('financial-state-data-v2/{user?}/{startDate?}/{endDate?}', [
            'as' => 'agents.reports.financial-state-data-new',
            'uses' => 'AgentsReportsController@financialStateData'
        ]);
        // Get Childrens
        Route::get('get-childrens', [
            'as' => 'agents.reports.get.childrens',
            'uses' => 'AgentsReportsController@getChildrens'
        ]);
    });
});
