<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier & Procurement Management</h1>
            <p class="mt-1 text-sm text-gray-600">Manage suppliers, contracts, and purchase orders</p>
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
              @click="showCreateSupplierModal = true"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Add Supplier
            </button>
            <button
              @click="showCreatePOModal = true"
              class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Create Purchase Order
            </button>
            <button
              @click="showCreateContractModal = true"
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium"
            >
              Create Contract
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
                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Approved Suppliers</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.suppliers?.total_onboarded || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Active Contracts</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.suppliers?.active_contracts || 0 }}</dd>
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
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Purchase Orders</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.purchase_orders?.total_orders || 0 }}</dd>
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
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Avg Performance</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ formatScore(summary.performance?.average_score) }}</dd>
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
          <!-- Suppliers Tab -->
          <div v-if="activeTab === 'suppliers'" class="space-y-6">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Onboarding Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Items</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="supplier in suppliers" :key="supplier.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-green-600">{{ supplier.company?.name?.charAt(0) }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ supplier.company?.name }}</div>
                          <div class="text-sm text-gray-500">{{ supplier.company?.business_type }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(supplier.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ supplier.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(supplier.onboarding_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div v-if="supplier.supplier_performance_scores">
                        Quality: {{ supplier.supplier_performance_scores.quality }}/10
                      </div>
                      <span v-else class="text-gray-400">N/A</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ supplier.approved_items?.length || 0 }} items
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Purchase Orders Tab -->
          <div v-if="activeTab === 'purchase-orders'" class="space-y-6">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="po in purchaseOrders" :key="po.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ po.po_number }}</div>
                      <div class="text-sm text-gray-500">{{ po.po_type }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ po.supplier_company?.name }}</div>
                      <div class="text-sm text-gray-500">{{ po.supplier_company?.code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(po.order_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(po.required_delivery_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatCurrency(po.total_amount, po.currency) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(po.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ po.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Contracts Tab -->
          <div v-if="activeTab === 'contracts'" class="space-y-6">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="contract in contracts" :key="contract.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ contract.contract_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ contract.company?.name }}</div>
                      <div class="text-sm text-gray-500">{{ contract.company?.code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ contract.contract_type }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(contract.start_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(contract.end_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatCurrency(contract.total_contract_value, contract.currency) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(contract.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ contract.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Performance Tab -->
          <div v-if="activeTab === 'performance'" class="space-y-6">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On-Time Delivery</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality Reject Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fill Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall Score</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="perf in performance" :key="perf.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-orange-600">{{ perf.company?.name?.charAt(0) }}</span>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ perf.company?.name }}</div>
                          <div class="text-sm text-gray-500">{{ perf.period_type }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(perf.performance_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ perf.on_time_delivery_pct }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ perf.quality_reject_rate }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ perf.fill_rate }}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getScoreClass(perf.overall_score)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ perf.overall_score }}/10
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

    <!-- Create Supplier Modal -->
    <div v-if="showCreateSupplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Add New Supplier</h3>
            <button @click="showCreateSupplierModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createSupplier" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Company Name</label>
              <input v-model="newSupplier.name" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Company Code</label>
              <input v-model="newSupplier.code" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Business Type</label>
              <select v-model="newSupplier.business_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                <option value="Manufacturer">Manufacturer</option>
                <option value="Distributor">Distributor</option>
                <option value="Supplier">Supplier</option>
                <option value="Service Provider">Service Provider</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="newSupplier.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone</label>
              <input v-model="newSupplier.phone" type="tel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreateSupplierModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Add Supplier
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Purchase Order Modal -->
    <div v-if="showCreatePOModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Create Purchase Order</h3>
            <button @click="showCreatePOModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createPurchaseOrder" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">PO Number</label>
              <input v-model="newPO.po_number" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Supplier</label>
              <select v-model="newPO.supplier_company_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Supplier</option>
                <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Order Date</label>
              <input v-model="newPO.order_date" type="date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Required Delivery Date</label>
              <input v-model="newPO.required_delivery_date" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Payment Terms</label>
              <select v-model="newPO.payment_terms" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Terms</option>
                <option value="NET15">NET 15</option>
                <option value="NET30">NET 30</option>
                <option value="NET45">NET 45</option>
                <option value="NET60">NET 60</option>
              </select>
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreatePOModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Create PO
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Contract Modal -->
    <div v-if="showCreateContractModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Create Contract</h3>
            <button @click="showCreateContractModal = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <form @submit.prevent="createContract" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Contract Number</label>
              <input v-model="newContract.contract_number" type="text" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Supplier</label>
              <select v-model="newContract.company_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Supplier</option>
                <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Contract Type</label>
              <select v-model="newContract.contract_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                <option value="standard">Standard</option>
                <option value="blanket">Blanket</option>
                <option value="master_agreement">Master Agreement</option>
                <option value="framework">Framework</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input v-model="newContract.start_date" type="date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input v-model="newContract.end_date" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total Contract Value</label>
              <input v-model="newContract.total_contract_value" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
              <button type="button" @click="showCreateContractModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                Cancel
              </button>
              <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Create Contract
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive data
const activeTab = ref('suppliers')
const summary = ref({})
const suppliers = ref([])
const purchaseOrders = ref([])
const contracts = ref([])
const performance = ref([])
const companies = ref([])

// Modal states
const showCreateSupplierModal = ref(false)
const showCreatePOModal = ref(false)
const showCreateContractModal = ref(false)

// Form data
const newSupplier = ref({
  name: '',
  code: '',
  business_type: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  state: '',
  country: 'USA',
  type: 'shipper',
  status: 'active'
})

const newPO = ref({
  po_number: '',
  po_type: 'standard',
  supplier_company_id: '',
  order_date: '',
  required_delivery_date: '',
  currency: 'USD',
  payment_terms: 'NET30',
  status: 'draft'
})

const newContract = ref({
  contract_number: '',
  company_id: '',
  start_date: '',
  end_date: '',
  contract_type: 'standard',
  total_contract_value: '',
  currency: 'USD',
  status: 'draft'
})

// Tabs configuration
const tabs = [
  { id: 'suppliers', name: 'Suppliers' },
  { id: 'purchase-orders', name: 'Purchase Orders' },
  { id: 'contracts', name: 'Contracts' },
  { id: 'performance', name: 'Performance' }
]

// Methods
const loadSummary = async () => {
  try {
    const response = await axios.get('/api/v1/supplier-procurement/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadSuppliers = async () => {
  try {
    const response = await axios.get('/api/v1/supplier-procurement/onboarding')
    suppliers.value = response.data.data
  } catch (error) {
    console.error('Error loading suppliers:', error)
  }
}

const loadPurchaseOrders = async () => {
  try {
    const response = await axios.get('/api/v1/supplier-procurement/purchase-orders')
    purchaseOrders.value = response.data.data
  } catch (error) {
    console.error('Error loading purchase orders:', error)
  }
}

const loadContracts = async () => {
  try {
    const response = await axios.get('/api/v1/supplier-procurement/contracts')
    contracts.value = response.data.data
  } catch (error) {
    console.error('Error loading contracts:', error)
  }
}

const loadPerformance = async () => {
  try {
    const response = await axios.get('/api/v1/supplier-procurement/performance')
    performance.value = response.data.data
  } catch (error) {
    console.error('Error loading performance:', error)
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

const createSupplier = async () => {
  try {
    await axios.post('/api/v1/companies', newSupplier.value)
    showCreateSupplierModal.value = false
    resetNewSupplier()
    await loadCompanies()
    await refreshData()
  } catch (error) {
    console.error('Error creating supplier:', error)
    alert('Error creating supplier. Please try again.')
  }
}

const createPurchaseOrder = async () => {
  try {
    // Get the buyer company (assume first company is buyer)
    const buyerCompany = companies.value.find(c => c.type === 'shipper') || companies.value[0]
    if (!buyerCompany) {
      alert('No buyer company found. Please create a company first.')
      return
    }

    const poData = {
      ...newPO.value,
      buyer_company_id: buyerCompany.id,
      created_by_user_id: 1, // Assume user ID 1
      approved_by_user_id: 1
    }

    await axios.post('/api/v1/purchase-orders', poData)
    showCreatePOModal.value = false
    resetNewPO()
    await refreshData()
  } catch (error) {
    console.error('Error creating purchase order:', error)
    alert('Error creating purchase order. Please try again.')
  }
}

const createContract = async () => {
  try {
    // Get the buyer company (assume first company is buyer)
    const buyerCompany = companies.value.find(c => c.type === 'shipper') || companies.value[0]
    if (!buyerCompany) {
      alert('No buyer company found. Please create a company first.')
      return
    }

    const contractData = {
      ...newContract.value,
      buyer_company_id: buyerCompany.id,
      contract_manager_id: 1 // Assume user ID 1
    }

    await axios.post('/api/v1/supplier-contracts', contractData)
    showCreateContractModal.value = false
    resetNewContract()
    await refreshData()
  } catch (error) {
    console.error('Error creating contract:', error)
    alert('Error creating contract. Please try again.')
  }
}

const resetNewSupplier = () => {
  newSupplier.value = {
    name: '',
    code: '',
    business_type: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    country: 'USA',
    type: 'shipper',
    status: 'active'
  }
}

const resetNewPO = () => {
  newPO.value = {
    po_number: '',
    po_type: 'standard',
    supplier_company_id: '',
    order_date: '',
    required_delivery_date: '',
    currency: 'USD',
    payment_terms: 'NET30',
    status: 'draft'
  }
}

const resetNewContract = () => {
  newContract.value = {
    contract_number: '',
    company_id: '',
    start_date: '',
    end_date: '',
    contract_type: 'standard',
    total_contract_value: '',
    currency: 'USD',
    status: 'draft'
  }
}

const refreshData = async () => {
  try {
    await Promise.all([
      loadSummary(),
      loadSuppliers(),
      loadPurchaseOrders(),
      loadContracts(),
      loadPerformance()
    ])
  } catch (error) {
    console.error('Error refreshing data:', error)
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'approved':
    case 'active':
    case 'confirmed':
      return 'bg-green-100 text-green-800'
    case 'pending':
    case 'open':
      return 'bg-yellow-100 text-yellow-800'
    case 'rejected':
    case 'cancelled':
    case 'terminated':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getScoreClass = (score) => {
  if (score >= 8) return 'bg-green-100 text-green-800'
  if (score >= 6) return 'bg-yellow-100 text-yellow-800'
  return 'bg-red-100 text-red-800'
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString()
}

const formatCurrency = (amount, currency = 'USD') => {
  if (!amount) return 'N/A'
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency
  }).format(amount)
}

const formatScore = (score) => {
  if (!score) return 'N/A'
  return (score / 10).toFixed(1)
}

// Lifecycle
onMounted(() => {
  loadCompanies()
  refreshData()
})
</script>
