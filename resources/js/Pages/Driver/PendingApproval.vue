<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <span class="text-white text-2xl font-bold">O</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Account Pending Approval</h1>
                <p class="text-gray-600">Your driver application is being reviewed</p>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Status Icon -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Application Under Review</h2>
                    <p class="text-gray-600">We're reviewing your driver application and documents</p>
                </div>

                <!-- Status Steps -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-700">Application submitted</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        </div>
                        <span class="text-sm text-gray-700">Background check in progress</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-gray-500">Document verification</span>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        </div>
                        <span class="text-sm text-gray-500">Final approval</span>
                    </div>
                </div>

                <!-- Driver Information -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Your Application Details</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ driverInfo.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ driverInfo.email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vehicle:</span>
                            <span class="font-medium">{{ driverInfo.vehicleYear }} {{ driverInfo.vehicleMake }} {{ driverInfo.vehicleModel }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">License:</span>
                            <span class="font-medium">{{ driverInfo.driverLicense }}</span>
                        </div>
                    </div>
                </div>

                <!-- Expected Timeline -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2">Expected Timeline</h3>
                    <p class="text-sm text-blue-700">
                        Most applications are reviewed within 2-3 business days. 
                        You'll receive an email notification once your application is approved.
                    </p>
                </div>

                <!-- Contact Information -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        Questions about your application?
                    </p>
                    <div class="flex space-x-3">
                        <button 
                            @click="contactSupport"
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            📞 Contact Support
                        </button>
                        <button 
                            @click="logout"
                            class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-sm text-gray-600">
                    Already have an approved account? 
                    <a href="/driver/login" class="text-blue-600 hover:text-blue-800 font-medium">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Reactive data
const driverInfo = ref({
    name: '',
    email: '',
    vehicleYear: '',
    vehicleMake: '',
    vehicleModel: '',
    driverLicense: ''
})

// Methods
const contactSupport = () => {
    // In a real app, this would open a support chat or email
    alert('Support contact: support@omnichain.com\nPhone: 1-800-OMNI-CHAIN')
}

const logout = () => {
    localStorage.removeItem('driver_token')
    localStorage.removeItem('driver_profile')
    router.visit('/driver/login')
}

// Lifecycle
onMounted(() => {
    // Load driver info from localStorage
    const profile = localStorage.getItem('driver_profile')
    if (profile) {
        const parsed = JSON.parse(profile)
        driverInfo.value = {
            name: parsed.name || '',
            email: parsed.email || '',
            vehicleYear: parsed.vehicle_year || '',
            vehicleMake: parsed.vehicle_make || '',
            vehicleModel: parsed.vehicle_model || '',
            driverLicense: parsed.driver_license || ''
        }
    }
})
</script>
