<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-white text-2xl font-bold">O</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Driver Registration</h1>
                <p class="text-gray-600">Join our delivery network</p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form @submit.prevent="register" class="space-y-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Personal Information</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="firstName"
                                    v-model="form.firstName"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="John"
                                />
                                <div v-if="errors.firstName" class="mt-1 text-sm text-red-600">{{ errors.firstName }}</div>
                            </div>
                            
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="lastName"
                                    v-model="form.lastName"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Doe"
                                />
                                <div v-if="errors.lastName" class="mt-1 text-sm text-red-600">{{ errors.lastName }}</div>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="john.doe@example.com"
                            />
                            <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="+1 (555) 123-4567"
                            />
                            <div v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</div>
                        </div>
                    </div>

                    <!-- Driver License Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Driver License</h3>
                        
                        <div>
                            <label for="driverLicense" class="block text-sm font-medium text-gray-700 mb-2">
                                Driver License Number <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="driverLicense"
                                v-model="form.driverLicense"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="DL123456789"
                            />
                            <div v-if="errors.driverLicense" class="mt-1 text-sm text-red-600">{{ errors.driverLicense }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="licenseState" class="block text-sm font-medium text-gray-700 mb-2">
                                    State/Province <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="licenseState"
                                    v-model="form.licenseState"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                                    <option value="">Select State</option>
                                    <option value="CA">California</option>
                                    <option value="NY">New York</option>
                                    <option value="TX">Texas</option>
                                    <option value="FL">Florida</option>
                                    <option value="IL">Illinois</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="OH">Ohio</option>
                                    <option value="GA">Georgia</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="MI">Michigan</option>
                                </select>
                                <div v-if="errors.licenseState" class="mt-1 text-sm text-red-600">{{ errors.licenseState }}</div>
                            </div>
                            
                            <div>
                                <label for="licenseExpiry" class="block text-sm font-medium text-gray-700 mb-2">
                                    Expiry Date <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="licenseExpiry"
                                    v-model="form.licenseExpiry"
                                    type="date"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                />
                                <div v-if="errors.licenseExpiry" class="mt-1 text-sm text-red-600">{{ errors.licenseExpiry }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Vehicle Information</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="vehicleType" class="block text-sm font-medium text-gray-700 mb-2">
                                    Vehicle Type <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="vehicleType"
                                    v-model="form.vehicleType"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                                    <option value="">Select Vehicle</option>
                                    <option value="car">Car</option>
                                    <option value="van">Van</option>
                                    <option value="truck">Truck</option>
                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="bicycle">Bicycle</option>
                                </select>
                                <div v-if="errors.vehicleType" class="mt-1 text-sm text-red-600">{{ errors.vehicleType }}</div>
                            </div>
                            
                            <div>
                                <label for="vehicleYear" class="block text-sm font-medium text-gray-700 mb-2">
                                    Year <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="vehicleYear"
                                    v-model="form.vehicleYear"
                                    type="number"
                                    min="1990"
                                    :max="new Date().getFullYear() + 1"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="2020"
                                />
                                <div v-if="errors.vehicleYear" class="mt-1 text-sm text-red-600">{{ errors.vehicleYear }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="vehicleMake" class="block text-sm font-medium text-gray-700 mb-2">
                                    Make <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="vehicleMake"
                                    v-model="form.vehicleMake"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Toyota"
                                />
                                <div v-if="errors.vehicleMake" class="mt-1 text-sm text-red-600">{{ errors.vehicleMake }}</div>
                            </div>
                            
                            <div>
                                <label for="vehicleModel" class="block text-sm font-medium text-gray-700 mb-2">
                                    Model <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="vehicleModel"
                                    v-model="form.vehicleModel"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Camry"
                                />
                                <div v-if="errors.vehicleModel" class="mt-1 text-sm text-red-600">{{ errors.vehicleModel }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="licensePlate" class="block text-sm font-medium text-gray-700 mb-2">
                                    License Plate <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="licensePlate"
                                    v-model="form.licensePlate"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="ABC123"
                                />
                                <div v-if="errors.licensePlate" class="mt-1 text-sm text-red-600">{{ errors.licensePlate }}</div>
                            </div>
                            
                            <div>
                                <label for="maxCapacity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Max Capacity (kg) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="maxCapacity"
                                    v-model="form.maxCapacity"
                                    type="number"
                                    min="1"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="500"
                                />
                                <div v-if="errors.maxCapacity" class="mt-1 text-sm text-red-600">{{ errors.maxCapacity }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Security -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Account Security</h3>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                v-model="form.password"
                                @input="validatePasswordConfirmation"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-12"
                                placeholder="Create a strong password"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                >
                                    <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            <div v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</div>
                        </div>

                        <div>
                            <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="confirmPassword"
                                v-model="form.confirmPassword"
                                @input="validatePasswordConfirmation"
                                type="password"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Confirm your password"
                            />
                            <div v-if="errors.confirmPassword" class="mt-1 text-sm text-red-600">{{ errors.confirmPassword }}</div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-start space-x-3">
                        <input
                            id="terms"
                            v-model="form.acceptTerms"
                            type="checkbox"
                            required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1"
                        />
                        <label for="terms" class="text-sm text-gray-600">
                            I agree to the 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> 
                            and 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>
                        </label>
                    </div>
                    <div v-if="errors.acceptTerms" class="text-sm text-red-600">{{ errors.acceptTerms }}</div>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating Account...
                        </span>
                        <span v-else>Create Driver Account</span>
                    </button>
                </form>

                <!-- Error Message -->
                <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-red-700 text-sm">{{ error }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="/driver/login" class="text-blue-600 hover:text-blue-800 font-medium">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

// Reactive data
const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

const form = reactive({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    driverLicense: '',
    licenseState: '',
    licenseExpiry: '',
    vehicleType: '',
    vehicleYear: '',
    vehicleMake: '',
    vehicleModel: '',
    licensePlate: '',
    maxCapacity: '',
    password: '',
    confirmPassword: '',
    acceptTerms: false
})

const errors = reactive({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    driverLicense: '',
    licenseState: '',
    licenseExpiry: '',
    vehicleType: '',
    vehicleYear: '',
    vehicleMake: '',
    vehicleModel: '',
    licensePlate: '',
    maxCapacity: '',
    password: '',
    confirmPassword: '',
    acceptTerms: ''
})

// Methods
const validateForm = () => {
    // Clear previous errors
    Object.keys(errors).forEach(key => errors[key] = '')
    
    let isValid = true

    // Password confirmation
    if (form.password !== form.confirmPassword) {
        errors.confirmPassword = 'Passwords do not match'
        isValid = false
    }

    // Password strength
    if (form.password.length < 8) {
        errors.password = 'Password must be at least 8 characters long'
        isValid = false
    }

    // License expiry validation
    if (form.licenseExpiry && new Date(form.licenseExpiry) < new Date()) {
        errors.licenseExpiry = 'License cannot be expired'
        isValid = false
    }

    // Vehicle year validation
    if (form.vehicleYear && (form.vehicleYear < 1990 || form.vehicleYear > new Date().getFullYear() + 1)) {
        errors.vehicleYear = 'Invalid vehicle year'
        isValid = false
    }

    return isValid
}

// Real-time password confirmation validation
const validatePasswordConfirmation = () => {
    if (form.confirmPassword && form.password !== form.confirmPassword) {
        errors.confirmPassword = 'Passwords do not match'
    } else {
        errors.confirmPassword = ''
    }
}

const register = async () => {
    if (!validateForm()) return

    loading.value = true
    error.value = ''

    try {
        const response = await fetch('/api/v1/driver/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(form)
        })

        const data = await response.json()

        if (response.ok) {
            // Store token and redirect
            localStorage.setItem('driver_token', data.data.token)
            localStorage.setItem('driver_profile', JSON.stringify(data.data.user))
            
            // Redirect to driver app
            router.visit('/driver')
        } else {
            if (data.errors) {
                // Set field-specific errors
                Object.keys(data.errors).forEach(field => {
                    if (errors.hasOwnProperty(field)) {
                        errors[field] = data.errors[field][0]
                    }
                })
            } else {
                error.value = data.message || 'Registration failed. Please try again.'
            }
        }
    } catch (err) {
        console.error('Registration error:', err)
        error.value = 'Network error. Please check your connection and try again.'
    } finally {
        loading.value = false
    }
}
</script>
