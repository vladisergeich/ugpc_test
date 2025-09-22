<template>
    <div class="col-span-full">
      <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-semibold">Заявки на перемещение</h2>
      </div>
  
      <DataTable :value="transfers" responsiveLayout="scroll">

        <template #header>
            <div class="flex justify-between items-center">
                <IconField>
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText v-model="search" placeholder="Найти" @input="fetchOrders" />
                </IconField>
                <Button label="Создать заявку" icon="pi pi-plus" primary size="small"  @click="createTransfer()" />
            </div>
        </template>

        <Column field="id" header="№ Заявки" :sortable="true"></Column>
        <Column field="shipping_date" header="Дата отгрузки" :sortable="true">
          <template #body="{ data }">
            {{ formatDate(data.shipping_date) }}
          </template>
        </Column>
        <Column field="sender.name" header="Отправитель" :sortable="true" />
        <Column field="recipient.name" header="Получатель" :sortable="true" />
        <Column field="status" header="Статус">
          <template #body="{ data }">
            <span
              :class="{
                'text-green-600': data.status === 'processing',
                'text-yellow-500': data.status === 'confirmed',
                'text-red-500': data.status !== 'processing' && data.status !== 'confirmed'
              }"
            >
              {{
                data.status === 'processing'
                  ? 'Открыто'
                  : data.status === 'confirmed'
                  ? 'Подтвержден'
                  : 'Учтено'
              }}
            </span>
          </template>
        </Column>
  
        <Column  header="">
            <template #body="slotProps">
                <Button label="Открыть" @click="showTransfer(slotProps.data)" size="small" link></Button>
            </template>
        </Column>
      </DataTable>
    </div>
  </template>
  
<script setup>
import { ref, computed } from 'vue';
import { DataTable, Column, IconField, InputIcon, InputText, Button, } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';

  const props = defineProps({
    transfers: Array,
  });

  const search = ref('');

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

  const showTransfer = async (transfer) => {
      router.visit(`/transfers/${transfer.id}`);
  };

  const createTransfer = () => {
      router.post('/transfers/create', {
          preserveScroll: true,  
          preserveState: true,   
      });
  };

</script>

<style scoped>

</style>