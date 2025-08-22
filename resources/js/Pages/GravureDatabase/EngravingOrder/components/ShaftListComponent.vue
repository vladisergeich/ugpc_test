<template>
    <div class="grid">
      <Tabs value="0">
        <TabList>
          <div class="flex justify-between items-center">
            <div class="flex gap-4 text-sm">
              <Tab value="0">Валы заказа</Tab>
              <Tab value="1">Активные валы в ОКВиДе</Tab>
            </div>
            <div class="flex items-center gap-2.5">
              <Button icon="pi pi-copy" size="small" outlined rounded severity="secondary" />
              <Button icon="pi pi-plus" size="small" outlined @click="addSectionDialog = true" rounded severity="secondary" />
            </div>
          </div>
        </TabList>
        <TabPanels>
          <TabPanel value="0">
            <DataTable size="small" :value="engravingOrder.engraving_order_shaft" class="min-w-[50rem] text-sm" :rowClass="getRowClass">
              <Column field="sequence_number" header="№" style="width: 2.5%"></Column>
              <Column field="parameters.reengraving" header="Гр." style="width: 2.5%">
                <template #body="{ data }">
                  <Checkbox 
                    size="small" 
                    binary class="mr-2" 
                    v-model="data.parameters.reengraving"
                    @change="updateEngravingOrderShaft(data)"
                  />
                </template>
              </Column>
              <Column field="shaft.code" header="ID вала" style="width: 10%"></Column>
              <Column field="shaft.ff" header="ff" style="width: 5%"></Column>
              <Column field="color" header="Цвет" style="width: 15%">
                <template #body="{ data }">
                  <InputText
                    size="small"
                    class="w-full"
                    v-if="data.status_id != 1"
                    v-model="data.color"
                    @change="updateEngravingOrderShaft(data)"
                  />
                  <span v-else>{{ data.color }}</span>
                </template>
              </Column>
              <Column field="parameters.shortening_scale_length" header="Ук." style="width: 5%">
                <template #body="{ data }">
                  <Checkbox 
                    size="small" 
                    binary class="mr-2" 
                    v-model="data.parameters.shortening_scale_length"
                    @change="updateEngravingOrderShaft(data)"
                  />
                </template>
              </Column>
              <Column field="lineature" header="Линиатура" style="width: 10%">
                <template #body="{ data }">
                  <InputText
                    size="small"
                    class="w-full"
                    v-if="data.status_id != 1"
                    v-model="data.lineature"
                    @change="updateEngravingOrderShaft(data)"
                  />
                  <span v-else>{{ data.lineature }}</span>
                </template>
              </Column>
              <Column field="corner" header="Угол" style="width: 10%">
                <template #body="{ data }">
                  <InputText
                    size="small"
                    class="w-full"
                    v-if="data.status_id != 1"
                    v-model="data.corner"
                    @change="updateEngravingOrderShaft(data)"
                  />
                  <span v-else>{{ data.corner }}</span>
                </template>
              </Column>
              <Column field="cutter" header="Резец" style="width: 10%">
                <template #body="{ data }">
                  <InputText
                    size="small"
                    class="w-full"
                    v-if="data.status_id != 1"
                    v-model="data.cutter"
                    @change="updateEngravingOrderShaft(data)"
                  />
                  <span v-else>{{ data.cutter }}</span>
                </template>
              </Column>
              <Column field="note" header="Примеч." style="width: 30%">
                <template #body="{ data }">
                  <InputText
                    size="small"
                    class="w-full"
                    v-if="data.status_id != 1"
                    v-model="data.note"
                    @change="updateEngravingOrderShaft(data)"
                  />
                  <span v-else>{{ data.note }}</span>
                </template>
              </Column>
              <Column class="w-1/6">
                <template #body="slotProps">
                    <Button type="button" @click="showEngravingShaftAction($event, slotProps.data)" icon="pi pi-ellipsis-v" severity="secondary" size="small"></Button>
                </template>
              </Column>
            </DataTable>
            <Popover ref="engravingShaftActions">
                <div class="grid">
                  <Button label="Подобрать вал" variant="text" @click="showFreeShafts()" v-if="selectedEngravingShaft.shaft?.state != 'engraving'"/>
                  <Button label="Удалить" variant="text" @click="deleteSection()" v-if="selectedEngravingShaft.shaft?.state != 'engraving'"/>
                  <Button label="Заменить вал" variant="text" @click="showFreeShafts()" v-if="selectedEngravingShaft.shaft?.status == 'rejected'"/>
                </div>
            </Popover>
          </TabPanel>
          <TabPanel value="1">
            <DataTable :value="macroOrder.shafts_in_work" size="small" class="min-w-[50rem] text-sm">
              <Column field="sequence_number" header="№" style="width: 5%"></Column>
              <Column field="engraving_order.okvid_number" header="ОКВиД" style="width: 10%"></Column>
              <Column field="shaft.code" header="ID вала" style="width: 10%"></Column>
              <Column field="color" header="Цвет" style="width: 10%"></Column>
              <Column field="shaft.warehouse.name" header="Склад" style="width: 15%"></Column>
              <Column field="shaft.warehouse_place" header="Ячейка" style="width: 10%"></Column>
              <Column field="status_id" header="Ресурс" style="width: 5%"></Column>
              <Column field="shaft.state" header="Состояние" style="width: 10%">
                <template #body="{ data }">
                  <Tag :value="data.shaft.state_text" :severity="getSeverity(data.shaft.state)" />
                </template>
              </Column>
              <Column header="Метраж" style="width: 10%"></Column>
              <Column class="w-1/6">
                <template #body="slotProps">
                    <Button type="button" @click="showActiveShaftAction($event, slotProps.data)" icon="pi pi-ellipsis-v" severity="secondary" size="small"></Button>
                </template>
              </Column>
            </DataTable>
            <ConfirmDialog></ConfirmDialog>
            <Popover ref="activeShaftActions">
                <div class="grid">
                  <Button label="Вернуть в предыдущий" variant="text" @click="returnShaftConfirm()" v-if="selectedEngravingShaft.shaft.state != 'engraving'"/>
                </div>
            </Popover>
          </TabPanel>
        </TabPanels>
      </Tabs>
      <Dialog v-model:visible="addSectionDialog" class="w-[400px]" header="Добавить секции" :modal="true">
        <label>Укажите количество секций</label>
        <InputNumber v-model="qtySection" inputId="minmax-buttons" mode="decimal" showButtons :min="0" :max="10" class="w-full" />
        <template #footer>
          <Button label="Отмена" icon="pi pi-times" text @click="addSectionDialog = false" />
          <Button label="Добавить" icon="pi pi-check" @click="addSections" />
        </template>
      </Dialog>
      <Dialog v-model:visible="freeShaftShow" class="w-6/12" header="Подобрать вал" :modal="true">
        <DataTable v-if="freeShafts" 
          :paginator="true"
          :rows="10"
          stripedRows
          :value="freeShafts" size="small" class="min-w-[50rem] text-sm ">
          <Column field="code" header="ID вала" style="width: 15%"></Column>
          <Column field="format" header="Формат" style="width: 10%"></Column>
          <Column field="ff" header="FF" style="width: 5%"></Column>
          <Column field="state_id" header="Состояние" style="width: 10%"></Column>
          <Column field="status_id" header="Статус" style="width: 5%"></Column>
          <Column field="warehouse_id" header="Склад" style="width: 10%"></Column>
          <Column field="note" header="Примечание" style="width: 40%"></Column>
          <Column class="w-1/6" style="width: 5%">
            <template #body="slotProps">
                <Button type="button"  size="small" @click="addShaft(slotProps.data)">Выбрать</Button>
            </template>
          </Column>
        </DataTable>
      </Dialog>
    </div>
  </template>
  
