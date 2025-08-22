<template>
    <div class="grid grid-cols-16 gap-5">
        <div class="col-span-7 bg-white p-4 gap-4 rounded-lg">
            <TopBarComponent 
                :engravingOrder="engravingOrder" 
                :engravingOrders="engravingOrders"
                @update:engravingOrder="handleChange"
            />
            <OrderNumberComponent 
            :engravingOrder="engravingOrder" 
            @update:engravingOrder="handleChange"/>
            <div class="grid grid-cols-7 gap-4">
                <div class="col-span-4 gap-4">
                    <ParametersComponent 
                    :engravingOrder="engravingOrder" 
                    @update:engravingOrder="handleChange"/>
                    <div class="grid grid-cols-4 gap-4">
                    <TypeWorkComponent class="grid col-span-2" 
                    :engravingOrder="engravingOrder" 
                    @update:engravingOrder="handleChange"/>
                    <AdditionalOptionsComponent class="grid col-span-2" 
                    :engravingOrder="engravingOrder" 
                    @update:engravingOrder="handleChange"/>
                    </div>
                    <TechElementsComponent 
                    :engravingOrder="engravingOrder" 
                    @update:engravingOrder="handleChange"/>
                    <Textarea class="mt-4 w-full" @change="handleChange" v-model="engravingOrder.comments.internal_comment" size="small" placeholder="Комментарий внутренний" rows="3" />
                </div>
                <div class="col-span-3">
                    <LayoutConstructorComponent 
                        :engravingOrder="engravingOrder" 
                    />
                    <PrintingСonditionsComponent 
                        :engravingOrder="engravingOrder" 
                        @update:engravingOrder="handleChange"
                    />
                </div>
            </div>
        </div>
        <div class="col-span-9 bg-white p-4 rounded-lg">
            <ItemListComponent 
            :engravingOrder="engravingOrder"
            />
            <ShaftListComponent 
            style="margin-top: 20px;" 
            :macroOrder="macroOrder"
            :engravingOrder="engravingOrder" />
            <UploadParamComponent
            style="margin-top: 20px;" 
            :engravingOrder="engravingOrder" 
            @update:engravingOrder="handleChange" />
        </div>
    </div>
  </template>
  
<script setup>
import TopBarComponent from './TopBarComponent.vue';
import OrderNumberComponent from './OrderNumberComponent.vue';
import ParametersComponent from './ParametersComponent.vue';
import PrintingСonditionsComponent from './PrintingСonditionsComponent.vue';
import TypeWorkComponent from './TypeWorkComponent.vue';
import AdditionalOptionsComponent from './AdditionalOptionsComponent.vue';
import ItemListComponent from './ItemListComponent.vue';
import ShaftListComponent from './ShaftListComponent.vue';
import TechElementsComponent from './TechElementsComponent.vue';
import LayoutConstructorComponent from './LayoutConstructorComponent.vue';

import UploadParamComponent from './UploadParamComponent.vue';
import { Textarea } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import axios from 'axios'
import { toRefs } from 'vue';


const props = defineProps({
    engravingOrders: Array,
    engravingOrder: Object,
    macroOrder: Object,
});

const { engravingOrder } = toRefs(props); 

const handleChange = () => {
    router.post('/engraving-orders/update', engravingOrder.value, {
        preserveScroll: true,  // Оставить прокрутку на месте
        preserveState: true,   // Не перезагружать всю страницу
    });
};

</script>