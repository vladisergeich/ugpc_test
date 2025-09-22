<template>
    <DataTable size="small" :value="profile?.print_process_colors" class="w-full text-sm">
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <span class="text-lg font-bold">Параметры печатного процесса:</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-2">
                <div>
                    <label class="block mb-1 text-sm font-medium">Печатная машина</label>
                    <InputText size="small" v-model="profile.print_process_parameters.print_machine" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Тип печати</label>
                    <InputText size="small" v-model="profile.print_process_parameters.print_type" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Скорость печати</label>
                    <InputText size="small" v-model="profile.print_process_parameters.print_speed" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Субстрат (тип/марка/поставщик)</label>
                    <InputText size="small" v-model="profile.print_process_parameters.substrate" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Тип обработки материала</label>
                    <InputText size="small" v-model="profile.print_process_parameters.type_material_process" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Поверхностное натяжение материала</label>
                    <InputText size="small" v-model="profile.print_process_parameters.surface_tension_material" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Производитель / серия красок</label>
                    <InputText size="small" v-model="profile.print_process_parameters.manufacturer_paint_series" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Температура воздуха</label>
                    <InputText size="small" v-model="profile.print_process_parameters.air_temperature" class="w-full" @change="updateProfile()"/>
                </div> 
                <div>
                    <label class="block mb-1 text-sm font-medium">Влажность</label>
                    <InputText size="small" v-model="profile.print_process_parameters.humidity" class="w-full" @change="updateProfile()"/>
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
        <Column field="viscosity" header="Вязкость" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.viscosity"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="solvent" header="Растворитель" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.solvent"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="inhibitor.value" header="Замедлитель" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.inhibitor.value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="inhibitor.percentage" header="% Замедлителя" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.inhibitor.percentage"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="coordinates_lab" header="Координаты LCH" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.coordinates_lab"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="eltex_value" header="Значение Eltex" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.eltex_value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raquel" header="Ракель" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                size="small"
                class="w-full"
                v-model="data.raquel"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="optical_density.value" header="Оптическая плотность" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.optical_density.value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_50.value" header="TVI 50" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_50.value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_50.admission" header="+/-" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_50.admission"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_15.value" header="TVI 15" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_15.value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_15.admission" header="+/-" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_15.admission"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_5.value" header="TVI 5" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_5.value"
                @change="updateSection(data)"
                />
            </template>
        </Column>
        <Column field="raster_tone_5.admission" header="+/-" headerClass="bg-violet-50">
            <template #body="{ data }">
                <InputText
                type="number"
                size="small"
                class="w-full"
                v-model="data.raster_tone_5.admission"
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

    const url = routeZiggy('profilePrintProcess.create', {}, undefined, Ziggy);

    router.post(url, profile.value, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция добавлена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка добавления секции', life: 3000 });
        },
    });
}

function updateSection(section) {

    const url = routeZiggy('profilePrintProcess.update', {}, undefined, Ziggy);

    router.post(url, section, {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Секция обновлена', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка обновления секции', life: 3000 });
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

function deleteSection(section) {

    const url = routeZiggy('profilePrintProcess.destroy', {}, undefined, Ziggy);

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
  