<?php

namespace App\Services;

use App\Models\Location;

class OpenRouteService extends BaseApiService
{
    protected function initializeConfig()
    {
        $config = config('external_apis.openroute');
        $this->baseUrl = $config['base_url'];
        $this->apiKey = $config['api_key'];
        $this->timeout = $config['timeout'];
        $this->cacheTtl = $config['cache_ttl'];
    }

    /**
     * Get optimized route between multiple points
     */
    public function getOptimizedRoute(array $coordinates, string $profile = 'driving-hgv'): ?array
    {
        $endpoint = 'directions/' . $profile;
        
        $params = [
            'coordinates' => $coordinates,
            'format' => 'json',
            'instructions' => true,
            'geometry' => true,
            'elevation' => false,
        ];

        return $this->makeRequest($endpoint, $params, 'POST');
    }

    /**
     * Get directions between two points
     */
    public function getDirections(array $coordinates, string $profile = 'driving-hgv', array $options = []): ?array
    {
        $endpoint = 'directions';
        
        $params = array_merge([
            'coordinates' => $coordinates,
            'profile' => $profile,
            'format' => 'json',
            'instructions' => true,
            'geometry' => true,
            'elevation' => false,
        ], $options);

        return $this->makeRequest($endpoint, $params, 'POST');
    }

    /**
     * Get distance matrix between multiple points
     */
    public function getDistanceMatrix(array $coordinates, string $profile = 'driving-hgv'): ?array
    {
        $endpoint = 'matrix';
        
        $params = [
            'locations' => $coordinates,
            'profile' => $profile,
            'sources' => [0],
            'destinations' => array_keys($coordinates),
            'metrics' => ['distance', 'duration'],
        ];

        return $this->makeRequest($endpoint, $params, 'POST');
    }

    /**
     * Get isochrone (travel time contours) from a location
     */
    public function getIsochrones(array $coordinates, array $ranges, string $profile = 'driving-hgv'): ?array
    {
        $endpoint = 'isochrones';
        
        $params = [
            'coordinates' => $coordinates,
            'profile' => $profile,
            'range' => $ranges,
            'range_type' => 'time',
            'format' => 'json',
        ];

        return $this->makeRequest($endpoint, $params, 'POST');
    }

    /**
     * Optimize route for a freight order
     */
    public function optimizeFreightRoute(Location $origin, Location $destination, array $waypoints = []): ?array
    {
        $coordinates = [
            [$origin->longitude, $origin->latitude]
        ];

        // Add waypoints
        foreach ($waypoints as $waypoint) {
            $coordinates[] = [$waypoint->longitude, $waypoint->latitude];
        }

        // Add destination
        $coordinates[] = [$destination->longitude, $destination->latitude];

        return $this->getOptimizedRoute($coordinates, 'driving-hgv');
    }

    /**
     * Get delivery zones for a warehouse
     */
    public function getDeliveryZones(Location $warehouse, array $timeRanges = [30, 60, 90]): ?array
    {
        $coordinates = [[$warehouse->longitude, $warehouse->latitude]];
        return $this->getIsochrones($coordinates, $timeRanges);
    }

    /**
     * Calculate route statistics
     */
    public function getRouteStats(array $routeData): array
    {
        if (!$routeData || !isset($routeData['routes'][0])) {
            return [];
        }

        $route = $routeData['routes'][0];
        $summary = $route['summary'];

        return [
            'distance' => $summary['distance'], // meters
            'duration' => $summary['duration'], // seconds
            'distance_km' => round($summary['distance'] / 1000, 2),
            'duration_hours' => round($summary['duration'] / 3600, 2),
            'waypoints' => count($route['way_points'] ?? []),
            'geometry' => $route['geometry'] ?? null,
        ];
    }
}
