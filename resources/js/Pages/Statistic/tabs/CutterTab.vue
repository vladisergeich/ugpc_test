<template>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-purple-600">Статистика по резцам</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-purple-800 mb-2">Операций с резцами</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ totalCutterOperations }}</div>
                        <div class="text-sm text-gray-600">операций</div>
                    </div>
                </div>
                
                <div class="bg-indigo-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-indigo-800 mb-2">Уникальных резцов</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ uniqueCutters }}</div>
                        <div class="text-sm text-gray-600">резцов</div>
                    </div>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-blue-800 mb-2">Ресурс резцов</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ averageCutterLife }}</div>
                        <div class="text-sm text-gray-600">операций/резец</div>
                    </div>
                </div>
                
                <div class="bg-cyan-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-cyan-800 mb-2">Эффективность</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold" :class="efficiencyClass">{{ cutterEfficiency }}%</div>
                        <div class="text-sm text-gray-600">использования</div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Топ резцов по использованию</h4>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <div v-for="cutter in topCutters" :key="cutter.cutter" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ cutter.cutter }}</span>
                            <span class="font-semibold text-purple-600">{{ cutter.count }} операций</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Состояние резцов</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center p-2 bg-green-50 rounded">
                            <span class="text-sm text-gray-700">Хорошее состояние</span>
                            <span class="font-semibold text-green-600">{{ cutterCondition.good }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-yellow-50 rounded">
                            <span class="text-sm text-gray-700">Требует внимания</span>
                            <span class="font-semibold text-yellow-600">{{ cutterCondition.warning }}</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                            <span class="text-sm text-gray-700">Требует замены</span>
                            <span class="font-semibold text-red-600">{{ cutterCondition.critical }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <DataTable 
                :value="cutterStatistics" 
                class="w-full"
                size="small"
                responsive-layout="scroll"
            >
                <Column field="cutter" header="Резец" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-medium text-gray-900">{{ data.cutter }}</span>
                    </template>
                </Column>
                <Column field="operationsCount" header="Операций" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-purple-600">{{ data.operationsCount }}</span>
                    </template>
                </Column>
                <Column field="averageTime" header="Среднее время" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-gray-700">{{ data.averageTime }} мин</span>
                    </template>
                </Column>
                <Column field="lastUsed" header="Последнее использование" :sortable="true">
                    <template #body="{ data }">
                        <span class="text-sm text-gray-600">{{ formatDate(data.lastUsed) }}</span>
                    </template>
                </Column>
                <Column field="status" header="Статус" :sortable="true">
                    <template #body="{ data }">
                        <span class="px-2 py-1 rounded text-xs font-medium" :class="getStatusClass(data.status)">
                            {{ data.status }}
                        </span>
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

// Фильтруем операции, которые используют резцы
const cutterOperations = computed(() => 
    props.data.filter(op => op.cutter || op.operation?.description?.toLowerCase().includes('резец'))
)

const totalCutterOperations = computed(() => cutterOperations.value.length)

const uniqueCutters = computed(() => {
    const cutters = new Set(cutterOperations.value.map(op => op.cutter?.variable_body || 'Неизвестный').filter(c => c !== 'Неизвестный'))
    return cutters.size
})

const averageCutterLife = computed(() => {
    if (uniqueCutters.value === 0) return 0
    return Math.round(totalCutterOperations.value / uniqueCutters.value)
})

const cutterEfficiency = computed(() => {
    const maxOperationsPerCutter = 500 // максимальное количество операций на резец
    const currentAverage = averageCutterLife.value
    return Math.round((currentAverage / maxOperationsPerCutter) * 100)
})

const efficiencyClass = computed(() => {
    const efficiency = cutterEfficiency.value
    if (efficiency >= 80) return 'text-green-600'
    if (efficiency >= 60) return 'text-yellow-600'
    return 'text-red-600'
})

const topCutters = computed(() => {
    const cutterStats = cutterOperations.value.reduce((acc, item) => {
        const cutter = item.cutter?.variable_body || 'Неизвестный'
        if (cutter !== 'Неизвестный') {
            acc[cutter] = (acc[cutter] || 0) + 1
        }
        return acc
    }, {})
    
    return Object.entries(cutterStats)
        .map(([cutter, count]) => ({ cutter, count }))
        .sort((a, b) => b.count - a.count)
        .slice(0, 10) // топ 10
})

const cutterCondition = computed(() => {
    const condition = { good: 0, warning: 0, critical: 0 }
    
    topCutters.value.forEach(cutter => {
        if (cutter.count > 400) {
            condition.critical++
        } else if (cutter.count > 300) {
            condition.warning++
        } else {
            condition.good++
        }
    })
    
    return condition
})

const cutterStatistics = computed(() => {
    const cutterStats = cutterOperations.value.reduce((acc, item) => {
        const cutter = item.cutter?.variable_body || 'Неизвестный'
        if (cutter !== 'Неизвестный') {
            if (!acc[cutter]) {
                acc[cutter] = {
                    cutter,
                    operations: [],
                    lastUsed: item.date
                }
            }
            acc[cutter].operations.push(item)
            if (new Date(item.date) > new Date(acc[cutter].lastUsed)) {
                acc[cutter].lastUsed = item.date
            }
        }
        return acc
    }, {})
    
    return Object.values(cutterStats).map(cutterData => {
        const avgTime = cutterData.operations.reduce((sum, item) => {
            const start = new Date(`1970-01-01T${item.start_time}`)
            const end = new Date(`1970-01-01T${item.end_time}`)
            return sum + (end - start) / 60000
        }, 0) / cutterData.operations.length
        
        const operationsCount = cutterData.operations.length
        let status = 'Хорошее'
        if (operationsCount > 400) status = 'Требует замены'
        else if (operationsCount > 300) status = 'Требует внимания'
        
        const targetTime = 30 // целевое время для операции с резцом
        const efficiency = Math.round((targetTime / avgTime) * 100)
        
        return {
            cutter: cutterData.cutter,
            operationsCount,
            averageTime: Math.round(avgTime),
            lastUsed: cutterData.lastUsed,
            status,
            efficiency: efficiency > 200 ? 200 : efficiency
        }
    }).sort((a, b) => b.operationsCount - a.operationsCount)
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

const getStatusClass = (status) => {
    switch (status) {
        case 'Хорошее':
            return 'bg-green-100 text-green-800'
        case 'Требует внимания':
            return 'bg-yellow-100 text-yellow-800'
        case 'Требует замены':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getEfficiencyClass = (efficiency) => {
    if (efficiency >= 100) return 'text-green-600'
    if (efficiency >= 80) return 'text-yellow-600'
    return 'text-red-600'
}
</script>