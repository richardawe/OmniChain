<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_number',
        'work_order_id',
        'production_line_id',
        'production_location_id',
        'product_id',
        'bom_id',
        'route_id',
        'quantity_planned',
        'quantity_produced',
        'quantity_scrapped',
        'start_datetime',
        'end_datetime',
        'planned_start_time',
        'planned_end_time',
        'actual_start_time',
        'actual_end_time',
        'shift_id',
        'status',
        'priority',
        'source_purchase_order_id',
        'notes',
        'created_by',
        'assigned_supervisor',
        'operator_ids',
        'associated_batch_numbers',
        'work_instructions',
        'special_requirements',
        'work_order_metadata'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'planned_start_time' => 'datetime',
        'planned_end_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'operator_ids' => 'array',
        'associated_batch_numbers' => 'array',
        'work_order_metadata' => 'array'
    ];

    public function productionLine(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'production_line_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(ProductionRoute::class, 'route_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_supervisor');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(QualityInspection::class);
    }

    public function batchTracking(): HasMany
    {
        return $this->hasMany(BatchTracking::class);
    }

    public function materialMovements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class);
    }

    public function sourcePurchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'source_purchase_order_id');
    }
}