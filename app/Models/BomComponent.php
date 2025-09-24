<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'component_product_id',
        'line_number',
        'quantity_required',
        'unit_of_measure',
        'sub_bom_id',
        'is_phantom',
        'scrap_factor',
        'notes',
        'component_metadata'
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
        'scrap_factor' => 'decimal:4',
        'is_phantom' => 'boolean',
        'component_metadata' => 'array'
    ];

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }

    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }

    public function subBom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class, 'sub_bom_id');
    }
}