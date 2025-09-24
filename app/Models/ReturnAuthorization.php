<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'authorization_number',
        'customer_id',
        'location_id',
        'original_order_number',
        'authorization_type',
        'status',
        'request_date',
        'authorized_date',
        'expiry_date',
        'authorized_by',
        'authorized_amount',
        'refund_method',
        'valid_days',
        'authorization_notes',
        'terms_conditions',
        'requires_approval',
        'auto_approve',
        'authorization_metadata'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'authorized_date' => 'datetime',
        'expiry_date' => 'datetime',
        'authorized_amount' => 'decimal:2',
        'requires_approval' => 'boolean',
        'auto_approve' => 'boolean',
        'authorization_metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'customer_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function authorizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ReturnRequest::class, 'return_authorization_id');
    }
}