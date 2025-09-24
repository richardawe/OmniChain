<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'step_number',
        'operation_code',
        'operation_name',
        'machine_id',
        'work_center_id',
        'standard_time_minutes',
        'setup_time_minutes',
        'queue_time_minutes',
        'move_time_minutes',
        'is_bottleneck',
        'parallel_capacity',
        'operation_description',
        'required_skills',
        'tool_requirements',
        'quality_checkpoints',
        'step_metadata'
    ];

    protected $casts = [
        'standard_time_minutes' => 'decimal:2',
        'setup_time_minutes' => 'decimal:2',
        'queue_time_minutes' => 'decimal:2',
        'move_time_minutes' => 'decimal:2',
        'is_bottleneck' => 'boolean',
        'required_skills' => 'array',
        'tool_requirements' => 'array',
        'quality_checkpoints' => 'array',
        'step_metadata' => 'array'
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(ProductionRoute::class, 'route_id');
    }

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'work_center_id');
    }
}