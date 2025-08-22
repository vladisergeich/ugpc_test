<template>
    <div class="col-span-full grid gap-2">
        <Card class="shadow-md rounded-lg border border-gray-200 p-4">
            <template #title>
                Заявка: {{ transfer.id }}
            </template>
            <template #subtitle>
                Статус: {{ transfer.status }}
            </template>
            <template #content>
                <div class="grid gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Дата заявки</span>
                                <div class="flex items-center">
                                    <InputText type="date" size="small" class="w-full" v-model="transfer.create_date" disabled/>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Дата отгрузки</span>
                                <div class="flex items-center">
                                    <InputText type="date" size="small" v-model="transfer.shipping_date" class="w-full" @change="updateApp"/>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Заполнил</span>
                                <div class="flex items-center">
                                        <Select 
                                        size="small" 
                                        class="w-full" 
                                        v-model="transfer.user_id" 
                                        :options="designers"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Выберите значение"
                                        @change="updateApp"
                                        />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Отправитель</span>
                                <div class="flex items-center">
                                    <Select 
                                    size="small" 
                                    class="w-full" 
                                    v-model="transfer.sender_id" 
                                    :options="warehouses"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Выберите значение"
                                    @change="updateApp"
                                    />                                 
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Получатель</span>
                                <div class="flex items-center">
                                    <Select 
                                    size="small" 
                                    class="w-full" 
                                    v-model="transfer.recipient_id" 
                                    :options="warehouses"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Выберите значение"
                                    @change="updateApp"
                                    /> 
                                </div>
                            </div>
                        </div>
                        <div class="gap-4">
                            <div class="flex flex-col">
                                <span class="text-gray-500 text-sm">Комментарий</span>
                                <div class="flex items-center">
                                    <Textarea size="small" v-model="transfer.note" class="w-full" placeholder="Комментарий" rows="4" @change="updateApp"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex gap-4 mt-2">
                    <Button label="Подбор валов" severity="secondary" size="small" class="w-full" @click="showShafts()"/>
                    <Button label="Упаковочный лист" severity="secondary" size="small" class="w-full" @click="upakList()"/>
                    <Button v-if="transfer.status == 'processing'" label="Подтвердить" severity="success" size="small" class="w-full" @click="confirmTransfer()"/>
                    <Button v-if="transfer.status == 'confirmed'" label="Учесть" size="small" class="w-full" />
                </div>
            </template>
        </Card>
        <DataTable
            :value="transfer.shafts"
            class="shadow-md rounded-lg border border-gray-200 p-4 bg-white"
        >
            <Column field="sequence" header="№"></Column>
            <Column field="shaft.code" header="ID вала"></Column>
            <Column field="shaft.ff" header="FF"></Column>
            <Column field="shaft.format" header="Формат"></Column>
            <Column header="Оквид"></Column>
            <Column header="Назначение">
                <template #body="{ data }">
                    <Select
                        v-model="data.type"
                        size="small"
                        class="w-full"
                        :options="types"
                        @change="updateSection(data)"
                    />
                </template>
            </Column>
            <Column class="w-2/12">
                <template #body="{ data }">
                    <i class="pi pi-trash" style="cursor: pointer;" @click="deleteSection(data)"></i>
                </template>
            </Column>
        </DataTable>
        <Dialog v-model:visible="visibleShafts" class="w-6/12" header="Подобрать вал" :modal="true">
            <div class="mb-2 flex justify-between items-center">
                <InputText v-model="search" placeholder="Поиск..." @input="debouncedSearch" class="w-1/3" />

                <Button
                    v-if="selectedShafts.length"
                    label="Добавить"
                    icon="pi pi-plus"
                    size="small"
                    @click="addMultipleShafts"
                    class="ml-2"
                />
            </div>

            <DataTable
                :value="shafts"
                selectionMode="multiple"
                v-model:selection="selectedShafts"
                dataKey="id"
                :lazy="true"
                :paginator="true"
                :rows="rows"
                :totalRecords="totalRecords"
                :first="(currentPage - 1) * rows"
                :loading="loading"
                @page="onPage"
                class="min-w-[50rem] text-sm"
            >
                <Column selectionMode="multiple" headerStyle="width: 3em" />
                <Column field="code" header="№" />
                <Column field="ff" header="FF" />
                <Column field="format" header="Формат" />
                <Column class="w-1/6">
                    <template #body="slotProps">
                        <Button v-if="!selectedShafts.length" type="button" size="small" @click="addShaft([slotProps.data])">Выбрать</Button>
                    </template>
                </Column>
            </DataTable>
        </Dialog>
    </div>
</template>

<script setup>
import { debounce } from 'lodash';
import { defineProps } from 'vue';
import { InputText,DataTable, Column, Card,Select,Textarea,Button,Dialog } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    transfer: Object,
    designers: Array,
    warehouses: Array,
});

const { transfer, designers, warehouses } = toRefs(props); 

const visibleShafts = ref(false);
const shafts = ref([]);
const totalRecords = ref(0);
const rows = 10;
const currentPage = ref(1);
const loading = ref(false);
const search = ref('');

const selectedShafts = ref([]);

const types = ref(['engraving','printing']);;

const toast = useToast();

const addShaft = async (shafts) => {
  try {
    const url = routeZiggy('transferShafts.create', {}, undefined, Ziggy);

    await router.post(url, { transfer: transfer.value, shafts: shafts }, {
      preserveScroll: true,
      preserveState: true,
    });

    visibleShafts.value = false;  
    toast.add({ severity: 'success', summary: 'Вал добавлен', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при добавлении вала', life: 3000 });
  }
};

const addMultipleShafts = () => {
    addShaft(selectedShafts.value);
    visibleShafts.value = false;  
};

const showShafts = () => {
  getShafts(1);
  visibleShafts.value = true;
};

const getShafts = async (page = 1) => {
  loading.value = true;

  try {
    const url = routeZiggy('transfers.getShafts', {
      page,
      search: search.value,
    }, undefined, Ziggy);

    const response = await axios.get(url);
    shafts.value = response.data.data;
    totalRecords.value = response.data.total;
    currentPage.value = response.data.current_page;
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка загрузки валов', life: 3000 });
  } finally {
    loading.value = false;
  }
};

const onPage = (event) => {
  const newPage = event.page + 1;
  getShafts(newPage);
};

const debouncedSearch = debounce(() => {
  getShafts(1);
}, 500);

const updateApp = async () => {
  try {
    const url = routeZiggy('transfers.update', {}, undefined, Ziggy);

    await router.post(url, transfer.value, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Заявка обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении заявки', life: 3000 });
  }
};

function updateSection(section) {

    const url = routeZiggy('transferShafts.update', {}, undefined, Ziggy);

    router.post(url, section, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция обновлена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка обновления секции', life: 3000 });
        },
    });
}

function deleteSection(section) {

    const url = routeZiggy('transferShafts.destroy', {}, undefined, Ziggy);

    router.post(url, section, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция удалена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка удаления секции', life: 3000 });
        },
    });
}

function confirmTransfer() {

    const url = routeZiggy('transfers.confirmTransfer', {}, undefined, Ziggy);

    router.post(url, transfer.value, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Заявка подтверждена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка подтверждения заявки', life: 3000 });
        },
    });
}



function upakList() {
  const url = routeZiggy('transfers.upakList', { id: transfer.value.id }, undefined, Ziggy);
  window.open(url, '_blank');
}

</script>
