<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderFulfillment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_company_id',
        'fulfillment_center_id',
        'assigned_warehouse_user_id',
        'order_type',
        'priority',
        'status',
        'order_date',
        'requested_delivery_date',
        'promised_delivery_date',
        'actual_delivery_date',
        'picking_started_at',
        'picking_completed_at',
        'packing_started_at',
        'packing_completed_at',
        'shipping_date',
        'total_line_items',
        'total_quantity',
        'total_weight_kg',
        'total_volume_cubic_meters',
        'order_value',
        'currency_code',
        'delivery_instructions',
        'special_handling_requirements',
        'customer_notes',
        'internal_notes',
        'fulfillment_metadata',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'requested_delivery_date' => 'datetime',
        'promised_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'picking_started_at' => 'datetime',
        'picking_completed_at' => 'datetime',
        'packing_started_at' => 'datetime',
        'packing_completed_at' => 'datetime',
        'shipping_date' => 'datetime',
        'total_weight_kg' => 'decimal:3',
        'total_volume_cubic_meters' => 'decimal:3',
        'order_value' => 'decimal:2',
        'delivery_instructions' => 'array',
        'special_handling_requirements' => 'array',
        'fulfillment_metadata' => 'array',
    ];

    /**
     * Get the customer company.
     */
    public function customerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'customer_company_id');
    }

    /**
     * Get the fulfillment center.
     */
    public function fulfillmentCenter(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'fulfillment_center_id');
    }

    /**
     * Get the assigned warehouse user.
     */
    public function assignedWarehouseUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_warehouse_user_id');
    }

    /**
     * Get the order fulfillment line items.
     */
    public function lineItems(): HasMany
    {
        return $this->hasMany(OrderFulfillmentLineItem::class);
    }

    /**
     * Get the delivery tasks for this order.
     */
    public function deliveryTasks(): HasMany
    {
        return $this->hasMany(DeliveryTask::class);
    }

    /**
     * Get the customer notifications for this order.
     */
    public function customerNotifications(): HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by order type.
     */
    public function scopeByOrderType($query, string $orderType)
    {
        return $query->where('order_type', $orderType);
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by customer.
     */
    public function scopeByCustomer($query, int $customerId)
    {
        return $query->where('customer_company_id', $customerId);
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the order is in progress.
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, ['processing', 'picked', 'packed', 'shipped']);
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the fulfillment duration in hours.
     */
    public function getFulfillmentDurationHoursAttribute(): ?float
    {
        if (!$this->order_date || !$this->shipping_date) {
            return null;
        }

        return $this->order_date->diffInHours($this->shipping_date);
    }

    /**
     * Get the delivery duration in hours.
     */
    public function getDeliveryDurationHoursAttribute(): ?float
    {
        if (!$this->shipping_date || !$this->actual_delivery_date) {
            return null;
        }

        return $this->shipping_date->diffInHours($this->actual_delivery_date);
    }

    /**
     * Generate a unique order number.
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'OF';
        $date = now()->format('Ymd');
        $sequence = str_pad(
            static::whereDate('created_at', today())->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        return "{$prefix}-{$date}-{$sequence}";
    }
}