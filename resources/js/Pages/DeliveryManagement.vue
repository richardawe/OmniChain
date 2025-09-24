<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Delivery Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage order fulfillment, delivery tasks, and customer notifications</p>
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

    <!-- Summary Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.order_fulfillments?.total || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Delivered</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.order_fulfillments?.delivered || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.delivery_tasks?.in_progress || 0 }}</dd>
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
                  <dt class="text-sm font-medium text-gray-500 truncate">Exceptions</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ summary.delivery_exceptions?.open || 0 }}</dd>
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
          <!-- Freight Orders Tab (Integrated from Dashboard) -->
          <div v-if="activeTab === 'orders'" class="space-y-6">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-gray-900">Freight Orders</h3>
              <div class="flex space-x-3">
                <select v-model="freightOrderFilters.status" @change="loadFreightOrders" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="draft">Draft</option>
                  <option value="booked">Booked</option>
                  <option value="picked_up">Picked Up</option>
                  <option value="in_transit">In Transit</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <select v-model="freightOrderFilters.service_type" @change="loadFreightOrders"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Services</option>
                  <option value="standard">Standard</option>
                  <option value="express">Express</option>
                  <option value="overnight">Overnight</option>
                  <option value="scheduled">Scheduled</option>
                </select>
                <button @click="showOrderForm = true" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  Create Order
                </button>
              </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow-sm border">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrier</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Route</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="order in freightOrders.data" :key="order.id">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ order.order_number }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ order.shipper_company?.name || 'N/A' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ order.carrier_company?.name || 'N/A' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ order.origin_location?.city }} → {{ order.destination_location?.city }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div v-if="order.assigned_driver" class="flex items-center space-x-2">
                          <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-600">{{ order.assigned_driver.name.charAt(0) }}</span>
                          </div>
                          <div>
                            <div class="font-medium text-gray-900">{{ order.assigned_driver.name }}</div>
                            <div class="text-xs text-gray-500">{{ order.assigned_driver.vehicle_type }} • {{ order.assigned_driver.phone }}</div>
                          </div>
                        </div>
                        <div v-else class="text-gray-400 italic">
                          Unassigned
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                              :class="getFreightStatusClass(order.status)">
                          {{ formatFreightStatus(order.status) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatWeight(order.total_weight) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button @click="viewFreightOrder(order.id)" 
                                class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                        <button @click="editFreightOrder(order)" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button v-if="order.status === 'draft'" @click="startFreightOrder(order.id)" 
                                class="text-green-600 hover:text-green-900 mr-3">Start</button>
                        <button @click="deleteFreightOrder(order.id)" 
                                class="text-red-600 hover:text-red-900">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="freightOrders.last_page > 1" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
              <div class="flex flex-1 justify-between sm:hidden">
                <button @click="loadFreightOrders(freightOrders.current_page - 1)" 
                        :disabled="freightOrders.current_page === 1"
                        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                  Previous
                </button>
                <button @click="loadFreightOrders(freightOrders.current_page + 1)" 
                        :disabled="freightOrders.current_page === freightOrders.last_page"
                        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                  Next
                </button>
              </div>
              <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing {{ freightOrders.from }} to {{ freightOrders.to }} of {{ freightOrders.total }} results
                  </p>
                </div>
                <div>
                  <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <button @click="loadFreightOrders(freightOrders.current_page - 1)" 
                            :disabled="freightOrders.current_page === 1"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                      <span class="sr-only">Previous</span>
                      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                      </svg>
                    </button>
                    <button v-for="page in visibleFreightPages" :key="page" 
                            @click="loadFreightOrders(page)"
                            :class="[
                              page === freightOrders.current_page 
                                ? 'relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                                : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'
                            ]">
                      {{ page }}
                    </button>
                    <button @click="loadFreightOrders(freightOrders.current_page + 1)" 
                            :disabled="freightOrders.current_page === freightOrders.last_page"
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

          <!-- Delivery Tasks Tab -->
          <div v-if="activeTab === 'tasks'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Delivery Tasks</h3>
              <div class="flex space-x-3">
                <select v-model="taskFilters.status" @change="loadDeliveryTasks" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="pending">Pending</option>
                  <option value="assigned">Assigned</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="failed">Failed</option>
                </select>
                <select v-model="taskFilters.task_type" @change="loadDeliveryTasks"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Types</option>
                  <option value="pickup">Pickup</option>
                  <option value="delivery">Delivery</option>
                  <option value="return_pickup">Return Pickup</option>
                  <option value="service_call">Service Call</option>
                </select>
              </div>
            </div>

            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="task in deliveryTasks.data" :key="task.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ task.task_number }}</div>
                      <div class="text-sm text-gray-500">{{ task.distance_km }} km</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ task.task_type }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ task.assigned_driver?.name || 'Unassigned' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="[
                        task.priority === 'urgent' ? 'bg-red-100 text-red-800' :
                        task.priority === 'high' ? 'bg-orange-100 text-orange-800' :
                        task.priority === 'normal' ? 'bg-blue-100 text-blue-800' :
                        'bg-gray-100 text-gray-800',
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                      ]">
                        {{ task.priority }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="[
                        task.status === 'completed' ? 'bg-green-100 text-green-800' :
                        task.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                        task.status === 'assigned' ? 'bg-yellow-100 text-yellow-800' :
                        task.status === 'failed' ? 'bg-red-100 text-red-800' :
                        'bg-gray-100 text-gray-800',
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                      ]">
                        {{ task.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ formatDate(task.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="viewTask(task.id)" 
                              class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                      <button @click="updateTaskStatus(task.id, task.status)" 
                              class="text-indigo-600 hover:text-indigo-900">Update</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Notifications Tab -->
          <div v-if="activeTab === 'notifications'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium text-gray-900">Customer Notifications</h3>
              <div class="flex space-x-3">
                <select v-model="notificationFilters.channel" @change="loadNotifications" 
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Channels</option>
                  <option value="email">Email</option>
                  <option value="sms">SMS</option>
                  <option value="push">Push</option>
                </select>
                <select v-model="notificationFilters.status" @change="loadNotifications"
                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                  <option value="">All Status</option>
                  <option value="sent">Sent</option>
                  <option value="delivered">Delivered</option>
                  <option value="failed">Failed</option>
                  <option value="opened">Opened</option>
                </select>
              </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
              <ul class="divide-y divide-gray-200">
                <li v-for="notification in notifications.data" :key="notification.id">
                  <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center">
                        <div class="flex-shrink-0">
                          <div :class="[
                            notification.channel === 'email' ? 'bg-blue-100' :
                            notification.channel === 'sms' ? 'bg-green-100' :
                            'bg-purple-100',
                            'h-10 w-10 rounded-full flex items-center justify-center'
                          ]">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ notification.notification_type.replace('_', ' ').toUpperCase() }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ notification.recipient_name }} ({{ notification.channel }})
                          </div>
                        </div>
                      </div>
                      <div class="flex items-center space-x-4">
                        <span :class="[
                          notification.status === 'sent' ? 'bg-blue-100 text-blue-800' :
                          notification.status === 'delivered' ? 'bg-green-100 text-green-800' :
                          notification.status === 'failed' ? 'bg-red-100 text-red-800' :
                          notification.status === 'opened' ? 'bg-purple-100 text-purple-800' :
                          'bg-gray-100 text-gray-800',
                          'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                        ]">
                          {{ notification.status }}
                        </span>
                        <div class="text-sm text-gray-500">
                          {{ formatDateTime(notification.sent_at) }}
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
  </div>

  <!-- Order Form Modal -->
  <div v-if="showOrderForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
          {{ editingOrder ? 'Edit Order' : 'Create Order' }}
        </h3>
        <form @submit.prevent="saveOrder" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Shipper Company</label>
            <select v-model="orderForm.shipper_company_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select Shipper</option>
              <option v-for="company in companies.filter(c => c.type === 'shipper')" :key="company.id" :value="company.id">
                {{ company.name }} ({{ company.code }})
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Carrier Company</label>
            <select v-model="orderForm.carrier_company_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select Carrier</option>
              <option v-for="company in companies.filter(c => c.type === 'carrier')" :key="company.id" :value="company.id">
                {{ company.name }} ({{ company.code }})
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Origin Location</label>
            <select v-model="orderForm.origin_location_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select Origin</option>
              <option v-for="location in allLocations" :key="location.id" :value="location.id">
                {{ location.name }} - {{ location.city }}, {{ location.state }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Destination Location</label>
            <select v-model="orderForm.destination_location_id" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="">Select Destination</option>
              <option v-for="location in allLocations" :key="location.id" :value="location.id">
                {{ location.name }} - {{ location.city }}, {{ location.state }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Service Type</label>
            <select v-model="orderForm.service_type" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="ltl">LTL</option>
              <option value="ftl">FTL</option>
              <option value="air">Air</option>
              <option value="ocean">Ocean</option>
              <option value="rail">Rail</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Priority</label>
            <select v-model="orderForm.priority" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="low">Low</option>
              <option value="normal">Normal</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Total Weight (kg)</label>
            <input v-model="orderForm.total_weight" type="number" step="0.01" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Total Volume (m³)</label>
            <input v-model="orderForm.total_volume" type="number" step="0.01" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Declared Value ($)</label>
            <input v-model="orderForm.declared_value" type="number" step="0.01" required
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select v-model="orderForm.status" required
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
              <option value="draft">Draft</option>
              <option value="quoted">Quoted</option>
              <option value="booked">Booked</option>
              <option value="picked_up">Picked Up</option>
              <option value="in_transit">In Transit</option>
              <option value="delivered">Delivered</option>
              <option value="exception">Exception</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="flex justify-end space-x-3">
            <button type="button" @click="closeOrderForm"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
              Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
              {{ editingOrder ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Freight Order View Modal -->
  <div v-if="showFreightOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Freight Order Details</h3>
          <button @click="showFreightOrderModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="selectedFreightOrder" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Order Number</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.order_number }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <span :class="getStatusClass(selectedFreightOrder.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ selectedFreightOrder.status }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Service Type</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.service_type }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Priority</label>
              <span :class="getPriorityClass(selectedFreightOrder.priority)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ selectedFreightOrder.priority }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Shipper</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.shipper_company?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Carrier</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.carrier_company?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Origin</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.origin_location?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Destination</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.destination_location?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total Weight</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatWeight(selectedFreightOrder.total_weight_kg) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Total Volume</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatVolume(selectedFreightOrder.total_volume_m3) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Pickup Date</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedFreightOrder.pickup_date) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Delivery Date</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedFreightOrder.delivery_date) }}</p>
            </div>
          </div>
          
          <div v-if="selectedFreightOrder.special_instructions">
            <label class="block text-sm font-medium text-gray-700">Special Instructions</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedFreightOrder.special_instructions }}</p>
          </div>
        </div>
        
        <div class="flex justify-end mt-6">
          <button @click="showFreightOrderModal = false" 
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delivery Task View Modal -->
  <div v-if="showTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium text-gray-900">Delivery Task Details</h3>
          <button @click="showTaskModal = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div v-if="selectedTask" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Task Number</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.task_number }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <span :class="getTaskStatusClass(selectedTask.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ selectedTask.status }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Task Type</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.task_type }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Priority</label>
              <span :class="getPriorityClass(selectedTask.priority)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                {{ selectedTask.priority }}
              </span>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Assigned Driver</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.assigned_driver?.name || 'Unassigned' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Distance</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.distance_km }} km</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Pickup Location</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.pickup_location?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Delivery Location</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.delivery_location?.name || 'N/A' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Scheduled Start</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedTask.scheduled_start_time) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Scheduled End</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(selectedTask.scheduled_end_time) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Actual Start</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.actual_start_time ? formatDateTime(selectedTask.actual_start_time) : 'Not started' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Actual End</label>
              <p class="mt-1 text-sm text-gray-900">{{ selectedTask.actual_end_time ? formatDateTime(selectedTask.actual_end_time) : 'Not completed' }}</p>
            </div>
          </div>
          
          <div v-if="selectedTask.task_instructions">
            <label class="block text-sm font-medium text-gray-700">Task Instructions</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedTask.task_instructions }}</p>
          </div>
          
          <div v-if="selectedTask.delivery_instructions">
            <label class="block text-sm font-medium text-gray-700">Delivery Instructions</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedTask.delivery_instructions }}</p>
          </div>
        </div>
        
        <div class="flex justify-end mt-6">
          <button @click="showTaskModal = false" 
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
            Close
          </button>
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
const orderFulfillments = ref({ data: [] })
const deliveryTasks = ref({ data: [] })
const notifications = ref({ data: [] })
const freightOrders = ref({ 
  data: [], 
  total: 0, 
  current_page: 1, 
  last_page: 1,
  per_page: 15,
  from: null,
  to: null
})
const loading = ref(false)

