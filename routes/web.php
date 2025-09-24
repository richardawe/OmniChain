<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverAppController;
use App\Http\Controllers\Admin\DriverManagementController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
