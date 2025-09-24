<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'company_id', 'sku', 'gtin', 'upc', 'ean', 'name', 'description', 'category', 'subcategory',
        'product_family', 'brand', 'manufacturer', 'unit_of_measure', 'weight', 'net_weight',
        'length', 'width', 'height', 'volume', 'hazardous', 'temperature_controlled',
        'hazardous_details', 'temperature_requirements', 'handling_requirements',
        'hazardous_material_code', 'storage_requirements', 'shelf_life_days',
        'batch_trackable', 'serialization_capable', 'bom_references', 'country_of_origin',
        'tariff_codes', 'lifecycle_status', 'manufacturer_part_number', 'status', 'metadata'
    ];

    protected $casts = [
        'hazardous_details' => 'array',
        'temperature_requirements' => 'array',
        'handling_requirements' => 'array',
        'storage_requirements' => 'array',
        'bom_references' => 'array',
        'tariff_codes' => 'array',
        'metadata' => 'array',
        'batch_trackable' => 'boolean',
        'serialization_capable' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Manufacturing Relationships
    public function billOfMaterials(): HasMany
    {
        return $this->hasMany(BillOfMaterial::class, 'product_id');
    }

    public function bomComponents(): HasMany
    {
        return $this->hasMany(BomComponent::class, 'component_sku', 'sku');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'product_id');
    }

    public function batchTracking(): HasMany
    {
        return $this->hasMany(BatchTracking::class, 'product_id');
    }

    public function materialMovements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class, 'sku', 'sku');
    }

    public function qualityInspections(): HasMany
    {
        return $this->hasMany(QualityInspection::class, 'product_id');
    }

    // Inventory & Warehousing Relationships
    public function inventoryBalances(): HasMany
    {
        return $this->hasMany(InventoryBalance::class, 'sku', 'sku');
    }

    public function inboundReceivingItems(): HasMany
    {
        return $this->hasMany(InboundReceiving::class, 'sku', 'sku');
    }

    public function outboundShipmentItems(): HasMany
    {
        return $this->hasMany(OutboundShipment::class, 'sku', 'sku');
    }

    public function putawayRecords(): HasMany
    {
        return $this->hasMany(PutawayRecord::class, 'sku', 'sku');
    }

    public function cycleCounts(): HasMany
    {
        return $this->hasMany(CycleCount::class, 'sku', 'sku');
    }

    // Supplier & Procurement Relationships
    public function supplierCatalogs(): HasMany
    {
        return $this->hasMany(SupplierCatalog::class, 'sku', 'sku');
    }

    public function purchaseOrderLineItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderLineItem::class, 'sku', 'sku');
    }

    // Delivery Management Relationships
    public function orderFulfillmentLineItems(): HasMany
    {
        return $this->hasMany(OrderFulfillmentLineItem::class, 'sku', 'sku');
    }

    // Returns & Reverse Logistics Relationships
    public function returnLineItems(): HasMany
    {
        return $this->hasMany(ReturnLineItem::class, 'sku', 'sku');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeHazardous($query)
    {
        return $query->where('hazardous', 'yes');
    }

    public function scopeTemperatureControlled($query)
    {
        return $query->where('temperature_controlled', 'yes');
    }
}
