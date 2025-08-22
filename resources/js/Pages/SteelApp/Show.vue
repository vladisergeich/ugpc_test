<template>
    <div class="col-span-full grid gap-2">
        <Card class="shadow-md rounded-lg border border-gray-200 p-4 bg-white">
            <template #title>
                Заявка: {{ steelApp.id }}
            </template>
            <template #content>
                <div class="grid gap-4 col-span-full grid-cols-10">
                    <div class="gap-4 col-span-4 gap-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Дата заявки</span>
                                <div class="flex items-center">
                                    <DatePicker size="small" class="w-full" v-model="steelApp.create_date" @change="updateApp"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Заявку оформил</span>
                                <div class="flex items-center">
                                    <Select 
                                        size="small" 
                                        class="w-full" 
                                        :options="designers"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Выберите значение"
                                        @change="updateApp" 
                                        v-model="steelApp.manager_id"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">№ партии</span>
                                <div class="flex items-center">
                                    <InputText 
                                        size="small" 
                                        class="w-full" @change="updateApp" v-model="steelApp.order_number"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Изготовитель</span>
                                <div class="flex items-center">
                                    <Select 
                                        size="small" 
                                        class="w-full" 
                                        :options="vendors"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Выберите значение"
                                        @change="updateApp" 
                                        v-model="steelApp.vendor_id"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Гравируем</span>
                                <div class="flex items-center">
                                    <Select 
                                        size="small" 
                                        class="w-full" 
                                        :options="vendors"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Выберите значение"
                                        @change="updateApp" 
                                        v-model="steelApp.engraver_id"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Формат</span>
                                <div class="flex items-center">
                                    <InputText 
                                        type="number" 
                                        size="small" 
                                        class="w-full" @change="updateApp" v-model="steelApp.format"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Кол-во валов</span>
                                <div class="flex items-center">
                                    <InputText
                                        type="number" 
                                        size="small" 
                                        class="w-full" @change="updateApp" v-model="steelApp.shaft_quantity"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Длина гильзы</span>
                                <div class="flex items-center">
                                    <Select
                                        v-model="steelApp.sleeve_length"
                                        size="small"
                                        class="w-full"
                                        :options="sleeveLenghtArr"
                                        placeholder="Выберите значение"
                                        @change="updateApp"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Длина цилиндра</span>
                                <div class="flex items-center">
                                    <Select
                                        v-model="steelApp.cilynder_length"
                                        size="small"
                                        class="w-full"
                                        :options="sleeveLenghtArr"
                                        placeholder="Выберите значение"
                                        @change="updateApp"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Клиент</span>
                                <div class="flex items-center">
                                    <Select 
                                        size="small" 
                                        class="w-full" @change="updateApp" v-model="steelApp.customer_id"/>
                                </div>
                            </div>
                        </div>
                        <div class="grid mt-2">
                            <div class="flex flex-col gap-2">
                                <span class="text-gray-500 text-sm">Комментарий</span>
                                <div class="flex items-center">
                                    <Textarea size="small" class="w-full" placeholder="Комментарий" rows="4" @change="updateApp" v-model="steelApp.note"/>
                                </div>
                            </div>
                        </div>
                        <div class="grid">
                            <UploadFiles :steelApp="steelApp"/>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <DataTable
                        :paginator="true"
                        :rows="10"
                        stripedRows
                            :value="steelApp?.shafts"
                            class="rounded-lg border border-gray-200 p-4 bg-sky-100"
                        >

                            <template #header>
                                <div class="flex justify-end items-center gap-4">
                                    <Button label="Добавить вал" icon="pi pi-plus" primary size="small" raised @click="visibleAddShafts = true"/>
                                    <Button label="Удалить все" severity="secondary" size="small" @click="deleteAllShaft()" raised />
                                </div>
                            </template>
                            <Column field="sequence" header="№"></Column>
                            <Column field="shaft.code" header="ID вала"></Column>
                            <Column field="shaft.ff" header="FF"></Column>
                            <Column field="shaft.diameter" header="Диаметер"></Column>
                            <Column field="shaft_ra" header="RA"></Column>
                            <Column field="shaft_rz" header="RZ"></Column>
                            <Column class="w-2/12">
                                <template #body="slotProps">
                                    <i class="pi pi-trash" style="cursor: pointer;" @click="deleteShaft(slotProps.data)"></i>
                                </template>
                            </Column>
                        </DataTable>
                        <Dialog v-model:visible="visibleAddShafts" class="w-2/12" header="Добавить вал" :modal="true">
                            <div class="col-span-full grid gap-4">
                                <div class="grid gap-2">
                                    <span class="text-gray-500 text-sm">ID вала</span>
                                    <InputText size="small" class="w-full" v-model="shaftId"/>
                                </div>
                                <div class="grid gap-2">
                                    <span class="text-gray-500 text-sm">FF</span>
                                    <Select
                                        v-model="ff"
                                        size="small"
                                        class="w-full"
                                        :options="ffArr"
                                        placeholder="Выберите значение"
                                    />
                                </div>
                                <div class="grid gap-2">
                                    <span class="text-gray-500 text-sm">Количество</span>
                                    <InputNumber size="small" class="w-full" v-model="qtyShaft"/>
                                </div>
                            </div>
                            <template #footer>
                                <Button label="Добавить вал" icon="pi pi-plus" class="w-full" @click="addShaft" />
                            </template>
                        </Dialog>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-4 mt-1">
                    <Button label="Отправить заявку" size="small" @click="sendApp()"/>
                </div>
            </template>
        </Card>
    </div>
