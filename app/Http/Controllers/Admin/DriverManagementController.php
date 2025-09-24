<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DriverManagementController extends Controller
{
    /**
     * Display the driver management dashboard
     */
    public function index()
    {
        return inertia('Admin/DriverManagement');
    }

    /**
     * Get all drivers with their status
     */
    public function getDrivers(Request $request): JsonResponse
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        
        $query = User::where('role', 'driver')
            ->orWhere('driver_license', '!=', null);
            
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('driver_license', 'like', "%{$search}%");
            });
        }
        
        $drivers = $query->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'data' => $drivers
        ]);
    }

    /**
     * Get driver details
     */
    public function getDriverDetails($id): JsonResponse
    {
        $driver = User::where('id', $id)
            ->where(function($query) {
                $query->where('role', 'driver')
                      ->orWhere('driver_license', '!=', null);
            })
            ->first();
            
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $driver
        ]);
    }

    /**
     * Approve a driver
     */
    public function approveDriver(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = User::find($id);
        
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }

        // Update driver status
        $driver->update([
            'status' => 'available',
            'metadata' => array_merge($driver->metadata ?? [], [
                'approval_status' => 'approved',
                'approved_at' => now()->toISOString(),
                'approved_by' => 'admin', // In real app, this would be the admin user ID
                'approval_notes' => $request->notes
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver approved successfully',
            'data' => $driver
        ]);
    }

    /**
     * Reject a driver
     */
    public function rejectDriver(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = User::find($id);
        
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }

        // Update driver status
        $driver->update([
            'status' => 'offline',
            'metadata' => array_merge($driver->metadata ?? [], [
                'approval_status' => 'rejected',
                'rejected_at' => now()->toISOString(),
                'rejected_by' => 'admin', // In real app, this would be the admin user ID
                'rejection_reason' => $request->reason
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver rejected successfully',
            'data' => $driver
        ]);
    }

    /**
     * Get driver statistics
     */
    public function getDriverStats(): JsonResponse
    {
        $stats = [
            'total_drivers' => User::where('role', 'driver')->orWhere('driver_license', '!=', null)->count(),
            'pending_approval' => User::where('status', 'pending_approval')->count(),
            'active_drivers' => User::where('status', 'available')->count(),
            'busy_drivers' => User::where('status', 'busy')->count(),
            'offline_drivers' => User::where('status', 'offline')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
