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

// Master Data Routes
Route::get('/master-data', function () {
    return inertia('MasterData/Index');
});

Route::get('/master-data/products', function () {
    return inertia('MasterData/Products');
});

Route::get('/master-data/companies', function () {
    return inertia('MasterData/Companies');
});

Route::get('/master-data/locations', function () {
    return inertia('MasterData/Locations');
});

Route::get('/master-data/employees', function () {
    return inertia('MasterData/Employees');
});

// Freight Order Management Routes
Route::get('/freight-orders', function () {
    return inertia('FreightOrders/Index');
});

Route::get('/freight-orders/create', function () {
    return inertia('FreightOrders/Create');
});

Route::get('/freight-orders/{id}', function ($id) {
    return inertia('FreightOrders/Show', [
        'orderId' => $id
    ]);
});

// Shipment Tracking Routes
Route::get('/track-shipment', function () {
    return inertia('TrackShipment');
});

Route::get('/track-shipment/{orderNumber}', function ($orderNumber) {
    return inertia('TrackShipment', [
        'orderNumber' => $orderNumber
    ]);
});

// Control Tower Routes
Route::get('/control-tower', function () {
    return inertia('ControlTower');
});

Route::get('/control-tower/map', function () {
    return inertia('ControlTower/Map');
});

Route::get('/control-tower/analytics', function () {
    return inertia('ControlTower/Analytics');
});

// Company Management Routes
Route::get('/companies', function () {
    return inertia('Companies/Index');
});

Route::get('/companies/create', function () {
    return inertia('Companies/Create');
});

Route::get('/companies/{id}', function ($id) {
    return inertia('Companies/Show', [
        'companyId' => $id
    ]);
});

// Location Management Routes
Route::get('/locations', function () {
    return inertia('Locations/Index');
});

Route::get('/locations/create', function () {
    return inertia('Locations/Create');
});

Route::get('/locations/{id}', function ($id) {
    return inertia('Locations/Show', [
        'locationId' => $id
    ]);
});

// Driver App Routes
Route::get('/driver/login', function () {
    return inertia('Driver/Login');
});

Route::get('/driver/dashboard', function () {
    return inertia('Driver/Dashboard');
});

Route::get('/driver/assignments', function () {
    return inertia('Driver/Assignments');
});

Route::get('/driver/assignment/{id}', function ($id) {
    return inertia('Driver/Assignment', [
        'assignmentId' => $id
    ]);
});

// Supplier & Procurement Routes
Route::get('/procurement/dashboard', function () {
    return inertia('Procurement/Dashboard');
});

Route::get('/procurement/suppliers', function () {
    return inertia('Procurement/Suppliers');
});

Route::get('/procurement/purchase-orders', function () {
    return inertia('Procurement/PurchaseOrders');
});

// Manufacturing Routes
Route::get('/manufacturing/dashboard', function () {
    return inertia('Manufacturing/Dashboard');
});

Route::get('/manufacturing/work-orders', function () {
    return inertia('Manufacturing/WorkOrders');
});

// Inventory & Warehouse Routes
Route::get('/inventory/dashboard', function () {
    return inertia('Inventory/Dashboard');
});

Route::get('/inventory/stock-levels', function () {
    return inertia('Inventory/StockLevels');
});

// Returns Management Routes
Route::get('/returns/dashboard', function () {
    return inertia('Returns/Dashboard');
});

Route::get('/returns/requests', function () {
    return inertia('Returns/Requests');
});

// Settings Routes
Route::get('/settings', function () {
    return inertia('Settings');
});

Route::get('/settings/users', function () {
    return inertia('Settings/Users');
});

Route::get('/settings/api-keys', function () {
    return inertia('Settings/ApiKeys');
});

// Profile Routes
Route::get('/profile', function () {
    return inertia('Profile');
});

// Help & Documentation Routes
Route::get('/help', function () {
    return inertia('Help');
});

Route::get('/help/api-docs', function () {
    return inertia('Help/ApiDocs');
});

// Error Pages
Route::get('/error/404', function () {
    return inertia('Error/404');
});

Route::get('/error/500', function () {
    return inertia('Error/500');
});

Route::get('/error/maintenance', function () {
    return inertia('Error/Maintenance');
});

// Fallback route for SPA
Route::fallback(function () {
    return inertia('Error/404');
});