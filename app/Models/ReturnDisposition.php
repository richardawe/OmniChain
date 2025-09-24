<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDisposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'disposition_code',
        'disposition_name',
        'description',
        'disposition_type',
        'requires_inspection',
        'requires_approval',
        'cost_per_item',
        'processing_days',
        'generates_credit',
        'generates_replacement',
        'affects_inventory',
        'inventory_action',
        'processing_instructions',
        'quality_requirements',
        'is_active',
        'notes',
        'disposition_metadata'
    ];

    protected $casts = [
        'requires_inspection' => 'boolean',
        'requires_approval' => 'boolean',
        'cost_per_item' => 'decimal:2',
        'generates_credit' => 'boolean',
        'generates_replacement' => 'boolean',
        'affects_inventory' => 'boolean',
        'is_active' => 'boolean',
        'disposition_metadata' => 'array'
    ];
}