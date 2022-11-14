<?php

/**
 * Invoices routes
 */
Route::group(['prefix' => 'invoices', 'middleware' => ['auth']], function () {

    // Show index
    Route::get('', [
        'as' => 'invoices.index',
        'uses' => 'InvoicesController@index'
    ]);

    // Show index
    Route::get('{start_date?}/{end_date?}/{whitelabel?}/{provider?}/{currency?}/{convert?}', [
        'as' => 'invoices.invoice-data',
        'uses' => 'InvoicesController@invoiceData'
    ]);
});
