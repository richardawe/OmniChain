<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'movement_id',
        'from_location_id',
        'to_location_id',
        'product_id',
        'batch_id',
        'quantity_moved',
        'unit_of_measure',
        'movement_timestamp',
        'responsible_employee_id',
        'work_order_id',
        'movement_type',
        'movement_reason',
        'movement_metadata'
    ];

    protected $casts = [
        'quantity_moved' => 'decimal:3',
        'movement_timestamp' => 'datetime',
        'movement_metadata' => 'array'
    ];

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function responsibleEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_employee_id');
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}