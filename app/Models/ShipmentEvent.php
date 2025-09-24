<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_order_id',
        'event_type',
        'event_code',
        'event_time',
        'location_name',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'description',
        'notes',
        'source',
        'reference_number',
        'raw_data',
        'metadata',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'raw_data' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the freight order that owns the shipment event.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Scope a query to filter by event type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope a query to filter by freight order.
     */
    public function scopeByFreightOrder($query, int $freightOrderId)
    {
        return $query->where('freight_order_id', $freightOrderId);
    }

    /**
     * Get the coordinates as an array.
     */
    public function getCoordinatesAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [
                'latitude' => (float) $this->latitude,
                'longitude' => (float) $this->longitude,
            ];
        }

        return null;
    }

    /**
     * Check if event has coordinates.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }
}