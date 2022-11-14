<?php

/**
 * Email configurations routes
 */
Route::group(['prefix' => 'email-configurations', 'middleware' => ['auth']], function () {

    //Get view email types
    Route::get('', [
        'as' => 'email-configurations.index',
        'uses' => 'EmailConfigurationsController@index'
    ]);

    // Get email types data
    Route::get('configurations-data', [
        'as' => 'email-configurations.data',
        'uses' => 'EmailConfigurationsController@configurationsData'
    ]);

    //get view update email types
    Route::get('edit/{id}', [
        'as' => 'email-configurations.edit',
        'uses' => 'EmailConfigurationsController@edit'
    ]);

    //update email content data
    Route::post('update', [
        'as' => 'email-configurations.updateEmail',
        'uses' => 'EmailConfigurationsController@updateEmail'
    ]);

});



