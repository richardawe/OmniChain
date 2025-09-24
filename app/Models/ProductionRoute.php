<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'route_name',
        'product_id',
        'description',
        'total_standard_time',
        'total_setup_time',
        'status',
        'effective_date',
        'expiry_date',
        'route_metadata'
    ];

    protected $casts = [
        'total_standard_time' => 'decimal:2',
        'total_setup_time' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'route_metadata' => 'array'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ProductionStep::class, 'route_id');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'route_id');
    }
}