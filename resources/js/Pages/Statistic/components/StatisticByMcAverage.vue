<template>
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h4 class="text-md font-medium text-gray-800 mb-3">{{ title }}</h4>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ averageValue }}</div>
            <div class="text-sm text-gray-600">{{ unit }}</div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    unit: {
        type: String,
        required: true,
    },
})

const averageValue = computed(() => {
    if (props.data.length === 0) return 0
    
    if (props.unit === 'шт') {
        // Среднее количество
        return Math.round(props.data.length / 30) // примерно за месяц
    } else {
        // Среднее время
        const totalTime = props.data.reduce((sum, item) => {
            const start = new Date(`1970-01-01T${item.start_time}`)
            const end = new Date(`1970-01-01T${item.end_time}`)
            return sum + (end - start) / 60000
        }, 0)
        return Math.round(totalTime / props.data.length)
    }
})
</script>