<template>
  <div class="flex gap-4 text-sm">
    <div class="w-[40%]">
      <label>Партия</label>
      <InputGroup>
        <InputText 
          size="small" 
          v-model="orderNumber" 
          :class="{ 'border-red-500': isError }" 
          placeholder="Введите партию" 
        />
        <Button 
          size="small" 
          icon="pi pi-chevron-right" 
          @click="saveOrder" 
        />
      </InputGroup>
    </div>
    <div class="flex gap-4 w-[60%]">
      <div class="w-full">
        <label>Формат</label>
        <InputText size="small" class="w-full" v-model="engravingOrder.format" disabled />
      </div>
      <div class="w-full">
        <label>Степпинг</label>
        <InputText size="small" class="w-full" v-model="engravingOrder.gradation_increase" @change="updateValue" />
      </div>
      <div class="w-full">
        <label>Кол-во валов</label>
        <InputText size="small" class="w-full" v-model="engravingOrder.quantity_shaft" @change="updateValue"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { InputText, InputNumber, Button, InputGroup } from "@danaflex/ui/components";
import { toRefs, ref, computed, watch } from 'vue';
import { useToast } from "primevue/usetoast";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    engravingOrder: Object,
});

const toast = useToast();
const { engravingOrder } = toRefs(props); 

const orderNumber = ref(engravingOrder.value?.order?.order_number ?? "");
const isError = ref(false); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

watch(() => engravingOrder.value?.order?.order_number, (newVal) => {
  orderNumber.value = newVal;
});


const saveOrder = async () => {
  try {
    const url = routeZiggy('order.saveOrder', {}, undefined, Ziggy);

    if (!engravingOrder.value?.id) {
      toast.add({ severity: 'error', summary: 'Отсутствует идентификатор заказа', life: 3000 });
      return;
    }

    router.post(url, { engraving_order_id: engravingOrder.value?.id, order_number: orderNumber.value }, {
      preserveScroll: true,
      preserveState: true,

      onSuccess: () => { 
        isError.value = false;
        toast.add({ severity: 'success', summary: 'Партия загружена', life: 3000 });
      },

      onError: (errors) => {
        isError.value = true;

        const msg =
          errors?.orderNumber || errors?.error || 'Ошибка при загрузке заказа';

        toast.add({ severity: 'error', summary: msg, life: 3000 });
      }
    });
  } catch (error) {
    console.error(error);
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};


</script>