<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContainerTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_order_id',
        'container_number',
        'seal_number',
        'container_type',
        'container_size',
        'container_owner',
        'container_operator',
        'max_gross_weight_kg',
        'tare_weight_kg',
        'payload_weight_kg',
        'max_volume_cubic_meters',
        'temperature_settings',
        'vessel_name',
        'voyage_number',
        'booking_reference',
        'status',
        'loaded_date',
        'departure_date',
        'arrival_date',
        'discharge_date',
        'delivery_date',
        'return_date',
        'current_location',
        'current_latitude',
        'current_longitude',
        'last_update_timestamp',
        'container_metadata',
    ];

    protected $casts = [
        'max_gross_weight_kg' => 'decimal:3',
        'tare_weight_kg' => 'decimal:3',
        'payload_weight_kg' => 'decimal:3',
        'max_volume_cubic_meters' => 'decimal:3',
        'temperature_settings' => 'array',
        'loaded_date' => 'datetime',
        'departure_date' => 'datetime',
        'arrival_date' => 'datetime',
        'discharge_date' => 'datetime',
        'delivery_date' => 'datetime',
        'return_date' => 'datetime',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_update_timestamp' => 'datetime',
        'container_metadata' => 'array',
    ];

    /**
     * Get the freight order.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Get the terminal events for this container.
     */
    public function terminalEvents(): HasMany
    {
        return $this->hasMany(TerminalEvent::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by container type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('container_type', $type);
    }

    /**
     * Scope a query to filter by vessel.
     */
    public function scopeByVessel($query, string $vesselName, string $voyageNumber)
    {
        return $query->where('vessel_name', $vesselName)
                    ->where('voyage_number', $voyageNumber);
    }

    /**
     * Check if the container is empty.
     */
    public function isEmpty(): bool
    {
        return $this->status === 'empty';
    }

    /**
     * Check if the container is loaded.
     */
    public function isLoaded(): bool
    {
        return $this->status === 'loaded';
    }

    /**
     * Check if the container is in transit.
     */
    public function isInTransit(): bool
    {
        return $this->status === 'in_transit';
    }

    /**
     * Check if the container is at port.
     */
    public function isAtPort(): bool
    {
        return $this->status === 'at_port';
    }

    /**
     * Check if the container is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Get the current location as a string.
     */
    public function getCurrentLocationStringAttribute(): string
    {
        if ($this->current_latitude && $this->current_longitude) {
            return "{$this->current_latitude}, {$this->current_longitude}";
        }

        return $this->current_location ?? 'Unknown';
    }

    /**
     * Get the transit time in days.
     */
    public function getTransitTimeDaysAttribute(): ?int
    {
        if (!$this->departure_date || !$this->arrival_date) {
            return null;
        }

        return $this->departure_date->diffInDays($this->arrival_date);
    }

    /**
     * Get the utilization percentage.
     */
    public function getUtilizationPercentageAttribute(): ?float
    {
        if (!$this->max_gross_weight_kg || !$this->payload_weight_kg) {
            return null;
        }

        return ($this->payload_weight_kg / $this->max_gross_weight_kg) * 100;
    }
}