<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Manufacturing Management</h1>
            <p class="mt-2 text-gray-600">Monitor production, quality, and machine performance</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="generateWorkOrders"
              :disabled="isGenerating"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="isGenerating" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
              </svg>
              {{ isGenerating ? 'Generating...' : 'Generate Work Orders' }}
            </button>
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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Work Orders</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.work_orders?.total || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Machines</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.machines?.total || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Quality Inspections</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.quality?.total_inspections || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Active Routes</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.production?.active_routes || 0 }}</dd>
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
          <!-- Work Orders Tab -->
          <div v-if="activeTab === 'work-orders'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Work Orders</h3>
              <div class="flex space-x-3">
                <select v-model="workOrderFilters.status" @change="loadWorkOrders" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="planned">Planned</option>
                  <option value="released">Released</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="on_hold">On Hold</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <input v-model="workOrderFilters.search" @input="loadWorkOrders" 
                       type="text" placeholder="Search work orders..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="workOrder in workOrders.data" :key="workOrder.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ workOrder.work_order_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ workOrder.product?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getWorkOrderStatusClass(workOrder.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ workOrder.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span :class="getPriorityClass(workOrder.priority)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ workOrder.priority }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ workOrder.quantity_produced }}/{{ workOrder.quantity_planned }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDateTime(workOrder.planned_start_time) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="viewWorkOrder(workOrder.id)" 
                              class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                      <button @click="editWorkOrder(workOrder.id)" 
                              class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Showing {{ workOrders.from || 0 }} to {{ workOrders.to || 0 }} of {{ workOrders.total || 0 }} results
              </div>
              <div class="flex space-x-2">
                <button v-for="page in visibleWorkOrderPages" :key="page" 
                        @click="loadWorkOrders(page)"
                        :class="[
                          page === workOrders.current_page
                            ? 'bg-blue-600 text-white'
                            : 'bg-white text-gray-700 hover:bg-gray-50',
                          'px-3 py-2 border border-gray-300 rounded-md text-sm font-medium'
                        ]">
                  {{ page }}
                </button>
              </div>
            </div>
          </div>

          <!-- Machines Tab -->
          <div v-if="activeTab === 'machines'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Machines</h3>
              <div class="flex space-x-3">
                <select v-model="machineFilters.status" @change="loadMachines" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="active">Active</option>
                  <option value="maintenance">Maintenance</option>
                  <option value="down">Down</option>
                  <option value="retired">Retired</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="machine in machines.data" :key="machine.id" class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-lg font-medium text-gray-900">{{ machine.machine_name }}</h4>
                  <span :class="getMachineStatusClass(machine.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ machine.status }}
                  </span>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                  <div><span class="font-medium">Type:</span> {{ machine.machine_type }}</div>
                  <div><span class="font-medium">Manufacturer:</span> {{ machine.manufacturer }}</div>
                  <div><span class="font-medium">Model:</span> {{ machine.model }}</div>
                  <div><span class="font-medium">Capacity:</span> {{ machine.capacity_per_hour }}/hr</div>
                  <div><span class="font-medium">Efficiency:</span> {{ machine.efficiency_rating }}%</div>
                  <div><span class="font-medium">Location:</span> {{ machine.location?.name || 'N/A' }}</div>
                </div>
                <div class="mt-4 flex space-x-2">
                  <button @click="viewMachine(machine.id)" 
                          class="text-blue-600 hover:text-blue-900 text-sm">View Details</button>
                  <button @click="viewMachineTelemetry(machine.id)" 
                          class="text-green-600 hover:text-green-900 text-sm">Telemetry</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Quality Tab -->
          <div v-if="activeTab === 'quality'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Quality Inspections</h3>
              <div class="flex space-x-3">
                <select v-model="qualityFilters.inspection_result" @change="loadQualityInspections" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Results</option>
                  <option value="pass">Pass</option>
                  <option value="fail">Fail</option>
                  <option value="conditional_pass">Conditional Pass</option>
                </select>
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspection ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspector</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="inspection in qualityInspections.data" :key="inspection.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ inspection.inspection_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inspection.work_order?.work_order_id || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inspection.inspection_type }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getInspectionResultClass(inspection.inspection_result)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ inspection.inspection_result }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ inspection.inspector?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDateTime(inspection.inspection_timestamp) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Batch Tracking Tab -->
          <div v-if="activeTab === 'batches'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Batch Tracking</h3>
              <div class="flex space-x-3">
                <select v-model="batchFilters.quality_status" @change="loadBatchTracking" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="pending">Pending</option>
                  <option value="passed">Passed</option>
                  <option value="failed">Failed</option>
                  <option value="quarantined">Quarantined</option>
                </select>
                <input v-model="batchFilters.search" @input="loadBatchTracking" 
                       type="text" placeholder="Search batches..."
                       class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Production Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="batch in batchTracking.data" :key="batch.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ batch.batch_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ batch.product?.name || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ batch.batch_quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getQualityStatusClass(batch.quality_status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ batch.quality_status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDate(batch.production_date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ batch.expiry_date ? formatDate(batch.expiry_date) : 'N/A' }}
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
const workOrders = ref({ data: [] })
const machines = ref({ data: [] })
const qualityInspections = ref({ data: [] })
const batchTracking = ref({ data: [] })
const loading = ref(false)
const isGenerating = ref(false)

const activeTab = ref('work-orders')

// Filter states
const workOrderFilters = ref({
  status: '',
  search: ''
})

const machineFilters = ref({
  status: ''
})

const qualityFilters = ref({
  inspection_result: ''
})

const batchFilters = ref({
  quality_status: '',
  search: ''
})

