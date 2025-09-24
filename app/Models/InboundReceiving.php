<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InboundReceiving extends Model
{
    use HasFactory;
    
    protected $table = 'inbound_receiving';

    protected $fillable = [
        'receiving_id',
        'carrier_id',
        'location_id',
        'asn_number',
        'po_number',
        'bill_of_lading',
        'container_number',
        'expected_arrival_time',
        'actual_arrival_time',
        'unload_start_time',
        'unload_complete_time',
        'status',
        'total_weight',
        'total_volume',
        'total_packages',
        'carrier_notes',
        'receiving_notes',
        'expected_items',
        'received_items',
        'qc_results',
        'receiving_metadata'
    ];

    protected $casts = [
        'expected_arrival_time' => 'datetime',
        'actual_arrival_time' => 'datetime',
        'unload_start_time' => 'datetime',
        'unload_complete_time' => 'datetime',
        'total_weight' => 'decimal:3',
        'total_volume' => 'decimal:3',
        'expected_items' => 'array',
        'received_items' => 'array',
        'qc_results' => 'array',
        'receiving_metadata' => 'array'
    ];

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'carrier_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function putawayRecords(): HasMany
    {
        return $this->hasMany(PutawayRecord::class, 'receiving_id');
    }

    public function crossDockEvents(): HasMany
    {
        return $this->hasMany(CrossDockEvent::class, 'incoming_shipment_id');
    }
}