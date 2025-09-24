<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProofOfDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_order_id',
        'route_plan_stop_id',
        'driver_id',
        'delivery_timestamp',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_status',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'recipient_signature',
        'delivery_photo_path',
        'delivery_notes',
        'delivered_quantity',
        'expected_quantity',
        'delivered_weight_kg',
        'delivered_volume_cubic_meters',
        'damage_report',
        'delivery_conditions',
        'failure_reason',
        'rescheduled_date',
        'rescheduled_reason',
        'customer_satisfaction_rating',
        'customer_feedback',
        'pod_metadata',
    ];

    protected $casts = [
        'delivery_timestamp' => 'datetime',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'delivered_weight_kg' => 'decimal:3',
        'delivered_volume_cubic_meters' => 'decimal:3',
        'damage_report' => 'array',
        'delivery_conditions' => 'array',
        'rescheduled_date' => 'datetime',
        'customer_satisfaction_rating' => 'boolean',
        'pod_metadata' => 'array',
    ];

    /**
     * Get the freight order.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Get the route plan stop.
     */
    public function routePlanStop(): BelongsTo
    {
        return $this->belongsTo(RoutePlanStop::class);
    }

    /**
     * Get the driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Scope a query to filter by delivery status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('delivery_status', $status);
    }

    /**
     * Scope a query to filter by driver.
     */
    public function scopeByDriver($query, int $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('delivery_timestamp', [$startDate, $endDate]);
    }

    /**
     * Check if the delivery was successful.
     */
    public function isSuccessful(): bool
    {
        return in_array($this->delivery_status, ['delivered', 'partially_delivered']);
    }

    /**
     * Check if the delivery failed.
     */
    public function isFailed(): bool
    {
        return $this->delivery_status === 'failed';
    }

    /**
     * Check if the delivery was rescheduled.
     */
    public function isRescheduled(): bool
    {
        return $this->delivery_status === 'rescheduled';
    }

    /**
     * Get the delivery completion percentage.
     */
    public function getCompletionPercentageAttribute(): ?float
    {
        if (!$this->expected_quantity || $this->expected_quantity === 0) {
            return null;
        }

        return ($this->delivered_quantity / $this->expected_quantity) * 100;
    }
}