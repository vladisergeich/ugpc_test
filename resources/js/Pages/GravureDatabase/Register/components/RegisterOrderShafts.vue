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
  const route = routeZiggy('register.orderShafts', {}, undefined, Ziggy)
  router.get(route, { search: search.value }, { preserveState: true })
}

function onLazyLoad(event) {
  const route = routeZiggy('register.orderShafts', {}, undefined, Ziggy)
  router.get(route, {
    page: event.first / event.rows + 1,
    search: search.value
  }, { preserveState: true, replace: true })
}
</script>

<template>
    <div class="grid w-full">
        <DataTable 
            v-model:expandedRows="expandedRows"
            :value="items.data" :lazy="true" :paginator="true" :rows="10" :totalRecords="items.total" @page="onLazyLoad"
            stripedRows
            dataKey="id"
            size="small"
            >
            <Column expander style="width: 5rem" />
            <Column field="format" header="Формат">
                <template #body="{ data }">
                    {{ data.shafts_in_work[0]?.engraving_order.format }}
                </template>
            </Column>
            <Column field="ff" header="FF">
                <template #body="{ data }">
                    {{ data?.shafts_in_work[0]?.shaft.ff }}
                </template>
            </Column>
            <Column header="кол-во валов">
                <template #body="{ data }">
                    {{ data.shafts_in_work?.length }}
                </template>
            </Column>
            <Column field="create_date" header="Дата заказа"></Column>
            <Column field="id" header="Оквид(макро)"></Column>
            <Column field="customer.name" header="Клиент"></Column>
            <Column field="warehouse.name" header="Общее описание"></Column>
            <Column field="warehouse_place" header="Гравировщик"></Column>
            <Column field="" header="Дата последней гравировки"></Column>
            <Column field="type" header="Склад"></Column>
            <Column field="diameter" header="ГП"></Column>
            <Column field="" header="SAP-код"></Column>
            <Column field="" header="Дата печати"></Column>
            <template #expansion="slotProps">
                <div class="p-4">
                    <DataTable :value="slotProps.data.shafts_in_work">
                        <Column field="shaft.code" header="ID вала(актив)" sortable></Column>
                        <Column field="shaft.warehouse.name" header="Склад" sortable></Column>
                        <Column field="" header="Ресурс" sortable></Column>
                    </DataTable>
                </div>
            </template>
        </DataTable>
    </div>
</template>
