<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'legal_name',
        'tax_id',
        'duns_number',
        'lei_number',
        'vat_number',
        'trade_name',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'type',
        'business_type',
        'currency',
        'timezone',
        'industry_classification',
        'certifications',
        'payment_terms_code',
        'credit_limit',
        'primary_contact_id',
        'addresses',
        'status',
        'metadata',
    ];

    protected $casts = [
        'certifications' => 'array',
        'addresses' => 'array',
        'metadata' => 'array',
    ];

    // Relationships
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function carriers(): HasMany
    {
        return $this->hasMany(Carrier::class);
    }

    public function shipperFreightOrders(): HasMany
    {
        return $this->hasMany(FreightOrder::class, 'shipper_company_id');
    }

    public function carrierFreightOrders(): HasMany
    {
        return $this->hasMany(FreightOrder::class, 'carrier_company_id');
    }

    public function carrierRates(): HasMany
    {
        return $this->hasMany(CarrierRate::class, 'carrier_company_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Supplier & Procurement Relationships
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    public function supplierContracts(): HasMany
    {
        return $this->hasMany(SupplierContract::class, 'supplier_id');
    }

    public function supplierCatalogs(): HasMany
    {
        return $this->hasMany(SupplierCatalog::class, 'supplier_id');
    }

    public function supplierPerformance(): HasMany
    {
        return $this->hasMany(SupplierPerformance::class, 'supplier_id');
    }

    public function supplierOnboarding(): HasMany
    {
        return $this->hasMany(SupplierOnboarding::class, 'supplier_id');
    }

    // Manufacturing Relationships
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'company_id');
    }

    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'company_id');
    }

    // Inventory & Warehousing Relationships
    public function inboundReceivings(): HasMany
    {
        return $this->hasMany(InboundReceiving::class, 'supplier_id');
    }

    public function outboundShipments(): HasMany
    {
        return $this->hasMany(OutboundShipment::class, 'shipper_id');
    }

    // Delivery Management Relationships
    public function orderFulfillments(): HasMany
    {
        return $this->hasMany(OrderFulfillment::class, 'customer_id');
    }

    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class, 'customer_id');
    }

    // Returns & Reverse Logistics Relationships
    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class, 'customer_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Accessors & Mutators
    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ])->filter()->implode(', ');
    }
}
