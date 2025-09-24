<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'work_order_id',
        'batch_id',
        'inspector_id',
        'inspection_timestamp',
        'inspection_type',
        'inspection_result',
        'sample_size',
        'defects_found',
        'defect_rate_percentage',
        'inspection_notes',
        'measured_attributes',
        'quality_metadata'
    ];

    protected $casts = [
        'inspection_timestamp' => 'datetime',
        'defect_rate_percentage' => 'decimal:2',
        'measured_attributes' => 'array',
        'quality_metadata' => 'array'
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}