<template>
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        Module Relationships
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Visual representation of data flow between supply chain modules</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600">Connected</span>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <!-- Module Relationship Flow -->
            <div class="relative">
                <!-- Flow Arrows -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 800 400">
                    <!-- Master Data to All Modules -->
                    <defs>
                        <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                            <polygon points="0 0, 10 3.5, 0 7" fill="#6B7280" />
                        </marker>
                        <marker id="arrowhead-blue" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                            <polygon points="0 0, 10 3.5, 0 7" fill="#3B82F6" />
                        </marker>
                        <marker id="arrowhead-green" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                            <polygon points="0 0, 10 3.5, 0 7" fill="#10B981" />
                        </marker>
                    </defs>
                    
                    <!-- Master Data connections -->
                    <line x1="100" y1="100" x2="200" y2="120" stroke="#6B7280" stroke-width="2" marker-end="url(#arrowhead)" />
                    <line x1="100" y1="100" x2="200" y2="160" stroke="#6B7280" stroke-width="2" marker-end="url(#arrowhead)" />
                    <line x1="100" y1="100" x2="200" y2="200" stroke="#6B7280" stroke-width="2" marker-end="url(#arrowhead)" />
                    <line x1="100" y1="100" x2="200" y2="240" stroke="#6B7280" stroke-width="2" marker-end="url(#arrowhead)" />
                    
                    <!-- Supplier & Procurement to Manufacturing -->
                    <line x1="300" y1="120" x2="400" y2="140" stroke="#3B82F6" stroke-width="2" marker-end="url(#arrowhead-blue)" />
                    
                    <!-- Manufacturing to Inventory -->
                    <line x1="500" y1="140" x2="600" y2="160" stroke="#10B981" stroke-width="2" marker-end="url(#arrowhead-green)" />
                    
                    <!-- Inventory to Transportation -->
                    <line x1="700" y1="160" x2="650" y2="200" stroke="#10B981" stroke-width="2" marker-end="url(#arrowhead-green)" />
                    
                    <!-- Transportation to Delivery -->
                    <line x1="650" y1="200" x2="650" y2="240" stroke="#3B82F6" stroke-width="2" marker-end="url(#arrowhead-blue)" />
                    
                    <!-- Delivery to Returns -->
                    <line x1="650" y1="240" x2="600" y2="280" stroke="#EF4444" stroke-width="2" marker-end="url(#arrowhead)" />
                    
                    <!-- Weather & Logistics to Transportation and Delivery -->
                    <line x1="450" y1="320" x2="500" y2="200" stroke="#F59E0B" stroke-width="2" marker-end="url(#arrowhead)" />
                    <line x1="450" y1="320" x2="550" y2="240" stroke="#F59E0B" stroke-width="2" marker-end="url(#arrowhead)" />
                </svg>
                
                <!-- Module Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Master Data -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Master Data</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Foundation</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Supplier & Procurement -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Supplier & Procurement</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Sourcing</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Manufacturing -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Manufacturing</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Production</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory & Warehouse -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Inventory & Warehouse</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Storage</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transportation -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Transportation</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Logistics</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delivery Management -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl p-4 border border-cyan-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Delivery Management</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Last Mile</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Returns & Reverse Logistics -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 14v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2h12a2 2 0 002-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Returns & Reverse Logistics</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Reverse Flow</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Weather & Logistics -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 text-center">Weather & Logistics</h3>
                            <p class="text-xs text-gray-600 text-center mt-1">Intelligence</p>
                            <div class="mt-2 flex justify-center space-x-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Flow Legend -->
            <div class="mt-8 p-6 bg-gray-50 rounded-xl">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Flow Legend</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-0.5 bg-gray-500"></div>
                        <div class="w-2 h-2 bg-gray-500 transform rotate-45"></div>
                        <span class="text-sm text-gray-600">Master Data References</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-0.5 bg-blue-500"></div>
                        <div class="w-2 h-2 bg-blue-500 transform rotate-45"></div>
                        <span class="text-sm text-gray-600">Procurement to Manufacturing</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-0.5 bg-green-500"></div>
                        <div class="w-2 h-2 bg-green-500 transform rotate-45"></div>
                        <span class="text-sm text-gray-600">Production Flow</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-0.5 bg-red-500"></div>
                        <div class="w-2 h-2 bg-red-500 transform rotate-45"></div>
                        <span class="text-sm text-gray-600">Return Flow</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-0.5 bg-yellow-500"></div>
                        <div class="w-2 h-2 bg-yellow-500 transform rotate-45"></div>
                        <span class="text-sm text-gray-600">Weather Intelligence</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-sm text-gray-600">Active Connections</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModuleRelationshipDiagram',
    props: {
        modules: {
            type: Array,
            default: () => []
        }
    },
    setup() {
        return {
            // Component logic can be added here
        }
    }
}
</script>
