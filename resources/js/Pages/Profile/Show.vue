<template>
    <div class="col-span-full p-4 bg-white rounded-lg grid gap-6">
        <div class="flex gap-4">
            <Button size="small" label="Отправить в Navision" @click="sendToNav()" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Краткое название</label>
                <InputText size="small" v-model="profile.code" class="w-full" @change="updateProfile()"/>
            </div> 
            <div>
                <label class="block mb-1 text-sm font-medium">Аналог поставщика</label>
                <InputText size="small" v-model="profile.supplier_analog_icc" class="w-full" @change="updateProfile()"/>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Печать</label>
                <InputText size="small" v-model="profile.print_type" class="w-full" @change="updateProfile()"/>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Гравировщик</label>
                <Select
                    v-model="profile.engraver_id"
                    size="small"
                    class="w-full"
                    :options="vendors"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Выберите значение"
                    @change="updateProfile()"
                />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Первичка</label>
                <Select
                    v-model="profile.primary_material_id"
                    size="small"
                    class="w-full"
                    :options="materials"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Выберите значение"
                    @change="updateProfile()"
                />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Вторичка</label>
                <Select
                    v-model="profile.secondary_material_id"
                    size="small"
                    class="w-full"
                    :options="materials"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="Выберите значение"
                    @change="updateProfile()"
                />
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium">Серия красок</label>
                <InputText size="small" v-model="profile.paint_series" class="w-full" @change="updateProfile()"/>
            </div>
        </div>
        <div>
            <FingerPrint :profile="profile" />
        </div>
        <div>
            <PrintProcess :profile="profile" />
        </div>
    </div>
  </template>
  
<script setup>
import { Select, Dialog,Button,InputText } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";
import FingerPrint from './components/FingerPrint.vue';
import PrintProcess from './components/PrintProcess.vue';
  
const props = defineProps({
    profile: Object,
    vendors: Array,
    materials: Array,
});

const { profile } = toRefs(props); 
const toast = useToast();

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

function sendToNav() {

    const url = routeZiggy('profile.sendToNav', {}, undefined, Ziggy);

    router.post(url, profile.value, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Профиль обновлен', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка обновлении профиля', life: 3000 });
        },
    });
}

</script>
  