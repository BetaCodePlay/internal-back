<?php

/**
 * IQ Soft routes
 */
Route::group(['prefix' => 'iq-soft', 'middleware' => ['auth']], function () {

    // Search ticket
    Route::get('ticket', [
        'as' => 'iq-soft.ticket',
        'uses' => 'IQSoftController@ticket'
    ]);

    // Get ticket data
    Route::get('ticket-data', [
        'as' => 'iq-soft.ticket-data',
        'uses' => 'IQSoftController@ticketData'
    ]);

    // Get ticket info
    Route::get('ticket-info/{ticket?}', [
        'as' => 'iq-soft.ticket-info',
        'uses' => 'IQSoftController@ticketInfo'
    ]);

    // Vie totals
    Route::get('totals', [
        'as' => 'iq-soft.totals',
        'uses' => 'IQSoftController@totals'
    ]);

    // Get  products users totals data
    Route::get('totals-data/{start_date?}/{end_date?}/{whitelabel?}/{currency?}', [
        'as' => 'iq-soft.totals-data',
        'uses' => 'IQSoftController@totalsData'
    ]);
});
