<template>
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-orange-600">Статистика по меди</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-orange-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-orange-800 mb-2">Всего операций</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ totalCopperOperations }}</div>
                        <div class="text-sm text-gray-600">операций с медью</div>
                    </div>
                </div>
                
                <div class="bg-amber-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-amber-800 mb-2">Среднее время</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-amber-600">{{ averageCopperTime }}</div>
                        <div class="text-sm text-gray-600">минут на операцию</div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h4 class="text-md font-medium text-yellow-800 mb-2">Эффективность</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold" :class="efficiencyClass">{{ copperEfficiency }}%</div>
                        <div class="text-sm text-gray-600">от плановой нормы</div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Операции по толщине меди</h4>
                    <div class="space-y-2">
                        <div v-for="thickness in copperByThickness" :key="thickness.thickness" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ thickness.thickness }} мкм</span>
                            <span class="font-semibold text-orange-600">{{ thickness.count }} операций</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Тренд по дням</h4>
                    <div class="space-y-2">
                        <div v-for="day in recentDays" :key="day.date" class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ formatDate(day.date) }}</span>
                            <span class="font-semibold text-orange-600">{{ day.count }} операций</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <DataTable 
                :value="copperOperationsByDate" 
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
                        <span class="font-semibold text-orange-600">{{ data.operationsCount }}</span>
                    </template>
                </Column>
                <Column field="averageThickness" header="Средняя толщина" :sortable="true">
                    <template #body="{ data }">
                        <span class="font-semibold text-gray-700">{{ data.averageThickness }} мкм</span>
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

// Фильтруем операции, связанные с медью (например, медение, операции с толщиной меди)
const copperOperations = computed(() => 
    props.data.filter(op => 
        op.operation?.description?.toLowerCase().includes('мед') || 
        op.thickness || 
        op.operation?.work_center === 'EP' // Электрогальваника
    )
)

const totalCopperOperations = computed(() => copperOperations.value.length)

const averageCopperTime = computed(() => {
    if (copperOperations.value.length === 0) return 0
    const totalTime = copperOperations.value.reduce((sum, item) => {
        const start = new Date(`1970-01-01T${item.start_time}`)
        const end = new Date(`1970-01-01T${item.end_time}`)
        return sum + (end - start) / 60000 // в минутах
    }, 0)
    return Math.round(totalTime / copperOperations.value.length)
})

const copperEfficiency = computed(() => {
    const targetTime = 45 // целевое время для операций с медью в минутах
    if (averageCopperTime.value === 0) return 0
    return Math.round((targetTime / averageCopperTime.value) * 100)
})

const efficiencyClass = computed(() => {
    const efficiency = copperEfficiency.value
    if (efficiency >= 100) return 'text-green-600'
    if (efficiency >= 80) return 'text-yellow-600'
    return 'text-red-600'
})

const copperByThickness = computed(() => {
    const thicknessStats = copperOperations.value.reduce((acc, item) => {
        const thickness = item.thickness?.float_value || item.thickness?.string_value || 'Неизвестно'
        acc[thickness] = (acc[thickness] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(thicknessStats)
        .map(([thickness, count]) => ({ thickness, count }))
        .sort((a, b) => b.count - a.count)
        .slice(0, 5) // топ 5
})

const recentDays = computed(() => {
    const dateStats = copperOperations.value.reduce((acc, item) => {
        const date = item.date
        acc[date] = (acc[date] || 0) + 1
        return acc
    }, {})
    
    return Object.entries(dateStats)
        .map(([date, count]) => ({ date, count }))
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 7) // последние 7 дней
})

const copperOperationsByDate = computed(() => {
    const dateStats = copperOperations.value.reduce((acc, item) => {
        const date = item.date
        if (!acc[date]) {
            acc[date] = {
                date,
                operations: [],
                thicknesses: []
            }
        }
        acc[date].operations.push(item)
        if (item.thickness?.float_value) {
            acc[date].thicknesses.push(item.thickness.float_value)
        }
        return acc
    }, {})
    
    return Object.values(dateStats).map(day => {
        const avgTime = day.operations.reduce((sum, item) => {
            const start = new Date(`1970-01-01T${item.start_time}`)
            const end = new Date(`1970-01-01T${item.end_time}`)
            return sum + (end - start) / 60000
        }, 0) / day.operations.length
        
        const avgThickness = day.thicknesses.length > 0 
            ? day.thicknesses.reduce((sum, t) => sum + t, 0) / day.thicknesses.length 
            : 0
        
        const targetTime = 45
        const efficiency = Math.round((targetTime / avgTime) * 100)
        
        return {
            date: day.date,
            operationsCount: day.operations.length,
            averageThickness: Math.round(avgThickness),
            averageTime: Math.round(avgTime),
            efficiency: efficiency > 200 ? 200 : efficiency
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