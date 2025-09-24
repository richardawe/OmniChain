<template>
    <div class="h-full w-full relative">
        <!-- Map Container -->
        <div id="driverMap" class="h-full w-full"></div>
        
        <!-- Map Controls -->
        <div class="absolute top-4 right-4 space-y-2">
            <!-- Center on Driver -->
            <button 
                @click="centerOnDriver"
                class="bg-white p-3 rounded-lg shadow-lg hover:bg-gray-50 transition-colors"
                title="Center on driver location"
            >
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </button>
            
            <!-- Toggle Traffic -->
            <button 
                @click="toggleTraffic"
                :class="showTraffic ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                class="p-3 rounded-lg shadow-lg hover:bg-gray-50 transition-colors"
                title="Toggle traffic layer"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </button>
        </div>
        
        <!-- Driver Status -->
        <div class="absolute top-4 left-4">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Driver Status</p>
                        <p class="text-xs text-gray-600">{{ driverStatus }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Task Info -->
        <div v-if="currentTask" class="absolute bottom-4 left-4 right-4">
            <div class="bg-white rounded-lg shadow-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ currentTask.order_number }}</h3>
                        <p class="text-sm text-gray-600">{{ currentTask.pickup_location.name }} → {{ currentTask.delivery_location.name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            @click="startNavigation"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            🧭 Navigate
                        </button>
                        <button 
                            @click="updateTaskStatus"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors"
                        >
                            {{ getNextStatusAction() }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loading Overlay -->
        <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-gray-600 mt-2">Loading map...</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'

// Props
const props = defineProps({
    driverLocation: {
        type: Object,
        default: null
    },
    tasks: {
        type: Array,
        default: () => []
    },
    currentTask: {
        type: Object,
        default: null
    }
})

// Emits
const emit = defineEmits(['taskSelected', 'statusUpdated', 'navigationStarted'])

// Reactive data
const map = ref(null)
const driverMarker = ref(null)
const taskMarkers = ref([])
const routeLines = ref([])
const loading = ref(true)
const showTraffic = ref(false)
const driverStatus = ref('Available')

// Methods
const initializeMap = () => {
    if (typeof L === 'undefined') {
        console.error('Leaflet not loaded')
        loading.value = false
        return
    }

    try {
        // Initialize map centered on driver location or default location
        const center = props.driverLocation 
            ? [props.driverLocation.latitude, props.driverLocation.longitude]
            : [39.8283, -98.5795] // Center of US

        map.value = L.map('driverMap').setView(center, 13)

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map.value)

        // Add driver marker
        if (props.driverLocation) {
            addDriverMarker(props.driverLocation)
        }

        // Add task markers
        props.tasks.forEach(task => {
            addTaskMarker(task)
        })

        loading.value = false
    } catch (error) {
        console.error('Error initializing map:', error)
        loading.value = false
    }
}

const addDriverMarker = (location) => {
    if (!map.value) return

    // Remove existing driver marker
    if (driverMarker.value) {
        map.value.removeLayer(driverMarker.value)
    }

    // Create driver marker with custom icon
    const driverIcon = L.divIcon({
        className: 'driver-marker',
        html: `
            <div class="driver-marker-container">
                <div class="driver-marker-icon">🚛</div>
                <div class="driver-marker-pulse"></div>
            </div>
        `,
        iconSize: [40, 40],
        iconAnchor: [20, 20]
    })

    driverMarker.value = L.marker([location.latitude, location.longitude], { icon: driverIcon })
        .addTo(map.value)
        .bindPopup(`
            <div class="text-center">
                <h3 class="font-semibold">Driver Location</h3>
                <p class="text-sm text-gray-600">Status: ${driverStatus.value}</p>
                <p class="text-xs text-gray-500">Updated: ${new Date().toLocaleTimeString()}</p>
            </div>
        `)
}

const addTaskMarker = (task) => {
    if (!map.value) return

    // Pickup marker
    const pickupIcon = L.divIcon({
        className: 'task-marker pickup',
        html: `
            <div class="task-marker-container pickup">
                <div class="task-marker-icon">📦</div>
                <div class="task-marker-label">P</div>
            </div>
        `,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    })

    const pickupMarker = L.marker([task.pickup_location.latitude, task.pickup_location.longitude], { icon: pickupIcon })
        .addTo(map.value)
        .bindPopup(`
            <div>
                <h3 class="font-semibold">Pickup: ${task.pickup_location.name}</h3>
                <p class="text-sm text-gray-600">${task.pickup_location.address}</p>
                <p class="text-xs text-blue-600">Order: ${task.order_number}</p>
            </div>
        `)

    // Delivery marker
    const deliveryIcon = L.divIcon({
        className: 'task-marker delivery',
        html: `
            <div class="task-marker-container delivery">
                <div class="task-marker-icon">🏠</div>
                <div class="task-marker-label">D</div>
            </div>
        `,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    })

    const deliveryMarker = L.marker([task.delivery_location.latitude, task.delivery_location.longitude], { icon: deliveryIcon })
        .addTo(map.value)
        .bindPopup(`
            <div>
                <h3 class="font-semibold">Delivery: ${task.delivery_location.name}</h3>
                <p class="text-sm text-gray-600">${task.delivery_location.address}</p>
                <p class="text-xs text-green-600">Order: ${task.order_number}</p>
            </div>
        `)

    // Add route line
    const routeLine = L.polyline([
        [task.pickup_location.latitude, task.pickup_location.longitude],
        [task.delivery_location.latitude, task.delivery_location.longitude]
    ], {
        color: task.status === 'in_transit' ? '#10B981' : '#3B82F6',
        weight: 3,
        opacity: 0.7,
        dashArray: '5, 5'
    }).addTo(map.value)

    taskMarkers.value.push({
        task,
        pickup: pickupMarker,
        delivery: deliveryMarker,
        route: routeLine
    })
}

const centerOnDriver = () => {
    if (map.value && props.driverLocation) {
        map.value.setView([props.driverLocation.latitude, props.driverLocation.longitude], 15)
    }
}

const toggleTraffic = () => {
    showTraffic.value = !showTraffic.value
    // In a real implementation, you would toggle traffic layer here
}

const startNavigation = () => {
    if (props.currentTask) {
        emit('navigationStarted', props.currentTask)
    }
}

const updateTaskStatus = () => {
    if (props.currentTask) {
        emit('statusUpdated', props.currentTask)
    }
}

const getNextStatusAction = () => {
    if (!props.currentTask) return 'Update Status'
    
    const actions = {
        'booked': 'Mark Picked Up',
        'picked_up': 'Start Delivery',
        'in_transit': 'Mark Delivered'
    }
    return actions[props.currentTask.status] || 'Update Status'
}

// Watch for changes
watch(() => props.driverLocation, (newLocation) => {
    if (newLocation && map.value) {
        addDriverMarker(newLocation)
    }
}, { deep: true })

watch(() => props.tasks, (newTasks) => {
    if (map.value) {
        // Clear existing task markers
        taskMarkers.value.forEach(markerGroup => {
            map.value.removeLayer(markerGroup.pickup)
            map.value.removeLayer(markerGroup.delivery)
            map.value.removeLayer(markerGroup.route)
        })
        taskMarkers.value = []

        // Add new task markers
        newTasks.forEach(task => {
            addTaskMarker(task)
        })
    }
}, { deep: true })

// Lifecycle
onMounted(() => {
    // Wait for Leaflet to load
    const checkLeaflet = () => {
        if (typeof L !== 'undefined') {
            initializeMap()
        } else {
            setTimeout(checkLeaflet, 100)
        }
    }
    checkLeaflet()
})

onUnmounted(() => {
    if (map.value) {
        map.value.remove()
    }
})
</script>

<style scoped>
.driver-marker-container {
    position: relative;
    width: 40px;
    height: 40px;
}

.driver-marker-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    z-index: 2;
}

.driver-marker-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: rgba(34, 197, 94, 0.3);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

.task-marker-container {
    position: relative;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.task-marker-container.pickup {
    background: rgba(59, 130, 246, 0.2);
    border: 2px solid #3B82F6;
}

.task-marker-container.delivery {
    background: rgba(16, 185, 129, 0.2);
    border: 2px solid #10B981;
}

.task-marker-icon {
    font-size: 16px;
    z-index: 2;
}

.task-marker-label {
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 4px;
    padding: 1px 4px;
    font-size: 10px;
    font-weight: bold;
    color: #374151;
}
</style>
