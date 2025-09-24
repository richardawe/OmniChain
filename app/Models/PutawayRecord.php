<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PutawayRecord extends Model
{
    use HasFactory;
    
    protected $table = 'putaway_records';

    protected $fillable = [
        'putaway_id',
        'receiving_id',
        'from_location_id',
        'to_bin_id',
        'product_id',
        'lot_bin',
        'quantity',
        'unit_of_measure',
        'putaway_timestamp',
        'putaway_operator_id',
        'supervisor_id',
        'status',
        'putaway_notes',
        'putaway_metadata'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'putaway_timestamp' => 'datetime',
        'putaway_metadata' => 'array'
    ];

    public function receiving(): BelongsTo
    {
        return $this->belongsTo(InboundReceiving::class, 'receiving_id');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toBin(): BelongsTo
    {
        return $this->belongsTo(WarehouseBin::class, 'to_bin_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function putawayOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'putaway_operator_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}