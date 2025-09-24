<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'product_id',
        'version',
        'effective_date',
        'expiry_date',
        'description',
        'status',
        'bom_metadata'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'bom_metadata' => 'array'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(BomComponent::class, 'bom_id');
    }

    public function subBoms(): HasMany
    {
        return $this->hasMany(BomComponent::class, 'sub_bom_id');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'bom_id');
    }
}