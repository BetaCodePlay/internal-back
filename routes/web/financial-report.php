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

});
