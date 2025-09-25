<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmniChain - Supply Chain Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-3xl font-bold text-gray-900">🚀 OmniChain</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        ✅ System Online
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Welcome to OmniChain
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Your unified supply chain management platform for end-to-end logistics, 
                manufacturing, and delivery operations.
            </p>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">📦</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Shipments</p>
                        <p class="text-2xl font-bold text-gray-900">1,247</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">🚛</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Drivers</p>
                        <p class="text-2xl font-bold text-gray-900">89</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">🏭</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Production Lines</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">📊</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Orders Today</p>
                        <p class="text-2xl font-bold text-gray-900">342</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="/track-shipment" class="group">
                    <div class="bg-blue-50 rounded-lg p-6 hover:bg-blue-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🔍</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Track Shipment</h4>
                                <p class="text-gray-600">Monitor shipment status in real-time</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/transportation-management" class="group">
                    <div class="bg-green-50 rounded-lg p-6 hover:bg-green-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🚛</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Transportation</h4>
                                <p class="text-gray-600">Manage fleet and logistics</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/inventory-warehouse-management" class="group">
                    <div class="bg-yellow-50 rounded-lg p-6 hover:bg-yellow-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">📦</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Inventory</h4>
                                <p class="text-gray-600">Warehouse and inventory management</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/manufacturing-management" class="group">
                    <div class="bg-purple-50 rounded-lg p-6 hover:bg-purple-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🏭</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Manufacturing</h4>
                                <p class="text-gray-600">Production planning and control</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/delivery-management" class="group">
                    <div class="bg-red-50 rounded-lg p-6 hover:bg-red-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🚚</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Delivery</h4>
                                <p class="text-gray-600">Last-mile delivery management</p>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/driver" class="group">
                    <div class="bg-indigo-50 rounded-lg p-6 hover:bg-indigo-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">👨‍💼</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">Driver App</h4>
                                <p class="text-gray-600">Driver portal and management</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">System Status</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-green-600 text-2xl">✅</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Application</h4>
                    <p class="text-green-600 font-medium">Running</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-green-600 text-2xl">✅</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">Frontend</h4>
                    <p class="text-green-600 font-medium">Loaded</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-green-600 text-2xl">✅</span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">API Services</h4>
                    <p class="text-green-600 font-medium">Operational</p>
                </div>
            </div>
        </div>

        @if(isset($error))
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="text-lg font-semibold text-yellow-800 mb-2">Debug Information</h4>
            <p class="text-yellow-700"><strong>Error:</strong> {{ $error }}</p>
            <p class="text-yellow-700"><strong>Timestamp:</strong> {{ $timestamp }}</p>
        </div>
        @endif
    </main>
</body>
</html>
