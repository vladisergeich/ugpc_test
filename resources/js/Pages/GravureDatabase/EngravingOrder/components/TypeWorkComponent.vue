<template>
  <div class="flex flex-col">
    <h3 class="font-medium mb-1.5 text-sm">Вид работы:</h3>
    <div v-for="(work, index) in workTypes" :key="index" class="flex items-center mb-1.5">
      <Checkbox 
        size="small" 
        v-if="engravingOrder.type_work_parameters"
        v-model="engravingOrder.type_work_parameters[work.key]"
        @change="updateValue"
        :inputId="'work-' + index" 
        binary class="mr-2" 
      />
      <label :for="'work-' + index" class="text-xs">{{ work.label }}</label>
    </div>
  </div>
</template>

<script setup>
import { Checkbox } from "@danaflex/ui/components";
import { computed, toRefs } from 'vue';

const props = defineProps({
  engravingOrder: Object, // Прокидываем объект заказа
});

const { engravingOrder } = toRefs(props); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};
const workTypes = computed(() => [
  { label: "Новая работа", key: "new_job" },
  { label: "Перегравировка", key: "reengraving" },
  { label: "Грав. с изм.", key: "with_changes" },
  { label: "Монтаж", key: "installation" },
  { label: "Цветопроба", key: "color_proof" },
  { label: "Пробопечать", key: "test_print" },
]);
</script>
