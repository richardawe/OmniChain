<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\RoutePlan;
use App\Models\RoutePlanStop;
use App\Models\VehicleTelematics;
use App\Models\ProofOfDelivery;
use App\Models\CustomsDocumentation;
use App\Models\ContainerTracking;
use App\Models\TerminalEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransportationController extends Controller
{
    /**
     * Get all vehicles with optional filtering
     */
    public function getVehicles(Request $request): JsonResponse
    {
        $query = Vehicle::with(['company', 'assignedDriver']);

        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('driver_id')) {
            $query->where('assigned_driver_id', $request->driver_id);
        }

        $vehicles = $query->orderBy('vehicle_number')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * Get vehicle details
     */
    public function getVehicle(string $id): JsonResponse
    {
        $vehicle = Vehicle::with(['company', 'assignedDriver', 'routePlans', 'telematics' => function($query) {
            $query->latest()->take(10);
        }])->find($id);

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vehicle
        ]);
    }

    /**
     * Create a new vehicle.
     */
    public function createVehicle(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|in:truck,van,trailer,container',
            'status' => 'required|string|in:active,maintenance,inactive',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_class' => 'nullable|string|in:light_duty,medium_duty,heavy_duty',
            'max_weight_kg' => 'nullable|numeric|min:0',
            'max_volume_cubic_meters' => 'nullable|numeric|min:0',
            'max_pallets' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|in:diesel,gasoline,electric,hybrid',
            'fuel_capacity_liters' => 'nullable|numeric|min:0',
            'average_fuel_consumption_km_per_liter' => 'nullable|numeric|min:0',
            'company_id' => 'required|exists:companies,id'
        ]);

        $vehicle = Vehicle::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehicle created successfully'
        ], 201);
    }

    /**
     * Update an existing vehicle.
     */
    public function updateVehicle(Request $request, string $id): JsonResponse
    {
        $vehicle = Vehicle::find($id);
        
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        $request->validate([
            'vehicle_number' => 'sometimes|required|string|max:255',
            'license_plate' => 'sometimes|required|string|max:255',
            'make' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'vehicle_type' => 'sometimes|required|string|in:truck,van,trailer,container',
            'status' => 'sometimes|required|string|in:active,maintenance,inactive',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vehicle_class' => 'nullable|string|in:light_duty,medium_duty,heavy_duty',
            'max_weight_kg' => 'nullable|numeric|min:0',
            'max_volume_cubic_meters' => 'nullable|numeric|min:0',
            'max_pallets' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|in:diesel,gasoline,electric,hybrid',
            'fuel_capacity_liters' => 'nullable|numeric|min:0',
            'average_fuel_consumption_km_per_liter' => 'nullable|numeric|min:0',
            'company_id' => 'sometimes|required|exists:companies,id'
        ]);

        $vehicle->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Vehicle updated successfully'
        ]);
    }

    /**
     * Delete a vehicle.
     */
    public function deleteVehicle(string $id): JsonResponse
    {
        $vehicle = Vehicle::find($id);
        
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found'
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ]);
    }

    /**
     * Get route plans with filtering
     */
    public function getRoutePlans(Request $request): JsonResponse
    {
        $query = RoutePlan::with(['carrierCompany', 'assignedDriver', 'vehicle', 'stops']);

        // Apply filters
        if ($request->filled('carrier_company_id')) {
            $query->where('carrier_company_id', $request->carrier_company_id);
        }

        if ($request->filled('driver_id')) {
            $query->where('assigned_driver_id', $request->driver_id);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('route_type')) {
            $query->where('route_type', $request->route_type);
        }

        if ($request->filled('date_from')) {
            $query->where('planned_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('planned_date', '<=', $request->date_to);
        }

        $routePlans = $query->orderBy('planned_date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $routePlans
        ]);
    }

    /**
     * Get route plan details
     */
    public function getRoutePlan(string $id): JsonResponse
    {
        $routePlan = RoutePlan::with([
            'carrierCompany', 
            'assignedDriver', 
            'vehicle', 
            'stops.location',
            'stops.freightOrder',
            'telematics' => function($query) {
                $query->latest()->take(20);
            }
        ])->find($id);

        if (!$routePlan) {
            return response()->json([
                'success' => false,
                'message' => 'Route plan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $routePlan
        ]);
    }

    /**
     * Create a new route plan
     */
    public function createRoutePlan(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'planned_departure_time' => 'required|date',
            'planned_arrival_time' => 'required|date|after:planned_departure_time',
            'total_distance_km' => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'required|integer|min:0',
            'route_status' => 'required|in:planned,active,completed,cancelled',
            'route_metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $routePlan = RoutePlan::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $routePlan->load(['vehicle', 'driver', 'originLocation', 'destinationLocation']),
                'message' => 'Route plan created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create route plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a route plan
     */
    public function updateRoutePlan(Request $request, string $id): JsonResponse
    {
        $routePlan = RoutePlan::find($id);

        if (!$routePlan) {
            return response()->json([
                'success' => false,
                'message' => 'Route plan not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'route_name' => 'sometimes|required|string|max:255',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'origin_location_id' => 'sometimes|required|exists:locations,id',
            'destination_location_id' => 'sometimes|required|exists:locations,id',
            'planned_departure_time' => 'sometimes|required|date',
            'planned_arrival_time' => 'sometimes|required|date|after:planned_departure_time',
            'total_distance_km' => 'sometimes|required|numeric|min:0',
            'estimated_duration_minutes' => 'sometimes|required|integer|min:0',
            'route_status' => 'sometimes|required|in:planned,active,completed,cancelled',
            'route_metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $routePlan->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $routePlan->load(['vehicle', 'driver', 'originLocation', 'destinationLocation']),
                'message' => 'Route plan updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update route plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a route plan
     */
    public function deleteRoutePlan(string $id): JsonResponse
    {
        $routePlan = RoutePlan::find($id);

        if (!$routePlan) {
            return response()->json([
                'success' => false,
                'message' => 'Route plan not found'
            ], 404);
        }

        try {
            $routePlan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route plan deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete route plan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vehicle telematics data
     */
    public function getVehicleTelematics(Request $request): JsonResponse
    {
        $query = VehicleTelematics::with(['vehicle', 'driver', 'routePlan']);

        // Apply filters
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('route_plan_id')) {
            $query->where('route_plan_id', $request->route_plan_id);
        }

        if ($request->filled('date_from')) {
            $query->where('timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('timestamp', '<=', $request->date_to);
        }

        $telematics = $query->orderBy('timestamp', 'desc')->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $telematics
        ]);
    }

    /**
     * Get proof of deliveries
     */
    public function getProofOfDeliveries(Request $request): JsonResponse
    {
        $query = ProofOfDelivery::with(['freightOrder', 'driver', 'routePlanStop']);

        // Apply filters
        if ($request->filled('freight_order_id')) {
            $query->where('freight_order_id', $request->freight_order_id);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        if ($request->filled('date_from')) {
            $query->where('delivery_timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('delivery_timestamp', '<=', $request->date_to);
        }

        $proofOfDeliveries = $query->orderBy('delivery_timestamp', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $proofOfDeliveries
        ]);
    }

    /**
     * Get customs documentation
     */
    public function getCustomsDocumentations(Request $request): JsonResponse
    {
        $query = CustomsDocumentation::with(['freightOrder']);

        // Apply filters
        if ($request->filled('freight_order_id')) {
            $query->where('freight_order_id', $request->freight_order_id);
        }

        if ($request->filled('customs_status')) {
            $query->where('customs_status', $request->customs_status);
        }

        if ($request->filled('shipment_type')) {
            $query->where('shipment_type', $request->shipment_type);
        }

        if ($request->filled('exporting_country')) {
            $query->where('exporting_country', $request->exporting_country);
        }

        if ($request->filled('importing_country')) {
            $query->where('importing_country', $request->importing_country);
        }

        $customsDocs = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $customsDocs
        ]);
    }

    /**
     * Get container tracking
     */
    public function getContainerTracking(Request $request): JsonResponse
    {
        $query = ContainerTracking::with(['freightOrder', 'terminalEvents']);

        // Apply filters
        if ($request->filled('freight_order_id')) {
            $query->where('freight_order_id', $request->freight_order_id);
        }

        if ($request->filled('container_number')) {
            $query->where('container_number', 'like', '%' . $request->container_number . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vessel_name')) {
            $query->where('vessel_name', 'like', '%' . $request->vessel_name . '%');
        }

        $containers = $query->orderBy('last_update_timestamp', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $containers
        ]);
    }

    /**
     * Get terminal events
     */
    public function getTerminalEvents(Request $request): JsonResponse
    {
        $query = TerminalEvent::with(['containerTracking', 'freightOrder']);

        // Apply filters
        if ($request->filled('container_tracking_id')) {
            $query->where('container_tracking_id', $request->container_tracking_id);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('event_status')) {
            $query->where('event_status', $request->event_status);
        }

        if ($request->filled('terminal_name')) {
            $query->where('terminal_name', 'like', '%' . $request->terminal_name . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('event_timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_timestamp', '<=', $request->date_to);
        }

        $events = $query->orderBy('event_timestamp', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get transportation summary statistics
     */
    public function getTransportationSummary(): JsonResponse
    {
        $summary = [
            'vehicles' => [
                'total' => Vehicle::count(),
                'active' => Vehicle::where('status', 'active')->count(),
                'maintenance' => Vehicle::where('status', 'maintenance')->count(),
                'by_type' => Vehicle::selectRaw('vehicle_type, count(*) as count')
                    ->groupBy('vehicle_type')
                    ->get()
            ],
            'route_plans' => [
                'total' => RoutePlan::count(),
                'planned' => RoutePlan::where('status', 'planned')->count(),
                'in_progress' => RoutePlan::where('status', 'in_progress')->count(),
                'completed' => RoutePlan::where('status', 'completed')->count(),
                'by_type' => RoutePlan::selectRaw('route_type, count(*) as count')
                    ->groupBy('route_type')
                    ->get()
            ],
            'proof_of_deliveries' => [
                'total' => ProofOfDelivery::count(),
                'delivered' => ProofOfDelivery::where('delivery_status', 'delivered')->count(),
                'failed' => ProofOfDelivery::where('delivery_status', 'failed')->count(),
                'partially_delivered' => ProofOfDelivery::where('delivery_status', 'partially_delivered')->count()
            ],
            'customs_documentations' => [
                'total' => CustomsDocumentation::count(),
                'approved' => CustomsDocumentation::where('customs_status', 'approved')->count(),
                'pending' => CustomsDocumentation::where('customs_status', 'pending')->count(),
                'rejected' => CustomsDocumentation::where('customs_status', 'rejected')->count()
            ],
            'container_tracking' => [
                'total' => ContainerTracking::count(),
                'in_transit' => ContainerTracking::where('status', 'in_transit')->count(),
                'at_port' => ContainerTracking::where('status', 'at_port')->count(),
                'delivered' => ContainerTracking::where('status', 'delivered')->count()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}