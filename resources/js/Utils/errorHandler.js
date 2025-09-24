import { useNotificationStore } from '../stores/notification'

class ErrorHandler {
    constructor() {
        this.notificationStore = useNotificationStore()
        this.setupGlobalErrorHandling()
    }

    /**
     * Setup global error handling for unhandled errors
     */
    setupGlobalErrorHandling() {
        // Handle unhandled promise rejections
        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason)
            this.handleError(event.reason, 'Unhandled Promise Rejection')
        })

        // Handle uncaught errors
        window.addEventListener('error', (event) => {
            console.error('Uncaught error:', event.error)
            this.handleError(event.error, 'Uncaught Error')
        })
    }

    /**
     * Handle API errors
     */
    handleApiError(error, context = 'API Request') {
        console.error(`${context} Error:`, error)

        if (error.response) {
            // Server responded with error status
            const { status, data } = error.response
            
            switch (status) {
                case 400:
                    this.showError('Bad Request', data.message || 'Invalid request parameters')
                    break
                case 401:
                    this.showError('Unauthorized', 'Please log in to continue')
                    this.handleUnauthorized()
                    break
                case 403:
                    this.showError('Forbidden', 'You do not have permission to perform this action')
                    break
                case 404:
                    this.showError('Not Found', 'The requested resource was not found')
                    break
                case 422:
                    this.handleValidationErrors(data.errors || {})
                    break
                case 429:
                    this.showError('Too Many Requests', 'Please wait before making another request')
                    break
                case 500:
                    this.showError('Server Error', 'An internal server error occurred. Please try again later.')
                    break
                default:
                    this.showError('Request Failed', data.message || 'An error occurred while processing your request')
            }
        } else if (error.request) {
            // Request was made but no response received
            this.showError('Network Error', 'Unable to connect to the server. Please check your internet connection.')
        } else {
            // Something else happened
            this.showError('Request Error', error.message || 'An unexpected error occurred')
        }
    }

    /**
     * Handle validation errors
     */
    handleValidationErrors(errors) {
        const errorMessages = []
        
        Object.keys(errors).forEach(field => {
            if (Array.isArray(errors[field])) {
                errorMessages.push(...errors[field])
            } else {
                errorMessages.push(errors[field])
            }
        })

        if (errorMessages.length > 0) {
            this.showError('Validation Failed', errorMessages.join(', '))
        }
    }

    /**
     * Handle unauthorized errors
     */
    handleUnauthorized() {
        // Clear user session
        localStorage.removeItem('token')
        
        // Redirect to login page
        window.location.href = '/login'
    }

    /**
     * Handle general errors
     */
    handleError(error, context = 'Application Error') {
        console.error(`${context}:`, error)

        let message = 'An unexpected error occurred'
        
        if (error instanceof Error) {
            message = error.message
        } else if (typeof error === 'string') {
            message = error
        }

        this.showError('Error', message)
    }

    /**
     * Show error notification
     */
    showError(title, message) {
        this.notificationStore.error(title, message)
    }

    /**
     * Show success notification
     */
    showSuccess(title, message) {
        this.notificationStore.success(title, message)
    }

    /**
     * Show warning notification
     */
    showWarning(title, message) {
        this.notificationStore.warning(title, message)
    }

    /**
     * Show info notification
     */
    showInfo(title, message) {
        this.notificationStore.info(title, message)
    }

    /**
     * Handle WebSocket errors
     */
    handleWebSocketError(error, context = 'WebSocket Error') {
        console.error(`${context}:`, error)
        
        let message = 'WebSocket connection error'
        
        if (error.message) {
            message = error.message
        }

        this.showError('Connection Error', message)
    }

    /**
     * Handle form validation errors
     */
    handleFormErrors(errors) {
        // This would typically be handled by form components
        // but we can provide a fallback notification
        const errorCount = Object.keys(errors).length
        
        if (errorCount > 0) {
            this.showError('Form Validation', `Please fix ${errorCount} error${errorCount > 1 ? 's' : ''} in the form`)
        }
    }

    /**
     * Handle network errors
     */
    handleNetworkError() {
        this.showError('Network Error', 'Unable to connect to the server. Please check your internet connection and try again.')
    }

    /**
     * Handle timeout errors
     */
    handleTimeoutError() {
        this.showError('Request Timeout', 'The request took too long to complete. Please try again.')
    }

    /**
     * Handle rate limit errors
     */
    handleRateLimitError(retryAfter = null) {
        let message = 'Too many requests. Please wait before trying again.'
        
        if (retryAfter) {
            message += ` Please wait ${retryAfter} seconds.`
        }

        this.showError('Rate Limited', message)
    }

    /**
     * Log error for debugging
     */
    logError(error, context = 'Application Error', additionalData = {}) {
        const errorData = {
            message: error.message || error,
            stack: error.stack,
            context,
            timestamp: new Date().toISOString(),
            url: window.location.href,
            userAgent: navigator.userAgent,
            ...additionalData
        }

        console.error('Error logged:', errorData)
        
        // In a real application, you might want to send this to a logging service
        // this.sendToLoggingService(errorData)
    }

    /**
     * Create a standardized error response
     */
    createErrorResponse(message, code = 'GENERIC_ERROR', details = {}) {
        return {
            success: false,
            error: {
                code,
                message,
                details,
                timestamp: new Date().toISOString()
            }
        }
    }
}

// Create singleton instance
const errorHandler = new ErrorHandler()

export default errorHandler
