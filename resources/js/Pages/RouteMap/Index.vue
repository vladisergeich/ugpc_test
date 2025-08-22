<template>
    <div class="col-span-full">
        <TabView class="w-full" :activeIndex="tabs.findIndex(tab => tab.key === activeTab)" @tabChange="changeTab">
            <TabPanel v-for="tab in tabs" :key="tab.key" :header="tab.label">
                <keep-alive>
                    <OrdersTable :engravingOrders="engravingOrders" :totalRecords="totalRecords" :filters="filters"/>
                </keep-alive>
            </TabPanel>
        </TabView>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { Tabs, TabList, Tab, TabPanels, TabPanel, TabView } from "@danaflex/ui/components";
import OrdersTable from "./OrdersTable.vue";

defineProps({
    engravingOrders: Array,
    totalRecords: Number,
    filters: Object,
});

const tabs = [
    { label: 'В работе', key: 'in_progress', status: 1 },
    { label: 'Завершены', key: 'completed', status: 2 },
];

const activeTab = ref('in_progress');

const changeTab = (event) => {
    activeTab.value = tabs[event.index];
};
</script>
