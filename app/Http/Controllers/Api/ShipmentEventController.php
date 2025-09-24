<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShipmentEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ShipmentEventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'freight_order_id' => 'nullable|exists:freight_orders,id',
            'event_type' => 'required|string|max:255',
            'event_code' => 'nullable|string|max:10',
            'event_time' => 'nullable|date',
            'location_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'raw_data' => 'nullable|array',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Set default event_time if not provided
        if (empty($data['event_time'])) {
            $data['event_time'] = now();
        }

        // Set default event_code if not provided
        if (empty($data['event_code'])) {
            $data['event_code'] = strtoupper(substr($data['event_type'], 0, 2));
        }

        $event = ShipmentEvent::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Shipment event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ShipmentEvent::with('freightOrder');

        // Filter by freight order
        if ($request->filled('freight_order_id')) {
            $query->where('freight_order_id', $request->freight_order_id);
        }

        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('event_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('event_time', '<=', $request->date_to);
        }

        $events = $query->orderBy('event_time', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $event = ShipmentEvent::with('freightOrder')->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }
}
