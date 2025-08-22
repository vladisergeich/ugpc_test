<script setup>
import { defineProps,toRefs } from 'vue';
import { InputText, DataTable, Column, Paginator } from "@danaflex/ui/components";
import { ref, watch, computed } from 'vue';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  items: Object,
  filters: Object
})

const { items, filters } = toRefs(props);

const search = ref(filters.search || '')

function onSearch() {
  const route = routeZiggy('register.items', {}, undefined, Ziggy)
  router.get(route, { search: search.value }, { preserveState: true })
}

function onLazyLoad(event) {
  const route = routeZiggy('register.items', {}, undefined, Ziggy)
  router.get(route, {
    page: event.first / event.rows + 1,
    search: search.value
  }, { preserveState: true, replace: true })
}
</script>

<template>
    <div class="grid w-full">
        <DataTable 
            :value="items.data" :lazy="true" :paginator="true" :rows="10" :totalRecords="items.total" @page="onLazyLoad"
            stripedRows
            size="small"
            >
            <Column field="customer" header="Клиент"></Column>
            <Column field="code" header="Код ГП"></Column>
            <Column field="sap_code" header="SAP-код"></Column>
            <Column field="category" header="Категория"></Column>
            <Column field="brand" header="Бренд"></Column>
            <Column field="description" header="Название"></Column>
        </DataTable>
    </div>
</template>
