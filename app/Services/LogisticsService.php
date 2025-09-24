<?php

namespace App\Services;

use App\Models\FreightOrder;
use App\Models\Location;

class LogisticsService
{
    protected $openRouteService;
    protected $openWeatherService;

    public function __construct(OpenRouteService $openRouteService, OpenWeatherService $openWeatherService)
    {
        $this->openRouteService = $openRouteService;
        $this->openWeatherService = $openWeatherService;
    }

    /**
     * Get comprehensive logistics data for a freight order
     */
    public function getFreightOrderLogistics(FreightOrder $freightOrder): array
    {
        $origin = $freightOrder->originLocation;
        $destination = $freightOrder->destinationLocation;

        // Get route optimization
        $routeData = $this->openRouteService->optimizeFreightRoute($origin, $destination);
        $routeStats = $routeData ? $this->openRouteService->getRouteStats($routeData) : null;

        // Get weather data for origin and destination
        $originWeather = $this->openWeatherService->getLocationWeather($origin);
        $destinationWeather = $this->openWeatherService->getLocationWeather($destination);

        // Calculate logistics metrics
        $logisticsData = [
            'route' => [
                'optimized' => $routeData ? true : false,
                'distance_km' => $routeStats['distance_km'] ?? 0,
                'duration_hours' => $routeStats['duration_hours'] ?? 0,
                'waypoints' => $routeStats['waypoints'] ?? 0,
                'geometry' => $routeStats['geometry'] ?? null,
            ],
            'weather' => [
                'origin' => $originWeather ? $this->openWeatherService->formatWeatherData($originWeather) : null,
                'destination' => $destinationWeather ? $this->openWeatherService->formatWeatherData($destinationWeather) : null,
            ],
            'safety' => $this->calculateRouteSafety($originWeather, $destinationWeather),
            'recommendations' => $this->getLogisticsRecommendations($routeStats, $originWeather, $destinationWeather),
            'estimated_cost' => $this->estimateDeliveryCost($routeStats, $originWeather, $destinationWeather),
        ];

        return $logisticsData;
    }

    /**
     * Get delivery zones for a warehouse with weather considerations
     */
    public function getDeliveryZonesWithWeather(Location $warehouse): array
    {
        // Get delivery zones
        $deliveryZones = $this->openRouteService->getDeliveryZones($warehouse);
        
        // Get weather data
        $weather = $this->openWeatherService->getLocationWeather($warehouse);
        $weatherData = $weather ? $this->openWeatherService->formatWeatherData($weather) : null;

        return [
            'zones' => $deliveryZones,
            'weather' => $weatherData,
            'weather_impact' => $weatherData ? $this->assessWeatherImpact($weatherData) : null,
        ];
    }

    /**
     * Assess if a delivery should be delayed due to weather
     */
    public function shouldDelayDelivery(FreightOrder $freightOrder): array
    {
        $originWeather = $this->openWeatherService->getLocationWeather($freightOrder->originLocation);
        $destinationWeather = $this->openWeatherService->getLocationWeather($freightOrder->destinationLocation);

        $originSafety = $originWeather ? $this->openWeatherService->isSafeForDelivery($originWeather) : ['is_safe' => true];
        $destinationSafety = $destinationWeather ? $this->openWeatherService->isSafeForDelivery($destinationWeather) : ['is_safe' => true];

        $shouldDelay = !$originSafety['is_safe'] || !$destinationSafety['is_safe'];

        return [
            'should_delay' => $shouldDelay,
            'reason' => $shouldDelay ? $this->getDelayReason($originSafety, $destinationSafety) : null,
            'estimated_delay_hours' => $shouldDelay ? $this->estimateDelayTime($originWeather, $destinationWeather) : 0,
            'origin_safety' => $originSafety,
            'destination_safety' => $destinationSafety,
        ];
    }

