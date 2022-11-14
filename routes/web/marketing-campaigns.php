<?php
/**
 * Marketing campaigns routes
 */
Route::group(['prefix' => 'marketing-campaigns', 'middleware' => ['auth']], function () {

    // Show marketing campaigns list
    Route::get('', [
        'as' => 'marketing-campaigns.index',
        'uses' => 'MarketingCampaignsController@index'
    ]);

    // Get all marketing campaigns
    Route::get('all', [
        'as' => 'marketing-campaigns.all',
        'uses' => 'MarketingCampaignsController@all'
    ]);

    // Delete marketing campaigns
    Route::get('delete/{id}', [
        'as' => 'marketing-campaigns.delete',
        'uses' => 'MarketingCampaignsController@delete'
    ]);

    // Edit marketing campaigns
    Route::get('edit/{id}', [
        'as' => 'marketing-campaigns.edit',
        'uses' => 'MarketingCampaignsController@edit'
    ]);

    // Create marketing campaigns
    Route::get('create', [
        'as' => 'marketing-campaigns.create',
        'uses' => 'MarketingCampaignsController@create'
    ]);

    // Store marketing campaigns
    Route::post('store', [
        'as' => 'marketing-campaigns.store',
        'uses' => 'MarketingCampaignsController@store'
    ]);

    // Update marketing campaigns
    Route::post('update', [
        'as' => 'marketing-campaigns.update',
        'uses' => 'MarketingCampaignsController@update'
    ]);
});
