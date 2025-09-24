<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Inventory & Warehouse Management</h1>
            <p class="mt-2 text-gray-600">Monitor inventory levels, warehouse operations, and logistics</p>
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
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Products</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.inventory?.total_products || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Low Stock</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.inventory?.low_stock_items || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Warehouse Bins</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.warehouse?.total_bins || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Receipts</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.receiving?.total_receipts || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Shipments</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.shipping?.total_shipments || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                activeTab === tab.id
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              {{ tab.name }}
              <span v-if="tab.count !== undefined" class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                {{ tab.count }}
              </span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Inventory Tab -->
          <div v-if="activeTab === 'inventory'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Inventory Balances</h3>
              <div class="flex space-x-3">
                <select v-model="inventoryFilters.status" @change="loadInventoryBalances" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="quarantined">Quarantined</option>
                  <option value="reserved">Reserved</option>
                </select>
                <button @click="inventoryFilters.low_stock = !inventoryFilters.low_stock; loadInventoryBalances()"
                        :class="[
                          inventoryFilters.low_stock 
                            ? 'bg-red-100 text-red-800' 
                            : 'bg-gray-100 text-gray-800',
                          'px-3 py-2 text-sm font-medium rounded-md'
                        ]">
                  Low Stock
                </button>
                <input v-model="inventoryFilters.search" @input="loadInventoryBalances" 
                       type="text" placeholder="Search inventory..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lot/Bin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Hand</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="inventory in inventoryBalances.data" :key="inventory.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ inventory.product?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inventory.location?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inventory.lot_bin || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inventory.quantity_on_hand }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inventory.quantity_available }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inventory.quantity_allocated }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getInventoryStatusClass(inventory.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ inventory.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ${{ formatCurrency(inventory.total_value) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Warehouse Bins Tab -->
          <div v-if="activeTab === 'bins'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Warehouse Bins</h3>
              <div class="flex space-x-3">
                <select v-model="binFilters.bin_type" @change="loadWarehouseBins" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Types</option>
                  <option value="storage">Storage</option>
                  <option value="picking">Picking</option>
                  <option value="receiving">Receiving</option>
                  <option value="shipping">Shipping</option>
                  <option value="cross_dock">Cross Dock</option>
                  <option value="quarantine">Quarantine</option>
                </select>
                <input v-model="binFilters.search" @input="loadWarehouseBins" 
                       type="text" placeholder="Search bins..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="bin in warehouseBins.data" :key="bin.id" class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-lg font-medium text-gray-900">{{ bin.bin_name }}</h4>
                  <span :class="getBinStatusClass(bin.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ bin.status }}
                  </span>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                  <div><span class="font-medium">Type:</span> {{ bin.bin_type }}</div>
                  <div><span class="font-medium">Zone:</span> {{ bin.zone }}</div>
                  <div><span class="font-medium">Aisle:</span> {{ bin.aisle }}</div>
                  <div><span class="font-medium">Capacity:</span> {{ bin.capacity_volume }}m³ / {{ bin.capacity_weight }}kg</div>
                  <div><span class="font-medium">Utilization:</span> {{ bin.utilization_percentage }}%</div>
                  <div><span class="font-medium">Location:</span> {{ bin.location?.name || 'N/A' }}</div>
                </div>
                <div class="mt-4">
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" :style="{ width: bin.utilization_percentage + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Inbound Receiving Tab -->
          <div v-if="activeTab === 'receiving'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Inbound Receiving</h3>
              <div class="flex space-x-3">
                <select v-model="receivingFilters.status" @change="loadInboundReceiving" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="expected">Expected</option>
                  <option value="arrived">Arrived</option>
                  <option value="unloading">Unloading</option>
                  <option value="unloaded">Unloaded</option>
                  <option value="qc_pending">QC Pending</option>
                  <option value="qc_completed">QC Completed</option>
                  <option value="putaway_pending">Putaway Pending</option>
                  <option value="completed">Completed</option>
                </select>
                <input v-model="receivingFilters.search" @input="loadInboundReceiving" 
                       type="text" placeholder="Search receiving..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receiving ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ASN Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Arrival</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packages</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="receiving in inboundReceiving.data" :key="receiving.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ receiving.receiving_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ receiving.asn_number || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ receiving.location?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ receiving.carrier?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getReceivingStatusClass(receiving.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ receiving.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDateTime(receiving.expected_arrival_time) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ receiving.total_packages || 'N/A' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Outbound Shipments Tab -->
          <div v-if="activeTab === 'shipments'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Outbound Shipments</h3>
              <div class="flex space-x-3">
                <select v-model="shipmentFilters.status" @change="loadOutboundShipments" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="pending">Pending</option>
                  <option value="picking">Picking</option>
                  <option value="picked">Picked</option>
                  <option value="packing">Packing</option>
                  <option value="packed">Packed</option>
                  <option value="shipped">Shipped</option>
                  <option value="delivered">Delivered</option>
                  <option value="exception">Exception</option>
                </select>
                <input v-model="shipmentFilters.search" @input="loadOutboundShipments" 
                       type="text" placeholder="Search shipments..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Ship</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="shipment in outboundShipments.data" :key="shipment.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ shipment.shipment_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ shipment.ship_from_location?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ shipment.ship_to_location?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ shipment.carrier?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getShipmentStatusClass(shipment.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ shipment.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDateTime(shipment.scheduled_ship_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ shipment.tracking_number || 'N/A' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Cycle Counts Tab -->
          <div v-if="activeTab === 'counts'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Cycle Counts</h3>
              <div class="flex space-x-3">
                <select v-model="countFilters.count_status" @change="loadCycleCounts" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="scheduled">Scheduled</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="disputed">Disputed</option>
                  <option value="approved">Approved</option>
                </select>
                <button @click="countFilters.has_discrepancy = !countFilters.has_discrepancy; loadCycleCounts()"
                        :class="[
                          countFilters.has_discrepancy 
                            ? 'bg-red-100 text-red-800' 
                            : 'bg-gray-100 text-gray-800',
                          'px-3 py-2 text-sm font-medium rounded-md'
                        ]">
                  Discrepancies
                </button>
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">System Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Counted Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discrepancy</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Counter</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="count in cycleCounts.data" :key="count.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ count.count_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ count.product?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ count.location?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ count.system_quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ count.counted_quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="count.discrepancy > 0 ? 'text-green-600' : count.discrepancy < 0 ? 'text-red-600' : 'text-gray-600'">
                        {{ count.discrepancy }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getCountStatusClass(count.count_status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ count.count_status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ count.counter?.name || 'N/A' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
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
const inventoryBalances = ref({ data: [] })
const warehouseBins = ref({ data: [] })
const inboundReceiving = ref({ data: [] })
const outboundShipments = ref({ data: [] })
const cycleCounts = ref({ data: [] })
const loading = ref(false)

const activeTab = ref('inventory')

// Filter states
const inventoryFilters = ref({
  status: '',
  low_stock: false,
  search: ''
})

const binFilters = ref({
  bin_type: '',
  search: ''
})

const receivingFilters = ref({
  status: '',
  search: ''
})

const shipmentFilters = ref({
  status: '',
  search: ''
})

const countFilters = ref({
  count_status: '',
  has_discrepancy: false
})

// Computed properties
const tabs = computed(() => [
  { id: 'inventory', name: 'Inventory', count: summary.value.inventory?.total_products },
  { id: 'bins', name: 'Warehouse Bins', count: summary.value.warehouse?.total_bins },
  { id: 'receiving', name: 'Inbound Receiving', count: summary.value.receiving?.total_receipts },
  { id: 'shipments', name: 'Outbound Shipments', count: summary.value.shipping?.total_shipments },
  { id: 'counts', name: 'Cycle Counts', count: summary.value.quality?.total_counts }
])

// Methods
const loadSummary = async () => {
  try {
    const response = await axios.get('/api/v1/inventory-warehouse/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadInventoryBalances = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...inventoryFilters.value
    }
    
    const response = await axios.get('/api/v1/inventory-warehouse/inventory-balances', { params })
    inventoryBalances.value = response.data.data
  } catch (error) {
    console.error('Error loading inventory balances:', error)
  } finally {
    loading.value = false
  }
}

const loadWarehouseBins = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...binFilters.value
    }
    
    const response = await axios.get('/api/v1/inventory-warehouse/warehouse-bins', { params })
    warehouseBins.value = response.data.data
  } catch (error) {
    console.error('Error loading warehouse bins:', error)
  } finally {
    loading.value = false
  }
}

const loadInboundReceiving = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...receivingFilters.value
    }
    
    const response = await axios.get('/api/v1/inventory-warehouse/inbound-receiving', { params })
    inboundReceiving.value = response.data.data
  } catch (error) {
    console.error('Error loading inbound receiving:', error)
  } finally {
    loading.value = false
  }
}

const loadOutboundShipments = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...shipmentFilters.value
    }
    
    const response = await axios.get('/api/v1/inventory-warehouse/outbound-shipments', { params })
    outboundShipments.value = response.data.data
  } catch (error) {
    console.error('Error loading outbound shipments:', error)
  } finally {
    loading.value = false
  }
}

