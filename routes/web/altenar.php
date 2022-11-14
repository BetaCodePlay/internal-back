<?php

/**
 * Altenar routes
 */
Route::group(['prefix' => 'altenar', 'middleware' => ['auth']], function () {

    // Search ticket
    Route::get('ticket', [
        'as' => 'altenar.ticket',
        'uses' => 'AltenarController@ticket'
    ]);

    // Get ticket data
    Route::get('ticket-data', [
        'as' => 'altenar.ticket-data',
        'uses' => 'AltenarController@ticketData'
    ]);

    // Get ticket info
    Route::get('ticket-info/{ticket?}', [
        'as' => 'altenar.ticket-info',
        'uses' => 'AltenarController@ticketInfo'
    ]);
});
