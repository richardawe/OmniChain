<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'vehicle_number',
        'license_plate',
        'make',
        'model',
        'year',
        'vehicle_type',
        'vehicle_class',
        'max_weight_kg',
        'max_volume_cubic_meters',
        'max_pallets',
        'equipment_features',
        'fuel_type',
        'fuel_capacity_liters',
        'average_fuel_consumption_km_per_liter',
        'insurance_policy_number',
        'insurance_expiry_date',
        'registration_number',
        'registration_expiry_date',
        'vin_number',
        'status',
        'assigned_driver_id',
        'vehicle_metadata',
    ];

    protected $casts = [
        'max_weight_kg' => 'decimal:3',
        'max_volume_cubic_meters' => 'decimal:3',
        'fuel_capacity_liters' => 'decimal:2',
        'average_fuel_consumption_km_per_liter' => 'decimal:2',
        'insurance_expiry_date' => 'date',
        'registration_expiry_date' => 'date',
        'equipment_features' => 'array',
        'vehicle_metadata' => 'array',
    ];

    /**
     * Get the company that owns the vehicle.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the assigned driver.
     */
    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    /**
     * Get route plans for this vehicle.
     */
    public function routePlans(): HasMany
    {
        return $this->hasMany(RoutePlan::class);
    }

    /**
     * Get telematics data for this vehicle.
     */
    public function telematics(): HasMany
    {
        return $this->hasMany(VehicleTelematics::class);
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by vehicle type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('vehicle_type', $type);
    }

    /**
     * Scope a query to filter by vehicle class.
     */
    public function scopeByClass($query, string $class)
    {
        return $query->where('vehicle_class', $class);
    }
}