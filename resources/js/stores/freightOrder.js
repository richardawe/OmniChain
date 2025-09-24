import { defineStore } from 'pinia'
import axios from 'axios'

export const useFreightOrderStore = defineStore('freightOrder', {
    state: () => ({
        freightOrders: [],
        currentOrder: null,
        loading: false,
        error: null,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0
        },
        filters: {
            status: '',
            service_type: '',
            priority: '',
            date_from: '',
            date_to: ''
        }
    }),

    getters: {
        getOrderById: (state) => (id) => state.freightOrders.find(order => order.id === id),
        ordersByStatus: (state) => (status) => state.freightOrders.filter(order => order.status === status),
        urgentOrders: (state) => state.freightOrders.filter(order => order.priority === 'urgent')
    },

    actions: {
        async fetchFreightOrders(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/freight-orders', { params })
                this.freightOrders = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch freight orders'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createFreightOrder(orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/freight-orders', orderData)
                this.freightOrders.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create freight order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateFreightOrder(id, orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/freight-orders/${id}`, orderData)
                const index = this.freightOrders.findIndex(order => order.id === id)
                if (index !== -1) {
                    this.freightOrders[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update freight order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteFreightOrder(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/freight-orders/${id}`)
                this.freightOrders = this.freightOrders.filter(order => order.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete freight order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async trackOrder(orderNumber) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get(`/api/v1/freight-orders/track/${orderNumber}`)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to track order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async getAvailableCarriers(orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/freight-orders/available-carriers', orderData)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to get available carriers'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        setCurrentOrder(order) {
            this.currentOrder = order
        },

        setFilters(filters) {
            this.filters = { ...this.filters, ...filters }
        },

        clearFilters() {
            this.filters = {
                status: '',
                service_type: '',
                priority: '',
                date_from: '',
                date_to: ''
            }
        },

        clearError() {
            this.error = null
        }
    }
})
