import { defineStore } from 'pinia'
import axios from 'axios'

export const useCompanyStore = defineStore('company', {
    state: () => ({
        companies: [],
        currentCompany: null,
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
        getCompanyById: (state) => (id) => state.companies.find(company => company.id === id),
        activeCompanies: (state) => state.companies.filter(company => company.status === 'active')
    },

    actions: {
        async fetchCompanies(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/companies', { params })
                this.companies = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch companies'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createCompany(companyData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/companies', companyData)
                this.companies.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create company'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateCompany(id, companyData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/companies/${id}`, companyData)
                const index = this.companies.findIndex(company => company.id === id)
                if (index !== -1) {
                    this.companies[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update company'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteCompany(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/companies/${id}`)
                this.companies = this.companies.filter(company => company.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete company'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        setCurrentCompany(company) {
            this.currentCompany = company
        },

        clearError() {
            this.error = null
        }
    }
})
