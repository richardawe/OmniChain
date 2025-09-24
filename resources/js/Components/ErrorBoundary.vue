<template>
  <div v-if="hasError" class="error-boundary">
    <div class="error-container">
      <div class="error-icon">
        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>
      
      <div class="error-content">
        <h2 class="error-title">Something went wrong</h2>
        <p class="error-message">{{ errorMessage }}</p>
        
        <div v-if="showDetails && errorDetails" class="error-details">
          <details>
            <summary class="error-details-summary">Error Details</summary>
            <pre class="error-details-content">{{ errorDetails }}</pre>
          </details>
        </div>
        
        <div class="error-actions">
          <button 
            @click="retry" 
            class="btn-retry"
            :disabled="isRetrying"
          >
            {{ isRetrying ? 'Retrying...' : 'Try Again' }}
          </button>
          
          <button 
            @click="reportError" 
            class="btn-report"
            v-if="!errorReported"
          >
            Report Error
          </button>
          
          <button 
            @click="goHome" 
            class="btn-home"
          >
            Go Home
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <slot v-else />
</template>

<script setup>
import { ref, onMounted, onUnmounted, onErrorCaptured } from 'vue'
import { useRouter } from 'vue-router'
import errorHandler from '../utils/errorHandler'

const props = defineProps({
  fallback: {
    type: String,
    default: 'Something went wrong. Please try again.'
  },
  showDetails: {
    type: Boolean,
    default: false
  },
  onError: {
    type: Function,
    default: null
  }
})

const router = useRouter()

const hasError = ref(false)
const errorMessage = ref('')
const errorDetails = ref('')
const isRetrying = ref(false)
const errorReported = ref(false)

// Capture errors in child components
onErrorCaptured((error, instance, info) => {
  console.error('Error captured by ErrorBoundary:', error)
  console.error('Component instance:', instance)
  console.error('Error info:', info)
  
  handleError(error, info)
  return false // Prevent the error from propagating
})

// Handle errors
const handleError = (error, info = null) => {
  hasError.value = true
  errorMessage.value = error.message || props.fallback
  errorDetails.value = error.stack || error.toString()
  
  // Log the error
  errorHandler.logError(error, 'ErrorBoundary', {
    component: info?.componentName,
    props: info?.props,
    lifecycle: info?.lifecycle
  })
  
  // Call custom error handler if provided
  if (props.onError) {
    props.onError(error, info)
  }
  
  // Show error notification
  errorHandler.handleError(error, 'Component Error')
}

// Retry function
const retry = async () => {
  isRetrying.value = true
  
  try {
    // Reset error state
    hasError.value = false
    errorMessage.value = ''
    errorDetails.value = ''
    errorReported.value = false
    
    // Wait a moment before allowing retry
    await new Promise(resolve => setTimeout(resolve, 1000))
  } catch (error) {
    handleError(error, 'Retry Error')
  } finally {
    isRetrying.value = false
  }
}

// Report error function
const reportError = () => {
  // In a real application, you would send this to your error reporting service
  console.log('Error reported:', {
    message: errorMessage.value,
    details: errorDetails.value,
    timestamp: new Date().toISOString(),
    url: window.location.href,
    userAgent: navigator.userAgent
  })
  
  errorReported.value = true
  errorHandler.showSuccess('Error Reported', 'Thank you for reporting this error. We will look into it.')
}

// Go home function
const goHome = () => {
  router.push('/')
}

// Setup global error handling
onMounted(() => {
  // Handle unhandled promise rejections
  const handleUnhandledRejection = (event) => {
    console.error('Unhandled promise rejection:', event.reason)
    handleError(event.reason, 'Unhandled Promise Rejection')
  }
  
  // Handle uncaught errors
  const handleUncaughtError = (event) => {
    console.error('Uncaught error:', event.error)
    handleError(event.error, 'Uncaught Error')
  }
  
  window.addEventListener('unhandledrejection', handleUnhandledRejection)
  window.addEventListener('error', handleUncaughtError)
  
  // Cleanup on unmount
  onUnmounted(() => {
    window.removeEventListener('unhandledrejection', handleUnhandledRejection)
    window.removeEventListener('error', handleUncaughtError)
  })
})
</script>

<style scoped>
.error-boundary {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f9fafb;
  padding: 2rem;
}

.error-container {
  max-width: 600px;
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  text-align: center;
}

.error-icon {
  margin-bottom: 1rem;
}

.error-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.error-message {
  color: #6b7280;
  margin-bottom: 1.5rem;
  line-height: 1.5;
}

.error-details {
  margin-bottom: 1.5rem;
  text-align: left;
}

.error-details-summary {
  cursor: pointer;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.5rem;
}

.error-details-content {
  background: #f3f4f6;
  padding: 1rem;
  border-radius: 0.25rem;
  font-family: monospace;
  font-size: 0.875rem;
  color: #374151;
  white-space: pre-wrap;
  word-break: break-all;
  max-height: 200px;
  overflow-y: auto;
}

.error-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-retry,
.btn-report,
.btn-home {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.btn-retry {
  background-color: #3b82f6;
  color: white;
}

.btn-retry:hover:not(:disabled) {
  background-color: #2563eb;
}

.btn-retry:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-report {
  background-color: #f59e0b;
  color: white;
}

.btn-report:hover {
  background-color: #d97706;
}

.btn-home {
  background-color: #6b7280;
  color: white;
}

.btn-home:hover {
  background-color: #4b5563;
}

@media (max-width: 640px) {
  .error-container {
    padding: 1rem;
  }
  
  .error-actions {
    flex-direction: column;
  }
  
  .btn-retry,
  .btn-report,
  .btn-home {
    width: 100%;
  }
}
</style>
