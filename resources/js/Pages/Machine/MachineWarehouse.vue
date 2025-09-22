<template>
    <div class="bg-white p-5 rounded-xl shadow-md">
        <h2 class="text-m font-semibold text-gray-700 mb-4">Валы в очереди</h2>

        <DataTable 
            :value="machine.shafts_in_warehouse" 
            class="w-full"
            size="small"
            responsiveLayout="scroll"
        >
            <Column header="№ Вала">
                <template #body="slotProps">
                    <Button 
                        text 
                        class="text-blue-500 hover:underline" 
                        @click="consumpShaft(slotProps.data.phase.engraving_order_shaft)"
                    >
                        {{ slotProps.data.phase.engraving_order_shaft.shaft?.code }}
                    </Button>
                </template>
            </Column>

            <Column field="phase.engraving_order_shaft.engraving_order.okvid_number" header="№ Оквид"></Column>
            <Column field="phase.engraving_order_shaft.shaft.type" header="Вид вала"></Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { DataTable, Column, Button } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";


const props = defineProps({
    machine: Object,
});

const { machine } = toRefs(props); 

const toast = useToast();

const consumpShaft = async (engravingOrderShaft) => {
  try {
    const url = routeZiggy('machine.create', {}, undefined, Ziggy);

    router.post(url, { engravingOrderShaftId: engravingOrderShaft.id, machineId: machine.value.id }, {
      preserveScroll: true,
      preserveState: true,

      onSuccess: () => { 
        toast.add({ severity: 'success', summary: 'Вал потрбелен', life: 3000 });
      },

      onError: (error) => {
        if (error?.error) {
          toast.add({ severity: 'error', summary: error?.error, life: 3000 });
        } else {
          toast.add({ severity: 'error', summary: "Ошибка при потреблении вала", life: 3000 });
        }
      }
    });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Неизвестная ошибка', life: 3000 });
  }
};

</script>
