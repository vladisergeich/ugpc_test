<template>
    <div class="grid w-12/12 text-sm">
      <DataTable :value="engravingOrder.layout_streams" size="small">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <span class="text-l font-bold">Конструктор монтажа</span>
                <Button icon="pi pi-plus" size="small" @click="addStreamsDialog = true" severity="secondary"/>
            </div>
        </template>
        <Column field="stream_number" header="№ р." class="w-2/12 bg-violet-100"></Column>
        <Column field="item_id" header="ГП" class="w-8/12 bg-violet-100">
            <template #body="slotProps">
                <Select
                v-model="slotProps.data.item_id"
                size="small"
                class="w-full"
                :options="engravingOrder.order?.items"
                optionLabel="item.code"
                optionValue="item.id"
                placeholder="Выберите значение"
                @change="updateStream(slotProps.data)"
            />
            </template>
        </Column>
        <Column class="w-2/12 bg-violet-100">
            <template #body="slotProps">
                <i class="pi pi-trash" style="cursor: pointer;" @click="deleteStream(slotProps.data)"></i>
            </template>
        </Column>
        <template #footer>
            <div class="flex justify-between items-center w-full">
                <Button label="Верстать" size="small" severity="contrast" @click="sendStreamInfo" />
                <Button label="Инфо. для шкал" outlined severity="contrast" size="small" @click="sendShaftInfo"/>
            </div>
        </template>
      </DataTable>
      <Dialog v-model:visible="addStreamsDialog" class="w-[400px]" header="Добавить ручьи" :modal="true">
        <label>Укажите количество ручьев</label>
        <InputNumber v-model="qtyStream" inputId="minmax-buttons" mode="decimal" showButtons :min="0" :max="10" class="w-full" />
        <template #footer>
          <Button label="Отмена" icon="pi pi-times" text @click="addStreamsDialog = false" />
          <Button label="Добавить" icon="pi pi-check" @click="addStreams" />
        </template>
      </Dialog>
    </div>
  </template>
  
<script setup>
import { ref, toRefs } from 'vue';
import { DataTable, Button, Column,Dialog,InputNumber,Select } from "@danaflex/ui/components";
import get from "lodash/get";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js"
import { router } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";

const props = defineProps({
    engravingOrder: Object, 
});

const toast = useToast();
const addStreamsDialog = ref(false);
const qtyStream = ref(null);

const { engravingOrder } = toRefs(props); 

const sendShaftInfo = async () => {
    try {
        const url = routeZiggy('engravingOrders.sendShaftInfo', {}, undefined, Ziggy);

        if (!engravingOrder.value?.id) {
            toast.add({ severity: 'error', summary: 'Не удалось определить заказ', life: 3000 });
            return;
        }

        await router.post(url, { engraving_order_id: engravingOrder.value.id } , {
            preserveScroll: true,
            preserveState: true,
        });

        addStreamsDialog.value = false;
        toast.add({ severity: 'success', summary: 'Информация отправлена', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при отправки информации', life: 3000 });
    }
};

const sendStreamInfo = async () => {
    try {
        const url = routeZiggy('engravingOrders.sendStreamInfo', {}, undefined, Ziggy);

        if (!engravingOrder.value?.id) {
            toast.add({ severity: 'error', summary: 'Не удалось определить заказ', life: 3000 });
            return;
        }

        await router.post(url, { engraving_order_id: engravingOrder.value.id } , {
            preserveScroll: true,
            preserveState: true,
        });

        addStreamsDialog.value = false;
        toast.add({ severity: 'success', summary: 'Информация отправлена', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при отправки информации', life: 3000 });
    }
};

const addStreams = async () => {
  try {
    const url = routeZiggy('layoutConstructor.create', {}, undefined, Ziggy);

    if (!engravingOrder.value?.id) {
      toast.add({ severity: 'error', summary: 'Не удалось определить заказ', life: 3000 });
      return;
    }

    await router.post(url, { quantity: qtyStream.value, engraving_order_id: engravingOrder.value.id }, {
      preserveScroll: true,
      preserveState: true,
    });

    addStreamsDialog.value = false;
    toast.add({ severity: 'success', summary: 'Ручьи добавлены', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при добавлении ручьев', life: 3000 });
  }
};
  
  
const deleteStream = async (stream) => {
    try {
        const url = routeZiggy('layoutConstructor.destroy', {}, undefined, Ziggy);

        if (!engravingOrder.value?.id) {
          toast.add({ severity: 'error', summary: 'Не удалось определить заказ', life: 3000 });
          return;
        }

        await router.post(url, { id: stream.id, engraving_order_id: engravingOrder.value.id }, {
        preserveScroll: true,
        preserveState: true,
        });

        toast.add({ severity: 'success', summary: 'Ручей удален', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при удалении ручья', life: 3000 });
    }
};

const updateStream = async (stream) => {
  try {
    const url = routeZiggy('layoutConstructor.update', {}, undefined, Ziggy);

    if (!engravingOrder.value?.id) {
      toast.add({ severity: 'error', summary: 'Не удалось определить заказ', life: 3000 });
      return;
    }

    await router.post(url, {
      id: stream.id,
      engraving_order_id: engravingOrder.value.id,
      item_id: stream.item_id,
      stream_number: stream.stream_number,
    }, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Ручей обновлен', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении ручья', life: 3000 });
  }
};
</script>

<style scoped>
.p-datatable-footer {
    background-color: #f3e8ff !important;
}

.p-datatable-header {
  background-color: #f3e8ff !important;
}
</style>
  