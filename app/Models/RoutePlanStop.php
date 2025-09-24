<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RoutePlanStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_plan_id',
        'sequence_number',
        'location_id',
        'freight_order_id',
        'stop_type',
        'status',
        'planned_arrival_time',
        'planned_departure_time',
        'actual_arrival_time',
        'actual_departure_time',
        'planned_duration_minutes',
        'actual_duration_minutes',
        'distance_from_previous_km',
        'special_instructions',
        'notes',
        'stop_metadata',
    ];

    protected $casts = [
        'planned_arrival_time' => 'datetime',
        'planned_departure_time' => 'datetime',
        'actual_arrival_time' => 'datetime',
        'actual_departure_time' => 'datetime',
        'distance_from_previous_km' => 'decimal:3',
        'stop_metadata' => 'array',
    ];

    /**
     * Get the route plan.
     */
    public function routePlan(): BelongsTo
    {
        return $this->belongsTo(RoutePlan::class);
    }

    /**
     * Get the location.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the freight order.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Get the proof of delivery for this stop.
     */
    public function proofOfDelivery(): HasOne
    {
        return $this->hasOne(ProofOfDelivery::class);
    }

    /**
     * Scope a query to only include stops by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by stop type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('stop_type', $type);
    }

    /**
     * Get the duration of this stop in minutes.
     */
    public function getActualDurationAttribute(): ?int
    {
        if (!$this->actual_arrival_time || !$this->actual_departure_time) {
            return null;
        }

        return $this->actual_arrival_time->diffInMinutes($this->actual_departure_time);
    }

    /**
     * Check if the stop is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the stop is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }
}