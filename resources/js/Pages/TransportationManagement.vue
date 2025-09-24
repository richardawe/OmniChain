<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Transportation Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage vehicles, routes, and transportation operations</p>
          </div>
          <div class="flex space-x-3">
            <a
              href="/"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Back to Dashboard
            </a>
            <button @click="refreshData" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Refresh
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Related Modules Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Related Modules</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <a href="/master-data" class="group flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors">
            <div class="w-8 h-8 bg-gray-100 group-hover:bg-blue-100 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 group-hover:text-blue-900">Master Data</p>
              <p class="text-xs text-gray-500">Companies, Locations</p>
            </div>
          </a>
          
          <a href="/delivery-management" class="group flex items-center p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors">
            <div class="w-8 h-8 bg-gray-100 group-hover:bg-green-100 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-gray-600 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 group-hover:text-green-900">Delivery Management</p>
              <p class="text-xs text-gray-500">Last Mile Operations</p>
            </div>
          </a>
          
          <a href="/inventory-warehouse-management" class="group flex items-center p-3 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors">
            <div class="w-8 h-8 bg-gray-100 group-hover:bg-purple-100 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-gray-600 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 group-hover:text-purple-900">Inventory & Warehouse</p>
              <p class="text-xs text-gray-500">Outbound Shipments</p>
            </div>
          </a>
          
          <a href="/track-shipment" class="group flex items-center p-3 rounded-lg border border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-colors">
            <div class="w-8 h-8 bg-gray-100 group-hover:bg-orange-100 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-4 h-4 text-gray-600 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 group-hover:text-orange-900">Track Shipment</p>
              <p class="text-xs text-gray-500">Live Tracking</p>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Vehicles</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.vehicles?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Active Vehicles</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.vehicles?.active || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Route Plans</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.route_plans?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.route_plans?.in_progress || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button v-for="tab in tabs" :key="tab.id" 
                    @click="activeTab = tab.id"
                    :class="[
                      activeTab === tab.id
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                      'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                    ]">
              {{ tab.name }}
              <span v-if="tab.count !== undefined" 
                    :class="[
                      activeTab === tab.id ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900',
                      'ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium'
                    ]">
                {{ tab.count }}
              </span>
            </button>
          </nav>
        </div>

        <div class="p-6">
          <!-- Vehicles Tab -->
          <div v-if="activeTab === 'vehicles'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Vehicle Fleet</h3>
              <div class="flex space-x-3">
                <select v-model="vehicleFilters.status" @change="loadVehicles" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="inactive">Inactive</option>
                </select>
                <select v-model="vehicleFilters.vehicle_type" @change="loadVehicles"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Types</option>
                  <option value="truck">Truck</option>
                  <option value="van">Van</option>
                  <option value="trailer">Trailer</option>
                  <option value="container">Container</option>
                </select>
                <button @click="showAddVehicleModal = true"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Add Vehicle
                </button>
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="vehicle in vehicles.data" :key="vehicle.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ vehicle.vehicle_number || `Vehicle ${vehicle.id}` }}</div>
                          <div class="text-sm text-gray-500">{{ vehicle.make }} {{ vehicle.model }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ vehicle.vehicle_type }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ vehicle.assigned_driver?.name || 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="[
                        vehicle.status === 'active' ? 'bg-green-100 text-green-800' :
                        vehicle.status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800',
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                      ]">
                        {{ vehicle.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ vehicle.company?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="viewVehicle(vehicle.id)" 
                              class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                      <button @click="editVehicle(vehicle.id)" 
                              class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="vehicles.last_page > 1" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
              <div class="flex flex-1 justify-between sm:hidden">
                <button @click="loadVehicles(vehicles.current_page - 1)" 
                        :disabled="vehicles.current_page === 1"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                  Previous
                </button>
                <button @click="loadVehicles(vehicles.current_page + 1)" 
                        :disabled="vehicles.current_page === vehicles.last_page"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                  Next
                </button>
              </div>
              <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing {{ vehicles.from }} to {{ vehicles.to }} of {{ vehicles.total }} results
                  </p>
                </div>
                <div>
                  <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <button @click="loadVehicles(vehicles.current_page - 1)" 
                            :disabled="vehicles.current_page === 1"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                      <span class="sr-only">Previous</span>
                      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                      </svg>
                    </button>
                    <button v-for="page in visiblePages" :key="page" 
                            @click="loadVehicles(page)"
                            :class="[
                              page === vehicles.current_page 
                                ? 'relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                                : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'
                            ]">
                      {{ page }}
                    </button>
                    <button @click="loadVehicles(vehicles.current_page + 1)" 
                            :disabled="vehicles.current_page === vehicles.last_page"
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                      <span class="sr-only">Next</span>
                      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </div>

          <!-- Route Plans Tab -->
          <div v-if="activeTab === 'routes'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Route Plans</h3>
              <div class="flex space-x-3">
                <button @click="showAddRouteModal = true" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  Add New Route Plan
                </button>
                <select v-model="routeFilters.status" @change="loadRoutePlans" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="planned">Planned</option>
                  <option value="assigned">Assigned</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stops</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="route in routePlans.data" :key="route.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ route.route_number }}</div>
                      <div class="text-sm text-gray-500">{{ route.total_distance_km }} km</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ route.route_type }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ route.assigned_driver?.name || 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ route.completed_stops }}/{{ route.total_stops }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="[
                        route.status === 'completed' ? 'bg-green-100 text-green-800' :
                        route.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                        route.status === 'assigned' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-gray-100 text-gray-800',
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                      ]">
                        {{ route.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDate(route.planned_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="viewRoute(route.id)" 
                              class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                      <button @click="editRoute(route.id)" 
                              class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Telematics Tab -->
          <div v-if="activeTab === 'telematics'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Vehicle Telematics</h3>
              <div class="flex space-x-3">
                <select v-model="telematicsFilters.vehicle_id" @change="loadTelematics" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Vehicles</option>
                  <option v-for="vehicle in allVehicles" :key="vehicle.id" :value="vehicle.id">
                    {{ vehicle.vehicle_number }}
                  </option>
                </select>
              </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
              <ul class="divide-y divide-gray-200">
                <li v-for="telemetry in telematics.data" :key="telemetry.id">
                  <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center">
                        <div class="flex-shrink-0">
                          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ telemetry.vehicle?.vehicle_number }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ formatDateTime(telemetry.timestamp) }}
                          </div>
                        </div>
                      </div>
                      <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-900">
                          <span class="font-medium">{{ telemetry.speed_kmh }} km/h</span>
                        </div>
                        <div class="text-sm text-gray-900">
                          <span class="font-medium">{{ telemetry.fuel_level_percentage }}% fuel</span>
                        </div>
                        <div class="text-sm text-gray-900">
                          <span class="font-medium">{{ telemetry.engine_on ? 'Engine On' : 'Engine Off' }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Vehicle Modal -->
    <div v-if="showAddVehicleModal || showEditVehicleModal || showViewVehicleModal" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
              {{ showAddVehicleModal ? 'Add New Vehicle' : showEditVehicleModal ? 'Edit Vehicle' : 'Vehicle Details' }}
            </h3>
            <button @click="closeVehicleModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <form @submit.prevent="saveVehicle" v-if="showAddVehicleModal || showEditVehicleModal">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Vehicle Number</label>
                <input v-model="vehicleForm.vehicle_number" type="text" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">License Plate</label>
                <input v-model="vehicleForm.license_plate" type="text" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Make</label>
                <input v-model="vehicleForm.make" type="text" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Model</label>
                <input v-model="vehicleForm.model" type="text" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                <select v-model="vehicleForm.vehicle_type" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  <option value="">Select Type</option>
                  <option value="truck">Truck</option>
                  <option value="van">Van</option>
                  <option value="trailer">Trailer</option>
                  <option value="container">Container</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select v-model="vehicleForm.status" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                  <option value="">Select Status</option>
                  <option value="active">Active</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
              <button type="button" @click="closeVehicleModal"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
              </button>
              <button type="submit" :disabled="loading"
                      class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ showEditVehicleModal ? 'Update' : 'Create' }}
              </button>
            </div>
          </form>
          
          <!-- View Mode -->
          <div v-if="showViewVehicleModal" class="space-y-4">
            <div v-if="selectedVehicle">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-500">Vehicle Number</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.vehicle_number }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">License Plate</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.license_plate }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Make</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.make }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Model</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.model }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Type</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.vehicle_type }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Status</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.status }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Driver</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.assigned_driver?.name || 'Unassigned' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-500">Company</label>
                  <p class="mt-1 text-sm text-gray-900">{{ selectedVehicle.company?.name }}</p>
                </div>
              </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
              <button @click="closeVehicleModal"
                      class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Close
              </button>
              <button @click="editVehicle(selectedVehicle?.id)"
                      class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Route Plan Modal -->
  <div v-if="showAddRouteModal || showEditRouteModal || showViewRouteModal" 
       class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            {{ showAddRouteModal ? 'Add New Route Plan' : showEditRouteModal ? 'Edit Route Plan' : 'Route Plan Details' }}
          </h3>
          <button @click="closeRouteModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <!-- Form Mode -->
        <form @submit.prevent="saveRoute" v-if="showAddRouteModal || showEditRouteModal">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Route Name</label>
              <input v-model="routeForm.route_name" type="text" required 
                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Vehicle</label>
              <select v-model="routeForm.vehicle_id" required 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Vehicle</option>
                <option v-for="vehicle in allVehicles" :key="vehicle.id" :value="vehicle.id">
                  {{ vehicle.vehicle_number }} - {{ vehicle.license_plate }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Driver</label>
              <select v-model="routeForm.driver_id" 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Driver (Optional)</option>
                <!-- Add drivers when available -->
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Origin Location</label>
              <select v-model="routeForm.origin_location_id" required 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Origin</option>
                <!-- Add locations when available -->
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Destination Location</label>
              <select v-model="routeForm.destination_location_id" required 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Destination</option>
                <!-- Add locations when available -->
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select v-model="routeForm.route_status" required 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="planned">Planned</option>
                <option value="active">Active</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Planned Departure</label>
              <input v-model="routeForm.planned_departure_time" type="datetime-local" required 
                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Planned Arrival</label>
              <input v-model="routeForm.planned_arrival_time" type="datetime-local" required 
                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total Distance (km)</label>
              <input v-model="routeForm.total_distance_km" type="number" step="0.1" required 
                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Estimated Duration (minutes)</label>
              <input v-model="routeForm.estimated_duration_minutes" type="number" required 
                     class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 mt-6">
            <button type="button" @click="closeRouteModal" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
              Cancel
            </button>
            <button type="submit" 
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              {{ showEditRouteModal ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>

        <!-- View Mode -->
        <div v-if="showViewRouteModal" class="space-y-4">
          <div v-if="selectedRoute">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-500">Route Name</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.route_name }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Vehicle</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.vehicle?.vehicle_number }} - {{ selectedRoute.vehicle?.license_plate }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Driver</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.driver?.name || 'Unassigned' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.route_status }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Origin</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.origin_location?.name || 'N/A' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Destination</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.destination_location?.name || 'N/A' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Distance</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.total_distance_km }} km</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Duration</label>
                <p class="mt-1 text-sm text-gray-900">{{ selectedRoute.estimated_duration_minutes }} minutes</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Departure</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedRoute.planned_departure_time) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-500">Arrival</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedRoute.planned_arrival_time) }}</p>
              </div>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3">
            <button @click="closeRouteModal" 
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
              Close
            </button>
            <button @click="editRoute(selectedRoute?.id)"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
              Edit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import axios from 'axios'

// Reactive data
const summary = ref({})
const vehicles = ref({ data: [] })
const routePlans = ref({ data: [] })
const telematics = ref({ data: [] })
const allVehicles = ref([])
const loading = ref(false)

const activeTab = ref('vehicles')

// Modal states
const showAddVehicleModal = ref(false)
const showEditVehicleModal = ref(false)
const showViewVehicleModal = ref(false)
const selectedVehicle = ref(null)

const showAddRouteModal = ref(false)
const showEditRouteModal = ref(false)
const showViewRouteModal = ref(false)
const selectedRoute = ref(null)
const editingRouteId = ref(null)

// Form data
const vehicleForm = ref({
  vehicle_number: '',
  license_plate: '',
  make: '',
  model: '',
  vehicle_type: '',
  status: '',
  year: new Date().getFullYear(),
  vehicle_class: 'medium_duty',
  max_weight_kg: '',
  max_volume_cubic_meters: '',
  max_pallets: '',
  fuel_type: 'diesel',
  fuel_capacity_liters: '',
  average_fuel_consumption_km_per_liter: '',
  company_id: 1 // Default company
})

const routeForm = ref({
  route_name: '',
  vehicle_id: '',
  driver_id: '',
  origin_location_id: '',
  destination_location_id: '',
  planned_departure_time: '',
  planned_arrival_time: '',
  total_distance_km: '',
  estimated_duration_minutes: '',
  route_status: 'planned'
})

const vehicleFilters = ref({
  status: '',
  vehicle_type: ''
})

const routeFilters = ref({
  status: ''
})

const telematicsFilters = ref({
  vehicle_id: ''
})

// Computed properties
const tabs = computed(() => [
  { id: 'vehicles', name: 'Vehicles', count: summary.value.vehicles?.total },
  { id: 'routes', name: 'Route Plans', count: summary.value.route_plans?.total },
  { id: 'telematics', name: 'Telematics', count: telematics.value.total }
])

const visiblePages = computed(() => {
  if (!vehicles.value.last_page) return []
  const current = vehicles.value.current_page
  const last = vehicles.value.last_page
  const delta = 2
  
  let start = Math.max(1, current - delta)
  let end = Math.min(last, current + delta)
  
  const pages = []
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Methods
const loadSummary = async () => {
  try {
    const response = await axios.get('/api/v1/transportation/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadVehicles = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...vehicleFilters.value
    }
    
    const response = await axios.get('/api/v1/transportation/vehicles', { params })
    
    if (response.data.success && response.data.data) {
      vehicles.value = response.data.data
    } else {
      console.error('Invalid response structure:', response.data)
      vehicles.value = { data: [] }
    }
    
    // Load all vehicles for dropdown
    if (allVehicles.value.length === 0) {
      const allResponse = await axios.get('/api/v1/transportation/vehicles', { params: { per_page: 100 } })
      allVehicles.value = allResponse.data.data.data
    }
  } catch (error) {
    console.error('Error loading vehicles:', error)
  } finally {
    loading.value = false
  }
}

const loadRoutePlans = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...routeFilters.value
    }
    
    const response = await axios.get('/api/v1/transportation/route-plans', { params })
    
    if (response.data.success && response.data.data) {
      routePlans.value = response.data.data
    } else {
      console.error('Invalid route plans response:', response.data)
      routePlans.value = { data: [] }
    }
  } catch (error) {
    console.error('Error loading route plans:', error)
  } finally {
    loading.value = false
  }
}

const loadTelematics = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      per_page: 20,
      ...telematicsFilters.value
    }
    
    const response = await axios.get('/api/v1/transportation/vehicle-telematics', { params })
    
    if (response.data.success && response.data.data) {
      telematics.value = response.data.data
    } else {
      console.error('Invalid telematics response:', response.data)
      telematics.value = { data: [] }
    }
  } catch (error) {
    console.error('Error loading telematics:', error)
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  loadSummary()
  if (activeTab.value === 'vehicles') loadVehicles()
  if (activeTab.value === 'routes') loadRoutePlans()
  if (activeTab.value === 'telematics') loadTelematics()
}

