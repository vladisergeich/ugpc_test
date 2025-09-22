<template>
    <div class="col-span-full">
      <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-semibold">Заявка на изготовление стальной заготовки</h2>
      </div>
  
      <DataTable :value="steelApps" responsiveLayout="scroll">

        <template #header>
            <div class="flex justify-between items-center">
                <IconField>
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText v-model="search" placeholder="Найти" @input="fetchOrders" />
                </IconField>
                <Button label="Создать заявку" icon="pi pi-plus" primary size="small" @click="createApp"/>
            </div>
        </template>

        <Column field="id" header="№ Заявки" :sortable="true">
          <template #body="{ data }">
            <span class="font-semibold">{{ data.id }}</span>
          </template>
        </Column>
  
        <Column field="create_date" header="Дата заявки" :sortable="true">
          <template #body="{ data }">
            {{ formatDate(data.create_date) }}
          </template>
        </Column>
  
        <Column field="customer.name" header="Клиент" :sortable="true" />
        <Column field="engraver.name" header="Изготовитель" :sortable="true" />
        <Column field="shaft_quantity" header="Кол-во валов" :sortable="true" />
        <Column  header="">
            <template #body="slotProps">
                <Button label="Открыть" @click="showApp(slotProps.data)" size="small" link></Button>
            </template>
        </Column>
      </DataTable>
    </div>
  </template>
  
<script setup>
  import { ref, computed } from 'vue';
  import { DataTable, Column, IconField, InputIcon, InputText, Button, } from "@danaflex/ui/components";
  import { useToast } from "primevue/usetoast";
  import { router } from '@inertiajs/vue3';
  import { route as routeZiggy } from "ziggy-js";
  import { Ziggy } from "@/ziggy.js"

  const props = defineProps({
    steelApps: Array
  });

  const search = ref('');
  const toast = useToast();

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

  const showApp = async (app) => {
      router.visit(`/steelApp/${app.id}`);
  };

  const createApp = async () => {
      try {
          const url = routeZiggy('steelApp.create', {}, undefined, Ziggy);

          await router.post(url, {
              preserveScroll: true,
              preserveState: true,
          });

          toast.add({ severity: 'success', summary: 'Заявка создана', life: 3000 });

      } catch (error) {
          toast.add({ severity: 'error', summary: 'Ошибка при создании заявки', life: 3000 });
      }
  };

</script>

<style scoped>

</style>