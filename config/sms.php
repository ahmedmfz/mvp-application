<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS provider
    |--------------------------------------------------------------------------
    | Change this anytime:
    | 'twilio' | 'nexmo' | 'messagebird'  (or your own)
    */
    'default' => env('SMS_PROVIDER', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | Providers configuration
    |--------------------------------------------------------------------------
    */
    'providers' => [

        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token'  => env('TWILIO_AUTH_TOKEN'),
            'from'        => env('TWILIO_FROM'),
        ],

        'nexmo' => [ // Vonage
            'api_key'    => env('VONAGE_KEY'),
            'api_secret' => env('VONAGE_SECRET'),
            'from'       => env('VONAGE_FROM', 'MyApp'),
        ],

        'messagebird' => [
            'access_key' => env('MESSAGEBIRD_ACCESS_KEY'),
            'originator' => env('MESSAGEBIRD_ORIGINATOR', 'MyApp'),
        ],
    ],
];