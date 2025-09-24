import axios from 'axios'
import errorHandler from './errorHandler'

// Create axios instance with error handling
const api = axios.create({
  baseURL: '/api/v1',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
})

// Request interceptor
api.interceptors.request.use(
  (config) => {
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (csrfToken) {
      config.headers['X-CSRF-TOKEN'] = csrfToken
    }

    // Add authorization token
    const token = localStorage.getItem('token')
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }

    // Add request ID for tracking
    config.headers['X-Request-ID'] = generateRequestId()

    return config
  },
  (error) => {
    console.error('Request interceptor error:', error)
    return Promise.reject(error)
  }
)

// Response interceptor
api.interceptors.response.use(
  (response) => {
    // Log successful requests in development
    if (import.meta.env.DEV) {
      console.log(`API Request: ${response.config.method?.toUpperCase()} ${response.config.url} - ${response.status}`)
    }
    
    return response
  },
  (error) => {
    // Handle different types of errors
    if (error.code === 'ECONNABORTED') {
      // Timeout error
      errorHandler.handleTimeoutError()
    } else if (error.code === 'NETWORK_ERROR' || !error.response) {
      // Network error
      errorHandler.handleNetworkError()
    } else {
      // API error with response
      errorHandler.handleApiError(error, 'API Request')
    }

    return Promise.reject(error)
  }
)

// Generate unique request ID
function generateRequestId() {
  return 'req_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now()
}

// Enhanced error handling for specific scenarios
export const handleApiError = (error, context = 'API Request') => {
  if (error.response) {
    const { status, data } = error.response
    
    // Handle specific status codes
    switch (status) {
      case 400:
        return {
          type: 'bad_request',
          message: data.message || 'Invalid request parameters',
          details: data.errors || {}
        }
      
      case 401:
        return {
          type: 'unauthorized',
          message: 'Authentication required',
          action: 'redirect_to_login'
        }
      
      case 403:
        return {
          type: 'forbidden',
          message: 'Access denied',
          details: data.message || 'You do not have permission to perform this action'
        }
      
      case 404:
        return {
          type: 'not_found',
          message: 'Resource not found',
          details: data.message || 'The requested resource was not found'
        }
      
      case 422:
        return {
          type: 'validation_error',
          message: 'Validation failed',
          details: data.errors || {}
        }
      
      case 429:
        return {
          type: 'rate_limited',
          message: 'Too many requests',
          details: data.message || 'Please wait before making another request',
          retryAfter: data.retry_after
        }
      
      case 500:
        return {
          type: 'server_error',
          message: 'Internal server error',
          details: data.message || 'An internal server error occurred'
        }
      
      default:
        return {
          type: 'unknown_error',
          message: data.message || 'An error occurred',
          details: data
        }
    }
  } else if (error.request) {
    return {
      type: 'network_error',
      message: 'Network error',
      details: 'Unable to connect to the server'
    }
  } else {
    return {
      type: 'request_error',
      message: error.message || 'Request failed',
      details: error
    }
  }
}

// Retry mechanism for failed requests
export const retryRequest = async (requestFn, maxRetries = 3, delay = 1000) => {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      return await requestFn()
    } catch (error) {
      if (attempt === maxRetries) {
        throw error
      }
      
      // Don't retry on certain error types
      if (error.response?.status === 401 || error.response?.status === 403) {
        throw error
      }
      
      // Wait before retrying
      await new Promise(resolve => setTimeout(resolve, delay * attempt))
    }
  }
}

// Request wrapper with automatic retry
export const apiRequest = async (requestFn, options = {}) => {
  const { retries = 0, retryDelay = 1000 } = options
  
  if (retries > 0) {
    return retryRequest(requestFn, retries, retryDelay)
  }
  
  return requestFn()
}

// Utility functions for common API patterns
export const apiUtils = {
  // GET request with error handling
  async get(url, config = {}) {
    try {
      const response = await api.get(url, config)
      return response.data
    } catch (error) {
      throw handleApiError(error, 'GET Request')
    }
  },

  // POST request with error handling
  async post(url, data, config = {}) {
    try {
      const response = await api.post(url, data, config)
      return response.data
    } catch (error) {
      throw handleApiError(error, 'POST Request')
    }
  },

  // PUT request with error handling
  async put(url, data, config = {}) {
    try {
      const response = await api.put(url, data, config)
      return response.data
    } catch (error) {
      throw handleApiError(error, 'PUT Request')
    }
  },

  // DELETE request with error handling
  async delete(url, config = {}) {
    try {
      const response = await api.delete(url, config)
      return response.data
    } catch (error) {
      throw handleApiError(error, 'DELETE Request')
    }
  },

  // PATCH request with error handling
  async patch(url, data, config = {}) {
    try {
      const response = await api.patch(url, data, config)
      return response.data
    } catch (error) {
      throw handleApiError(error, 'PATCH Request')
    }
  }
}

export default api
