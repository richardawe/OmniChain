<template>
  <div class="websocket-status">
    <div 
      class="status-indicator"
      :class="{
        'connected': isConnected,
        'disconnected': !isConnected,
        'connecting': connectionState === 'connecting'
      }"
    >
      <div class="status-dot"></div>
      <span class="status-text">
        {{ statusText }}
      </span>
    </div>
    
    <div v-if="error" class="error-message">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'
import { useWebSocketStore } from '../stores/websocket'

const websocketStore = useWebSocketStore()

const isConnected = computed(() => websocketStore.isWebSocketConnected)
const connectionState = computed(() => websocketStore.getConnectionState)
const error = computed(() => websocketStore.error)

const statusText = computed(() => {
  switch (connectionState.value) {
    case 'connected':
      return 'Connected'
    case 'connecting':
      return 'Connecting...'
    case 'disconnected':
      return 'Disconnected'
    default:
      return 'Unknown'
  }
})

onMounted(() => {
  websocketStore.initialize()
})

onUnmounted(() => {
  websocketStore.disconnect()
})
</script>

<style scoped>
.websocket-status {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 1000;
}

.status-indicator {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  border-radius: 20px;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  font-size: 12px;
  font-weight: 500;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  transition: background-color 0.3s ease;
}

.status-indicator.connected .status-dot {
  background-color: #10b981;
  animation: pulse 2s infinite;
}

.status-indicator.disconnected .status-dot {
  background-color: #ef4444;
}

.status-indicator.connecting .status-dot {
  background-color: #f59e0b;
  animation: pulse 1s infinite;
}

.status-text {
  color: #374151;
}

.error-message {
  margin-top: 4px;
  padding: 4px 8px;
  background: #fef2f2;
  color: #dc2626;
  border-radius: 4px;
  font-size: 11px;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>
