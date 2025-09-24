<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <!-- Navigation -->
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
                                    Module Relationships
                                </h1>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                                    Supply Chain Module Interconnections
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" 
                           class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all duration-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                                </svg>
                                <span>Back to Dashboard</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="px-6 py-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-indigo-800 bg-clip-text text-transparent mb-4">
                    Supply Chain Module Relationships
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                    Visual representation of how all supply chain modules interconnect and depend on each other for seamless operations.
                </p>
            </div>

            <!-- Module Relationship Diagram -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100/50 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                Module Interconnection Map
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">Interactive diagram showing data flow and dependencies between modules</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-600">Live Diagram</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <!-- SVG Diagram -->
                    <div class="w-full h-auto">
                        <svg viewBox="0 0 1200 800" class="w-full h-auto">
                            <!-- Background -->
                            <rect width="1200" height="800" fill="url(#gradient)" />
                            <defs>
                                <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#f8fafc;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#e2e8f0;stop-opacity:1" />
                                </linearGradient>
                            </defs>

                            <!-- Master Data (Center) -->
                            <g transform="translate(500, 350)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#3B82F6" stroke="#1E40AF" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Master Data</text>
                                <text x="0" y="10" text-anchor="middle" class="text-blue-100 text-xs">Core Reference</text>
                            </g>

                            <!-- Supplier & Procurement (Top Left) -->
                            <g transform="translate(200, 150)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#10B981" stroke="#059669" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Supplier &</text>
                                <text x="0" y="5" text-anchor="middle" class="text-white font-bold text-sm">Procurement</text>
                                <text x="0" y="20" text-anchor="middle" class="text-green-100 text-xs">Vendor Management</text>
                            </g>

                            <!-- Transportation (Top Right) -->
                            <g transform="translate(800, 150)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#8B5CF6" stroke="#7C3AED" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Transportation</text>
                                <text x="0" y="10" text-anchor="middle" class="text-purple-100 text-xs">Logistics & TMS</text>
                            </g>

                            <!-- Manufacturing (Bottom Left) -->
                            <g transform="translate(200, 550)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#EF4444" stroke="#DC2626" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Manufacturing</text>
                                <text x="0" y="10" text-anchor="middle" class="text-red-100 text-xs">Production & MES</text>
                            </g>

                            <!-- Inventory & Warehouse (Bottom Right) -->
                            <g transform="translate(800, 550)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#6366F1" stroke="#4F46E5" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Inventory &</text>
                                <text x="0" y="5" text-anchor="middle" class="text-white font-bold text-sm">Warehouse</text>
                                <text x="0" y="20" text-anchor="middle" class="text-indigo-100 text-xs">WMS & Storage</text>
                            </g>

                            <!-- Delivery Management (Top Center) -->
                            <g transform="translate(500, 150)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#F97316" stroke="#EA580C" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Delivery</text>
                                <text x="0" y="5" text-anchor="middle" class="text-white font-bold text-sm">Management</text>
                                <text x="0" y="20" text-anchor="middle" class="text-orange-100 text-xs">Last-Mile & POD</text>
                            </g>

                            <!-- Returns Management (Bottom Center) -->
                            <g transform="translate(500, 550)">
                                <rect x="-80" y="-40" width="160" height="80" rx="12" fill="#EC4899" stroke="#DB2777" stroke-width="2"/>
                                <text x="0" y="-10" text-anchor="middle" class="text-white font-bold text-sm">Returns &</text>
                                <text x="0" y="5" text-anchor="middle" class="text-white font-bold text-sm">Reverse Logistics</text>
                                <text x="0" y="20" text-anchor="middle" class="text-pink-100 text-xs">RMA Processing</text>
                            </g>

                            <!-- Arrows showing relationships -->
                            <!-- Master Data to all modules -->
                            <line x1="500" y1="350" x2="200" y2="150" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="350" x2="800" y2="150" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="350" x2="500" y2="150" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="350" x2="200" y2="550" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="350" x2="800" y2="550" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="350" x2="500" y2="550" stroke="#3B82F6" stroke-width="3" marker-end="url(#arrowhead)"/>

                            <!-- Inter-module relationships -->
                            <line x1="200" y1="150" x2="500" y2="150" stroke="#10B981" stroke-width="2" stroke-dasharray="5,5" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="150" x2="800" y2="150" stroke="#F97316" stroke-width="2" stroke-dasharray="5,5" marker-end="url(#arrowhead)"/>
                            <line x1="200" y1="550" x2="500" y2="550" stroke="#EF4444" stroke-width="2" stroke-dasharray="5,5" marker-end="url(#arrowhead)"/>
                            <line x1="500" y1="550" x2="800" y2="550" stroke="#EC4899" stroke-width="2" stroke-dasharray="5,5" marker-end="url(#arrowhead)"/>

                            <!-- Arrow marker definition -->
                            <defs>
                                <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="9" refY="3.5" orient="auto">
                                    <polygon points="0 0, 10 3.5, 0 7" fill="#3B82F6" />
                                </marker>
                            </defs>
                        </svg>
                    </div>

                    <!-- Legend -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                                <div>
                                    <h4 class="font-semibold text-blue-900">Core Data Flow</h4>
                                    <p class="text-sm text-blue-700">Master data provides reference information to all modules</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <div>
                                    <h4 class="font-semibold text-green-900">Direct Integration</h4>
                                    <p class="text-sm text-green-700">Modules that directly exchange data and processes</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-orange-500 rounded"></div>
                                <div>
                                    <h4 class="font-semibold text-orange-900">Process Flow</h4>
                                    <p class="text-sm text-orange-700">Sequential operations and workflow dependencies</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module Details -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-white/20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Data Dependencies</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Master Data feeds all modules</li>
                        <li>• Supplier data flows to Procurement</li>
                        <li>• Product data drives Manufacturing</li>
                        <li>• Location data enables Transportation</li>
                    </ul>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-white/20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Process Flows</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Procurement → Manufacturing</li>
                        <li>• Manufacturing → Inventory</li>
                        <li>• Inventory → Transportation</li>
                        <li>• Transportation → Delivery</li>
                    </ul>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-white/20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Integration Points</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Real-time data synchronization</li>
                        <li>• Event-driven notifications</li>
                        <li>• Cross-module reporting</li>
                        <li>• Unified user experience</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModuleRelationships',
    setup() {
        return {};
    }
};
</script>

<style scoped>
/* Custom styles for the diagram */
text {
    font-family: 'Inter', system-ui, sans-serif;
}
</style>