<script setup>
import { InputNumber, Button, Column, DataTable, Tabs, TabList, Tab, TabPanel, Dialog, InputText, Checkbox, Popover,Tag } from "@danaflex/ui/components";
import { ref, toRefs } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";


const props = defineProps({
  engravingOrder: Object, 
  macroOrder: Object,
});

const { engravingOrder } = toRefs(props); 

const toast = useToast();
const confirm = useConfirm();

const engravingShaftActions = ref(false);
const activeShaftActions = ref(false);
const freeShaftShow = ref();
const selectedEngravingShaft = ref();
const addSectionDialog = ref(false);
const qtySection = ref(null);
const freeShafts = ref([]);

const showFreeShafts = () => {
  getFreeShafts();
  
  freeShaftShow.value = true;   
}

const getSeverity = (status) => {
  const severityMap = {
    repair: 'danger',
    free: 'success',
    engraving: 'info',
    refuse: 'warn',
    renewal: null,
  };
  return severityMap[status];
};

const getRowClass = (data) => {
  return data?.status === 'written_off' ? 'opacity-35' : '';
};

const showEngravingShaftAction = (event, shaft) => {
    selectedEngravingShaft.value = shaft;   
    engravingShaftActions.value.toggle(event);
}

const showActiveShaftAction = (event, shaft) => {
    selectedEngravingShaft.value = shaft;   
    activeShaftActions.value.toggle(event);
}

