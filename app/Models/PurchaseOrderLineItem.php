<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderLineItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_line_items';

    protected $fillable = [
        'purchase_order_id',
        'line_number',
        'sku',
        'supplier_part_number',
        'product_name',
        'description',
        'quantity_ordered',
        'unit_of_measure',
        'unit_price',
        'line_total',
        'promised_date',
        'delivery_location_id',
        'schedule_lines',
        'quantity_received',
        'quantity_rejected',
        'quantity_pending',
        'status',
        'line_notes',
        'quality_requirements',
        'metadata',
        'product_id', // Added product_id field
    ];

    protected $casts = [
        'promised_date' => 'date',
        'schedule_lines' => 'array',
        'quality_requirements' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function deliveryLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'delivery_location_id');
    }

    // Add the product relationship
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopePartiallyReceived($query)
    {
        return $query->where('status', 'partially_received');
    }

    public function scopeFullyReceived($query)
    {
        return $query->where('status', 'fully_received');
    }

    public function scopeOverdue($query)
    {
        return $query->where('promised_date', '<', now())
                    ->whereNotIn('status', ['fully_received', 'rejected', 'cancelled']);
    }

    // Accessors
    public function getRemainingQuantityAttribute(): float
    {
        return $this->quantity_ordered - $this->quantity_received;
    }

    public function getReceiptPercentageAttribute(): float
    {
        if ($this->quantity_ordered == 0) return 0;
        
        return ($this->quantity_received / $this->quantity_ordered) * 100;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->promised_date && 
               $this->promised_date < now() && 
               !in_array($this->status, ['fully_received', 'rejected', 'cancelled']);
    }
}