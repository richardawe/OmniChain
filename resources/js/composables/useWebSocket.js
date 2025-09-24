import { ref, onMounted, onUnmounted } from 'vue'
import { useWebSocketStore } from '../stores/websocket'

export function useWebSocket() {
  const websocketStore = useWebSocketStore()
  const isConnected = ref(false)
  const connectionState = ref('disconnected')
  const lastMessage = ref(null)
  const error = ref(null)

  // Initialize WebSocket connection
  const initialize = async () => {
    try {
      await websocketStore.initialize()
      isConnected.value = websocketStore.isWebSocketConnected
      connectionState.value = websocketStore.getConnectionState
    } catch (err) {
      error.value = err.message
      console.error('WebSocket initialization failed:', err)
    }
  }

  // Subscribe to freight orders
  const subscribeToFreightOrders = (callback) => {
    websocketStore.subscribeToFreightOrders((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to manufacturing updates
  const subscribeToManufacturing = (callback) => {
    websocketStore.subscribeToManufacturing((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to inventory updates
  const subscribeToInventory = (callback) => {
    websocketStore.subscribeToInventory((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to delivery updates
  const subscribeToDelivery = (callback) => {
    websocketStore.subscribeToDelivery((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to return updates
  const subscribeToReturns = (callback) => {
    websocketStore.subscribeToReturns((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to supplier updates
  const subscribeToSuppliers = (callback) => {
    websocketStore.subscribeToSuppliers((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to notifications
  const subscribeToNotifications = (callback) => {
    websocketStore.subscribeToNotifications((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to user updates
  const subscribeToUser = (userId, callback) => {
    websocketStore.subscribeToUser(userId, (data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to company updates
  const subscribeToCompany = (companyId, callback) => {
    websocketStore.subscribeToCompany(companyId, (data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to location tracking
  const subscribeToTracking = (callback) => {
    websocketStore.subscribeToTracking((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Subscribe to system alerts
  const subscribeToAlerts = (callback) => {
    websocketStore.subscribeToAlerts((data) => {
      lastMessage.value = data
      callback(data)
    })
  }

  // Unsubscribe from a specific subscription
  const unsubscribe = (type) => {
    websocketStore.unsubscribe(type)
  }

  // Unsubscribe from all subscriptions
  const unsubscribeAll = () => {
    websocketStore.unsubscribeAll()
  }

  // Disconnect WebSocket
  const disconnect = () => {
    websocketStore.disconnect()
    isConnected.value = false
    connectionState.value = 'disconnected'
  }

  // Clear error
  const clearError = () => {
    websocketStore.clearError()
    error.value = null
  }

  // Auto-initialize on mount
  onMounted(() => {
    initialize()
  })

  // Auto-disconnect on unmount
  onUnmounted(() => {
    disconnect()
  })

  return {
    // State
    isConnected,
    connectionState,
    lastMessage,
    error,
    
    // Methods
    initialize,
    subscribeToFreightOrders,
    subscribeToManufacturing,
    subscribeToInventory,
    subscribeToDelivery,
    subscribeToReturns,
    subscribeToSuppliers,
    subscribeToNotifications,
    subscribeToUser,
    subscribeToCompany,
    subscribeToTracking,
    subscribeToAlerts,
    unsubscribe,
    unsubscribeAll,
    disconnect,
    clearError
  }
}
