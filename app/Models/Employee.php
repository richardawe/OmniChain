<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'employee_id',
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'role',
        'department',
        'certifications',
        'home_location_id',
        'work_schedule',
        'hire_date',
        'termination_date',
        'status',
        'emergency_contact',
        'metadata',
    ];

    protected $casts = [
        'certifications' => 'array',
        'work_schedule' => 'array',
        'emergency_contact' => 'array',
        'metadata' => 'array',
        'hire_date' => 'date',
        'termination_date' => 'date',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function homeLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'home_location_id');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }
}