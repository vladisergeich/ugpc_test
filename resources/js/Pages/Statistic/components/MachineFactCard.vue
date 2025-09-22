<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ title }}</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Валов выпущено:</span>
                <span class="text-xl font-bold text-blue-600">{{ shaftCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Среднее время:</span>
                <span class="text-lg font-semibold text-gray-700">{{ averageTime }} мин</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Загрузка:</span>
                <span class="text-lg font-semibold" :class="loadClass">{{ loadPercent }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="h-2 rounded-full transition-all duration-300"
                    :class="loadBarClass"
                    :style="{ width: Math.min(loadPercent, 100) + '%' }"
                ></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
})

const shaftCount = computed(() => props.data.length)

const averageTime = computed(() => {
    if (props.data.length === 0) return 0
    const totalTime = props.data.reduce((sum, item) => {
        const start = new Date(`1970-01-01T${item.start_time}`)
        const end = new Date(`1970-01-01T${item.end_time}`)
        return sum + (end - start) / 60000 // в минутах
    }, 0)
    return Math.round(totalTime / props.data.length)
})

const loadPercent = computed(() => {
    // Примерный расчет загрузки на основе количества операций
    const maxOperations = 100 // максимальное количество операций в день
    return Math.min(Math.round((shaftCount.value / maxOperations) * 100), 100)
})

const loadClass = computed(() => {
    const load = loadPercent.value
    if (load >= 80) return 'text-green-600'
    if (load >= 60) return 'text-yellow-600'
    return 'text-red-600'
})

const loadBarClass = computed(() => {
    const load = loadPercent.value
    if (load >= 80) return 'bg-green-500'
    if (load >= 60) return 'bg-yellow-500'
    return 'bg-red-500'
})
</script>