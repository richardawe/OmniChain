<?php

namespace App\Services;

use Pusher\Pusher;
use Illuminate\Support\Facades\Log;

class WebSocketService
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => config('broadcasting.connections.pusher.options.useTLS', true),
            ]
        );
    }

    /**
     * Broadcast a real-time update to a specific channel
     */
    public function broadcast(string $channel, string $event, array $data): bool
    {
        try {
            $this->pusher->trigger($channel, $event, $data);
            Log::info('WebSocket broadcast sent', [
                'channel' => $channel,
                'event' => $event,
                'data' => $data
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('WebSocket broadcast failed', [
                'channel' => $channel,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Broadcast freight order updates
     */
    public function broadcastFreightOrderUpdate(array $orderData): bool
    {
        return $this->broadcast(
            'freight-orders',
            'order-updated',
            [
                'type' => 'freight_order',
                'data' => $orderData,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast manufacturing updates
     */
    public function broadcastManufacturingUpdate(string $type, array $data): bool
    {
        return $this->broadcast(
            'manufacturing',
            'manufacturing-updated',
            [
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast inventory updates
     */
    public function broadcastInventoryUpdate(string $type, array $data): bool
    {
        return $this->broadcast(
            'inventory',
            'inventory-updated',
            [
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast delivery updates
     */
    public function broadcastDeliveryUpdate(string $type, array $data): bool
    {
        return $this->broadcast(
            'delivery',
            'delivery-updated',
            [
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast return updates
     */
    public function broadcastReturnUpdate(string $type, array $data): bool
    {
        return $this->broadcast(
            'returns',
            'return-updated',
            [
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast supplier updates
     */
    public function broadcastSupplierUpdate(string $type, array $data): bool
    {
        return $this->broadcast(
            'suppliers',
            'supplier-updated',
            [
                'type' => $type,
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast system notifications
     */
    public function broadcastNotification(array $notificationData): bool
    {
        return $this->broadcast(
            'notifications',
            'notification',
            [
                'type' => 'system_notification',
                'data' => $notificationData,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast to user-specific channel
     */
    public function broadcastToUser(int $userId, string $event, array $data): bool
    {
        return $this->broadcast(
            "user-{$userId}",
            $event,
            [
                'type' => 'user_specific',
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast to company-specific channel
     */
    public function broadcastToCompany(int $companyId, string $event, array $data): bool
    {
        return $this->broadcast(
            "company-{$companyId}",
            $event,
            [
                'type' => 'company_specific',
                'data' => $data,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast location tracking updates
     */
    public function broadcastLocationUpdate(array $locationData): bool
    {
        return $this->broadcast(
            'tracking',
            'location-updated',
            [
                'type' => 'location_tracking',
                'data' => $locationData,
                'timestamp' => now()->toISOString()
            ]
        );
    }

    /**
     * Broadcast system alerts
     */
    public function broadcastAlert(string $level, string $message, array $context = []): bool
    {
        return $this->broadcast(
            'alerts',
            'system-alert',
            [
                'type' => 'system_alert',
                'level' => $level,
                'message' => $message,
                'context' => $context,
                'timestamp' => now()->toISOString()
            ]
        );
    }
}
