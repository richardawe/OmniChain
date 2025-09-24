import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: [],
        currentProduct: null,
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
        getProductById: (state) => (id) => state.products.find(product => product.id === id),
        activeProducts: (state) => state.products.filter(product => product.status === 'active'),
        productsByCategory: (state) => (category) => state.products.filter(product => product.category === category)
    },

    actions: {
        async fetchProducts(params = {}) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.get('/api/v1/products', { params })
                this.products = response.data.data.data
                this.pagination = {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total
                }
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch products'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async createProduct(productData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/products', productData)
                this.products.unshift(response.data.data)
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create product'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async updateProduct(id, productData) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.put(`/api/v1/products/${id}`, productData)
                const index = this.products.findIndex(product => product.id === id)
                if (index !== -1) {
                    this.products[index] = response.data.data
                }
                return { success: true, data: response.data.data }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update product'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async deleteProduct(id) {
            this.loading = true
            this.error = null

            try {
                await axios.delete(`/api/v1/products/${id}`)
                this.products = this.products.filter(product => product.id !== id)
                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete product'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        setCurrentProduct(product) {
            this.currentProduct = product
        },

        clearError() {
            this.error = null
        }
    }
})
