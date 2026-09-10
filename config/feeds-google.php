<?php

return [

    'api' => [
        'credentials' => [
            'path' => env('GOOGLE_API_CREDENTIALS_PATH'),
        ],
    ],

    'merchant' => [
        'id' => env('GOOGLE_MERCHANT_ID', null),

        'data_source' => env('GOOGLE_MERCHANT_DATA_SOURCE', null),
        'data_source_name' => env('GOOGLE_MERCHANT_DATA_SOURCE_NAME', null),
        'feed_label' => env('GOOGLE_MERCHANT_FEED_LABEL', 'ES'),
        'content_language' => env('GOOGLE_MERCHANT_CONTENT_LANGUAGE', 'es'),
        'transport' => env('GOOGLE_MERCHANT_TRANSPORT', 'rest'),

        'queue' => [
            'connection' => env('GOOGLE_MERCHANT_QUEUE_CONNECTION', 'sync'),
            'name' => env('GOOGLE_MERCHANT_QUEUE_NAME', 'google_merchant'),
            'delay' => env('GOOGLE_MERCHANT_QUEUE_DELAY', 60),
            'tries' => env('GOOGLE_MERCHANT_QUEUE_TRIES', 1),
        ],
    ],

    'index' => [
        'enabled' => env('GOOGLE_INDEX_ENABLED', null),

        'queue' => [
            'connection' => env('GOOGLE_INDEX_QUEUE_CONNECTION', 'sync'),
            'name' => env('GOOGLE_INDEX_QUEUE_NAME', 'google_index'),
            'delay' => env('GOOGLE_INDEX_QUEUE_DELAY', 60),
            'tries' => env('GOOGLE_INDEX_QUEUE_TRIES', 1),
        ],
    ],

];
