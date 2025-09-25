<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Weather & Logistics Test</h1>
            
            <div class="bg-green-100 border border-green-300 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-green-800 mb-2">✅ Component is Working!</h2>
                <p class="text-green-700">This is a test to verify that the Vue component is loading correctly.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Enhanced Features Available:</h3>
                <ul class="space-y-2">
                    <li class="flex items-center text-gray-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Location Dropdowns - Select from Master Data locations
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Real Weather Data - OpenWeather API integration
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Route Intelligence - Safety & Efficiency scores
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Smart Recommendations - AI-powered route suggestions
                    </li>
                    <li class="flex items-center text-gray-700">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Weather Impact Analysis - Real-time weather impact assessment
                    </li>
                </ul>
            </div>

            <div class="mt-6">
                <a href="/weather-logistics" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Go to Full Weather & Logistics Page
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WeatherTest'
};
</script>
