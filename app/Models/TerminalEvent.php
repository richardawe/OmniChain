<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TerminalEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_tracking_id',
        'freight_order_id',
        'terminal_name',
        'terminal_code',
        'terminal_location',
        'terminal_latitude',
        'terminal_longitude',
        'event_type',
        'event_timestamp',
        'planned_timestamp',
        'equipment_id',
        'operator_id',
        'event_status',
        'event_description',
        'event_metadata',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'event_timestamp' => 'datetime',
        'planned_timestamp' => 'datetime',
        'terminal_latitude' => 'decimal:8',
        'terminal_longitude' => 'decimal:8',
        'duration_minutes' => 'decimal:2',
        'event_metadata' => 'array',
    ];

    /**
     * Get the container tracking.
     */
    public function containerTracking(): BelongsTo
    {
        return $this->belongsTo(ContainerTracking::class);
    }

    /**
     * Get the freight order.
     */
    public function freightOrder(): BelongsTo
    {
        return $this->belongsTo(FreightOrder::class);
    }

    /**
     * Scope a query to filter by event type.
     */
    public function scopeByEventType($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope a query to filter by event status.
     */
    public function scopeByEventStatus($query, string $eventStatus)
    {
        return $query->where('event_status', $eventStatus);
    }

    /**
     * Scope a query to filter by terminal.
     */
    public function scopeByTerminal($query, string $terminalName)
    {
        return $query->where('terminal_name', $terminalName);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_timestamp', [$startDate, $endDate]);
    }

    /**
     * Check if the event is completed.
     */
    public function isCompleted(): bool
    {
        return $this->event_status === 'completed';
    }

    /**
     * Check if the event is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->event_status === 'in_progress';
    }

    /**
     * Check if the event is delayed.
     */
    public function isDelayed(): bool
    {
        return $this->event_status === 'delayed';
    }

    /**
     * Check if the event is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->event_status === 'cancelled';
    }

    /**
     * Get the delay time in minutes.
     */
    public function getDelayTimeMinutesAttribute(): ?int
    {
        if (!$this->planned_timestamp || !$this->event_timestamp) {
            return null;
        }

        if ($this->event_timestamp <= $this->planned_timestamp) {
            return 0;
        }

        return $this->planned_timestamp->diffInMinutes($this->event_timestamp);
    }

    /**
     * Check if the event is on time.
     */
    public function isOnTime(): bool
    {
        return $this->delay_time_minutes === 0;
    }
}