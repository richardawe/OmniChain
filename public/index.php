<?php

// Fix for output issues - ensure no control characters are output
ob_start(function($buffer) {
    // Remove the SOH character (ASCII 0x01) if it's the very first character
    if (strlen($buffer) > 0 && ord($buffer[0]) === 1) {
        error_log('SOH character detected and removed by index.php callback.');
        return substr($buffer, 1);
    }
    return $buffer;
});

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

// For debugging
if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

try {
    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    )->send();

    $kernel->terminate($request, $response);
} catch (Exception $e) {
    // Log the error
    error_log('Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    error_log($e->getTraceAsString());
    
    // Display error in production with limited information
    http_response_code(500);
    if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true') {
        echo '<h1>Application Error</h1>';
        echo '<p>Message: ' . $e->getMessage() . '</p>';
        echo '<p>File: ' . $e->getFile() . ' on line ' . $e->getLine() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        echo '<h1>Internal Server Error</h1>';
        echo '<p>An unexpected error occurred. Please try again later.</p>';
    }
}

// Only flush if not being included by soh_fix.php
if (!defined('SKIP_OB_END_FLUSH')) {
    // Clean output buffer
    ob_end_flush();
}