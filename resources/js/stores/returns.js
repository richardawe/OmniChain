import { defineStore } from 'pinia'
import axios from 'axios'

export const useReturnsStore = defineStore('returns', {
    state: () => ({
        returnRequests: [],
        returnAuthorizations: [],
        returnLineItems: [],
        returnProcessing: [],
        returnReasons: [],
        returnDispositions: [],
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
        getReturnRequestById: (state) => (id) => state.returnRequests.find(request => request.id === id),
        pendingReturns: (state) => state.returnRequests.filter(request => request.status === 'requested'),
        authorizedReturns: (state) => state.returnRequests.filter(request => request.status === 'authorized'),
        completedReturns: (state) => state.returnRequests.filter(request => request.status === 'completed')
    },

    actions: {
        // Return Requests
        async fetchReturnRequests(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-requests', { params })
                this.returnRequests = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return requests'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnRequest(requestData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-requests', requestData)
                this.returnRequests.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return request'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateReturnRequest(id, requestData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/returns/return-requests/${id}`, requestData)
                const index = this.returnRequests.findIndex(request => request.id === id)
                if (index !== -1) {
                    this.returnRequests[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update return request'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteReturnRequest(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/returns/return-requests/${id}`)
                this.returnRequests = this.returnRequests.filter(request => request.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete return request'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Return Authorizations
        async fetchReturnAuthorizations(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-authorizations', { params })
                this.returnAuthorizations = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return authorizations'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnAuthorization(authData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-authorizations', authData)
                this.returnAuthorizations.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return authorization'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Return Line Items
        async fetchReturnLineItems(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-line-items', { params })
                this.returnLineItems = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return line items'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnLineItem(lineItemData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-line-items', lineItemData)
                this.returnLineItems.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return line item'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Return Processing
        async fetchReturnProcessing(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-processing', { params })
                this.returnProcessing = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return processing'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnProcessing(processingData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-processing', processingData)
                this.returnProcessing.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return processing'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Return Reasons
        async fetchReturnReasons(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-reasons', { params })
                this.returnReasons = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return reasons'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnReason(reasonData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-reasons', reasonData)
                this.returnReasons.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return reason'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        // Return Dispositions
        async fetchReturnDispositions(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/returns/return-dispositions', { params })
                this.returnDispositions = response.data.data.data
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch return dispositions'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createReturnDisposition(dispositionData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/returns/return-dispositions', dispositionData)
                this.returnDispositions.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create return disposition'
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
