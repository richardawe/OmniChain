<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'machine_name',
        'machine_type',
        'manufacturer',
        'model',
        'serial_number',
        'location_id',
        'status',
        'installation_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'capacity_per_hour',
        'efficiency_rating',
        'operational_parameters',
        'machine_metadata'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'capacity_per_hour' => 'decimal:2',
        'efficiency_rating' => 'decimal:2',
        'operational_parameters' => 'array',
        'machine_metadata' => 'array'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function productionSteps(): HasMany
    {
        return $this->hasMany(ProductionStep::class);
    }

    public function telemetry(): HasMany
    {
        return $this->hasMany(MachineTelemetry::class);
    }
}