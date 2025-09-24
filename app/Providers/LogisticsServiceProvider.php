<?php

namespace App\Providers;

use App\Services\LogisticsService;
use App\Services\OpenRouteService;
use App\Services\OpenWeatherService;
use Illuminate\Support\ServiceProvider;

class LogisticsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OpenRouteService::class, function ($app) {
            return new OpenRouteService();
        });

        $this->app->singleton(OpenWeatherService::class, function ($app) {
            return new OpenWeatherService();
        });

        $this->app->singleton(LogisticsService::class, function ($app) {
            return new LogisticsService(
                $app->make(OpenRouteService::class),
                $app->make(OpenWeatherService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
