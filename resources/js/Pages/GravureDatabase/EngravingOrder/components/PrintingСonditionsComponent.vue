<template>
  <div class="grid text-sm w-12/12">
    <DataTable :value="conditions" size="small">
      <Column field="label" header="Условия печати" class="w-6/12"></Column>
      <Column class="w-6/12">
        <template #body="{ data }">
          <InputText
            v-if="data.edit"
            v-model="engravingOrder[data.key]" 
            size="small"
            class="w-full"
            @change="updateValue"
          />
          <span v-else>{{ get(engravingOrder, data.key, '') || '-' }}</span>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { computed, toRefs } from 'vue';
import { DataTable, InputText, Column } from "@danaflex/ui/components";
import get from "lodash/get";

const props = defineProps({
    engravingOrder: Object, 
});

const { engravingOrder } = toRefs(props); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

const conditions = computed(() => {
  const isUniversal = engravingOrder.value?.parameters?.universal_shaft ?? false; 

    return [
      { label: "Ширина мат-ла", key: "material_width", edit: isUniversal },
      { label: "Первичка", key: "profile.primary.name", edit: false },
      { label: "Вторичка", key: "profile.secondary.name", edit: false },
      { label: "Краски", key: "profile.paint_series", edit: false },
      { label: "Вывод", key: "profile.print_type", edit: false },
      { label: "Ширина ручья", key: "stream_width", edit: isUniversal },
      { label: "Шаг печати", key: "print_step", edit: isUniversal },
      { label: "Кол-во ручьев", key: "streams_quantity", edit: false },
      { label: "Цвет линии реза", key: "cutting_line_color", edit: true },
      { label: "Фраг. в обороте", key: "fragments_in_circulation", edit: isUniversal },
      { label: "Число стадий", key: "odd_stages", edit: false },
      { label: "Вар. намотки", key: "order.winding_option", edit: false },
      { label: "Длина гильзы", key: "sleeve_length", edit: isUniversal },
      { label: "Ширина гр-ки", key: "engraving_width", edit: isUniversal }
    ];
});
</script>
