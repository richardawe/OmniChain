<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\RateLimiting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\RouteServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimit::class,
            'input.sanitize' => \App\Http\Middleware\InputSanitization::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
        ]);
        
        // Apply input sanitization to all API routes
        $middleware->append(\App\Http\Middleware\InputSanitization::class);
        
        // Apply audit logging to all API routes
        $middleware->append(\App\Http\Middleware\AuditMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
