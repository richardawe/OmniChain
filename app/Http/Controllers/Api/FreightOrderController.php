<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreightOrder;
use App\Models\Company;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FreightOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation',
            'shipmentEvents',
            'assignedDriver'
        ]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // Filter by shipper
        if ($request->filled('shipper_id')) {
            $query->where('shipper_company_id', $request->shipper_id);
        }

        // Filter by carrier
        if ($request->filled('carrier_id')) {
            $query->where('carrier_company_id', $request->carrier_id);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipper_company_id' => 'required|exists:companies,id',
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'service_type' => 'required|in:ltl,ftl,air,ocean,rail,parcel',
            'priority' => 'in:low,normal,high,urgent',
            'requested_pickup_date' => 'nullable|date',
            'requested_delivery_date' => 'nullable|date',
            'total_weight' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'total_pieces' => 'nullable|integer|min:1',
            'declared_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'special_instructions' => 'nullable|string',
            'equipment_requirements' => 'nullable|array',
            'temperature_requirements' => 'nullable|array',
            'hazardous_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = new FreightOrder($request->all());
        $order->order_number = $order->generateOrderNumber();
        $order->save();

        $order->load([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation',
            'assignedDriver'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Freight order created successfully',
            'data' => $order
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $order = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation',
            'assignedDriver',
            'shipmentEvents' => function ($query) {
                $query->orderBy('event_time', 'desc');
            }
        ])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = FreightOrder::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'carrier_company_id' => 'nullable|exists:companies,id',
            'service_type' => 'in:ltl,ftl,air,ocean,rail,parcel',
            'priority' => 'in:low,normal,high,urgent',
            'pickup_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'requested_pickup_date' => 'nullable|date',
            'requested_delivery_date' => 'nullable|date',
            'status' => 'in:draft,quoted,booked,picked_up,in_transit,delivered,exception,cancelled',
            'total_weight' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'total_pieces' => 'nullable|integer|min:1',
            'declared_value' => 'nullable|numeric|min:0',
            'special_instructions' => 'nullable|string',
            'equipment_requirements' => 'nullable|array',
            'temperature_requirements' => 'nullable|array',
            'hazardous_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->all());
        $order->load([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Freight order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = FreightOrder::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        // Only allow deletion of draft orders
        if ($order->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Only draft orders can be deleted'
            ], 422);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Freight order deleted successfully'
        ]);
    }

    /**
     * Get shipment tracking information
     */
    public function track(string $orderNumber): JsonResponse
    {
        $order = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation',
            'shipmentEvents' => function ($query) {
                $query->orderBy('event_time', 'desc');
            }
        ])->where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Freight order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'current_status' => $order->status,
                'latest_event' => $order->latest_event,
                'estimated_transit_time' => $order->estimated_transit_time,
            ]
        ]);
    }

    /**
     * Get available carriers for a route
     */
    public function getAvailableCarriers(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'service_type' => 'required|in:ltl,ftl,air,ocean,rail,parcel',
            'pickup_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $originLocation = Location::find($request->origin_location_id);
        $destinationLocation = Location::find($request->destination_location_id);

        // Get carriers that service this route
        $carriers = Company::byType('carrier')
            ->active()
            ->whereHas('carriers', function ($query) use ($request) {
                $query->where('status', 'active');
            })
            ->with(['carriers' => function ($query) use ($request) {
                $query->where('status', 'active');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'route' => [
                    'origin' => $originLocation,
                    'destination' => $destinationLocation,
                ],
                'carriers' => $carriers,
            ]
        ]);
    }

    /**
     * Find an available driver for assignment
     */
    private function findAvailableDriver(): ?User
    {
        // Find drivers with status 'available' and assign based on workload
        $availableDriver = User::where('status', 'available')
            ->withCount(['assignedFreightOrders as active_orders_count' => function ($query) {
                $query->whereIn('status', ['booked', 'picked_up', 'in_transit']);
            }])
            ->orderBy('active_orders_count', 'asc') // Assign to driver with least active orders
            ->orderBy('created_at', 'asc') // Then by oldest driver
            ->first();

        return $availableDriver;
    }
}
