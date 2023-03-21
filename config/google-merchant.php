<?php

return [
    'enabled'     => env('GOOGLE_MERCHANT_ENABLED', false),
    'id'          => env('GOOGLE_MERCHANT_ID'),
    'app_name'    => env('GOOGLE_MERCHANT_APP_NAME'),
    'version'     => env('GOOGLE_MERCHANT_VERSION', 'v2.1'),
    'base_uri'    => env('GOOGLE_MERCHANT_BASE_URI', 'https://www.googleapis.com/content'),
    'credentials' => [
        'path' => env('GOOGLE_MERCHANT_CREDENTIALS_PATH'),
    ],
];
