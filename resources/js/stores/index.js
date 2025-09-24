import { createPinia } from 'pinia'

export const pinia = createPinia()

// Export all stores
export { useAuthStore } from './auth'
export { useCompanyStore } from './company'
export { useProductStore } from './product'
export { useFreightOrderStore } from './freightOrder'
export { useManufacturingStore } from './manufacturing'
export { useInventoryStore } from './inventory'
export { useReturnsStore } from './returns'
export { useSupplierStore } from './supplier'
export { useDeliveryStore } from './delivery'
export { useNotificationStore } from './notification'
export { useWebSocketStore } from './websocket'
