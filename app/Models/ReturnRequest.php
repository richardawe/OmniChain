<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRequest extends Model
{
    use HasFactory;
    
    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'original_order_number',
        'customer_id',
        'location_id',
        'return_authorization_id',
        'return_type',
        'status',
        'priority',
        'request_date',
        'authorized_date',
        'received_date',
        'completed_date',
        'total_value',
        'refund_amount',
        'refund_method',
        'tracking_number',
        'carrier',
        'return_reason',
        'customer_notes',
        'internal_notes',
        'requires_inspection',
        'customer_notified',
        'return_metadata'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'authorized_date' => 'datetime',
        'received_date' => 'datetime',
        'completed_date' => 'datetime',
        'total_value' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'requires_inspection' => 'boolean',
        'customer_notified' => 'boolean',
        'return_metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function returnAuthorization(): BelongsTo
    {
        return $this->belongsTo(ReturnAuthorization::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(ReturnLineItem::class, 'return_id');
    }

    public function processing(): HasMany
    {
        return $this->hasMany(ReturnProcessing::class, 'return_id');
    }
}