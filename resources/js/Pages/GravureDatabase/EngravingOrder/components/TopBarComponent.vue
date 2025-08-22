<template>
    <div class="flex justify-between text-sm">
      <div class="flex gap-1.5">
        <Button icon="pi pi-angle-double-left" size="small" outlined severity="secondary" />
        <Button icon="pi pi-chevron-left" size="small" outlined severity="secondary" />
        <Select size="small" class="w-full" showButtons buttonLayout="horizontal" :options="engravingOrders" :optionLabel="formatOptionLabel" :optionValue="'id'" v-model="engravingOrder.id" @change="changeOrder">

        </Select>
        <Button icon="pi pi-chevron-right" size="small" outlined severity="secondary" />
        <Button icon="pi pi-angle-double-right" size="small" outlined severity="secondary" />
      </div>
      <div class="flex gap-4">
        <div class="flex items-center gap-2">
          <Checkbox 
            size="small" 
            v-if="engravingOrder.parameters"
            v-model="engravingOrder.parameters.universal_shaft"
            @change="updateValue"
            :inputId="'universal_shaft'" 
            binary class="mr-2" 
          />
          <label for="universalShaft">Универсальный вал</label>
        </div>
        <Button label="Дублировать" severity="primary" size="small" @click="createEngravingOrder" />
      </div>
    </div>
</template>
  
<script setup>
import { router } from '@inertiajs/vue3';
import { Select, Button, Checkbox } from "@danaflex/ui/components";
import { computed, toRefs } from 'vue';

const props = defineProps({
    engravingOrders: Array,
    engravingOrder: Object,
});

const { engravingOrder } = toRefs(props); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

const createEngravingOrder = () => {
    router.post('/engraving-orders/create', engravingOrder.value, {
        preserveScroll: true,  
        preserveState: true,   
    });
};

const changeOrder = async () => {
  try {
    router.visit(`${props.engravingOrder.id}`);
  } catch (error) {
    console.error('Ошибка при загрузке заказа:', error);
  }
};

const formatOptionLabel = (engravingOrder) => {
    const paddedSequence = String(engravingOrder.sequence_number).padStart(2, '0');
    return `${engravingOrder.macro_order_id} - ${paddedSequence}`;
};

</script>