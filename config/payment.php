<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateways
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | by the payment library. You may set this to any of the gateways
    | defined in the "gateways" array below.
    |
    */

    'default' => 'paypal',

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    | Here you may configure your payment gateways for your application.
    | Supported: "Stripe", "PayPal", "vodafone" (custom)
    |
    */

    'gateways' => [
        'stripe' => [
            'driver' => 'stripe',
            'api_key' => env('STRIPE_API_KEY'),
            'secret'  => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            // all config options for stripe gateway
        ],

        'paypal' => [
            'driver' => 'paypal',
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret'    => env('PAYPAL_SECRET'),
            'mode'      => env('PAYPAL_MODE', 'sandbox'),
            // all config options for stripe gateway
        ],

        'vodafone' => [
            'driver' => 'vodafone',
            // all config options for vodafone custom gateway
        ],
    ],
];
