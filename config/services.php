<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'safe_haven' => [
        'base_url' => env('SAFE_HAVEN_BASE_URL', 'https://api.safehaven.com'),
        'api_key' => env('SAFE_HAVEN_API_KEY'),
        'api_secret' => env('SAFE_HAVEN_API_SECRET'),
    ],

    'safehaven' => [
        'client_id' => env('SAFE_HEAVEN_CLIENTID'),
        'client_assertion' => env('SAFE_HEAVEN_CLIENT_ASSERTION'),
        'url' => env('SAFE_HEAVEN_URL'),
        'debit_account_number' => env('SAFE_HAVEN_DEBIT_ACCOUNT_NUMBER'),
    ],
    'termii' => [
        'api_key' => env('TERMII_API_KEY'),
        'sender_id' => env('TERMII_SENDER_ID'),
        'base_url' => env('TERMII_API_URL', 'https://api.ng.termii.com/api/'),
        'channel' => env('TERMII_CHANNEL', 'generic'),
    ],
];
