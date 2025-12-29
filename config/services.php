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

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Moçambique
    |--------------------------------------------------------------------------
    */
    'mpesa' => [
        'sandbox' => env('MPESA_SANDBOX', true),
        'api_key' => env('MPESA_API_KEY'),
        'public_key' => env('MPESA_PUBLIC_KEY'),
        'service_provider_code' => env('MPESA_SERVICE_PROVIDER_CODE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | e-Mola
    |--------------------------------------------------------------------------
    */
    'emola' => [
        'sandbox' => env('EMOLA_SANDBOX', true),
        'merchant_id' => env('EMOLA_MERCHANT_ID'),
        'api_key' => env('EMOLA_API_KEY'),
        'secret_key' => env('EMOLA_SECRET_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe (Card Payments)
    |--------------------------------------------------------------------------
    */
    'stripe' => [
        'sandbox' => env('STRIPE_SANDBOX', true),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

];
