<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    /**
     * Log an audit event
     */
    public function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null,
        ?array $metadata = null
    ): AuditLog {
        $request = request();
        $sessionId = null;
        
        try {
            $sessionId = $request?->session()?->getId();
        } catch (\RuntimeException $e) {
            // Session not available (e.g., during seeding)
            $sessionId = null;
        }
        
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'table_name' => $model ? $model->getTable() : null,
            'record_id' => $model ? $model->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'request_id' => $request?->header('X-Request-ID'),
            'session_id' => $sessionId,
            'url' => $request?->fullUrl(),
            'method' => $request?->method(),
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log model creation
     */
    public function logCreated(Model $model, ?string $description = null, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'created',
            $model,
            null,
            $model->getAttributes(),
            $description ?: "Created {$this->getModelName($model)}",
            $metadata
        );
    }

    /**
     * Log model update
     */
    public function logUpdated(Model $model, array $oldValues, ?string $description = null, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'updated',
            $model,
            $oldValues,
            $model->getChanges(),
            $description ?: "Updated {$this->getModelName($model)}",
            $metadata
        );
    }

    /**
     * Log model deletion
     */
    public function logDeleted(Model $model, ?string $description = null, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'deleted',
            $model,
            $model->getAttributes(),
            null,
            $description ?: "Deleted {$this->getModelName($model)}",
            $metadata
        );
    }

    /**
     * Log user login
     */
    public function logLogin(?string $email = null, ?array $metadata = null): AuditLog
    {
        $user = Auth::user();
        $description = $user ? "User {$user->email} logged in" : "Login attempt for {$email}";
        
        return $this->log(
            'login',
            $user,
            null,
            null,
            $description,
            array_merge(['email' => $email ?: $user?->email], $metadata ?: [])
        );
    }

    /**
     * Log user logout
     */
    public function logLogout(?array $metadata = null): AuditLog
    {
        $user = Auth::user();
        
        return $this->log(
            'logout',
            $user,
            null,
            null,
            $user ? "User {$user->email} logged out" : "User logged out",
            $metadata
        );
    }

    /**
     * Log failed login attempt
     */
    public function logFailedLogin(string $email, ?string $reason = null, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'failed_login',
            null,
            null,
            null,
            "Failed login attempt for {$email}" . ($reason ? ": {$reason}" : ""),
            array_merge(['email' => $email, 'reason' => $reason], $metadata ?: [])
        );
    }

    /**
     * Log password change
     */
    public function logPasswordChanged(?Model $user = null, ?array $metadata = null): AuditLog
    {
        $user = $user ?: Auth::user();
        
        return $this->log(
            'password_changed',
            $user,
            null,
            null,
            $user ? "Password changed for {$user->email}" : "Password changed",
            $metadata
        );
    }

    /**
     * Log permission change
     */
    public function logPermissionChanged(Model $model, array $oldPermissions, array $newPermissions, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'permission_changed',
            $model,
            ['permissions' => $oldPermissions],
            ['permissions' => $newPermissions],
            "Permissions changed for {$this->getModelName($model)}",
            $metadata
        );
    }

    /**
     * Log role assignment
     */
    public function logRoleAssigned(Model $user, string $role, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'role_assigned',
            $user,
            null,
            ['role' => $role],
            "Role '{$role}' assigned to {$this->getModelName($user)}",
            $metadata
        );
    }

    /**
     * Log role removal
     */
    public function logRoleRemoved(Model $user, string $role, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'role_removed',
            $user,
            ['role' => $role],
            null,
            "Role '{$role}' removed from {$this->getModelName($user)}",
            $metadata
        );
    }

    /**
     * Log data export
     */
    public function logExport(string $dataType, ?int $recordCount = null, ?array $metadata = null): AuditLog
    {
        $description = "Exported {$dataType}";
        if ($recordCount) {
            $description .= " ({$recordCount} records)";
        }
        
        return $this->log(
            'export',
            null,
            null,
            null,
            $description,
            array_merge(['data_type' => $dataType, 'record_count' => $recordCount], $metadata ?: [])
        );
    }

    /**
     * Log data import
     */
    public function logImport(string $dataType, ?int $recordCount = null, ?array $metadata = null): AuditLog
    {
        $description = "Imported {$dataType}";
        if ($recordCount) {
            $description .= " ({$recordCount} records)";
        }
        
        return $this->log(
            'import',
            null,
            null,
            null,
            $description,
            array_merge(['data_type' => $dataType, 'record_count' => $recordCount], $metadata ?: [])
        );
    }

    /**
     * Log bulk operations
     */
    public function logBulkUpdate(string $table, array $criteria, array $updates, int $affectedCount, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'bulk_update',
            null,
            $criteria,
            $updates,
            "Bulk updated {$affectedCount} records in {$table}",
            array_merge(['table' => $table, 'affected_count' => $affectedCount], $metadata ?: [])
        );
    }

    /**
     * Log bulk deletion
     */
    public function logBulkDelete(string $table, array $criteria, int $deletedCount, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'bulk_delete',
            null,
            $criteria,
            null,
            "Bulk deleted {$deletedCount} records from {$table}",
            array_merge(['table' => $table, 'deleted_count' => $deletedCount], $metadata ?: [])
        );
    }

    /**
     * Log status changes
     */
    public function logStatusChanged(Model $model, string $oldStatus, string $newStatus, ?array $metadata = null): AuditLog
    {
        return $this->log(
            'status_changed',
            $model,
            ['status' => $oldStatus],
            ['status' => $newStatus],
            "Status changed from '{$oldStatus}' to '{$newStatus}' for {$this->getModelName($model)}",
            $metadata
        );
    }

    /**
     * Log approval/rejection
     */
    public function logApproved(Model $model, ?string $reason = null, ?array $metadata = null): AuditLog
    {
        $description = "Approved {$this->getModelName($model)}";
        if ($reason) {
            $description .= ": {$reason}";
        }
        
        return $this->log(
            'approved',
            $model,
            null,
            ['approved_at' => now(), 'reason' => $reason],
            $description,
            $metadata
        );
    }

    public function logRejected(Model $model, ?string $reason = null, ?array $metadata = null): AuditLog
    {
        $description = "Rejected {$this->getModelName($model)}";
        if ($reason) {
            $description .= ": {$reason}";
        }
        
        return $this->log(
            'rejected',
            $model,
            null,
            ['rejected_at' => now(), 'reason' => $reason],
            $description,
            $metadata
        );
    }

    /**
     * Log sensitive operations
     */
    public function logSensitiveOperation(string $operation, ?Model $model = null, ?array $data = null, ?array $metadata = null): AuditLog
    {
        return $this->log(
            $operation,
            $model,
            null,
            $data,
            "Sensitive operation: {$operation}",
            array_merge(['sensitive' => true], $metadata ?: [])
        );
    }

    /**
     * Get human-readable model name
     */
    private function getModelName(Model $model): string
    {
        $className = class_basename($model);
        
        // Convert PascalCase to readable format
        $name = preg_replace('/(?<!^)[A-Z]/', ' $0', $className);
        
        // Add ID if available
        if ($model->getKey()) {
            $name .= " (ID: {$model->getKey()})";
        }
        
        return $name;
    }

    /**
     * Get audit logs for a specific model
     */
    public function getModelAuditLogs(Model $model, ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = AuditLog::where('table_name', $model->getTable())
                         ->where('record_id', $model->getKey())
                         ->with('user')
                         ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Get audit logs for a user
     */
    public function getUserAuditLogs(int $userId, ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = AuditLog::where('user_id', $userId)
                         ->with('user')
                         ->orderBy('created_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Search audit logs
     */
    public function searchAuditLogs(array $filters = [], ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');
        
        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        
        if (isset($filters['table_name'])) {
            $query->where('table_name', $filters['table_name']);
        }
        
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        
        if (isset($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}
