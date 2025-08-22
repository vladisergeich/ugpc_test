<template>
    <div class="col-span-full">
        <template class="flex items-center justify-between border-b border-gray-300 pb-5 relative">
            <div class="flex items-center justify-start gap-4">
                <div v-for="info in headerInfo" :key="info.title" class="mr-6">
                    <span class="text-m font-normal text-gray-400 mr-1.5">{{ info.title }}:</span>
                    <span class="text-m font-normal">{{ info.value }}</span>
                </div>
            </div>

            <div class="flex">
                <Button label="Доп. операции" severity="primary" raised size="small" @click="showOperation = true" v-if="!machine.consump_shaft"/>

                <Dialog 
                v-model:visible="showOperation" 
                modal 
                header="Доп. операции" 
                class="w-[400px]"
                >
                    <Dropdown 
                        v-model="selectOperation" 
                        :options="machine.work_center.secondary_operations" 
                        optionKey="id" 
                        optionLabel="name" 
                        placeholder="Выберите операцию" 
                        class="w-full"
                    />
                    <template #footer>
                        <Button label="Начать" icon="pi pi-check" class="p-button-primary" @click="startOperation" />
                    </template>
                </Dialog>
            </div>
        </template>
    </div>
</template>
  
<script setup>
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button, Dropdown } from "@danaflex/ui/components";
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    machine: Object,
    workShift: Object,
});

const toast = useToast();
const showOperation = ref(false);
const page = usePage();
const user = computed(() => page.props.user);
const selectOperation = ref(null);


const { machine } = toRefs(props);
const { workShift } = toRefs(props);


const startOperation = async () => {
    try {
        const url = routeZiggy('operationLedgerEntry.create', {}, undefined, Ziggy);

        router.post(url, { machineId: machine.value.id, operationId: selectOperation.value.id, engravingOrderShaft:  machine.value?.consump_shaft?.engraving_order_shaft }, {
        preserveScroll: true,
        preserveState: true,

        });

        showOperation.value = false;
    } catch (error) {
        console.log(error);
        toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
    }
};


const headerInfo = computed(() => {
  return [
    { title: 'МЦ', value: machine.value.name },
    { title: 'Оператор', value: user.value?.employer_name_1c },
    { title: 'Смена', value: workShift.value?.letter },
    { title: 'Время', value: `${workShift.value?.starting_time}-${workShift.value?.ending_time}` },
    { title: 'Дата', value: workShift.value?.date },
    ];
});

</script>

<style scoped>

</style>
  