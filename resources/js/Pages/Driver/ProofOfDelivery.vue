<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-blue-600 text-white shadow-lg">
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <button @click="goBack" class="p-2 rounded-lg hover:bg-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold">Proof of Delivery</h1>
                    <div class="w-8"></div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 space-y-6">
            <!-- Task Info -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h2 class="text-lg font-semibold mb-3">{{ task?.order_number }}</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">From:</span> {{ task?.pickup_location?.name }}</p>
                    <p><span class="font-medium">To:</span> {{ task?.delivery_location?.name }}</p>
                    <p><span class="font-medium">Address:</span> {{ task?.delivery_location?.address }}</p>
                </div>
            </div>

            <!-- Photo Capture -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h3 class="text-lg font-semibold mb-3">📸 Delivery Photo</h3>
                
                <div v-if="!deliveryPhoto" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <input
                        ref="photoInput"
                        type="file"
                        accept="image/*"
                        capture="environment"
                        @change="capturePhoto"
                        class="hidden"
                    >
                    <button
                        @click="$refs.photoInput.click()"
                        class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors"
                    >
                        📷 Take Photo
                    </button>
                    <p class="text-sm text-gray-600 mt-2">Required: Photo of delivered package</p>
                </div>

                <div v-else class="space-y-3">
                    <div class="relative">
                        <img :src="deliveryPhoto" alt="Delivery photo" class="w-full h-64 object-cover rounded-lg">
                        <button
                            @click="retakePhoto"
                            class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-green-600">✓ Photo captured successfully</p>
                </div>
            </div>

            <!-- Signature -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h3 class="text-lg font-semibold mb-3">✍️ Recipient Signature</h3>
                
                <div v-if="!signature" class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600">Have the recipient sign below</p>
                    </div>
                    
                    <canvas
                        ref="signatureCanvas"
                        @mousedown="startDrawing"
                        @mousemove="draw"
                        @mouseup="stopDrawing"
                        @touchstart="startDrawing"
                        @touchmove="draw"
                        @touchend="stopDrawing"
                        class="w-full h-32 border border-gray-300 rounded cursor-crosshair"
                        style="touch-action: none;"
                    ></canvas>
                    
                    <div class="flex space-x-2 mt-4">
                        <button
                            @click="clearSignature"
                            class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors"
                        >
                            Clear
                        </button>
                        <button
                            @click="saveSignature"
                            :disabled="!hasSignature"
                            class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Save Signature
                        </button>
                    </div>
                </div>

                <div v-else class="space-y-3">
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <img :src="signature" alt="Recipient signature" class="max-w-full h-20 object-contain">
                    </div>
                    <button
                        @click="clearSignature"
                        class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors"
                    >
                        Resign
                    </button>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="bg-white rounded-lg shadow-sm border p-4">
                <h3 class="text-lg font-semibold mb-3">📋 Delivery Details</h3>
                
                <div class="space-y-4">
                    <!-- Recipient Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Name</label>
                        <input
                            v-model="deliveryDetails.recipientName"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter recipient name"
                        >
                    </div>

                    <!-- Recipient Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Phone</label>
                        <input
                            v-model="deliveryDetails.recipientPhone"
                            type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter recipient phone"
                        >
                    </div>

                    <!-- Delivery Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Notes</label>
                        <textarea
                            v-model="deliveryDetails.notes"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Any additional notes about the delivery..."
                        ></textarea>
                    </div>

                    <!-- Delivery Confirmed -->
                    <div class="flex items-center">
                        <input
                            v-model="deliveryDetails.confirmed"
                            type="checkbox"
                            id="confirmed"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="confirmed" class="ml-2 text-sm text-gray-700">
                            I confirm that the package was delivered successfully
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pb-4">
                <button
                    @click="submitProofOfDelivery"
                    :disabled="!canSubmit"
                    class="w-full bg-green-600 text-white py-4 px-6 rounded-lg font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="submitting" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Submitting...
                    </span>
                    <span v-else>✅ Complete Delivery</span>
                </button>
            </div>
        </main>

        <!-- Success Modal -->
        <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-sm w-full p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delivery Complete!</h3>
                <p class="text-gray-600 mb-4">Proof of delivery has been submitted successfully.</p>
                <button
                    @click="goBackToTasks"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors"
                >
                    Back to Tasks
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
    taskId: {
        type: [String, Number],
        required: true
    }
})

// Reactive data
const task = ref(null)
const deliveryPhoto = ref(null)
const signature = ref(null)
const signatureCanvas = ref(null)
const isDrawing = ref(false)
const submitting = ref(false)
const showSuccessModal = ref(false)

