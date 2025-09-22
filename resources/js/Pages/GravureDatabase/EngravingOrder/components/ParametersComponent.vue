<template>
  <div class="grid text-sm">
    <DataTable :value="parameters" size="small">
      <Column field="label" header="Параметры" class="w-6/12"></Column>
      <Column class="w-6/12">
        <template #body="{ data }">
          <Select
            v-if="data.options"
            v-model="engravingOrder[data.key]"
            size="small"
            class="w-full"
            :options="data.options"
            :optionLabel="data.optionLabel"
            :optionValue="data.optionValue"
            placeholder="Выберите значение"
            @change="updateValue"
          />
          <span v-else>{{ get(engravingOrder, data.key, '') || '-' }}</span>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { DataTable, Select, Column } from "@danaflex/ui/components";
import { computed, toRefs } from 'vue';
import { useGravureStore } from '@/stores/gravureStore';
import get from "lodash/get";

const props = defineProps({
  engravingOrder: Object, // Прокидываем объект заказа
});

const { engravingOrder } = toRefs(props); 

const emit = defineEmits(["update:engravingOrder"]);

const updateValue = (newValue, key) => {
  emit("update:engravingOrder");
};

const gravureStore = useGravureStore();
const { profiles, vendors, engravingOrderStatuses, engravingOrderConditions, designers } = storeToRefs(gravureStore);

const parameters = computed(() => [
  { label: 'Профиль', key: 'profile_id', options: profiles.value, optionLabel: 'code', optionValue: 'id' },
  { label: 'Грав. из профиля', key: "profile.vendor.name" }, // Пока без редактирования
  { label: 'Статус', key: 'status_id', options: engravingOrderStatuses.value, optionLabel: 'name', optionValue: 'id' },
  { label: 'Тех. состояние', key: 'condition_id', options: engravingOrderConditions.value, optionLabel: 'name', optionValue: 'id' },
  { label: 'Репро', key: 'repro_id', options: vendors.value, optionLabel: 'name', optionValue: 'id' },
  { label: 'Гравировка', key: 'engraver_id', options: vendors.value, optionLabel: 'name', optionValue: 'id' },
  { label: 'Менеджер ОСЗ', key: "order.support_manager_name"}, // Статичный текст
  { label: 'Спец. ОКВиД', key: 'designer_id', options: designers.value, optionLabel: 'name', optionValue: 'id' },
]);
</script>
