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

    // Master Data Routes
    Route::apiResource('products', MasterDataController::class);
    Route::get('/products/search/{term}', [MasterDataController::class, 'searchProducts']);
    Route::get('/products/category/{category}', [MasterDataController::class, 'getProductsByCategory']);
    Route::apiResource('employees', MasterDataController::class);
    Route::get('/employees/search/{term}', [MasterDataController::class, 'searchEmployees']);
    Route::get('/employees/department/{department}', [MasterDataController::class, 'getEmployeesByDepartment']);

    // Supplier & Procurement Routes
    Route::prefix('supplier-procurement')->group(function () {
        // Supplier Onboarding
        Route::apiResource('supplier-onboarding', SupplierProcurementController::class);
        Route::post('/supplier-onboarding/{id}/approve', [SupplierProcurementController::class, 'approveSupplier']);
        Route::post('/supplier-onboarding/{id}/reject', [SupplierProcurementController::class, 'rejectSupplier']);
        
        // Supplier Catalog
        Route::apiResource('supplier-catalogs', SupplierProcurementController::class);
        Route::get('/supplier-catalogs/supplier/{supplierId}', [SupplierProcurementController::class, 'getSupplierCatalogs']);
        Route::post('/supplier-catalogs/bulk-upload', [SupplierProcurementController::class, 'bulkUploadCatalogs']);
        
        // Supplier Contracts
        Route::apiResource('supplier-contracts', SupplierProcurementController::class);
        Route::get('/supplier-contracts/supplier/{supplierId}', [SupplierProcurementController::class, 'getSupplierContracts']);
        Route::post('/supplier-contracts/{id}/renew', [SupplierProcurementController::class, 'renewContract']);
        Route::post('/supplier-contracts/{id}/terminate', [SupplierProcurementController::class, 'terminateContract']);
        
        // Supplier Performance
        Route::apiResource('supplier-performance', SupplierProcurementController::class);
        Route::get('/supplier-performance/supplier/{supplierId}', [SupplierProcurementController::class, 'getSupplierPerformance']);
        Route::post('/supplier-performance/{id}/evaluate', [SupplierProcurementController::class, 'evaluateSupplier']);
        
        // Purchase Orders
        Route::apiResource('purchase-orders', SupplierProcurementController::class);
        Route::get('/purchase-orders/supplier/{supplierId}', [SupplierProcurementController::class, 'getSupplierPurchaseOrders']);
        Route::post('/purchase-orders/{id}/approve', [SupplierProcurementController::class, 'approvePurchaseOrder']);
        Route::post('/purchase-orders/{id}/cancel', [SupplierProcurementController::class, 'cancelPurchaseOrder']);
        Route::post('/purchase-orders/{id}/confirm', [SupplierProcurementController::class, 'confirmPurchaseOrder']);
        
        // Purchase Order Line Items
        Route::apiResource('purchase-order-line-items', SupplierProcurementController::class);
        Route::get('/purchase-order-line-items/purchase-order/{purchaseOrderId}', [SupplierProcurementController::class, 'getPurchaseOrderLineItems']);
    });

    // Returns Management Routes
    Route::prefix('returns')->group(function () {
        // Return Requests
        Route::apiResource('return-requests', ReturnsController::class);
        Route::post('/return-requests/{id}/approve', [ReturnsController::class, 'approveReturnRequest']);
        Route::post('/return-requests/{id}/reject', [ReturnsController::class, 'rejectReturnRequest']);
        
        // Return Authorizations
        Route::apiResource('return-authorizations', ReturnsController::class);
        Route::get('/return-authorizations/request/{requestId}', [ReturnsController::class, 'getReturnAuthorizations']);
        
        // Return Line Items
        Route::apiResource('return-line-items', ReturnsController::class);
        Route::get('/return-line-items/authorization/{authorizationId}', [ReturnsController::class, 'getReturnLineItems']);
        
        // Return Processing
        Route::apiResource('return-processing', ReturnsController::class);
        Route::post('/return-processing/{id}/complete', [ReturnsController::class, 'completeReturnProcessing']);
        
        // Return Reasons
        Route::apiResource('return-reasons', ReturnsController::class);
        
        // Return Dispositions
        Route::apiResource('return-dispositions', ReturnsController::class);
    });

    // Transportation & Delivery Routes
    Route::prefix('transportation')->group(function () {
        // Vehicles
        Route::apiResource('vehicles', TransportationDeliveryController::class);
        Route::get('/vehicles/company/{companyId}', [TransportationDeliveryController::class, 'getCompanyVehicles']);
        Route::post('/vehicles/{id}/assign-driver', [TransportationDeliveryController::class, 'assignDriver']);
        
        // Route Plans
        Route::apiResource('route-plans', TransportationDeliveryController::class);
        Route::get('/route-plans/vehicle/{vehicleId}', [TransportationDeliveryController::class, 'getVehicleRoutePlans']);
        Route::post('/route-plans/{id}/optimize', [TransportationDeliveryController::class, 'optimizeRoutePlan']);
        
        // Order Fulfillment
        Route::apiResource('order-fulfillments', TransportationDeliveryController::class);
        Route::get('/order-fulfillments/order/{orderId}', [TransportationDeliveryController::class, 'getOrderFulfillments']);
        
        // Delivery Tasks
        Route::apiResource('delivery-tasks', TransportationDeliveryController::class);
        Route::get('/delivery-tasks/route-plan/{routePlanId}', [TransportationDeliveryController::class, 'getRouteDeliveryTasks']);
        Route::post('/delivery-tasks/{id}/complete', [TransportationDeliveryController::class, 'completeDeliveryTask']);
        
        // Customer Notifications
        Route::apiResource('customer-notifications', TransportationDeliveryController::class);
        Route::post('/customer-notifications/send-eta', [TransportationDeliveryController::class, 'sendEtaNotification']);
        
        // Delivery Exceptions
        Route::apiResource('delivery-exceptions', TransportationDeliveryController::class);
        Route::post('/delivery-exceptions/{id}/resolve', [TransportationDeliveryController::class, 'resolveDeliveryException']);
        
        // Geofence Events
        Route::apiResource('geofence-events', TransportationDeliveryController::class);
        Route::post('/geofence-events/trigger', [TransportationDeliveryController::class, 'triggerGeofenceEvent']);
    });

    // Manufacturing Routes
    Route::prefix('manufacturing')->group(function () {
        // Work Orders
        Route::apiResource('work-orders', ManufacturingController::class);
        Route::post('/work-orders/{id}/release', [ManufacturingController::class, 'releaseWorkOrder']);
        Route::post('/work-orders/{id}/start', [ManufacturingController::class, 'startWorkOrder']);
        Route::post('/work-orders/{id}/complete', [ManufacturingController::class, 'completeWorkOrder']);
        Route::post('/work-orders/{id}/hold', [ManufacturingController::class, 'holdWorkOrder']);
        Route::post('/work-orders/{id}/cancel', [ManufacturingController::class, 'cancelWorkOrder']);
        
        // Machines
        Route::apiResource('machines', ManufacturingController::class);
        Route::get('/machines/location/{locationId}', [ManufacturingController::class, 'getLocationMachines']);
        Route::post('/machines/{id}/maintenance', [ManufacturingController::class, 'scheduleMaintenance']);
        
        // Quality Inspections
        Route::apiResource('quality-inspections', ManufacturingController::class);
        Route::get('/quality-inspections/work-order/{workOrderId}', [ManufacturingController::class, 'getWorkOrderInspections']);
        Route::post('/quality-inspections/{id}/approve', [ManufacturingController::class, 'approveQualityInspection']);
        Route::post('/quality-inspections/{id}/reject', [ManufacturingController::class, 'rejectQualityInspection']);
        
        // Batch Tracking
        Route::apiResource('batch-tracking', ManufacturingController::class);
        Route::get('/batch-tracking/work-order/{workOrderId}', [ManufacturingController::class, 'getWorkOrderBatches']);
        
        // Manufacturing Workflow
        Route::post('/workflow/generate-work-orders', [ManufacturingWorkflowController::class, 'generateFromPurchaseOrders']);
    });

    // Inventory & Warehouse Routes
    Route::prefix('inventory')->group(function () {
        // Inventory Balances
        Route::apiResource('inventory-balances', InventoryWarehouseController::class);
        Route::get('/inventory-balances/product/{productId}', [InventoryWarehouseController::class, 'getProductInventory']);
        Route::get('/inventory-balances/location/{locationId}', [InventoryWarehouseController::class, 'getLocationInventory']);
        
        // Warehouse Bins
        Route::apiResource('warehouse-bins', InventoryWarehouseController::class);
        Route::get('/warehouse-bins/location/{locationId}', [InventoryWarehouseController::class, 'getLocationBins']);
        
        // Inbound Receiving
        Route::apiResource('inbound-receiving', InventoryWarehouseController::class);
        Route::post('/inbound-receiving/{id}/complete', [InventoryWarehouseController::class, 'completeReceiving']);
        
        // Outbound Shipments
        Route::apiResource('outbound-shipments', InventoryWarehouseController::class);
        Route::post('/outbound-shipments/{id}/pick', [InventoryWarehouseController::class, 'pickOutboundShipment']);
        Route::post('/outbound-shipments/{id}/pack', [InventoryWarehouseController::class, 'packOutboundShipment']);
        Route::post('/outbound-shipments/{id}/ship', [InventoryWarehouseController::class, 'shipOutboundShipment']);
        
        // Cycle Counts
        Route::apiResource('cycle-counts', InventoryWarehouseController::class);
        Route::post('/cycle-counts/{id}/complete', [InventoryWarehouseController::class, 'completeCycleCount']);
        Route::post('/cycle-counts/{id}/reconcile', [InventoryWarehouseController::class, 'reconcileCycleCount']);
    });
});