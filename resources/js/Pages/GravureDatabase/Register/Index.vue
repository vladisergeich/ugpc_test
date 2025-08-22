<script setup>
import { ref, watch,defineAsyncComponent, toRefs } from 'vue';
import { router } from '@inertiajs/vue3';
import { TabView, TabPanel } from "@danaflex/ui/components";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { debounce } from 'lodash';

const props = defineProps({
  activeTab: String,
  items: Object,
  filters: Object,
  states: Array,
  statuses: Array,
  warehouses: Array,
  formats: Array,
})

const { items, filters, activeTab } = toRefs(props);

const tabs = ['shafts', 'orderShafts', 'items', 'formats']
const tabIndex = ref(tabs.indexOf(activeTab.value) || 0)


function onTabChange(e) {
  const routeName = 'register.'+tabs[e.index];

  if (routeName !== activeTab.value) {
    const newTab = routeZiggy(routeName, {}, undefined, Ziggy)
    router.visit(newTab)
  }
}

const RegisterShafts = defineAsyncComponent(() => import('./components/RegisterShafts.vue'))
const RegisterOrderShafts = defineAsyncComponent(() => import('./components/RegisterOrderShafts.vue'))
const RegisterItems = defineAsyncComponent(() => import('./components/RegisterItems.vue'))
const RegisterFormats = defineAsyncComponent(() => import('./components/RegisterFormats.vue'))

</script>

<template>
    <div class="grid col-span-full">
        <TabView v-model:activeIndex="tabIndex" @tabChange="onTabChange">
            <TabPanel header="Валы">
                <RegisterShafts :items="items" :filters="filters" :states="states" :statuses="statuses" :warehouses="warehouses" :formats="formats"/>
            </TabPanel>
            <TabPanel header="Валы по заказам">
                <RegisterOrderShafts :items="items" :filters="filters" />
            </TabPanel>
            <TabPanel header="Реестр ГП">
                <RegisterItems :items="items" :filters="filters" />
            </TabPanel>
            <TabPanel header="Формат валов">
                <RegisterFormats :items="items" :filters="filters" />
            </TabPanel>
        </TabView>
    </div>
</template>