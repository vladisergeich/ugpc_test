<template>
    <DataTable size="small" :value="engravingOrder?.order?.items" class="min-w-[50rem] text-sm">
      <Column field="item.code" header="Код ГП" :headerStyle="{ background: '#FEFCE8'}">
        <template #body="slotProps">
          <span>{{ slotProps.data.item.subspecies > 0 ? slotProps.data.item.code+'-'+slotProps.data.item.subspecies :  slotProps.data.item.code}}</span>
        </template>
      </Column>
      <Column field="item.customer" header="Заказчик" :headerStyle="{ background: '#FEFCE8'}"></Column>
      <Column field="item.category" header="Категория" :headerStyle="{ background: '#FEFCE8'}"></Column>
      <Column field="item.brand" header="Бренд" :headerStyle="{ background: '#FEFCE8'}"></Column>
      <Column field="item.sap_code" header="SAP-код" :headerStyle="{ background: '#FEFCE8'}"></Column>
    </DataTable>
</template>

<script setup>
import { ref, toRefs } from 'vue';
import { Column, DataTable,Accordion,AccordionHeader,AccordionContent} from "@danaflex/ui/components";


const props = defineProps({
  engravingOrder: Object, 
});


const { engravingOrder } = toRefs(props); 

</script>

  