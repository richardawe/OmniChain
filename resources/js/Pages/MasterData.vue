<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Master Data Management</h1>
            <p class="mt-1 text-sm text-gray-600">Comprehensive supply chain master data</p>
          </div>
          <div class="flex space-x-4">
            <a
              href="/"
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Back to Dashboard
            </a>
            <button
              @click="showCreateCompanyModal = true"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Add Company
            </button>
            <button
              @click="showCreateLocationModal = true"
              class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Add Location
            </button>
            <button
              @click="showCreateProductModal = true"
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Add Product
            </button>
            <button
              @click="refreshData"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Refresh Data
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Companies</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.companies?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Locations</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.locations?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Products</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.products?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Employees</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.employees?.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white shadow">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                activeTab === tab.id
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              {{ tab.name }}
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Companies Tab -->
          <div v-if="activeTab === 'companies'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Company Types</h3>
                <div class="space-y-2">
                  <div v-for="type in summary.companies?.by_type" :key="type.type" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ type.type }}</span>
                    <span class="text-sm font-medium">{{ type.count }}</span>
                  </div>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Business Types</h3>
                <div class="space-y-2">
                  <div v-for="business in summary.companies?.by_business_type" :key="business.business_type" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ business.business_type }}</span>
                    <span class="text-sm font-medium">{{ business.count }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DUNS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="company in companies" :key="company.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">{{ company.name.charAt(0) }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ company.name }}</div>
                          <div class="text-sm text-gray-500">{{ company.code }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ company.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ company.business_type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ company.duns_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(company.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ company.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Locations Tab -->
          <div v-if="activeTab === 'locations'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location Types</h3>
                <div class="space-y-2">
                  <div v-for="type in summary.locations?.by_type" :key="type.type" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ type.type }}</span>
                    <span class="text-sm font-medium">{{ type.count }}</span>
                  </div>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Capacity Summary</h3>
                <div class="space-y-2">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Volume (m³)</span>
                    <span class="text-sm font-medium">{{ formatNumber(summary.locations?.total_capacity_volume) }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Weight (kg)</span>
                    <span class="text-sm font-medium">{{ formatNumber(summary.locations?.total_capacity_weight) }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loading Bays</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="location in locations" :key="location.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ location.name }}</div>
                          <div class="text-sm text-gray-500">{{ location.code }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.company?.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="location.capacity_volume">
                        {{ formatNumber(location.capacity_volume) }}m³
                      </div>
                      <div v-if="location.capacity_weight">
                        {{ formatNumber(location.capacity_weight) }}kg
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ location.loading_bays_count || 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(location.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ location.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Products Tab -->
          <div v-if="activeTab === 'products'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Categories</h3>
                <div class="space-y-2">
                  <div v-for="category in summary.products?.by_category" :key="category.category" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ category.category }}</span>
                    <span class="text-sm font-medium">{{ category.count }}</span>
                  </div>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Features</h3>
                <div class="space-y-2">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Hazardous Materials</span>
                    <span class="text-sm font-medium">{{ summary.products?.hazardous || 0 }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Batch Trackable</span>
                    <span class="text-sm font-medium">{{ summary.products?.batch_trackable || 0 }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Serialization Capable</span>
                    <span class="text-sm font-medium">{{ summary.products?.serialization_capable || 0 }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GTIN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="product in products" :key="product.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                          <div class="text-sm text-gray-500">{{ product.sku }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.company?.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.category }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.gtin }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex space-x-1">
                        <span v-if="product.hazardous === 'yes'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                          Hazmat
                        </span>
                        <span v-if="product.batch_trackable" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                          Batch
                        </span>
                        <span v-if="product.serialization_capable" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                          Serial
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(product.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ product.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Employees Tab -->
          <div v-if="activeTab === 'employees'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Roles</h3>
                <div class="space-y-2">
                  <div v-for="role in summary.employees?.by_role" :key="role.role" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ role.role }}</span>
                    <span class="text-sm font-medium">{{ role.count }}</span>
                  </div>
                </div>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Departments</h3>
                <div class="space-y-2">
                  <div v-for="dept in summary.employees?.by_department" :key="dept.department" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ dept.department }}</span>
                    <span class="text-sm font-medium">{{ dept.count }}</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certifications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="employee in employees" :key="employee.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-orange-600">{{ employee.first_name.charAt(0) }}{{ employee.last_name.charAt(0) }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ employee.full_name }}</div>
                          <div class="text-sm text-gray-500">{{ employee.employee_id }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ employee.company?.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ employee.role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ employee.department }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div v-if="employee.certifications?.length" class="flex flex-wrap gap-1">
                        <span v-for="cert in employee.certifications.slice(0, 2)" :key="cert" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                          {{ cert }}
                        </span>
                        <span v-if="employee.certifications.length > 2" class="text-xs text-gray-500">
                          +{{ employee.certifications.length - 2 }} more
                        </span>
                      </div>
                      <span v-else class="text-sm text-gray-400">None</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(employee.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ employee.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Company Modal -->
    <div v-if="showCreateCompanyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Create New Company</h3>
            <button @click="showCreateCompanyModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createCompany" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Company Name</label>
              <input v-model="newCompany.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Company Code</label>
              <input v-model="newCompany.code" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Type</label>
              <select v-model="newCompany.type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                <option value="shipper">Shipper</option>
                <option value="carrier">Carrier</option>
                <option value="3pl">3PL</option>
                <option value="broker">Broker</option>
                <option value="customer">Customer</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Business Type</label>
              <input v-model="newCompany.business_type" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="newCompany.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreateCompanyModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Create Company
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Location Modal -->
    <div v-if="showCreateLocationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Create New Location</h3>
            <button @click="showCreateLocationModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createLocation" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Company</label>
              <select v-model="newLocation.company_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Company</option>
                <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Location Name</label>
              <input v-model="newLocation.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Location Code</label>
              <input v-model="newLocation.code" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Type</label>
              <select v-model="newLocation.type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                <option value="warehouse">Warehouse</option>
                <option value="distribution_center">Distribution Center</option>
                <option value="manufacturing_plant">Manufacturing Plant</option>
                <option value="retail_store">Retail Store</option>
                <option value="cross_dock">Cross Dock</option>
                <option value="customer_location">Customer Location</option>
                <option value="port">Port</option>
                <option value="airport">Airport</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Address</label>
              <input v-model="newLocation.address" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">City</label>
                <div class="relative" @click.stop>
                  <input v-model="citySearch" @input="searchCities" @blur="setTimeout(closeCitySearch, 200)" type="text" 
                         placeholder="Type to search cities..." required
                         class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                  <div v-if="citySearchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <div v-for="city in citySearchResults" :key="city.place_id" 
                         @click="selectCity(city)"
                         class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100">
                      <div class="font-medium">{{ city.display_name }}</div>
                      <div class="text-sm text-gray-500">{{ city.lat }}, {{ city.lon }}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">State</label>
                <input v-model="newLocation.state" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Country</label>
                <input v-model="newLocation.country" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                <input v-model="newLocation.postal_code" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Latitude</label>
                <input v-model="newLocation.latitude" type="number" step="any" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Longitude</label>
                <input v-model="newLocation.longitude" type="number" step="any" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Timezone</label>
              <select v-model="newLocation.timezone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Timezone</option>
                <option value="America/New_York">America/New_York (EST)</option>
                <option value="America/Chicago">America/Chicago (CST)</option>
                <option value="America/Denver">America/Denver (MST)</option>
                <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                <option value="Europe/London">Europe/London (GMT)</option>
                <option value="Europe/Paris">Europe/Paris (CET)</option>
                <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                <option value="Asia/Shanghai">Asia/Shanghai (CST)</option>
              </select>
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreateLocationModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Create Location
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Product Modal -->
    <div v-if="showCreateProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Create New Product</h3>
            <button @click="showCreateProductModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createProduct" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Company</label>
              <select v-model="newProduct.company_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Company</option>
                <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">SKU</label>
              <input v-model="newProduct.sku" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Product Name</label>
              <input v-model="newProduct.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description</label>
              <textarea v-model="newProduct.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Category</label>
              <input v-model="newProduct.category" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                <input v-model="newProduct.weight" type="number" step="0.001" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Volume (m³)</label>
                <input v-model="newProduct.volume" type="number" step="0.001" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreateProductModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Create Product
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

// Reactive data
const activeTab = ref('companies')
const summary = ref({})
const companies = ref([])
const locations = ref([])
const products = ref([])
const employees = ref([])

// Modal states
const showCreateCompanyModal = ref(false)
const showCreateLocationModal = ref(false)
const showCreateProductModal = ref(false)

// City search functionality
const citySearch = ref('')
const citySearchResults = ref([])
const searchTimeout = ref(null)

// Form data
const newCompany = ref({
  name: '',
  code: '',
  type: '',
  business_type: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  state: '',
  country: 'USA',
  postal_code: '',
  status: 'active'
})

const newLocation = ref({
  company_id: '',
  name: '',
  code: '',
  type: '',
  address: '',
  city: '',
  state: '',
  country: 'USA',
  postal_code: '',
  latitude: null,
  longitude: null,
  timezone: '',
  operating_hours: null,
  capabilities: null,
  status: 'active'
})

const newProduct = ref({
  company_id: '',
  sku: '',
  name: '',
  description: '',
  category: '',
  weight: null,
  volume: null,
  status: 'active'
})
const loading = ref(false)

// Tabs configuration
const tabs = [
  { id: 'companies', name: 'Companies' },
  { id: 'locations', name: 'Locations' },
  { id: 'products', name: 'Products' },
  { id: 'employees', name: 'Employees' }
]

// Methods
const loadSummary = async () => {
  try {
    const response = await axios.get('/api/v1/master-data/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadCompanies = async () => {
  try {
    const response = await axios.get('/api/v1/master-data/companies')
    companies.value = response.data.data
  } catch (error) {
    console.error('Error loading companies:', error)
  }
}

const loadLocations = async () => {
  try {
    const response = await axios.get('/api/v1/master-data/locations')
    locations.value = response.data.data
  } catch (error) {
    console.error('Error loading locations:', error)
  }
}

const loadProducts = async () => {
  try {
    const response = await axios.get('/api/v1/master-data/products')
    products.value = response.data.data
  } catch (error) {
    console.error('Error loading products:', error)
  }
}

const loadEmployees = async () => {
  try {
    const response = await axios.get('/api/v1/master-data/employees')
    employees.value = response.data.data
  } catch (error) {
    console.error('Error loading employees:', error)
  }
}

const createCompany = async () => {
  try {
    loading.value = true
    await axios.post('/api/v1/companies', newCompany.value)
    showCreateCompanyModal.value = false
    resetNewCompany()
    await refreshData()
  } catch (error) {
    console.error('Error creating company:', error)
    alert('Error creating company. Please try again.')
  } finally {
    loading.value = false
  }
}

const createLocation = async () => {
  try {
    loading.value = true
    await axios.post('/api/v1/locations', newLocation.value)
    showCreateLocationModal.value = false
    resetNewLocation()
    await refreshData()
  } catch (error) {
    console.error('Error creating location:', error)
    if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat().join(', ')
      alert(`Validation Error: ${errors}`)
    } else {
      alert('Error creating location. Please try again.')
    }
  } finally {
    loading.value = false
  }
}

const createProduct = async () => {
  try {
    loading.value = true
    await axios.post('/api/v1/products', newProduct.value)
    showCreateProductModal.value = false
    resetNewProduct()
    await refreshData()
  } catch (error) {
    console.error('Error creating product:', error)
    alert('Error creating product. Please try again.')
  } finally {
    loading.value = false
  }
}

const resetNewCompany = () => {
  newCompany.value = {
    name: '',
    code: '',
    type: '',
    business_type: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    country: 'USA',
    postal_code: '',
    status: 'active'
  }
}

const resetNewLocation = () => {
  newLocation.value = {
    company_id: '',
    name: '',
    code: '',
    type: '',
    address: '',
    city: '',
    state: '',
    country: 'USA',
    postal_code: '',
    latitude: null,
    longitude: null,
    timezone: '',
    operating_hours: null,
    capabilities: null,
    status: 'active'
  }
  citySearch.value = ''
  citySearchResults.value = []
}

const resetNewProduct = () => {
  newProduct.value = {
    company_id: '',
    sku: '',
    name: '',
    description: '',
    category: '',
    weight: null,
    volume: null,
    status: 'active'
  }
}

// City search methods
const searchCities = async () => {
  if (citySearch.value.length < 3) {
    citySearchResults.value = []
    return
  }

  clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(async () => {
    try {
      const response = await axios.get('/api/v1/cities/search', {
        params: {
          q: citySearch.value,
          limit: 5
        }
      })
      
      if (response.data.success) {
        citySearchResults.value = response.data.data
      } else {
        throw new Error(response.data.message || 'Failed to search cities')
      }
    } catch (error) {
      console.error('Error searching cities:', error)
      citySearchResults.value = []
      // Show user-friendly error message
      alert('Unable to search cities. Please check your internet connection and try again.')
    }
  }, 300)
}

const selectCity = (city) => {
  citySearch.value = city.display_name
  citySearchResults.value = []
  
  // Parse address components
  const address = city.address || {}
  
  // Extract city name from multiple sources
  let cityName = address.city || address.town || address.village || city.name || ''
  if (!cityName && city.display_name) {
    // Try to extract city from display_name (format: "City, State, Country")
    const parts = city.display_name.split(',')
    if (parts.length > 0) {
      cityName = parts[0].trim()
    }
  }
  
  // Ensure we have a city name
  if (!cityName) {
    cityName = 'Unknown City'
  }
  
  newLocation.value.city = cityName
  newLocation.value.state = address.state || ''
  newLocation.value.country = address.country || 'USA'
  newLocation.value.postal_code = address.postcode || ''
  newLocation.value.latitude = city.lat
  newLocation.value.longitude = city.lon
  
  console.log('Selected city:', {
    cityName,
    address,
    display_name: city.display_name,
    name: city.name
  })
}

const closeCitySearch = () => {
  citySearchResults.value = []
}

const refreshData = async () => {
  loading.value = true
  try {
    await Promise.all([
      loadSummary(),
      loadCompanies(),
      loadLocations(),
      loadProducts(),
      loadEmployees()
    ])
  } catch (error) {
    console.error('Error refreshing data:', error)
  } finally {
    loading.value = false
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'active':
      return 'bg-green-100 text-green-800'
    case 'inactive':
      return 'bg-gray-100 text-gray-800'
    case 'suspended':
      return 'bg-yellow-100 text-yellow-800'
    case 'terminated':
      return 'bg-red-100 text-red-800'
    case 'maintenance':
      return 'bg-orange-100 text-orange-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const formatNumber = (number) => {
  if (!number) return '0'
  return new Intl.NumberFormat().format(number)
}

// Lifecycle
onMounted(() => {
  refreshData()
})
</script>
