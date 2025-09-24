<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryTask extends Model
{
    protected $fillable = [
        'task_number',
        'order_fulfillment_id',
        'freight_order_id',
        'route_plan_id',
        'assigned_driver_id',
        'pickup_location_id',
        'delivery_location_id',
        'task_type',
        'priority',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'scheduled_start_time',
        'scheduled_end_time',
        'actual_start_time',
        'actual_end_time',
        'estimated_duration_minutes',
        'actual_duration_minutes',
        'distance_km',
        'task_instructions',
        'delivery_instructions',
        'special_requirements',
        'delivery_contact_info',
        'task_metadata'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'scheduled_start_time' => 'datetime',
        'scheduled_end_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'estimated_duration_minutes' => 'decimal:2',
        'actual_duration_minutes' => 'decimal:2',
        'distance_km' => 'decimal:3',
        'delivery_contact_info' => 'array',
        'task_metadata' => 'array'
    ];

    public function orderFulfillment(): BelongsTo
    {
        return $this->belongsTo(OrderFulfillment::class);
    }

    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_location_id');
    }

    public function deliveryLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'delivery_location_id');
    }

    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    public function routePlan(): BelongsTo
    {
        return $this->belongsTo(RoutePlan::class);
    }
}
