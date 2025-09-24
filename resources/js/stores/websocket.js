import { defineStore } from 'pinia'
import websocketService from '../services/websocket'

export const useWebSocketStore = defineStore('websocket', {
    state: () => ({
        isConnected: false,
        connectionState: 'disconnected',
        subscriptions: [],
        lastMessage: null,
        error: null
    }),

    getters: {
        isWebSocketConnected: (state) => state.isConnected,
        getConnectionState: (state) => state.connectionState,
        getLastMessage: (state) => state.lastMessage,
        getSubscriptions: (state) => state.subscriptions
    },

    actions: {
        /**
         * Initialize WebSocket connection
         */
        async initialize() {
            try {
                websocketService.initialize()
                this.isConnected = true
                this.connectionState = 'connected'
                this.error = null
            } catch (error) {
                this.error = error.message
                console.error('WebSocket initialization failed:', error)
            }
        },

        /**
         * Subscribe to freight orders
         */
        subscribeToFreightOrders(callback) {
            const subscription = websocketService.subscribeToFreightOrders((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'freight-orders',
                subscription
            })
        },

        /**
         * Subscribe to manufacturing updates
         */
        subscribeToManufacturing(callback) {
            const subscription = websocketService.subscribeToManufacturing((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'manufacturing',
                subscription
            })
        },

        /**
         * Subscribe to inventory updates
         */
        subscribeToInventory(callback) {
            const subscription = websocketService.subscribeToInventory((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'inventory',
                subscription
            })
        },

        /**
         * Subscribe to delivery updates
         */
        subscribeToDelivery(callback) {
            const subscription = websocketService.subscribeToDelivery((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'delivery',
                subscription
            })
        },

        /**
         * Subscribe to return updates
         */
        subscribeToReturns(callback) {
            const subscription = websocketService.subscribeToReturns((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'returns',
                subscription
            })
        },

        /**
         * Subscribe to supplier updates
         */
        subscribeToSuppliers(callback) {
            const subscription = websocketService.subscribeToSuppliers((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'suppliers',
                subscription
            })
        },

        /**
         * Subscribe to notifications
         */
        subscribeToNotifications(callback) {
            const subscription = websocketService.subscribeToNotifications((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'notifications',
                subscription
            })
        },

        /**
         * Subscribe to user updates
         */
        subscribeToUser(userId, callback) {
            const subscription = websocketService.subscribeToUser(userId, (data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: `user-${userId}`,
                subscription
            })
        },

        /**
         * Subscribe to company updates
         */
        subscribeToCompany(companyId, callback) {
            const subscription = websocketService.subscribeToCompany(companyId, (data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: `company-${companyId}`,
                subscription
            })
        },

        /**
         * Subscribe to location tracking
         */
        subscribeToTracking(callback) {
            const subscription = websocketService.subscribeToTracking((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'tracking',
                subscription
            })
        },

        /**
         * Subscribe to system alerts
         */
        subscribeToAlerts(callback) {
            const subscription = websocketService.subscribeToAlerts((data) => {
                this.lastMessage = data
                callback(data)
            })
            this.subscriptions.push({
                type: 'alerts',
                subscription
            })
        },

        /**
         * Unsubscribe from a specific subscription
         */
        unsubscribe(type) {
            const subscriptionIndex = this.subscriptions.findIndex(sub => sub.type === type)
            if (subscriptionIndex !== -1) {
                const subscription = this.subscriptions[subscriptionIndex]
                websocketService.unsubscribe(subscription.type, 'update')
                this.subscriptions.splice(subscriptionIndex, 1)
            }
        },

        /**
         * Unsubscribe from all subscriptions
         */
        unsubscribeAll() {
            this.subscriptions.forEach(subscription => {
                websocketService.unsubscribe(subscription.type, 'update')
            })
            this.subscriptions = []
        },

        /**
         * Disconnect WebSocket
         */
        disconnect() {
            websocketService.disconnect()
            this.isConnected = false
            this.connectionState = 'disconnected'
            this.subscriptions = []
        },

        /**
         * Update connection state
         */
        updateConnectionState(state) {
            this.connectionState = state
            this.isConnected = state === 'connected'
        },

        /**
         * Clear error
         */
        clearError() {
            this.error = null
        }
    }
})