const addShaft = (shaft) => {
  selectedEngravingShaft.value.shaft_id = shaft.id;

  freeShaftShow.value = false;
  updateEngravingOrderShaft(selectedEngravingShaft.value);
}

const returnShaftConfirm = () => {
    confirm.require({
        message: 'Вернуть вал в предыдущий оквид?',
        header: 'возврат вала',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Отмена',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Вернуть'
        },
        accept: () => {
            returnShaft();
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Вал возвращен в предыдущий оквид', life: 3000 });
        },
        reject: () => {
            
        }
    });
};

const returnShaft = async () => {
  try {
    const url = routeZiggy('engravingOrderShafts.returnShaft', {}, undefined, Ziggy);

    await router.post(url, selectedEngravingShaft.value, {
      preserveScroll: true,
      preserveState: true,
    });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при возврате вала', life: 3000 });
  }
};

const addSections = async () => {
  try {
    const url = routeZiggy('engravingOrderShafts.create', {}, undefined, Ziggy);

    await router.post(url, { qty: qtySection.value, engravingOrder: engravingOrder.value }, {
      preserveScroll: true,
      preserveState: true,
    });

    addSectionDialog.value = false;
    toast.add({ severity: 'success', summary: 'Секции добавлены', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при добавлении секций', life: 3000 });
  }
};

const deleteSection = async () => {
  try {
    const url = routeZiggy('engravingOrderShafts.destroy', {}, undefined, Ziggy);

    await router.post(url, selectedEngravingShaft.value, {
      preserveScroll: true,
      preserveState: true,
    });

    engravingShaftActions.value.toggle(event);
    toast.add({ severity: 'success', summary: 'Секция удалена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при удалении секции', life: 3000 });
  }
};

const updateEngravingOrderShaft = async (section) => {
  try {
    const url = routeZiggy('engravingOrderShafts.update', {}, undefined, Ziggy);

    await router.post(url, section, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Секция обновлена', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при обновлении секции', life: 3000 });
  }
};

const getFreeShafts = async () => {
  try {
    const url = routeZiggy(
        'engravingOrderShafts.getFreeShafts', 
        {},
        undefined,
        Ziggy
    ); 
    const response = await axios.get(url);

    freeShafts.value = response.data;
    engravingShaftActions.value.toggle(event);
  }
  catch (error) {
      console.error('Ошибка в загрзку свободных валов', error);
  }
};


</script>
  