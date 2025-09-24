<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CycleCount extends Model
{
    use HasFactory;
    
    protected $table = 'cycle_counts';

    protected $fillable = [
        'count_id',
        'location_id',
        'product_id',
        'lot_bin',
        'system_quantity',
        'counted_quantity',
        'discrepancy',
        'count_date',
        'counter_id',
        'supervisor_id',
        'count_status',
        'count_type',
        'audit_comments',
        'discrepancy_reason',
        'count_metadata'
    ];

    protected $casts = [
        'system_quantity' => 'decimal:3',
        'counted_quantity' => 'decimal:3',
        'discrepancy' => 'decimal:3',
        'count_date' => 'date',
        'count_metadata' => 'array'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counter_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}