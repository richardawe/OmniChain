<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTelematics extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'route_plan_id',
        'driver_id',
        'timestamp',
        'latitude',
        'longitude',
        'speed_kmh',
        'heading_degrees',
        'altitude_meters',
        'odometer_km',
        'fuel_level_percentage',
        'fuel_consumed_liters',
        'engine_rpm',
        'engine_load_percentage',
        'coolant_temperature_celsius',
        'battery_voltage',
        'engine_on',
        'driver_seatbelt',
        'door_open',
        'diagnostic_codes',
        'telematics_metadata',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed_kmh' => 'decimal:2',
        'heading_degrees' => 'decimal:2',
        'altitude_meters' => 'decimal:2',
        'odometer_km' => 'decimal:3',
        'fuel_level_percentage' => 'decimal:2',
        'fuel_consumed_liters' => 'decimal:3',
        'engine_load_percentage' => 'decimal:2',
        'coolant_temperature_celsius' => 'decimal:2',
        'battery_voltage' => 'decimal:2',
        'engine_on' => 'boolean',
        'driver_seatbelt' => 'boolean',
        'door_open' => 'boolean',
        'diagnostic_codes' => 'array',
        'telematics_metadata' => 'array',
    ];

    /**
     * Get the vehicle.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the route plan.
     */
    public function routePlan(): BelongsTo
    {
        return $this->belongsTo(RoutePlan::class);
    }

    /**
     * Get the driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Scope a query to filter by vehicle.
     */
    public function scopeByVehicle($query, int $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    /**
     * Scope a query to filter by route plan.
     */
    public function scopeByRoutePlan($query, int $routePlanId)
    {
        return $query->where('route_plan_id', $routePlanId);
    }

    /**
     * Scope a query to filter by driver.
     */
    public function scopeByDriver($query, int $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }

    /**
     * Check if the vehicle is moving.
     */
    public function isMoving(): bool
    {
        return $this->speed_kmh > 0;
    }

    /**
     * Check if there are any diagnostic issues.
     */
    public function hasDiagnosticIssues(): bool
    {
        return !empty($this->diagnostic_codes);
    }
}