const deliveryDetails = ref({
    recipientName: '',
    recipientPhone: '',
    notes: '',
    confirmed: false
})

// Computed properties
const hasSignature = computed(() => {
    if (!signatureCanvas.value) return false
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)
    return imageData.data.some(channel => channel !== 0)
})

const canSubmit = computed(() => {
    return deliveryPhoto.value && 
           signature.value && 
           deliveryDetails.value.recipientName && 
           deliveryDetails.value.confirmed
})

// Methods
const loadTask = async () => {
    try {
        const response = await fetch(`/api/v1/driver/tasks/${props.taskId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Accept': 'application/json'
            }
        })
        
        if (response.ok) {
            const data = await response.json()
            task.value = data.data
        }
    } catch (error) {
        console.error('Error loading task:', error)
    }
}

const capturePhoto = (event) => {
    const file = event.target.files[0]
    if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
            deliveryPhoto.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

const retakePhoto = () => {
    deliveryPhoto.value = null
}

const startDrawing = (event) => {
    isDrawing.value = true
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    const rect = canvas.getBoundingClientRect()
    
    ctx.strokeStyle = '#000000'
    ctx.lineWidth = 2
    ctx.lineCap = 'round'
    
    const x = (event.clientX || event.touches[0].clientX) - rect.left
    const y = (event.clientY || event.touches[0].clientY) - rect.top
    
    ctx.beginPath()
    ctx.moveTo(x, y)
}

const draw = (event) => {
    if (!isDrawing.value) return
    
    event.preventDefault()
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    const rect = canvas.getBoundingClientRect()
    
    const x = (event.clientX || event.touches[0].clientX) - rect.left
    const y = (event.clientY || event.touches[0].clientY) - rect.top
    
    ctx.lineTo(x, y)
    ctx.stroke()
}

const stopDrawing = () => {
    isDrawing.value = false
}

const clearSignature = () => {
    if (signatureCanvas.value) {
        const canvas = signatureCanvas.value
        const ctx = canvas.getContext('2d')
        ctx.clearRect(0, 0, canvas.width, canvas.height)
    }
    signature.value = null
}

const saveSignature = () => {
    if (signatureCanvas.value && hasSignature.value) {
        signature.value = signatureCanvas.value.toDataURL('image/png')
    }
}

const submitProofOfDelivery = async () => {
    if (!canSubmit.value) return
    
    submitting.value = true
    
    try {
        const formData = new FormData()
        
        // Convert photo to blob
        if (deliveryPhoto.value) {
            const photoBlob = await dataURLToBlob(deliveryPhoto.value)
            formData.append('delivery_photo', photoBlob, 'delivery_photo.jpg')
        }
        
        // Add signature if available
        if (signature.value) {
            formData.append('signature', signature.value)
        }
        
        // Add other details
        formData.append('recipient_name', deliveryDetails.value.recipientName)
        formData.append('recipient_phone', deliveryDetails.value.recipientPhone)
        formData.append('delivery_notes', deliveryDetails.value.notes)
        formData.append('delivery_confirmed', deliveryDetails.value.confirmed)
        
        const response = await fetch(`/api/v1/driver/tasks/${props.taskId}/proof-of-delivery`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Accept': 'application/json'
            },
            body: formData
        })
        
        if (response.ok) {
            showSuccessModal.value = true
        } else {
            const error = await response.json()
            alert('Error submitting proof of delivery: ' + (error.message || 'Unknown error'))
        }
    } catch (error) {
        console.error('Error submitting proof of delivery:', error)
        alert('Network error. Please try again.')
    } finally {
        submitting.value = false
    }
}

const dataURLToBlob = (dataURL) => {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')
        const img = new Image()
        
        img.onload = () => {
            canvas.width = img.width
            canvas.height = img.height
            ctx.drawImage(img, 0, 0)
            canvas.toBlob(resolve, 'image/jpeg', 0.8)
        }
        
        img.src = dataURL
    })
}

const goBack = () => {
    router.visit('/driver')
}

const goBackToTasks = () => {
    showSuccessModal.value = false
    router.visit('/driver')
}

// Lifecycle
onMounted(() => {
    loadTask()
    
    // Set up signature canvas
    if (signatureCanvas.value) {
        const canvas = signatureCanvas.value
        const ctx = canvas.getContext('2d')
        ctx.fillStyle = 'white'
        ctx.fillRect(0, 0, canvas.width, canvas.height)
    }
})
</script>
