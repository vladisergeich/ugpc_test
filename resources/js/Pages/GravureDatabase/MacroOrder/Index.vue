<template>
    <div class="col-span-full">

        <h1 class="text-2xl text-color mb-4">ОКВиД</h1>

        <DataTable
            v-model:filters="filters"
            :value="macroOrders"
            :paginator="true"
            :rows="10"
            stripedRows       
        >
            <template #header>
                <div class="flex justify-between items-center">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Найти" />
                    </IconField>
                    <Button label="Создать ОКВиД" @click="addMacroOrder = true" />
                </div>
            </template>

            <Column field="id" header="№ ОКВиД"></Column>
            <Column field="create_date" header="Дата создания"></Column>
            <Column field="customer.name" header="Клиент"></Column>
            <Column field="note" header="Примечание"></Column>
            <Column header="Действия" :exportable="false">
                <template #body="slotProps">
                    <Button @click="showMacroOrder(slotProps.data)" link>
                        Открыть
                    </Button>
                </template>
            </Column>
        </DataTable>
        </div>
        <Dialog v-model:visible="addMacroOrder" class="w-[400px]" header="Создать макрозаказ" :modal="true">
            <Select size="small" v-model="customerId" :options="customers" filter optionLabel="name" optionValue="id" placeholder="Выберите клиента" class="w-full">
                
            </Select>
            <template #footer>
            <Button label="Отмена" icon="pi pi-times" text @click="addMacroOrder = false" />
            <Button label="Создать" icon="pi pi-check" @click="createMacroOrder" />
            </template>
        </Dialog>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { DataTable, InputText, Button, Column, IconField, InputIcon, Dialog, Select } from "@danaflex/ui/components";

const props = defineProps({
    macroOrders: Array,
    customers: Array,
});

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});


const addMacroOrder = ref(false);
const customerId = ref(null);

const showMacroOrder = async (macroOrder) => {
    router.visit(`macro-orders/${macroOrder.id}`);
};

const createMacroOrder = () => {
    router.post('/macro-orders/create', {customerId: customerId.value } , {
        preserveScroll: true,  
        preserveState: true,   
    });

    addMacroOrder.value = false;
};

</script>
