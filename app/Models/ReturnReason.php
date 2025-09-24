<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason_code',
        'reason_name',
        'description',
        'category',
        'requires_approval',
        'auto_approve',
        'approval_level',
        'max_refund_percentage',
        'charge_restocking_fee',
        'restocking_fee_percentage',
        'requires_return_shipping',
        'requires_inspection',
        'valid_days',
        'is_active',
        'notes',
        'reason_metadata'
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'auto_approve' => 'boolean',
        'max_refund_percentage' => 'decimal:2',
        'charge_restocking_fee' => 'boolean',
        'restocking_fee_percentage' => 'decimal:2',
        'requires_return_shipping' => 'boolean',
        'requires_inspection' => 'boolean',
        'is_active' => 'boolean',
        'reason_metadata' => 'array'
    ];
}