    /**
     * Get optimized route with weather considerations
     */
    public function getWeatherAwareRoute(Location $origin, Location $destination, array $waypoints = []): array
    {
        // Get base route
        $routeData = $this->openRouteService->optimizeFreightRoute($origin, $destination, $waypoints);
        
        // Get weather along the route
        $routeWeather = $this->getRouteWeather($routeData);
        
        // Adjust route based on weather
        $adjustedRoute = $this->adjustRouteForWeather($routeData, $routeWeather);

        return [
            'original_route' => $routeData,
            'weather_adjusted_route' => $adjustedRoute,
            'weather_data' => $routeWeather,
            'adjustments_made' => $this->getRouteAdjustments($routeData, $adjustedRoute),
        ];
    }

    /**
     * Calculate route safety score
     */
    protected function calculateRouteSafety(?array $originWeather, ?array $destinationWeather): array
    {
        $originSafety = $originWeather ? $this->openWeatherService->isSafeForDelivery($originWeather) : ['safety_score' => 100];
        $destinationSafety = $destinationWeather ? $this->openWeatherService->isSafeForDelivery($destinationWeather) : ['safety_score' => 100];

        $averageSafety = ($originSafety['safety_score'] + $destinationSafety['safety_score']) / 2;

        return [
            'overall_score' => $averageSafety,
            'risk_level' => $this->getRiskLevel($averageSafety),
            'origin_score' => $originSafety['safety_score'],
            'destination_score' => $destinationSafety['safety_score'],
        ];
    }

