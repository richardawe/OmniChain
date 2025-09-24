import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token'),
        isAuthenticated: false,
        loading: false,
        error: null
    }),

    getters: {
        isLoggedIn: (state) => !!state.token && state.isAuthenticated,
        userRole: (state) => state.user?.role,
        userPermissions: (state) => state.user?.permissions || []
    },

    actions: {
        async login(credentials) {
            this.loading = true
            this.error = null

            try {
                const response = await axios.post('/api/v1/auth/login', credentials)
                const { user, token } = response.data.data

                this.user = user
                this.token = token
                this.isAuthenticated = true

                localStorage.setItem('token', token)
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

                return { success: true }
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed'
                return { success: false, error: this.error }
            } finally {
                this.loading = false
            }
        },

        async logout() {
            try {
                await axios.post('/api/v1/auth/logout')
            } catch (error) {
                console.error('Logout error:', error)
            } finally {
                this.user = null
                this.token = null
                this.isAuthenticated = false
                localStorage.removeItem('token')
                delete axios.defaults.headers.common['Authorization']
            }
        },

        async fetchUser() {
            if (!this.token) return

            try {
                const response = await axios.get('/api/v1/auth/me')
                this.user = response.data.data
                this.isAuthenticated = true
            } catch (error) {
                this.logout()
            }
        },

        setToken(token) {
            this.token = token
            this.isAuthenticated = true
            localStorage.setItem('token', token)
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        },

        clearError() {
            this.error = null
        }
    }
})