// Modal states
const showFreightOrderModal = ref(false)
const showTaskModal = ref(false)
const selectedFreightOrder = ref(null)
const selectedTask = ref(null)

const activeTab = ref('orders')

const orderFilters = ref({
  search: '',
  status: ''
})

const freightOrderFilters = ref({
  status: '',
  service_type: ''
})

const taskFilters = ref({
  status: '',
  task_type: ''
})

const notificationFilters = ref({
  channel: '',
  status: ''
})

// Order form state
const showOrderForm = ref(false)
const editingOrder = ref(null)
const orderForm = ref({
  shipper_company_id: '',
  carrier_company_id: '',
  origin_location_id: '',
  destination_location_id: '',
  service_type: 'ltl',
  priority: 'normal',
  total_weight: '',
  total_volume: '',
  declared_value: '',
  status: 'draft'
})

// Data for dropdowns
const companies = ref([])
const allLocations = ref([])

// Computed properties
const tabs = computed(() => [
  { id: 'orders', name: 'Orders', count: freightOrders.value.total || 0 },
  { id: 'tasks', name: 'Delivery Tasks', count: summary.value.delivery_tasks?.total },
  { id: 'notifications', name: 'Notifications', count: summary.value.customer_notifications?.total }
])

const visibleFreightPages = computed(() => {
  if (!freightOrders.value.last_page) return []
  const current = freightOrders.value.current_page
  const last = freightOrders.value.last_page
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
    const response = await axios.get('/api/v1/delivery/summary')
    summary.value = response.data.data
  } catch (error) {
    console.error('Error loading summary:', error)
  }
}

