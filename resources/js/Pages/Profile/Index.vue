<template>
    <div class="grid gap-4 col-span-full">
        <div>
            <div class="flex gap-4">
                <Button size="small" label="Добавить профиль" @click="showCreateModal = true" />
            </div>

            <Dialog v-model:visible="showCreateModal" header="Создание нового профиля" :modal="true" class="w-[500px]">
                <div class="grid gap-2">
                    <div>
                        <label class="block mb-1 text-sm font-medium">Краткое название</label>
                        <InputText size="small" v-model="newProfile.code" class="w-full" />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Аналог поставщика</label>
                        <InputText size="small" v-model="newProfile.supplier_analog_icc" class="w-full" />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Печать</label>
                        <InputText size="small" v-model="newProfile.print_type" class="w-full" />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Гравировщик</label>
                        <Select
                            v-model="newProfile.engraver_id"
                            size="small"
                            class="w-full"
                            :options="vendors"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Выберите значение"
                        />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Первичка</label>
                        <Select
                            v-model="newProfile.primary_material_id"
                            size="small"
                            class="w-full"
                            :options="materials"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Выберите значение"
                        />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Вторичка</label>
                        <Select
                            v-model="newProfile.secondary_material_id"
                            size="small"
                            class="w-full"
                            :options="materials"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Выберите значение"
                        />
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium">Серия красок</label>
                        <InputText size="small" v-model="newProfile.paint_series" class="w-full" />
                    </div>
                </div>
                <template #footer>
                    <Button label="Создать" @click="createProfile" />
                </template>
            </Dialog>
        </div>

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-3">
                <Listbox 
                :options="vendors" 
                optionLabel="name" 
                v-model="selectedVendor" 
                @change="loadProfiles"
                class="w-full"
                />
            </div>
            <div class="col-span-9">
                <Card v-if="selectedVendor">
                <template #title>Профили поставщика: {{ selectedVendor.name }}</template>
                <template #content>
                    <DataTable :value="profiles" responsiveLayout="scroll">
                        <Column field="code" header="Профиль">
                            <template #body="slotProps">
                                <Button @click="showProfile(slotProps.data)" link>
                                    {{ slotProps.data.code }}
                                </Button>
                            </template>
                        </Column>
                        <Column field="print_type" header="Печать" />
                        <Column field="vendor.name" header="Гравировщик" />
                        <Column field="primary.name" header="Первичка" />
                        <Column field="secondary.name" header="Вторичка" />
                        <Column field="paint_series" header="Серия красок" />
                    </DataTable>
                </template>
                </Card>
                <div v-else class="text-center text-gray-500 py-8 bg-white">
                Выберите поставщика слева, чтобы просмотреть профили.
                </div>
            </div>
        </div>
    </div>
  </template>
  
<script setup>
import { DataTable, Column, Listbox, Card,Button,Dialog,InputText,Select } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";

const props = defineProps({
    vendors: Array,
    materials: Array,
    profiles: Array
});

const { vendors, profiles } = toRefs(props); 

const toast = useToast();
const selectedVendor = ref(null);

const showCreateModal = ref(false);
const newProfile = ref({
  short_name: '',
  print: '',
  paint_series: '',
  primary: '',
  secondary_housing: '',
});

const loadProfiles = async () => {
    try {
        const url = routeZiggy('profile.getProfiles', {}, undefined, Ziggy);

        await router.post(url, selectedVendor.value , {
            preserveScroll: true,
            preserveState: true,
        });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при загрузке профилей', life: 3000 });
    }
};

const showProfile = async (profile) => {
    router.visit(`/profile/${profile.id}`);
};


function createProfile() {

    const url = routeZiggy('profile.create', {}, undefined, Ziggy);

    router.post(url, newProfile.value, {
        onSuccess: () => {
        toast.add({ severity: 'success', summary: 'Профиль создан', life: 3000 });
        showCreateModal.value = false;
        },
        onError: () => {
        toast.add({ severity: 'error', summary: 'Ошибка создания профиля', life: 3000 });
        },
    });
}

</script>