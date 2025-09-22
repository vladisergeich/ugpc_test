<script setup>
import { ref, watch,defineAsyncComponent, toRefs } from 'vue';
import { router } from '@inertiajs/vue3';
import { TabView, TabPanel } from "@danaflex/ui/components";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { debounce } from 'lodash';

const props = defineProps({
    activeTab: String,
    phases: Array,
    machines: Array,
    filters: Object,
})

const { items, filters, activeTab } = toRefs(props);

const tabs = ['statistic', 'tablePlan']
const tabIndex = ref(tabs.indexOf(activeTab.value) || 0)


function onTabChange(e) {
  const routeName = 'dailyPlan.'+tabs[e.index];

  if (routeName !== activeTab.value) {
    const newTab = routeZiggy(routeName, {}, undefined, Ziggy)
    router.visit(newTab)
  }
}

const DailyStatistic = defineAsyncComponent(() => import('./components/DailyStatistic.vue'))
const TablePlan = defineAsyncComponent(() => import('./components/TablePlan.vue'))

</script>

<template>
    <div class="grid col-span-full">
        <TabView v-model:activeIndex="tabIndex" @tabChange="onTabChange">
            <TabPanel header="Свод">
                <DailyStatistic :machines="machines" :filters="filters"/>
            </TabPanel>
            <TabPanel header="Табличный план">
                <TablePlan :phases="phases" />
            </TabPanel>
        </TabView>
    </div>
</template>