<template>
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h4 class="text-md font-medium text-gray-800 mb-3">Статистика по сменам</h4>
        <div class="space-y-3">
            <div v-for="shift in shiftStats" :key="shift.letter" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                <span class="text-sm font-medium text-gray-700">Смена {{ shift.letter }}</span>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">{{ shift.count }} операций</span>
                    <div class="w-16 bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-blue-500 h-2 rounded-full" 
                            :style="{ width: (shift.count / maxShiftCount * 100) + '%' }"
                        ></div>
                    </div>
                </div>
            </div>
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
})

const shiftStats = computed(() => {
    const stats = props.data.reduce((acc, item) => {
        const letter = item.letter || 'Неизвестно'
        acc[letter] = (acc[letter] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(stats)
        .map(([letter, count]) => ({ letter, count }))
        .sort((a, b) => a.letter.localeCompare(b.letter))
})

const maxShiftCount = computed(() => {
    return Math.max(...shiftStats.value.map(s => s.count), 1)
})
</script>