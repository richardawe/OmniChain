<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Root route for the main dashboard
Route::get('/', function () {
    return inertia('Dashboard');
});

// Test route to check if the application is working
Route::get('/test', function () {
    return 'Test route works!';
});

// Raw PHP output test
Route::get('/raw', function () {
    echo 'Raw PHP output';
    return '';
});

// Health check route
Route::get('/health', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => config('app.version', '1.0.0'),
        'environment' => config('app.env'),
        'database' => $dbStatus
    ]);
});

// Basic Test Routes
Route::get('/basic-test', function () {
    return inertia('BasicTest');
});

Route::get('/simple-test', function () {
    return inertia('SimpleTest', [
        'debug' => 'Debug message from controller',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Master Data Routes
Route::get('/master-data', function () {
    return inertia('MasterData');
});

// Transportation Management Routes
Route::get('/transportation-management', function () {
    return inertia('TransportationManagement');
});

// Supplier Procurement Routes
Route::get('/supplier-procurement', function () {
    return inertia('SupplierProcurement');
});

// Returns Management Routes
Route::get('/returns-management', function () {
    return inertia('ReturnsManagement');
});

// Weather Logistics Routes
Route::get('/weather-logistics', function () {
    return inertia('WeatherLogistics');
});

// Delivery Management Routes
Route::get('/delivery-management', function () {
    return inertia('DeliveryManagement');
});

// Shipment Tracking Routes
Route::get('/track-shipment', function () {
    return inertia('TrackShipment');
});

// Inventory Warehouse Management Routes
Route::get('/inventory-warehouse-management', function () {
    return inertia('InventoryWarehouseManagement');
});

// Manufacturing Management Routes
Route::get('/manufacturing-management', function () {
    return inertia('ManufacturingManagement');
});

// Module Relationships Routes
Route::get('/module-relationships', function () {
    return inertia('ModuleRelationships');
});

// Driver Management Routes
Route::get('/driver-management', function () {
    return inertia('DriverManagement');
});

// Fallback route for SPA
Route::fallback(function () {
    return inertia('Dashboard');
});