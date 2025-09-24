<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <button @click="goBack" class="p-2 rounded-lg hover:bg-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold">Location Tracking</h1>
                    <div class="flex items-center space-x-2">
                        <div :class="isTracking ? 'bg-green-400' : 'bg-red-400'" class="w-3 h-3 rounded-full animate-pulse"></div>
                        <span class="text-xs">{{ isTracking ? 'Tracking' : 'Stopped' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 space-y-6">
            <!-- Location Status -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">📍 Current Location</h2>
                    <button
                        @click="toggleTracking"
                        :class="isTracking ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                        class="px-4 py-2 rounded-lg text-white text-sm font-medium transition-colors"
                    >
                        {{ isTracking ? 'Stop Tracking' : 'Start Tracking' }}
                    </button>
                </div>

                <div v-if="currentLocation" class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Latitude</p>
                            <p class="font-medium">{{ currentLocation.latitude.toFixed(6) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Longitude</p>
                            <p class="font-medium">{{ currentLocation.longitude.toFixed(6) }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Accuracy</p>
                            <p class="font-medium">{{ currentLocation.accuracy }}m</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Speed</p>
                            <p class="font-medium">{{ currentLocation.speed || 0 }} km/h</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="font-medium">{{ formatTime(currentLocation.timestamp) }}</p>
                    </div>
                </div>

                <div v-else class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-4">📍</div>
                    <p class="text-gray-600">Location not available</p>
                    <p class="text-sm text-gray-500">Enable location tracking to see your current position</p>
                </div>
            </div>

            <!-- Location History -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">📍 Location History</h2>
                    <button
                        @click="clearHistory"
                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300 transition-colors"
                    >
                        Clear
                    </button>
                </div>

                <div v-if="locationHistory.length > 0" class="space-y-2">
                    <div
                        v-for="(location, index) in locationHistory.slice(0, 10)"
                        :key="index"
                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium">
                                    {{ location.latitude.toFixed(4) }}, {{ location.longitude.toFixed(4) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ formatTime(location.timestamp) }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ location.accuracy }}m
                        </div>
                    </div>
                    
                    <div v-if="locationHistory.length > 10" class="text-center pt-2">
                        <p class="text-xs text-gray-500">+{{ locationHistory.length - 10 }} more locations</p>
                    </div>
                </div>

                <div v-else class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-4">📊</div>
                    <p class="text-gray-600">No location history</p>
                    <p class="text-sm text-gray-500">Location updates will appear here</p>
                </div>
            </div>

            <!-- Tracking Settings -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold mb-4">⚙️ Tracking Settings</h2>
                
                <div class="space-y-4">
                    <!-- Update Interval -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Interval</label>
                        <select
                            v-model="trackingSettings.interval"
                            @change="updateTrackingSettings"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="10000">10 seconds</option>
                            <option value="30000">30 seconds</option>
                            <option value="60000">1 minute</option>
                            <option value="300000">5 minutes</option>
                        </select>
                    </div>

                    <!-- Accuracy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Required Accuracy</label>
                        <select
                            v-model="trackingSettings.accuracy"
                            @change="updateTrackingSettings"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="1">High (1m)</option>
                            <option value="10">Medium (10m)</option>
                            <option value="100">Low (100m)</option>
                        </select>
                    </div>

                    <!-- Background Tracking -->
                    <div class="flex items-center">
                        <input
                            v-model="trackingSettings.background"
                            @change="updateTrackingSettings"
                            type="checkbox"
                            id="background"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="background" class="ml-2 text-sm text-gray-700">
                            Continue tracking in background
                        </label>
                    </div>

                    <!-- Auto Submit -->
                    <div class="flex items-center">
                        <input
                            v-model="trackingSettings.autoSubmit"
                            @change="updateTrackingSettings"
                            type="checkbox"
                            id="autoSubmit"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="autoSubmit" class="ml-2 text-sm text-gray-700">
                            Automatically submit location updates
                        </label>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold mb-4">📊 Tracking Statistics</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ totalUpdates }}</div>
                        <div class="text-sm text-gray-600">Total Updates</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ formatDuration(sessionDuration) }}</div>
                        <div class="text-sm text-gray-600">Session Duration</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ averageAccuracy.toFixed(1) }}m</div>
                        <div class="text-sm text-gray-600">Avg Accuracy</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ maxSpeed.toFixed(1) }} km/h</div>
                        <div class="text-sm text-gray-600">Max Speed</div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Permission Request Modal -->
        <div v-if="showPermissionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-sm w-full p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Location Permission Required</h3>
                    <p class="text-gray-600 mb-4">
                        To track your location, please allow location access in your browser settings.
                    </p>
                    <div class="flex space-x-3">
                        <button
                            @click="requestLocationPermission"
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors"
                        >
                            Allow Location
                        </button>
                        <button
                            @click="showPermissionModal = false"
                            class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Reactive data
const currentLocation = ref(null)
const locationHistory = ref([])
const isTracking = ref(false)
const showPermissionModal = ref(false)
const trackingSettings = ref({
    interval: 30000, // 30 seconds
    accuracy: 10, // 10 meters
    background: true,
    autoSubmit: true
})

const sessionStartTime = ref(null)
const totalUpdates = ref(0)

// Computed properties
const sessionDuration = computed(() => {
    if (!sessionStartTime.value) return 0
    return Date.now() - sessionStartTime.value
})

const averageAccuracy = computed(() => {
    if (locationHistory.value.length === 0) return 0
    const total = locationHistory.value.reduce((sum, loc) => sum + loc.accuracy, 0)
    return total / locationHistory.value.length
})

const maxSpeed = computed(() => {
    if (locationHistory.value.length === 0) return 0
    return Math.max(...locationHistory.value.map(loc => loc.speed || 0))
})

