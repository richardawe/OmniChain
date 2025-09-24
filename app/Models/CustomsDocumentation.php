<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomsDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_order_id',
        'customs_declaration_number',
        'bill_of_lading_number',
        'commercial_invoice_number',
        'packing_list_number',
        'certificate_of_origin_number',
        'export_license_number',
        'import_license_number',
        'exporting_country',
        'importing_country',
        'export_port',
        'import_port',
        'shipment_type',
        'total_customs_value',
        'currency_code',
        'hs_codes',
        'duty_rates',
        'total_duty_amount',
        'total_tax_amount',
        'restricted_items',
        'required_certifications',
        'customs_status',
        'submission_date',
        'approval_date',
        'release_date',
        'customs_notes',
        'rejection_reason',
        'customs_broker',
        'customs_officer',
        'customs_metadata',
    ];

    protected $casts = [
        'total_customs_value' => 'decimal:2',
        'total_duty_amount' => 'decimal:2',
        'total_tax_amount' => 'decimal:2',
        'hs_codes' => 'array',
        'duty_rates' => 'array',
        'restricted_items' => 'array',
        'required_certifications' => 'array',
        'submission_date' => 'datetime',
        'approval_date' => 'datetime',
        'release_date' => 'datetime',
        'customs_metadata' => 'array',
    ];

    /**
     * Get the freight order.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Scope a query to filter by customs status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('customs_status', $status);
    }

    /**
     * Scope a query to filter by shipment type.
     */
    public function scopeByShipmentType($query, string $type)
    {
        return $query->where('shipment_type', $type);
    }

    /**
     * Scope a query to filter by country.
     */
    public function scopeByCountry($query, string $exportingCountry, string $importingCountry)
    {
        return $query->where('exporting_country', $exportingCountry)
                    ->where('importing_country', $importingCountry);
    }

    /**
     * Check if customs documentation is approved.
     */
    public function isApproved(): bool
    {
        return $this->customs_status === 'approved';
    }

    /**
     * Check if customs documentation is pending.
     */
    public function isPending(): bool
    {
        return in_array($this->customs_status, ['pending', 'submitted', 'under_review']);
    }

    /**
     * Check if customs documentation is rejected.
     */
    public function isRejected(): bool
    {
        return $this->customs_status === 'rejected';
    }

    /**
     * Check if customs documentation is held.
     */
    public function isHeld(): bool
    {
        return $this->customs_status === 'held';
    }

    /**
     * Check if customs documentation is released.
     */
    public function isReleased(): bool
    {
        return $this->customs_status === 'released';
    }

    /**
     * Get the total customs charges.
     */
    public function getTotalChargesAttribute(): float
    {
        return $this->total_duty_amount + $this->total_tax_amount;
    }

    /**
     * Get the processing time in days.
     */
    public function getProcessingTimeDaysAttribute(): ?int
    {
        if (!$this->submission_date || !$this->approval_date) {
            return null;
        }

        return $this->submission_date->diffInDays($this->approval_date);
    }
}