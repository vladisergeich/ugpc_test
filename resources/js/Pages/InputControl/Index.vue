
<template>
    <div class="col-span-full">
        <h1 class="text-2xl font-bold mb-4">Входной контроль</h1>
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

            <Column expander style="width: 5rem;" headerClass="bg-blue-100"/>
            <Column field="okvid_number" header="Оквид" headerClass="bg-blue-100"></Column>
            <Column field="order.order_number" header="Заказ" headerClass="bg-blue-100"></Column>
            <Column header="Наименование" headerClass="bg-blue-100" style="width: 10%">
                <template #body="{ data }">
                    {{ (data.order?.items[0]?.item?.category ?? '')+'  '+(data.order?.items[0]?.item?.brand ?? '')}}
                </template>
            </Column>
            <Column field="macro_order.customer.name" header="Клиент" headerClass="bg-blue-100"></Column>
            <template #expansion="{ data }">
                <DataTable :value="data.engraving_order_shaft" responsiveLayout="scroll">
                    <Column field="shaft.code" header="№ Вала"></Column>
                    <Column header="Печать">
                        <template #body="{ data }">
                        <Button icon="pi pi-print" label="Напечатать" severity="primary" size="small" @click="openLabel(data)" />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </DataTable>
        <Dialog v-model:visible="showModal" header="Печать бирки" :modal="true" class="w-96">
            <div v-if="selectedShaft" class="p-4 border rounded-md bg-gray-50" ref="modalLabelContent" id="modalLabelContent">
                <p class="font-bold text-m">Вал: {{ selectedShaft.shaft.code }}</p>
                <p>№: {{ selectedShaft.sequence_number }}</p>
                <p>№ сепарации: {{ selectedShaft.separation }}</p>
                <p>Формат: {{ selectedShaft.engraving_order.format }}</p>
                <p>№ Партии: {{ selectedShaft.engraving_order?.order?.order_number }}</p>
                <p class="font-bold text-m">Оквид: {{ selectedShaft.engraving_order.okvid_number }}</p>
                <p>Линиатура: {{ selectedShaft.lineature }}</p>
                <p>Угол: {{ selectedShaft.corner }}</p>
                <p>Резец: {{ selectedShaft.cutter }}</p>
                <p class="text-m font-bold">Цвет: {{ selectedShaft.color }}</p>
                <div v-html="qr_data" class="mt-4"></div>
            </div>
            <Button label="Распечатать" icon="pi pi-print" class="mt-3 w-full" @click="printLabel" />
        </Dialog>
    </div>
</template>

<script setup>
import { ref, watch, toRefs } from 'vue';
import { DataTable, Column, IconField, InputIcon, InputText, Dialog, Button} from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import printJS from 'print-js';
import QRCode from 'qrcode-generator';

const props = defineProps({
    engravingOrders: Array,
    totalRecords: Number,
    filters: Object,
});

const { engravingOrders, totalRecords, filters } = toRefs(props);
const showModal = ref(false);
const selectedShaft = ref(null);
const qr_data = ref(null);
const modalLabelContent = ref(null);


const search = ref(filters.value?.search || ''); // Исправлено: безопасное обращение
const loading = ref(false);
const perPage = ref(10);
const first = ref(0);

const onSearchInput = debounce(() => {
    fetchOrders();
}, 500);

const fetchOrders = () => {
    loading.value = true;
    router.get('/input-control', { 
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
    router.get('/input-control', { 
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

const expandedRows = ref([]);

const openLabel = (record) => {
  selectedShaft.value = record;
  qr_data.value = generateQRCode(`shaft|${record.id}`);
  showModal.value = true;
};

const generateQRCode = (text) => {
  const qr = QRCode(0, 'M');
  qr.addData(text);
  qr.make();
  return qr.createSvgTag();
};

const printLabel = () => {
  printJS({ printable: 'modalLabelContent', type: 'html' });
};

</script>
    