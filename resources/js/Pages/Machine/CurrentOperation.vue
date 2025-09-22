<template>
    <template v-if="machine.current_operation">
        <div class="flex items-center justify-between gap-4">
          <div class="flex flex-col flex-1 w-12/12" v-for="param in machine?.current_operation?.operation?.parameters" :key="param.id">
            <span class="text-lg font-normal text-gray-400">{{ param.parameter.name }}:</span>
            <InputText :type="param.parameter.type" v-model="param.value" size="small"/>
          </div>
        </div>
        <div class="flex items-center justify-between">
            <div class="flex gap-4 rounded-xl p-3 bg-blue-50 items-center w-7/12 justify-between">
                <div class="flex gap-4 items-center">               
                  <i class="pi pi-clock"></i>
                  <span class="text-lg font-normal text-gray-400">Текущая операция:</span>
                  <span class="text-lg font-normal">{{machine?.current_operation?.operation?.name}}</span>
                </div>
                <i class="pi pi-trash cursor-pointer" @click="deleteOperation"></i>
            </div>
            <div class="flex gap-4">
              <Button v-if="[8, 9, 10].includes(machine?.current_operation?.operation_id)" label="Пред. меднение" severity="primary" size="small" @click="closeOperation(true)" />
              <Button label="Завершить" severity="primary" size="small" @click="closeOperation(false)" />
            </div>
        </div>
    </template>
    <template v-else>
        <div class="flex items-center justify-end gap-4">
            <Button type="button" severity="primary" size="small" raised @click="showOperation = true">Добавить операцию</Button>
            <Button type="button" severity="secondary" size="small" raised @click="deleteConsump()">Отменить</Button>
            <Dialog v-model:visible="showOperation" modal header="Начать операцию" :style="{ width: '25rem' }">
                <Dropdown 
                    v-model="selectOperation" 
                    :options="machine.consump_shaft.engraving_order_shaft.active_stage.operations" 
                    optionKey="operation.id" 
                    optionLabel="operation.name" 
                    placeholder="Выберите операцию" 
                    class="w-full"
                />
                <template #footer>
                    <Button label="Отмена" severity="secondary" size="small" @click="showOperation = false" />
                    <Button label="Начать" severity="primary" size="small" @click="startOperation()" />
                </template>
            </Dialog>
        </div>
    </template>
</template>
  
<script setup>
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button, Dropdown, InputText } from "@danaflex/ui/components";
import { useToast } from "primevue/usetoast";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";

const props = defineProps({
    machine: Object,
});

const toast = useToast();
const { machine } = toRefs(props);
const showOperation = ref(false);
const selectOperation = ref(null);


const deleteConsump = async () => {
  console.log(machine);
  try {
    const url = routeZiggy('machine.destroy', {}, undefined, Ziggy);

    router.post(url, { consumpId: machine.value.consump_shaft.id, machineId: machine.value.id }, {
      preserveScroll: true,
      preserveState: true,

    });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};

const deleteOperation = async () => {
  try {
    const url = routeZiggy('operationLedgerEntry.destroy', {}, undefined, Ziggy);

    router.post(url, { operationId: machine.value.current_operation.id, machineId: machine.value.id}, {
      preserveScroll: true,
      preserveState: true,

    });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};

const closeOperation = async (preCopper) => {
  try {
    const url = routeZiggy('operationLedgerEntry.update', {}, undefined, Ziggy);

    router.post(url, { operation: machine.value.current_operation, machineId: machine.value.id, preCopper: preCopper}, {
      preserveScroll: true,
      preserveState: true,

    });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};

const startOperation = async () => {
  try {
    const url = routeZiggy('operationLedgerEntry.create', {}, undefined, Ziggy);

    router.post(url, { machineId: machine.value.id, operationId: selectOperation.value.operation.id, engravingOrderShaft:  machine.value.consump_shaft.engraving_order_shaft}, {
      preserveScroll: true,
      preserveState: true,

    });

    showOperation.value = false;
  } catch (error) {
    console.log(error);
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};
</script>

<style scoped>

</style>
  