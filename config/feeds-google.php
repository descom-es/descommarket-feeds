<?php

return [

    'api' => [
        'id'          => env('GOOGLE_API_ID'),
        'app_name'    => env('GOOGLE_API_APP_NAME'),
        'version'     => env('GOOGLE_API_VERSION', 'v2.1'),
        'base_uri'    => env('GOOGLE_API_BASE_URI', 'https://www.googleapis.com/content'),
        'credentials' => [
            'path' => env('GOOGLE_API_CREDENTIALS_PATH'),
        ],
    ],

    'merchant' => [
        'enabled'     => env('GOOGLE_MERCHANT_ENABLED', false),

        'queue' => [
            'connection' => env('GOOGLE_MERCHANT_QUEUE_CONNECTION', 'sync'),
            'name'    => env('GOOGLE_MERCHANT_QUEUE_NAME', 'google_merchant'),
            'delay'   => env('GOOGLE_MERCHANT_QUEUE_DELAY', 60),
            'tries'  => env('GOOGLE_MERCHANT_QUEUE_TRIES', 1),
        ]
    ],

];
