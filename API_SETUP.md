# External API Integration Setup

This document explains how to set up the external API integrations for OpenRouteService and OpenWeatherMap.

## Required API Keys

### 1. OpenRouteService API Key
- **Website**: https://openrouteservice.org/dev/#/signup
- **Free Tier**: 2,000 requests/day
- **Features**: Route optimization, directions, distance matrix, isochrones

**Setup Steps:**
1. Visit https://openrouteservice.org/dev/#/signup
2. Create a free account
3. Get your API key from the dashboard
4. Add to `.env` file: `OPENROUTE_API_KEY=your_api_key_here`

### 2. OpenWeatherMap API Key
- **Website**: https://openweathermap.org/api
- **Free Tier**: 1,000 calls/day
- **Features**: Current weather, 5-day forecast, weather alerts

**Setup Steps:**
1. Visit https://openweathermap.org/api
2. Sign up for a free account
3. Get your API key from the dashboard
4. Add to `.env` file: `OPENWEATHER_API_KEY=your_api_key_here`

## Environment Configuration

Add these lines to your `.env` file:

```env
# External API Keys
OPENROUTE_API_KEY=your_openroute_api_key_here
OPENWEATHER_API_KEY=your_openweather_api_key_here
```

## Available API Endpoints

### Logistics Endpoints

#### Get Freight Order Logistics
```
GET /api/v1/logistics/freight-orders/{id}
```
Returns comprehensive logistics data including route optimization and weather conditions.

#### Get Delivery Zones
```
GET /api/v1/logistics/delivery-zones/{id}
```
Returns delivery zones with weather impact assessment for a warehouse.

#### Check Delivery Delay
```
GET /api/v1/logistics/delivery-delay/{id}
```
Checks if a delivery should be delayed due to weather conditions.

#### Get Location Weather
```
GET /api/v1/logistics/weather/{id}
```
Returns current weather data for a specific location.

#### Optimize Route
```
POST /api/v1/logistics/optimize-route
```
Body:
```json
{
    "origin_latitude": 40.7128,
    "origin_longitude": -74.0060,
    "destination_latitude": 34.0522,
    "destination_longitude": -118.2437,
    "waypoints": [
        {"latitude": 39.9526, "longitude": -75.1652}
    ]
}
```

#### Get Multiple Locations Weather
```
POST /api/v1/logistics/multiple-weather
```
Body:
```json
{
    "location_ids": [1, 2, 3]
}
```

#### Get Weather-Aware Route
```
POST /api/v1/logistics/weather-aware-route
```
Body:
```json
{
    "origin_id": 1,
    "destination_id": 2,
    "waypoint_ids": [3, 4]
}
```

## Features Implemented

### Route Optimization
- Multi-stop route optimization
- Truck-specific routing (avoiding low bridges, weight restrictions)
- Distance and time calculations
- Route alternatives

### Weather Integration
- Current weather conditions
- 5-day weather forecasts
- Weather-based safety assessments
- Delivery delay recommendations

### Logistics Intelligence
- Combined route and weather analysis
- Risk assessment scoring
- Cost estimation with weather factors
- Delivery zone analysis

## Testing the Integration

1. **Set up API keys** in your `.env` file
2. **Run the application**: `php artisan serve`
3. **Test endpoints** using the API routes above
4. **Check logs** for any API errors in `storage/logs/laravel.log`

## Caching

The services implement intelligent caching:
- **Route data**: Cached for 1 hour
- **Weather data**: Cached for 30 minutes
- **Cache keys**: Generated based on request parameters

## Error Handling

- **API failures**: Graceful fallback with logging
- **Rate limiting**: Automatic retry with exponential backoff
- **Invalid responses**: Validation and error reporting
- **Network issues**: Timeout handling and retry logic

## Free Tier Optimization

To maximize free tier usage:
1. **Cache responses** - Implemented automatically
2. **Batch requests** - Use multiple locations endpoint
3. **Smart scheduling** - Only fetch when needed
4. **Error handling** - Avoid unnecessary retries

## Next Steps

1. **Get API keys** from both services
2. **Update .env file** with your keys
3. **Test the endpoints** using Postman or curl
4. **Integrate with frontend** for enhanced user experience
5. **Monitor usage** to stay within free tier limits
