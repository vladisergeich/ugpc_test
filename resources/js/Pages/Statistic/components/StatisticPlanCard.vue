<template>
    <div class="p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">{{ title }}</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm">План:</span>
                <span class="text-xl font-bold">{{ plan }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm">Факт:</span>
                <span class="text-xl font-bold">{{ fact }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm">Выполнение:</span>
                <span class="text-xl font-bold" :class="progressClass">{{ progressPercent }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="h-2 rounded-full transition-all duration-300"
                    :class="progressBarClass"
                    :style="{ width: Math.min(progressPercent, 100) + '%' }"
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
    plan: {
        type: Number,
        required: true,
    },
    fact: {
        type: Number,
        required: true,
    },
})

const progressPercent = computed(() => {
    if (props.plan === 0) return 0
    return Math.round((props.fact / props.plan) * 100)
})

const progressClass = computed(() => {
    const percent = progressPercent.value
    if (percent >= 100) return 'text-green-600'
    if (percent >= 80) return 'text-yellow-600'
    return 'text-red-600'
})

const progressBarClass = computed(() => {
    const percent = progressPercent.value
    if (percent >= 100) return 'bg-green-500'
    if (percent >= 80) return 'bg-yellow-500'
    return 'bg-red-500'
})
</script>