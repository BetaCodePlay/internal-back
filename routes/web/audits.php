<?php

/**
 * Audits routes
 */
Route::group(['prefix' => 'audits', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'reports'], function () { 
        // Show audits list
        Route::get('', [
            'as' => 'audits.index',
            'uses' => 'AuditsController@index'
        ]);

        // Audits Data
        Route::get('audits-data/{start_date?}/{end_date?}/{type?}/{users?}', [
            'as' => 'audits.data',
            'uses' => 'AuditsController@auditsData'
        ]);
    });
});
