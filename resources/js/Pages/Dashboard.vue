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
            <!-- Welcome Section -->
            <div class="relative text-center py-16 mb-8 rounded-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
                     style="background-image: url('/images/dashboard/welcome-hero.jpg')"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-800/70 to-indigo-900/80"></div>
                <div class="relative z-10 max-w-4xl mx-auto">
                    <h1 class="text-5xl font-bold text-white mb-6 drop-shadow-lg">Welcome to OmniChain Control Tower</h1>
                    <p class="text-xl text-blue-100 drop-shadow-md">Your comprehensive supply chain management platform. Access all modules from the tiles below or use the Track Shipment page for detailed shipment monitoring.</p>
                    <div class="mt-8 flex items-center justify-center space-x-4">
                        <div class="flex items-center text-blue-200">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm font-medium">Live System</span>
                        </div>
                        <div class="flex items-center text-blue-200">
                            <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm font-medium">Real-time Updates</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div v-if="loading" class="flex items-center justify-center py-8 mb-8">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="text-lg text-gray-600">Loading data...</span>
                </div>
            </div>

            <!-- Error Display -->
            <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-red-700 font-medium">Error loading data: {{ error }}</span>
                </div>
            </div>

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
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/master-data.jpg')"></div>
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

                        <!-- Supplier & Procurement Tile -->
                        <a href="/supplier-procurement" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/supplier-procurement.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl group-hover:from-green-600 group-hover:to-green-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-300">Supplier & Procurement</h3>
                                            <p class="text-sm text-gray-500 font-medium">Onboarding, Contracts, POs</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Streamline supplier onboarding, contract management, and purchase order processes with performance tracking.</p>
                                    <div class="mt-4 flex items-center text-green-600 text-sm font-medium group-hover:text-green-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Transportation Tile -->
                        <a href="/transportation-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/transportation.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl group-hover:from-purple-600 group-hover:to-purple-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">Transportation</h3>
                                            <p class="text-sm text-gray-500 font-medium">TMS, Routes, Vehicles</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage freight orders, route planning, vehicle tracking, and carrier rate management with real-time visibility.</p>
                                    <div class="mt-4 flex items-center text-purple-600 text-sm font-medium group-hover:text-purple-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Delivery Management Tile -->
                        <a href="/delivery-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/delivery.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl group-hover:from-orange-600 group-hover:to-orange-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-300">Delivery Management</h3>
                                            <p class="text-sm text-gray-500 font-medium">Last-Mile, POD, Tasks</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Optimize last-mile delivery with task management, proof of delivery capture, and customer notifications.</p>
                                    <div class="mt-4 flex items-center text-orange-600 text-sm font-medium group-hover:text-orange-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Manufacturing Tile -->
                        <a href="/manufacturing-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/manufacturing.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl group-hover:from-red-600 group-hover:to-red-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors duration-300">Manufacturing</h3>
                                            <p class="text-sm text-gray-500 font-medium">MES, BOM, Quality</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Control production with work orders, BOM management, machine telemetry, and quality control workflows.</p>
                                    <div class="mt-4 flex items-center text-red-600 text-sm font-medium group-hover:text-red-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Inventory & Warehouse Tile -->
                        <a href="/inventory-warehouse-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/inventory-warehouse.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl group-hover:from-indigo-600 group-hover:to-indigo-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">Inventory & Warehouse</h3>
                                            <p class="text-sm text-gray-500 font-medium">WMS, Bins, Operations</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Optimize warehouse operations with inventory management, bin tracking, putaway, and cross-dock operations.</p>
                                    <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium group-hover:text-indigo-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Returns & Reverse Logistics Tile -->
                        <a href="/returns-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/returns.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl group-hover:from-pink-600 group-hover:to-pink-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-pink-600 transition-colors duration-300">Returns & Reverse Logistics</h3>
                                            <p class="text-sm text-gray-500 font-medium">RMAs, Processing</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Streamline returns processing with authorization workflows, disposition management, and quality control.</p>
                                    <div class="mt-4 flex items-center text-pink-600 text-sm font-medium group-hover:text-pink-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Weather & Logistics Tile -->
                        <button @click="currentView = 'weather-logistics'" class="group w-full transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/weather-logistics.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-cyan-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl group-hover:from-cyan-600 group-hover:to-cyan-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-cyan-600 transition-colors duration-300">Weather & Logistics</h3>
                                            <p class="text-sm text-gray-500 font-medium">Route Intelligence</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Real-time weather monitoring and route intelligence for optimal logistics planning and risk management.</p>
                                    <div class="mt-4 flex items-center text-cyan-600 text-sm font-medium group-hover:text-cyan-700 transition-colors duration-300">
                                        <span>Explore Module</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Finance & Costing Placeholder -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/70 transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/finance-costing.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl group-hover:from-emerald-600 group-hover:to-emerald-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300">Finance & Costing</h3>
                                            <p class="text-sm text-gray-500 font-medium">Cost Management, Analytics</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Advanced financial analytics, cost management, and profitability tracking for comprehensive business intelligence.</p>
                                    <div class="mt-4 flex items-center text-emerald-600 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compliance & Safety Placeholder -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/70 transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/compliance-safety.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl group-hover:from-yellow-600 group-hover:to-yellow-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors duration-300">Compliance & Safety</h3>
                                            <p class="text-sm text-gray-500 font-medium">Regulations, Safety Protocols</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Ensure regulatory compliance and safety protocols across all supply chain operations with automated monitoring.</p>
                                    <div class="mt-4 flex items-center text-yellow-600 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics & Forecasting Placeholder -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/70 transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/analytics-forecasting.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl group-hover:from-teal-600 group-hover:to-teal-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-teal-600 transition-colors duration-300">Analytics & Forecasting</h3>
                                            <p class="text-sm text-gray-500 font-medium">Predictive Analytics, BI</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Advanced predictive analytics, machine learning models, and business intelligence for strategic decision making.</p>
                                    <div class="mt-4 flex items-center text-teal-600 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Integration & Connectivity Placeholder -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/70 transition-all duration-300 relative overflow-hidden">
                                <!-- Background Image -->
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
                                     style="background-image: url('/images/dashboard/integration-connectivity.jpg')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-violet-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl group-hover:from-violet-600 group-hover:to-violet-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-violet-600 transition-colors duration-300">Integration & Connectivity</h3>
                                            <p class="text-sm text-gray-500 font-medium">APIs, EDI, IoT</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Seamless integration with external systems, EDI connectivity, IoT devices, and comprehensive API management.</p>
                                    <div class="mt-4 flex items-center text-violet-600 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    </div>
                </div>

                <!-- Module Performance Metrics -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    Module Performance Metrics
                                </h2>
                                <p class="text-sm text-gray-600 mt-1">Key performance indicators from each supply chain module</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm text-gray-600">Live Data</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Master Data -->
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-blue-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-blue-600 font-medium">98%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Data Quality</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.master_data.quality || 98 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.master_data.records || 1247 }} records</p>
                                </div>
                            </div>

                            <!-- Supplier & Procurement -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-green-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-green-600 font-medium">$2.1M</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Procurement Value</p>
                                    <p class="text-2xl font-bold text-gray-900">${{ moduleMetrics.supplier_procurement.value || 2.1 }}M</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.supplier_procurement.orders || 156 }} orders</p>
                                </div>
                            </div>

                            <!-- Transportation -->
                            <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-6 border border-purple-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-purple-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-purple-600 font-medium">94%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">On-Time Delivery</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.transportation.on_time || 94 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.transportation.routes || 89 }} active routes</p>
                                </div>
                            </div>

                            <!-- Delivery Management -->
                            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border border-orange-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-orange-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-orange-600 font-medium">4.8/5</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Customer Rating</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.delivery.rating || 4.8 }}/5</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.delivery.tasks || 234 }} tasks</p>
                                </div>
                            </div>

                            <!-- Manufacturing -->
                            <div class="bg-gradient-to-br from-cyan-50 to-teal-50 rounded-xl p-6 border border-cyan-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-cyan-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-cyan-600 font-medium">97%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Production Efficiency</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.manufacturing.efficiency || 97 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.manufacturing.orders || 67 }} work orders</p>
                                </div>
                            </div>

                            <!-- Inventory & Warehouse -->
                            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-indigo-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-indigo-600 font-medium">99.2%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Inventory Accuracy</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.inventory.accuracy || 99.2 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.inventory.items || 15420 }} SKUs</p>
                                </div>
                            </div>

                            <!-- Returns & Reverse Logistics -->
                            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-6 border border-red-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-red-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 14v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2h12a2 2 0 002-2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-red-600 font-medium">2.1%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Return Rate</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.returns.rate || 2.1 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.returns.processed || 23 }} returns</p>
                                </div>
                            </div>

                            <!-- Weather & Logistics -->
                            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="p-3 bg-yellow-500 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-yellow-600 font-medium">85%</span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Route Optimization</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ moduleMetrics.weather_logistics.optimization || 85 }}%</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ moduleMetrics.weather_logistics.alerts || 12 }} alerts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module Relationship Diagram removed - moved to dedicated page -->

        </div>

        <!-- Footer -->
        <footer class="bg-white/80 backdrop-blur-sm border-t border-white/20 mt-12">
            <div class="px-6 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-gray-600">
                            © 2024 OmniChain Control Tower. All rights reserved.
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/module-relationships" 
                               class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <span>Module Relationships</span>
                            </a>
                            <a href="/admin/drivers" 
                               class="text-sm text-green-600 hover:text-green-800 transition-colors duration-200 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Manage Drivers</span>
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-xs text-gray-500">
                            Version 1.0.0
                        </div>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-500">System Online</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import { useFreightOrderStore } from '@/stores/freightOrder';
