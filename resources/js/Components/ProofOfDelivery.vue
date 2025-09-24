<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Proof of Delivery</h3>
                    <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mt-2">Order: {{ task.order_number }}</p>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Delivery Photo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Photo <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                        <div v-if="!deliveryPhoto" class="space-y-2">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-600">Take a photo of the delivered package</p>
                            <button 
                                @click="takePhoto"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors"
                            >
                                📷 Take Photo
                            </button>
                        </div>
                        <div v-else class="space-y-2">
                            <img :src="deliveryPhoto" alt="Delivery photo" class="w-full h-48 object-cover rounded-lg">
                            <button 
                                @click="retakePhoto"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Retake Photo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recipient Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recipient Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.recipientName"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter recipient name"
                        />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recipient Phone
                        </label>
                        <input
                            v-model="form.recipientPhone"
                            type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter recipient phone number"
                        />
                    </div>
                </div>

                <!-- Delivery Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Notes
                    </label>
                    <textarea
                        v-model="form.notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Any additional notes about the delivery..."
                    ></textarea>
                </div>

                <!-- Signature -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Digital Signature <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-gray-300 rounded-lg p-4">
                        <div v-if="!signature" class="text-center">
                            <p class="text-sm text-gray-600 mb-4">Please sign below</p>
                            <canvas 
                                ref="signatureCanvas"
                                @mousedown="startSignature"
                                @mousemove="drawSignature"
                                @mouseup="endSignature"
                                @touchstart="startSignature"
                                @touchmove="drawSignature"
                                @touchend="endSignature"
                                class="border border-gray-300 rounded cursor-crosshair"
                                width="300"
                                height="150"
                            ></canvas>
                            <button 
                                @click="clearSignature"
                                class="mt-2 text-sm text-gray-600 hover:text-gray-800"
                            >
                                Clear Signature
                            </button>
                        </div>
                        <div v-else class="text-center">
                            <p class="text-sm text-green-600 mb-2">✓ Signature captured</p>
                            <button 
                                @click="clearSignature"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Redo Signature
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Delivery Time
                    </label>
                    <input
                        v-model="form.deliveryTime"
                        type="datetime-local"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-200 flex space-x-3">
                <button 
                    @click="$emit('close')"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    @click="submitProof"
                    :disabled="!canSubmit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Submit Proof
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Props
const props = defineProps({
    task: {
        type: Object,
        required: true
    }
})

// Emits
const emit = defineEmits(['close', 'submitted'])

// Reactive data
const deliveryPhoto = ref(null)
const signature = ref(null)
const signatureCanvas = ref(null)
const isDrawing = ref(false)

const form = ref({
    recipientName: '',
    recipientPhone: '',
    notes: '',
    deliveryTime: new Date().toISOString().slice(0, 16)
})

// Computed
const canSubmit = computed(() => {
    return deliveryPhoto.value && 
           signature.value && 
           form.value.recipientName.trim() !== ''
})

// Methods
const takePhoto = () => {
    // Create file input for camera
    const input = document.createElement('input')
    input.type = 'file'
    input.accept = 'image/*'
    input.capture = 'environment' // Use back camera on mobile
    
    input.onchange = (e) => {
        const file = e.target.files[0]
        if (file) {
            const reader = new FileReader()
            reader.onload = (e) => {
                deliveryPhoto.value = e.target.result
            }
            reader.readAsDataURL(file)
        }
    }
    
    input.click()
}

const retakePhoto = () => {
    deliveryPhoto.value = null
    takePhoto()
}

const startSignature = (e) => {
    isDrawing.value = true
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    
    const rect = canvas.getBoundingClientRect()
    const x = (e.clientX || e.touches[0].clientX) - rect.left
    const y = (e.clientY || e.touches[0].clientY) - rect.top
    
    ctx.beginPath()
    ctx.moveTo(x, y)
}

const drawSignature = (e) => {
    if (!isDrawing.value) return
    
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    
    const rect = canvas.getBoundingClientRect()
    const x = (e.clientX || e.touches[0].clientX) - rect.left
    const y = (e.clientY || e.touches[0].clientY) - rect.top
    
    ctx.lineTo(x, y)
    ctx.stroke()
}

const endSignature = () => {
    if (isDrawing.value) {
        isDrawing.value = false
        const canvas = signatureCanvas.value
        signature.value = canvas.toDataURL()
    }
}

const clearSignature = () => {
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    signature.value = null
}

const submitProof = async () => {
    try {
        const proofData = {
            task_id: props.task.id,
            delivery_photo: deliveryPhoto.value,
            signature: signature.value,
            recipient_name: form.value.recipientName,
            recipient_phone: form.value.recipientPhone,
            notes: form.value.notes,
            delivery_time: form.value.deliveryTime
        }

        const response = await fetch(`/api/v1/driver/tasks/${props.task.id}/proof-of-delivery`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('driver_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(proofData)
        })

        if (response.ok) {
            emit('submitted', proofData)
            emit('close')
        } else {
            const error = await response.json()
            alert(`Failed to submit proof: ${error.message}`)
        }
    } catch (error) {
        console.error('Error submitting proof:', error)
        alert('Failed to submit proof. Please try again.')
    }
}

// Lifecycle
onMounted(() => {
    // Set up signature canvas
    const canvas = signatureCanvas.value
    const ctx = canvas.getContext('2d')
    ctx.strokeStyle = '#000'
    ctx.lineWidth = 2
    ctx.lineCap = 'round'
})
</script>