const viewVehicle = async (id) => {
  try {
    const response = await axios.get(`/api/v1/transportation/vehicles/${id}`)
    selectedVehicle.value = response.data.data
    showViewVehicleModal.value = true
    showEditVehicleModal.value = false
    showAddVehicleModal.value = false
  } catch (error) {
    console.error('Error loading vehicle:', error)
    alert('Error loading vehicle details')
  }
}

const editVehicle = async (id) => {
  try {
    const response = await axios.get(`/api/v1/transportation/vehicles/${id}`)
    const vehicle = response.data.data
    vehicleForm.value = {
      ...vehicle,
      vehicle_number: vehicle.vehicle_number || '',
      license_plate: vehicle.license_plate || '',
      make: vehicle.make || '',
      model: vehicle.model || '',
      vehicle_type: vehicle.vehicle_type || '',
      status: vehicle.status || '',
      year: vehicle.year || new Date().getFullYear(),
      vehicle_class: vehicle.vehicle_class || 'medium_duty',
      max_weight_kg: vehicle.max_weight_kg || '',
      max_volume_cubic_meters: vehicle.max_volume_cubic_meters || '',
      max_pallets: vehicle.max_pallets || '',
      fuel_type: vehicle.fuel_type || 'diesel',
      fuel_capacity_liters: vehicle.fuel_capacity_liters || '',
      average_fuel_consumption_km_per_liter: vehicle.average_fuel_consumption_km_per_liter || '',
      company_id: vehicle.company_id || 1
    }
    showEditVehicleModal.value = true
    showViewVehicleModal.value = false
    showAddVehicleModal.value = false
  } catch (error) {
    console.error('Error loading vehicle for edit:', error)
    alert('Error loading vehicle for edit')
  }
}

