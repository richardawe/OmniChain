import { defineStore } from 'pinia'
import axios from 'axios'

export const useSupplierStore = defineStore('supplier', {
    state: () => ({
        suppliers: [],
        purchaseOrders: [],
        supplierContracts: [],
        supplierPerformance: [],
        supplierCatalogs: [],
        supplierOnboarding: [],
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
        getSupplierById: (state) => (id) => state.suppliers.find(supplier => supplier.id === id),
        activeSuppliers: (state) => state.suppliers.filter(supplier => supplier.status === 'active'),
        pendingOnboarding: (state) => state.supplierOnboarding.filter(item => item.status === 'pending'),
        activeContracts: (state) => state.supplierContracts.filter(contract => contract.status === 'active')
    },

    actions: {
        // Purchase Orders
        async fetchPurchaseOrders(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/supplier-procurement/purchase-orders', { params })
                this.purchaseOrders = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch purchase orders'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createPurchaseOrder(orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/supplier-procurement/purchase-orders', orderData)
                this.purchaseOrders.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create purchase order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updatePurchaseOrder(id, orderData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/supplier-procurement/purchase-orders/${id}`, orderData)
                const index = this.purchaseOrders.findIndex(order => order.id === id)
                if (index !== -1) {
                    this.purchaseOrders[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update purchase order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deletePurchaseOrder(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/supplier-procurement/purchase-orders/${id}`)
                this.purchaseOrders = this.purchaseOrders.filter(order => order.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete purchase order'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Supplier Contracts
        async fetchSupplierContracts(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/supplier-procurement/contracts', { params })
                this.supplierContracts = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch supplier contracts'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createSupplierContract(contractData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/supplier-procurement/contracts', contractData)
                this.supplierContracts.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create supplier contract'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateSupplierContract(id, contractData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/supplier-procurement/contracts/${id}`, contractData)
                const index = this.supplierContracts.findIndex(contract => contract.id === id)
                if (index !== -1) {
                    this.supplierContracts[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update supplier contract'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteSupplierContract(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/supplier-procurement/contracts/${id}`)
                this.supplierContracts = this.supplierContracts.filter(contract => contract.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete supplier contract'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Supplier Performance
        async fetchSupplierPerformance(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/supplier-procurement/performance', { params })
                this.supplierPerformance = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch supplier performance'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createSupplierPerformance(performanceData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/supplier-procurement/performance', performanceData)
                this.supplierPerformance.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create supplier performance'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Supplier Catalogs
        async fetchSupplierCatalogs(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/supplier-procurement/catalogs', { params })
                this.supplierCatalogs = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch supplier catalogs'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createSupplierCatalog(catalogData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/supplier-procurement/catalogs', catalogData)
                this.supplierCatalogs.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create supplier catalog'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Supplier Onboarding
        async fetchSupplierOnboarding(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/supplier-procurement/onboarding', { params })
                this.supplierOnboarding = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch supplier onboarding'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createSupplierOnboarding(onboardingData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/supplier-procurement/onboarding', onboardingData)
                this.supplierOnboarding.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create supplier onboarding'
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