    /**
     * Get logistics recommendations
     */
    protected function getLogisticsRecommendations(?array $routeStats, ?array $originWeather, ?array $destinationWeather): array
    {
        $recommendations = [];
        $priority = 'medium';

        // Route-based recommendations
        if ($routeStats) {
            // Distance-based recommendations
            if ($routeStats['distance_km'] > 1000) {
                $recommendations[] = [
                    'type' => 'route',
                    'priority' => 'high',
                    'title' => 'Long Distance Route',
                    'message' => 'Route exceeds 1000km - consider multi-day delivery with overnight stops',
                    'action' => 'Plan driver rest periods and fuel stops'
                ];
            } elseif ($routeStats['distance_km'] > 500) {
                $recommendations[] = [
                    'type' => 'route',
                    'priority' => 'medium',
                    'title' => 'Extended Distance',
                    'message' => 'Route is ' . round($routeStats['distance_km']) . 'km - monitor driver fatigue',
                    'action' => 'Ensure adequate rest periods'
                ];
            }

            // Duration-based recommendations
            if ($routeStats['duration_hours'] > 12) {
                $recommendations[] = [
                    'type' => 'route',
                    'priority' => 'high',
                    'title' => 'Extended Travel Time',
                    'message' => 'Travel time exceeds 12 hours - requires overnight planning',
                    'action' => 'Schedule driver rest and accommodation'
                ];
            } elseif ($routeStats['duration_hours'] > 8) {
                $recommendations[] = [
                    'type' => 'route',
                    'priority' => 'medium',
                    'title' => 'Long Travel Time',
                    'message' => 'Travel time is ' . round($routeStats['duration_hours'], 1) . ' hours - monitor driver compliance',
                    'action' => 'Verify driver hours of service'
                ];
            }

            // Route efficiency
            if ($routeStats['distance_km'] > 0 && $routeStats['duration_hours'] > 0) {
                $avgSpeed = $routeStats['distance_km'] / $routeStats['duration_hours'];
                if ($avgSpeed < 30) {
                    $recommendations[] = [
                        'type' => 'route',
                        'priority' => 'medium',
                        'title' => 'Low Average Speed',
                        'message' => 'Average speed is ' . round($avgSpeed, 1) . ' km/h - check for traffic or route issues',
                        'action' => 'Consider alternative routes or departure time'
                    ];
                }
            }
        }

        // Weather-based recommendations
        if ($originWeather) {
            $originSafety = $this->openWeatherService->isSafeForDelivery($originWeather);
            if (!$originSafety['is_safe']) {
                $recommendations[] = [
                    'type' => 'weather',
                    'priority' => 'high',
                    'title' => 'Origin Weather Risk',
                    'message' => 'Unsafe weather conditions at pickup location',
                    'action' => 'Delay pickup until conditions improve'
                ];
            } elseif ($originSafety['safety_score'] < 80) {
                $recommendations[] = [
                    'type' => 'weather',
                    'priority' => 'medium',
                    'title' => 'Origin Weather Caution',
                    'message' => 'Weather conditions at origin require extra caution',
                    'action' => 'Use appropriate safety equipment'
                ];
            }
        }

        if ($destinationWeather) {
            $destinationSafety = $this->openWeatherService->isSafeForDelivery($destinationWeather);
            if (!$destinationSafety['is_safe']) {
                $recommendations[] = [
                    'type' => 'weather',
                    'priority' => 'high',
                    'title' => 'Destination Weather Risk',
                    'message' => 'Unsafe weather conditions at delivery location',
                    'action' => 'Delay delivery until conditions improve'
                ];
            } elseif ($destinationSafety['safety_score'] < 80) {
                $recommendations[] = [
                    'type' => 'weather',
                    'priority' => 'medium',
                    'title' => 'Destination Weather Caution',
                    'message' => 'Weather conditions at destination require extra caution',
                    'action' => 'Prepare for challenging delivery conditions'
                ];
            }
        }

        // Add positive recommendations when conditions are good
        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'positive',
                'priority' => 'low',
                'title' => 'Optimal Conditions',
                'message' => 'Route and weather conditions are optimal for delivery',
                'action' => 'Proceed with scheduled delivery'
            ];
        }

        // Sort by priority
        usort($recommendations, function($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] - $priorityOrder[$a['priority']];
        });

        return $recommendations;
    }

    /**
     * Estimate delivery cost based on route and weather
     */
    protected function estimateDeliveryCost(?array $routeStats, ?array $originWeather, ?array $destinationWeather): array
    {
        $baseCost = $routeStats ? ($routeStats['distance_km'] * 0.5) : 100; // $0.50 per km base rate or $100 default
        
        $weatherMultiplier = 1.0;
        if ($originWeather || $destinationWeather) {
            $weatherMultiplier = 1.2; // 20% increase for weather considerations
        }

        return [
            'base_cost' => $baseCost,
            'weather_adjustment' => $weatherMultiplier,
            'estimated_total' => $baseCost * $weatherMultiplier,
            'currency' => 'USD',
        ];
    }

    /**
     * Get risk level based on safety score
     */
    protected function getRiskLevel(float $safetyScore): string
    {
        if ($safetyScore >= 90) return 'low';
        if ($safetyScore >= 70) return 'medium';
        if ($safetyScore >= 50) return 'high';
        return 'critical';
    }

    /**
     * Get delay reason
     */
    protected function getDelayReason(array $originSafety, array $destinationSafety): string
    {
        $reasons = [];
        
        if (!$originSafety['is_safe']) {
            $reasons[] = 'unsafe conditions at origin';
        }
        
        if (!$destinationSafety['is_safe']) {
            $reasons[] = 'unsafe conditions at destination';
        }

        return implode(' and ', $reasons);
    }

    /**
     * Estimate delay time based on weather
     */
    protected function estimateDelayTime(?array $originWeather, ?array $destinationWeather): int
    {
        // Simple estimation - in a real system, this would be more sophisticated
        return 2; // 2 hours default delay
    }

    /**
     * Get weather data along a route
     */
    protected function getRouteWeather(?array $routeData): array
    {
        // This would typically sample weather along the route
        // For now, return empty array
        return [];
    }

    /**
     * Adjust route based on weather conditions
     */
    protected function adjustRouteForWeather(?array $routeData, array $routeWeather): ?array
    {
        // In a real implementation, this would modify the route
        // For now, return the original route
        return $routeData;
    }

    /**
     * Get route adjustments made
     */
    protected function getRouteAdjustments(?array $originalRoute, ?array $adjustedRoute): array
    {
        return [
            'distance_change_km' => 0,
            'duration_change_hours' => 0,
            'adjustments' => [],
        ];
    }

    /**
     * Assess weather impact on delivery zones
     */
    protected function assessWeatherImpact(array $weatherData): array
    {
        return [
            'delivery_delay_risk' => $weatherData['safety']['safety_score'] < 70 ? 'high' : 'low',
            'visibility_impact' => $weatherData['visibility'] < 1000 ? 'poor' : 'good',
            'wind_impact' => $weatherData['wind_speed'] > 15 ? 'high' : 'low',
        ];
    }
}
