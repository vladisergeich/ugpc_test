<template>
    <DataTable size="small" :value="profile?.finger_print_colors" class="w-full text-sm">
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <span class="text-lg font-bold">Спецификация фингерпринта</span>
            </div>
            <div class="flex gap-4 mb-2">
                <div>
                    <label class="block mb-1 text-sm font-medium">Id-номер комплекта цилиндров</label>
                    <InputText size="small" v-model="profile.fingerprint_parameters.cylinder_set_id" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Производитель</label>
                    <InputText size="small" v-model="profile.fingerprint_parameters.vendor" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Номер заказа</label>
                    <InputText size="small" v-model="profile.fingerprint_parameters.order_number" class="w-full" @change="updateProfile()"/>
                </div> 
            </div>
            <div class="flex">
                <Button size="small" icon="pi pi-plus" severity="success" class="mr-2" @click="addSection()" />
            </div>
        </template>
        <Column field="sequence" header="№" headerClass="bg-violet-50" ></Column>
        <Column field="color" header="Краска" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.color"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="pigment_paste" header="Пигментная паста" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.pigment_paste"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="technical_lacquer" header="Технический лак" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.technical_lacquer"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="base_lacquer" header="Базовый лак" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.base_lacquer"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="lineature" header="Линиатура" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.lineature"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="corner" header="Угол" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.corner"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="cutter" header="Резец" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.cutter"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="note" header="Примечание" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.note"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column headerClass="bg-violet-50">
            <template #body="{ data }">
                <i class="pi pi-trash" style="cursor: pointer;" @click="deleteSection(data)"></i>
            </template>
        </Column>
    </DataTable>
</template>
  
<script setup>
import { InputText, DataTable,Button,Column } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";
  
const props = defineProps({
    profile: Object,
});

const { profile } = toRefs(props); 
const toast = useToast();

function addSection() {

    const url = routeZiggy('profileFingerPrint.create', {}, undefined, Ziggy);

    router.post(url, profile.value, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция добавлена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка добавления секции', life: 3000 });
        },
    });
}

function updateProfile() {

    const url = routeZiggy('profile.update', {}, undefined, Ziggy);

    router.post(url, profile.value, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Профиль обновлен', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка обновлении профиля', life: 3000 });
        },
    });
}

function updateSection(section) {

    const url = routeZiggy('profileFingerPrint.update', {}, undefined, Ziggy);

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

    const url = routeZiggy('profileFingerPrint.destroy', {}, undefined, Ziggy);

    router.post(url, section, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция удалена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка удаления секции', life: 3000 });
        },
    });
}

</script>
  