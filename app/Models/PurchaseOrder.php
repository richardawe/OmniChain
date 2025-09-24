<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'po_type',
        'created_by_user_id',
        'buyer_company_id',
        'supplier_company_id',
        'order_date',
        'required_delivery_date',
        'currency',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'incoterms',
        'payment_terms',
        'status',
        'special_instructions',
        'delivery_requirements',
        'attachments',
        'approved_by_user_id',
        'approved_at',
        'confirmed_at',
        'closed_at',
        'metadata',
    ];

    protected $casts = [
        'order_date' => 'date',
        'required_delivery_date' => 'date',
        'delivery_requirements' => 'array',
        'attachments' => 'array',
        'approved_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'closed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function buyerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'buyer_company_id');
    }

    public function supplierCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'supplier_company_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderLineItem::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByBuyer($query, int $buyerCompanyId)
    {
        return $query->where('buyer_company_id', $buyerCompanyId);
    }

    public function scopeBySupplier($query, int $supplierCompanyId)
    {
        return $query->where('supplier_company_id', $supplierCompanyId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_delivery_date', '<', now())
                    ->whereNotIn('status', ['closed', 'cancelled']);
    }

    // Accessors
    public function getTotalLineItemsAttribute(): int
    {
        return $this->lineItems()->count();
    }

    public function getTotalQuantityOrderedAttribute(): float
    {
        return $this->lineItems()->sum('quantity_ordered');
    }

    public function getTotalQuantityReceivedAttribute(): float
    {
        return $this->lineItems()->sum('quantity_received');
    }

    public function getCompletionPercentageAttribute(): float
    {
        $totalOrdered = $this->total_quantity_ordered;
        if ($totalOrdered == 0) return 0;
        
        return ($this->total_quantity_received / $totalOrdered) * 100;
    }
}