const loadOrderFulfillments = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...orderFilters.value
    }
    
    const response = await axios.get('/api/v1/delivery/order-fulfillments', { params })
    orderFulfillments.value = response.data.data
  } catch (error) {
    console.error('Error loading order fulfillments:', error)
  } finally {
    loading.value = false
  }
}

const loadDeliveryTasks = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...taskFilters.value
    }
    
    const response = await axios.get('/api/v1/delivery/delivery-tasks', { params })
    deliveryTasks.value = response.data.data
  } catch (error) {
    console.error('Error loading delivery tasks:', error)
  } finally {
    loading.value = false
  }
}

const loadNotifications = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      ...notificationFilters.value
    }
    
    const response = await axios.get('/api/v1/delivery/customer-notifications', { params })
    notifications.value = response.data.data
  } catch (error) {
    console.error('Error loading notifications:', error)
  } finally {
    loading.value = false
  }
}

const loadFreightOrders = async (page = 1) => {
  try {
    loading.value = true
    const params = {
      page,
      per_page: 15,
      ...freightOrderFilters.value
    }
    
    const response = await axios.get('/api/v1/freight-orders', { params })
    
    if (response.data.success && response.data.data) {
      freightOrders.value = response.data.data
    } else {
      console.error('❌ Invalid freight orders response:', response.data)
      freightOrders.value = { 
        data: [], 
        total: 0, 
        current_page: 1, 
        last_page: 1,
        per_page: 15,
        from: null,
        to: null
      }
    }
  } catch (error) {
    console.error('💥 Error loading freight orders:', error)
  } finally {
    loading.value = false
  }
}

