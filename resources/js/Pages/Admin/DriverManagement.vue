<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Driver Management</h1>
                        <p class="text-gray-600 mt-1">Manage driver registrations and approvals</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button 
                            @click="refreshData"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Drivers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.total_drivers || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ stats.pending_approval || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active</p>
                            <p class="text-2xl font-bold text-green-600">{{ stats.active_drivers || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Busy</p>
                            <p class="text-2xl font-bold text-orange-600">{{ stats.busy_drivers || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Offline</p>
                            <p class="text-2xl font-bold text-gray-600">{{ stats.offline_drivers || 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Drivers</label>
                            <input
                                v-model="searchQuery"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Search by name, email, or license..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>
                        <div class="sm:w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                            <select
                                v-model="statusFilter"
                                @change="loadDrivers"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="all">All Drivers</option>
                                <option value="pending_approval">Pending Approval</option>
                                <option value="available">Available</option>
                                <option value="busy">Busy</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drivers Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Drivers</h3>
                </div>
                
                <div v-if="loading" class="p-8 text-center">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading drivers...
                    </div>
                </div>

                <div v-else-if="drivers.length === 0" class="p-8 text-center text-gray-500">
                    No drivers found
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="driver in drivers" :key="driver.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ driver.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ driver.name }}</div>
                                            <div class="text-sm text-gray-500">License: {{ driver.driver_license }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ driver.email }}</div>
                                    <div class="text-sm text-gray-500">{{ driver.phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ driver.vehicle_make }} {{ driver.vehicle_model }}</div>
                                    <div class="text-sm text-gray-500">{{ driver.vehicle_type }} • {{ driver.max_capacity }}kg</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="getStatusBadgeClass(driver.status)"
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                    >
                                        {{ getStatusText(driver.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(driver.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="viewDriverDetails(driver)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            View
                                        </button>
                                        <button
                                            v-if="driver.status === 'pending_approval'"
                                            @click="approveDriver(driver)"
                                            class="text-green-600 hover:text-green-900"
                                        >
                                            Approve
                                        </button>
                                        <button
                                            v-if="driver.status === 'pending_approval'"
                                            @click="rejectDriver(driver)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Driver Details Modal -->
        <div v-if="showDriverModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Driver Details</h3>
                        <button @click="showDriverModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div v-if="selectedDriver" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.email }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Driver License</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.driver_license }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.vehicle_make }} {{ selectedDriver.vehicle_model }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Capacity</label>
                                <p class="text-sm text-gray-900">{{ selectedDriver.max_capacity }}kg</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                @click="showDriverModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
                            >
                                Close
                            </button>
                            <button
                                v-if="selectedDriver.status === 'pending_approval'"
                                @click="approveDriver(selectedDriver)"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700"
                            >
                                Approve Driver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve/Reject Modal -->
        <div v-if="showActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ actionType === 'approve' ? 'Approve Driver' : 'Reject Driver' }}
                    </h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ actionType === 'approve' ? 'Approval Notes (Optional)' : 'Rejection Reason' }}
                        </label>
                        <textarea
                            v-model="actionNotes"
                            :placeholder="actionType === 'approve' ? 'Add any notes about this approval...' : 'Please provide a reason for rejection...'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            rows="3"
                        ></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="showActionModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button
                            @click="confirmAction"
                            :class="actionType === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
                            class="px-4 py-2 text-sm font-medium text-white rounded-lg"
                        >
                            {{ actionType === 'approve' ? 'Approve' : 'Reject' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'

// Reactive data
const drivers = ref([])
const stats = ref({})
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('all')
const showDriverModal = ref(false)
const showActionModal = ref(false)
const selectedDriver = ref(null)
const actionType = ref('')
const actionNotes = ref('')

// Debounced search
let searchTimeout = null
const debouncedSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        loadDrivers()
    }, 500)
}

// Methods
const loadDrivers = async () => {
    loading.value = true
    try {
        const params = new URLSearchParams({
            status: statusFilter.value,
            search: searchQuery.value
        })
        
        const response = await fetch(`/api/v1/admin/drivers?${params}`)
        const data = await response.json()
        
        if (data.success) {
            drivers.value = data.data.data
        }
    } catch (error) {
        console.error('Error loading drivers:', error)
    } finally {
        loading.value = false
    }
}

const loadStats = async () => {
    try {
        const response = await fetch('/api/v1/admin/driver-stats')
        const data = await response.json()
        
        if (data.success) {
            stats.value = data.data
        }
    } catch (error) {
        console.error('Error loading stats:', error)
    }
}

const refreshData = () => {
    loadDrivers()
    loadStats()
}

const viewDriverDetails = (driver) => {
    selectedDriver.value = driver
    showDriverModal.value = true
}

const approveDriver = (driver) => {
    selectedDriver.value = driver
    actionType.value = 'approve'
    actionNotes.value = ''
    showActionModal.value = true
}

const rejectDriver = (driver) => {
    selectedDriver.value = driver
    actionType.value = 'reject'
    actionNotes.value = ''
    showActionModal.value = true
}

const confirmAction = async () => {
    if (actionType.value === 'reject' && !actionNotes.value.trim()) {
        alert('Please provide a reason for rejection')
        return
    }
    
    try {
        const url = actionType.value === 'approve' 
            ? `/api/v1/admin/drivers/${selectedDriver.value.id}/approve`
            : `/api/v1/admin/drivers/${selectedDriver.value.id}/reject`
            
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                [actionType.value === 'approve' ? 'notes' : 'reason']: actionNotes.value
            })
        })
        
        const data = await response.json()
        
        if (data.success) {
            showActionModal.value = false
            showDriverModal.value = false
            refreshData()
            alert(`Driver ${actionType.value}d successfully`)
        } else {
            alert(data.message || 'Action failed')
        }
    } catch (error) {
        console.error('Error performing action:', error)
        alert('Action failed. Please try again.')
    }
}

const getStatusBadgeClass = (status) => {
    const classes = {
        'pending_approval': 'bg-yellow-100 text-yellow-800',
        'available': 'bg-green-100 text-green-800',
        'busy': 'bg-orange-100 text-orange-800',
        'offline': 'bg-gray-100 text-gray-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
    const texts = {
        'pending_approval': 'Pending Approval',
        'available': 'Available',
        'busy': 'Busy',
        'offline': 'Offline'
    }
    return texts[status] || status
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString()
}

// Lifecycle
onMounted(() => {
    loadDrivers()
    loadStats()
})
</script>
