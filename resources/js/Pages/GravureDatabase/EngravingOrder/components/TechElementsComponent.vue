<template>
  <div class="grid text-sm">
    <DataTable :value="parameters" size="small">
      <Column field="label" header="Техно. элементы"></Column>
      <Column header="Слева">
        <template #body="{ data }">
          <Checkbox v-model="engravingOrder.technological_elements[data.value_left]" binary  :inputId="data.value_left"  size="small" @change="updateValue"  />
        </template>
      </Column>
      <Column header="Справа">
        <template #body="{ data }">
          <Checkbox v-model="engravingOrder.technological_elements[data.value_right]" binary  :inputId="data.value_right" size="small" @change="updateValue"  />
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { computed, toRefs } from 'vue';
import { DataTable, Checkbox, Column } from "@danaflex/ui/components";

const props = defineProps({
  engravingOrder: Object, // Прокидываем объект заказа
});

const { engravingOrder } = toRefs(props); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

const parameters = computed(() => [
  { label: "Метки привода", value_left: "drive_label_left", value_right: "drive_label_right"},
  { label: "Светофор", value_left: "traffic_lights_left", value_right: "traffic_lights_right"},
  { label: "Кресты", value_left: "cross_left", value_right: "cross_right"},
  { label: "Линия реза", value_left: "cutting_line_left", value_right: "cutting_line_right"},
]); 

</script>
