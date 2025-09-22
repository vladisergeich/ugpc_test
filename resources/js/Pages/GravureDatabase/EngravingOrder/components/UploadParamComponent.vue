<template>
    <div class="flex flex-col gap-2.5">
      <div class="flex gap-2.5 w-full">
        <div class="w-[60%] flex flex-col">
          <Textarea size="small" @change="updateValue" v-model="engravingOrder.comments.engraver_comment" placeholder="Комментарий гравировщику" rows="2" />
        </div>
        <div class="w-[20%] flex flex-col">
          <label>Повтор с</label>
          <InputText size="small" class="w-full" :value="engravingOrder?.repeat_engraving_order?.okvid_number"/>
        </div>
        <div class="w-[20%] flex flex-col">
          <label>Дата утвержд. заказа</label>
          <DatePicker size="small" class="w-full" v-model="engravingOrder.order_approval_date" disabled dateFormat="dd.mm.yy"/>
        </div>
      </div>
      <div class="flex gap-2.5 w-full">
        <div class="w-[40%] flex flex-col">
          <label>Параметры монтажа</label>
          <Select 
            size="small" 
            class="w-full" 
            :options="mountingParameters"
            optionLabel="name"
            optionValue="id"
            placeholder="Выберите значение"
            v-model="engravingOrder.mounting_parameter_id"
            @change="updateValue"/>
        </div>
        <div class="w-[40%] flex flex-col">
          <label>Заявку выгрузил</label>
          <Select 
            size="small" 
            class="w-full" 
            :options="designers"
            optionLabel="name"
            optionValue="id"
            placeholder="Выберите значение"
            v-model="engravingOrder.designer_id"
            @change="updateValue"/>
        </div>
        <div class="w-[20%] flex flex-col">
          <label>Дата выгрузки</label>
          <DatePicker size="small" class="w-full" v-model="engravingOrder.order_request_date" disabled dateFormat="dd.mm.yy"/>
        </div>
      </div>
      <div class="flex justify-end gap-2.5" v-if="engravingOrder.parameters">
        <Checkbox 
            size="small" 
            v-if="engravingOrder.parameters"
            v-model="engravingOrder.parameters.updated_application"
            @change="updateValue"
            :inputId="'updated_application'" 
            binary class="mr-2" 
          />
        <label for="universalShaft">Уточненная</label>
        <Checkbox 
            size="small" 
            v-if="engravingOrder.parameters"
            v-model="engravingOrder.parameters.without_unloading_application"
            @change="updateValue"
            :inputId="'without_unloading_application'" 
            binary class="mr-2" 
          />
        <label for="universalShaft">Без выгрузки</label>
      </div>
      <div class="flex justify-between">
        <div></div>
        <div class="flex gap-2.5">
          <Button label="Отправить заявку" severity="primary" size="small" @click="sendApplication"/>
        </div>
      </div>
    </div>
  </template>
  
<script setup>
import { storeToRefs } from 'pinia';
import { computed, toRefs } from 'vue';
import { Textarea, InputText, Button, Select, Checkbox, DatePicker } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { useGravureStore } from '@/stores/gravureStore';
import { useToast } from "primevue/usetoast";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";

const props = defineProps({
  engravingOrder: Object, // Прокидываем объект заказа
});

const toast = useToast();

const { engravingOrder } = toRefs(props); 

const gravureStore = useGravureStore();
const { mountingParameters, designers } = storeToRefs(gravureStore);

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

const sendApplication = async () => {
  try {
    const url = routeZiggy('engravingOrders.sendApplication', {}, undefined, Ziggy);

    await router.post(url, engravingOrder.value, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Заявка отправлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при отправке заявки', life: 3000 });
  }
};
</script>
  