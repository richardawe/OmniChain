<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;

class LogisticsTestController extends Controller
{
    /**
     * Test the logistics services without external API calls
     */
    public function testServices(): JsonResponse
    {
        $locations = Location::with('company')->take(2)->get();

        if ($locations->count() < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Need at least 2 locations to test logistics services',
                'data' => [
                    'available_locations' => $locations->count(),
                    'required' => 2
                ]
            ]);
        }

        $origin = $locations[0];
        $destination = $locations[1];

        // Test data structure
        $testData = [
            'services_available' => [
                'openroute' => class_exists(\App\Services\OpenRouteService::class),
                'openweather' => class_exists(\App\Services\OpenWeatherService::class),
                'logistics' => class_exists(\App\Services\LogisticsService::class),
            ],
            'test_locations' => [
                'origin' => [
                    'id' => $origin->id,
                    'name' => $origin->name,
                    'city' => $origin->city,
                    'state' => $origin->state,
                    'coordinates' => [
                        'latitude' => $origin->latitude,
                        'longitude' => $origin->longitude,
                    ]
                ],
                'destination' => [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'city' => $destination->city,
                    'state' => $destination->state,
                    'coordinates' => [
                        'latitude' => $destination->latitude,
                        'longitude' => $destination->longitude,
                    ]
                ]
            ],
            'api_endpoints' => [
                'logistics_freight_order' => '/api/v1/logistics/freight-orders/{id}',
                'delivery_zones' => '/api/v1/logistics/delivery-zones/{id}',
                'weather_check' => '/api/v1/logistics/weather/{id}',
                'optimize_route' => '/api/v1/logistics/optimize-route',
                'weather_aware_route' => '/api/v1/logistics/weather-aware-route',
            ],
            'configuration' => [
                'openroute_configured' => !empty(config('external_apis.openroute.api_key')),
                'openweather_configured' => !empty(config('external_apis.openweather.api_key')),
            ],
            'next_steps' => [
                '1. Get API keys from OpenRouteService and OpenWeatherMap',
                '2. Add keys to .env file',
                '3. Test endpoints with real data',
                '4. Integrate with frontend dashboard'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Logistics services test completed',
            'data' => $testData
        ]);
    }
}
