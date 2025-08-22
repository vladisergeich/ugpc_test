<template>
    <div class="space-y-4">
      <!-- Диаметр Входного контроля -->
        <div class="grid grid-cols-16 gap-4">
            <!--
            <div class="grid col-span-4 gap-4 rounded-xl p-5 bg-cyan-100" v-if="machine.code != 'VK'">
                <span class="font-normal">Параметры Вх. контроля:</span>
                <div class="flex gap-4">
                    <div v-for="parameter in machine.consump_shaft.engraving_order_shaft.active_stage.parameters" :key="parameter.id">
                        <span class="font-normal text-gray-400 mr-1.5">{{ parameter.parameter.name }}:</span>
                        <span>{{ parameter.float_value !== null ? parameter.float_value : parameter.string_value }}</span>
                    </div>
                </div>
                <span class="font-semibold">Диаметр Вх. контроля:</span>
                <span>{{ machine.consump_shaft.engraving_order_shaft }}</span>
                <span class="font-semibold">Толщина меди Вх. контроль:</span>
                <span>{{ machine.consump_shaft.engraving_order_shaft }}</span>
            </div>
            -->
    
            <!-- Плановые показатели -->
            <div class="grid col-span-12 gap-4 border-solid border-2 rounded-xl p-2">
                <div class="grid">
                    <span class="font-normal">Плановые показатели:</span>
                    <div class="flex gap-4">
                        <div v-for="parameter in machine.consump_shaft.engraving_order_shaft.active_stage.parameters" :key="parameter.id">
                            <span class="font-normal text-gray-400 mr-1.5">{{ parameter.parameter.name }}:</span>
                            <span>{{ parameter.float_value !== null ? parameter.float_value : parameter.string_value }}</span>
                        </div>
                    </div>
                </div>
        
                <!-- Время гравировки -->
                <div v-if="machine.code == 'K5'">
                    <span class="font-semibold">Время гравировки:</span>
                    <span class="text-gray-600"></span>
                </div>
        
                <!-- Показатели Мед. тираж -->
                <div class="mt-4" v-if="machine.code == 'CFM'">
                    <span class="font-semibold">Показатели Мед. тираж:</span>
                    <div class="space-y-1">
                    <span class="font-medium">:</span>
                    <span></span>
                    </div>
                    <span class="text-gray-600">Шлифовка без резца</span>
                </div>
        
                <div  class="mt-4" v-if="machine.code == 'CFM'">
                    <span class="font-semibold">Показатели Шлифовка базы:</span>
                        <div class="space-y-1">
                        <span class="font-medium">:</span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
  
        <!-- Примечания -->
        <div class="flex justify-start gap-4">
            <div class="flex flex-col justify-items-start gap-2 border-solid border-2 rounded-xl p-2">
                <span class="font-semibold">Примечание:</span>
                <span v-if="machine.consump_shaft.engraving_order_shaft.engraving_order?.order?.order_number.charAt(0) == 'N'" class="font-semibold">Необходима Шлифовка с сеткой</span>
            </div>
    
            <!-- Для гравировщика -->
            <div class="flex flex-col justify-items-start gap-2 border-solid border-2 rounded-xl p-2">
                <span class="font-semibold">Для гравировщика:</span>
                <span class="text-gray-600">{{machine.consump_shaft.engraving_order_shaft.engraving_order.comments.engraver_comment}}</span>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button, Dropdown } from "@danaflex/ui/components";
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    machine: Object,
});

const { machine } = toRefs(props);

</script>

<style scoped>

</style>
  