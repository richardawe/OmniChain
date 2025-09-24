<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnProcessing extends Model
{
    use HasFactory;
    
    protected $table = 'return_processing';

    protected $fillable = [
        'return_id',
        'return_line_item_id',
        'processor_id',
        'processing_step',
        'status',
        'started_at',
        'completed_at',
        'processing_time_minutes',
        'inspection_notes',
        'processing_notes',
        'quality_check',
        'photos',
        'refund_amount',
        'refund_method',
        'replacement_order_number',
        'repair_ticket_number',
        'disposition_notes',
        'processing_metadata'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'quality_check' => 'array',
        'photos' => 'array',
        'refund_amount' => 'decimal:2',
        'processing_metadata' => 'array'
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class, 'return_id');
    }

    public function returnLineItem(): BelongsTo
    {
        return $this->belongsTo(ReturnLineItem::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processor_id');
    }
}