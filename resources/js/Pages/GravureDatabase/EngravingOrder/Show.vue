<template>
    <div class="col-span-full grid gap-2">
        <div class="flex justify-end">
            <Button icon="pi pi-search" @click="searchDrawer = true" severity="contrast" size="small"/>
        </div>
        <Drawer v-model:visible="searchDrawer" header="Поиск" position="right" class="md:!w-80 lg:!w-[30rem]">
            <Tabs value="0">
                <TabList>
                    <Tab value="0">По партии</Tab>
                    <Tab value="1">По ГП</Tab>
                    <Tab value="2">По валу</Tab>
                </TabList>
                <TabPanels>
                    <TabPanel value="0">
                        <SearchOrder 
                        mode="batch" 
                        :search="search" 
                        :results="results" 
                        @update:search="handleSearch"
                        />
                    </TabPanel>
                    <TabPanel value="1">
                        <SearchOrder 
                            mode="product" 
                            :search="search" 
                            :results="results" 
                            @update:search="handleSearch"
                            />
                    </TabPanel>
                    <TabPanel value="2">
                        <SearchOrder 
                            mode="shaft" 
                            :search="search" 
                            :results="results" 
                            @update:search="handleSearch"
                            />
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </Drawer>
        <MacroOrderInfo v-if="macroOrder" :macroOrder="macroOrder" :engravingOrder="engravingOrder"/>
        <EngravingOrderInfo v-if="engravingOrder" 
            :macroOrder="macroOrder"
            :engravingOrder="engravingOrder" 
            :engravingOrders="engravingOrders"
        />
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { ref, onMounted,computed } from "vue";
import MacroOrderInfo from './components/MacroOrderInfo.vue';
import EngravingOrderInfo from './components/EngravingOrderInfo.vue';
import { Drawer, Button,Tabs,TabPanels,TabPanel,TabList,Tab } from "@danaflex/ui/components";
import { useGravureStore } from '@/stores/gravureStore';
import SearchOrder from './components/SearchOrder.vue';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const gravureStore = useGravureStore();

const searchDrawer = ref(false);

const props = defineProps({
    macroOrder: Object,
    engravingOrders: Array,
    engravingOrder: Object,
});

const page = usePage();
const search = computed(() => page.props.search || '');
const mode = computed(() => page.props.mode || 'batch');
const results = computed(() => page.props.results || []);


const handleSearch = (newSearch, newMode) => {
    router.get(
        routeZiggy('engravingOrders.search', {}, undefined, Ziggy),
        { search: newSearch, mode: newMode },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['results', 'search', 'mode'],
        }
    );
};


onMounted(() => {
    gravureStore.getDirectory();
});
</script>
