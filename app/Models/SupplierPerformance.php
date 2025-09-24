<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPerformance extends Model
{
    use HasFactory;

    protected $table = 'supplier_performance';

    protected $fillable = [
        'company_id',
        'buyer_company_id',
        'performance_date',
        'period_type',
        'on_time_delivery_pct',
        'quality_reject_rate',
        'fill_rate',
        'lead_time_variance_days',
        'cost_performance_score',
        'communication_score',
        'innovation_score',
        'overall_score',
        'audit_results',
        'corrective_actions',
        'performance_notes',
        'kpi_metrics',
        'evaluated_by_user_id',
        'metadata',
    ];

    protected $casts = [
        'performance_date' => 'date',
        'audit_results' => 'array',
        'corrective_actions' => 'array',
        'kpi_metrics' => 'array',
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

    public function evaluatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by_user_id');
    }

    // Scopes
    public function scopeByPeriod($query, string $periodType)
    {
        return $query->where('period_type', $periodType);
    }

    public function scopeRecent($query, int $months = 12)
    {
        return $query->where('performance_date', '>=', now()->subMonths($months));
    }

    public function scopeHighPerforming($query, float $threshold = 8.0)
    {
        return $query->where('overall_score', '>=', $threshold);
    }

    public function scopeUnderperforming($query, float $threshold = 6.0)
    {
        return $query->where('overall_score', '<', $threshold);
    }
}