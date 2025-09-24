<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_number',
        'carrier_company_id',
        'assigned_driver_id',
        'vehicle_id',
        'route_type',
        'status',
        'planned_date',
        'planned_start_time',
        'planned_end_time',
        'actual_start_date',
        'actual_start_time',
        'actual_end_date',
        'actual_end_time',
        'total_stops',
        'completed_stops',
        'total_distance_km',
        'estimated_duration_minutes',
        'fuel_cost',
        'toll_cost',
        'route_waypoints',
        'route_metadata',
        'special_instructions',
        'created_by_user_id',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'planned_start_time' => 'datetime',
        'planned_end_time' => 'datetime',
        'actual_start_date' => 'date',
        'actual_start_time' => 'datetime',
        'actual_end_date' => 'date',
        'actual_end_time' => 'datetime',
        'total_distance_km' => 'decimal:3',
        'fuel_cost' => 'decimal:2',
        'toll_cost' => 'decimal:2',
        'route_waypoints' => 'array',
        'route_metadata' => 'array',
    ];

    /**
     * Get the carrier company.
     */
    public function carrierCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'carrier_company_id');
    }

    /**
     * Get the assigned driver.
     */
    public function assignedDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    /**
     * Get the assigned vehicle.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who created this route plan.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the route plan stops.
     */
    public function stops(): HasMany
    {
        return $this->hasMany(RoutePlanStop::class);
    }

    /**
     * Get telematics data for this route plan.
     */
    public function telematics(): HasMany
    {
        return $this->hasMany(VehicleTelematics::class);
    }

    /**
     * Scope a query to only include routes by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by route type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('route_type', $type);
    }

    /**
     * Scope a query to filter by carrier.
     */
    public function scopeByCarrier($query, int $carrierId)
    {
        return $query->where('carrier_company_id', $carrierId);
    }

    /**
     * Scope a query to filter by driver.
     */
    public function scopeByDriver($query, int $driverId)
    {
        return $query->where('assigned_driver_id', $driverId);
    }

    /**
     * Get the completion percentage of this route.
     */
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_stops === 0) {
            return 0;
        }
        return ($this->completed_stops / $this->total_stops) * 100;
    }

    /**
     * Generate a unique route number.
     */
    public function generateRouteNumber(): string
    {
        $prefix = 'RT';
        $date = now()->format('Ymd');
        $sequence = str_pad(
            static::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "{$prefix}-{$date}-{$sequence}";
    }
}