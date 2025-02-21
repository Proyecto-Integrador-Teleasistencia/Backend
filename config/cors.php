<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'auth/*', 'login/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5174', 'https://accounts.google.com'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

    'supports_credentials' => true,

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin-Opener-Policy Configuration
    |--------------------------------------------------------------------------
    */
    'access_control_allow_origin' => '*',
    'cross_origin_opener_policy' => 'same-origin-allow-popups',
];
