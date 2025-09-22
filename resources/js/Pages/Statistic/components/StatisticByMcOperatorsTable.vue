<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h4 class="text-lg font-medium text-gray-800 mb-4">Статистика по операторам</h4>
        <DataTable 
            :value="tableData" 
            :loading="isLoading"
            class="w-full"
            size="small"
            responsive-layout="scroll"
        >
            <Column field="user_name" header="Оператор" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold">{{ data.user_name || 'Неизвестный' }}</span>
                </template>
            </Column>
            <Column field="totalShaft" header="Валов" :sortable="true">
                <template #body="{ data }">
                    <span class="text-blue-600 font-semibold">{{ data.totalShaft }}</span>
                </template>
            </Column>
            <Column field="avgTimePerUnit" header="Среднее время" :sortable="true">
                <template #body="{ data }">
                    <span class="text-green-600">{{ data.avgTimePerUnit }} мин</span>
                </template>
            </Column>
            <Column field="usefulTime" header="Полезное время" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold" :class="getUsefulTimeClass(data.usefulTime)">
                        {{ data.usefulTime }}%
                    </span>
                </template>
            </Column>
            <Column field="operationsCount" header="Операций" :sortable="true">
                <template #body="{ data }">
                    <span class="text-purple-600">{{ data.operationsCount }}</span>
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
const fetchTimeout = ref(null)

const debouncedFetchData = () => {
    clearTimeout(fetchTimeout.value)
    fetchTimeout.value = setTimeout(() => {
        fetchTableData()
    }, 500)
}

const fetchTableData = async () => {
    if (!props.operations.length || !props.machines.length) return
    
    try {
        isLoading.value = true
        const response = await axios.get(routeZiggy('statistic.getOperatorData', {}, undefined, Ziggy), {
            params: {
                filters: JSON.stringify({ dates: ['2024-01-01', '2024-12-31'] }),
                operations: props.operations,
                machines: props.machines.map(m => m.machine),
            }
        })
        
        tableData.value = response.data.map(operator => {
            const operations = Object.values(operator.operations || {})
            const avgTimePerUnit = operations.length > 0 
                ? Math.round(operations.reduce((sum, op) => sum + (op.avgTimePerUnit || 0), 0) / operations.length)
                : 0
            const usefulTime = operations.length > 0
                ? Math.round(operations.reduce((sum, op) => sum + (op.usefulTime || 0), 0) / operations.length)
                : 0
            
            return {
                user_name: operator.user_name,
                totalShaft: operator.totalShaft || 0,
                avgTimePerUnit,
                usefulTime,
                operationsCount: operations.length
            }
        })
    } catch (error) {
        console.error('Ошибка при загрузке данных операторов:', error)
    } finally {
        isLoading.value = false
    }
}

const getUsefulTimeClass = (usefulTime) => {
    if (usefulTime >= 80) return 'text-green-600'
    if (usefulTime >= 60) return 'text-yellow-600'
    return 'text-red-600'
}

watch(() => [props.operations, props.machines], debouncedFetchData, { immediate: true, deep: true })
</script>