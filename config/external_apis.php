<?php

return [
    /*
    |--------------------------------------------------------------------------
    | External API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for external APIs used by the OmniChain platform
    |
    */

    'openroute' => [
        'api_key' => env('OPENROUTE_API_KEY'),
        'base_url' => 'https://api.openrouteservice.org/v2',
        'default_profile' => 'driving-hgv', // Heavy goods vehicle
        'timeout' => 30,
        'cache_ttl' => 3600, // 1 hour cache
    ],

    'openweather' => [
        'api_key' => env('OPENWEATHER_API_KEY'),
        'base_url' => 'https://api.openweathermap.org/data/2.5',
        'timeout' => 15,
        'cache_ttl' => 1800, // 30 minutes cache
        'units' => 'metric', // Celsius, meters, etc.
    ],
];
