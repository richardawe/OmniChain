<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 text-center">
            <!-- Offline Icon -->
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">You're Offline</h1>
            
            <!-- Description -->
            <p class="text-gray-600 mb-6">
                No internet connection detected. Some features may be limited while offline.
            </p>

            <!-- Offline Features -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="font-semibold text-gray-900 mb-3">Available Offline:</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        View assigned tasks
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Access task details
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update task status
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit proof of delivery
                    </li>
                </ul>
            </div>

            <!-- Connection Status -->
            <div class="flex items-center justify-center space-x-2 mb-6">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">No internet connection</span>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <button
                    @click="goToDriverApp"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors"
                >
                    Continue Offline
                </button>
                
                <button
                    @click="checkConnection"
                    class="w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors"
                >
                    Check Connection
                </button>
            </div>

            <!-- Sync Status -->
            <div v-if="pendingSyncCount > 0" class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>{{ pendingSyncCount }}</strong> actions pending sync
                </p>
                <p class="text-xs text-yellow-600 mt-1">
                    Will sync automatically when connection is restored
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Reactive data
const pendingSyncCount = ref(0)
const connectionCheckInterval = ref(null)

// Methods
const goToDriverApp = () => {
    router.visit('/driver')
}

const checkConnection = () => {
    if (navigator.onLine) {
        router.visit('/driver')
    } else {
        // Show connection still offline
        console.log('Still offline')
    }
}

const updateSyncCount = () => {
    // Check IndexedDB for pending sync items
    if ('indexedDB' in window) {
        const request = indexedDB.open('OmniChainDriver', 1)
        
        request.onsuccess = () => {
            const db = request.result
            if (db.objectStoreNames.contains('offlineData')) {
                const transaction = db.transaction(['offlineData'], 'readonly')
                const store = transaction.objectStore('offlineData')
                const countRequest = store.count()
                
                countRequest.onsuccess = () => {
                    pendingSyncCount.value = countRequest.result
                }
            }
        }
    }
}

// Lifecycle
onMounted(() => {
    updateSyncCount()
    
    // Check connection status periodically
    connectionCheckInterval.value = setInterval(() => {
        if (navigator.onLine) {
            router.visit('/driver')
        } else {
            updateSyncCount()
        }
    }, 5000)

    // Listen for online event
    const handleOnline = () => {
        router.visit('/driver')
    }

    window.addEventListener('online', handleOnline)
    
    onUnmounted(() => {
        if (connectionCheckInterval.value) {
            clearInterval(connectionCheckInterval.value)
        }
        window.removeEventListener('online', handleOnline)
    })
})
</script>
