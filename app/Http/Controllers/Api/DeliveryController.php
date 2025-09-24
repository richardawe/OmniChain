<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderFulfillment;
use App\Models\OrderFulfillmentLineItem;
use App\Models\DeliveryTask;
use App\Models\GeofenceEvent;
use App\Models\CustomerNotification;
use App\Models\DeliveryException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    /**
     * Get order fulfillments with filtering
     */
    public function getOrderFulfillments(Request $request): JsonResponse
    {
        $query = OrderFulfillment::with(['customerCompany', 'fulfillmentCenter', 'assignedWarehouseUser']);

        // Apply filters
        if ($request->filled('customer_company_id')) {
            $query->where('customer_company_id', $request->customer_company_id);
        }

        if ($request->filled('fulfillment_center_id')) {
            $query->where('fulfillment_center_id', $request->fulfillment_center_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customerCompany', function ($subQuery) use ($request) {
                      $subQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orderFulfillments = $query->orderBy('order_date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $orderFulfillments
        ]);
    }

    /**
     * Get delivery tasks with filtering
     */
    public function getDeliveryTasks(Request $request): JsonResponse
    {
        $query = DeliveryTask::with([
            'orderFulfillment.customerCompany',
            'freightOrder',
            'routePlan',
            'assignedDriver',
            'pickupLocation',
            'deliveryLocation'
        ]);

        // Apply filters
        if ($request->filled('assigned_driver_id')) {
            $query->where('assigned_driver_id', $request->assigned_driver_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('task_type')) {
            $query->where('task_type', $request->task_type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $deliveryTasks = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $deliveryTasks
        ]);
    }

    /**
     * Get delivery summary statistics
     */
    public function getDeliverySummary(): JsonResponse
    {
        $summary = [
            'order_fulfillments' => [
                'total' => OrderFulfillment::count(),
                'received' => OrderFulfillment::where('status', 'received')->count(),
                'processing' => OrderFulfillment::where('status', 'processing')->count(),
                'delivered' => OrderFulfillment::where('status', 'delivered')->count(),
            ],
            'delivery_tasks' => [
                'total' => DeliveryTask::count(),
                'pending' => DeliveryTask::where('status', 'pending')->count(),
                'in_progress' => DeliveryTask::where('status', 'in_progress')->count(),
                'completed' => DeliveryTask::where('status', 'completed')->count(),
            ],
            'customer_notifications' => [
                'total' => CustomerNotification::count(),
                'sent' => CustomerNotification::where('status', 'sent')->count(),
                'delivered' => CustomerNotification::where('status', 'delivered')->count(),
            ],
            'delivery_exceptions' => [
                'total' => DeliveryException::count(),
                'open' => DeliveryException::where('status', 'open')->count(),
                'resolved' => DeliveryException::where('status', 'resolved')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get customer notifications with filtering
     */
    public function getCustomerNotifications(Request $request): JsonResponse
    {
        $query = CustomerNotification::query();

        // Apply filters
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('notification_type')) {
            $query->where('notification_type', $request->notification_type);
        }

        if ($request->filled('date_from')) {
            $query->where('sent_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('sent_at', '<=', $request->date_to);
        }

        $notifications = $query->orderBy('sent_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Get a specific delivery task by ID
     */
    public function showDeliveryTask(string $id): JsonResponse
    {
        $task = DeliveryTask::with([
            'orderFulfillment.customerCompany',
            'assignedDriver',
            'pickupLocation',
            'deliveryLocation',
            'freightOrder',
            'routePlan'
        ])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    // CRUD Operations for Order Fulfillments
    public function storeOrderFulfillment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:50|unique:order_fulfillments,order_number',
            'customer_company_id' => 'required|integer|exists:companies,id',
            'fulfillment_center_id' => 'required|integer|exists:locations,id',
            'order_type' => 'required|string|in:sales_order,transfer_order,return_order,service_order',
            'status' => 'required|string|in:received,processing,picked,packed,shipped,delivered,cancelled',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'order_date' => 'required|date',
            'required_delivery_date' => 'nullable|date|after:order_date',
            'actual_delivery_date' => 'nullable|date',
            'total_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'assigned_warehouse_user_id' => 'nullable|integer|exists:users,id',
            'order_notes' => 'nullable|string|max:1000',
            'order_metadata' => 'nullable|array',
        ]);

        $fulfillment = OrderFulfillment::create($validated);
        $fulfillment->load(['customerCompany', 'fulfillmentCenter', 'assignedWarehouseUser']);

        return response()->json(['success' => true, 'data' => $fulfillment], 201);
    }

    public function showOrderFulfillment(OrderFulfillment $orderFulfillment): JsonResponse
    {
        $orderFulfillment->load(['customerCompany', 'fulfillmentCenter', 'assignedWarehouseUser']);
        return response()->json(['success' => true, 'data' => $orderFulfillment]);
    }

    public function updateOrderFulfillment(Request $request, OrderFulfillment $orderFulfillment): JsonResponse
    {
        $validated = $request->validate([
            'order_number' => 'sometimes|string|max:50|unique:order_fulfillments,order_number,' . $orderFulfillment->id,
            'customer_company_id' => 'sometimes|integer|exists:companies,id',
            'fulfillment_center_id' => 'sometimes|integer|exists:locations,id',
            'order_type' => 'sometimes|string|in:sales_order,transfer_order,return_order,service_order',
            'status' => 'sometimes|string|in:received,processing,picked,packed,shipped,delivered,cancelled',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
            'order_date' => 'sometimes|date',
            'required_delivery_date' => 'nullable|date|after:order_date',
            'actual_delivery_date' => 'nullable|date',
            'total_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'assigned_warehouse_user_id' => 'nullable|integer|exists:users,id',
            'order_notes' => 'nullable|string|max:1000',
            'order_metadata' => 'nullable|array',
        ]);

        $orderFulfillment->update($validated);
        $orderFulfillment->load(['customerCompany', 'fulfillmentCenter', 'assignedWarehouseUser']);

        return response()->json(['success' => true, 'data' => $orderFulfillment]);
    }

    public function destroyOrderFulfillment(OrderFulfillment $orderFulfillment): JsonResponse
    {
        $orderFulfillment->delete();
        return response()->json(['success' => true, 'message' => 'Order fulfillment deleted successfully']);
    }

    // CRUD Operations for Order Fulfillment Line Items
    public function storeOrderFulfillmentLineItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_fulfillment_id' => 'required|integer|exists:order_fulfillments,id',
            'product_id' => 'required|integer|exists:products,id',
            'line_number' => 'required|integer|min:1',
            'quantity_ordered' => 'required|integer|min:1',
            'quantity_fulfilled' => 'nullable|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_line_amount' => 'nullable|numeric|min:0',
            'line_notes' => 'nullable|string|max:500',
            'line_metadata' => 'nullable|array',
        ]);

        $lineItem = OrderFulfillmentLineItem::create($validated);
        $lineItem->load(['orderFulfillment', 'product']);

        return response()->json(['success' => true, 'data' => $lineItem], 201);
    }

    public function showOrderFulfillmentLineItem(OrderFulfillmentLineItem $orderFulfillmentLineItem): JsonResponse
    {
        $orderFulfillmentLineItem->load(['orderFulfillment', 'product']);
        return response()->json(['success' => true, 'data' => $orderFulfillmentLineItem]);
    }

    public function updateOrderFulfillmentLineItem(Request $request, OrderFulfillmentLineItem $orderFulfillmentLineItem): JsonResponse
    {
        $validated = $request->validate([
            'order_fulfillment_id' => 'sometimes|integer|exists:order_fulfillments,id',
            'product_id' => 'sometimes|integer|exists:products,id',
            'line_number' => 'sometimes|integer|min:1',
            'quantity_ordered' => 'sometimes|integer|min:1',
            'quantity_fulfilled' => 'nullable|integer|min:0',
            'unit_price' => 'sometimes|numeric|min:0',
            'total_line_amount' => 'nullable|numeric|min:0',
            'line_notes' => 'nullable|string|max:500',
            'line_metadata' => 'nullable|array',
        ]);

        $orderFulfillmentLineItem->update($validated);
        $orderFulfillmentLineItem->load(['orderFulfillment', 'product']);

        return response()->json(['success' => true, 'data' => $orderFulfillmentLineItem]);
    }

    public function destroyOrderFulfillmentLineItem(OrderFulfillmentLineItem $orderFulfillmentLineItem): JsonResponse
    {
        $orderFulfillmentLineItem->delete();
        return response()->json(['success' => true, 'message' => 'Order fulfillment line item deleted successfully']);
    }

    // CRUD Operations for Delivery Tasks
    public function storeDeliveryTask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_fulfillment_id' => 'required|integer|exists:order_fulfillments,id',
            'freight_order_id' => 'nullable|integer|exists:freight_orders,id',
            'route_plan_id' => 'nullable|integer|exists:route_plans,id',
            'task_type' => 'required|string|in:pickup,delivery,transfer,inspection,maintenance',
            'status' => 'required|string|in:pending,assigned,in_progress,completed,cancelled',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'assigned_driver_id' => 'nullable|integer|exists:users,id',
            'pickup_location_id' => 'nullable|integer|exists:locations,id',
            'delivery_location_id' => 'nullable|integer|exists:locations,id',
            'scheduled_start_time' => 'required|date',
            'scheduled_end_time' => 'required|date|after:scheduled_start_time',
            'actual_start_time' => 'nullable|date',
            'actual_end_time' => 'nullable|date|after:actual_start_time',
            'task_notes' => 'nullable|string|max:1000',
            'task_metadata' => 'nullable|array',
        ]);

        $task = DeliveryTask::create($validated);
        $task->load(['orderFulfillment.customerCompany', 'freightOrder', 'routePlan', 'assignedDriver', 'pickupLocation', 'deliveryLocation']);

        return response()->json(['success' => true, 'data' => $task], 201);
    }

    public function updateDeliveryTask(Request $request, DeliveryTask $deliveryTask): JsonResponse
    {
        $validated = $request->validate([
            'order_fulfillment_id' => 'sometimes|integer|exists:order_fulfillments,id',
            'freight_order_id' => 'nullable|integer|exists:freight_orders,id',
            'route_plan_id' => 'nullable|integer|exists:route_plans,id',
            'task_type' => 'sometimes|string|in:pickup,delivery,transfer,inspection,maintenance',
            'status' => 'sometimes|string|in:pending,assigned,in_progress,completed,cancelled',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
            'assigned_driver_id' => 'nullable|integer|exists:users,id',
            'pickup_location_id' => 'nullable|integer|exists:locations,id',
            'delivery_location_id' => 'nullable|integer|exists:locations,id',
            'scheduled_start_time' => 'sometimes|date',
            'scheduled_end_time' => 'sometimes|date|after:scheduled_start_time',
            'actual_start_time' => 'nullable|date',
            'actual_end_time' => 'nullable|date|after:actual_start_time',
            'task_notes' => 'nullable|string|max:1000',
            'task_metadata' => 'nullable|array',
        ]);

        $deliveryTask->update($validated);
        $deliveryTask->load(['orderFulfillment.customerCompany', 'freightOrder', 'routePlan', 'assignedDriver', 'pickupLocation', 'deliveryLocation']);

        return response()->json(['success' => true, 'data' => $deliveryTask]);
    }

    public function destroyDeliveryTask(DeliveryTask $deliveryTask): JsonResponse
    {
        $deliveryTask->delete();
        return response()->json(['success' => true, 'message' => 'Delivery task deleted successfully']);
    }

    // CRUD Operations for Customer Notifications
    public function storeCustomerNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_company_id' => 'required|integer|exists:companies,id',
            'order_fulfillment_id' => 'nullable|integer|exists:order_fulfillments,id',
            'delivery_task_id' => 'nullable|integer|exists:delivery_tasks,id',
            'notification_type' => 'required|string|in:order_confirmation,shipping_update,delivery_scheduled,delivery_completed,exception_alert',
            'channel' => 'required|string|in:email,sms,push,webhook',
            'status' => 'required|string|in:pending,sent,delivered,failed',
            'subject' => 'required|string|max:200',
            'message_content' => 'required|string|max:2000',
            'sent_at' => 'nullable|date',
            'delivered_at' => 'nullable|date|after:sent_at',
            'notification_metadata' => 'nullable|array',
        ]);

        $notification = CustomerNotification::create($validated);
        return response()->json(['success' => true, 'data' => $notification], 201);
    }

    public function showCustomerNotification(CustomerNotification $customerNotification): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $customerNotification]);
    }

    public function updateCustomerNotification(Request $request, CustomerNotification $customerNotification): JsonResponse
    {
        $validated = $request->validate([
            'customer_company_id' => 'sometimes|integer|exists:companies,id',
            'order_fulfillment_id' => 'nullable|integer|exists:order_fulfillments,id',
            'delivery_task_id' => 'nullable|integer|exists:delivery_tasks,id',
            'notification_type' => 'sometimes|string|in:order_confirmation,shipping_update,delivery_scheduled,delivery_completed,exception_alert',
            'channel' => 'sometimes|string|in:email,sms,push,webhook',
            'status' => 'sometimes|string|in:pending,sent,delivered,failed',
            'subject' => 'sometimes|string|max:200',
            'message_content' => 'sometimes|string|max:2000',
            'sent_at' => 'nullable|date',
            'delivered_at' => 'nullable|date|after:sent_at',
            'notification_metadata' => 'nullable|array',
        ]);

        $customerNotification->update($validated);
        return response()->json(['success' => true, 'data' => $customerNotification]);
    }

    public function destroyCustomerNotification(CustomerNotification $customerNotification): JsonResponse
    {
        $customerNotification->delete();
        return response()->json(['success' => true, 'message' => 'Customer notification deleted successfully']);
    }

    // CRUD Operations for Delivery Exceptions
    public function storeDeliveryException(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'delivery_task_id' => 'required|integer|exists:delivery_tasks,id',
            'exception_type' => 'required|string|in:delay,damage,missing,access_denied,weather,vehicle_breakdown,driver_unavailable',
            'status' => 'required|string|in:open,investigating,resolved,closed',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'description' => 'required|string|max:1000',
            'reported_by_user_id' => 'required|integer|exists:users,id',
            'assigned_to_user_id' => 'nullable|integer|exists:users,id',
            'reported_at' => 'required|date',
            'resolved_at' => 'nullable|date|after:reported_at',
            'resolution_notes' => 'nullable|string|max:1000',
            'exception_metadata' => 'nullable|array',
        ]);

        $exception = DeliveryException::create($validated);
        $exception->load(['deliveryTask', 'reportedByUser', 'assignedToUser']);

        return response()->json(['success' => true, 'data' => $exception], 201);
    }

    public function showDeliveryException(DeliveryException $deliveryException): JsonResponse
    {
        $deliveryException->load(['deliveryTask', 'reportedByUser', 'assignedToUser']);
        return response()->json(['success' => true, 'data' => $deliveryException]);
    }

    public function updateDeliveryException(Request $request, DeliveryException $deliveryException): JsonResponse
    {
        $validated = $request->validate([
            'delivery_task_id' => 'sometimes|integer|exists:delivery_tasks,id',
            'exception_type' => 'sometimes|string|in:delay,damage,missing,access_denied,weather,vehicle_breakdown,driver_unavailable',
            'status' => 'sometimes|string|in:open,investigating,resolved,closed',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
            'description' => 'sometimes|string|max:1000',
            'reported_by_user_id' => 'sometimes|integer|exists:users,id',
            'assigned_to_user_id' => 'nullable|integer|exists:users,id',
            'reported_at' => 'sometimes|date',
            'resolved_at' => 'nullable|date|after:reported_at',
            'resolution_notes' => 'nullable|string|max:1000',
            'exception_metadata' => 'nullable|array',
        ]);

        $deliveryException->update($validated);
        $deliveryException->load(['deliveryTask', 'reportedByUser', 'assignedToUser']);

        return response()->json(['success' => true, 'data' => $deliveryException]);
    }

    public function destroyDeliveryException(DeliveryException $deliveryException): JsonResponse
    {
        $deliveryException->delete();
        return response()->json(['success' => true, 'message' => 'Delivery exception deleted successfully']);
    }

    // CRUD Operations for Geofence Events
    public function storeGeofenceEvent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'delivery_task_id' => 'required|integer|exists:delivery_tasks,id',
            'event_type' => 'required|string|in:entry,exit,proximity',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
            'event_timestamp' => 'required|date',
            'event_metadata' => 'nullable|array',
        ]);

        $event = GeofenceEvent::create($validated);
        $event->load(['deliveryTask']);

        return response()->json(['success' => true, 'data' => $event], 201);
    }

    public function showGeofenceEvent(GeofenceEvent $geofenceEvent): JsonResponse
    {
        $geofenceEvent->load(['deliveryTask']);
        return response()->json(['success' => true, 'data' => $geofenceEvent]);
    }

    public function updateGeofenceEvent(Request $request, GeofenceEvent $geofenceEvent): JsonResponse
    {
        $validated = $request->validate([
            'delivery_task_id' => 'sometimes|integer|exists:delivery_tasks,id',
            'event_type' => 'sometimes|string|in:entry,exit,proximity',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
            'event_timestamp' => 'sometimes|date',
            'event_metadata' => 'nullable|array',
        ]);

        $geofenceEvent->update($validated);
        $geofenceEvent->load(['deliveryTask']);

        return response()->json(['success' => true, 'data' => $geofenceEvent]);
    }

    public function destroyGeofenceEvent(GeofenceEvent $geofenceEvent): JsonResponse
    {
        $geofenceEvent->delete();
        return response()->json(['success' => true, 'message' => 'Geofence event deleted successfully']);
    }
}