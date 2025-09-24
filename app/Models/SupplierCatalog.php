<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCatalog extends Model
{
    use HasFactory;

    protected $table = 'supplier_catalogs';

    protected $fillable = [
        'company_id',
        'buyer_company_id',
        'supplier_part_number',
        'buyer_sku',
        'product_name',
        'description',
        'category',
        'unit_of_measure',
        'unit_price',
        'currency',
        'lead_time_days',
        'minimum_order_quantity',
        'pricing_tiers',
        'availability_indicators',
        'effective_date',
        'expiry_date',
        'status',
        'specifications',
        'metadata',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'pricing_tiers' => 'array',
        'availability_indicators' => 'array',
        'specifications' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function buyerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'buyer_company_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeEffective($query)
    {
        return $query->where('effective_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>=', now());
                    });
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}