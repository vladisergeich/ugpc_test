<script setup>
import { ref, computed, defineProps,toRefs } from 'vue';
import {
  InputText,
  InputNumber,
  DataTable,
  Column,
  Button,
  InputIcon,
  Tag,
  Dialog,
  IconField,
  Select,
  MultiSelect
} from "@danaflex/ui/components";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { usePage } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useToast } from "primevue/usetoast";


const props = defineProps({
  items: Object,
  states: Array,
  statuses: Array,
  warehouses: Array,
  formats: Array,
})

const filters = ref({
    format: { value: null, matchMode: FilterMatchMode.IN },
});

const formatOptions = computed(() =>
  Object.entries(formats.value).map(([value, label]) => ({ value, label }))
);

const { items, states, statuses, warehouses,formats } = toRefs(props);

const expandedRows = ref([]);
const toast = useToast();
const search = ref(filters.search || '')
const searchResource = ref('');
const macro = ref(null);
const displayDialog = ref(false);
const resource = ref({
  order: '',
  footage: '',
  date: '',
});


const openDialog = () => {
    displayDialog.value = true;
};

const stateOptions = computed(() =>
  Object.entries(states.value).map(([value, label]) => ({ label, value }))
);

const statusesOptions = computed(() =>
  Object.entries(statuses.value).map(([value, label]) => ({ label, value }))
);

function onSearch() {
  const route = routeZiggy('register.shafts', {}, undefined, Ziggy)
  router.get(route, { search: search.value }, { preserveState: true })
}

function onLazyLoad(event) {
  const route = routeZiggy('register.shafts', {}, undefined, Ziggy);

  const appliedFilters = event.filters?.format?.value ?? null;

  router.get(route, {
    page: event.first / event.rows + 1,
    search: search.value,
    format: appliedFilters, // 💥 передаем сюда фильтр формата
  }, {
    preserveState: true,
    replace: true
  });
}


const page = usePage();
const user = computed(() => page.props.user);

// Состояние для отображения истории
const showHistory = ref(false);
const historyShaft = ref(null);
const editingRows = ref([]);

// Определение доступных для редактирования полей
const allowedFields = computed(() => {
  const userId = user.value?.id;
  if (userId === 783) return ['warehouse_id'];
  if (userId === 782) return ['note','warehouse_id'];
  return [];
});

// Получение истории вала
const getHistory = async (shaftId) => {
  try {
    const url = routeZiggy('register.getHistory', { shaft: shaftId }, undefined, Ziggy);
    const response = await axios.get(url);
    historyShaft.value = response.data;
    showHistory.value = true;
  } catch (error) {
    console.error('Ошибка при загрузке истории вала:', error);
  }
};

const updateShaft = async (shaft) => {
  try {
    const url = routeZiggy('shafts.update', {}, undefined, Ziggy);

    await router.post(url, shaft, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Вал обновлен', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении вала', life: 3000 });
  }
};

const addResource = async () => {
  try {
    const url = routeZiggy('shaftResource.create', {}, undefined, Ziggy);

    await router.post(url, {macro: macro.value, resource: resource.value}, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Ресурс добавлен', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при добавлении ресурса', life: 3000 });
  }
};

const deleteResource = async (resource) => {
  try {
    const url = routeZiggy('shaftResource.destroy', {}, undefined, Ziggy);

    await router.post(url, {macro: macro.value, resource: resource}, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Ресурс удален', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при удалении ресурса', life: 3000 });
  }
};



function onFilter(event) {
  onLazyLoad(event); // просто передаём всё в onLazyLoad
}

// Очистка фильтров
const clearFilter = () => {
  filters.value = initFilters();
};

// Сохранение изменений в строке
const onRowEditSave = ({ newData, index }) => {
  items.value[index] = newData;
  updateShaft(newData);
};

const formatDate = (text) => {
  if (text != null) {
      return new Date(text).toLocaleDateString('ru-RU', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
      });
  }
  return '';
  };

// Определение уровня важности статуса
const getSeverity = (status) => {
  const severityMap = {
    repair: 'danger',
    free: 'success',
    engraving: 'info',
    refuse: 'warn',
    renewal: null,
  };
  return severityMap[status];
};

const getMacro = async () => {
  try {
    const url = routeZiggy('gravuredatabase.getMacro', { macro: searchResource.value }, undefined, Ziggy);
    const response = await axios.get(url);
    macro.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке валов', error);
  }
};
</script>

