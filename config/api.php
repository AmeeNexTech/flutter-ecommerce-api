<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the API including
    | rate limiting, pagination, and response formatting.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for different API endpoints
    |
    */

    'rate_limiting' => [
        'default' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'auth' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'otp' => [
            'max_attempts' => 3,
            'decay_minutes' => 5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for API responses
    |
    */

    'pagination' => [
        'per_page' => 15,
        'max_per_page' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Format
    |--------------------------------------------------------------------------
    |
    | Standard response format for all API endpoints
    |
    */

    'response_format' => [
        'include_timestamp' => true,
        'include_version' => true,
        'version' => '1.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Cache configuration for API responses
    |
    */

    'cache' => [
        'enabled' => env('API_CACHE_ENABLED', false),
        'ttl' => env('API_CACHE_TTL', 300), // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | API request/response logging configuration
    |
    */

    'logging' => [
        'enabled' => env('API_LOGGING_ENABLED', true),
        'log_requests' => env('API_LOG_REQUESTS', true),
        'log_responses' => env('API_LOG_RESPONSES', false),
        'log_errors' => env('API_LOG_ERRORS', true),
    ],

];