const loadCycleCounts = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...countFilters.value
    }
    
    const response = await axios.get('/api/v1/inventory-warehouse/cycle-counts', { params })
    cycleCounts.value = response.data.data
  } catch (error) {
    console.error('Error loading cycle counts:', error)
  } finally {
    loading.value = false
  }
}

// Utility functions
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount || 0)
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString()
}

const getInventoryStatusClass = (status) => {
  const classMap = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'quarantined': 'bg-red-100 text-red-800',
    'reserved': 'bg-yellow-100 text-yellow-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getBinStatusClass = (status) => {
  const classMap = {
    'active': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800',
    'maintenance': 'bg-yellow-100 text-yellow-800',
    'reserved': 'bg-blue-100 text-blue-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getReceivingStatusClass = (status) => {
  const classMap = {
    'expected': 'bg-blue-100 text-blue-800',
    'arrived': 'bg-yellow-100 text-yellow-800',
    'unloading': 'bg-orange-100 text-orange-800',
    'unloaded': 'bg-purple-100 text-purple-800',
    'qc_pending': 'bg-red-100 text-red-800',
    'qc_completed': 'bg-green-100 text-green-800',
    'putaway_pending': 'bg-indigo-100 text-indigo-800',
    'completed': 'bg-green-100 text-green-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getShipmentStatusClass = (status) => {
  const classMap = {
    'pending': 'bg-gray-100 text-gray-800',
    'picking': 'bg-blue-100 text-blue-800',
    'picked': 'bg-yellow-100 text-yellow-800',
    'packing': 'bg-orange-100 text-orange-800',
    'packed': 'bg-purple-100 text-purple-800',
    'shipped': 'bg-green-100 text-green-800',
    'delivered': 'bg-green-100 text-green-800',
    'exception': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getCountStatusClass = (status) => {
  const classMap = {
    'scheduled': 'bg-blue-100 text-blue-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'disputed': 'bg-red-100 text-red-800',
    'approved': 'bg-green-100 text-green-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

// Watch for tab changes
watch(activeTab, (newTab) => {
  if (newTab === 'inventory') loadInventoryBalances()
  if (newTab === 'bins') loadWarehouseBins()
  if (newTab === 'receiving') loadInboundReceiving()
  if (newTab === 'shipments') loadOutboundShipments()
  if (newTab === 'counts') loadCycleCounts()
})

// Initialize data
onMounted(() => {
  loadSummary()
  loadInventoryBalances()
})
</script>
