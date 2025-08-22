<template>
    <DataTable
        :value="engravingOrders"
        v-model:expandedRows="expandedRows"
        paginator
        :rows="perPage"
        :first="first"
        :totalRecords="totalRecords"
        :loading="loading"
        dataKey="id"
        stripedRows
        lazy
        @page="onPageChange"
    >
        <Column expander style="width: 5rem;" />
        <Column field="okvid_number" header="Оквид"></Column>
        <Column field="order.order_number" header="Заказ"></Column>
        <Column field="create_sample_date" header="Наименование">
            <template #body="slotProps">
                <span>{{ (slotProps.data.order?.items[0]?.item?.category ?? '')+'  '+(slotProps.data.order?.items[0]?.item?.brand ?? '') }}</span>
            </template>
        </Column>
        <Column field="macro_order.customer.name" header="Клиент"></Column>
        <template #expansion="slotProps">
            <div class="p-4">
                <DataTable :value="slotProps.data.engraving_order_shaft">
                    <Column  header="Вал">
                        <template #body="slotProps">
                            <Button @click="showRouteMap(slotProps.data)" link>
                                {{ slotProps.data?.shaft?.code }}
                            </Button>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </template>
        <template #header>
            <div class="flex justify-between items-center">
                <IconField>
                    <InputIcon>
                        <i class="pi pi-search" />
                    </InputIcon>
                    <InputText 
                        v-model="search" 
                        placeholder="Найти" 
                        @input="onSearchInput"
                    />
                </IconField>
            </div>
        </template>

        <!-- Колонки остаются без изменений -->
    </DataTable>
</template>

<script setup>
import { ref, watch, toRefs } from 'vue';
import { DataTable, Column, IconField, InputIcon, InputText, Button } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
    engravingOrders: Array,
    totalRecords: Number,
    filters: Object,
});

const { engravingOrders, totalRecords, filters } = toRefs(props);

const search = ref(filters.value?.search || ''); // Исправлено: безопасное обращение
const loading = ref(false);
const expandedRows = ref([]);
const perPage = ref(10);
const first = ref(0);

const onSearchInput = debounce(() => {
    fetchOrders();
}, 500);

const fetchOrders = () => {
    loading.value = true;
    router.get('/route-map', { 
        search: search.value,
        per_page: perPage.value
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => loading.value = false
    });
};

const onPageChange = (event) => {
    first.value = event.first;
    loading.value = true;
    router.get('/route-map', { 
        page: event.page + 1,
        per_page: perPage.value,
        search: search.value
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => loading.value = false
    });
};

// Инициализация при загрузке (безопасная проверка)
watch(() => props.filters, (newFilters) => {
    search.value = newFilters?.search || ''; // Исправлено: безопасное обращение
}, { immediate: true });

const showRouteMap = async (routeMap) => {
    router.visit(`/route-map/${routeMap.id}`);
};
</script>

