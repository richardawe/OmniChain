<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FreightOrderController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ShipmentEventController;
use App\Http\Controllers\Api\LogisticsController;
use App\Http\Controllers\Api\LogisticsTestController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Admin\DriverManagementController;
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

// API Routes for OmniChain
Route::prefix('v1')->middleware(['throttle:api'])->group(function () {
    
    // Authentication routes with strict rate limiting
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'throttle:10,1']);
    Route::get('auth/me', [AuthController::class, 'me'])->middleware(['auth:sanctum', 'throttle:30,1']);
    
    // Broadcasting authentication
    Route::post('broadcasting/auth', [\App\Http\Controllers\Api\BroadcastingController::class, 'auth'])->middleware(['auth:sanctum', 'throttle:60,1']);
    
    // Audit API Routes
    Route::prefix('audit')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\AuditController::class, 'index'])->middleware('throttle:60,1');
        Route::get('/statistics', [\App\Http\Controllers\Api\AuditController::class, 'getStatistics'])->middleware('throttle:30,1');
        Route::get('/actions', [\App\Http\Controllers\Api\AuditController::class, 'getActions'])->middleware('throttle:30,1');
        Route::get('/tables', [\App\Http\Controllers\Api\AuditController::class, 'getTables'])->middleware('throttle:30,1');
        Route::post('/export', [\App\Http\Controllers\Api\AuditController::class, 'export'])->middleware('throttle:5,1');
        Route::get('/model', [\App\Http\Controllers\Api\AuditController::class, 'getModelAuditLogs'])->middleware('throttle:60,1');
        Route::get('/user/{userId}', [\App\Http\Controllers\Api\AuditController::class, 'getUserAuditLogs'])->middleware('throttle:60,1');
        Route::get('/{id}', [\App\Http\Controllers\Api\AuditController::class, 'show'])->middleware('throttle:100,1');
    });
    
    // Freight Orders with rate limiting
    Route::apiResource('freight-orders', FreightOrderController::class)->middleware('throttle:60,1');
    Route::get('freight-orders/track/{orderNumber}', [FreightOrderController::class, 'track'])->middleware('throttle:100,1');
    Route::post('freight-orders/available-carriers', [FreightOrderController::class, 'getAvailableCarriers'])->middleware('throttle:30,1');
    
    // Companies
    Route::apiResource('companies', CompanyController::class);
    
    // Locations
    Route::get('locations/nearby', [LocationController::class, 'nearby']);
    Route::apiResource('locations', LocationController::class);
    
    // Shipment Events
    Route::apiResource('shipment-events', ShipmentEventController::class);
    
    // Logistics & External APIs with strict rate limiting
    Route::prefix('logistics')->middleware('throttle:20,1')->group(function () {
        Route::get('freight-orders/{id}', [LogisticsController::class, 'getFreightOrderLogistics']);
        Route::get('delivery-zones/{id}', [LogisticsController::class, 'getDeliveryZones']);
        Route::get('delivery-delay/{id}', [LogisticsController::class, 'checkDeliveryDelay']);
        Route::get('weather/{id}', [LogisticsController::class, 'getLocationWeather']);
        Route::post('optimize-route', [LogisticsController::class, 'getOptimizedRoute'])->middleware('throttle:10,1'); // More restrictive for expensive operations
        Route::post('multiple-weather', [LogisticsController::class, 'getMultipleLocationsWeather'])->middleware('throttle:10,1');
        Route::post('weather-aware-route', [LogisticsController::class, 'getWeatherAwareRoute'])->middleware('throttle:10,1');
    });
    
    // Test endpoint for logistics services
    Route::get('test-logistics', [LogisticsTestController::class, 'testServices']);
    
    // Driver API endpoints
    Route::prefix('driver')->group(function () {
        // Authentication endpoints (no auth required)
        Route::post('login', [DriverController::class, 'login']);
        Route::post('register', [DriverController::class, 'register']);
        Route::post('logout', [DriverController::class, 'logout']);
        
        // Driver endpoints (temporarily without auth for testing)
        Route::get('profile', [DriverController::class, 'getProfile']);
        Route::put('profile', [DriverController::class, 'updateProfile']);
        Route::get('tasks', [DriverController::class, 'getTasks']);
        Route::get('tasks/{id}', [DriverController::class, 'getTaskDetails']);
        Route::post('orders/{id}/claim', [DriverController::class, 'claimOrder']);
        Route::put('tasks/{id}/status', [DriverController::class, 'updateTaskStatus']);
        Route::post('tasks/{id}/proof-of-delivery', [DriverController::class, 'submitProofOfDelivery']);
        Route::post('location', [DriverController::class, 'updateLocation']);
    });
    
    // Public driver locations endpoint (for dashboard)
    Route::get('driver-locations', [DriverController::class, 'getActiveDriverLocations']);
    
    // Master Data API Routes
    Route::prefix('master-data')->group(function () {
        Route::get('companies', [\App\Http\Controllers\Api\MasterDataController::class, 'getCompanies']);
        Route::get('locations', [\App\Http\Controllers\Api\MasterDataController::class, 'getLocations']);
        Route::get('products', [\App\Http\Controllers\Api\MasterDataController::class, 'getProducts']);
        Route::get('employees', [\App\Http\Controllers\Api\MasterDataController::class, 'getEmployees']);
        Route::get('summary', [\App\Http\Controllers\Api\MasterDataController::class, 'getSummary']);
    });
    
    // Master Data Create Routes
    Route::post('companies', [\App\Http\Controllers\Api\MasterDataController::class, 'createCompany']);
    Route::post('products', [\App\Http\Controllers\Api\MasterDataController::class, 'createProduct']);
    
    // Supplier & Procurement API Routes
    Route::prefix('supplier-procurement')->group(function () {
        Route::get('onboarding', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getSupplierOnboarding']);
        Route::get('catalogs', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getSupplierCatalogs']);
        Route::get('contracts', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getSupplierContracts']);
        Route::get('performance', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getSupplierPerformance']);
        Route::get('purchase-orders', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getPurchaseOrders']);
        Route::get('summary', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'getSummary']);
        
        // CRUD Operations for Purchase Orders
        Route::post('purchase-orders', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'createPurchaseOrder']);
        Route::get('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showPurchaseOrder']);
        Route::put('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updatePurchaseOrder']);
        Route::delete('purchase-orders/{purchaseOrder}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroyPurchaseOrder']);
        
        // CRUD Operations for Purchase Order Line Items
        Route::post('purchase-order-line-items', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'storePurchaseOrderLineItem']);
        Route::get('purchase-order-line-items/{purchaseOrderLineItem}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showPurchaseOrderLineItem']);
        Route::put('purchase-order-line-items/{purchaseOrderLineItem}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updatePurchaseOrderLineItem']);
        Route::delete('purchase-order-line-items/{purchaseOrderLineItem}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroyPurchaseOrderLineItem']);
        
        // CRUD Operations for Supplier Onboarding
        Route::post('onboarding', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'storeSupplierOnboarding']);
        Route::get('onboarding/{supplierOnboarding}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showSupplierOnboarding']);
        Route::put('onboarding/{supplierOnboarding}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updateSupplierOnboarding']);
        Route::delete('onboarding/{supplierOnboarding}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroySupplierOnboarding']);
        
        // CRUD Operations for Supplier Catalogs
        Route::post('catalogs', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'storeSupplierCatalog']);
        Route::get('catalogs/{supplierCatalog}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showSupplierCatalog']);
        Route::put('catalogs/{supplierCatalog}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updateSupplierCatalog']);
        Route::delete('catalogs/{supplierCatalog}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroySupplierCatalog']);
        
        // CRUD Operations for Supplier Contracts
        Route::post('contracts', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'createContract']);
        Route::get('contracts/{supplierContract}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showSupplierContract']);
        Route::put('contracts/{supplierContract}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updateSupplierContract']);
        Route::delete('contracts/{supplierContract}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroySupplierContract']);
        
        // CRUD Operations for Supplier Performance
        Route::post('performance', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'storeSupplierPerformance']);
        Route::get('performance/{supplierPerformance}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'showSupplierPerformance']);
        Route::put('performance/{supplierPerformance}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'updateSupplierPerformance']);
        Route::delete('performance/{supplierPerformance}', [\App\Http\Controllers\Api\SupplierProcurementController::class, 'destroySupplierPerformance']);
    });
    
    // City Search Routes
    Route::get('cities/search', [\App\Http\Controllers\Api\CitySearchController::class, 'search']);
    Route::get('cities/details', [\App\Http\Controllers\Api\CitySearchController::class, 'details']);
    
