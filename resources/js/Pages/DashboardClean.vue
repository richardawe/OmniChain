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
                                    OmniChain Control Tower
                                </h1>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                                    Real-time Supply Chain Visibility
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-6">
                            <button @click="currentView = 'dashboard'" 
                                    :class="currentView === 'dashboard' ? 'text-blue-600 bg-blue-50 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                    class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                                    </svg>
                                    <span>Dashboard</span>
                                </div>
                            </button>
                            <a href="/track-shipment" 
                               class="px-4 py-2 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>Track Shipment</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
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
            <!-- Dashboard View -->
            <div v-if="currentView === 'dashboard'">
                <!-- Module Tiles -->
                <div class="mb-12">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-indigo-800 bg-clip-text text-transparent mb-2">
                            Supply Chain Modules
                        </h2>
                        <p class="text-gray-600 text-lg">Comprehensive tools for end-to-end supply chain management</p>
                        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mx-auto mt-4"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Master Data Tile -->
                        <a href="/master-data" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">Master Data</h3>
                                            <p class="text-sm text-gray-500 font-medium">Companies, Locations, Products</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage core reference data including companies, locations, products, and carriers across your supply chain.</p>
                                    <div class="mt-4 flex items-center text-blue-600 text-sm font-medium group-hover:text-blue-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Other tiles would go here - keeping it simple for now -->
                    </div>
                </div>

                <!-- Modern Stats Section -->
                <div class="mb-12">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-indigo-800 bg-clip-text text-transparent mb-2">
                            Real-time Performance Metrics
                        </h2>
                        <p class="text-gray-600">Live supply chain insights and key performance indicators</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Active Shipments -->
                        <div class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Active Shipments</p>
                                    <p class="text-3xl font-bold text-blue-600">23</p>
                                    <p class="text-xs text-green-600 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        +12% this week
                                    </p>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Overview Content -->
                <div class="text-center py-16">
                    <div class="max-w-2xl mx-auto">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome to OmniChain Control Tower</h2>
                        <p class="text-lg text-gray-600 mb-8">Your comprehensive supply chain management platform. Access all modules from the tiles above or use the Track Shipment page for detailed shipment monitoring.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="/track-shipment" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Track Shipments
                            </a>
                            <button @click="currentView = 'weather-logistics'" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                </svg>
                                Weather & Logistics
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather & Logistics View -->
            <div v-if="currentView === 'weather-logistics'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Weather & Logistics Intelligence</h2>
                    <div class="flex space-x-2">
                        <button @click="loadWeatherData" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            🌤️ Refresh Weather
                        </button>
                        <button @click="loadLogisticsData" 
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            🚛 Refresh Logistics
                        </button>
                    </div>
                </div>
                <div class="text-center py-16">
                    <p class="text-lg text-gray-600">Weather & Logistics functionality will be implemented here.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'Dashboard',
    setup() {
        const currentView = ref('dashboard');

        const formatTime = (date) => {
            return date.toLocaleTimeString();
        };

        const loadWeatherData = () => {
            console.log('Loading weather data...');
        };

        const loadLogisticsData = () => {
            console.log('Loading logistics data...');
        };

        return {
            currentView,
            formatTime,
            loadWeatherData,
            loadLogisticsData
        };
    }
};
</script>
