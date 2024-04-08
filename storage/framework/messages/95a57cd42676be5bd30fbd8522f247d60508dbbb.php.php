<?php

return [
    /**
     * Client credentials grant
     */
    'client_credentials_grant' => [
        /**
         * Client ID
         */
        'client_id' => env('WALLET_CLIENT_CREDENTIALS_GRANT_ID'),

        /**
         * Client secret
         */
        'client_secret' => env('WALLET_CLIENT_CREDENTIALS_GRANT_SECRET'),
    ],

    /**
     * Password grant
     */
    'password_grant' => [
        /**
         * Client ID
         */
        'client_id' => env('WALLET_PASSWORD_GRANT_ID'),

        /**
         * Client secret
         */
        'client_secret' => env('WALLET_PASSWORD_GRANT_SECRET'),
    ],

    /**
     * Wallet URL
     */
    'url' => env('WALLET_URL'),
];
