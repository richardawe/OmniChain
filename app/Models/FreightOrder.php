<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreightOrder extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'order_number',
        'shipper_company_id',
        'carrier_company_id',
        'assigned_driver_id',
        'origin_location_id',
        'destination_location_id',
        'service_type',
        'priority',
        'pickup_date',
        'delivery_date',
        'requested_pickup_date',
        'requested_delivery_date',
        'requested_pickup_time',
        'requested_delivery_time',
        'total_weight',
        'total_volume',
        'total_pieces',
        'declared_value',
        'currency',
        'status',
        'cargo_description',
        'pickup_instructions',
        'delivery_instructions',
        'delivery_photo_path',
        'signature_path',
        'delivery_notes',
        'recipient_name',
        'recipient_phone',
        'special_instructions',
        'equipment_requirements',
        'temperature_requirements',
        'hazardous_details',
        'customs_details',
        'metadata',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
        'requested_pickup_date' => 'datetime',
        'requested_delivery_date' => 'datetime',
        'total_weight' => 'decimal:3',
        'total_volume' => 'decimal:3',
        'declared_value' => 'decimal:2',
        'equipment_requirements' => 'array',
        'temperature_requirements' => 'array',
        'hazardous_details' => 'array',
        'customs_details' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function shipperCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'shipper_company_id');
    }

    public function carrierCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'carrier_company_id');
    }

    public function originLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function shipmentEvents(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class);
    }

    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    // Transportation Relationships
    public function routePlans(): HasMany
    {
        return $this->hasMany(RoutePlan::class);
    }

    public function proofOfDelivery(): HasMany
    {
        return $this->hasMany(ProofOfDelivery::class);
    }

    public function customsDocumentation(): HasMany
    {
        return $this->hasMany(CustomsDocumentation::class);
    }

    public function containerTracking(): HasMany
    {
        return $this->hasMany(ContainerTracking::class);
    }

    public function terminalEvents(): HasMany
    {
        return $this->hasMany(TerminalEvent::class);
    }

    public function vehicleTelematics(): HasMany
    {
        return $this->hasMany(VehicleTelematics::class);
    }

    // Delivery Management Relationships
    public function deliveryTasks(): HasMany
    {
        return $this->hasMany(DeliveryTask::class);
    }

    public function deliveryExceptions(): HasMany
    {
        return $this->hasMany(DeliveryException::class);
    }

    public function geofenceEvents(): HasMany
    {
        return $this->hasMany(GeofenceEvent::class);
    }

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    // Returns & Reverse Logistics Relationships
    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class);
    }

    // Inventory & Warehousing Relationships
    public function outboundShipments(): HasMany
    {
        return $this->hasMany(OutboundShipment::class);
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByServiceType($query, string $serviceType)
    {
        return $query->where('service_type', $serviceType);
    }

    public function scopeByShipper($query, int $shipperId)
    {
        return $query->where('shipper_company_id', $shipperId);
    }

    public function scopeByCarrier($query, int $carrierId)
    {
        return $query->where('carrier_company_id', $carrierId);
    }

    // Accessors & Mutators
    public function getLatestEventAttribute()
    {
        return $this->shipmentEvents()->latest('event_time')->first();
    }

    public function getEstimatedTransitTimeAttribute(): ?int
    {
        if (!$this->pickup_date || !$this->delivery_date) {
            return null;
        }

        return $this->pickup_date->diffInDays($this->delivery_date);
    }

    // Methods
    public function generateOrderNumber(): string
    {
        $prefix = 'FO';
        $date = now()->format('Ymd');
        $sequence = str_pad(
            static::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "{$prefix}-{$date}-{$sequence}";
    }

    public function updateStatus(string $status): bool
    {
        $this->status = $status;
        return $this->save();
    }
}
