<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-medium text-gray-800 mb-4">Время работы по сменам</h4>
        <DataTable 
            :value="tableData" 
            :loading="isLoading"
            class="w-full"
            size="small"
            responsive-layout="scroll"
        >
            <Column field="shift" header="Смена" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold">{{ data.shift }}</span>
                </template>
            </Column>
            <Column field="totalTime" header="Общее время" :sortable="true">
                <template #body="{ data }">
                    <span class="text-blue-600">{{ data.totalTime }} мин</span>
                </template>
            </Column>
            <Column field="averageTime" header="Среднее время" :sortable="true">
                <template #body="{ data }">
                    <span class="text-green-600">{{ data.averageTime }} мин</span>
                </template>
            </Column>
            <Column field="efficiency" header="Эффективность" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold" :class="getEfficiencyClass(data.efficiency)">
                        {{ data.efficiency }}%
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { DataTable, Column } from '@danaflex/ui/components'
import { route as routeZiggy } from 'ziggy-js'
import { Ziggy } from '@/ziggy.js'
import axios from 'axios'

const props = defineProps({
    operations: {
        type: Array,
        required: true,
    },
    machines: {
        type: Array,
        required: true,
    },
})

const tableData = ref([])
const isLoading = ref(false)

const fetchTableData = async () => {
    if (!props.operations.length || !props.machines.length) return
    
    try {
        isLoading.value = true
        const response = await axios.get(routeZiggy('statistic.getShiftData', {}, undefined, Ziggy), {
            params: {
                filters: JSON.stringify({ dates: ['2024-01-01', '2024-12-31'] }),
                operations: props.operations,
                machines: props.machines.map(m => m.machine),
            }
        })
        
        tableData.value = response.data.map(shift => ({
            shift: `Смена ${shift.letter}`,
            totalTime: shift.workTimeAll || 0,
            averageTime: Math.round((shift.workTimeAll || 0) / Math.max(Object.keys(shift.operations || {}).length, 1)),
            efficiency: Math.round(((shift.workTimeAll || 0) / 720) * 100) // 720 минут в смене
        }))
    } catch (error) {
        console.error('Ошибка при загрузке данных по сменам:', error)
    } finally {
        isLoading.value = false
    }
}

const getEfficiencyClass = (efficiency) => {
    if (efficiency >= 80) return 'text-green-600'
    if (efficiency >= 60) return 'text-yellow-600'
    return 'text-red-600'
}

watch(() => [props.operations, props.machines], fetchTableData, { immediate: true, deep: true })
</script>