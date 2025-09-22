<template>
  <div class="col-span-full grid grid-cols-3 gap-4">
    <Card class="grid" v-for="machine in machines" :key="machine.id">
      <template #title><Button @click="showMachine(machine)" link>{{ machine.name }}</Button></template>
      <template #content>

      </template>
    </Card>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, toRefs, computed } from 'vue';
import { Dialog, Button,Card } from "@danaflex/ui/components";

const props = defineProps({
  machines: Array,
});

const showMachine = async (machine) => {
  router.visit(`machine/${machine.id}`);
};
</script>

<style scoped>

</style>

  