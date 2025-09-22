<template>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ title }}</h3>
        <DataTable 
            :value="tableData" 
            class="w-full"
            size="small"
            responsive-layout="scroll"
        >
            <Column field="date" header="Дата" :sortable="true">
                <template #body="{ data }">
                    {{ formatDate(data.date) }}
                </template>
            </Column>
            <Column field="plan" header="План" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold text-blue-600">{{ data.plan }}</span>
                </template>
            </Column>
            <Column field="fact" header="Факт" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold" :class="getFactClass(data.fact, data.plan)">
                        {{ data.fact }}
                    </span>
                </template>
            </Column>
            <Column field="percentage" header="%" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold" :class="getPercentageClass(data.percentage)">
                        {{ data.percentage }}%
                    </span>
                </template>
            </Column>
            <Column field="deviation" header="Отклонение" :sortable="true">
                <template #body="{ data }">
                    <span class="font-semibold" :class="getDeviationClass(data.deviation)">
                        {{ data.deviation > 0 ? '+' : '' }}{{ data.deviation }}
                    </span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { DataTable, Column } from '@danaflex/ui/components'

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    plan: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
})

const tableData = computed(() => {
    const result = []
    const planByDate = props.plan.reduce((acc, item) => {
        const date = item.start_date
        acc[date] = (acc[date] || 0) + item.plan_qty
        return acc
    }, {})

    const factByDate = props.data.reduce((acc, item) => {
        const date = item.posting_date
        acc[date] = (acc[date] || 0) + 1
        return acc
    }, {})

    const allDates = [...new Set([...Object.keys(planByDate), ...Object.keys(factByDate)])]
    
    allDates.forEach(date => {
        const plan = planByDate[date] || 0
        const fact = factByDate[date] || 0
        const percentage = plan > 0 ? Math.round((fact / plan) * 100) : 0
        const deviation = fact - plan

        result.push({
            date,
            plan,
            fact,
            percentage,
            deviation
        })
    })

    return result.sort((a, b) => new Date(a.date) - new Date(b.date))
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU')
}

const getFactClass = (fact, plan) => {
    if (fact >= plan) return 'text-green-600'
    if (fact >= plan * 0.8) return 'text-yellow-600'
    return 'text-red-600'
}

const getPercentageClass = (percentage) => {
    if (percentage >= 100) return 'text-green-600'
    if (percentage >= 80) return 'text-yellow-600'
    return 'text-red-600'
}

const getDeviationClass = (deviation) => {
    if (deviation > 0) return 'text-green-600'
    if (deviation < 0) return 'text-red-600'
    return 'text-gray-600'
}
</script>