// Methods
const goBack = () => {
    router.visit('/driver')
}

const requestLocationPermission = async () => {
    showPermissionModal.value = false
    
    if ('geolocation' in navigator) {
        try {
            const position = await getCurrentPosition()
            handleLocationUpdate(position)
            startTracking()
        } catch (error) {
            console.error('Location permission denied:', error)
            alert('Location permission is required for tracking. Please enable it in your browser settings.')
        }
    } else {
        alert('Geolocation is not supported by this browser.')
    }
}

const getCurrentPosition = () => {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        })
    })
}

const startTracking = () => {
    if (isTracking.value) return
    
    isTracking.value = true
    sessionStartTime.value = Date.now()
    
    // Start watching position
    const watchId = navigator.geolocation.watchPosition(
        handleLocationUpdate,
        handleLocationError,
        {
            enableHighAccuracy: trackingSettings.value.accuracy < 10,
            timeout: 10000,
            maximumAge: 5000
        }
    )
    
    // Store watch ID for cleanup
    window.locationWatchId = watchId
    
    // Set up periodic updates
    const intervalId = setInterval(async () => {
        if (isTracking.value) {
            try {
                const position = await getCurrentPosition()
                handleLocationUpdate(position)
            } catch (error) {
                console.error('Periodic location update failed:', error)
            }
        }
    }, trackingSettings.value.interval)
    
    window.locationIntervalId = intervalId
}

const stopTracking = () => {
    if (!isTracking.value) return
    
    isTracking.value = false
    
    // Clear geolocation watch
    if (window.locationWatchId) {
        navigator.geolocation.clearWatch(window.locationWatchId)
        window.locationWatchId = null
    }
    
    // Clear interval
    if (window.locationIntervalId) {
        clearInterval(window.locationIntervalId)
        window.locationIntervalId = null
    }
}

const toggleTracking = async () => {
    if (isTracking.value) {
        stopTracking()
    } else {
        // Check if we have permission
        try {
            const position = await getCurrentPosition()
            handleLocationUpdate(position)
            startTracking()
        } catch (error) {
            showPermissionModal.value = true
        }
    }
}

const handleLocationUpdate = async (position) => {
    const location = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
        accuracy: position.coords.accuracy,
        speed: position.coords.speed ? position.coords.speed * 3.6 : 0, // Convert m/s to km/h
        heading: position.coords.heading,
        timestamp: new Date().toISOString()
    }
    
    currentLocation.value = location
    locationHistory.value.unshift(location)
    
    // Keep only last 100 locations
    if (locationHistory.value.length > 100) {
        locationHistory.value = locationHistory.value.slice(0, 100)
    }
    
    totalUpdates.value++
    
    // Auto submit if enabled
    if (trackingSettings.value.autoSubmit) {
        await submitLocationUpdate(location)
    }
    
    // Save to localStorage
    saveLocationData()
}

const handleLocationError = (error) => {
    console.error('Location error:', error)
    
    switch (error.code) {
        case error.PERMISSION_DENIED:
            showPermissionModal.value = true
            break
        case error.POSITION_UNAVAILABLE:
            alert('Location information is unavailable.')
            break
        case error.TIMEOUT:
            alert('Location request timed out.')
            break
    }
}

const submitLocationUpdate = async (location) => {
    try {
        const response = await fetch('/api/v1/driver/location', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(location)
        })
        
        if (!response.ok) {
            console.error('Failed to submit location update')
        }
    } catch (error) {
        console.error('Error submitting location update:', error)
    }
}

const clearHistory = () => {
    locationHistory.value = []
    saveLocationData()
}

const updateTrackingSettings = () => {
    localStorage.setItem('trackingSettings', JSON.stringify(trackingSettings.value))
    
    // Restart tracking with new settings
    if (isTracking.value) {
        stopTracking()
        startTracking()
    }
}

const saveLocationData = () => {
    const data = {
        currentLocation: currentLocation.value,
        locationHistory: locationHistory.value.slice(0, 50), // Save only recent history
        totalUpdates: totalUpdates.value,
        sessionStartTime: sessionStartTime.value
    }
    localStorage.setItem('locationData', JSON.stringify(data))
}

const loadLocationData = () => {
    try {
        const data = localStorage.getItem('locationData')
        if (data) {
            const parsed = JSON.parse(data)
            currentLocation.value = parsed.currentLocation
            locationHistory.value = parsed.locationHistory || []
            totalUpdates.value = parsed.totalUpdates || 0
            sessionStartTime.value = parsed.sessionStartTime
        }
        
        const settings = localStorage.getItem('trackingSettings')
        if (settings) {
            trackingSettings.value = { ...trackingSettings.value, ...JSON.parse(settings) }
        }
    } catch (error) {
        console.error('Error loading location data:', error)
    }
}

const formatTime = (timestamp) => {
    return new Date(timestamp).toLocaleTimeString()
}

const formatDuration = (milliseconds) => {
    const seconds = Math.floor(milliseconds / 1000)
    const minutes = Math.floor(seconds / 60)
    const hours = Math.floor(minutes / 60)
    
    if (hours > 0) {
        return `${hours}h ${minutes % 60}m`
    } else if (minutes > 0) {
        return `${minutes}m ${seconds % 60}s`
    } else {
        return `${seconds}s`
    }
}

// Lifecycle
onMounted(() => {
    loadLocationData()
    
    // Check if geolocation is supported
    if (!('geolocation' in navigator)) {
        alert('Geolocation is not supported by this browser.')
        return
    }
})

onUnmounted(() => {
    // Clean up tracking when component is unmounted
    stopTracking()
})
</script>