<template>
  <div class="grid w-full">
    <DataTable
      :value="items.data" 
      :lazy="true" 
      :paginator="true" 
      :rows="10" 
      :totalRecords="items.total" 
      @page="onLazyLoad"
      size="small"
      v-model:editingRows="editingRows"
      editMode="row"
      v-model:filters="filters"
      filterDisplay="row"
      @filter="onFilter"
      :globalFilterFields="['format']"
      @row-edit-save="onRowEditSave"
    >
      <template #header>
        <div class="flex flex-start gap-3">
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText size="small" placeholder="Поиск" />
          </IconField>
          <Button
            type="button"
            icon="pi pi-filter-slash"
            label="Очистить"
            size="small"
            severity="secondary"
            outlined
            @click="clearFilter"
          />
          <Button label="Добавить ресурс" @click="openDialog" />
        </div>
      </template>

      <Column header="ID">
        <template #body="{ data }">
          <Button :label="data.code" @click="getHistory(data.id)" variant="link" />
        </template>
      </Column>

      <Column field="format" header="Формат" filterField="format" :showFilterMenu="false" style="width: 10%; max-width: 150px;">
        <template #filter="{ filterModel, filterCallback }">
          <MultiSelect
            size="small"
            v-model="filterModel.value"
            @change="filterCallback()"
            :options="formatOptions"
            optionLabel="label"
            placeholder="Выберите формат"
            optionValue="value"
          />
        </template>
      </Column>

      <Column field="ff" header="FF" />
      <Column field="vendor.name" header="Изготовитель" />
      <Column field="manufacture_date" header="Дата заказа" />

      <Column field="state" header="Состояние" sortable filterMatchMode="equals">
        <template #body="{ data }">
          <Tag :value="data.state_text" :severity="getSeverity(data.state)" />
        </template>
        <template #editor="{ data }">
          <Select 
            size="small" 
            v-model="data.state"
            :options="stateOptions" 
            optionLabel="label" 
            optionValue="value" 
            class="w-full"
          />
        </template>
      </Column>

      <Column field="status" header="Статус" bodyClass="text-center">
        <template #body="{ data }">
          <i
            class="pi"
            v-tooltip="data.status_text"
            :class="{
              'pi-check-circle text-green-500': data.status == 'normal',
              'pi-times-circle text-red-500': data.status == 'rejected'
            }"
          ></i>
        </template>
        <template #editor="{ data }">
          <Select 
            size="small" 
            v-model="data.status"
            :options="statusesOptions" 
            optionLabel="label" 
            optionValue="value" 
            class="w-full"
          />
        </template>
      </Column>

      <Column field="warehouse.name" header="Склад">
        <template #editor="{ data }">
          <Select 
            size="small" 
            v-model="data.warehouse_id"
            :options="warehouses" 
            optionLabel="name" 
            optionValue="id" 
            class="w-full"
          />
        </template>
      </Column>

      <Column field="warehouse_place" header="Ячейка">
        <template #editor="{ data }">
          <InputText size="small" v-model="data.warehouse_place" fluid />
        </template>
      </Column>

      <Column header="Оквид(последний)" />
      <Column field="type" header="Тип" />
      <Column field="diameter" header="Диаметр" />
      <Column header="Ресурс" />

      <Column field="note" header="Примечание" style="width: 7%; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        <template #body="{ data }">
          <span v-tooltip="data.note">{{ data.note }}</span>
        </template>
        <template #editor="{ data }">
          <InputText
            v-if="allowedFields.includes('note')"
            size="small"
            v-model="data.note"
            fluid
          />
        </template>
      </Column>

      <Column :rowEditor="true" style="width: 5%; min-width: 8rem" bodyStyle="text-align:center" />
    </DataTable>

    <Dialog
        v-model:visible="displayDialog"
        header="Добавить ресурс"
        :modal="true"
        :style="{ width: '30vw' }"
        :maximizable="true"
      >
        <IconField class="gap-2 flex w-full">
          <InputIcon>
            <i class="pi pi-search" />
          </InputIcon>
          <InputText placeholder="Введите макро заказ" v-model="searchResource" class="w-full"/>
          <Button label="Поиск" @click="getMacro" />
        </IconField>
          
        <div class="mt-4" v-if="macro?.shafts_in_work">
          <span class="text-color">Введите значения для всех валов сразу</span>
          <div class="flex gap-3 mt-2">
            <InputText
              size="small"
              placeholder="Введите партию"
              v-model="resource.order"
              fluid
            />
            <InputText
              type="number"
              size="small"
              placeholder="Введите метраж"
              v-model="resource.footage"
              fluid
            />
            <InputText
              type="date"
              placeholder="Введите дату"
              size="small"
              v-model="resource.date"
              fluid
            />
            <Button label="+" @click="addResource()" />
          </div>
          <DataTable
            :value="macro.shafts_in_work"
            dataKey="id"
            class="mt-2"
            :paginator="true"
            :rows="10"
            :loading="loading"
            v-model:expandedRows="expandedRows"
          >
            <Column expander style="width: 5rem;" />
            <Column field="shaft.code" header="Вал" />
            <Column field="engraving_order.okvid_number" header="Оквид" />
            <template #expansion="slotProps">
              <div class="p-4">
                  <DataTable :value="slotProps.data.resources">
                      <Column field="order_number"  header="Номер партии" />
                      <Column field="footage"  header="Метраж" />
                      <Column field="print_date" header="Дата печати" :sortable="true">
                        <template #body="{ data }">
                          {{ formatDate(data.print_date) }}
                        </template>
                      </Column>
                      <Column class="w-2/12">
                          <template #body="slotProps">
                              <i class="pi pi-trash" style="cursor: pointer;" @click="deleteResource(slotProps.data)"></i>
                          </template>
                      </Column>
                  </DataTable>
              </div>
            </template>
          </DataTable>
        </div>

    </Dialog>

    <Dialog v-model:visible="showHistory" modal header="История вала" :style="{ width: '50%' }">
      <DataTable :value="historyShaft" responsiveLayout="scroll" stripedRows>
        <Column field="engraving_order.order.order_number" header="Номер заказа" />
        <Column header="Оквид">
            <template #body="{ data }">
            {{ data.engraving_order.okvid_number }}
            </template>
        </Column>
        <Column field="engraving_order.format" header="Формат" />
        <Column field="engraving_order.engraver.name" header="Гравировщик" />
        <Column field="write_off_date" header="Дата списания" />
      </DataTable>
    </Dialog>
  </div>
</template>
