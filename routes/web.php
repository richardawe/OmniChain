<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverAppController;
use App\Http\Controllers\Admin\DriverManagementController;

// Include simple routes
require_once __DIR__ . '/simple.php';

// Simple health check route for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'message' => 'OmniChain is running'
    ]);
});

// Test routes for debugging
Route::get('/test', function () {
    return 'OmniChain is working!';
});

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'time' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

Route::get('/info', function () {
    return response()->json([
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => app()->environment(),
        'debug' => config('app.debug'),
        'timezone' => config('app.timezone'),
        'extensions' => get_loaded_extensions()
    ]);
});

// Main application route - serve the Laravel application
Route::get('/', function () {
    try {
        return inertia('Dashboard');
    } catch (Exception $e) {
        // Fallback to simple HTML if Inertia fails
        return response()->view('simple-dashboard', [
            'error' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
});

// Healthcheck route for Railway
Route::get('/health', function () {
    return response()->json([
        'message' => 'OmniChain API is running',
        'version' => '1.0.0',
        'timestamp' => date('Y-m-d H:i:s'),
        'status' => 'healthy',
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

// Ultra-simple route for debugging
Route::get('/ping', function () {
    return 'pong';
});

// Debug route to check environment
Route::get('/debug', function () {
    return response()->json([
        'app_name' => config('app.name'),
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'vite_app_name' => env('VITE_APP_NAME'),
        'has_openroute_key' => !empty(env('OPENROUTE_API_KEY')),
        'has_openweather_key' => !empty(env('OPENWEATHER_API_KEY')),
        'build_exists' => file_exists(public_path('build/manifest.json')),
        'database_url' => !empty(env('DATABASE_URL')),
        'redis_url' => !empty(env('REDIS_URL')),
        'railway_private_domain' => env('RAILWAY_PRIVATE_DOMAIN'),
        'postgres_db' => env('POSTGRES_DB'),
        'postgres_user' => env('POSTGRES_USER'),
        'redishost' => env('REDISHOST'),
        'redisport' => env('REDISPORT'),
        'timestamp' => now()
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/track-shipment', function () {
    return inertia('TrackShipment');
})->name('track-shipment');
Route::get('/master-data', function () {
    return inertia('MasterData');
})->name('master-data');

Route::get('/supplier-procurement', function () {
    return inertia('SupplierProcurement');
})->name('supplier-procurement');

Route::get('/transportation-management', function () {
    return inertia('TransportationManagement');
})->name('transportation-management');

Route::get('/delivery-management', function () {
    return inertia('DeliveryManagement');
})->name('delivery-management');

Route::get('/manufacturing-management', function () {
    return inertia('ManufacturingManagement');
})->name('manufacturing-management');

Route::get('/inventory-warehouse-management', function () {
    return inertia('InventoryWarehouseManagement');
})->name('inventory-warehouse-management');

Route::get('/returns-management', function () {
    return inertia('ReturnsManagement');
})->name('returns-management');

Route::get('/module-relationships', function () {
    return inertia('ModuleRelationships');
})->name('module-relationships');

// Driver App Routes
Route::prefix('driver')->group(function () {
    Route::get('/', [DriverAppController::class, 'index'])->name('driver.app');
    Route::get('/login', [DriverAppController::class, 'login'])->name('driver.login');
    Route::get('/register', [DriverAppController::class, 'register'])->name('driver.register');
    Route::get('/pending-approval', [DriverAppController::class, 'pendingApproval'])->name('driver.pending-approval');
    Route::get('/offline', [DriverAppController::class, 'offline'])->name('driver.offline');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/drivers', [DriverManagementController::class, 'index'])->name('admin.drivers');
});