const saveVehicle = async () => {
  try {
    loading.value = true
    
    if (showEditVehicleModal.value) {
      // Update existing vehicle
      await axios.put(`/api/v1/transportation/vehicles/${selectedVehicle.value.id}`, vehicleForm.value)
    } else {
      // Create new vehicle
      await axios.post('/api/v1/transportation/vehicles', vehicleForm.value)
    }
    
    closeVehicleModal()
    loadVehicles()
    loadSummary()
    alert(showEditVehicleModal.value ? 'Vehicle updated successfully!' : 'Vehicle created successfully!')
  } catch (error) {
    console.error('Error saving vehicle:', error)
    alert('Error saving vehicle')
  } finally {
    loading.value = false
  }
}

const closeVehicleModal = () => {
  showAddVehicleModal.value = false
  showEditVehicleModal.value = false
  showViewVehicleModal.value = false
  selectedVehicle.value = null
  vehicleForm.value = {
    vehicle_number: '',
    license_plate: '',
    make: '',
    model: '',
    vehicle_type: '',
    status: '',
    year: new Date().getFullYear(),
    vehicle_class: 'medium_duty',
    max_weight_kg: '',
    max_volume_cubic_meters: '',
    max_pallets: '',
    fuel_type: 'diesel',
    fuel_capacity_liters: '',
    average_fuel_consumption_km_per_liter: '',
    company_id: 1
  }
}

