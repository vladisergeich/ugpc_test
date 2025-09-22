<template>
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Статистика УГПЦ</h2>
        <div class="flex gap-6">
            <div class="w-80">
                <FiltersComponent 
                    :users="data?.users" 
                    @filters-applied="fetchStatistics" 
                    @filter-change="applyFilters" 
                />
            </div>
            <div class="flex-1">
                
                <TabView>
                    <TabPanel header="Общая статистика">
                        <GeneralStatisticTab 
                            v-if="data" 
                            :data="filteredData" 
                            :plan="data.plan"
                        />
                    </TabPanel>
                    <TabPanel header="Свод по МЦ и операторам">
                        <StatisticByMcTab 
                            v-if="data" 
                            :data="filteredData" 
                            :plan="data.plan" 
                            :workCenters="workCenters"
                        />
                    </TabPanel>
                    <TabPanel header="Динамика по кол-ву операций">
                        <OperationDynamicsTab 
                            v-if="data" 
                            :data="filteredData"
                        />
                    </TabPanel>
                    <TabPanel header="Брак">
                        <DefectTab 
                            v-if="data" 
                            :data="filteredData" 
                            :workCenters="workCenters"
                        />
                    </TabPanel>
                    <TabPanel header="Медь">
                        <CopperTab 
                            v-if="data" 
                            :data="filteredData"
                        />
                    </TabPanel>
                    <TabPanel header="Резцы">
                        <CutterTab 
                            v-if="data" 
                            :data="filteredData"
                        />
                    </TabPanel>
                </TabView>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { TabView, TabPanel } from '@danaflex/ui/components'
import FiltersComponent from './components/FiltersComponent.vue'
import GeneralStatisticTab from './tabs/GeneralStatisticTab.vue'
import StatisticByMcTab from './tabs/StatisticByMcTab.vue'
import OperationDynamicsTab from './tabs/OperationDynamicsTab.vue'
import DefectTab from './tabs/DefectTab.vue'
import CopperTab from './tabs/CopperTab.vue'
import CutterTab from './tabs/CutterTab.vue'
import { route as routeZiggy } from 'ziggy-js'
import { Ziggy } from '@/ziggy.js'
import axios from 'axios'

const data = ref(null)
const workCenters = ref(null)
const filters = ref({
    letters: ['А', 'Б', 'В', 'Г'],
    user: null,
})

const filteredData = computed(() => {
    if (!data.value?.data) return []
    
    return data.value.data.filter(operation => {
        const matchesLetters = filters.value.letters.length === 0 || filters.value.letters.includes(operation.letter)
        const matchesOperator = !filters.value.user || filters.value.user === operation.user_id
        
        return matchesLetters && matchesOperator
    })
})

const fetchStatistics = async (filterParams) => {
    try {
        const response = await axios.get(routeZiggy('statistic.getData', {}, undefined, Ziggy), {
            params: {
                filters: JSON.stringify(filterParams),
            }
        })
        data.value = response.data
    } catch (error) {
        console.error('Ошибка при загрузке статистики:', error)
    }
}

const fetchMachines = async () => {
    try {
        const response = await axios.get(routeZiggy('statistic.getWorkCenters', {}, undefined, Ziggy))
        workCenters.value = response.data.workCenters
    } catch (error) {
        console.error('Ошибка при загрузке станков:', error)
    }
}

const applyFilters = (filterParams) => {
    filters.value = filterParams.filters
}

onMounted(() => {
    fetchMachines()
})
</script>