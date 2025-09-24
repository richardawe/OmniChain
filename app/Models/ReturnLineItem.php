<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnLineItem extends Model
{
    use HasFactory;
    
    protected $table = 'return_line_items';

    protected $fillable = [
        'return_id',
        'product_id',
        'original_line_item_id',
        'quantity_returned',
        'quantity_received',
        'quantity_damaged',
        'unit_price',
        'total_value',
        'refund_amount',
        'condition',
        'disposition',
        'condition_notes',
        'inspection_notes',
        'requires_approval',
        'approved',
        'approved_by',
        'approved_at',
        'line_item_metadata'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'requires_approval' => 'boolean',
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'line_item_metadata' => 'array'
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class, 'return_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processing(): HasMany
    {
        return $this->hasMany(ReturnProcessing::class, 'return_line_item_id');
    }
}