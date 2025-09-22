<template>
    <div class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
            <StatisticPlanCard 
                class="bg-gradient-to-br from-blue-400 to-blue-600 text-white" 
                title="Статистика месячного плана" 
                :data="monthlyData" 
                :plan="monthlyPlan" 
                :fact="monthlyFact"
            />
            <StatisticPlanCard 
                class="bg-white shadow-md" 
                title="Статистика суточного плана" 
                :data="dailyData" 
                :plan="dailyPlan" 
                :fact="dailyFact"
            />
        </div>

        <div class="grid grid-cols-2 gap-6">
            <DefectCard 
                title="Брак" 
                :data="defectData" 
                :fact="totalFact"
            />
            <PaceDayCard 
                title="Темп по выпуску готовых ПЦ в сутки" 
                :data="paceData" 
                :plan="monthlyPlan" 
                :fact="totalFact"
            />
        </div>

        <div class="grid grid-cols-3 gap-6">
            <MachineFactCard title="CFM-1" :data="cfm1Data"/>
            <MachineFactCard title="CFM-2" :data="cfm2Data"/>
            <MachineFactCard title="Гальваника" :data="electroplatingData"/>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <MachineFactCard title="K5-1" :data="k51Data"/>
            <MachineFactCard title="K5-2" :data="k52Data"/>
            <MachineFactCard title="Пробпечать" :data="proofData"/>
        </div>

        <div class="mt-6">
            <PlanFactDynamicsTable 
                title="План/Факт от месячного плана в динамике" 
                :plan="plan" 
                :data="proofData"
            />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import StatisticPlanCard from '../components/StatisticPlanCard.vue'
import DefectCard from '../components/DefectCard.vue'
import PaceDayCard from '../components/PaceDayCard.vue'
import MachineFactCard from '../components/MachineFactCard.vue'
import PlanFactDynamicsTable from '../components/PlanFactDynamicsTable.vue'

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    plan: {
        type: Array,
        required: true,
    },
})

const monthlyData = computed(() => 
    props.data.filter(machine => machine.machine_id !== 16).filter(op => op.type === 'main')
)

const monthlyPlan = computed(() => 
    props.plan.filter(value => value.start_date !== value.end_date).reduce((sum, item) => sum + item.plan_qty, 0)
)

const monthlyFact = computed(() => 
    props.data.filter(machine => machine.machine_id === 13).filter(op => op.type === 'main').length
)

const dailyData = computed(() => 
    props.data.filter(machine => machine.machine_id !== 16).filter(op => op.type === 'main')
)

const dailyPlan = computed(() => 
    props.plan.filter(value => value.start_date === value.end_date).reduce((sum, item) => sum + item.plan_qty, 0)
)

const dailyFact = computed(() => 
    props.data.filter(machine => machine.machine_id === 13).filter(op => op.type === 'main').length
)

const defectData = computed(() => 
    props.data.filter(machine => machine.operation_id === 68)
)

const totalFact = computed(() => 
    props.data.filter(machine => machine.machine_id === 13).filter(op => op.type === 'main').length
)

const paceData = computed(() => 
    props.data.filter(machine => machine.machine_id !== 16).filter(op => op.type === 'main')
)

const cfm1Data = computed(() => 
    props.data.filter(op => op.type === 'main').filter(machine => machine.machine_id === 14)
)

const cfm2Data = computed(() => 
    props.data.filter(machine => machine.machine_id === 15).filter(op => op.type === 'main')
)

const electroplatingData = computed(() => 
    props.data.filter(machine => machine.work_center_id === 1).filter(op => op.type === 'main')
)

const k51Data = computed(() => 
    props.data.filter(machine => machine.machine_id === 11).filter(op => op.type === 'main')
)

const k52Data = computed(() => 
    props.data.filter(machine => machine.machine_id === 12).filter(op => op.type === 'main')
)

const proofData = computed(() => 
    props.data.filter(machine => machine.machine_id === 13).filter(op => op.type === 'main')
)
</script>