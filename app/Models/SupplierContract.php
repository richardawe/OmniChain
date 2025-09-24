<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierContract extends Model
{
    use HasFactory;

    protected $table = 'supplier_contracts';

    protected $fillable = [
        'contract_number',
        'company_id',
        'buyer_company_id',
        'start_date',
        'end_date',
        'contract_type',
        'sla_details',
        'penalties',
        'volume_commitments',
        'pricing_rules',
        'total_contract_value',
        'currency',
        'status',
        'terms_conditions',
        'renewal_terms',
        'contract_manager_id',
        'signatures',
        'attachments',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sla_details' => 'array',
        'penalties' => 'array',
        'volume_commitments' => 'array',
        'pricing_rules' => 'array',
        'renewal_terms' => 'array',
        'signatures' => 'array',
        'attachments' => 'array',
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

    public function contractManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contract_manager_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('contract_type', $type);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '<=', now()->addDays($days))
                    ->whereNotNull('end_date');
    }
}