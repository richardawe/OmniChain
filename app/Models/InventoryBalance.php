<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBalance extends Model
{
    use HasFactory;
    
    protected $table = 'inventory_balances';

    protected $fillable = [
        'location_id',
        'product_id',
        'lot_bin',
        'quantity_on_hand',
        'quantity_available',
        'quantity_allocated',
        'quantity_on_order',
        'reorder_point',
        'safety_stock',
        'max_stock_level',
        'last_count_date',
        'last_count_quantity',
        'average_cost',
        'total_value',
        'status',
        'inventory_metadata'
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:3',
        'quantity_available' => 'decimal:3',
        'quantity_allocated' => 'decimal:3',
        'quantity_on_order' => 'decimal:3',
        'reorder_point' => 'decimal:3',
        'safety_stock' => 'decimal:3',
        'max_stock_level' => 'decimal:3',
        'last_count_quantity' => 'decimal:3',
        'average_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'last_count_date' => 'date',
        'inventory_metadata' => 'array'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}