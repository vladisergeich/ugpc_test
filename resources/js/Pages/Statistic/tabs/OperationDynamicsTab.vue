<template>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Динамика по количеству операций</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-blue-800 mb-2">Общая статистика</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Всего операций:</span>
                            <span class="font-semibold text-blue-600">{{ totalOperations }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Уникальных валов:</span>
                            <span class="font-semibold text-blue-600">{{ uniqueShafts }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Среднее время операции:</span>
                            <span class="font-semibold text-blue-600">{{ averageOperationTime }} мин</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-green-800 mb-2">По сменам</h4>
                    <div class="space-y-2">
                        <div v-for="shift in shiftStats" :key="shift.letter" class="flex justify-between">
                            <span class="text-sm text-gray-600">Смена {{ shift.letter }}:</span>
                            <span class="font-semibold text-green-600">{{ shift.count }} операций</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <DataTable 
                :value="operationsByDate" 
                class="w-full"
                size="small"
                responsive-layout="scroll"
            >
                <Column field="date" header="Дата" :sortable="true">
                    <template #body="{ data }">
                        {{ formatDate(data.date) }}
                    </template>
                </Column>
                <Column field="operationsCount" header="Операций" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-blue-600">{{ data.operationsCount }}</span>
                    </template>
                </Column>
                <Column field="shaftsCount" header="Валов" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-green-600">{{ data.shaftsCount }}</span>
                    </template>
                </Column>
                <Column field="averageTime" header="Среднее время" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-gray-700">{{ data.averageTime }} мин</span>
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
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { DataTable, Column } from '@danaflex/ui/components'

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
})

const totalOperations = computed(() => props.data.length)

const uniqueShafts = computed(() => {
    const shaftIds = new Set(props.data.map(op => op.engraving_order_id))
    return shaftIds.size
})

const averageOperationTime = computed(() => {
    if (props.data.length === 0) return 0
    const totalTime = props.data.reduce((sum, item) => {
        const start = new Date(`1970-01-01T${item.start_time}`)
        const end = new Date(`1970-01-01T${item.end_time}`)
        return sum + (end - start) / 60000 // в минутах
    }, 0)
    return Math.round(totalTime / props.data.length)
})

const shiftStats = computed(() => {
    const stats = props.data.reduce((acc, item) => {
        const letter = item.letter
        acc[letter] = (acc[letter] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(stats).map(([letter, count]) => ({
        letter,
        count
    })).sort((a, b) => a.letter.localeCompare(b.letter))
})

const operationsByDate = computed(() => {
    const dateStats = props.data.reduce((acc, item) => {
        const date = item.date
        if (!acc[date]) {
            acc[date] = {
                date,
                operations: [],
                shafts: new Set()
            }
        }
        acc[date].operations.push(item)
        acc[date].shafts.add(item.engraving_order_id)
        return acc
    }, {})
    
    return Object.values(dateStats).map(day => {
        const avgTime = day.operations.reduce((sum, item) => {
            const start = new Date(`1970-01-01T${item.start_time}`)
            const end = new Date(`1970-01-01T${item.end_time}`)
            return sum + (end - start) / 60000
        }, 0) / day.operations.length
        
        const targetTime = 60 // целевое время на операцию в минутах
        const efficiency = Math.round((targetTime / avgTime) * 100)
        
        return {
            date: day.date,
            operationsCount: day.operations.length,
            shaftsCount: day.shafts.size,
            averageTime: Math.round(avgTime),
            efficiency: efficiency > 200 ? 200 : efficiency // ограничиваем максимум
        }
    }).sort((a, b) => new Date(a.date) - new Date(b.date))
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

const getEfficiencyClass = (efficiency) => {
    if (efficiency >= 100) return 'text-green-600'
    if (efficiency >= 80) return 'text-yellow-600'
    return 'text-red-600'
}
</script>