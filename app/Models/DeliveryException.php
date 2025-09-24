<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryException extends Model
{
    protected $fillable = [
        'delivery_task_id',
        'freight_order_id',
        'driver_id',
        'exception_code',
        'exception_type',
        'severity',
        'status',
        'exception_timestamp',
        'exception_latitude',
        'exception_longitude',
        'exception_location',
        'description',
        'root_cause',
        'resolution_notes',
        'customer_communication',
        'photos_attached',
        'required_actions',
        'resolved_at',
        'resolved_by_user_id',
        'estimated_resolution_time_hours',
        'actual_resolution_time_hours',
        'exception_metadata'
    ];

    protected $casts = [
        'exception_timestamp' => 'datetime',
        'exception_latitude' => 'decimal:8',
        'exception_longitude' => 'decimal:8',
        'photos_attached' => 'array',
        'required_actions' => 'array',
        'resolved_at' => 'datetime',
        'estimated_resolution_time_hours' => 'decimal:2',
        'actual_resolution_time_hours' => 'decimal:2',
        'exception_metadata' => 'array'
    ];

    public function deliveryTask(): BelongsTo
    {
        return $this->belongsTo(DeliveryTask::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function resolvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }
}
