<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchTracking extends Model
{
    use HasFactory;

    protected $table = 'batch_tracking';

    protected $fillable = [
        'batch_id',
        'product_id',
        'work_order_id',
        'batch_quantity',
        'production_date',
        'expiry_date',
        'quality_status',
        'created_by',
        'component_batches',
        'batch_metadata'
    ];

    protected $casts = [
        'batch_quantity' => 'decimal:3',
        'production_date' => 'date',
        'expiry_date' => 'date',
        'component_batches' => 'array',
        'batch_metadata' => 'array'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(QualityInspection::class, 'batch_id', 'batch_id');
    }

    public function materialMovements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class, 'batch_id', 'batch_id');
    }
}