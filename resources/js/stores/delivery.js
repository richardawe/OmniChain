import { defineStore } from 'pinia'
import axios from 'axios'

export const useDeliveryStore = defineStore('delivery', {
    state: () => ({
        orderFulfillments: [],
        deliveryTasks: [],
        customerNotifications: [],
        deliveryExceptions: [],
        geofenceEvents: [],
        loading: false,
        error: null,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0
        }
    }),

    getters: {
        getOrderFulfillmentById: (state) => (id) => state.orderFulfillments.find(fulfillment => fulfillment.id === id),
        getDeliveryTaskById: (state) => (id) => state.deliveryTasks.find(task => task.id === id),
        pendingTasks: (state) => state.deliveryTasks.filter(task => task.status === 'pending'),
        inProgressTasks: (state) => state.deliveryTasks.filter(task => task.status === 'in_progress'),
        completedTasks: (state) => state.deliveryTasks.filter(task => task.status === 'completed'),
        openExceptions: (state) => state.deliveryExceptions.filter(exception => exception.status === 'open')
    },

    actions: {
        // Order Fulfillments
        async fetchOrderFulfillments(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/delivery/order-fulfillments', { params })
                this.orderFulfillments = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch order fulfillments'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createOrderFulfillment(fulfillmentData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/delivery/order-fulfillments', fulfillmentData)
                this.orderFulfillments.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create order fulfillment'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateOrderFulfillment(id, fulfillmentData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/delivery/order-fulfillments/${id}`, fulfillmentData)
                const index = this.orderFulfillments.findIndex(fulfillment => fulfillment.id === id)
                if (index !== -1) {
                    this.orderFulfillments[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update order fulfillment'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteOrderFulfillment(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/delivery/order-fulfillments/${id}`)
                this.orderFulfillments = this.orderFulfillments.filter(fulfillment => fulfillment.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete order fulfillment'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Delivery Tasks
        async fetchDeliveryTasks(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/delivery/delivery-tasks', { params })
                this.deliveryTasks = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch delivery tasks'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createDeliveryTask(taskData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/delivery/delivery-tasks', taskData)
                this.deliveryTasks.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create delivery task'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateDeliveryTask(id, taskData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/delivery/delivery-tasks/${id}`, taskData)
                const index = this.deliveryTasks.findIndex(task => task.id === id)
                if (index !== -1) {
                    this.deliveryTasks[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update delivery task'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteDeliveryTask(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/delivery/delivery-tasks/${id}`)
                this.deliveryTasks = this.deliveryTasks.filter(task => task.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete delivery task'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Customer Notifications
        async fetchCustomerNotifications(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/delivery/customer-notifications', { params })
                this.customerNotifications = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch customer notifications'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createCustomerNotification(notificationData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/delivery/customer-notifications', notificationData)
                this.customerNotifications.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create customer notification'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Delivery Exceptions
        async fetchDeliveryExceptions(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/delivery/delivery-exceptions', { params })
                this.deliveryExceptions = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch delivery exceptions'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createDeliveryException(exceptionData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/delivery/delivery-exceptions', exceptionData)
                this.deliveryExceptions.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create delivery exception'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Geofence Events
        async fetchGeofenceEvents(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/delivery/geofence-events', { params })
                this.geofenceEvents = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch geofence events'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createGeofenceEvent(eventData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/delivery/geofence-events', eventData)
                this.geofenceEvents.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create geofence event'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        clearError() {
            this.error = null
        }
    }
})
