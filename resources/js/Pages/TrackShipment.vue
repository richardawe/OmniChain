<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <!-- Modern Navigation -->
        <nav class="bg-white/80 backdrop-blur-md shadow-lg border-b border-white/20 sticky top-0 z-50">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full animate-pulse"></div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                    Track Shipment
                                </h1>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                                    Real-time Shipment Tracking & Monitoring
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <a href="/" class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                                    </svg>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                            <button class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-blue-600 bg-blue-50 border-b-2 border-blue-600">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Track Shipment</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Shipment Selector -->
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Track Shipment:</label>
                            <div class="relative">
                                <select v-model="selectedShipmentId" @change="onShipmentChange" 
                                        class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm appearance-none pr-8">
                                    <option value="">All Shipments</option>
                                    <option v-for="shipment in allShipments" :key="shipment.id" :value="shipment.id">
                                        {{ shipment.order_number }} - {{ (shipment.status || 'unknown').toUpperCase() }}
                                    </option>
                                </select>
                                <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 px-4 py-2 bg-white/50 backdrop-blur-sm rounded-lg border border-white/20">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-lg"></div>
                            <span class="text-sm font-medium text-gray-700">Live</span>
                        </div>
                        <div class="text-sm text-gray-500 px-3 py-2 bg-white/30 backdrop-blur-sm rounded-lg border border-white/20">
                            {{ formatTime(new Date()) }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="px-6 py-6">
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map and Active Shipments -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Interactive Map -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        Live Shipment Tracking
                                    </h2>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ selectedShipment ? `Tracking: ${selectedShipment.order_number}` : 'Real-time movement visualization' }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            v-model="showDriverLocations" 
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        >
                                        <span class="ml-2 text-sm text-gray-600">Show Driver Locations</span>
                                    </label>
                                    <span class="text-sm text-gray-600">Zoom: {{ mapZoom.toFixed(1) }}</span>
                                    <button @click="resetMap"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Reset Map
                                    </button>
                                    <button @click="refreshMap"
                                            class="inline-flex items-center px-3 py-1.5 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Refresh Map
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="mapContainer" class="w-full" style="height: 500px;"></div>
                        <!-- Movement Timeline for Selected Shipment -->
                        <div v-if="selectedShipment && selectedShipment.shipment_events?.length > 0" class="mt-4 p-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Movement Timeline</h3>
                            <div class="space-y-2">
                                <div v-for="(event, index) in selectedShipment.shipment_events" :key="event.id" 
                                     class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ event.description }}</p>
                                        <p class="text-sm text-gray-500">{{ event.location_name }}, {{ event.city }}, {{ event.state }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">{{ formatTime(event.event_time) }}</p>
                                        <p class="text-xs text-gray-400">{{ formatDate(event.event_time) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Shipments Table -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-green-50/50 to-emerald-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                Active Shipments
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Overview of all in-progress freight orders</p>
                        </div>
                        <div class="p-8">
                            <!-- All Shipments List -->
                            <div class="space-y-4">
                                <div v-for="shipment in allShipments" :key="shipment.id" 
                                     class="p-6 hover:bg-gray-50 transition-colors cursor-pointer rounded-lg border"
                                     :class="{ 'bg-blue-50 border-l-4 border-blue-500 border-l-4': selectedShipmentId == shipment.id }"
                                     @click="selectShipment(shipment.id)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                                     :class="getShipmentIconClass(shipment.status)">
                                                    <svg class="w-6 h-6" :class="getShipmentIconColor(shipment.status)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="text-sm font-medium text-gray-900">{{ shipment.order_number }}</h3>
                                                    <span v-if="selectedShipmentId == shipment.id" class="text-blue-600 text-xs">● SELECTED</span>
                                                </div>
                                                <p class="text-sm text-gray-500">
                                                    {{ shipment.origin_location?.city }}, {{ shipment.origin_location?.state }} 
                                                    → {{ shipment.destination_location?.city }}, {{ shipment.destination_location?.state }}
                                                </p>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                          :class="getStatusClass(shipment.status)">
                                                        {{ formatStatus(shipment.status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ shipment.service_type?.toUpperCase() }}</span>
                                                    <span v-if="shipment.shipment_events?.length > 0" class="text-xs text-green-600">
                                                        {{ shipment.shipment_events.length }} events
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ formatWeight(shipment.total_weight) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ formatDate(shipment.requested_pickup_date) }}
                                            </div>
                                            <div v-if="shipment.carrier_company" class="text-xs text-gray-400 mt-1">
                                                {{ shipment.carrier_company.name }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mt-4">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Progress</span>
                                            <span>{{ getShipmentProgress(shipment) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-300"
                                                 :class="getProgressBarColor(shipment.status)"
                                                 :style="{ width: getShipmentProgress(shipment) + '%' }"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipment Details and Events -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Shipment Details Card -->
                    <div v-if="selectedShipment" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-purple-50/50 to-indigo-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Shipment Details
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Information for {{ selectedShipment.order_number }}</p>
                        </div>
                        <div class="p-8 space-y-4">
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Order Number</h3>
                                <p class="text-gray-900">{{ selectedShipment.order_number }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Status</h3>
                                <p :class="getStatusClass(selectedShipment.status)" class="font-semibold">{{ formatStatus(selectedShipment.status) }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Origin</h3>
                                <p class="text-gray-900">{{ selectedShipment.origin_location?.name }}, {{ selectedShipment.origin_location?.city }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Destination</h3>
                                <p class="text-gray-900">{{ selectedShipment.destination_location?.name }}, {{ selectedShipment.destination_location?.city }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Carrier</h3>
                                <p class="text-gray-900">{{ selectedShipment.carrier_company?.name || 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Driver</h3>
                                <p class="text-gray-900">{{ selectedShipment.assigned_driver ? selectedShipment.assigned_driver.name : 'Unassigned' }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Service Type</h3>
                                <p class="text-gray-900">{{ selectedShipment.service_type }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Planned Pickup</h3>
                                <p class="text-gray-900">{{ formatDateTime(selectedShipment.planned_pickup_at) }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Estimated Delivery</h3>
                                <p class="text-gray-900">{{ formatDateTime(selectedShipment.estimated_delivery_at) }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Actual Delivery</h3>
                                <p class="text-gray-900">{{ selectedShipment.actual_delivery_at ? formatDateTime(selectedShipment.actual_delivery_at) : 'N/A' }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Total Weight</h3>
                                <p class="text-gray-900">{{ selectedShipment.total_weight }} {{ selectedShipment.weight_unit }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Total Volume</h3>
                                <p class="text-gray-900">{{ selectedShipment.total_volume }} {{ selectedShipment.volume_unit }}</p>
                            </div>
                            <div>
                                <h3 class="text-md font-medium text-gray-700">Value</h3>
                                <p class="text-gray-900">{{ selectedShipment.currency }} {{ selectedShipment.value }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No Shipment Selected -->
                    <div v-else class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-gray-50/50 to-gray-100/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Shipment Details
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Select a shipment to view details</p>
                        </div>
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Shipment Selected</h3>
                            <p class="text-gray-600 mb-4">Choose a shipment from the dropdown above to view detailed information and tracking data.</p>
                            <div class="text-sm text-gray-500">
                                <p>Available shipments: {{ allShipments.length }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipment Events Card -->
                    <div v-if="selectedShipment" class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-orange-50/50 to-red-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Shipment Events
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Timeline of key events</p>
                        </div>
                        <div class="p-8">
                            <ul class="space-y-4">
                                <li v-for="event in selectedShipment.shipment_events" :key="event.id" class="relative pl-8">
                                    <div class="absolute left-0 top-0 flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ (event.event_type || 'UNKNOWN').replace(/_/g, ' ').toUpperCase() }}</p>
                                        <p class="text-xs text-gray-500">{{ formatDateTime(event.event_timestamp) }}</p>
                                        <p v-if="event.location" class="text-xs text-gray-500">{{ event.location.name }}, {{ event.location.city }}</p>
                                        <p v-if="event.description" class="text-xs text-gray-600 mt-1">{{ event.description }}</p>
                                    </div>
                                </li>
                            </ul>
                            <p v-if="!selectedShipment.shipment_events || selectedShipment.shipment_events.length === 0" class="text-sm text-gray-500">No events recorded for this shipment.</p>
                        </div>
                    </div>

                    <!-- Performance Metrics -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                Performance Metrics
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Key performance indicators</p>
                        </div>
                        <div class="p-8">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">On-Time Delivery</span>
                                        <span class="font-medium">{{ performanceMetrics.on_time_delivery || 95 }}%</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" 
                                             :style="{ width: (performanceMetrics.on_time_delivery || 95) + '%' }"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Avg Transit Time</span>
                                        <span class="font-medium">{{ performanceMetrics.average_transit_time || 3.2 }} days</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Carriers</span>
                                        <span class="font-medium">{{ stats.total_carriers || 12 }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Total Shippers</span>
                                        <span class="font-medium">{{ stats.total_shippers || 8 }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Active Shipments</span>
                                        <span class="font-medium">{{ allShipments.length }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Completed Today</span>
                                        <span class="font-medium">{{ getCompletedToday() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom styles for driver markers and animations */
.driver-marker {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Custom div icon styles */
.custom-div-icon {
    background: transparent !important;
    border: none !important;
}

/* Route line animation */
.route-line {
    stroke-dasharray: 10, 10;
    animation: dash 1s linear infinite;
}

@keyframes dash {
    to {
        stroke-dashoffset: -20;
    }
}

/* Driver location pulsing circle */
.driver-circle {
    animation: ripple 2s infinite;
}

@keyframes ripple {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

export default {
    name: 'TrackShipment',
    setup() {
        // Reactive data
        const selectedShipmentId = ref('');
        const selectedShipment = ref(null);
        const allShipments = ref([]);
        const showDriverLocations = ref(true);
        const mapZoom = ref(6);
        const map = ref(null);
        const performanceMetrics = ref({
            on_time_delivery: 95,
            average_transit_time: 3.2
        });
        const stats = ref({
            total_carriers: 12,
            total_shippers: 8
        });

        // Computed properties
        const filteredShipments = computed(() => {
            if (!selectedShipmentId.value) {
                return allShipments.value.filter(shipment => 
                    ['pending', 'in_transit', 'out_for_delivery', 'delivered'].includes(shipment.status)
                );
            }
            return allShipments.value.filter(shipment => shipment.id === selectedShipmentId.value);
        });

        // Methods
        const loadShipments = async () => {
            try {
                console.log('Loading shipments...');
                const response = await axios.get('/api/v1/freight-orders');
                if (response.data.success) {
                    allShipments.value = response.data.data.data || [];
                    console.log('Shipments loaded:', allShipments.value.length);
                    
                    // Set default selected shipment if none is selected
                    if (allShipments.value.length > 0 && !selectedShipmentId.value) {
                        selectedShipmentId.value = allShipments.value[0].id;
                        selectedShipment.value = allShipments.value[0];
                    }
                    
                    initializeMap();
                    loadDriverLocations();
                }
            } catch (error) {
                console.error('Error loading shipments:', error);
            }
        };

        // Load driver locations for real-time tracking
        const loadDriverLocations = async () => {
            try {
                console.log('Loading driver locations...');
                const response = await axios.get('/api/v1/driver-locations');
                if (response.data.success) {
                    console.log('Driver locations loaded:', response.data.data.length);
                    addDriverMarkersToMap(response.data.data);
                }
            } catch (error) {
                console.error('Error loading driver locations:', error);
            }
        };

        // Add driver markers to map
        const addDriverMarkersToMap = (drivers) => {
            if (!map.value) return;

            drivers.forEach(driver => {
                if (driver.location) {
                    const location = driver.location;
                    const driverIcon = L.divIcon({
                        html: `
                            <div class="relative">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold shadow-lg animate-pulse">
                                    🚛
                                </div>
                                <div class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                            </div>
                        `,
                        className: 'custom-div-icon driver-marker',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    L.marker([location.latitude, location.longitude], { 
                        icon: driverIcon,
                        zIndexOffset: 1000
                    }).addTo(map.value)
                    .bindPopup(`
                        <div class="p-2">
                            <h3 class="font-bold text-blue-600">🚛 ${driver.driver_name || 'Driver'}</h3>
                            <p class="text-sm"><strong>Status:</strong> ${driver.status || 'Active'}</p>
                            <p class="text-sm"><strong>Vehicle:</strong> ${driver.vehicle_type || 'Truck'}</p>
                            <p class="text-sm"><strong>Phone:</strong> ${driver.driver_phone || 'N/A'}</p>
                            <p class="text-xs text-gray-500">Last updated: ${new Date(location.timestamp).toLocaleTimeString()}</p>
                            <div class="mt-2 text-xs text-green-600">
                                <div class="w-2 h-2 bg-green-500 rounded-full inline-block mr-1 animate-pulse"></div>
                                Live tracking active
                            </div>
                        </div>
                    `);
                }
            });
        };

        const initializeMap = () => {
            // Wait for Leaflet to be available and DOM to be ready
            const initMap = () => {
                const mapContainer = document.getElementById('mapContainer');
                if (typeof L !== 'undefined' && mapContainer && mapContainer.offsetHeight > 0) {
                    try {
                        console.log('Initializing map...');
                        // Initialize map
                        map.value = L.map('mapContainer').setView([39.8283, -98.5795], 6);
                    
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map.value);

                        console.log('Map initialized, adding markers...');
                        // Add shipment markers
                        addShipmentMarkers();
                    } catch (error) {
                        console.error('Error initializing map:', error);
                        // Retry after a delay
                        setTimeout(initMap, 500);
                    }
                } else {
                    if (typeof L === 'undefined') {
                        console.log('Leaflet not available, retrying...');
                    } else {
                        console.log('Map container not found, retrying...');
                    }
                    // Retry after a short delay
                    setTimeout(initMap, 100);
                }
            };
            
            initMap();
        };

        const addShipmentMarkers = () => {
            if (!map.value) return;

            console.log('Adding shipment markers for', filteredShipments.value.length, 'shipments');

            // Clear existing markers and routes
            map.value.eachLayer(layer => {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    map.value.removeLayer(layer);
                }
            });

            filteredShipments.value.forEach(shipment => {
                console.log('Processing shipment:', shipment.order_number, 'Status:', shipment.status);
                if (shipment.origin_location && shipment.origin_location.latitude && shipment.origin_location.longitude) {
                    const originIcon = L.divIcon({
                        html: `<div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold shadow-lg">O</div>`,
                        className: 'custom-div-icon',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    L.marker([shipment.origin_location.latitude, shipment.origin_location.longitude], { icon: originIcon })
                        .addTo(map.value)
                        .bindPopup(`<strong>Origin:</strong> ${shipment.origin_location.name}<br><strong>Order:</strong> ${shipment.order_number}`);
                }

                if (shipment.destination_location && shipment.destination_location.latitude && shipment.destination_location.longitude) {
                    const destinationIcon = L.divIcon({
                        html: `<div class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-xs font-bold shadow-lg">D</div>`,
                        className: 'custom-div-icon',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    });

                    L.marker([shipment.destination_location.latitude, shipment.destination_location.longitude], { icon: destinationIcon })
                        .addTo(map.value)
                        .bindPopup(`<strong>Destination:</strong> ${shipment.destination_location.name}<br><strong>Order:</strong> ${shipment.order_number}`);
                }

                // Add route line between origin and destination
                if (shipment.origin_location && shipment.destination_location && 
                    shipment.origin_location.latitude && shipment.origin_location.longitude &&
                    shipment.destination_location.latitude && shipment.destination_location.longitude) {
                    
                    const routeLine = L.polyline([
                        [shipment.origin_location.latitude, shipment.origin_location.longitude],
                        [shipment.destination_location.latitude, shipment.destination_location.longitude]
                    ], {
                        color: '#3B82F6',
                        weight: 3,
                        opacity: 0.7,
                        dashArray: '10, 10'
                    }).addTo(map.value);

                    // Add driver location if shipment is in transit
                    if (shipment.status === 'in_transit' || shipment.status === 'picked_up') {
                        addDriverLocation(shipment);
                    }
                }
            });
        };

        // Add driver location with animated movement
        const addDriverLocation = (shipment) => {
            if (!map.value || !shipment.origin_location || !shipment.destination_location) return;

            // Calculate intermediate position based on status
            let driverLat, driverLng;
            const originLat = parseFloat(shipment.origin_location.latitude);
            const originLng = parseFloat(shipment.origin_location.longitude);
            const destLat = parseFloat(shipment.destination_location.latitude);
            const destLng = parseFloat(shipment.destination_location.longitude);

            // Simulate driver position based on status
            if (shipment.status === 'picked_up') {
                // Driver is 20% along the route
                driverLat = originLat + (destLat - originLat) * 0.2;
                driverLng = originLng + (destLng - originLng) * 0.2;
            } else if (shipment.status === 'in_transit') {
                // Driver is 60% along the route
                driverLat = originLat + (destLat - originLat) * 0.6;
                driverLng = originLng + (destLng - originLng) * 0.6;
            }

            // Create animated driver marker
            const driverIcon = L.divIcon({
                html: `
                    <div class="relative">
                        <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold shadow-lg animate-pulse">
                            🚛
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                    </div>
                `,
                className: 'custom-div-icon driver-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            const driverMarker = L.marker([driverLat, driverLng], { 
                icon: driverIcon,
                zIndexOffset: 1000
            }).addTo(map.value);

            // Add driver info popup
            driverMarker.bindPopup(`
                <div class="p-2">
                    <h3 class="font-bold text-blue-600">🚛 Driver Location</h3>
                    <p class="text-sm"><strong>Order:</strong> ${shipment.order_number}</p>
                    <p class="text-sm"><strong>Status:</strong> ${shipment.status.toUpperCase()}</p>
                    <p class="text-sm"><strong>ETA:</strong> ${getETA(shipment)}</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <div class="w-2 h-2 bg-green-500 rounded-full inline-block mr-1 animate-pulse"></div>
                        Live tracking active
                    </div>
                </div>
            `);

            // Add pulsing circle around driver
            const driverCircle = L.circle([driverLat, driverLng], {
                color: '#3B82F6',
                fillColor: '#3B82F6',
                fillOpacity: 0.1,
                radius: 2000
            }).addTo(map.value);

            // Simulate movement animation
            if (shipment.status === 'in_transit') {
                animateDriverMovement(driverMarker, originLat, originLng, destLat, destLng);
            }
        };

        // Animate driver movement along route
        const animateDriverMovement = (marker, startLat, startLng, endLat, endLng) => {
            let progress = 0.6; // Start at 60% for in_transit
            const duration = 30000; // 30 seconds animation
            const steps = 100;
            const stepDuration = duration / steps;
            const latStep = (endLat - startLat) / steps;
            const lngStep = (endLng - startLng) / steps;

            let currentStep = 0;
            const animate = () => {
                if (currentStep < steps && progress < 0.95) {
                    const newLat = startLat + (latStep * currentStep);
                    const newLng = startLng + (lngStep * currentStep);
                    
                    marker.setLatLng([newLat, newLng]);
                    progress = currentStep / steps;
                    currentStep++;
                    
                    setTimeout(animate, stepDuration);
                }
            };
            
            animate();
        };

        // Get estimated time of arrival
        const getETA = (shipment) => {
            const now = new Date();
            const eta = new Date(now.getTime() + (2 * 60 * 60 * 1000)); // 2 hours from now
            return eta.toLocaleTimeString();
        };

        const selectShipment = (shipment) => {
            selectedShipment.value = shipment;
            selectedShipmentId.value = shipment.id;
        };

        const onShipmentChange = () => {
            if (selectedShipmentId.value) {
                const shipment = allShipments.value.find(s => s.id === selectedShipmentId.value);
                if (shipment) {
                    selectedShipment.value = shipment;
                    // Center map on selected shipment
                    if (shipment.origin_location && shipment.origin_location.latitude && shipment.origin_location.longitude) {
                        map.value.setView([shipment.origin_location.latitude, shipment.origin_location.longitude], 10);
                    }
                }
            } else {
                selectedShipment.value = null;
                resetMap();
            }
        };

        const resetMap = () => {
            if (map.value) {
                map.value.setView([39.8283, -98.5795], 6);
                addShipmentMarkers();
            }
        };

        const refreshMap = () => {
            console.log('Refreshing map...');
            loadShipments();
            loadDriverLocations();
        };

        const getStatusClass = (status) => {
            const statusClasses = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-blue-100 text-blue-800',
                'in_transit': 'bg-purple-100 text-purple-800',
                'out_for_delivery': 'bg-orange-100 text-orange-800',
                'delivered': 'bg-green-100 text-green-800',
                'cancelled': 'bg-red-100 text-red-800',
                'exception': 'bg-red-100 text-red-800'
            };
            return statusClasses[status] || 'bg-gray-100 text-gray-800';
        };

        const formatDateTime = (dateString) => {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleString();
        };

        const formatTime = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleTimeString();
        };

        const formatDate = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString();
        };

        const formatWeight = (weight) => {
            if (!weight) return 'N/A';
            return `${weight} lbs`;
        };

        const formatStatus = (status) => {
            if (!status || typeof status !== 'string') return 'UNKNOWN';
            return status.replace(/_/g, ' ').toUpperCase();
        };

        const getShipmentIconClass = (status) => {
            if (!status || typeof status !== 'string') return 'bg-gray-100';
            const iconClasses = {
                'pending': 'bg-yellow-100',
                'confirmed': 'bg-blue-100',
                'in_transit': 'bg-purple-100',
                'out_for_delivery': 'bg-orange-100',
                'delivered': 'bg-green-100',
                'cancelled': 'bg-red-100',
                'exception': 'bg-red-100'
            };
            return iconClasses[status] || 'bg-gray-100';
        };

        const getShipmentIconColor = (status) => {
            if (!status || typeof status !== 'string') return 'text-gray-600';
            const iconColors = {
                'pending': 'text-yellow-600',
                'confirmed': 'text-blue-600',
                'in_transit': 'text-purple-600',
                'out_for_delivery': 'text-orange-600',
                'delivered': 'text-green-600',
                'cancelled': 'text-red-600',
                'exception': 'text-red-600'
            };
            return iconColors[status] || 'text-gray-600';
        };

        const getProgressBarColor = (status) => {
            if (!status || typeof status !== 'string') return 'bg-gray-500';
            const progressColors = {
                'pending': 'bg-yellow-500',
                'confirmed': 'bg-blue-500',
                'in_transit': 'bg-purple-500',
                'out_for_delivery': 'bg-orange-500',
                'delivered': 'bg-green-500',
                'cancelled': 'bg-red-500',
                'exception': 'bg-red-500'
            };
            return progressColors[status] || 'bg-gray-500';
        };

        const getShipmentProgress = (shipment) => {
            if (!shipment || !shipment.status || typeof shipment.status !== 'string') return 0;
            const statusProgress = {
                'pending': 10,
                'confirmed': 25,
                'in_transit': 60,
                'out_for_delivery': 85,
                'delivered': 100,
                'cancelled': 0,
                'exception': 0
            };
            return statusProgress[shipment.status] || 0;
        };

        const getCompletedToday = () => {
            const today = new Date();
            const todayStart = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            return allShipments.value.filter(shipment => {
                if (shipment.actual_delivery_at) {
                    const deliveryDate = new Date(shipment.actual_delivery_at);
                    return deliveryDate >= todayStart;
                }
                return false;
            }).length;
        };

        const updateDriverMarkersOnMap = () => {
            // Implementation for driver markers
            console.log('Update driver markers:', showDriverLocations.value);
        };

        const viewShipmentDetails = (shipment) => {
            selectShipment(shipment);
        };

        const editShipment = (shipment) => {
            // Navigate to edit page or open modal
            console.log('Edit shipment:', shipment);
        };

        // Watch for changes in filtered shipments to update map
        watch(filteredShipments, () => {
            addShipmentMarkers();
        });

        // Watch for zoom changes
        watch(() => map.value, (newMap) => {
            if (newMap) {
                newMap.on('zoomend', () => {
                    mapZoom.value = newMap.getZoom();
                });
            }
        });

        // Set up real-time updates
        const setupRealTimeUpdates = () => {
            // Update driver locations every 30 seconds
            setInterval(() => {
                if (showDriverLocations.value) {
                    loadDriverLocations();
                }
            }, 30000);

            // Update shipments every 2 minutes
            setInterval(() => {
                loadShipments();
            }, 120000);
        };

        // Lifecycle
        onMounted(() => {
            loadShipments();
            setupRealTimeUpdates();
        });

        return {
            selectedShipmentId,
            selectedShipment,
            allShipments,
            filteredShipments,
            showDriverLocations,
            mapZoom,
            performanceMetrics,
            stats,
            selectShipment,
            onShipmentChange,
            resetMap,
            refreshMap,
            getStatusClass,
            formatDateTime,
            formatTime,
            formatDate,
            formatWeight,
            formatStatus,
            getShipmentIconClass,
            getShipmentIconColor,
            getProgressBarColor,
            getShipmentProgress,
            getCompletedToday,
            updateDriverMarkersOnMap,
            viewShipmentDetails,
            editShipment
        };
    }
};
</script>

<style scoped>
.custom-div-icon {
    background: transparent;
    border: none;
}
</style>
