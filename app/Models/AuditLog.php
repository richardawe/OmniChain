<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'request_id',
        'session_id',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by table name
     */
    public function scopeByTable($query, $table)
    {
        return $query->where('table_name', $table);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the changes made
     */
    public function getChangesAttribute()
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }
        
        return $changes;
    }

    /**
     * Get human readable action description
     */
    public function getActionDescriptionAttribute()
    {
        $descriptions = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'failed_login' => 'Failed Login Attempt',
            'password_changed' => 'Password Changed',
            'permission_changed' => 'Permissions Changed',
            'role_assigned' => 'Role Assigned',
            'role_removed' => 'Role Removed',
            'export' => 'Data Exported',
            'import' => 'Data Imported',
            'bulk_update' => 'Bulk Update',
            'bulk_delete' => 'Bulk Delete',
            'status_changed' => 'Status Changed',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'sent' => 'Sent',
            'cancelled' => 'Cancelled',
        ];

        return $descriptions[$this->action] ?? ucfirst($this->action);
    }
}
