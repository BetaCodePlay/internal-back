<?php

/**
 * Email templates routes
 */
Route::group(['prefix' => 'email-templates', 'middleware' => ['auth']], function () {

    // Get all templates
    Route::get('all', [
        'as' => 'email-templates.all',
        'uses' => 'EmailTemplatesController@all'
    ]);

    // Show create view
    Route::get('create', [
        'as' => 'email-templates.create',
        'uses' => 'EmailTemplatesController@create'
    ]);

    // Delete template
    Route::get('delete/{id}', [
        'as' => 'email-templates.delete',
        'uses' => 'EmailTemplatesController@delete'
    ]);

    // Duplicate template
    Route::get('duplicate/{id}', [
        'as' => 'email-templates.duplicate',
        'uses' => 'EmailTemplatesController@duplicate'
    ]);

    // Show edit view
    Route::get('edit/{id}', [
        'as' => 'email-templates.edit',
        'uses' => 'EmailTemplatesController@edit'
    ]);

    // Show templates list
    Route::get('send/{template}', [
        'as' => 'email-templates.send',
        'uses' => 'EmailTemplatesController@email'
    ]);

    // Store template
    Route::post('store', [
        'as' => 'email-templates.store',
        'uses' => 'EmailTemplatesController@store'
    ]);

    // Store template
    Route::post('test-email', [
        'as' => 'email-templates.test-email',
        'uses' => 'EmailTemplatesController@testEmail'
    ]);

    // Update template
    Route::post('update', [
        'as' => 'email-templates.update',
        'uses' => 'EmailTemplatesController@update'
    ]);

    // Show templates list
    Route::get('', [
        'as' => 'email-templates.index',
        'uses' => 'EmailTemplatesController@index'
    ]);


    Route::group(['prefix' => 'transaction'], function () {
        // Get all templates
        Route::get('all', [
            'as' => 'email-templates-transaction.all',
            'uses' => 'EmailTemplatesController@allTransactions'
        ]);

        // Show create transaction view
        Route::get('create', [
            'as' => 'email-templates-transaction.create',
            'uses' => 'EmailTemplatesController@createTransaction'
        ]);

        // Delete template
        Route::get('delete/{id}', [
            'as' => 'email-templates-transaction.delete',
            'uses' => 'EmailTemplatesController@deleteTransaction'
        ]);


        // Duplicate template
        Route::get('duplicate/{id}/{email_templates_type_id}', [
            'as' => 'email-templates-transaction.duplicate',
            'uses' => 'EmailTemplatesController@duplicateTransaction'
        ]);

        // Show edit view
        Route::get('edit/{id}', [
            'as' => 'email-templates-transaction.edit',
            'uses' => 'EmailTemplatesController@editTransactions'
        ]);

        // Show templates list
        Route::get('send/{template}', [
            'as' => 'email-templates.send',
            'uses' => 'EmailTemplatesController@email'
        ]);

        // Store template
        Route::post('store', [
            'as' => 'email-templates-transaction.store',
            'uses' => 'EmailTemplatesController@storeTransaction'
        ]);

        // Update template
        Route::post('update', [
            'as' => 'email-templates-transaction.update',
            'uses' => 'EmailTemplatesController@updateTransaction'
        ]);

        // Show templates transaction list
        Route::get('', [
            'as' => 'email-templates-transaction.index',
            'uses' => 'EmailTemplatesController@indexTransaction'
        ]);
    });
});

/**
 * Email templates routes
 */
Route::group(['prefix' => 'email-templates'], function () {

    // Upload images
    Route::post('upload-images', [
        'as' => 'email-templates.upload-images',
        'uses' => 'EmailTemplatesController@uploadImages'
    ]);

    // Get gallery images
    Route::get('upload-images', [
        'as' => 'email-templates.images',
        'uses' => 'EmailTemplatesController@images'
    ]);
});


