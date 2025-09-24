import { defineStore } from 'pinia'
import axios from 'axios'

export const useManufacturingStore = defineStore('manufacturing', {
    state: () => ({
        workOrders: [],
        machines: [],
        qualityInspections: [],
        batchTracking: [],
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
        getWorkOrderById: (state) => (id) => state.workOrders.find(order => order.id === id),
        getMachineById: (state) => (id) => state.machines.find(machine => machine.id === id),
        activeMachines: (state) => state.machines.filter(machine => machine.status === 'active'),
        pendingWorkOrders: (state) => state.workOrders.filter(order => order.status === 'pending')
    },

    actions: {
        // Work Orders
        async fetchWorkOrders(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/manufacturing/work-orders', { params })
                this.workOrders = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch work orders'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createWorkOrder(orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/manufacturing/work-orders', orderData)
                this.workOrders.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create work order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateWorkOrder(id, orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/manufacturing/work-orders/${id}`, orderData)
                const index = this.workOrders.findIndex(order => order.id === id)
                if (index !== -1) {
                    this.workOrders[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update work order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteWorkOrder(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/manufacturing/work-orders/${id}`)
                this.workOrders = this.workOrders.filter(order => order.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete work order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Machines
        async fetchMachines(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/manufacturing/machines', { params })
                this.machines = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch machines'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createMachine(machineData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/manufacturing/machines', machineData)
                this.machines.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create machine'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateMachine(id, machineData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/manufacturing/machines/${id}`, machineData)
                const index = this.machines.findIndex(machine => machine.id === id)
                if (index !== -1) {
                    this.machines[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update machine'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteMachine(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/manufacturing/machines/${id}`)
                this.machines = this.machines.filter(machine => machine.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete machine'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Quality Inspections
        async fetchQualityInspections(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/manufacturing/quality-inspections', { params })
                this.qualityInspections = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch quality inspections'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createQualityInspection(inspectionData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/manufacturing/quality-inspections', inspectionData)
                this.qualityInspections.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create quality inspection'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Batch Tracking
        async fetchBatchTracking(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/manufacturing/batch-tracking', { params })
                this.batchTracking = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch batch tracking'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createBatchTracking(batchData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/manufacturing/batch-tracking', batchData)
                this.batchTracking.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create batch tracking'
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
