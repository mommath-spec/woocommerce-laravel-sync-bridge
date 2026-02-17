<?php

return [
    'store_url' => env('WOO_STORE_URL'),
    'consumer_key' => env('WOO_CONSUMER_KEY'),
    'consumer_secret' => env('WOO_CONSUMER_SECRET'),
    'version' => env('WOO_API_VERSION', 'wc/v3'),

    'timeout' => (int) env('WOO_TIMEOUT', 20),
    'retry_times' => (int) env('WOO_RETRY_TIMES', 3),
    'per_page' => (int) env('WOO_PER_PAGE', 50),

    'log_channel' => env('WOO_SYNC_LOG_CHANNEL', 'stack'),
];
