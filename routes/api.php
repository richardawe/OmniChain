<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FreightOrderController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ShipmentEventController;
use App\Http\Controllers\Api\LogisticsController;
use App\Http\Controllers\Api\DriverApiController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\SupplierProcurementController;
use App\Http\Controllers\Api\ReturnsController;
use App\Http\Controllers\Api\TransportationDeliveryController;
use App\Http\Controllers\Api\ManufacturingController;
use App\Http\Controllers\Api\InventoryWarehouseController;
use App\Http\Controllers\Api\ManufacturingWorkflowController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Version Prefix
Route::prefix('v1')->group(function () {
    
    // Health Check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env')
        ]);
    });

    // Authentication Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

    // Master Data Routes
    Route::prefix('master-data')->group(function () {
        Route::get('/summary', [MasterDataController::class, 'getSummary']);
        Route::get('/companies', [MasterDataController::class, 'getCompanies']);
        Route::get('/locations', [MasterDataController::class, 'getLocations']);
        Route::get('/products', [MasterDataController::class, 'getProducts']);
        Route::get('/employees', [MasterDataController::class, 'getEmployees']);
        Route::post('/companies', [MasterDataController::class, 'createCompany']);
        Route::post('/products', [MasterDataController::class, 'createProduct']);
    });

    // Freight Order Routes
    Route::apiResource('freight-orders', FreightOrderController::class);
    Route::get('/freight-orders/track/{orderNumber}', [FreightOrderController::class, 'trackShipment']);
    Route::post('/freight-orders/available-carriers', [FreightOrderController::class, 'findAvailableCarriers']);
    Route::post('/freight-orders/{id}/assign-carrier', [FreightOrderController::class, 'assignCarrier']);
    Route::post('/freight-orders/{id}/update-status', [FreightOrderController::class, 'updateStatus']);
    Route::get('/freight-orders/{id}/events', [FreightOrderController::class, 'getEvents']);
    Route::get('/freight-orders/stats/summary', [FreightOrderController::class, 'getStatsSummary']);
    Route::get('/freight-orders/stats/by-status', [FreightOrderController::class, 'getStatsByStatus']);
    Route::get('/freight-orders/stats/by-carrier', [FreightOrderController::class, 'getStatsByCarrier']);

    // Company Routes
    Route::apiResource('companies', CompanyController::class);
    Route::get('/companies/{id}/locations', [CompanyController::class, 'getLocations']);
    Route::get('/companies/{id}/freight-orders', [CompanyController::class, 'getFreightOrders']);
    Route::get('/companies/types/carriers', [CompanyController::class, 'getCarriers']);
    Route::get('/companies/types/shippers', [CompanyController::class, 'getShippers']);
    Route::get('/companies/search/{term}', [CompanyController::class, 'search']);
    
    // Location Routes
    Route::apiResource('locations', LocationController::class);
    Route::get('/locations/{id}/freight-orders', [LocationController::class, 'getFreightOrders']);
    Route::get('/locations/search/{term}', [LocationController::class, 'search']);
    Route::get('/locations/nearby', [LocationController::class, 'findNearby']);
    Route::get('/locations/types/{type}', [LocationController::class, 'getByType']);
    Route::get('/locations/company/{companyId}', [LocationController::class, 'getByCompany']);
    
    // Shipment Event Routes
    Route::apiResource('shipment-events', ShipmentEventController::class);
    Route::get('/shipment-events/order/{orderId}', [ShipmentEventController::class, 'getByOrder']);
    Route::post('/shipment-events/bulk', [ShipmentEventController::class, 'storeBulk']);
    Route::get('/shipment-events/types', [ShipmentEventController::class, 'getEventTypes']);
    Route::get('/shipment-events/recent', [ShipmentEventController::class, 'getRecentEvents']);
    Route::get('/shipment-events/delayed', [ShipmentEventController::class, 'getDelayedEvents']);

    // Logistics Routes
    Route::get('/logistics/dashboard/summary', [LogisticsController::class, 'getDashboardSummary']);
    Route::get('/logistics/dashboard/map-data', [LogisticsController::class, 'getMapData']);
    Route::get('/logistics/dashboard/active-shipments', [LogisticsController::class, 'getActiveShipments']);
    Route::get('/logistics/dashboard/upcoming-deliveries', [LogisticsController::class, 'getUpcomingDeliveries']);
    Route::get('/logistics/dashboard/recent-events', [LogisticsController::class, 'getRecentEvents']);
    Route::get('/logistics/dashboard/alerts', [LogisticsController::class, 'getAlerts']);
    Route::get('/logistics/dashboard/kpis', [LogisticsController::class, 'getKPIs']);
    Route::get('/logistics/dashboard/carrier-performance', [LogisticsController::class, 'getCarrierPerformance']);
    Route::get('/logistics/weather/{lat}/{lng}', [LogisticsController::class, 'getWeatherData']);
    Route::get('/logistics/route/{origin}/{destination}', [LogisticsController::class, 'getRouteData']);

    // Driver API Routes
    Route::post('/driver/login', [DriverApiController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/driver/assignments', [DriverApiController::class, 'getAssignments']);
        Route::get('/driver/assignment/{id}', [DriverApiController::class, 'getAssignmentDetails']);
        Route::post('/driver/update-location', [DriverApiController::class, 'updateLocation']);
        Route::post('/driver/update-status/{id}', [DriverApiController::class, 'updateStatus']);
        Route::post('/driver/complete-delivery/{id}', [DriverApiController::class, 'completeDelivery']);
        Route::post('/driver/report-issue/{id}', [DriverApiController::class, 'reportIssue']);
        Route::get('/driver/profile', [DriverApiController::class, 'getProfile']);
        Route::post('/driver/profile', [DriverApiController::class, 'updateProfile']);
    });
    
    // Driver Locations (public)
    Route::get('/driver-locations', [DriverApiController::class, 'getDriverLocations']);

    // Transportation Routes
    Route::prefix('transportation')->group(function () {
        Route::get('/summary', [TransportationDeliveryController::class, 'getSummary']);
        Route::get('/vehicles', [TransportationDeliveryController::class, 'getVehicles']);
        Route::get('/route-plans', [TransportationDeliveryController::class, 'getRoutePlans']);
        Route::get('/delivery-tasks', [TransportationDeliveryController::class, 'getDeliveryTasks']);
        Route::get('/vehicles/{id}', [TransportationDeliveryController::class, 'getVehicleDetails']);
    });
});