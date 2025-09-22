<template>
    <div class="bg-white p-4 rounded-lg shadow-md">
        <h4 class="text-md font-medium text-gray-800 mb-3">Статистика по машинам</h4>
        <div class="h-64 flex items-center justify-center text-gray-500">
            <div class="text-center">
                <i class="pi pi-chart-bar text-4xl mb-2"></i>
                <p>График статистики по машинам</p>
                <p class="text-sm">{{ data.length }} операций</p>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
})
</script>