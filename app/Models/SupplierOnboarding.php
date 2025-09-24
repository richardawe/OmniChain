<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierOnboarding extends Model
{
    use HasFactory;

    protected $table = 'supplier_onboarding';

    protected $fillable = [
        'company_id',
        'buyer_company_id',
        'onboarding_date',
        'status',
        'kyc_documents',
        'compliance_certs',
        'approved_items',
        'lead_times_days',
        'minimum_order_quantity',
        'supplier_performance_scores',
        'onboarding_notes',
        'assigned_to_user_id',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'metadata',
    ];

    protected $casts = [
        'onboarding_date' => 'date',
        'kyc_documents' => 'array',
        'compliance_certs' => 'array',
        'approved_items' => 'array',
        'supplier_performance_scores' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByBuyer($query, int $buyerCompanyId)
    {
        return $query->where('buyer_company_id', $buyerCompanyId);
    }
}