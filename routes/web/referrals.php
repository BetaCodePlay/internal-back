<?php

/**
 * Referrals routes
 */
Route::group(['prefix' => 'referrals'], function () {

    // Show referral user
    Route::get('', [
        'as' => 'referrals.index',
        'uses' => 'ReferralsController@index'
    ]);

    // Show referral user
    Route::get('referral-users-list', [
        'as' => 'referrals.referral-users-list-data',
        'uses' => 'ReferralsController@usersList'
    ]);

    // Show referral user
    Route::get('create', [
        'as' => 'referrals.create',
        'uses' => 'ReferralsController@user'
    ]);

    // Referral user data
    Route::post('referral-users-data', [
        'as' => 'referrals.referral-user-data',
        'uses' => 'ReferralsController@userData'
    ]);

    // Remove referral user
    Route::get('remove-referral-user/{user}', [
        'as' => 'referrals.remove-referral-user',
        'uses' => 'ReferralsController@removeReferralUserData'
    ]);

    // Show referral totals
    Route::get('referral-totals', [
        'as' => 'referrals.referral-totals',
        'uses' => 'ReferralsController@referralsTotals'
    ]);

    // List referral totals
    Route::get('referral-totals-list', [
        'as' => 'referrals.referral-totals-list-data',
        'uses' => 'ReferralsController@referralsTotalsList'
    ]);

    // Show referral top totals
    Route::get('referral-top', [
        'as' => 'referrals.referral-top',
        'uses' => 'ReferralsController@referralsTop'
    ]);

    // List referral top
    Route::get('referral-top-list', [
        'as' => 'referrals.referral-top-list-data',
        'uses' => 'ReferralsController@referralsTopList'
    ]);
});
