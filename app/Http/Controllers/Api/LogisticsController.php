<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreightOrder;
use App\Models\Location;
use App\Services\LogisticsService;
use App\Services\OpenRouteService;
use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LogisticsController extends Controller
{
    protected $logisticsService;
    protected $openRouteService;
    protected $openWeatherService;

    public function __construct(
        LogisticsService $logisticsService,
        OpenRouteService $openRouteService,
        OpenWeatherService $openWeatherService
    ) {
        $this->logisticsService = $logisticsService;
        $this->openRouteService = $openRouteService;
        $this->openWeatherService = $openWeatherService;
    }

    /**
     * Get logistics data for a freight order
     */
    public function getFreightOrderLogistics(string $id): JsonResponse
    {
        $freightOrder = FreightOrder::with(['originLocation', 'destinationLocation'])->find($id);

        if (!$freightOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        $logisticsData = $this->logisticsService->getFreightOrderLogistics($freightOrder);

        return response()->json([
            'success' => true,
            'data' => $logisticsData
        ]);
    }

    /**
     * Get delivery zones for a location
     */
    public function getDeliveryZones(string $id): JsonResponse
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $deliveryZones = $this->logisticsService->getDeliveryZonesWithWeather($location);

        return response()->json([
            'success' => true,
            'data' => $deliveryZones
        ]);
    }

    /**
     * Check if delivery should be delayed
     */
    public function checkDeliveryDelay(string $id): JsonResponse
    {
        $freightOrder = FreightOrder::with(['originLocation', 'destinationLocation'])->find($id);

        if (!$freightOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        $delayData = $this->logisticsService->shouldDelayDelivery($freightOrder);

        return response()->json([
            'success' => true,
            'data' => $delayData
        ]);
    }

    /**
     * Get weather data for a location
     */
    public function getLocationWeather(string $id): JsonResponse
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        $weather = $this->openWeatherService->getLocationWeather($location);

        if (!$weather) {
            return response()->json([
                'success' => false,
                'message' => 'Weather data not available'
            ], 404);
        }

        $formattedWeather = $this->openWeatherService->formatWeatherData($weather);

        return response()->json([
            'success' => true,
            'data' => $formattedWeather
        ]);
    }

    /**
     * Get optimized route between locations
     */
    public function getOptimizedRoute(Request $request): JsonResponse
    {
        $request->validate([
            'origin_latitude' => 'required|numeric',
            'origin_longitude' => 'required|numeric',
            'destination_latitude' => 'required|numeric',
            'destination_longitude' => 'required|numeric',
            'waypoints' => 'nullable|array',
            'waypoints.*.latitude' => 'numeric',
            'waypoints.*.longitude' => 'numeric',
        ]);

        $coordinates = [
            [$request->origin_longitude, $request->origin_latitude]
        ];

        // Add waypoints
        if ($request->waypoints) {
            foreach ($request->waypoints as $waypoint) {
                $coordinates[] = [$waypoint['longitude'], $waypoint['latitude']];
            }
        }

        // Add destination
        $coordinates[] = [$request->destination_longitude, $request->destination_latitude];

        $routeData = $this->openRouteService->getOptimizedRoute($coordinates);
        $routeStats = $this->openRouteService->getRouteStats($routeData);

        return response()->json([
            'success' => true,
            'data' => [
                'route' => $routeData,
                'stats' => $routeStats,
            ]
        ]);
    }

    /**
     * Get weather data for multiple locations
     */
    public function getMultipleLocationsWeather(Request $request): JsonResponse
    {
        $request->validate([
            'location_ids' => 'required|array',
            'location_ids.*' => 'integer|exists:locations,id'
        ]);

        $locations = Location::whereIn('id', $request->location_ids)->get();
        $weatherData = $this->openWeatherService->getMultipleLocationsWeather($locations);

        return response()->json([
            'success' => true,
            'data' => $weatherData
        ]);
    }

    /**
     * Get weather-aware route
     */
    public function getWeatherAwareRoute(Request $request): JsonResponse
    {
        $request->validate([
            'origin_id' => 'required|integer|exists:locations,id',
            'destination_id' => 'required|integer|exists:locations,id',
            'waypoint_ids' => 'nullable|array',
            'waypoint_ids.*' => 'integer|exists:locations,id'
        ]);

        $origin = Location::find($request->origin_id);
        $destination = Location::find($request->destination_id);
        $waypoints = [];

        if ($request->waypoint_ids) {
            $waypoints = Location::whereIn('id', $request->waypoint_ids)->get()->toArray();
        }

        $weatherAwareRoute = $this->logisticsService->getWeatherAwareRoute($origin, $destination, $waypoints);

        return response()->json([
            'success' => true,
            'data' => $weatherAwareRoute
        ]);
    }
}
