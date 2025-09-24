<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FreightOrder;
use App\Models\ShipmentEvent;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Driver login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // For demo purposes, accept any email/password combination
        // In production, this would use proper authentication
        $credentials = $request->only('email', 'password');
        
        // Create a mock token for demo
        $token = 'demo_token_' . time() . '_' . rand(1000, 9999);
        
        // Mock driver data
        $driver = [
            'id' => 2,
            'name' => 'Test Driver',
            'email' => $credentials['email'],
            'phone' => '+1-555-0123',
            'driver_license' => 'DL123456',
            'vehicle_type' => 'truck',
            'max_capacity' => 5000,
            'status' => 'active',
            'last_location' => null,
            'created_at' => now()->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $driver,
                'expires_at' => now()->addDays(7)->toISOString()
            ]
        ]);
    }

    /**
     * Driver registration
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'driverLicense' => 'required|string|max:50',
            'licenseState' => 'required|string|max:2',
            'licenseExpiry' => 'required|date|after:today',
            'vehicleType' => 'required|string|in:car,van,truck,motorcycle,bicycle',
            'vehicleYear' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'vehicleMake' => 'required|string|max:100',
            'vehicleModel' => 'required|string|max:100',
            'licensePlate' => 'required|string|max:20',
            'maxCapacity' => 'required|integer|min:1|max:10000',
            'password' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:password',
            'acceptTerms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new driver user
            $driver = User::create([
                'name' => $request->firstName . ' ' . $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'role' => 'driver',
                'status' => 'pending_approval', // New drivers need approval
                'driver_license' => $request->driverLicense,
                'license_state' => $request->licenseState,
                'license_expiry' => $request->licenseExpiry,
                'vehicle_type' => $request->vehicleType,
                'vehicle_year' => $request->vehicleYear,
                'vehicle_make' => $request->vehicleMake,
                'vehicle_model' => $request->vehicleModel,
                'license_plate' => $request->licensePlate,
                'max_capacity' => $request->maxCapacity,
                'metadata' => [
                    'registration_date' => now()->toISOString(),
                    'approval_status' => 'pending',
                    'documents_uploaded' => false,
                    'background_check' => 'pending'
                ]
            ]);

            // Create a mock token for demo
            $token = 'demo_token_' . time() . '_' . rand(1000, 9999);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Your account is pending approval.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $driver->id,
                        'name' => $driver->name,
                        'email' => $driver->email,
                        'phone' => $driver->phone,
                        'driver_license' => $driver->driver_license,
                        'vehicle_type' => $driver->vehicle_type,
                        'max_capacity' => $driver->max_capacity,
                        'status' => $driver->status,
                        'created_at' => $driver->created_at->toISOString(),
                    ],
                    'expires_at' => now()->addDays(7)->toISOString(),
                    'requires_approval' => true
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Driver logout
     */
    public function logout(): JsonResponse
    {
        // In production, this would invalidate the token
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get driver profile
     */
    public function getProfile(): JsonResponse
    {
        $driver = Auth::user();
        
        // For testing without authentication, return mock driver data
        if (!$driver) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => 2,
                    'name' => 'Test Driver',
                    'email' => 'driver@test.com',
                    'phone' => '+1-555-0123',
                    'driver_license' => 'DL123456',
                    'vehicle_type' => 'truck',
                    'max_capacity' => 5000,
                    'status' => 'active',
                    'last_location' => null,
                    'created_at' => now()->toISOString(),
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'email' => $driver->email,
                'phone' => $driver->phone,
                'driver_license' => $driver->driver_license,
                'vehicle_type' => $driver->vehicle_type,
                'max_capacity' => $driver->max_capacity,
                'status' => $driver->status,
                'last_location' => $driver->last_location,
                'created_at' => $driver->created_at,
            ]
        ]);
    }

    /**
     * Update driver profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'driver_license' => 'sometimes|string|max:50',
            'vehicle_type' => 'sometimes|string|in:van,truck,car,motorcycle',
            'max_capacity' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|string|in:available,busy,offline',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::user();
        $driver->update($request->only([
            'name', 'phone', 'driver_license', 'vehicle_type', 'max_capacity', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $driver->fresh()
        ]);
    }

    /**
     * Get assigned tasks and available orders for driver
     */
    public function getTasks(Request $request): JsonResponse
    {
        $driver = Auth::user();
        $status = $request->get('status', 'all');
        
        // For testing without authentication, use driver ID 2 (from seeded data)
        $driverId = $driver ? $driver->id : 2;
        
        // Get assigned tasks
        $assignedQuery = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation'
        ])->where('assigned_driver_id', $driverId);

        if ($status !== 'all') {
            $assignedQuery->where('status', $status);
        }

        $assignedTasks = $assignedQuery->orderBy('created_at', 'desc')->get();

        // Get available orders (unassigned and in bookable status)
        $availableOrders = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation'
        ])->whereNull('assigned_driver_id')
          ->whereIn('status', ['booked', 'quoted'])
          ->orderBy('priority', 'desc') // Show urgent orders first
          ->orderBy('created_at', 'desc')
          ->get();

        // Format assigned tasks
        $formattedAssignedTasks = $assignedTasks->map(function ($task) {
            return $this->formatTaskData($task, true);
        });

        // Format available orders
        $formattedAvailableOrders = $availableOrders->map(function ($task) {
            return $this->formatTaskData($task, false);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'assigned_tasks' => $formattedAssignedTasks,
                'available_orders' => $formattedAvailableOrders
            ]
        ]);
    }

    /**
     * Format task data for API response
     */
    private function formatTaskData($task, $isAssigned = true) {
        return [
            'id' => $task->id,
            'order_number' => $task->order_number,
            'status' => $task->status,
            'priority' => $task->priority,
            'service_type' => $task->service_type,
            'is_assigned' => $isAssigned,
            'pickup_location' => [
                'id' => $task->originLocation->id,
                'name' => $task->originLocation->name,
                'address' => $task->originLocation->address,
                'city' => $task->originLocation->city,
                'state' => $task->originLocation->state,
                'latitude' => $task->originLocation->latitude,
                'longitude' => $task->originLocation->longitude,
                'contact_phone' => $task->originLocation->contact_phone,
                'pickup_instructions' => $task->pickup_instructions,
            ],
            'delivery_location' => [
                'id' => $task->destinationLocation->id,
                'name' => $task->destinationLocation->name,
                'address' => $task->destinationLocation->address,
                'city' => $task->destinationLocation->city,
                'state' => $task->destinationLocation->state,
                'latitude' => $task->destinationLocation->latitude,
                'longitude' => $task->destinationLocation->longitude,
                'contact_phone' => $task->destinationLocation->contact_phone,
                'delivery_instructions' => $task->delivery_instructions,
            ],
            'cargo' => [
                'weight' => $task->total_weight,
                'volume' => $task->total_volume,
                'description' => $task->cargo_description,
                'value' => $task->declared_value,
            ],
            'timing' => [
                'pickup_date' => $task->pickup_date,
                'delivery_date' => $task->delivery_date,
                'requested_pickup_time' => $task->requested_pickup_time,
                'requested_delivery_time' => $task->requested_delivery_time,
            ],
            'created_at' => $task->created_at,
            'updated_at' => $task->updated_at,
        ];
    }

    /**
     * Claim an available order
     */
    public function claimOrder(Request $request, $orderId): JsonResponse
    {
        $driver = Auth::user();
        
        // For testing without authentication, use driver ID 2 (from seeded data)
        $driverId = $driver ? $driver->id : 2;
        
        $order = FreightOrder::whereNull('assigned_driver_id')
            ->whereIn('status', ['booked', 'quoted'])
            ->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not available for claiming'
            ], 404);
        }

        $order->assigned_driver_id = $driverId;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order claimed successfully',
            'data' => $this->formatTaskData($order->load(['originLocation', 'destinationLocation', 'shipperCompany']), true)
        ]);
    }

    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request, $taskId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:picked_up,in_transit,delivered',
            'location' => 'sometimes|array',
            'location.latitude' => 'required_with:location|numeric',
            'location.longitude' => 'required_with:location|numeric',
            'notes' => 'sometimes|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::user();
        $driverId = $driver ? $driver->id : 2;
        
        $task = FreightOrder::where('id', $taskId)
            ->where('assigned_driver_id', $driverId)
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or not assigned to you'
            ], 404);
        }

        $oldStatus = $task->status;
        $task->update(['status' => $request->status]);

        // Create shipment event
        $eventData = [
            'event_type' => $request->status,
            'description' => $this->getStatusDescription($request->status),
            'location_name' => $request->location ? 'Current Location' : 'Unknown',
            'latitude' => $request->location['latitude'] ?? null,
            'longitude' => $request->location['longitude'] ?? null,
            'notes' => $request->notes,
        ];

        ShipmentEvent::create([
            'freight_order_id' => $task->id,
            'event_type' => $request->status,
            'event_time' => now(),
            'description' => $eventData['description'],
            'location_name' => $eventData['location_name'],
            'latitude' => $eventData['latitude'],
            'longitude' => $eventData['longitude'],
            'notes' => $eventData['notes'],
            'created_by' => $driver->id,
        ]);

        // Update driver location if provided
        if ($request->location) {
            $driver->update([
                'last_location' => json_encode($request->location),
                'last_location_updated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => [
                'task_id' => $task->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'event_created' => true
            ]
        ]);
    }

    /**
     * Update driver location
     */
    public function updateLocation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'sometimes|numeric|min:0',
            'heading' => 'sometimes|numeric|between:0,360',
            'speed' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::user();
        $driverId = $driver ? $driver->id : 2;
        
        $locationData = $request->only(['latitude', 'longitude', 'accuracy', 'heading', 'speed']);
        $locationData['timestamp'] = now()->toISOString();

        if ($driver) {
            $driver->update([
                'last_location' => json_encode($locationData),
                'last_location_updated_at' => now()
            ]);
        } else {
            // For testing without authentication, update the test driver (ID 2)
            $testDriver = User::find(2);
            if ($testDriver) {
                $testDriver->update([
                    'last_location' => json_encode($locationData),
                    'last_location_updated_at' => now()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $locationData
        ]);
    }

    /**
     * Get active driver locations (for dashboard)
     */
    public function getActiveDriverLocations(): JsonResponse
    {
        $activeDrivers = User::whereNotNull('last_location')
            ->where('last_location_updated_at', '>', now()->subHours(2)) // Only drivers active in last 2 hours
            ->with(['assignedFreightOrders' => function($query) {
                $query->whereIn('status', ['picked_up', 'in_transit'])
                      ->with(['originLocation', 'destinationLocation', 'shipperCompany']);
            }])
            ->get();

        $driverData = $activeDrivers->map(function($driver) {
            $locationData = json_decode($driver->last_location, true);
            
            return [
                'driver_id' => $driver->id,
                'driver_name' => $driver->name,
                'driver_phone' => $driver->phone,
                'vehicle_type' => $driver->vehicle_type,
                'status' => $driver->status,
                'location' => $locationData,
                'last_updated' => $driver->last_location_updated_at,
                'active_tasks' => $driver->assignedFreightOrders->map(function($order) {
                    return [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'priority' => $order->priority,
                        'service_type' => $order->service_type,
                        'origin' => [
                            'name' => $order->originLocation->name,
                            'address' => $order->originLocation->address,
                            'city' => $order->originLocation->city,
                            'state' => $order->originLocation->state,
                            'latitude' => $order->originLocation->latitude,
                            'longitude' => $order->originLocation->longitude,
                        ],
                        'destination' => [
                            'name' => $order->destinationLocation->name,
                            'address' => $order->destinationLocation->address,
                            'city' => $order->destinationLocation->city,
                            'state' => $order->destinationLocation->state,
                            'latitude' => $order->destinationLocation->latitude,
                            'longitude' => $order->destinationLocation->longitude,
                        ],
                        'cargo' => [
                            'weight' => $order->total_weight,
                            'volume' => $order->total_volume,
                            'description' => $order->cargo_description,
                        ]
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $driverData
        ]);
    }

    /**
     * Submit proof of delivery
     */
    public function submitProofOfDelivery(Request $request, $taskId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'delivery_photo' => 'required|file|image|max:5120', // 5MB max
            'signature' => 'sometimes|string',
            'delivery_notes' => 'sometimes|string|max:1000',
            'recipient_name' => 'sometimes|string|max:255',
            'recipient_phone' => 'sometimes|string|max:20',
            'delivery_confirmed' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::user();
        $driverId = $driver ? $driver->id : 2;
        
        $task = FreightOrder::where('id', $taskId)
            ->where('assigned_driver_id', $driverId)
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or not assigned to you'
            ], 404);
        }

        // Store delivery photo
        $photoPath = $request->file('delivery_photo')->store(
            "delivery-proofs/{$task->id}",
            'public'
        );

        // Store signature if provided
        $signaturePath = null;
        if ($request->signature) {
            $signatureData = $request->signature;
            $signaturePath = "signatures/{$task->id}/" . uniqid() . '.png';
            
            // Convert base64 signature to file
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signatureBinary = base64_decode($signatureData);
            
            Storage::disk('public')->put($signaturePath, $signatureBinary);
        }

        // Update task status to delivered
        $task->update([
            'status' => 'delivered',
            'delivery_date' => now(),
            'delivery_photo_path' => $photoPath,
            'signature_path' => $signaturePath,
            'delivery_notes' => $request->delivery_notes,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
        ]);

        // Create delivery event
        ShipmentEvent::create([
            'freight_order_id' => $task->id,
            'event_type' => 'delivered',
            'event_time' => now(),
            'description' => 'Package delivered successfully',
            'location_name' => $task->destinationLocation->name,
            'latitude' => $task->destinationLocation->latitude,
            'longitude' => $task->destinationLocation->longitude,
            'notes' => $request->delivery_notes,
            'created_by' => $driver->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proof of delivery submitted successfully',
            'data' => [
                'task_id' => $task->id,
                'delivery_photo_path' => $photoPath,
                'signature_path' => $signaturePath,
                'delivery_confirmed' => $request->delivery_confirmed,
                'delivered_at' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Get task details
     */
    public function getTaskDetails($taskId): JsonResponse
    {
        $driver = Auth::user();
        $driverId = $driver ? $driver->id : 2;
        
        $task = FreightOrder::with([
            'shipperCompany',
            'carrierCompany',
            'originLocation',
            'destinationLocation',
            'shipmentEvents' => function ($query) {
                $query->orderBy('event_time', 'desc');
            }
        ])->where('id', $taskId)
          ->where('assigned_driver_id', $driverId)
          ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or not assigned to you'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $task->id,
                'order_number' => $task->order_number,
                'status' => $task->status,
                'priority' => $task->priority,
                'service_type' => $task->service_type,
                'pickup_location' => $task->originLocation,
                'delivery_location' => $task->destinationLocation,
                'cargo' => [
                    'weight' => $task->total_weight,
                    'volume' => $task->total_volume,
                    'description' => $task->cargo_description,
                    'value' => $task->declared_value,
                ],
                'timing' => [
                    'pickup_date' => $task->pickup_date,
                    'delivery_date' => $task->delivery_date,
                    'requested_pickup_time' => $task->requested_pickup_time,
                    'requested_delivery_time' => $task->requested_delivery_time,
                ],
                'events' => $task->shipmentEvents->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'event_type' => $event->event_type,
                        'event_time' => $event->event_time,
                        'description' => $event->description,
                        'location_name' => $event->location_name,
                        'notes' => $event->notes,
                    ];
                }),
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ]
        ]);
    }

    /**
     * Get status description
     */
    private function getStatusDescription($status): string
    {
        $descriptions = [
            'picked_up' => 'Package picked up from origin location',
            'in_transit' => 'Package in transit to destination',
            'delivered' => 'Package delivered to destination',
        ];

        return $descriptions[$status] ?? 'Status updated';
    }
}
