<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-sm">O</span>
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold">OmniChain Driver</h1>
                            <p class="text-blue-200 text-xs">{{ driverName || 'Loading...' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Location Status Indicator -->
                        <div v-if="locationPermissionStatus === 'granted'" class="flex items-center space-x-1 text-green-200">
                            <span class="text-sm">📍</span>
                            <span class="text-xs">Location OK</span>
                        </div>
                        <div v-else-if="locationPermissionStatus === 'denied'" class="flex items-center space-x-1 text-red-200">
                            <span class="text-sm">📍</span>
                            <span class="text-xs">Location Denied</span>
                        </div>
                        <div v-else-if="locationPermissionStatus === 'not-supported'" class="flex items-center space-x-1 text-yellow-200">
                            <span class="text-sm">📍</span>
                            <span class="text-xs">No GPS</span>
                        </div>
                        <div v-else class="flex items-center space-x-1 text-blue-200">
                            <span class="text-sm">📍</span>
                            <span class="text-xs">Checking...</span>
                        </div>
                        
                        <div class="flex items-center space-x-1">
                            <div :class="connectionStatus === 'online' ? 'bg-green-400' : 'bg-red-400'" class="w-2 h-2 rounded-full"></div>
                            <span class="text-xs">{{ connectionStatus === 'online' ? 'Online' : 'Offline' }}</span>
                        </div>
                        <button @click="toggleMenu" class="p-2 rounded-lg hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <nav class="bg-white border-b border-gray-200">
            <div class="px-4">
                <div class="flex space-x-8">
                    <button 
                        @click="currentTab = 'tasks'"
                        :class="currentTab === 'tasks' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        📦 Tasks
                        <span v-if="pendingTasksCount > 0" class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ pendingTasksCount }}</span>
                    </button>
                    <button 
                        @click="currentTab = 'map'"
                        :class="currentTab === 'map' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        🗺️ Map
                    </button>
                    <button 
                        @click="currentTab = 'profile'"
                        :class="currentTab === 'profile' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        👤 Profile
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Tasks Tab -->
            <div v-if="currentTab === 'tasks'" class="p-4">
                <div class="space-y-4">
                    <!-- Task Status Summary -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm border">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ pendingTasksCount }}</div>
                                <div class="text-sm text-gray-600">Pending</div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ inProgressTasksCount }}</div>
                                <div class="text-sm text-gray-600">In Progress</div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm border">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ completedTasksCount }}</div>
                                <div class="text-sm text-gray-600">Completed</div>
                            </div>
                        </div>
                    </div>

                    <!-- Task Filter -->
                    <div class="flex space-x-2">
                        <button 
                            v-for="status in taskStatuses" 
                            :key="status.value"
                            @click="selectedTaskStatus = status.value"
                            :class="selectedTaskStatus === status.value ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            {{ status.label }}
                        </button>
                    </div>

                    <!-- Task List -->
                    <div class="space-y-3">
                        <div v-if="loading" class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-gray-600 mt-2">Loading tasks...</p>
                        </div>

                        <div v-else-if="filteredTasks.length === 0" class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-4">📦</div>
                            <p class="text-gray-600">No tasks found</p>
                        </div>

                        <div v-else>
                            <div v-for="task in filteredTasks" :key="task.id" class="bg-white rounded-lg shadow-sm border p-4">
                                <!-- Task Header -->
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ task.order_number }}</h3>
                                        <p class="text-sm text-gray-600">{{ task.pickup_location.name }} → {{ task.delivery_location.name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span :class="getTaskStatusClass(task.status, task.is_assigned)" class="px-2 py-1 rounded-full text-xs font-medium">
                                            {{ getTaskStatusLabel(task.status, task.is_assigned) }}
                                        </span>
                                        <span :class="getPriorityClass(task.priority)" class="px-2 py-1 rounded-full text-xs font-medium">
                                            {{ task.priority }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Task Details -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Pickup</p>
                                        <p class="font-medium">{{ task.pickup_location.address }}</p>
                                        <p class="text-sm text-gray-600">{{ task.pickup_location.city }}, {{ task.pickup_location.state }}</p>
                                        <p v-if="task.timing.requested_pickup_time" class="text-xs text-blue-600">
                                            📅 {{ formatTime(task.timing.requested_pickup_time) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Delivery</p>
                                        <p class="font-medium">{{ task.delivery_location.address }}</p>
                                        <p class="text-sm text-gray-600">{{ task.delivery_location.city }}, {{ task.delivery_location.state }}</p>
                                        <p v-if="task.timing.requested_delivery_time" class="text-xs text-green-600">
                                            📅 {{ formatTime(task.timing.requested_delivery_time) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Cargo Info -->
                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Cargo</p>
                                    <p class="font-medium">{{ task.cargo.description || 'No description' }}</p>
                                    <div class="flex space-x-4 mt-2 text-sm text-gray-600">
                                        <span v-if="task.cargo.weight">⚖️ {{ task.cargo.weight }}kg</span>
                                        <span v-if="task.cargo.volume">📦 {{ task.cargo.volume }}m³</span>
                                        <span v-if="task.cargo.value">💰 ${{ task.cargo.value }}</span>
                                    </div>
                                </div>

                                <!-- Task Actions -->
                                <div class="flex space-x-2">
                                    <button @click="viewTaskDetails(task)" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                        View Details
                                    </button>
                                    <button 
                                        v-if="!task.is_assigned"
                                        @click="claimOrder(task)"
                                        class="flex-1 bg-orange-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                        Claim Order
                                    </button>
                                    <button 
                                        v-else-if="canUpdateTaskStatus(task)"
                                        @click="updateTaskStatus(task)"
                                        class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                                        {{ getNextStatusAction(task.status) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Tab -->
            <div v-if="currentTab === 'map'" class="h-screen">
                <DriverMap 
                    :driver-location="driverLocation"
                    :tasks="tasks"
                    :current-task="selectedTask"
                    @task-selected="viewTaskDetails"
                    @status-updated="updateTaskStatus"
                    @navigation-started="startNavigation"
                />
            </div>

            <!-- Profile Tab -->
            <div v-if="currentTab === 'profile'" class="p-4">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <h3 class="text-lg font-semibold mb-4">Driver Profile</h3>
                    <div v-if="driverProfile" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ driverProfile.name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ driverProfile.email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ driverProfile.phone || 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Driver License</label>
                            <p class="mt-1 text-sm text-gray-900">{{ driverProfile.driver_license || 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ driverProfile.vehicle_type || 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span :class="getDriverStatusClass(driverProfile.status)" class="inline-block px-2 py-1 rounded-full text-xs font-medium">
                                {{ driverProfile.status }}
                            </span>
                        </div>
                    </div>
                    <button @click="logout" class="w-full mt-6 bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
        </main>

        <!-- Task Details Modal -->
        <div v-if="selectedTask && !showProofOfDelivery" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full max-h-96 overflow-y-auto">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">{{ selectedTask.order_number }}</h3>
                        <button @click="selectedTask = null" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Task details content here -->
                    <div>
                        <h4 class="font-medium mb-2">Pickup Instructions</h4>
                        <p class="text-sm text-gray-600">{{ selectedTask.pickup_instructions || 'No special instructions' }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">Delivery Instructions</h4>
                        <p class="text-sm text-gray-600">{{ selectedTask.delivery_instructions || 'No special instructions' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="startNavigation(selectedTask)" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium">
                            🧭 Navigate
                        </button>
                        <button @click="callLocation(selectedTask.pickup_location)" class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium">
                            📞 Call Pickup
                        </button>
                    </div>
                    <div v-if="selectedTask.status === 'in_transit'" class="pt-4 border-t border-gray-200">
                        <button @click="showProofOfDelivery = true" class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                            📋 Submit Proof of Delivery
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proof of Delivery Modal -->
        <ProofOfDelivery 
            v-if="showProofOfDelivery && selectedTask"
            :task="selectedTask"
            @close="showProofOfDelivery = false"
            @submitted="handleProofSubmitted"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import offlineStorage from '../../Utils/OfflineStorage.js'
import DriverMap from '../../Components/DriverMap.vue'
import ProofOfDelivery from '../../Components/ProofOfDelivery.vue'

// Reactive data
const currentTab = ref('tasks')
const selectedTaskStatus = ref('all')
const loading = ref(false)
const tasks = ref([])
const driverProfile = ref(null)
const selectedTask = ref(null)
const connectionStatus = ref('online')
const driverName = ref('')
const locationPermissionStatus = ref('unknown')
const driverLocation = ref(null)
const showProofOfDelivery = ref(false)

// Task statuses for filtering
const taskStatuses = [
    { value: 'all', label: 'All Tasks' },
    { value: 'booked', label: 'Pending' },
    { value: 'picked_up', label: 'Picked Up' },
    { value: 'in_transit', label: 'In Transit' },
    { value: 'delivered', label: 'Delivered' }
]

// Computed properties
const filteredTasks = computed(() => {
    if (selectedTaskStatus.value === 'all') {
        return tasks.value
    }
    return tasks.value.filter(task => task.status === selectedTaskStatus.value)
})

const pendingTasksCount = computed(() => tasks.value.filter(task => task.status === 'booked').length)
const inProgressTasksCount = computed(() => tasks.value.filter(task => ['picked_up', 'in_transit'].includes(task.status)).length)
const completedTasksCount = computed(() => tasks.value.filter(task => task.status === 'delivered').length)

// Methods
const loadTasks = async () => {
    loading.value = true
    try {
        const response = await fetch('/api/v1/driver/tasks', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (response.ok) {
            const data = await response.json()
            // Handle new API structure with assigned_tasks and available_orders
            const allTasks = [
                ...(data.data.assigned_tasks || []),
                ...(data.data.available_orders || [])
            ]
            tasks.value = allTasks
            
            // Store tasks offline
            await offlineStorage.storeTasks(allTasks)
        }
    } catch (error) {
        console.error('Error loading tasks:', error)
        
        // Load from offline storage if online request fails
        try {
            const offlineTasks = await offlineStorage.getTasks()
            tasks.value = offlineTasks
            console.log('Loaded tasks from offline storage')
        } catch (offlineError) {
            console.error('Failed to load offline tasks:', offlineError)
        }
    } finally {
        loading.value = false
    }
}

const loadDriverProfile = async () => {
    try {
        const response = await fetch('/api/v1/driver/profile', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (response.ok) {
            const data = await response.json()
            driverProfile.value = data.data
            driverName.value = data.data.name
        }
    } catch (error) {
        console.error('Error loading profile:', error)
    }
}

const viewTaskDetails = (task) => {
    selectedTask.value = task
}

const canUpdateTaskStatus = (task) => {
    return ['booked', 'picked_up', 'in_transit'].includes(task.status)
}

const claimOrder = async (task) => {
    try {
        const response = await fetch(`/api/v1/driver/orders/${task.id}/claim`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (response.ok) {
            // Mark task as assigned locally
            task.is_assigned = true
            task.status = 'booked'
            
            // Refresh tasks to get updated list
            await loadTasks()
            
            alert('Order claimed successfully!')
        } else {
            const error = await response.json()
            alert(`Failed to claim order: ${error.message}`)
        }
    } catch (error) {
        console.error('Error claiming order:', error)
        alert('Failed to claim order. Please try again.')
    }
}

const updateTaskStatus = async (task) => {
    const statusMap = {
        'booked': 'picked_up',
        'picked_up': 'in_transit',
        'in_transit': 'delivered'
    }
    
    const newStatus = statusMap[task.status]
    if (!newStatus) return
    
    // Get current location for status updates
    let locationData = null
    if (navigator.geolocation) {
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                })
            })
            
            locationData = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy,
                heading: position.coords.heading,
                speed: position.coords.speed
            }
        } catch (error) {
            console.warn('Could not get location:', error)
            
            // Handle different geolocation errors
            if (error.code === 1) {
                alert('Location permission denied. Please enable location access in your browser settings to track deliveries.')
            } else if (error.code === 2) {
                alert('Location unavailable. Please check your GPS settings.')
            } else if (error.code === 3) {
                alert('Location request timed out. Please try again.')
            }
        }
    } else {
        alert('Geolocation is not supported by this browser.')
    }
    
    const updateData = { 
        status: newStatus,
        location: locationData
    }
    
    try {
        const response = await fetch(`/api/v1/driver/tasks/${task.id}/status`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(updateData)
        })
        
        if (response.ok) {
            // Update local task status
            task.status = newStatus
            await loadTasks() // Refresh tasks
            
            // Start location tracking if starting delivery
            if (newStatus === 'picked_up' || newStatus === 'in_transit') {
                startLocationTracking()
            } else if (newStatus === 'delivered') {
                stopLocationTracking()
            }
        }
    } catch (error) {
        console.error('Error updating task status:', error)
        
        // Store update for offline sync
        await offlineStorage.addToSyncQueue(
            'task_status_update',
            updateData,
            `/api/v1/driver/tasks/${task.id}/status`,
            'PUT'
        )
        
        // Update local task status anyway
        task.status = newStatus
    }
}

const getNextStatusAction = (status) => {
    const actions = {
        'booked': 'Mark Picked Up',
        'picked_up': 'Start Delivery',
        'in_transit': 'Mark Delivered'
    }
    return actions[status] || 'Update Status'
}

const getTaskStatusClass = (status, isAssigned = true) => {
    if (!isAssigned) {
        return 'bg-orange-100 text-orange-800'
    }
    const classes = {
        'booked': 'bg-blue-100 text-blue-800',
        'picked_up': 'bg-yellow-100 text-yellow-800',
        'in_transit': 'bg-purple-100 text-purple-800',
        'delivered': 'bg-green-100 text-green-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getTaskStatusLabel = (status, isAssigned = true) => {
    if (!isAssigned) {
        return 'Available'
    }
    const labels = {
        'booked': 'Pending',
        'picked_up': 'Picked Up',
        'in_transit': 'In Transit',
        'delivered': 'Delivered'
    }
    return labels[status] || status
}

const getPriorityClass = (priority) => {
    const classes = {
        'high': 'bg-red-100 text-red-800',
        'medium': 'bg-yellow-100 text-yellow-800',
        'low': 'bg-green-100 text-green-800'
    }
    return classes[priority] || 'bg-gray-100 text-gray-800'
}

const getDriverStatusClass = (status) => {
    const classes = {
        'available': 'bg-green-100 text-green-800',
        'busy': 'bg-yellow-100 text-yellow-800',
        'offline': 'bg-gray-100 text-gray-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatTime = (time) => {
    if (!time) return ''
    return new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}

const startNavigation = (task) => {
    // Open navigation app with destination
    const destination = `${task.delivery_location.latitude},${task.delivery_location.longitude}`
    window.open(`https://maps.google.com/maps?daddr=${destination}`, '_blank')
}

const callLocation = (location) => {
    if (location.contact_phone) {
        window.location.href = `tel:${location.contact_phone}`
    }
}

const logout = () => {
    localStorage.removeItem('driver_token')
    router.visit('/driver/login')
}

const toggleMenu = () => {
    // Menu toggle functionality
}

const handleProofSubmitted = async (proofData) => {
    try {
        // Update task status to delivered
        await updateTaskStatus(selectedTask.value)
        
        // Close modals
        showProofOfDelivery.value = false
        selectedTask.value = null
        
        // Refresh tasks
        await loadTasks()
        
        alert('Proof of delivery submitted successfully!')
    } catch (error) {
        console.error('Error handling proof submission:', error)
        alert('Failed to submit proof of delivery. Please try again.')
    }
}

// Location tracking
let locationTrackingInterval = null

// Check location permission status
const checkLocationPermission = async () => {
    if (!navigator.geolocation) {
        locationPermissionStatus.value = 'not-supported'
        return
    }
    
    try {
        // Try to get current position with a short timeout
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: false,
                timeout: 1000,
                maximumAge: 60000
            })
        })
        locationPermissionStatus.value = 'granted'
    } catch (error) {
        if (error.code === 1) {
            locationPermissionStatus.value = 'denied'
        } else {
            locationPermissionStatus.value = 'unknown'
        }
    }
}

const startLocationTracking = () => {
    if (locationTrackingInterval) return // Already tracking
    
    console.log('Starting location tracking...')
    
    // Check if geolocation is available
    if (!navigator.geolocation) {
        alert('Geolocation is not supported by this browser. Location tracking disabled.')
        return
    }
    
    // Send location updates every 30 seconds
    locationTrackingInterval = setInterval(async () => {
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 30000 // Accept location up to 30 seconds old
                })
            })
            
            const locationData = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy,
                heading: position.coords.heading,
                speed: position.coords.speed
            }
            
            // Send location update
            await fetch('/api/v1/driver/location', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(locationData)
            })
            
        } catch (error) {
            console.warn('Location tracking error:', error)
            
            // If permission is denied, stop tracking and notify user
            if (error.code === 1) {
                stopLocationTracking()
                alert('Location permission denied. Location tracking stopped. Please enable location access to resume tracking.')
            }
        }
    }, 30000) // Update every 30 seconds
}

const stopLocationTracking = () => {
    if (locationTrackingInterval) {
        clearInterval(locationTrackingInterval)
        locationTrackingInterval = null
        console.log('Location tracking stopped')
    }
}

// Lifecycle
onMounted(() => {
    // Check if driver is authenticated
    const token = localStorage.getItem('driver_token')
    if (!token) {
        router.visit('/driver/login')
        return
    }
    
    // Check if driver needs approval
    const profile = localStorage.getItem('driver_profile')
    if (profile) {
        const parsed = JSON.parse(profile)
        if (parsed.status === 'pending_approval') {
            router.visit('/driver/pending-approval')
            return
        }
    }
    
    loadDriverProfile()
    loadTasks()
    checkLocationPermission() // Check location permission status
    
    // Set up periodic task refresh
    setInterval(loadTasks, 30000) // Refresh every 30 seconds
    
    // Monitor connection status
    const updateConnectionStatus = () => {
        connectionStatus.value = navigator.onLine ? 'online' : 'offline'
    }
    
    window.addEventListener('online', updateConnectionStatus)
    window.addEventListener('offline', updateConnectionStatus)
    
    onUnmounted(() => {
        window.removeEventListener('online', updateConnectionStatus)
        window.removeEventListener('offline', updateConnectionStatus)
        stopLocationTracking() // Stop location tracking when component unmounts
    })
})
</script>
