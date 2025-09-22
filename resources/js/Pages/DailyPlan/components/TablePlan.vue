<template>
    <div class="col-span-full bg-white">
        <table class="w-full">
            <thead>
                <tr class="text-black">
                    <th class="border border-gray-200 px-2 py-2 font-normal">Вал</th>
                    <th class="border border-gray-200 px-2 py-2 font-normal">Сеп</th>
                    <th class="border border-gray-200 px-2 py-2 font-normal">Подтв.</th>
                    <th class="border border-gray-200 px-2 py-2 font-normal" v-for="stage in stages" :key="stage.key">
                        {{ stage.name }}
                    </th>
                    <th class="border border-gray-200 px-2 py-2 font-normal">Время грав.</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(phase, index) in phases" :key="index">
                    <tr v-if="phase.group_row" class="font-bold bg-purple-100">
                        <td colspan="100%" class="border border-gray-300 px-2 py-2 font-normal text-left">
                            ОКВИД: {{ phase.okvid_number }}
                        </td>
                    </tr>
                    <tr v-else>
                        <td 
                            v-if="shouldShowShaft(index)" 
                            :rowspan="countShaftOccurrences(phase.engraving_order_shaft.shaft.code)" 
                            class="border border-gray-300 px-2 py-2 font-normal text-center align-middle"
                        >
                            {{ phase.engraving_order_shaft.shaft.code }}
                        </td>
                        <td 
                            v-if="shouldShowShaft(index)" 
                            :rowspan="countShaftOccurrences(phase.engraving_order_shaft.shaft.code)" 
                            class="border border-gray-300 px-2 py-2 font-normal text-center align-middle"
                        >
                            {{ phase.engraving_order_shaft.separation }}
                        </td>
                        <td 
                            v-if="shouldShowShaft(index)" 
                            :rowspan="countShaftOccurrences(phase.engraving_order_shaft.shaft.code)" 
                            class="border border-gray-300 px-2 py-2 font-normal text-center align-middle"
                        >
                            <i v-if="!phase.engraving_order_shaft.parameters.confirmed" 
                                class="pi pi-spinner pi-spin text-blue-500"></i>
                            <i v-if="phase.engraving_order_shaft.parameters.confirmed === 'completed'" 
                                class="pi pi-check-circle text-green-500"></i>
                        </td>
                        <td v-for="stage in stages" :key="stage.key" class="border border-gray-300 px-2 py-2 text-center">
                            <div v-if="phase.stages_by_stage_id && phase.stages_by_stage_id[stage.key]">
                                <i v-if="phase.stages_by_stage_id[stage.key]?.operation?.status === 'in_progress'" 
                                   class="pi pi-spinner pi-spin text-blue-500"></i>
                                <i v-if="phase.stages_by_stage_id[stage.key].status === 'completed'" 
                                   class="pi pi-check-circle text-green-500"></i>
                                <i v-if="phase.stages_by_stage_id[stage.key].status === 'rejected'" 
                                   class="pi pi-times-circle text-red-500"></i>
                                <i v-if="phase.stages_by_stage_id[stage.key].status === 'in_progress' && phase.stages_by_stage_id[stage.key]?.operation?.status != 'in_progress'" 
                                   class="pi pi-check-circle"></i>
                            </div>
                            <div v-else>
                                <span>
                                    —
                                </span>
                            </div>
                        </td>
                        <td 
                            v-if="shouldShowShaft(index)" 
                            :rowspan="countShaftOccurrences(phase.engraving_order_shaft.shaft.code)" 
                            class="border border-gray-300 px-2 py-2 text-center align-middle"
                        >
                            <InputText
                                v-model="phase.engraving_order_shaft.engraving_time" 
                                type="number"
                                size="small"
                                class="w-full"
                                @change="updateEngravingOrderShaft(phase.engraving_order_shaft)"
                            />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { toRefs } from 'vue';
import { onMounted, ref } from 'vue';
import { InputText } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";

const props = defineProps({
    phases: Array,
});


onMounted(() => {
    window.Echo.private('stage')
        .listen('StageUpdated', (event) => {
            const phaseIndex = phases.value.findIndex(phase => phase.id === event.stage.phase_id);

            phases.value[phaseIndex].stages_by_stage_id[event.stage.stage_id].status = event.stage.status;
            phases.value[phaseIndex].stages_by_stage_id[event.stage.stage_id].operation = event.stage.operation;
    })
});

const updateEngravingOrderShaft = async (shaft) => {
  try {
    const url = routeZiggy('engravingOrderShafts.update', {}, undefined, Ziggy);

    await router.post(url, shaft, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Секция обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении секции', life: 3000 });
  }
};

const stages = [
    { key: 14, name: "Вход. конт" },
    { key: 1, name: "Дехром" },
    { key: 3, name: "Шлиф.базы" },
    { key: 2, name: "Обезжир" },
    { key: 5, name: "Пред. мед." },
    { key: 6, name: "Мед.базы" },
    { key: 4, name: "Обезжир" },
    { key: 12, name: "Мед.тираж" },
    { key: 7, name: "Шлиф.тираж" },
    { key: 9, name: "Грав." },
    { key: 8, name: "Обезжир" },
    { key: 10, name: "Хромир" },
    { key: 11, name: "Пол. хром" },
    { key: 13, name: "Проб. печать" },
];

const { phases } = toRefs(props);

const countShaftOccurrences = (shaftCode) => {
    return phases.value.filter(phase => 
        phase.engraving_order_shaft?.shaft.code === shaftCode
    ).length;
};

const shouldShowShaft = (index) => {
    if (index === 0) return true;
    return phases.value[index].engraving_order_shaft?.shaft.code !== phases.value[index - 1].engraving_order_shaft?.shaft.code;
};
</script>

<style scoped>
th {
    text-align: center;
}
</style>
