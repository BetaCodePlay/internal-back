<?php

// Login
Route::get('login', [
    'as' => 'auth.login.api',
    'uses' => 'AuthController@loginApi'
]);
