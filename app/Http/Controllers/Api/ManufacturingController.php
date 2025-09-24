<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\BillOfMaterial;
use App\Models\ProductionRoute;
use App\Models\Machine;
use App\Models\MachineTelemetry;
use App\Models\QualityInspection;
use App\Models\BatchTracking;
use App\Models\MaterialMovement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ManufacturingController extends Controller
{
    public function getManufacturingSummary(): JsonResponse
    {
        $summary = [
            'work_orders' => [
                'total' => WorkOrder::count(),
                'planned' => WorkOrder::where('status', 'planned')->count(),
                'in_progress' => WorkOrder::where('status', 'in_progress')->count(),
                'completed' => WorkOrder::where('status', 'completed')->count(),
                'on_hold' => WorkOrder::where('status', 'on_hold')->count(),
            ],
            'machines' => [
                'total' => Machine::count(),
                'active' => Machine::where('status', 'active')->count(),
                'maintenance' => Machine::where('status', 'maintenance')->count(),
                'down' => Machine::where('status', 'down')->count(),
            ],
            'quality' => [
                'total_inspections' => QualityInspection::count(),
                'pass_rate' => QualityInspection::where('inspection_result', 'pass')->count(),
                'fail_rate' => QualityInspection::where('inspection_result', 'fail')->count(),
                'pending_batches' => BatchTracking::where('quality_status', 'pending')->count(),
            ],
            'production' => [
                'total_boms' => BillOfMaterial::count(),
                'active_routes' => ProductionRoute::where('status', 'active')->count(),
                'material_movements' => MaterialMovement::count(),
            ]
        ];

        return response()->json(['success' => true, 'data' => $summary]);
    }

    public function getWorkOrders(Request $request): JsonResponse
    {
        $query = WorkOrder::with(['product', 'productionLine', 'bom', 'route', 'shift', 'creator', 'supervisor']);

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);
        if ($request->filled('production_line_id')) $query->where('production_line_id', $request->production_line_id);
        if ($request->filled('priority')) $query->where('priority', $request->priority);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('work_order_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $workOrders = $query->orderBy('planned_start_time', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $workOrders]);
    }

    public function getMachines(Request $request): JsonResponse
    {
        $query = Machine::with(['location']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('machine_type')) $query->where('machine_type', $request->machine_type);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);

        $machines = $query->orderBy('machine_name')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $machines]);
    }

    public function getMachineTelemetry(Request $request): JsonResponse
    {
        $query = MachineTelemetry::with(['machine']);
        if ($request->filled('machine_id')) $query->where('machine_id', $request->machine_id);
        if ($request->filled('date_from')) $query->where('timestamp', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->where('timestamp', '<=', $request->date_to);
        if ($request->filled('operational_state')) $query->where('operational_state', $request->operational_state);

        $telemetry = $query->orderBy('timestamp', 'desc')->paginate($request->get('per_page', 50));
        return response()->json(['success' => true, 'data' => $telemetry]);
    }

    public function getQualityInspections(Request $request): JsonResponse
    {
        $query = QualityInspection::with(['workOrder.product', 'inspector']);
        if ($request->filled('inspection_result')) $query->where('inspection_result', $request->inspection_result);
        if ($request->filled('inspection_type')) $query->where('inspection_type', $request->inspection_type);
        if ($request->filled('inspector_id')) $query->where('inspector_id', $request->inspector_id);

        $inspections = $query->orderBy('inspection_timestamp', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $inspections]);
    }

    public function getBatchTracking(Request $request): JsonResponse
    {
        $query = BatchTracking::with(['product', 'workOrder', 'creator']);
        if ($request->filled('quality_status')) $query->where('quality_status', $request->quality_status);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('batch_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $batches = $query->orderBy('production_date', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $batches]);
    }

    public function getBOMs(Request $request): JsonResponse
    {
        $query = BillOfMaterial::with(['product', 'components.componentProduct']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bom_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $boms = $query->orderBy('effective_date', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $boms]);
    }

    public function getProductionRoutes(Request $request): JsonResponse
    {
        $query = ProductionRoute::with(['product', 'steps.machine']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('route_id', 'like', "%{$search}%")
                  ->orWhere('route_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $routes = $query->orderBy('effective_date', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $routes]);
    }

    public function getMaterialMovements(Request $request): JsonResponse
    {
        $query = MaterialMovement::with(['fromLocation', 'toLocation', 'product', 'responsibleEmployee', 'workOrder']);
        if ($request->filled('movement_type')) $query->where('movement_type', $request->movement_type);
        if ($request->filled('from_location_id')) $query->where('from_location_id', $request->from_location_id);
        if ($request->filled('to_location_id')) $query->where('to_location_id', $request->to_location_id);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);

        $movements = $query->orderBy('movement_timestamp', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $movements]);
    }

    // CRUD Operations for Work Orders
    public function storeWorkOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'required|string|max:50|unique:work_orders,work_order_id',
            'production_line_id' => 'required|integer|exists:locations,id',
            'product_id' => 'required|integer|exists:products,id',
            'bom_id' => 'nullable|integer|exists:bill_of_materials,id',
            'route_id' => 'nullable|integer|exists:production_routes,id',
            'quantity_planned' => 'required|integer|min:1',
            'planned_start_time' => 'required|date|after_or_equal:now',
            'planned_end_time' => 'required|date|after:planned_start_time',
            'shift_id' => 'nullable|integer|exists:shifts,id',
            'priority' => 'required|integer|min:1|max:5',
            'created_by' => 'required|integer|exists:users,id',
            'assigned_supervisor' => 'nullable|integer|exists:users,id',
            'operator_ids' => 'nullable|array',
            'operator_ids.*' => 'integer|exists:users,id',
            'associated_batch_numbers' => 'nullable|array',
            'associated_batch_numbers.*' => 'string|max:50',
            'work_instructions' => 'nullable|string|max:2000',
            'special_requirements' => 'nullable|string|max:1000',
            'work_order_metadata' => 'nullable|array',
        ]);

        $workOrder = WorkOrder::create($validated);
        $workOrder->load(['product', 'productionLine', 'bom', 'route', 'shift', 'creator', 'supervisor']);

        return response()->json(['success' => true, 'data' => $workOrder], 201);
    }

    public function showWorkOrder(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->load(['product', 'productionLine', 'bom', 'route', 'shift', 'creator', 'supervisor']);
        return response()->json(['success' => true, 'data' => $workOrder]);
    }

    public function updateWorkOrder(Request $request, WorkOrder $workOrder): JsonResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'sometimes|string|max:50|unique:work_orders,work_order_id,' . $workOrder->id,
            'production_line_id' => 'sometimes|integer|exists:locations,id',
            'product_id' => 'sometimes|integer|exists:products,id',
            'bom_id' => 'nullable|integer|exists:bill_of_materials,id',
            'route_id' => 'nullable|integer|exists:production_routes,id',
            'quantity_planned' => 'sometimes|integer|min:1',
            'quantity_produced' => 'sometimes|integer|min:0',
            'quantity_scrapped' => 'sometimes|integer|min:0',
            'planned_start_time' => 'sometimes|date',
            'planned_end_time' => 'sometimes|date|after:planned_start_time',
            'actual_start_time' => 'nullable|date',
            'actual_end_time' => 'nullable|date|after:actual_start_time',
            'shift_id' => 'nullable|integer|exists:shifts,id',
            'status' => 'sometimes|string|in:planned,in_progress,completed,on_hold,cancelled',
            'priority' => 'sometimes|integer|min:1|max:5',
            'assigned_supervisor' => 'nullable|integer|exists:users,id',
            'operator_ids' => 'nullable|array',
            'operator_ids.*' => 'integer|exists:users,id',
            'associated_batch_numbers' => 'nullable|array',
            'associated_batch_numbers.*' => 'string|max:50',
            'work_instructions' => 'nullable|string|max:2000',
            'special_requirements' => 'nullable|string|max:1000',
            'work_order_metadata' => 'nullable|array',
        ]);

        $workOrder->update($validated);
        $workOrder->load(['product', 'productionLine', 'bom', 'route', 'shift', 'creator', 'supervisor']);

        return response()->json(['success' => true, 'data' => $workOrder]);
    }

    public function destroyWorkOrder(WorkOrder $workOrder): JsonResponse
    {
        $workOrder->delete();
        return response()->json(['success' => true, 'message' => 'Work order deleted successfully']);
    }

    // CRUD Operations for Machines
    public function storeMachine(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'machine_name' => 'required|string|max:100',
            'machine_type' => 'required|string|max:50',
            'location_id' => 'required|integer|exists:locations,id',
            'company_id' => 'required|integer|exists:companies,id',
            'status' => 'required|string|in:active,maintenance,down,retired',
            'manufacturer' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'capacity' => 'nullable|numeric|min:0',
            'efficiency_rating' => 'nullable|numeric|min:0|max:100',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date|after:last_maintenance_date',
            'machine_metadata' => 'nullable|array',
        ]);

        $machine = Machine::create($validated);
        $machine->load(['location', 'company']);

        return response()->json(['success' => true, 'data' => $machine], 201);
    }

    public function showMachine(Machine $machine): JsonResponse
    {
        $machine->load(['location', 'company']);
        return response()->json(['success' => true, 'data' => $machine]);
    }

    public function updateMachine(Request $request, Machine $machine): JsonResponse
    {
        $validated = $request->validate([
            'machine_name' => 'sometimes|string|max:100',
            'machine_type' => 'sometimes|string|max:50',
            'location_id' => 'sometimes|integer|exists:locations,id',
            'company_id' => 'sometimes|integer|exists:companies,id',
            'status' => 'sometimes|string|in:active,maintenance,down,retired',
            'manufacturer' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'capacity' => 'nullable|numeric|min:0',
            'efficiency_rating' => 'nullable|numeric|min:0|max:100',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date|after:last_maintenance_date',
            'machine_metadata' => 'nullable|array',
        ]);

        $machine->update($validated);
        $machine->load(['location', 'company']);

        return response()->json(['success' => true, 'data' => $machine]);
    }

    public function destroyMachine(Machine $machine): JsonResponse
    {
        $machine->delete();
        return response()->json(['success' => true, 'message' => 'Machine deleted successfully']);
    }

    // CRUD Operations for Quality Inspections
    public function storeQualityInspection(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'required|integer|exists:work_orders,id',
            'inspection_type' => 'required|string|max:50',
            'inspection_result' => 'required|string|in:pass,fail,pending',
            'inspector_id' => 'required|integer|exists:users,id',
            'inspection_timestamp' => 'required|date',
            'inspection_notes' => 'nullable|string|max:1000',
            'defect_description' => 'nullable|string|max:500',
            'corrective_action' => 'nullable|string|max:500',
            'inspection_metadata' => 'nullable|array',
        ]);

        $inspection = QualityInspection::create($validated);
        $inspection->load(['workOrder.product', 'inspector']);

        return response()->json(['success' => true, 'data' => $inspection], 201);
    }

    public function showQualityInspection(QualityInspection $qualityInspection): JsonResponse
    {
        $qualityInspection->load(['workOrder.product', 'inspector']);
        return response()->json(['success' => true, 'data' => $qualityInspection]);
    }

    public function updateQualityInspection(Request $request, QualityInspection $qualityInspection): JsonResponse
    {
        $validated = $request->validate([
            'work_order_id' => 'sometimes|integer|exists:work_orders,id',
            'inspection_type' => 'sometimes|string|max:50',
            'inspection_result' => 'sometimes|string|in:pass,fail,pending',
            'inspector_id' => 'sometimes|integer|exists:users,id',
            'inspection_timestamp' => 'sometimes|date',
            'inspection_notes' => 'nullable|string|max:1000',
            'defect_description' => 'nullable|string|max:500',
            'corrective_action' => 'nullable|string|max:500',
            'inspection_metadata' => 'nullable|array',
        ]);

        $qualityInspection->update($validated);
        $qualityInspection->load(['workOrder.product', 'inspector']);

        return response()->json(['success' => true, 'data' => $qualityInspection]);
    }

    public function destroyQualityInspection(QualityInspection $qualityInspection): JsonResponse
    {
        $qualityInspection->delete();
        return response()->json(['success' => true, 'message' => 'Quality inspection deleted successfully']);
    }

    // CRUD Operations for Batch Tracking
    public function storeBatchTracking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'batch_id' => 'required|string|max:50|unique:batch_tracking,batch_id',
            'work_order_id' => 'nullable|integer|exists:work_orders,id',
            'quantity_produced' => 'required|integer|min:1',
            'production_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:production_date',
            'quality_status' => 'required|string|in:pending,approved,rejected,quarantined',
            'created_by' => 'required|integer|exists:users,id',
            'batch_metadata' => 'nullable|array',
        ]);

        $batch = BatchTracking::create($validated);
        $batch->load(['product', 'workOrder', 'creator']);

        return response()->json(['success' => true, 'data' => $batch], 201);
    }

    public function showBatchTracking(BatchTracking $batchTracking): JsonResponse
    {
        $batchTracking->load(['product', 'workOrder', 'creator']);
        return response()->json(['success' => true, 'data' => $batchTracking]);
    }

    public function updateBatchTracking(Request $request, BatchTracking $batchTracking): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|integer|exists:products,id',
            'batch_id' => 'sometimes|string|max:50|unique:batch_tracking,batch_id,' . $batchTracking->id,
            'work_order_id' => 'nullable|integer|exists:work_orders,id',
            'quantity_produced' => 'sometimes|integer|min:1',
            'production_date' => 'sometimes|date',
            'expiry_date' => 'nullable|date|after:production_date',
            'quality_status' => 'sometimes|string|in:pending,approved,rejected,quarantined',
            'batch_metadata' => 'nullable|array',
        ]);

        $batchTracking->update($validated);
        $batchTracking->load(['product', 'workOrder', 'creator']);

        return response()->json(['success' => true, 'data' => $batchTracking]);
    }

    public function destroyBatchTracking(BatchTracking $batchTracking): JsonResponse
    {
        $batchTracking->delete();
        return response()->json(['success' => true, 'message' => 'Batch tracking record deleted successfully']);
    }
}