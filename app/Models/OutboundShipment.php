<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutboundShipment extends Model
{
    use HasFactory;
    
    protected $table = 'outbound_shipments';

    protected $fillable = [
        'shipment_id',
        'pick_list_id',
        'ship_from_location_id',
        'ship_to_location_id',
        'carrier_id',
        'tracking_number',
        'order_type',
        'status',
        'scheduled_ship_date',
        'actual_ship_date',
        'estimated_delivery_date',
        'actual_delivery_date',
        'total_weight',
        'total_volume',
        'dimensions',
        'shipping_cost',
        'service_level',
        'signature_required',
        'insurance_required',
        'declared_value',
        'special_instructions',
        'packed_items',
        'shipping_metadata'
    ];

    protected $casts = [
        'scheduled_ship_date' => 'datetime',
        'actual_ship_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'total_weight' => 'decimal:3',
        'total_volume' => 'decimal:3',
        'shipping_cost' => 'decimal:2',
        'declared_value' => 'decimal:2',
        'signature_required' => 'boolean',
        'insurance_required' => 'boolean',
        'dimensions' => 'array',
        'packed_items' => 'array',
        'shipping_metadata' => 'array'
    ];

    public function shipFromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'ship_from_location_id');
    }

    public function shipToLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'ship_to_location_id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'carrier_id');
    }

    public function crossDockEvents(): HasMany
    {
        return $this->hasMany(CrossDockEvent::class, 'outgoing_shipment_id');
    }
}