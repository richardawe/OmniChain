<?php

namespace App\Traits;

use App\Services\AuditLogService;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    /**
     * Boot the auditable trait
     */
    protected static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            if ($model->shouldAudit('created')) {
                app(AuditLogService::class)->logCreated($model);
            }
        });

        static::updated(function (Model $model) {
            if ($model->shouldAudit('updated') && $model->wasChanged()) {
                $oldValues = [];
                foreach ($model->getChanges() as $key => $value) {
                    $oldValues[$key] = $model->getOriginal($key);
                }
                
                app(AuditLogService::class)->logUpdated($model, $oldValues);
            }
        });

        static::deleted(function (Model $model) {
            if ($model->shouldAudit('deleted')) {
                app(AuditLogService::class)->logDeleted($model);
            }
        });
    }

    /**
     * Determine if the model should be audited for the given action
     */
    public function shouldAudit(string $action): bool
    {
        // Check if auditing is globally disabled
        if (!config('audit.enabled', true)) {
            return false;
        }

        // Check model-specific audit configuration
        if (property_exists($this, 'auditEvents')) {
            return in_array($action, $this->auditEvents);
        }

        // Default: audit all events for models with this trait
        return true;
    }

    /**
     * Get the audit logs for this model
     */
    public function auditLogs()
    {
        return app(AuditLogService::class)->getModelAuditLogs($this);
    }

    /**
     * Get recent audit logs for this model
     */
    public function recentAuditLogs(int $limit = 10)
    {
        return app(AuditLogService::class)->getModelAuditLogs($this, $limit);
    }

    /**
     * Log a custom audit event for this model
     */
    public function auditLog(string $action, ?array $oldValues = null, ?array $newValues = null, ?string $description = null, ?array $metadata = null): void
    {
        app(AuditLogService::class)->log(
            $action,
            $this,
            $oldValues,
            $newValues,
            $description,
            $metadata
        );
    }

    /**
     * Log status change for this model
     */
    public function auditStatusChange(string $oldStatus, string $newStatus, ?array $metadata = null): void
    {
        app(AuditLogService::class)->logStatusChanged($this, $oldStatus, $newStatus, $metadata);
    }

    /**
     * Log approval for this model
     */
    public function auditApproval(?string $reason = null, ?array $metadata = null): void
    {
        app(AuditLogService::class)->logApproved($this, $reason, $metadata);
    }

    /**
     * Log rejection for this model
     */
    public function auditRejection(?string $reason = null, ?array $metadata = null): void
    {
        app(AuditLogService::class)->logRejected($this, $reason, $metadata);
    }
}
