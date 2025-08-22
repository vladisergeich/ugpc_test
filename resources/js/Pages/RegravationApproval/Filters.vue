<template>
    <div class="p-5 bg-white rounded-lg shadow-sm space-y-5">
      <div class="flex items-center">
        <svg class="mr-3" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.434 19.3588C13.5505 19.3017 13.6487 19.213 13.7175 19.1029C13.7863 18.9929 13.8229 18.8657 13.8232 18.7359V14.9915C13.8232 14.8071 13.8971 14.6298 14.0294 14.4989L18.3376 10.2379C18.468 10.1091 18.5717 9.95566 18.6425 9.78652C18.7133 9.61738 18.7498 9.43588 18.75 9.25253V7.45354C18.7496 7.36155 18.7311 7.27053 18.6955 7.1857C18.6599 7.10087 18.608 7.02389 18.5426 6.95917C18.4772 6.89445 18.3997 6.84325 18.3145 6.80852C18.2294 6.77379 18.1382 6.75619 18.0462 6.75675H6.78491C6.39569 6.75675 6.08108 7.06784 6.08108 7.45354V9.25253C6.08108 9.62204 6.22959 9.97677 6.49352 10.2379L10.8017 14.4989C10.8669 14.5632 10.9188 14.64 10.9542 14.7245C10.9896 14.8091 11.0078 14.8999 11.0079 14.9915V19.4327C11.0079 19.95 11.5583 20.2865 12.0263 20.0549L13.434 19.3588Z" fill="#4A9DFF"/>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M22.2973 0.675676H2.7027C1.58321 0.675676 0.675676 1.58321 0.675676 2.7027V22.2973C0.675676 23.4168 1.58321 24.3243 2.7027 24.3243H22.2973C23.4168 24.3243 24.3243 23.4168 24.3243 22.2973V2.7027C24.3243 1.58321 23.4168 0.675676 22.2973 0.675676ZM2.7027 0C1.21004 0 0 1.21004 0 2.7027V22.2973C0 23.79 1.21004 25 2.7027 25H22.2973C23.79 25 25 23.79 25 22.2973V2.7027C25 1.21004 23.79 0 22.2973 0H2.7027Z" fill="#4A9DFF"/>
        </svg>
        <span class="text-base font-semibold">Фильтры</span>  
      </div>
  
      <div class="flex flex-col space-y-1">
        <Calendar  selectionMode="range" 
                  dateFormat="yy-mm-dd" showIcon
                  class="w-full"
                  :manualInput="false" />
      </div>
  
      <div class="flex flex-col space-y-1">
        <label class="text-base font-semibold text-gray-700">Клиент</label>
        <Dropdown :options="customers" v-model="filters.customer"
                  optionLabel="name" placeholder="Выберите клиента"
                  showClear class="w-full" />
      </div>
  
      <div class="flex flex-col space-y-1">
        <label class="text-base font-semibold text-gray-700">Статус заявки</label>
        <Dropdown  :options="statuses" 
                  placeholder="Выберите статус"
                  showClear class="w-full" />
      </div>
  
      <div class="flex flex-col space-y-1">
        <label class="text-base font-semibold text-gray-700">Заказ</label>
        <Dropdown  :options="orders" 
                  placeholder="Выберите заказ"
                  optionLabel="order_number"
                  showClear class="w-full" />
      </div>
  
      <div class="flex flex-col space-y-1">
        <label class="text-base font-semibold text-gray-700">Этап согласования</label>
        <Dropdown  :options="stages" 
                  placeholder="Выберите этап"
                  optionLabel="name"
                  showClear class="w-full" />
      </div>
  
      <div class="flex flex-col space-y-1">
        <label class="text-base font-semibold text-gray-700">Причина гравировки</label>
        <Dropdown  :options="reasons" 
                  placeholder="Выберите причину"
                  showClear class="w-full" />
      </div>
      <div class="pt-2">
        <Button label="Применить" @click="applyFilters"
                class="w-full" />
      </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Checkbox, Dropdown, Button, Calendar } from "@danaflex/ui/components";

const props = defineProps({
    applications: Array,
    customers: Array,
    stages: Array,
    orders: Array,
});

const emit = defineEmits(['apply-filters']);

const filters = ref({
  dateRange: null,
  customer: null,
  status: null,
  order: null,
  stage: null,
  reason: null
});

const applyFilters = () => {
  // Очищаем null значения
  const cleanFilters = Object.fromEntries(
    Object.entries(filters.value).filter(([_, v]) => v !== null)
  );
  
  emit('apply-filters', cleanFilters);
};

</script>
