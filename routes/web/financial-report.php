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

    // Delete sliders
    Route::get('delete/{id}', [
        'as' => 'financial-report.delete',
        'uses' => 'FinancialReportController@delete'
    ]);

    // edit financial report
    Route::get('edit/{id}', [
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

    // Store financial report
    Route::post('update', [
        'as' => 'financial-report.update',
        'uses' => 'FinancialReportController@update'
    ]);

    /**
     * Financial report providers routes
     */
    Route::group(['prefix' => 'providers', 'middleware' => ['auth']], function () {

        // Index
        Route::get('', [
            'as' => 'financial-report.providers.index',
            'uses' => 'FinancialReportController@indexReportProvider'
        ]);

        // Delete sliders
        Route::get('delete/{id}', [
            'as' => 'financial-report.providers.delete',
            'uses' => 'FinancialReportController@deleteReportProvider'
        ]);

        // edit financial report
        Route::get('edit/{id}', [
            'as' => 'financial-report.providers.edit',
            'uses' => 'FinancialReportController@editReportProvider'
        ]);

        // Store financial report
        Route::post('search', [
            'as' => 'financial-report.providers.search',
            'uses' => 'FinancialReportController@search'
        ]);

    });
});