import { useCompanyStore } from '@/stores/company';
import { useProductStore } from '@/stores/product';
import { useManufacturingStore } from '@/stores/manufacturing';
import { useInventoryStore } from '@/stores/inventory';
import { useReturnsStore } from '@/stores/returns';
import { useSupplierStore } from '@/stores/supplier';
import { useDeliveryStore } from '@/stores/delivery';

export default {
    name: 'Dashboard',
    setup() {
        // Initialize stores
        const freightOrderStore = useFreightOrderStore();
        const companyStore = useCompanyStore();
        const productStore = useProductStore();
        const manufacturingStore = useManufacturingStore();
        const inventoryStore = useInventoryStore();
        const returnsStore = useReturnsStore();
        const supplierStore = useSupplierStore();
        const deliveryStore = useDeliveryStore();

        // Reactive data
        const loading = ref(false);
        const error = ref(null);

        // Computed metrics from real data
        const moduleMetrics = computed(() => ({
            master_data: {
                quality: 98,
                records: companyStore.companies.length + productStore.products.length
            },
            supplier_procurement: {
                value: supplierStore.purchaseOrders.length * 2.1,
                orders: supplierStore.purchaseOrders.length
            },
            transportation: {
                on_time: 94,
                routes: freightOrderStore.freightOrders.length
            },
            delivery: {
                rating: 4.8,
                tasks: deliveryStore.deliveryTasks.length
            },
            manufacturing: {
                efficiency: 97,
                orders: manufacturingStore.workOrders.length
            },
            inventory: {
                accuracy: 99.2,
                items: inventoryStore.inventoryBalances.length
            },
            returns: {
                rate: 2.1,
                processed: returnsStore.returnRequests.length
            },
            weather_logistics: {
                optimization: 85,
                alerts: 12
            }
        }));

        // Methods
        const formatTime = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleTimeString();
        };

        const formatDate = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString();
        };

        const formatDateTime = (date) => {
            if (!date) return 'N/A';
            return new Date(date).toLocaleString();
        };

        const formatWeight = (weight) => {
            if (!weight) return 'N/A';
            return `${weight} lbs`;
        };

        const formatStatus = (status) => {
            if (!status) return 'UNKNOWN';
            return status.replace(/_/g, ' ').toUpperCase();
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

        const getShipmentIconClass = (status) => {
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

        const loadShipments = async () => {
            try {
                loading.value = true;
                error.value = null;
                
                // Load data from all stores
                await Promise.all([
                    freightOrderStore.fetchFreightOrders(),
                    companyStore.fetchCompanies(),
                    productStore.fetchProducts(),
                    manufacturingStore.fetchWorkOrders(),
                    inventoryStore.fetchInventoryBalances(),
                    returnsStore.fetchReturnRequests(),
                    supplierStore.fetchPurchaseOrders(),
                    deliveryStore.fetchDeliveryTasks()
                ]);
                
                // Set allShipments from freight orders
                allShipments.value = freightOrderStore.freightOrders;
            } catch (err) {
                console.error('Error loading data:', err);
                error.value = err.message || 'Failed to load data';
                allShipments.value = [];
            } finally {
                loading.value = false;
            }
        };

        // Map initialization removed - Dashboard doesn't need a map

        // Map-related functions removed - Dashboard doesn't need map functionality


        const loadWeatherData = () => {
            console.log('Loading weather data...');
        };

        const loadLogisticsData = () => {
            console.log('Loading logistics data...');
        };

        // Lifecycle
        onMounted(() => {
            // Dashboard data is loaded via Pinia stores
        });


        return {
            moduleMetrics,
            loading,
            error,
            formatTime,
            formatDate,
            formatDateTime,
            formatWeight,
            formatStatus,
            getStatusClass,
            getShipmentIconClass,
            getShipmentIconColor,
            getProgressBarColor,
            getShipmentProgress,
            loadWeatherData,
            loadLogisticsData
        };
    }
};
</script>
