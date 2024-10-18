<?php
/**
 * Financial report routes
 */
Route::group(['prefix' => 'financial-report', 'middleware' => ['auth']], function () {

    // Index
    Route::get('', [
        'as' => 'financial-report.index',
        'uses' => 'FinancialReportController@index'
    ]);

    // Get all financial report
    Route::get('all', [
        'as' => 'financial-report.all',
        'uses' => 'FinancialReportController@all'
    ]);

    // edit financial report
    Route::get('edit', [
        'as' => 'financial-report.edit',
        'uses' => 'FinancialReportController@edit'
    ]);

    //  all providers makers
    Route::get('makers/provider', [
        'as' => 'financial-report.maker',
        'uses' => 'FinancialReportController@maker'
    ]);

    // Store financial report
    Route::post('store', [
        'as' => 'financial-report.store',
        'uses' => 'FinancialReportController@store'
    ]);

});
