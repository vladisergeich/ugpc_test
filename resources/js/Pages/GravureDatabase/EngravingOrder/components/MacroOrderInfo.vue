<template>
    <div class="flex gap-4 p-4 bg-white rounded-lg text-sm">
      <div class="flex-3">
        <label class="block mb-2">ОКВиД</label>
        <InputNumber
          size="small"
          v-if="macroOrder"
          v-model="selectedMacroOrderId "
          @update:modelValue="showMacroOrder"
          class="w-full"
          showButtons
          buttonLayout="horizontal"
        >
          <template #decrementbuttonicon>
            <span class="pi pi-chevron-left" />
          </template>
          <template #incrementbuttonicon>
            <span class="pi pi-chevron-right" />
          </template>
        </InputNumber>
      </div>
  
      <div class="flex-1">
        <label class="block mb-2">Клиент</label>
        <InputText size="small" :value="macroOrder?.customer?.name" class="w-full" disabled />
      </div>
  
      <div class="flex-1">
        <label class="block mb-2">Описание</label>
        <InputText size="small" :value="description" class="w-full" disabled />
      </div>
  
      <div class="flex-1">
        <label class="block mb-2">Примечание</label>
        <InputText size="small" v-model="macroOrder.note" class="w-full" />
      </div>
  
      <div class="flex-2 flex items-end">
        <Button size="small" label="Создать ОКВиД" class="w-full" severity="secondary" @click="addMacroOrder = true" />
      </div>
  
      <Dialog v-model:visible="addMacroOrder" class="w-[400px]" header="Создать макрозаказ" :modal="true">
        <Select size="small" v-model="customerId" :options="customers" filter optionLabel="name" optionValue="id" placeholder="Выберите клиента" class="w-full">
              
        </Select>
        <template #footer>
          <Button label="Отмена" icon="pi pi-times" text @click="addMacroOrder = false" />
          <Button label="Создать" icon="pi pi-check" @click="createMacroOrder" />
        </template>
      </Dialog>
    </div>
  </template>
  
<script setup>
import { storeToRefs } from 'pinia';
import { computed, toRefs, ref, watch } from 'vue';
import { InputNumber, InputText, Button,Dialog, Select } from "@danaflex/ui/components";
import { useGravureStore } from '@/stores/gravureStore';
import { router } from '@inertiajs/vue3';
  
const props = defineProps({
    macroOrder: Object,
    engravingOrder: Object,
});

const { macroOrder } = toRefs(props);
const { engravingOrder } = toRefs(props);
const selectedMacroOrderId = ref(macroOrder.value?.id || null);

const gravureStore = useGravureStore();
const { customers } = storeToRefs(gravureStore);

const addMacroOrder = ref(false);
const customerId = ref(null);

const description = ref((engravingOrder.value?.order?.items[0]?.item?.category ?? '')+'  '+(engravingOrder.value?.order?.items[0]?.item?.brand ?? ''));

const createMacroOrder = () => {
    router.post('/macro-orders/create', {customerId: customerId.value } , {
        preserveScroll: true,  
        preserveState: true,   
    });

    addMacroOrder.value = false;
};

const showMacroOrder = async () => {
  router.visit(`/macro-orders/${selectedMacroOrderId.value}`);
};

watch(
  () => macroOrder.value?.id,
  (newId) => {
    selectedMacroOrderId.value = newId;
  }
);
</script>
  