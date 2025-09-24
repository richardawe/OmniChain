import Pusher from 'pusher-js'
import { useNotificationStore } from '../stores/notification'

class WebSocketService {
    constructor() {
        this.pusher = null
        this.channels = new Map()
        this.isConnected = false
        this.reconnectAttempts = 0
        this.maxReconnectAttempts = 5
        this.reconnectDelay = 1000
    }

    /**
     * Initialize WebSocket connection
     */
    initialize() {
        if (this.pusher) {
            return this.pusher
        }

        this.pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            encrypted: true,
            authEndpoint: '/api/v1/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            }
        })

        this.setupEventHandlers()
        return this.pusher
    }

    /**
     * Setup connection event handlers
     */
    setupEventHandlers() {
        this.pusher.connection.bind('connected', () => {
            this.isConnected = true
            this.reconnectAttempts = 0
            console.log('WebSocket connected')
        })

        this.pusher.connection.bind('disconnected', () => {
            this.isConnected = false
            console.log('WebSocket disconnected')
        })

        this.pusher.connection.bind('error', (error) => {
            console.error('WebSocket error:', error)
            this.handleReconnection()
        })
    }

    /**
     * Handle reconnection logic
     */
    handleReconnection() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++
            setTimeout(() => {
                console.log(`Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`)
                this.pusher.connect()
            }, this.reconnectDelay * this.reconnectAttempts)
        }
    }

    /**
     * Subscribe to a channel
     */
    subscribe(channelName, eventName, callback) {
        if (!this.pusher) {
            this.initialize()
        }

        const channel = this.pusher.subscribe(channelName)
        channel.bind(eventName, callback)
        
        this.channels.set(`${channelName}:${eventName}`, { channel, callback })
        
        return channel
    }

    /**
     * Unsubscribe from a channel
     */
    unsubscribe(channelName, eventName) {
        const key = `${channelName}:${eventName}`
        const subscription = this.channels.get(key)
        
        if (subscription) {
            subscription.channel.unbind(eventName, subscription.callback)
            this.channels.delete(key)
        }
    }

    /**
     * Subscribe to freight order updates
     */
    subscribeToFreightOrders(callback) {
        return this.subscribe('freight-orders', 'order-updated', (data) => {
            console.log('Freight order update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to manufacturing updates
     */
    subscribeToManufacturing(callback) {
        return this.subscribe('manufacturing', 'manufacturing-updated', (data) => {
            console.log('Manufacturing update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to inventory updates
     */
    subscribeToInventory(callback) {
        return this.subscribe('inventory', 'inventory-updated', (data) => {
            console.log('Inventory update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to delivery updates
     */
    subscribeToDelivery(callback) {
        return this.subscribe('delivery', 'delivery-updated', (data) => {
            console.log('Delivery update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to return updates
     */
    subscribeToReturns(callback) {
        return this.subscribe('returns', 'return-updated', (data) => {
            console.log('Return update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to supplier updates
     */
    subscribeToSuppliers(callback) {
        return this.subscribe('suppliers', 'supplier-updated', (data) => {
            console.log('Supplier update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to notifications
     */
    subscribeToNotifications(callback) {
        return this.subscribe('notifications', 'notification', (data) => {
            console.log('Notification received:', data)
            const notificationStore = useNotificationStore()
            notificationStore.addNotification({
                type: data.data.type || 'info',
                title: data.data.title || 'Notification',
                message: data.data.message || 'You have a new notification'
            })
            callback(data)
        })
    }

    /**
     * Subscribe to user-specific updates
     */
    subscribeToUser(userId, callback) {
        return this.subscribe(`user-${userId}`, 'user-update', (data) => {
            console.log('User update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to company-specific updates
     */
    subscribeToCompany(companyId, callback) {
        return this.subscribe(`company-${companyId}`, 'company-update', (data) => {
            console.log('Company update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to location tracking
     */
    subscribeToTracking(callback) {
        return this.subscribe('tracking', 'location-updated', (data) => {
            console.log('Location update:', data)
            callback(data)
        })
    }

    /**
     * Subscribe to system alerts
     */
    subscribeToAlerts(callback) {
        return this.subscribe('alerts', 'system-alert', (data) => {
            console.log('System alert:', data)
            const notificationStore = useNotificationStore()
            notificationStore.addNotification({
                type: data.level === 'error' ? 'error' : data.level === 'warning' ? 'warning' : 'info',
                title: 'System Alert',
                message: data.message
            })
            callback(data)
        })
    }

    /**
     * Disconnect from WebSocket
     */
    disconnect() {
        if (this.pusher) {
            this.pusher.disconnect()
            this.channels.clear()
            this.isConnected = false
        }
    }

    /**
     * Get connection state
     */
    getConnectionState() {
        return this.pusher ? this.pusher.connection.state : 'disconnected'
    }

    /**
     * Check if connected
     */
    isConnected() {
        return this.isConnected
    }
}

// Create singleton instance
const websocketService = new WebSocketService()

export default websocketService
