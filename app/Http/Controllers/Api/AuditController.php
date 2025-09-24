<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    /**
     * Get audit logs with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'nullable|string|max:50',
            'table_name' => 'nullable|string|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'ip_address' => 'nullable|ip',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $perPage = $validated['per_page'] ?? 20;
        $page = $validated['page'] ?? 1;

        // Build query
        $query = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if (isset($validated['action'])) {
            $query->where('action', $validated['action']);
        }

        if (isset($validated['table_name'])) {
            $query->where('table_name', $validated['table_name']);
        }

        if (isset($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        if (isset($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        if (isset($validated['ip_address'])) {
            $query->where('ip_address', $validated['ip_address']);
        }

        // Paginate results
        $auditLogs = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $auditLogs->items(),
            'pagination' => [
                'current_page' => $auditLogs->currentPage(),
                'per_page' => $auditLogs->perPage(),
                'total' => $auditLogs->total(),
                'last_page' => $auditLogs->lastPage(),
                'has_more_pages' => $auditLogs->hasMorePages(),
            ]
        ]);
    }

    /**
     * Get audit log details
     */
    public function show(int $id): JsonResponse
    {
        $auditLog = \App\Models\AuditLog::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $auditLog
        ]);
    }

    /**
     * Get audit logs for a specific model
     */
    public function getModelAuditLogs(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'table_name' => 'required|string|max:100',
            'record_id' => 'required|integer',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $auditLogs = \App\Models\AuditLog::where('table_name', $validated['table_name'])
                                       ->where('record_id', $validated['record_id'])
                                       ->with('user')
                                       ->orderBy('created_at', 'desc')
                                       ->limit($validated['limit'] ?? 50)
                                       ->get();

        return response()->json([
            'success' => true,
            'data' => $auditLogs
        ]);
    }

    /**
     * Get audit logs for a specific user
     */
    public function getUserAuditLogs(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'nullable|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = \App\Models\AuditLog::where('user_id', $userId)
                                   ->with('user')
                                   ->orderBy('created_at', 'desc');

        if (isset($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $auditLogs = $query->limit($validated['limit'] ?? 50)->get();

        return response()->json([
            'success' => true,
            'data' => $auditLogs
        ]);
    }

    /**
     * Get audit statistics
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = \App\Models\AuditLog::query();

        if (isset($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $statistics = [
            'total_events' => $query->count(),
            'unique_users' => $query->distinct('user_id')->count('user_id'),
            'unique_ip_addresses' => $query->distinct('ip_address')->count('ip_address'),
            'events_by_action' => $query->groupBy('action')
                                       ->selectRaw('action, COUNT(*) as count')
                                       ->pluck('count', 'action'),
            'events_by_table' => $query->whereNotNull('table_name')
                                      ->groupBy('table_name')
                                      ->selectRaw('table_name, COUNT(*) as count')
                                      ->orderByDesc('count')
                                      ->limit(10)
                                      ->pluck('count', 'table_name'),
            'recent_activity' => $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                      ->groupBy('date')
                                      ->orderByDesc('date')
                                      ->limit(30)
                                      ->pluck('count', 'date'),
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Get available actions for filtering
     */
    public function getActions(): JsonResponse
    {
        $actions = \App\Models\AuditLog::distinct('action')
                                     ->orderBy('action')
                                     ->pluck('action');

        return response()->json([
            'success' => true,
            'data' => $actions
        ]);
    }

    /**
     * Get available tables for filtering
     */
    public function getTables(): JsonResponse
    {
        $tables = \App\Models\AuditLog::whereNotNull('table_name')
                                    ->distinct('table_name')
                                    ->orderBy('table_name')
                                    ->pluck('table_name');

        return response()->json([
            'success' => true,
            'data' => $tables
        ]);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'format' => 'required|string|in:csv,json,xlsx',
            'action' => 'nullable|string|max:50',
            'table_name' => 'nullable|string|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Log the export operation
        $this->auditLogService->logExport(
            'audit_logs',
            null,
            [
                'format' => $validated['format'],
                'filters' => array_filter($validated, fn($key) => $key !== 'format', ARRAY_FILTER_USE_KEY)
            ]
        );

        // Build query with same filters as index method
        $query = \App\Models\AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters (same as index method)
        if (isset($validated['action'])) {
            $query->where('action', $validated['action']);
        }

        if (isset($validated['table_name'])) {
            $query->where('table_name', $validated['table_name']);
        }

        if (isset($validated['user_id'])) {
            $query->where('user_id', $validated['user_id']);
        }

        if (isset($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $auditLogs = $query->get();

        // Generate filename
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.' . $validated['format'];

        // Return file download response based on format
        switch ($validated['format']) {
            case 'json':
                return response()->json([
                    'success' => true,
                    'data' => $auditLogs,
                    'filename' => $filename,
                    'count' => $auditLogs->count()
                ]);

            case 'csv':
                // For CSV, we would typically use a package like league/csv
                // For now, return success with filename
                return response()->json([
                    'success' => true,
                    'message' => 'CSV export prepared',
                    'filename' => $filename,
                    'count' => $auditLogs->count()
                ]);

            case 'xlsx':
                // For Excel, we would typically use a package like PhpSpreadsheet
                // For now, return success with filename
                return response()->json([
                    'success' => true,
                    'message' => 'Excel export prepared',
                    'filename' => $filename,
                    'count' => $auditLogs->count()
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported export format'
                ], 400);
        }
    }
}