// Returns & Reverse Logistics API Routes
Route::prefix('returns')->group(function () {
    Route::get('summary', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnsSummary']);
    Route::get('return-requests', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnRequests']);
    Route::get('return-authorizations', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnAuthorizations']);
    Route::get('return-reasons', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnReasons']);
    Route::get('return-dispositions', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnDispositions']);
    Route::get('return-processing', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnProcessing']);
    Route::get('return-line-items', [\App\Http\Controllers\Api\ReturnsController::class, 'getReturnLineItems']);
    
    // CRUD Operations for Return Requests
    Route::post('return-requests', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnRequest']);
    Route::get('return-requests/{returnRequest}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnRequest']);
    Route::put('return-requests/{returnRequest}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnRequest']);
    Route::delete('return-requests/{returnRequest}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnRequest']);
    
    // CRUD Operations for Return Authorizations
    Route::post('return-authorizations', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnAuthorization']);
    Route::get('return-authorizations/{returnAuthorization}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnAuthorization']);
    Route::put('return-authorizations/{returnAuthorization}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnAuthorization']);
    Route::delete('return-authorizations/{returnAuthorization}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnAuthorization']);
    
    // CRUD Operations for Return Line Items
    Route::post('return-line-items', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnLineItem']);
    Route::get('return-line-items/{returnLineItem}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnLineItem']);
    Route::put('return-line-items/{returnLineItem}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnLineItem']);
    Route::delete('return-line-items/{returnLineItem}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnLineItem']);
    
    // CRUD Operations for Return Processing
    Route::post('return-processing', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnProcessing']);
    Route::get('return-processing/{returnProcessing}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnProcessing']);
    Route::put('return-processing/{returnProcessing}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnProcessing']);
    Route::delete('return-processing/{returnProcessing}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnProcessing']);
    
    // CRUD Operations for Return Reasons
    Route::post('return-reasons', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnReason']);
    Route::get('return-reasons/{returnReason}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnReason']);
    Route::put('return-reasons/{returnReason}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnReason']);
    Route::delete('return-reasons/{returnReason}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnReason']);
    
    // CRUD Operations for Return Dispositions
    Route::post('return-dispositions', [\App\Http\Controllers\Api\ReturnsController::class, 'storeReturnDisposition']);
    Route::get('return-dispositions/{returnDisposition}', [\App\Http\Controllers\Api\ReturnsController::class, 'showReturnDisposition']);
    Route::put('return-dispositions/{returnDisposition}', [\App\Http\Controllers\Api\ReturnsController::class, 'updateReturnDisposition']);
    Route::delete('return-dispositions/{returnDisposition}', [\App\Http\Controllers\Api\ReturnsController::class, 'destroyReturnDisposition']);
});