const refreshData = () => {
  loadSummary()
  if (activeTab.value === 'orders') loadFreightOrders()
  if (activeTab.value === 'tasks') loadDeliveryTasks()
  if (activeTab.value === 'notifications') loadNotifications()
}

const viewOrder = (id) => {
  console.log('View order:', id)
}

const editOrder = (id) => {
  console.log('Edit order:', id)
}

// Freight Order Actions
const viewFreightOrder = async (id) => {
  try {
    const response = await axios.get(`/api/v1/freight-orders/${id}`)
    if (response.data.success) {
      selectedFreightOrder.value = response.data.data
      showFreightOrderModal.value = true
    }
  } catch (error) {
    console.error('Error fetching freight order:', error)
    alert('Failed to load freight order details')
  }
}


const startFreightOrder = (id) => {
  console.log('Start freight order:', id)
}

const deleteFreightOrder = (id) => {
  console.log('Delete freight order:', id)
}

// Utility Functions
const formatWeight = (weight) => {
  if (!weight) return 'N/A'
  return `${weight} kg`
}

const formatFreightStatus = (status) => {
  const statusMap = {
    'draft': 'Draft',
    'booked': 'Booked',
    'picked_up': 'Picked Up',
    'in_transit': 'In Transit',
    'delivered': 'Delivered',
    'cancelled': 'Cancelled'
  }
  return statusMap[status] || status
}

