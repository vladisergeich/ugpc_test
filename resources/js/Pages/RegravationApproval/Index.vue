<template>
    <div class="col-span-full">
      <div class="flex justify-between mb-2">
        <h3>Этапы согласования перегравировки</h3>
        <div class="col-md-6 flex justify-end">
          <Button label="+ Новая заявка" @click="openDialog" class="w-full" />
        </div>
      </div>
  
      <Dialog
        v-model:visible="displayDialog"
        header="Создание заявки на перегравировку"
        :modal="true"
        :style="{ width: '70vw' }"
        :maximizable="true"
      >
        <div class="mt-5">
          <template v-if="currentStep === 0">
            <div class="flex justify-center">
              <IconField class="gap-2 flex">
                <InputIcon>
                  <i class="pi pi-search" />
                </InputIcon>
                <InputText placeholder="Введите макро заказ" v-model="searchQuery" />
                <Button label="Поиск" @click="getMacro" />
              </IconField>
            </div>
  
            <DataTable
              v-if="macro?.shafts_in_work"
              :value="macro.shafts_in_work"
              v-model:selection="selectedShafts"
              selectionMode="multiple"
              dataKey="id"
              :paginator="true"
              :rows="10"
              :loading="loading"
            >
              <Column selectionMode="multiple" headerStyle="width: 3rem" />
              <Column field="shaft.code" header="Код" />
            </DataTable>
          </template>
  
          <template v-else-if="currentStep === 1">
            <div class="grid grid-cols-3 gap-5">
              <ShaftCard
                v-for="(shaft, index) in selectedShafts"
                :key="shaft.id"
                :shaft="shaft"
              />
            </div>
          </template>
        </div>
  
        <template #footer>
          <Button
            label="Назад"
            icon="pi pi-arrow-left"
            class="p-button-text"
            @click="prevStep"
            :disabled="currentStep === 0"
          />
          <Button
            :label="currentStep === steps.length - 1 ? 'Завершить' : 'Далее'"
            icon="pi pi-arrow-right"
            iconPos="right"
            @click="nextStep"
          />
        </template>
      </Dialog>
  
      <div class="grid grid-cols-8 gap-5">
        <div class="col-span-2 gap-4">
          <Filters :customers="customers" :stages="stages" @apply-filters="handleFilters"/>
        </div>
        <div class="col-span-6">
            <div class="grid gap-4">
                <AppCard
                    v-for="application in applications"
                    :key="application.id"
                    :application="application"
                />
            </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { router } from '@inertiajs/vue3';
  import Filters from './Filters.vue';
  import AppCard from './AppCard.vue';
  import ShaftCard from './ShaftCard.vue';
  import {
    DataTable,
    InputText,
    Button,
    Column,
    Dialog,
    Steps,
    IconField,
    InputIcon
  } from "@danaflex/ui/components";
  import { useToast } from 'primevue/usetoast';
  import axios from 'axios';
  import { ref, toRefs, computed } from "vue";
  import { route as routeZiggy } from "ziggy-js";
  import { Ziggy } from "@/ziggy.js";
  
  const props = defineProps({
    applications: Array,
    customers: Array,
    stages: Array,
    orders: Array,
  });

  
  const toast = useToast();
  const displayDialog = ref(false);


  const currentStep = ref(0);

  const searchQuery = ref('');
  const selectedShafts = ref([]);
  const loading = ref(false);
  const macro = ref(null);
  const shaftData = ref([]);
  
  const steps = [
    { label: 'Выбор валов' },
    { label: 'Заполнение данных' }
  ];
  
  const openDialog = () => {
    displayDialog.value = true;
  };

   const handleFilters = (filters) => {
        
        const url = routeZiggy('regravation.index', {}, undefined, Ziggy);
        router.get(url, filters, {
            preserveState: true,
            replace: true,
            only: ['applications'] // Обновляем только applications
        });
    };
  
  const getMacro = async () => {
    try {
      const url = routeZiggy('gravuredatabase.getMacro', { macro: searchQuery.value }, undefined, Ziggy);
      const response = await axios.get(url);
      macro.value = response.data;
      selectedShafts.value = [];
    } catch (error) {
      console.error('Ошибка при загрузке валов', error);
    }
  };
  
  const nextStep = () => {
    if (currentStep.value === 0 && selectedShafts.value.length === 0) {
      toast.add({ severity: 'warn', summary: 'Внимание', detail: 'Выберите хотя бы один вал', life: 3000 });
      return;
    }
  
    if (currentStep.value < steps.length - 1) {
      if (currentStep.value === 0) {
        shaftData.value = selectedShafts.value.map(shaft => ({
          shaftId: shaft.id,
          reason: null,
          comment: ''
        }));
      }
      currentStep.value++;
    } else {
        createApplication();
    }
  };
  
  const prevStep = () => {
    if (currentStep.value > 0) {
      currentStep.value--;
    }
  };
  
const createApplication = async () => {
  try {
    const url = routeZiggy('regravation.create', {}, undefined, Ziggy);

    await router.post(url, { shafts: selectedShafts.value, macro: macro.value}, {
      preserveScroll: true,
      preserveState: true,
    });

    displayDialog.value = false;
    macro.value = null;
    selectedShafts.value = [];
    currentStep.value = 0;
    toast.add({ severity: 'success', summary: 'Секции добавлены', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при добавлении секций', life: 3000 });
  }
};


</script>