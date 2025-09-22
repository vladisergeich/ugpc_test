<template>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Операция:</label>
                <Select
                    v-if="operations"
                    placeholder="Выберите операцию"
                    v-model="selectOperation"
                    :options="operations"
                    option-label="description"
                    option-value="id"
                    multiple
                    class="w-full"
                />
            </div>
            
            <div v-if="selectOperation.length > 0" class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <StatisticByMcChart :data="filteredData" />
                    <StatisticByMcShiftChart :data="filteredData" />
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <StatisticByMcAverage
                        :data="filteredData"
                        title="Среднее кол-во ПЦ за смену"
                        unit="шт"
                    />
                    <StatisticByMcAverage
                        :data="filteredData"
                        title="Среднее время работы на 1 ПЦ (все операции)"
                        unit="мин"
                    />
                </div>
                
                <StatisticByMcWorkTime 
                    :operations="selectOperation" 
                    :machines="machines" 
                />
                
                <StatisticByMcOperatorsTable 
                    :operations="selectOperation" 
                    :machines="machines"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Select } from '@danaflex/ui/components'
import StatisticByMcChart from '../components/StatisticByMcChart.vue'
import StatisticByMcShiftChart from '../components/StatisticByMcShiftChart.vue'
import StatisticByMcAverage from '../components/StatisticByMcAverage.vue'
import StatisticByMcWorkTime from '../components/StatisticByMcWorkTime.vue'
import StatisticByMcOperatorsTable from '../components/StatisticByMcOperatorsTable.vue'
import { route as routeZiggy } from 'ziggy-js'
import { Ziggy } from '@/ziggy.js'
import axios from 'axios'

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    plan: {
        type: Array,
        required: true,
    },
    workCenters: {
        type: Array,
        required: true,
    },
})

const selectOperation = ref([])
const operations = ref([])
const machines = computed(() => props.workCenters.flatMap(wc => wc.machines))

const filteredData = computed(() => {
    if (!selectOperation.value || selectOperation.value.length === 0) return props.data
    return props.data.filter((op) =>
        selectOperation.value.includes(op.operation_id)
    )
})

const fetchOperations = async () => {
    try {
        const response = await axios.get(routeZiggy('statistic.getOperations', {}, undefined, Ziggy), {
            params: {
                machines: machines.value.map(m => m.machine),
            }
        })
        operations.value = response.data.operations
    } catch (error) {
        console.error('Ошибка при загрузке операций:', error)
    }
}

watch(() => machines.value, fetchOperations, { immediate: true, deep: true })

onMounted(() => {
    fetchOperations()
})
</script>