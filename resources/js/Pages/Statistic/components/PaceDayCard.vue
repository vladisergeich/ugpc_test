<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-blue-600">{{ title }}</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Текущий темп:</span>
                <span class="text-2xl font-bold text-blue-600">{{ currentPace }}/день</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Требуемый темп:</span>
                <span class="text-xl font-bold text-gray-700">{{ requiredPace }}/день</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Отклонение:</span>
                <span class="text-xl font-bold" :class="deviationClass">{{ deviation }}</span>
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
    plan: {
        type: Number,
        required: true,
    },
    fact: {
        type: Number,
        required: true,
    },
})

const currentDate = new Date()
const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate()
const currentDay = currentDate.getDate()

const currentPace = computed(() => {
    if (currentDay === 0) return 0
    return Math.round(props.fact / currentDay)
})

const requiredPace = computed(() => {
    if (daysInMonth === 0) return 0
    return Math.round(props.plan / daysInMonth)
})

const deviation = computed(() => {
    const diff = currentPace.value - requiredPace.value
    return diff >= 0 ? `+${diff}` : `${diff}`
})

const deviationClass = computed(() => {
    const diff = currentPace.value - requiredPace.value
    if (diff > 0) return 'text-green-600'
    if (diff < 0) return 'text-red-600'
    return 'text-gray-600'
})
</script>