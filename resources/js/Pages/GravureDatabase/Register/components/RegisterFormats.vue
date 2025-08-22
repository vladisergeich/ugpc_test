<script setup>
import { defineProps, toRefs } from 'vue';
import { DataTable, Column } from "@danaflex/ui/components";
import { ref, watch, computed } from 'vue';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  items: Array,
  filters: Object
})

const { items, filters } = toRefs(props);

const search = ref(props.filters.search || '')

function onSearch() {
  const route = routeZiggy('register.formats', {}, undefined, Ziggy)
  router.get(route, { search: search.value }, { preserveState: true })
}

function onLazyLoad(event) {
  const route = routeZiggy('register.formats', {}, undefined, Ziggy)
  router.get(route, {
    page: event.first / event.rows + 1,
    search: search.value
  }, { preserveState: true, replace: true })
}
</script>

<template>
    <div class="grid w-full">
        <DataTable 
            :value="items.data" :lazy="true" :paginator="true" :rows="20" :totalRecords="items.total" @page="onLazyLoad"
            size="small" rowGroupMode="rowspan" groupRowsBy="shaft.code" sortMode="single" sortField="shaft.code" :sortOrder="1"
            >
            <Column field="shaft.code" header="ID вала" style="width: 40%">
              <template #body="{ data, index }">
                  <div>
                    <span>{{ data.shaft.code }}</span>
                  </div>
                  <div>
                    <span class="text-sm">( {{ data.shaft?.vendor?.name }} )</span>
                  </div>
              </template>
            </Column>
            <Column field="engraving_order.format" header="Формат" />
            <Column field="final_diameter" header="Диаметр" />
            <Column field="engraving_order.okvid_number" header="Оквид" />
            <Column field="engraving_order.order.order_number" header="Заказ" />
            <Column field="engraving_order.engraving_request_date" header="Дата гравировки (выгрузки заказа)" />
        </DataTable>
    </div>
</template>
