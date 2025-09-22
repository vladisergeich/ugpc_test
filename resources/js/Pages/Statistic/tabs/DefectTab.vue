<template>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-red-600">Статистика брака</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-red-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-red-800 mb-2">Общий брак</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ totalDefects }}</div>
                        <div class="text-sm text-gray-600">случаев</div>
                    </div>
                </div>
                
                <div class="bg-orange-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-orange-800 mb-2">% от общего</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ defectPercentage }}%</div>
                        <div class="text-sm text-gray-600">от всех операций</div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-yellow-800 mb-2">Тренд</h4>
                    <div class="text-center">
                        <div class="text-2xl font-bold" :class="trendClass">
                            {{ trendDirection }}{{ Math.abs(trendValue) }}%
                        </div>
                        <div class="text-sm text-gray-600">за последние дни</div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Брак по операциям</h4>
                    <div class="space-y-2">
                        <div v-for="defect in defectsByOperation" :key="defect.operation" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ defect.operation }}</span>
                            <span class="font-semibold text-red-600">{{ defect.count }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Брак по машинам</h4>
                    <div class="space-y-2">
                        <div v-for="defect in defectsByMachine" :key="defect.machine" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ defect.machine }}</span>
                            <span class="font-semibold text-red-600">{{ defect.count }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <DataTable 
                :value="defectsByDate" 
                class="w-full"
                size="small"
                responsive-layout="scroll"
            >
                <Column field="date" header="Дата" :sortable="true">
                    <template #body="{ data }">
                        {{ formatDate(data.date) }}
                    </template>
                </Column>
                <Column field="defectCount" header="Количество брака" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-red-600">{{ data.defectCount }}</span>
                    </template>
                </Column>
                <Column field="totalOperations" header="Всего операций" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-blue-600">{{ data.totalOperations }}</span>
                    </template>
                </Column>
                <Column field="percentage" header="% брака" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold" :class="getDefectPercentageClass(data.percentage)">
                            {{ data.percentage }}%
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
    workCenters: {
        type: Array,
        required: true,
    },
})

const defectOperations = computed(() => 
    props.data.filter(op => op.operation_id === 68 || op.operation?.type === 'defect')
)

const totalDefects = computed(() => defectOperations.value.length)

const defectPercentage = computed(() => {
    if (props.data.length === 0) return 0
    return Math.round((totalDefects.value / props.data.length) * 100)
})

const defectsByOperation = computed(() => {
    const stats = defectOperations.value.reduce((acc, item) => {
        const operation = item.operation?.description || 'Неизвестная операция'
        acc[operation] = (acc[operation] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(stats)
        .map(([operation, count]) => ({ operation, count }))
        .sort((a, b) => b.count - a.count)
})

const defectsByMachine = computed(() => {
    const stats = defectOperations.value.reduce((acc, item) => {
        const machine = item.machine?.machine || 'Неизвестная машина'
        acc[machine] = (acc[machine] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(stats)
        .map(([machine, count]) => ({ machine, count }))
        .sort((a, b) => b.count - a.count)
})

const defectsByDate = computed(() => {
    const dateStats = props.data.reduce((acc, item) => {
        const date = item.date
        if (!acc[date]) {
            acc[date] = {
                date,
                defectCount: 0,
                totalOperations: 0
            }
        }
        acc[date].totalOperations++
        if (item.operation_id === 68 || item.operation?.type === 'defect') {
            acc[date].defectCount++
        }
        return acc
    }, {})
    
    return Object.values(dateStats).map(day => ({
        ...day,
        percentage: day.totalOperations > 0 ? Math.round((day.defectCount / day.totalOperations) * 100) : 0
    })).sort((a, b) => new Date(a.date) - new Date(b.date))
})

const trendValue = computed(() => {
    const recent = defectsByDate.value.slice(-7) // последние 7 дней
    const older = defectsByDate.value.slice(-14, -7) // предыдущие 7 дней
    
    if (recent.length === 0 || older.length === 0) return 0
    
    const recentAvg = recent.reduce((sum, day) => sum + day.percentage, 0) / recent.length
    const olderAvg = older.reduce((sum, day) => sum + day.percentage, 0) / older.length
    
    return Math.round(recentAvg - olderAvg)
})

const trendDirection = computed(() => trendValue.value >= 0 ? '+' : '')

const trendClass = computed(() => {
    if (trendValue.value > 0) return 'text-red-600'
    if (trendValue.value < 0) return 'text-green-600'
    return 'text-gray-600'
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

const getDefectPercentageClass = (percentage) => {
    if (percentage >= 10) return 'text-red-600'
    if (percentage >= 5) return 'text-yellow-600'
    return 'text-green-600'
}
</script>