<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryBalance;
use App\Models\WarehouseBin;
use App\Models\CycleCount;
use App\Models\InboundReceiving;
use App\Models\PutawayRecord;
use App\Models\OutboundShipment;
use App\Models\CrossDockEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryWarehouseController extends Controller
{
    public function getInventoryWarehouseSummary(): JsonResponse
    {
        $summary = [
            'inventory' => [
                'total_products' => InventoryBalance::distinct('product_id')->count(),
                'total_locations' => InventoryBalance::distinct('location_id')->count(),
                'low_stock_items' => InventoryBalance::whereColumn('quantity_on_hand', '<=', 'reorder_point')->count(),
                'out_of_stock' => InventoryBalance::where('quantity_on_hand', 0)->count(),
                'total_value' => InventoryBalance::sum('total_value'),
            ],
            'warehouse' => [
                'total_bins' => WarehouseBin::count(),
                'active_bins' => WarehouseBin::where('status', 'active')->count(),
                'high_utilization' => WarehouseBin::where('utilization_percentage', '>', 80)->count(),
                'temperature_controlled' => WarehouseBin::where('requires_temperature_control', true)->count(),
            ],
            'receiving' => [
                'total_receipts' => InboundReceiving::count(),
                'pending_receipts' => InboundReceiving::whereIn('status', ['expected', 'arrived'])->count(),
                'completed_receipts' => InboundReceiving::where('status', 'completed')->count(),
                'putaway_pending' => InboundReceiving::where('status', 'putaway_pending')->count(),
            ],
            'shipping' => [
                'total_shipments' => OutboundShipment::count(),
                'pending_shipments' => OutboundShipment::whereIn('status', ['pending', 'picking'])->count(),
                'shipped' => OutboundShipment::whereIn('status', ['shipped', 'delivered'])->count(),
                'delivered' => OutboundShipment::where('status', 'delivered')->count(),
            ],
            'quality' => [
                'total_counts' => CycleCount::count(),
                'pending_counts' => CycleCount::whereIn('count_status', ['scheduled', 'in_progress'])->count(),
                'discrepancies' => CycleCount::where('discrepancy', '!=', 0)->count(),
                'accuracy_rate' => CycleCount::where('count_status', 'completed')->count() > 0 
                    ? (CycleCount::where('count_status', 'completed')->where('discrepancy', 0)->count() / CycleCount::where('count_status', 'completed')->count()) * 100 
                    : 0,
            ]
        ];

        return response()->json(['success' => true, 'data' => $summary]);
    }

    public function getInventoryBalances(Request $request): JsonResponse
    {
        $query = InventoryBalance::with(['location', 'product']);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('product_id')) $query->where('product_id', $request->product_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('low_stock')) $query->whereColumn('quantity_on_hand', '<=', 'reorder_point');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('lot_bin', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $inventory = $query->orderBy('updated_at', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $inventory]);
    }

    public function getWarehouseBins(Request $request): JsonResponse
    {
        $query = WarehouseBin::with(['location']);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('bin_type')) $query->where('bin_type', $request->bin_type);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('zone')) $query->where('zone', $request->zone);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bin_id', 'like', "%{$search}%")
                  ->orWhere('bin_name', 'like', "%{$search}%");
            });
        }

        $bins = $query->orderBy('bin_name')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $bins]);
    }

    public function getInboundReceiving(Request $request): JsonResponse
    {
        $query = InboundReceiving::with(['carrier', 'location', 'putawayRecords']);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('carrier_id')) $query->where('carrier_id', $request->carrier_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receiving_id', 'like', "%{$search}%")
                  ->orWhere('asn_number', 'like', "%{$search}%")
                  ->orWhere('po_number', 'like', "%{$search}%");
            });
        }

        $receiving = $query->orderBy('expected_arrival_time', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $receiving]);
    }

    public function getOutboundShipments(Request $request): JsonResponse
    {
        $query = OutboundShipment::with(['shipFromLocation', 'shipToLocation', 'carrier']);
        if ($request->filled('ship_from_location_id')) $query->where('ship_from_location_id', $request->ship_from_location_id);
        if ($request->filled('ship_to_location_id')) $query->where('ship_to_location_id', $request->ship_to_location_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('order_type')) $query->where('order_type', $request->order_type);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shipment_id', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('pick_list_id', 'like', "%{$search}%");
            });
        }

        $shipments = $query->orderBy('scheduled_ship_date', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $shipments]);
    }

    public function getCycleCounts(Request $request): JsonResponse
    {
        $query = CycleCount::with(['location', 'product', 'counter', 'supervisor']);
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('count_status')) $query->where('count_status', $request->count_status);
        if ($request->filled('count_type')) $query->where('count_type', $request->count_type);
        if ($request->filled('has_discrepancy')) $query->where('discrepancy', '!=', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('count_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $counts = $query->orderBy('count_date', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $counts]);
    }

    public function getPutawayRecords(Request $request): JsonResponse
    {
        $query = PutawayRecord::with(['receiving', 'fromLocation', 'toBin', 'product', 'putawayOperator']);
        if ($request->filled('receiving_id')) $query->where('receiving_id', $request->receiving_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('to_bin_id')) $query->where('to_bin_id', $request->to_bin_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('putaway_id', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $putaways = $query->orderBy('putaway_timestamp', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $putaways]);
    }

    public function getCrossDockEvents(Request $request): JsonResponse
    {
        $query = CrossDockEvent::with(['incomingShipment', 'outgoingShipment', 'crossDockLocation', 'operator']);
        if ($request->filled('cross_dock_location_id')) $query->where('cross_dock_location_id', $request->cross_dock_location_id);
        if ($request->filled('status')) $query->where('status', $request->status);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_id', 'like', "%{$search}%")
                  ->orWhereHas('incomingShipment', function($receivingQuery) use ($search) {
                      $receivingQuery->where('receiving_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('outgoingShipment', function($shipmentQuery) use ($search) {
                      $shipmentQuery->where('shipment_id', 'like', "%{$search}%");
                  });
            });
        }

        $events = $query->orderBy('transfer_start_time', 'desc')->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $events]);
    }

    // CRUD Operations for Inventory Balances
    public function storeInventoryBalance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity_on_hand' => 'required|integer|min:0',
            'quantity_allocated' => 'nullable|integer|min:0',
            'quantity_available' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'status' => 'required|string|in:active,inactive,quarantined,reserved',
            'lot_bin' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_value' => 'nullable|numeric|min:0',
            'last_counted_date' => 'nullable|date',
            'last_movement_date' => 'nullable|date',
            'inventory_metadata' => 'nullable|array',
        ]);

        $inventory = InventoryBalance::create($validated);
        $inventory->load(['location', 'product']);

        return response()->json(['success' => true, 'data' => $inventory], 201);
    }

    public function showInventoryBalance(InventoryBalance $inventoryBalance): JsonResponse
    {
        $inventoryBalance->load(['location', 'product']);
        return response()->json(['success' => true, 'data' => $inventoryBalance]);
    }

    public function updateInventoryBalance(Request $request, InventoryBalance $inventoryBalance): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'sometimes|integer|exists:locations,id',
            'product_id' => 'sometimes|integer|exists:products,id',
            'quantity_on_hand' => 'sometimes|integer|min:0',
            'quantity_allocated' => 'nullable|integer|min:0',
            'quantity_available' => 'nullable|integer|min:0',
            'reorder_point' => 'nullable|integer|min:0',
            'reorder_quantity' => 'nullable|integer|min:0',
            'status' => 'sometimes|string|in:active,inactive,quarantined,reserved',
            'lot_bin' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_value' => 'nullable|numeric|min:0',
            'last_counted_date' => 'nullable|date',
            'last_movement_date' => 'nullable|date',
            'inventory_metadata' => 'nullable|array',
        ]);

        $inventoryBalance->update($validated);
        $inventoryBalance->load(['location', 'product']);

        return response()->json(['success' => true, 'data' => $inventoryBalance]);
    }

    public function destroyInventoryBalance(InventoryBalance $inventoryBalance): JsonResponse
    {
        $inventoryBalance->delete();
        return response()->json(['success' => true, 'message' => 'Inventory balance deleted successfully']);
    }

    // CRUD Operations for Warehouse Bins
    public function storeWarehouseBin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'bin_id' => 'required|string|max:50|unique:warehouse_bins,bin_id',
            'bin_name' => 'required|string|max:100',
            'bin_type' => 'required|string|in:storage,picking,receiving,shipping,quarantine',
            'zone' => 'nullable|string|max:50',
            'aisle' => 'nullable|string|max:50',
            'rack' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'status' => 'required|string|in:active,inactive,maintenance,quarantine',
            'capacity' => 'nullable|numeric|min:0',
            'current_utilization' => 'nullable|numeric|min:0|max:100',
            'utilization_percentage' => 'nullable|numeric|min:0|max:100',
            'requires_temperature_control' => 'boolean',
            'temperature_range_min' => 'nullable|numeric',
            'temperature_range_max' => 'nullable|numeric',
            'hazardous_materials_allowed' => 'boolean',
            'bin_metadata' => 'nullable|array',
        ]);

        $bin = WarehouseBin::create($validated);
        $bin->load(['location']);

        return response()->json(['success' => true, 'data' => $bin], 201);
    }

    public function showWarehouseBin(WarehouseBin $warehouseBin): JsonResponse
    {
        $warehouseBin->load(['location']);
        return response()->json(['success' => true, 'data' => $warehouseBin]);
    }

    public function updateWarehouseBin(Request $request, WarehouseBin $warehouseBin): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'sometimes|integer|exists:locations,id',
            'bin_id' => 'sometimes|string|max:50|unique:warehouse_bins,bin_id,' . $warehouseBin->id,
            'bin_name' => 'sometimes|string|max:100',
            'bin_type' => 'sometimes|string|in:storage,picking,receiving,shipping,quarantine',
            'zone' => 'nullable|string|max:50',
            'aisle' => 'nullable|string|max:50',
            'rack' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:active,inactive,maintenance,quarantine',
            'capacity' => 'nullable|numeric|min:0',
            'current_utilization' => 'nullable|numeric|min:0|max:100',
            'utilization_percentage' => 'nullable|numeric|min:0|max:100',
            'requires_temperature_control' => 'boolean',
            'temperature_range_min' => 'nullable|numeric',
            'temperature_range_max' => 'nullable|numeric',
            'hazardous_materials_allowed' => 'boolean',
            'bin_metadata' => 'nullable|array',
        ]);

        $warehouseBin->update($validated);
        $warehouseBin->load(['location']);

        return response()->json(['success' => true, 'data' => $warehouseBin]);
    }

    public function destroyWarehouseBin(WarehouseBin $warehouseBin): JsonResponse
    {
        $warehouseBin->delete();
        return response()->json(['success' => true, 'message' => 'Warehouse bin deleted successfully']);
    }

    // CRUD Operations for Inbound Receiving
    public function storeInboundReceiving(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'carrier_id' => 'nullable|integer|exists:carriers,id',
            'receiving_id' => 'required|string|max:50|unique:inbound_receiving,receiving_id',
            'asn_number' => 'nullable|string|max:100',
            'po_number' => 'nullable|string|max:100',
            'expected_arrival_time' => 'required|date',
            'actual_arrival_time' => 'nullable|date',
            'status' => 'required|string|in:expected,arrived,receiving,inspecting,putaway_pending,completed',
            'total_expected_items' => 'nullable|integer|min:0',
            'total_received_items' => 'nullable|integer|min:0',
            'total_expected_quantity' => 'nullable|numeric|min:0',
            'total_received_quantity' => 'nullable|numeric|min:0',
            'receiving_notes' => 'nullable|string|max:1000',
            'receiving_metadata' => 'nullable|array',
        ]);

        $receiving = InboundReceiving::create($validated);
        $receiving->load(['carrier', 'location', 'putawayRecords']);

        return response()->json(['success' => true, 'data' => $receiving], 201);
    }

    public function showInboundReceiving(InboundReceiving $inboundReceiving): JsonResponse
    {
        $inboundReceiving->load(['carrier', 'location', 'putawayRecords']);
        return response()->json(['success' => true, 'data' => $inboundReceiving]);
    }

    public function updateInboundReceiving(Request $request, InboundReceiving $inboundReceiving): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'sometimes|integer|exists:locations,id',
            'carrier_id' => 'nullable|integer|exists:carriers,id',
            'receiving_id' => 'sometimes|string|max:50|unique:inbound_receiving,receiving_id,' . $inboundReceiving->id,
            'asn_number' => 'nullable|string|max:100',
            'po_number' => 'nullable|string|max:100',
            'expected_arrival_time' => 'sometimes|date',
            'actual_arrival_time' => 'nullable|date',
            'status' => 'sometimes|string|in:expected,arrived,receiving,inspecting,putaway_pending,completed',
            'total_expected_items' => 'nullable|integer|min:0',
            'total_received_items' => 'nullable|integer|min:0',
            'total_expected_quantity' => 'nullable|numeric|min:0',
            'total_received_quantity' => 'nullable|numeric|min:0',
            'receiving_notes' => 'nullable|string|max:1000',
            'receiving_metadata' => 'nullable|array',
        ]);

        $inboundReceiving->update($validated);
        $inboundReceiving->load(['carrier', 'location', 'putawayRecords']);

        return response()->json(['success' => true, 'data' => $inboundReceiving]);
    }

    public function destroyInboundReceiving(InboundReceiving $inboundReceiving): JsonResponse
    {
        $inboundReceiving->delete();
        return response()->json(['success' => true, 'message' => 'Inbound receiving record deleted successfully']);
    }

    // CRUD Operations for Outbound Shipments
    public function storeOutboundShipment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ship_from_location_id' => 'required|integer|exists:locations,id',
            'ship_to_location_id' => 'required|integer|exists:locations,id',
            'carrier_id' => 'nullable|integer|exists:carriers,id',
            'shipment_id' => 'required|string|max:50|unique:outbound_shipments,shipment_id',
            'tracking_number' => 'nullable|string|max:100',
            'pick_list_id' => 'nullable|string|max:100',
            'order_type' => 'required|string|in:sales_order,transfer_order,return_order,service_order',
            'status' => 'required|string|in:pending,picking,picked,packed,shipped,delivered,cancelled',
            'scheduled_ship_date' => 'required|date',
            'actual_ship_date' => 'nullable|date',
            'expected_delivery_date' => 'nullable|date',
            'actual_delivery_date' => 'nullable|date',
            'total_items' => 'nullable|integer|min:0',
            'total_quantity' => 'nullable|numeric|min:0',
            'total_weight' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'shipping_notes' => 'nullable|string|max:1000',
            'shipment_metadata' => 'nullable|array',
        ]);

        $shipment = OutboundShipment::create($validated);
        $shipment->load(['shipFromLocation', 'shipToLocation', 'carrier']);

        return response()->json(['success' => true, 'data' => $shipment], 201);
    }

    public function showOutboundShipment(OutboundShipment $outboundShipment): JsonResponse
    {
        $outboundShipment->load(['shipFromLocation', 'shipToLocation', 'carrier']);
        return response()->json(['success' => true, 'data' => $outboundShipment]);
    }

    public function updateOutboundShipment(Request $request, OutboundShipment $outboundShipment): JsonResponse
    {
        $validated = $request->validate([
            'ship_from_location_id' => 'sometimes|integer|exists:locations,id',
            'ship_to_location_id' => 'sometimes|integer|exists:locations,id',
            'carrier_id' => 'nullable|integer|exists:carriers,id',
            'shipment_id' => 'sometimes|string|max:50|unique:outbound_shipments,shipment_id,' . $outboundShipment->id,
            'tracking_number' => 'nullable|string|max:100',
            'pick_list_id' => 'nullable|string|max:100',
            'order_type' => 'sometimes|string|in:sales_order,transfer_order,return_order,service_order',
            'status' => 'sometimes|string|in:pending,picking,picked,packed,shipped,delivered,cancelled',
            'scheduled_ship_date' => 'sometimes|date',
            'actual_ship_date' => 'nullable|date',
            'expected_delivery_date' => 'nullable|date',
            'actual_delivery_date' => 'nullable|date',
            'total_items' => 'nullable|integer|min:0',
            'total_quantity' => 'nullable|numeric|min:0',
            'total_weight' => 'nullable|numeric|min:0',
            'total_volume' => 'nullable|numeric|min:0',
            'shipping_notes' => 'nullable|string|max:1000',
            'shipment_metadata' => 'nullable|array',
        ]);

        $outboundShipment->update($validated);
        $outboundShipment->load(['shipFromLocation', 'shipToLocation', 'carrier']);

        return response()->json(['success' => true, 'data' => $outboundShipment]);
    }

    public function destroyOutboundShipment(OutboundShipment $outboundShipment): JsonResponse
    {
        $outboundShipment->delete();
        return response()->json(['success' => true, 'message' => 'Outbound shipment deleted successfully']);
    }

    // CRUD Operations for Cycle Counts
    public function storeCycleCount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'count_id' => 'required|string|max:50|unique:cycle_counts,count_id',
            'count_type' => 'required|string|in:full,partial,spot,abc',
            'count_status' => 'required|string|in:scheduled,in_progress,completed,cancelled',
            'count_date' => 'required|date',
            'counter_id' => 'required|integer|exists:users,id',
            'supervisor_id' => 'nullable|integer|exists:users,id',
            'expected_quantity' => 'nullable|numeric|min:0',
            'counted_quantity' => 'nullable|numeric|min:0',
            'discrepancy' => 'nullable|numeric',
            'discrepancy_percentage' => 'nullable|numeric|min:0|max:100',
            'count_notes' => 'nullable|string|max:1000',
            'count_metadata' => 'nullable|array',
        ]);

        $count = CycleCount::create($validated);
        $count->load(['location', 'product', 'counter', 'supervisor']);

        return response()->json(['success' => true, 'data' => $count], 201);
    }

    public function showCycleCount(CycleCount $cycleCount): JsonResponse
    {
        $cycleCount->load(['location', 'product', 'counter', 'supervisor']);
        return response()->json(['success' => true, 'data' => $cycleCount]);
    }

    public function updateCycleCount(Request $request, CycleCount $cycleCount): JsonResponse
    {
        $validated = $request->validate([
            'location_id' => 'sometimes|integer|exists:locations,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'count_id' => 'sometimes|string|max:50|unique:cycle_counts,count_id,' . $cycleCount->id,
            'count_type' => 'sometimes|string|in:full,partial,spot,abc',
            'count_status' => 'sometimes|string|in:scheduled,in_progress,completed,cancelled',
            'count_date' => 'sometimes|date',
            'counter_id' => 'sometimes|integer|exists:users,id',
            'supervisor_id' => 'nullable|integer|exists:users,id',
            'expected_quantity' => 'nullable|numeric|min:0',
            'counted_quantity' => 'nullable|numeric|min:0',
            'discrepancy' => 'nullable|numeric',
            'discrepancy_percentage' => 'nullable|numeric|min:0|max:100',
            'count_notes' => 'nullable|string|max:1000',
            'count_metadata' => 'nullable|array',
        ]);

        $cycleCount->update($validated);
        $cycleCount->load(['location', 'product', 'counter', 'supervisor']);

        return response()->json(['success' => true, 'data' => $cycleCount]);
    }

    public function destroyCycleCount(CycleCount $cycleCount): JsonResponse
    {
        $cycleCount->delete();
        return response()->json(['success' => true, 'message' => 'Cycle count deleted successfully']);
    }
}