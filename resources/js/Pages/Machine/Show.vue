<template>
    <div class="col-span-full">
        <div class="grid grid-cols-16 gap-5">
            <div class="col-span-11 gap-4">
                <div class="grid bg-white p-5 gap-4 rounded-xl">
                    <MachineInfo :machine="machine" :workShift="workShift"/>
                    <StageInfo :machine="machine"/>
                </div>
                <MachineOperations  :machine="machine"/>
            </div>
            <div class="col-span-5 gap-4">
                <MachineWarehouse :machine="machine"/>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { router } from '@inertiajs/vue3';
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button,Card } from "@danaflex/ui/components";
import MachineInfo from './MachineInfo.vue';
import StageInfo from './StageInfo.vue';
import MachineWarehouse from './MachineWarehouse.vue';
import MachineOperations from './MachineOperations.vue';

const props = defineProps({
    machine: Object,
    workShift: Object,
});


</script>

<style scoped>

</style>
  