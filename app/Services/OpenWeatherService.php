<?php

namespace App\Services;

use App\Models\Location;

class OpenWeatherService extends BaseApiService
{
    protected function initializeConfig()
    {
        $config = config('external_apis.openweather');
        $this->baseUrl = $config['base_url'];
        $this->apiKey = $config['api_key'];
        $this->timeout = $config['timeout'];
        $this->cacheTtl = $config['cache_ttl'];
        $this->units = $config['units'];
    }

    /**
     * Get current weather for a location
     */
    public function getCurrentWeather(float $latitude, float $longitude): ?array
    {
        $endpoint = 'weather';
        
        $params = [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $this->apiKey,
            'units' => $this->units,
        ];

        return $this->makeRequest($endpoint, $params);
    }

    /**
     * Get weather forecast for a location
     */
    public function getWeatherForecast(float $latitude, float $longitude, int $days = 5): ?array
    {
        $endpoint = 'forecast';
        
        $params = [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $this->apiKey,
            'units' => $this->units,
            'cnt' => $days * 8, // 8 forecasts per day (3-hour intervals)
        ];

        return $this->makeRequest($endpoint, $params);
    }

    /**
     * Get weather for a Location model
     */
    public function getLocationWeather(Location $location): ?array
    {
        if (!$location->latitude || !$location->longitude) {
            return null;
        }

        return $this->getCurrentWeather($location->latitude, $location->longitude);
    }

    /**
     * Get weather for multiple locations
     */
    public function getMultipleLocationsWeather($locations): array
    {
        $weatherData = [];
        
        foreach ($locations as $location) {
            if ($location instanceof Location) {
                $weather = $this->getLocationWeather($location);
                if ($weather) {
                    $weatherData[$location->id] = $this->formatWeatherData($weather);
                }
            }
        }

        return $weatherData;
    }

    /**
     * Check if weather conditions are safe for delivery
     */
    public function isSafeForDelivery(array $weatherData): array
    {
        $conditions = $weatherData['weather'][0]['main'] ?? '';
        $description = $weatherData['weather'][0]['description'] ?? '';
        $visibility = $weatherData['visibility'] ?? 10000; // meters
        $windSpeed = $weatherData['wind']['speed'] ?? 0; // m/s

        $risks = [];
        $safetyScore = 100;

        // Check for severe weather conditions
        if (in_array($conditions, ['Thunderstorm', 'Snow', 'Extreme'])) {
            $risks[] = 'Severe weather conditions';
            $safetyScore -= 50;
        }

        if (str_contains($description, 'heavy') || str_contains($description, 'extreme')) {
            $risks[] = 'Heavy/extreme weather';
            $safetyScore -= 30;
        }

        // Check visibility
        if ($visibility < 1000) {
            $risks[] = 'Poor visibility';
            $safetyScore -= 20;
        }

        // Check wind speed
        if ($windSpeed > 15) { // m/s (about 34 mph)
            $risks[] = 'High wind speed';
            $safetyScore -= 15;
        }

        return [
            'is_safe' => $safetyScore >= 70,
            'safety_score' => max(0, $safetyScore),
            'risks' => $risks,
            'recommendations' => $this->getWeatherRecommendations($risks),
        ];
    }

    /**
     * Get weather-based delivery recommendations
     */
    protected function getWeatherRecommendations(array $risks): array
    {
        $recommendations = [];

        if (in_array('Severe weather conditions', $risks)) {
            $recommendations[] = 'Consider delaying delivery until weather improves';
        }

        if (in_array('Poor visibility', $risks)) {
            $recommendations[] = 'Use extra caution and reduce speed';
        }

        if (in_array('High wind speed', $risks)) {
            $recommendations[] = 'Secure all cargo and avoid high-profile vehicles';
        }

        if (empty($risks)) {
            $recommendations[] = 'Weather conditions are optimal for delivery';
        }

        return $recommendations;
    }

    /**
     * Format weather data for frontend consumption
     */
    public function formatWeatherData(array $weatherData): array
    {
        $main = $weatherData['main'] ?? [];
        $weather = $weatherData['weather'][0] ?? [];
        $wind = $weatherData['wind'] ?? [];
        $clouds = $weatherData['clouds'] ?? [];

        return [
            'temperature' => $main['temp'] ?? 0,
            'feels_like' => $main['feels_like'] ?? 0,
            'humidity' => $main['humidity'] ?? 0,
            'pressure' => $main['pressure'] ?? 0,
            'condition' => $weather['main'] ?? '',
            'description' => $weather['description'] ?? '',
            'icon' => $weather['icon'] ?? '',
            'wind_speed' => $wind['speed'] ?? 0,
            'wind_direction' => $wind['deg'] ?? 0,
            'cloudiness' => $clouds['all'] ?? 0,
            'visibility' => $weatherData['visibility'] ?? 10000,
            'safety' => $this->isSafeForDelivery($weatherData),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Get weather alerts for a location
     */
    public function getWeatherAlerts(float $latitude, float $longitude): ?array
    {
        $endpoint = 'onecall';
        
        $params = [
            'lat' => $latitude,
            'lon' => $longitude,
            'appid' => $this->apiKey,
            'units' => $this->units,
            'exclude' => 'minutely,hourly,daily',
        ];

        $data = $this->makeRequest($endpoint, $params);
        
        return $data['alerts'] ?? [];
    }
}