const getFreightStatusClass = (status) => {
  const classMap = {
    'draft': 'bg-gray-100 text-gray-800',
    'booked': 'bg-blue-100 text-blue-800',
    'picked_up': 'bg-yellow-100 text-yellow-800',
    'in_transit': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const viewTask = async (id) => {
  try {
    const response = await axios.get(`/api/v1/delivery/delivery-tasks/${id}`)
    if (response.data.success) {
      selectedTask.value = response.data.data
      showTaskModal.value = true
    }
  } catch (error) {
    console.error('Error fetching task:', error)
    alert('Failed to load task details')
  }
}

const updateTaskStatus = async (id, currentStatus) => {
  const newStatus = prompt('Enter new status:', currentStatus)
  if (newStatus && newStatus !== currentStatus) {
    try {
      await axios.put(`/api/v1/delivery/delivery-tasks/${id}/status`, {
        status: newStatus
      })
      loadDeliveryTasks()
    } catch (error) {
      console.error('Error updating task status:', error)
      alert('Failed to update task status')
    }
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString()
}

const formatVolume = (volume) => {
  if (!volume) return 'N/A'
  return `${volume} m³`
}

const getStatusClass = (status) => {
  const classMap = {
    'draft': 'bg-gray-100 text-gray-800',
    'booked': 'bg-blue-100 text-blue-800',
    'picked_up': 'bg-yellow-100 text-yellow-800',
    'in_transit': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

const getPriorityClass = (priority) => {
  const classMap = {
    'low': 'bg-gray-100 text-gray-800',
    'normal': 'bg-blue-100 text-blue-800',
    'high': 'bg-orange-100 text-orange-800',
    'urgent': 'bg-red-100 text-red-800'
  }
  return classMap[priority] || 'bg-gray-100 text-gray-800'
}

const getTaskStatusClass = (status) => {
  const classMap = {
    'pending': 'bg-gray-100 text-gray-800',
    'assigned': 'bg-blue-100 text-blue-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'failed': 'bg-red-100 text-red-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}

// Order Form Methods
const loadCompaniesAndLocations = async () => {
  try {
    // Load companies
    const companiesResponse = await axios.get('/api/v1/master-data/companies')
    companies.value = companiesResponse.data.data || []
    
    // Load locations
    const locationsResponse = await axios.get('/api/v1/master-data/locations')
    allLocations.value = locationsResponse.data.data || []
  } catch (error) {
    console.error('Error loading companies and locations:', error)
  }
}

const resetOrderForm = () => {
  orderForm.value = {
    shipper_company_id: '',
    carrier_company_id: '',
    origin_location_id: '',
    destination_location_id: '',
    service_type: 'ltl',
    priority: 'normal',
    total_weight: '',
    total_volume: '',
    declared_value: '',
    status: 'draft'
  }
}

const closeOrderForm = () => {
  showOrderForm.value = false
  editingOrder.value = null
  resetOrderForm()
}

const saveOrder = async () => {
  try {
    const url = editingOrder.value 
      ? `/api/v1/freight-orders/${editingOrder.value.id}` 
      : '/api/v1/freight-orders'
    const method = editingOrder.value ? 'PUT' : 'POST'
    
    // Add required fields for new orders
    const data = { ...orderForm.value }
    if (!editingOrder.value) {
      data.order_number = 'FO-' + new Date().toISOString().slice(0, 10).replace(/-/g, '') + '-' + String(Date.now()).slice(-4)
      data.status = 'booked' // Set to booked so it appears as available order for drivers
    }
    
    const response = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify(data)
    })
    
    if (response.ok) {
      const result = await response.json()
      closeOrderForm()
      // Refresh freight orders data
      await loadFreightOrders()
      alert(editingOrder.value ? 'Order updated successfully!' : 'Order created successfully!')
    } else {
      const error = await response.json()
      console.error('Order save error:', error)
      alert('Failed to save order: ' + (error.message || 'Unknown error'))
    }
  } catch (error) {
    console.error('Error saving order:', error)
    alert('Error saving order')
  }
}

const editFreightOrder = (order) => {
  editingOrder.value = order
  orderForm.value = {
    shipper_company_id: order.shipper_company_id || '',
    carrier_company_id: order.carrier_company_id || '',
    origin_location_id: order.origin_location_id || '',
    destination_location_id: order.destination_location_id || '',
    service_type: order.service_type || 'ltl',
    priority: order.priority || 'normal',
    total_weight: order.total_weight || '',
    total_volume: order.total_volume || '',
    declared_value: order.declared_value || '',
    status: order.status || 'draft'
  }
  showOrderForm.value = true
}

// Watch for tab changes
watch(activeTab, (newTab) => {
  if (newTab === 'orders') {
    loadFreightOrders()
  } else if (newTab === 'tasks') {
    loadDeliveryTasks()
  } else if (newTab === 'notifications') {
    loadNotifications()
  }
})

// Initialize
onMounted(() => {
  loadSummary()
  loadFreightOrders()
  loadCompaniesAndLocations()
})
</script>