// Transportation API Routes
Route::prefix('transportation')->group(function () {
    // Vehicle CRUD operations
    Route::get('vehicles', [\App\Http\Controllers\Api\TransportationController::class, 'getVehicles']);
    Route::get('vehicles/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'getVehicle']);
    Route::post('vehicles', [\App\Http\Controllers\Api\TransportationController::class, 'createVehicle']);
    Route::put('vehicles/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'updateVehicle']);
    Route::delete('vehicles/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'deleteVehicle']);
    
    // Other transportation endpoints
    Route::get('route-plans', [\App\Http\Controllers\Api\TransportationController::class, 'getRoutePlans']);
    Route::get('route-plans/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'getRoutePlan']);
    Route::post('route-plans', [\App\Http\Controllers\Api\TransportationController::class, 'createRoutePlan']);
    Route::put('route-plans/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'updateRoutePlan']);
    Route::delete('route-plans/{id}', [\App\Http\Controllers\Api\TransportationController::class, 'deleteRoutePlan']);
    Route::get('vehicle-telematics', [\App\Http\Controllers\Api\TransportationController::class, 'getVehicleTelematics']);
    Route::get('proof-of-deliveries', [\App\Http\Controllers\Api\TransportationController::class, 'getProofOfDeliveries']);
    Route::get('customs-documentations', [\App\Http\Controllers\Api\TransportationController::class, 'getCustomsDocumentations']);
    Route::get('container-tracking', [\App\Http\Controllers\Api\TransportationController::class, 'getContainerTracking']);
    Route::get('terminal-events', [\App\Http\Controllers\Api\TransportationController::class, 'getTerminalEvents']);
    Route::get('summary', [\App\Http\Controllers\Api\TransportationController::class, 'getTransportationSummary']);
});
    
    // Delivery API Routes
    Route::prefix('delivery')->group(function () {
        Route::get('order-fulfillments', [\App\Http\Controllers\Api\DeliveryController::class, 'getOrderFulfillments']);
        Route::get('order-fulfillments/{id}', [\App\Http\Controllers\Api\DeliveryController::class, 'getOrderFulfillment']);
        Route::get('delivery-tasks', [\App\Http\Controllers\Api\DeliveryController::class, 'getDeliveryTasks']);
        Route::get('delivery-tasks/{id}', [\App\Http\Controllers\Api\DeliveryController::class, 'showDeliveryTask']);
        Route::put('delivery-tasks/{id}/status', [\App\Http\Controllers\Api\DeliveryController::class, 'updateDeliveryTaskStatus']);
        Route::get('customer-notifications', [\App\Http\Controllers\Api\DeliveryController::class, 'getCustomerNotifications']);
        Route::get('summary', [\App\Http\Controllers\Api\DeliveryController::class, 'getDeliverySummary']);
        
        // CRUD Operations for Order Fulfillments
        Route::post('order-fulfillments', [\App\Http\Controllers\Api\DeliveryController::class, 'storeOrderFulfillment']);
        Route::get('order-fulfillments/{orderFulfillment}', [\App\Http\Controllers\Api\DeliveryController::class, 'showOrderFulfillment']);
        Route::put('order-fulfillments/{orderFulfillment}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateOrderFulfillment']);
        Route::delete('order-fulfillments/{orderFulfillment}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyOrderFulfillment']);
        
        // CRUD Operations for Order Fulfillment Line Items
        Route::post('order-fulfillment-line-items', [\App\Http\Controllers\Api\DeliveryController::class, 'storeOrderFulfillmentLineItem']);
        Route::get('order-fulfillment-line-items/{orderFulfillmentLineItem}', [\App\Http\Controllers\Api\DeliveryController::class, 'showOrderFulfillmentLineItem']);
        Route::put('order-fulfillment-line-items/{orderFulfillmentLineItem}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateOrderFulfillmentLineItem']);
        Route::delete('order-fulfillment-line-items/{orderFulfillmentLineItem}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyOrderFulfillmentLineItem']);
        
        // CRUD Operations for Delivery Tasks
        Route::post('delivery-tasks', [\App\Http\Controllers\Api\DeliveryController::class, 'storeDeliveryTask']);
        Route::get('delivery-tasks/{deliveryTask}', [\App\Http\Controllers\Api\DeliveryController::class, 'showDeliveryTask']);
        Route::put('delivery-tasks/{deliveryTask}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateDeliveryTask']);
        Route::delete('delivery-tasks/{deliveryTask}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyDeliveryTask']);
        
        // CRUD Operations for Customer Notifications
        Route::post('customer-notifications', [\App\Http\Controllers\Api\DeliveryController::class, 'storeCustomerNotification']);
        Route::get('customer-notifications/{customerNotification}', [\App\Http\Controllers\Api\DeliveryController::class, 'showCustomerNotification']);
        Route::put('customer-notifications/{customerNotification}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateCustomerNotification']);
        Route::delete('customer-notifications/{customerNotification}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyCustomerNotification']);
        
        // CRUD Operations for Delivery Exceptions
        Route::post('delivery-exceptions', [\App\Http\Controllers\Api\DeliveryController::class, 'storeDeliveryException']);
        Route::get('delivery-exceptions/{deliveryException}', [\App\Http\Controllers\Api\DeliveryController::class, 'showDeliveryException']);
        Route::put('delivery-exceptions/{deliveryException}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateDeliveryException']);
        Route::delete('delivery-exceptions/{deliveryException}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyDeliveryException']);
        
        // CRUD Operations for Geofence Events
        Route::post('geofence-events', [\App\Http\Controllers\Api\DeliveryController::class, 'storeGeofenceEvent']);
        Route::get('geofence-events/{geofenceEvent}', [\App\Http\Controllers\Api\DeliveryController::class, 'showGeofenceEvent']);
        Route::put('geofence-events/{geofenceEvent}', [\App\Http\Controllers\Api\DeliveryController::class, 'updateGeofenceEvent']);
        Route::delete('geofence-events/{geofenceEvent}', [\App\Http\Controllers\Api\DeliveryController::class, 'destroyGeofenceEvent']);
    });
    
    // Manufacturing API Routes
    Route::prefix('manufacturing')->group(function () {
        Route::get('summary', [\App\Http\Controllers\Api\ManufacturingController::class, 'getManufacturingSummary']);
        Route::get('work-orders', [\App\Http\Controllers\Api\ManufacturingController::class, 'getWorkOrders']);
        Route::get('machines', [\App\Http\Controllers\Api\ManufacturingController::class, 'getMachines']);
        Route::get('machine-telemetry', [\App\Http\Controllers\Api\ManufacturingController::class, 'getMachineTelemetry']);
        Route::get('quality-inspections', [\App\Http\Controllers\Api\ManufacturingController::class, 'getQualityInspections']);
        Route::get('batch-tracking', [\App\Http\Controllers\Api\ManufacturingController::class, 'getBatchTracking']);
        Route::get('boms', [\App\Http\Controllers\Api\ManufacturingController::class, 'getBOMs']);
        Route::get('production-routes', [\App\Http\Controllers\Api\ManufacturingController::class, 'getProductionRoutes']);
        Route::get('material-movements', [\App\Http\Controllers\Api\ManufacturingController::class, 'getMaterialMovements']);
        
        // CRUD Operations for Work Orders
        Route::post('work-orders', [\App\Http\Controllers\Api\ManufacturingController::class, 'storeWorkOrder']);
        Route::get('work-orders/{workOrder}', [\App\Http\Controllers\Api\ManufacturingController::class, 'showWorkOrder']);
        Route::put('work-orders/{workOrder}', [\App\Http\Controllers\Api\ManufacturingController::class, 'updateWorkOrder']);
        Route::delete('work-orders/{workOrder}', [\App\Http\Controllers\Api\ManufacturingController::class, 'destroyWorkOrder']);
        
        // CRUD Operations for Machines
        Route::post('machines', [\App\Http\Controllers\Api\ManufacturingController::class, 'storeMachine']);
        Route::get('machines/{machine}', [\App\Http\Controllers\Api\ManufacturingController::class, 'showMachine']);
        Route::put('machines/{machine}', [\App\Http\Controllers\Api\ManufacturingController::class, 'updateMachine']);
        Route::delete('machines/{machine}', [\App\Http\Controllers\Api\ManufacturingController::class, 'destroyMachine']);
        
        // CRUD Operations for Quality Inspections
        Route::post('quality-inspections', [\App\Http\Controllers\Api\ManufacturingController::class, 'storeQualityInspection']);
        Route::get('quality-inspections/{qualityInspection}', [\App\Http\Controllers\Api\ManufacturingController::class, 'showQualityInspection']);
        Route::put('quality-inspections/{qualityInspection}', [\App\Http\Controllers\Api\ManufacturingController::class, 'updateQualityInspection']);
        Route::delete('quality-inspections/{qualityInspection}', [\App\Http\Controllers\Api\ManufacturingController::class, 'destroyQualityInspection']);
        
        // CRUD Operations for Batch Tracking
        Route::post('batch-tracking', [\App\Http\Controllers\Api\ManufacturingController::class, 'storeBatchTracking']);
        Route::get('batch-tracking/{batchTracking}', [\App\Http\Controllers\Api\ManufacturingController::class, 'showBatchTracking']);
        Route::put('batch-tracking/{batchTracking}', [\App\Http\Controllers\Api\ManufacturingController::class, 'updateBatchTracking']);
        Route::delete('batch-tracking/{batchTracking}', [\App\Http\Controllers\Api\ManufacturingController::class, 'destroyBatchTracking']);
        
        // Manufacturing Workflow API Routes
        Route::post('generate-work-orders/from-purchase-orders', [\App\Http\Controllers\Api\ManufacturingWorkflowController::class, 'generateFromPurchaseOrders']);
        Route::post('generate-work-orders/for-products', [\App\Http\Controllers\Api\ManufacturingWorkflowController::class, 'generateForProducts']);
        Route::get('workflow-status', [\App\Http\Controllers\Api\ManufacturingWorkflowController::class, 'getWorkflowStatus']);
    });
    
    // Inventory & Warehouse API Routes
    Route::prefix('inventory-warehouse')->group(function () {
        Route::get('summary', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getInventoryWarehouseSummary']);
        Route::get('inventory-balances', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getInventoryBalances']);
        Route::get('warehouse-bins', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getWarehouseBins']);
        Route::get('inbound-receiving', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getInboundReceiving']);
        Route::get('outbound-shipments', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getOutboundShipments']);
        Route::get('cycle-counts', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getCycleCounts']);
        Route::get('putaway-records', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getPutawayRecords']);
        Route::get('cross-dock-events', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'getCrossDockEvents']);
        
        // CRUD Operations for Inventory Balances
        Route::post('inventory-balances', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'storeInventoryBalance']);
        Route::get('inventory-balances/{inventoryBalance}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'showInventoryBalance']);
        Route::put('inventory-balances/{inventoryBalance}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'updateInventoryBalance']);
        Route::delete('inventory-balances/{inventoryBalance}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'destroyInventoryBalance']);
        
        // CRUD Operations for Warehouse Bins
        Route::post('warehouse-bins', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'storeWarehouseBin']);
        Route::get('warehouse-bins/{warehouseBin}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'showWarehouseBin']);
        Route::put('warehouse-bins/{warehouseBin}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'updateWarehouseBin']);
        Route::delete('warehouse-bins/{warehouseBin}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'destroyWarehouseBin']);
        
        // CRUD Operations for Inbound Receiving
        Route::post('inbound-receiving', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'storeInboundReceiving']);
        Route::get('inbound-receiving/{inboundReceiving}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'showInboundReceiving']);
        Route::put('inbound-receiving/{inboundReceiving}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'updateInboundReceiving']);
        Route::delete('inbound-receiving/{inboundReceiving}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'destroyInboundReceiving']);
        
        // CRUD Operations for Outbound Shipments
        Route::post('outbound-shipments', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'storeOutboundShipment']);
        Route::get('outbound-shipments/{outboundShipment}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'showOutboundShipment']);
        Route::put('outbound-shipments/{outboundShipment}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'updateOutboundShipment']);
        Route::delete('outbound-shipments/{outboundShipment}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'destroyOutboundShipment']);
        
        // CRUD Operations for Cycle Counts
        Route::post('cycle-counts', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'storeCycleCount']);
        Route::get('cycle-counts/{cycleCount}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'showCycleCount']);
        Route::put('cycle-counts/{cycleCount}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'updateCycleCount']);
        Route::delete('cycle-counts/{cycleCount}', [\App\Http\Controllers\Api\InventoryWarehouseController::class, 'destroyCycleCount']);
    });
    
    // Admin API endpoints
    Route::prefix('admin')->group(function () {
        Route::get('drivers', [DriverManagementController::class, 'getDrivers']);
        Route::get('drivers/{id}', [DriverManagementController::class, 'getDriverDetails']);
        Route::post('drivers/{id}/approve', [DriverManagementController::class, 'approveDriver']);
        Route::post('drivers/{id}/reject', [DriverManagementController::class, 'rejectDriver']);
        Route::get('driver-stats', [DriverManagementController::class, 'getDriverStats']);
    });
    
    // Health check
    Route::get('health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0'
        ]);
    });
});
