<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'code', 'type', 'address', 'city', 'state', 'country',
        'postal_code', 'latitude', 'longitude', 'timezone', 'operating_hours',
        'capabilities', 'capacity_volume', 'capacity_weight', 'hazardous_material_capabilities',
        'temperature_control', 'loading_bays_count', 'dock_types', 'accessibility_notes',
        'status', 'metadata',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'operating_hours' => 'array',
        'capabilities' => 'array',
        'hazardous_material_capabilities' => 'array',
        'temperature_control' => 'array',
        'dock_types' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the company that owns the location.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get freight orders that originate from this location.
     */
    public function originFreightOrders(): HasMany
    {
        return $this->hasMany(FreightOrder::class, 'origin_location_id');
    }

    /**
     * Get freight orders that are destined for this location.
     */
    public function destinationFreightOrders(): HasMany
    {
        return $this->hasMany(FreightOrder::class, 'destination_location_id');
    }

    // Manufacturing Relationships
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'production_location_id');
    }

    public function materialMovementsFrom(): HasMany
    {
        return $this->hasMany(MaterialMovement::class, 'from_location_id');
    }

    public function materialMovementsTo(): HasMany
    {
        return $this->hasMany(MaterialMovement::class, 'to_location_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'location_id');
    }

    // Inventory & Warehousing Relationships
    public function inventoryBalances(): HasMany
    {
        return $this->hasMany(InventoryBalance::class, 'location_id');
    }

    public function warehouseBins(): HasMany
    {
        return $this->hasMany(WarehouseBin::class, 'location_id');
    }

    public function inboundReceivings(): HasMany
    {
        return $this->hasMany(InboundReceiving::class, 'location_id');
    }

    public function outboundShipments(): HasMany
    {
        return $this->hasMany(OutboundShipment::class, 'ship_from_location_id');
    }

    public function putawayRecords(): HasMany
    {
        return $this->hasMany(PutawayRecord::class, 'location_id');
    }

    public function cycleCounts(): HasMany
    {
        return $this->hasMany(CycleCount::class, 'location_id');
    }

    public function crossDockEvents(): HasMany
    {
        return $this->hasMany(CrossDockEvent::class, 'location_id');
    }

    // Transportation Relationships
    public function routePlanStops(): HasMany
    {
        return $this->hasMany(RoutePlanStop::class, 'location_id');
    }

    public function terminalEvents(): HasMany
    {
        return $this->hasMany(TerminalEvent::class, 'terminal_location_id');
    }

    // Delivery Management Relationships
    public function orderFulfillments(): HasMany
    {
        return $this->hasMany(OrderFulfillment::class, 'fulfillment_location_id');
    }

    public function deliveryTasks(): HasMany
    {
        return $this->hasMany(DeliveryTask::class, 'delivery_location_id');
    }

    public function geofenceEvents(): HasMany
    {
        return $this->hasMany(GeofenceEvent::class, 'location_id');
    }

    // Returns & Reverse Logistics Relationships
    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class, 'return_location_id');
    }

    /**
     * Scope a query to only include active locations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by location type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get the full address as a single string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
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
     * Check if location has coordinates.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }
}
