<template>
    <div class="col-span-full">
        <div class="flex">
            <Calendar 
            v-model="dates" 
            selectionMode="range" 
            :manualInput="false" 
            :showTime="false"
            dateFormat="dd.mm.yy" 
            size="small" 
            placeholder="Дата операции"
            @date-select="fetchOrders"
            />
        </div>
        <div class="grid grid-cols-3 gap-4 mt-2">
            <MachineCard
            v-for="machine in machines"
            :key="machine.id"
            :machine="machine"
            @click="showMachineTable(machine)"
            />
        </div>
    
        <Dialog v-model:visible="visibleMachineTable" modal :header="selectMachine.name" :style="{ width: '60vw' }">
            <DataTable :value="selectMachine.complete_operations" :scrollable="true" scrollHeight="300px">
                <Column field="engraving_order_shaft.shaft.code" header="Вал" headerClass="bg-blue-100"/>
                <Column field="operation.name" header="Операция" headerClass="bg-blue-100"/>
            </DataTable>
        </Dialog>
    </div>
  </template>

<script setup>
import { toRefs } from 'vue';
import { onMounted, ref } from 'vue';
import { router, usePage } from "@inertiajs/vue3";
import { Dropdown,Dialog,DataTable,Column,Calendar } from "@danaflex/ui/components";
import MachineCard from './MachineCard.vue'
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";

const props = defineProps({
    machines: Array,
    filters: Object,
});

const { machines, filters } = toRefs(props);

const dates = ref(
  filters.value.start_date && filters.value.end_date
    ? [new Date(filters.value.start_date), new Date(filters.value.end_date)]
    : null
);

const visibleMachineTable = ref(false)
const selectMachine = ref('')

const showMachineTable = (machine) => {
  selectMachine.value = machine
  visibleMachineTable.value = true
}

const fetchOrders = () => {

    const params = {
        start_date: null,
        end_date: null,
    };
    
    if (dates.value && dates.value.length === 2) {
        params.start_date = dates.value[0].toLocaleDateString('sv-SE');
        params.end_date = dates.value[1].toLocaleDateString('sv-SE');
    }

    const url = routeZiggy(
        'dailyPlan.statistic', 
        {},
        undefined,
        Ziggy
    ); 

    router.get(url, params, {
        preserveState: true,
        only: ['machines', 'filters'],
        replace: true,
    });
};

</script>