const viewRoute = async (id) => {
  try {
    const response = await axios.get(`/api/v1/transportation/route-plans/${id}`)
    selectedRoute.value = response.data.data
    showViewRouteModal.value = true
    showEditRouteModal.value = false
  } catch (error) {
    console.error('Error fetching route plan:', error)
    alert('Failed to load route plan details')
  }
}

const editRoute = async (id) => {
  try {
    const response = await axios.get(`/api/v1/transportation/route-plans/${id}`)
    const route = response.data.data
    routeForm.value = {
      route_name: route.route_name || '',
      vehicle_id: route.vehicle_id || '',
      driver_id: route.driver_id || '',
      origin_location_id: route.origin_location_id || '',
      destination_location_id: route.destination_location_id || '',
      planned_departure_time: route.planned_departure_time ? new Date(route.planned_departure_time).toISOString().slice(0, 16) : '',
      planned_arrival_time: route.planned_arrival_time ? new Date(route.planned_arrival_time).toISOString().slice(0, 16) : '',
      total_distance_km: route.total_distance_km || '',
      estimated_duration_minutes: route.estimated_duration_minutes || '',
      route_status: route.route_status || 'planned'
    }
    showEditRouteModal.value = true
    showViewRouteModal.value = false
    editingRouteId.value = id
  } catch (error) {
    console.error('Error fetching route plan:', error)
    alert('Failed to load route plan details')
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString()
}

const saveRoute = async () => {
  try {
    if (showEditRouteModal.value) {
      // Update existing route
      await axios.put(`/api/v1/transportation/route-plans/${editingRouteId.value}`, routeForm.value)
    } else {
      // Create new route
      await axios.post('/api/v1/transportation/route-plans', routeForm.value)
    }
    
    closeRouteModal()
    loadRoutePlans()
    loadSummary()
    alert(showEditRouteModal.value ? 'Route plan updated successfully!' : 'Route plan created successfully!')
  } catch (error) {
    console.error('Error saving route plan:', error)
    alert('Failed to save route plan')
  }
}

const closeRouteModal = () => {
  showAddRouteModal.value = false
  showEditRouteModal.value = false
  showViewRouteModal.value = false
  selectedRoute.value = null
  editingRouteId.value = null
  routeForm.value = {
    route_name: '',
    vehicle_id: '',
    driver_id: '',
    origin_location_id: '',
    destination_location_id: '',
    planned_departure_time: '',
    planned_arrival_time: '',
    total_distance_km: '',
    estimated_duration_minutes: '',
    route_status: 'planned'
  }
}

// Watch for tab changes
watch(activeTab, (newTab) => {
  if (newTab === 'vehicles') {
    loadVehicles()
  } else if (newTab === 'routes') {
    loadRoutePlans()
  } else if (newTab === 'telematics') {
    loadTelematics()
  }
})

// Initialize
onMounted(() => {
  loadSummary()
  loadVehicles()
})
</script>
