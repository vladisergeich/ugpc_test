<template>
    <div class="grid gap-4">
        <template v-if="!machine.consump_shaft && !machine.current_operation">
            <span class="flex items-center justify-center mt-5 min-h-52 text-xl text-gray-400">Выберите вал из списка или доп.операцию</span>
        </template>
        <template v-if="machine.consump_shaft || machine.current_operation">
            <ShaftInfo :machine="machine" v-if="machine.consump_shaft"/>
            <PlanParam :machine="machine" v-if="machine.consump_shaft"/>
            <CurrentOperation :machine="machine"/>
        </template>
    </div>
</template>
  
<script setup>
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button, Dropdown } from "@danaflex/ui/components";
import { usePage } from '@inertiajs/vue3';
import PlanParam from './PlanParam.vue';
import ShaftInfo from './ShaftInfo.vue';
import CurrentOperation from './CurrentOperation.vue';

const props = defineProps({
    machine: Object,
});

const { machine } = toRefs(props);

</script>

<style scoped>

</style>
  