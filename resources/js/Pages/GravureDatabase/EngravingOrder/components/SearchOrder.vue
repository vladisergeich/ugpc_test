<template>
    <div>
        <InputGroup>
            <InputText 
            size="small" 
            v-model="localSearch" 
            placeholder="Введите значение" 
            />
            <Button 
            size="small" 
            icon="pi pi-search" 
            @click="searchOrder" 
            />
        </InputGroup>
        <DataTable :value="results" v-if="results.length > 0">
            <Column field="okvid_number" header="ОКВиД"></Column>
            <Column field="order.order_number" header="Заказ"></Column>
        </DataTable>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import { DataTable, InputText, Column,InputGroup,Button } from "@danaflex/ui/components";
  
  const props = defineProps({
    mode: String,
    search: String,
    results: Array,
  });
  
  const emit = defineEmits(['update:search']);
  
  const localSearch = ref(props.search);
  
  const searchOrder = () => {
    emit('update:search', localSearch.value, props.mode);
  };
  </script>