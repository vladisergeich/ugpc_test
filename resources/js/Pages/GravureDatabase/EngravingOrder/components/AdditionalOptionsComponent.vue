<template>
  <div class="flex flex-col">
    <h3 class="font-medium mb-1.5 text-sm">Доп. опции:</h3>
    <div v-for="(option, index) in additionalOptions" :key="index" class="flex items-center mb-1.5">
      <Checkbox 
        size="small" 
        v-if="engravingOrder.additional_options"
        v-model="engravingOrder.additional_options[option.key]"
        @change="updateValue"
        :inputId="'option-' + index" 
        binary 
        class="mr-2" 
      />
      <label :for="'option-' + index" class="text-xs">{{ option.label }}</label>
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

const additionalOptions = computed(() => [
  { label: "Автосмещение ручьев", key: "automatic_stream" },
  { label: "Зазор 10мм между ручьями", key: "gap" },
  { label: "Relsa 100%", key: "rail" },
  { label: "БезРеза", key: "without_cutting" },
  { label: "RotateFactor", key: "rotate_factor" },
]);
</script>
