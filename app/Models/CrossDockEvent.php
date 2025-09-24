<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrossDockEvent extends Model
{
    use HasFactory;
    
    protected $table = 'cross_dock_events';

    protected $fillable = [
        'event_id',
        'incoming_shipment_id',
        'outgoing_shipment_id',
        'cross_dock_location_id',
        'transfer_start_time',
        'transfer_complete_time',
        'transfer_duration_minutes',
        'status',
        'items_transferred',
        'weight_transferred',
        'volume_transferred',
        'operator_id',
        'transfer_notes',
        'transfer_metadata'
    ];

    protected $casts = [
        'transfer_start_time' => 'datetime',
        'transfer_complete_time' => 'datetime',
        'transfer_duration_minutes' => 'decimal:2',
        'weight_transferred' => 'decimal:3',
        'volume_transferred' => 'decimal:3',
        'transfer_metadata' => 'array'
    ];

    public function incomingShipment(): BelongsTo
    {
        return $this->belongsTo(InboundReceiving::class, 'incoming_shipment_id');
    }

    public function outgoingShipment(): BelongsTo
    {
        return $this->belongsTo(OutboundShipment::class, 'outgoing_shipment_id');
    }

    public function crossDockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'cross_dock_location_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}