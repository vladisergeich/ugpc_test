<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-red-600">{{ title }}</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Количество брака:</span>
                <span class="text-2xl font-bold text-red-600">{{ defectCount }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">% от общего:</span>
                <span class="text-xl font-bold text-red-600">{{ defectPercent }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="bg-red-500 h-2 rounded-full transition-all duration-300"
                    :style="{ width: Math.min(defectPercent, 100) + '%' }"
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
    fact: {
        type: Number,
        required: true,
    },
})

const defectCount = computed(() => props.data.length)

const defectPercent = computed(() => {
    if (props.fact === 0) return 0
    return Math.round((defectCount.value / props.fact) * 100)
})
</script>