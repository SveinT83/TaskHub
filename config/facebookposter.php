<?php

return [
    'facebook_api' => [
        'app_id' => env('FACEBOOK_APP_ID', 'your-app-id'),
        'app_secret' => env('FACEBOOK_APP_SECRET', 'your-app-secret'),
        'default_access_token' => env('FACEBOOK_ACCESS_TOKEN', 'your-access-token'),
        'default_graph_version' => 'v21.0', // Or the latest version
    ],
];