</template>

<script setup>
import UploadFiles from './components/UploadFiles.vue';
import { defineProps } from 'vue';
import { DataTable, Column, Card,DatePicker,Select,Textarea,Button,Dialog,InputText,InputNumber } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { useToast } from "primevue/usetoast";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js"

const props = defineProps({
    steelApp: Object,
    vendors: Array,
    designers: Array,
});

const { steelApp, vendors, designers } = toRefs(props); 
const visibleAddShafts = ref();
const shaftId = ref(null);
const ff = ref(null);
const qtyShaft = ref();
const toast = useToast();

const sleeveLenghtArr = ref([null,1370,1360,1380,1400,2108]);
const ffArr = ref(['HS','RM','SC']);

const addShaft = async () => {
    try {
        const url = routeZiggy('steelAppShafts.create', {}, undefined, Ziggy);

        await router.post(url, { qty: qtyShaft.value, ff: ff.value, shaftId: shaftId.value, steelApp: steelApp.value }, {
            preserveScroll: true,
            preserveState: true,
        });

        visibleAddShafts.value = false;
        toast.add({ severity: 'success', summary: 'Валы добавлены', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при добавлении валов', life: 3000 });
    }
};

const sendApp = async () => {
    try {
        const url = routeZiggy('steelApp.sendApp', {}, undefined, Ziggy);

        await router.post(url, steelApp.value, {
            preserveScroll: true,
            preserveState: true,
        });

        toast.add({ severity: 'success', summary: 'Заявка отправлена', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при отправлении заявки', life: 3000 });
    }
};

const updateApp = async () => {
  try {
    const url = routeZiggy('steelApp.update', {}, undefined, Ziggy);

    await router.post(url, steelApp.value, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Заявка обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении заявки', life: 3000 });
  }
};

const deleteShaft = async (shaft) => {
  try {
    const url = routeZiggy('steelAppShafts.destroy', {}, undefined, Ziggy);

    await router.post(url, shaft, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Заявка обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении заявки', life: 3000 });
  }
};

const deleteAllShaft = async (shaft) => {
  try {
    const url = routeZiggy('steelAppShafts.destroyAll', {}, undefined, Ziggy);

    await router.post(url, { app: steelApp.value }, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Заявка обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении заявки', life: 3000 });
  }
};


</script>
