import { defineStore } from 'pinia'
import axios from 'axios'

export const useInventoryStore = defineStore('inventory', {
    state: () => ({
        inventoryBalances: [],
        warehouseBins: [],
        inboundReceiving: [],
        outboundShipments: [],
        cycleCounts: [],
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
        getInventoryById: (state) => (id) => state.inventoryBalances.find(item => item.id === id),
        lowStockItems: (state) => state.inventoryBalances.filter(item => item.quantity_on_hand <= item.reorder_point),
        outOfStockItems: (state) => state.inventoryBalances.filter(item => item.quantity_on_hand === 0),
        activeBins: (state) => state.warehouseBins.filter(bin => bin.status === 'active')
    },

    actions: {
        // Inventory Balances
        async fetchInventoryBalances(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/inventory-warehouse/inventory-balances', { params })
                this.inventoryBalances = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch inventory balances'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createInventoryBalance(balanceData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/inventory-warehouse/inventory-balances', balanceData)
                this.inventoryBalances.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create inventory balance'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateInventoryBalance(id, balanceData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/inventory-warehouse/inventory-balances/${id}`, balanceData)
                const index = this.inventoryBalances.findIndex(item => item.id === id)
                if (index !== -1) {
                    this.inventoryBalances[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update inventory balance'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteInventoryBalance(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/inventory-warehouse/inventory-balances/${id}`)
                this.inventoryBalances = this.inventoryBalances.filter(item => item.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete inventory balance'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Warehouse Bins
        async fetchWarehouseBins(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/inventory-warehouse/warehouse-bins', { params })
                this.warehouseBins = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch warehouse bins'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createWarehouseBin(binData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/inventory-warehouse/warehouse-bins', binData)
                this.warehouseBins.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create warehouse bin'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateWarehouseBin(id, binData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/inventory-warehouse/warehouse-bins/${id}`, binData)
                const index = this.warehouseBins.findIndex(bin => bin.id === id)
                if (index !== -1) {
                    this.warehouseBins[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update warehouse bin'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteWarehouseBin(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/inventory-warehouse/warehouse-bins/${id}`)
                this.warehouseBins = this.warehouseBins.filter(bin => bin.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete warehouse bin'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Inbound Receiving
        async fetchInboundReceiving(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/inventory-warehouse/inbound-receiving', { params })
                this.inboundReceiving = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch inbound receiving'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createInboundReceiving(receivingData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/inventory-warehouse/inbound-receiving', receivingData)
                this.inboundReceiving.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create inbound receiving'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Outbound Shipments
        async fetchOutboundShipments(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/inventory-warehouse/outbound-shipments', { params })
                this.outboundShipments = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch outbound shipments'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createOutboundShipment(shipmentData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/inventory-warehouse/outbound-shipments', shipmentData)
                this.outboundShipments.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create outbound shipment'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Cycle Counts
        async fetchCycleCounts(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/inventory-warehouse/cycle-counts', { params })
                this.cycleCounts = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch cycle counts'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createCycleCount(countData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/inventory-warehouse/cycle-counts', countData)
                this.cycleCounts.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create cycle count'
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
