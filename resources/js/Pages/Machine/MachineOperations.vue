<template>
    <div class="col-span-full bg-white p-5 rounded-xl mt-5">
        <div class="flex justify-between mb-4 border-b border-gray-300 w-12/12">
            <div class="flex space-x-4">
                <div class="flex">
                    <span class="text-gray-600 mr-3">Сделано валов:</span>
                    <span class="text-black font-semibold">{{ shaftsMadeWorkShift }}</span>
                </div>
                <template v-if="machine">
                    <div v-if="machine.cascade">
                        <span class="text-gray-600">Каскад:</span>
                        <span class="text-black font-semibold">{{ machine.cascade.variable_body }}</span>
                    </div>
                    <div v-if="machine.main_cutter">
                        <span class="text-gray-600">Основной резец:</span>
                        <span class="text-black font-semibold">{{ machine.main_cutter.variable_body }}</span>
                    </div>
                    <div v-if="machine.end_cutter">
                        <span class="text-gray-600">Торцевой резец:</span>
                        <span class="text-black font-semibold">{{ machine.end_cutter.variable_body }}</span>
                    </div>
                </template>
            </div>
        </div>
        <DataTable :value="machine.operations_in_work_shift" class="w-full">
            <Column v-for="col in dynamicColumns" :key="col.field" :field="col.field" :header="col.header" />
        </DataTable>
    </div>
</template>

<script setup>
import { computed, toRefs } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { DataTable, Column } from "@danaflex/ui/components";

const props = defineProps({
    machine: Object,
});

const { machine } = toRefs(props);

const shaftsMadeWorkShift = computed(() => {
    return [...new Set(machine.value.operations_in_work_shift.map(s => s.engraving_order_shaft_id).filter(Boolean))].length;
});

const baseColumns = [
    { field: 'operation.name', header: 'Операция' },
    { field: 'engraving_order_shaft.shaft.code', header: 'Вал' },
    { field: 'engraving_order_shaft.engraving_order.okvid_number', header: 'Оквид' },
    { field: 'starting_time', header: 'Время начала' },
    { field: 'ending_time', header: 'Время окончания' }
];

const extraColumnsMap = {
    CFM: [
        { field: 'fact_diameter.float_value', header: 'Диаметр' },
        { field: 'diameter_difference.float_value', header: 'Разница D' }
    ],
    K5: [
        { field: 'head_number.float_value', header: 'Номер головы' }
    ]
};

const dynamicColumns = computed(() => [...baseColumns, ...(extraColumnsMap[machine?.workCenter?.name] || [])]);
</script>

<style scoped></style>
