<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseBin extends Model
{
    use HasFactory;
    
    protected $table = 'warehouse_bins';

    protected $fillable = [
        'bin_id',
        'location_id',
        'bin_name',
        'bin_type',
        'zone',
        'aisle',
        'rack',
        'level',
        'position',
        'capacity_volume',
        'capacity_weight',
        'current_volume',
        'current_weight',
        'utilization_percentage',
        'status',
        'requires_temperature_control',
        'min_temperature',
        'max_temperature',
        'hazardous_material_allowed',
        'bin_metadata'
    ];

    protected $casts = [
        'capacity_volume' => 'decimal:3',
        'capacity_weight' => 'decimal:3',
        'current_volume' => 'decimal:3',
        'current_weight' => 'decimal:3',
        'utilization_percentage' => 'decimal:2',
        'min_temperature' => 'decimal:2',
        'max_temperature' => 'decimal:2',
        'requires_temperature_control' => 'boolean',
        'hazardous_material_allowed' => 'boolean',
        'bin_metadata' => 'array'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function putawayRecords(): HasMany
    {
        return $this->hasMany(PutawayRecord::class, 'to_bin_id');
    }
}