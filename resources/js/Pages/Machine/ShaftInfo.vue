<template>
    <div class="flex items-center justify-between">
        <div class="flex items-center justify-start gap-4">
            <div v-for="info in shaftInfo" :key="info.title">
                <span class="text-lg font-normal text-gray-400 mr-1.5">{{ info.title }}:</span>
                <span class="text-lg font-normal">{{ info.value }}</span>
            </div>
        </div>
        <div class="flex">
            <Button type="button" link icon="pi pi-ellipsis-v" @click="showEngravingShaftAction" aria-haspopup="true" size="small" aria-controls="overlay_menu" />
            <Menu ref="action" id="overlay_menu" :model="actions" :popup="true" />
        </div>
        <Dialog v-model:visible="showDefect" modal header="Вал на рассмотрение" :style="{ width: '40rem' }">
            <Dropdown 
                v-model="defect.reason_id"
                placeholder="Причина" 
                class="w-full"
            />
            <Textarea size="small" class="mt-5 w-full" v-model="defect.note" placeholder="Комментарий" rows="4" />
            <template #footer>
                <Button label="На рассмотрение" severity="primary" size="small" @click="defectShaft()" />
            </template>
        </Dialog>
    </div>
</template>
  
<script setup>
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button, Popover, Menu, Dropdown, Textarea } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js"

const props = defineProps({
    machine: Object,
});

const action = ref(false);
const showDefect = ref(false);
const { machine } = toRefs(props);
const defect = ref({
    reason_id: null,
    note: null,
});

const showEngravingShaftAction = (event) => {  
    action.value.toggle(event);
}

const actions = ref([
    {
        items: [
            {
                label: 'Вал на рассмотрение',
                command: () => {
                    showDefect.value = true;
                }
            },
        ]
    }
]);

const shaftInfo = computed(() => {
    return [
        { title: 'Вал', value: machine.value.consump_shaft?.engraving_order_shaft.shaft.code },
        { title: 'Оквид', value: machine.value.consump_shaft?.engraving_order_shaft.engraving_order.okvid_number },
        { title: 'Заказ', value: machine.value.consump_shaft?.engraving_order_shaft.engraving_order?.order?.order_number},
        { title: 'Формат', value: machine.value.consump_shaft?.engraving_order_shaft.engraving_order.format },
        { title: 'Проход', value: machine.value.consump_shaft?.engraving_order_shaft.active_stage.phase.sequence },
    ];
});

const defectShaft = async () => {
  try {
    const url = routeZiggy('defect.create', {}, undefined, Ziggy);

    router.post(url, { machine: machine.value, defect: defect.value }, {
      preserveScroll: true,
      preserveState: true,

    });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};
</script>

<style scoped>

</style>
  