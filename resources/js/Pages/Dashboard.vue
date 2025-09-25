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
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-blue-100/40 before:to-blue-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
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

                        <!-- Track Shipment Tile -->
                        <a href="/track-shipment" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-green-100/40 before:to-green-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl group-hover:from-green-600 group-hover:to-green-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-300">Track Shipment</h3>
                                            <p class="text-sm text-gray-500 font-medium">Real-time Shipment Tracking</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Monitor shipment status in real-time with live tracking, events, and delivery confirmations.</p>
                                    <div class="mt-4 flex items-center text-green-600 text-sm font-medium group-hover:text-green-700 transition-colors duration-300">
                                        <span>Track Now</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Transportation Management Tile -->
                        <a href="/transportation-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-purple-100/40 before:to-purple-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl group-hover:from-purple-600 group-hover:to-purple-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">Transportation</h3>
                                            <p class="text-sm text-gray-500 font-medium">Fleet & Route Management</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage fleet operations, route planning, vehicle telematics, and transportation logistics.</p>
                                    <div class="mt-4 flex items-center text-purple-600 text-sm font-medium group-hover:text-purple-700 transition-colors duration-300">
                                        <span>Manage Fleet</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Inventory & Warehouse Management Tile -->
                        <a href="/inventory-warehouse-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-orange-100/40 before:to-orange-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl group-hover:from-orange-600 group-hover:to-orange-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-300">Inventory & WMS</h3>
                                            <p class="text-sm text-gray-500 font-medium">Warehouse Management</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage inventory balances, warehouse operations, inbound/outbound shipments, and cycle counts.</p>
                                    <div class="mt-4 flex items-center text-orange-600 text-sm font-medium group-hover:text-orange-700 transition-colors duration-300">
                                        <span>Manage Inventory</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Manufacturing Management Tile -->
                        <a href="/manufacturing-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-indigo-100/40 before:to-indigo-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl group-hover:from-indigo-600 group-hover:to-indigo-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">Manufacturing</h3>
                                            <p class="text-sm text-gray-500 font-medium">Production Management</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage production planning, work orders, BOMs, material movements, and quality control.</p>
                                    <div class="mt-4 flex items-center text-indigo-600 text-sm font-medium group-hover:text-indigo-700 transition-colors duration-300">
                                        <span>Manage Production</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Delivery Management Tile -->
                        <a href="/delivery-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-red-100/40 before:to-red-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl group-hover:from-red-600 group-hover:to-red-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors duration-300">Delivery Management</h3>
                                            <p class="text-sm text-gray-500 font-medium">Last-Mile Operations</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage order fulfillment, delivery tasks, customer notifications, and last-mile logistics.</p>
                                    <div class="mt-4 flex items-center text-red-600 text-sm font-medium group-hover:text-red-700 transition-colors duration-300">
                                        <span>Manage Deliveries</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Supplier Procurement Tile -->
                        <a href="/supplier-procurement" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-teal-100/40 before:to-teal-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-teal-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl group-hover:from-teal-600 group-hover:to-teal-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-teal-600 transition-colors duration-300">Supplier Procurement</h3>
                                            <p class="text-sm text-gray-500 font-medium">SRM & Procurement</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage supplier relationships, procurement processes, contracts, and supplier performance.</p>
                                    <div class="mt-4 flex items-center text-teal-600 text-sm font-medium group-hover:text-teal-700 transition-colors duration-300">
                                        <span>Manage Suppliers</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Returns Management Tile -->
                        <a href="/returns-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-yellow-100/40 before:to-yellow-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl group-hover:from-yellow-600 group-hover:to-yellow-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-yellow-600 transition-colors duration-300">Returns Management</h3>
                                            <p class="text-sm text-gray-500 font-medium">Reverse Logistics</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Manage returns, RMAs, repair orders, and reverse logistics operations.</p>
                                    <div class="mt-4 flex items-center text-yellow-600 text-sm font-medium group-hover:text-yellow-700 transition-colors duration-300">
                                        <span>Manage Returns</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Module Relationships Tile -->
                        <a href="/module-relationships" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-pink-100/40 before:to-pink-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl group-hover:from-pink-600 group-hover:to-pink-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-pink-600 transition-colors duration-300">Module Relationships</h3>
                                            <p class="text-sm text-gray-500 font-medium">Data Flow & Integration</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Visualize data relationships and integration flows between all supply chain modules.</p>
                                    <div class="mt-4 flex items-center text-pink-600 text-sm font-medium group-hover:text-pink-700 transition-colors duration-300">
                                        <span>View Relationships</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Driver Management Tile -->
                        <a href="/driver-management" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-emerald-100/40 before:to-emerald-50/10 before:rounded-2xl before:z-0">
                                           <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                                style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
                                           <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                           <div class="relative">
                                               <div class="flex items-center mb-6">
                                                   <div class="p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl group-hover:from-emerald-600 group-hover:to-emerald-700 transition-all duration-300 shadow-lg group-hover:shadow-xl">
                                                       <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                       </svg>
                                                   </div>
                                                   <div class="ml-4">
                                                       <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300">Driver Management</h3>
                                                       <p class="text-sm text-gray-500 font-medium">Fleet & Driver Operations</p>
                                                   </div>
                                               </div>
                                               <p class="text-sm text-gray-600 leading-relaxed">Manage driver profiles, assignments, performance tracking, and fleet operations.</p>
                                               <div class="mt-4 flex items-center text-emerald-600 text-sm font-medium group-hover:text-emerald-700 transition-colors duration-300">
                                                   <span>Manage Drivers</span>
                                                   <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                   </svg>
                                               </div>
                                           </div>
                                       </div>
                                   </a>

                        <!-- Weather & Logistics Tile -->
                        <a href="/weather-logistics" class="group transform hover:scale-105 transition-all duration-300">
                            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 hover:shadow-2xl hover:bg-white/90 transition-all duration-300 relative overflow-hidden before:absolute before:inset-0 before:bg-gradient-to-br before:from-cyan-100/40 before:to-cyan-50/10 before:rounded-2xl before:z-0">
                                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat rounded-2xl opacity-30 group-hover:opacity-15 transition-all duration-500 transform group-hover:scale-105" 
                                     style="background-image: url('https://images.unsplash.com/photo-1504608524841-42fe6f032b4b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80')"></div>
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
                                            <p class="text-sm text-gray-500 font-medium">Intelligence & Analytics</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">Real-time weather data, route optimization, and logistics intelligence for better decision making.</p>
                                    <div class="mt-4 flex items-center text-cyan-600 text-sm font-medium group-hover:text-cyan-700 transition-colors duration-300">
                                        <span>View Intelligence</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                           </div>
                                       </div>
                                   </a>
                    </div>
                </div>

                <!-- Coming Soon Modules -->
                <div class="mb-12">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-indigo-800 bg-clip-text text-transparent mb-2">
                            Coming Soon
                        </h2>
                        <p class="text-gray-600">Advanced modules currently in development</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Analytics & Forecasting -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-600">Analytics & Forecasting</h3>
                                            <p class="text-sm text-gray-500 font-medium">Coming Soon</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed">Advanced analytics, demand forecasting, and predictive maintenance capabilities.</p>
                                    <div class="mt-4 flex items-center text-gray-500 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compliance & Safety -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-600">Compliance & Safety</h3>
                                            <p class="text-sm text-gray-500 font-medium">Coming Soon</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed">Safety incident tracking, compliance monitoring, and audit management.</p>
                                    <div class="mt-4 flex items-center text-gray-500 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sustainability & ESG -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-600">Sustainability & ESG</h3>
                                            <p class="text-sm text-gray-500 font-medium">Coming Soon</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed">Track emissions, carbon footprint, and environmental impact across the supply chain.</p>
                                    <div class="mt-4 flex items-center text-gray-500 text-sm font-medium">
                                        <span>Coming Soon</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Finance & Costing -->
                        <div class="group transform hover:scale-105 transition-all duration-300 opacity-75">
                            <div class="bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-white/20 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gray-100/50 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
                                <div class="relative">
                                    <div class="flex items-center mb-6">
                                        <div class="p-4 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-xl font-bold text-gray-600">Finance & Costing</h3>
                                            <p class="text-sm text-gray-500 font-medium">Coming Soon</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed">Financial management, invoicing, landed cost calculations, and cost optimization.</p>
                                    <div class="mt-4 flex items-center text-gray-500 text-sm font-medium">
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
                                       <a href="/weather-logistics" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                           <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                           </svg>
                                           Weather & Logistics
                                       </a>
                        </div>
                    </div>
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

        return {
            currentView,
            formatTime
        };
    }
};
</script>