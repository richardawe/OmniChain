<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineTelemetry extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'timestamp',
        'operational_state',
        'availability_percentage',
        'performance_percentage',
        'quality_percentage',
        'overall_oee',
        'cycle_time_seconds',
        'units_produced',
        'temperature_celsius',
        'vibration_level',
        'pressure_psi',
        'power_consumption_kw',
        'error_codes',
        'sensor_data',
        'notes'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'availability_percentage' => 'decimal:2',
        'performance_percentage' => 'decimal:2',
        'quality_percentage' => 'decimal:2',
        'overall_oee' => 'decimal:2',
        'cycle_time_seconds' => 'decimal:2',
        'temperature_celsius' => 'decimal:2',
        'vibration_level' => 'decimal:4',
        'pressure_psi' => 'decimal:2',
        'power_consumption_kw' => 'decimal:2',
        'error_codes' => 'array',
        'sensor_data' => 'array'
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }
}