// Computed properties
const tabs = computed(() => [
  { id: 'work-orders', name: 'Work Orders', count: summary.value.work_orders?.total },
  { id: 'machines', name: 'Machines', count: summary.value.machines?.total },
  { id: 'quality', name: 'Quality', count: summary.value.quality?.total_inspections },
  { id: 'batches', name: 'Batches', count: summary.value.quality?.pending_batches }
])

const visibleWorkOrderPages = computed(() => {
  if (!workOrders.value.last_page) return []
  const current = workOrders.value.current_page
  const last = workOrders.value.last_page
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
    const response = await axios.get('/api/v1/manufacturing/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadWorkOrders = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...workOrderFilters.value
    }
    
    const response = await axios.get('/api/v1/manufacturing/work-orders', { params })
    workOrders.value = response.data.data
  } catch (error) {
    console.error('Error loading work orders:', error)
  } finally {
    loading.value = false
  }
}

const loadMachines = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...machineFilters.value
    }
    
    const response = await axios.get('/api/v1/manufacturing/machines', { params })
    machines.value = response.data.data
  } catch (error) {
    console.error('Error loading machines:', error)
  } finally {
    loading.value = false
  }
}

const loadQualityInspections = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...qualityFilters.value
    }
    
    const response = await axios.get('/api/v1/manufacturing/quality-inspections', { params })
    qualityInspections.value = response.data.data
  } catch (error) {
    console.error('Error loading quality inspections:', error)
  } finally {
    loading.value = false
  }
}

const loadBatchTracking = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...batchFilters.value
    }
    
    const response = await axios.get('/api/v1/manufacturing/batch-tracking', { params })
    batchTracking.value = response.data.data
  } catch (error) {
    console.error('Error loading batch tracking:', error)
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  loadSummary()
  if (activeTab.value === 'work-orders') loadWorkOrders()
  if (activeTab.value === 'machines') loadMachines()
  if (activeTab.value === 'quality') loadQualityInspections()
  if (activeTab.value === 'batches') loadBatchTracking()
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString()
}

const getWorkOrderStatusClass = (status) => {
  const classMap = {
    'planned': 'bg-gray-100 text-gray-800',
    'released': 'bg-blue-100 text-blue-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'on_hold': 'bg-orange-100 text-orange-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getMachineStatusClass = (status) => {
  const classMap = {
    'active': 'bg-green-100 text-green-800',
    'maintenance': 'bg-yellow-100 text-yellow-800',
    'down': 'bg-red-100 text-red-800',
    'retired': 'bg-gray-100 text-gray-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getInspectionResultClass = (result) => {
  const classMap = {
    'pass': 'bg-green-100 text-green-800',
    'fail': 'bg-red-100 text-red-800',
    'conditional_pass': 'bg-yellow-100 text-yellow-800'
  }
  return classMap[result] || 'bg-gray-100 text-gray-800'
}

const getQualityStatusClass = (status) => {
  const classMap = {
    'pending': 'bg-gray-100 text-gray-800',
    'passed': 'bg-green-100 text-green-800',
    'failed': 'bg-red-100 text-red-800',
    'quarantined': 'bg-orange-100 text-orange-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getPriorityClass = (priority) => {
  const classMap = {
    1: 'bg-red-100 text-red-800',
    2: 'bg-orange-100 text-orange-800',
    3: 'bg-yellow-100 text-yellow-800',
    4: 'bg-blue-100 text-blue-800',
    5: 'bg-gray-100 text-gray-800'
  }
  return classMap[priority] || 'bg-gray-100 text-gray-800'
}

// Placeholder functions for actions
const viewWorkOrder = (id) => {
  console.log('View work order:', id)
}

const editWorkOrder = (id) => {
  console.log('Edit work order:', id)
}

const viewMachine = (id) => {
  console.log('View machine:', id)
}

const viewMachineTelemetry = (id) => {
  console.log('View machine telemetry:', id)
}

// Generate work orders function
const generateWorkOrders = async () => {
  try {
    isGenerating.value = true
    
    // Try to generate from purchase orders first
    const response = await axios.post('/api/v1/manufacturing/generate-work-orders/from-purchase-orders')
    
    if (response.data.success) {
      const count = response.data.data.work_orders_count
      if (count > 0) {
        alert(`Successfully generated ${count} work orders from purchase orders!`)
        // Reload data
        loadSummary()
        loadWorkOrders()
      } else {
        // If no work orders from purchase orders, generate for products with BOMs
        const productResponse = await axios.post('/api/v1/manufacturing/generate-work-orders/for-products', {
          product_ids: [1], // Use product ID 1 which has a BOM
          quantity: 100
        })
        
        if (productResponse.data.success) {
          const count = productResponse.data.data.work_orders_count
          alert(`Successfully generated ${count} work orders for products!`)
          // Reload data
          loadSummary()
          loadWorkOrders()
        } else {
          alert('Failed to generate work orders: ' + productResponse.data.message)
        }
      }
    } else {
      alert('Failed to generate work orders: ' + response.data.message)
    }
  } catch (error) {
    console.error('Error generating work orders:', error)
    alert('Error generating work orders: ' + (error.response?.data?.message || error.message))
  } finally {
    isGenerating.value = false
  }
}

// Watch for tab changes
watch(activeTab, (newTab) => {
  if (newTab === 'work-orders') loadWorkOrders()
  if (newTab === 'machines') loadMachines()
  if (newTab === 'quality') loadQualityInspections()
  if (newTab === 'batches') loadBatchTracking()
})

// Initialize data
onMounted(() => {
  loadSummary()
  loadWorkOrders()
})
</script>
