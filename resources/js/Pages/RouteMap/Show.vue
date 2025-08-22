<template>
    <div class="col-span-full grid gap-2">
        <Card class="shadow-md rounded-lg border border-gray-200 p-4">
            <template #title>
                Вал: {{ engravingOrderShaft.shaft.code }}
            </template>
            <template #content>
                <div class="grid gap-4">
                  <div class="grid grid-cols-4 gap-4">
                      <div v-for="(item, index) in details" :key="index" class="flex flex-col">
                          <span class="text-gray-500 text-sm">{{ item.label }}</span>
                          <div class="flex items-center">
                              <span v-show="!item.edit" class="text-gray-900">{{ item.value }}</span>
                              <InputText 
                              v-show="item.edit" 
                              v-model="item.value" 
                              class="p-inputtext-sm w-1/2"
                              />
                          </div>
                      </div>
                  </div>
                  <div class="gap-4">
                      <Button icon="pi pi-print" label="Бирка" severity="primary" size="small" @click="openLabel()" />
                      <Button v-if="!engravingOrderShaft.parameters['confirmed']" icon="pi pi-print" label="Подтвердить" severity="primary" size="small" @click="confirmShaft()" />
                      <Dialog v-model:visible="showModal" header="Печать бирки" :modal="true" class="w-96">
                          <div class="p-4 border rounded-md bg-gray-50" ref="modalLabelContent" id="modalLabelContent">
                              <p class="font-bold text-m">Вал: {{ engravingOrderShaft.shaft.code }}</p>
                              <p>№: {{ engravingOrderShaft.sequence_number }}</p>
                              <p>№ сепарации: {{ engravingOrderShaft.separation }}</p>
                              <p>Формат: {{ engravingOrderShaft.engraving_order.format }}</p>
                              <p>№ Партии: {{ engravingOrderShaft.engraving_order?.order?.order_number }}</p>
                              <p class="font-bold text-m">Оквид: {{ engravingOrderShaft.engraving_order.okvid_number }}</p>
                              <p>Линиатура: {{ engravingOrderShaft.lineature }}</p>
                              <p>Угол: {{ engravingOrderShaft.corner }}</p>
                              <p>Резец: {{ engravingOrderShaft.cutter }}</p>
                              <p class="text-m font-bold">Цвет: {{ engravingOrderShaft.color }}</p>
                              <div v-html="qr_data" class="mt-4 qr-code"></div>
                          </div>
                          <Button label="Распечатать" icon="pi pi-print" class="mt-3 w-full" @click="printLabel" />
                      </Dialog>
                  </div>
                </div>
            </template>
        </Card>
        <Accordion v-for="phase in engravingOrderShaft.phases" :key="phase.id" value="0">
            <AccordionPanel >
                <AccordionHeader><div class="flex gap-4"><span class="text-color">Этап - {{ phase.sequence }}</span> <span class="text-color">{{ phase.status_text }}</span></div></AccordionHeader>
                <AccordionContent>
                    <div class="flex gap-4 mb-1.5" v-if="phase.status == 'rejected'">
                        <Button size="small" severity="contrast" variant="outlined" label="Пропустить" @click="skipDefect(phase)"/>
                        <Button size="small" severity="contrast" variant="outlined" label="Заменить вал" @click="replaceShaft()"/>
                        <Button size="small" severity="contrast" variant="outlined" label="Переделка" @click="showAlterationInfo(phase)" />
                    </div>
                    <Dialog 
                        header="Переделка" 
                        v-model:visible="showAlteration" 
                        :modal="true"
                        :style="{ width: '50vw' }"
                        >
                        <div class="checkbox-wrapper">
                            <div v-for="stage in alterationStages" :key="stage.id" class="field-checkbox">
                            <Checkbox 
                                v-model="stage.selected" 
                                :binary="true" 
                                :inputId="'stage_' + stage.id"
                            />
                            <label :for="'stage_' + stage.id" class="ml-2">{{ stage.description }}</label>
                            </div>
                        </div>

                        <template #footer>
                            <Button 
                            label="Отправить на переделку" 
                            icon="pi pi-check" 
                            class="p-button-primary" 
                            @click="alterationStage" 
                            />
                        </template>
                    </Dialog>
                    <DataTable
                        :value="phase.stages"
                    >
                        <Column field="stage.name" header="Операция"></Column>
                        <Column  header="План">
                            <template #body="slotProps">
                                <div class="flex gap-4" v-for="parameter in slotProps.data?.parameters" :key="parameter.id">
                                    <span>{{ parameter.parameter.name }}:</span>
                                    <span>{{ parameter.float_value ||  parameter.string_value}}</span>
                                </div>
                            </template>
                        </Column>
                        <Column  header="Факт">
                            <template #body="slotProps">
                                <div class="flex gap-4" v-for="parameter in slotProps.data.operation?.parameters" :key="parameter.id">
                                    <span>{{ parameter.parameter.name }}:</span>
                                    <span>{{ parameter.float_value ||  parameter.string_value}}</span>
                                </div>
                            </template>
                        </Column>
                        <Column field="operation.work_shift_code.letter" header="Смена"></Column>
                        <Column field="operation.work_shift_code.date" header="Дата"></Column>
                    </DataTable>
                </AccordionContent>
            </AccordionPanel>
        </Accordion>
    </div>
</template>
<style> 
.qr-code svg {
  width: 250px;
  height: 250px;
}
</style>
<script setup>
import { defineProps } from 'vue';
import { Accordion, AccordionPanel, AccordionContent, AccordionHeader, DataTable, Column, Card,Button,Dialog,Checkbox } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import { useToast } from "primevue/usetoast";
import printJS from 'print-js';
import QRCode from 'qrcode-generator';

const props = defineProps({
    engravingOrderShaft: Object,
});

const { engravingOrderShaft } = toRefs(props); 

const toast = useToast();
const showAlteration = ref(false);
const selectedPhase = ref(null);
const showModal = ref(false);
const qr_data = ref(null);
const modalLabelContent = ref(null);

const alterationStages = ref([
      {id: 1, description: 'Дехромирование', selected: false},
      {id: 3, description: 'Шлифовка базы', selected: false},
      {id: 2, description: 'Обезжиривание стали', selected: false},
      {id: 5, description: 'Предварительное меднение', selected: false},
      {id: 6, description: 'Меднение базы', selected: false},
      {id: 4, description: 'Обезжиривание базы', selected: false},
      {id: 12, description: 'Меднение тираж', selected: false},
      {id: 7, description: 'Шлифовка тираж', selected: false},
      {id: 9, description: 'Гравирование', selected: false},
      {id: 8, description: 'Обезжиривание тираж', selected: false},
      {id: 10, description: 'Хромирование', selected: false},
      {id: 11, description: 'Полировка хром', selected: false},
      {id: 13, description: 'Пробная печать', selected: false},
]);

const openLabel = () => {
  qr_data.value = generateQRCode(`shaft|${engravingOrderShaft.value.id}`);
  showModal.value = true;
};

const generateQRCode = (text) => {
  const qr = QRCode(0, 'M');
  qr.addData(text);
  qr.make();
  return qr.createSvgTag();
};

const printLabel = () => {
  printJS({ printable: 'modalLabelContent', type: 'html' });
};

const showAlterationInfo = (phase) => {
    selectedPhase.value = phase;
    showAlteration.value = true;
};

const confirmShaft = async () => {
    engravingOrderShaft.value.parameters['confirmed'] = true; 
    try {
        const url = routeZiggy('engravingOrderShafts.update', {}, undefined, Ziggy);

        await router.post(url, engravingOrderShaft.value, {
        preserveScroll: true,
        preserveState: true,
        });

        toast.add({ severity: 'success', summary: 'Вал подтвержден', life: 3000 });

    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при подтверждении вала', life: 3000 });
    }
};

const alterationStage = async () => {
  try {
    const url = routeZiggy('routeMap.alterationStages', {}, undefined, Ziggy);

    await router.post(url, { phase: selectedPhase.value, alterationStages: alterationStages.value }, {
      preserveScroll: true,
      preserveState: true,
    });

    showAlteration.value = false;
    toast.add({ severity: 'success', summary: 'Новый этап создан', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при создании нового этапа', life: 3000 });
  }
};

const skipDefect = async (phase) => {
  try {
    const url = routeZiggy('routeMap.skipDefect', {}, undefined, Ziggy);

    await router.post(url, { phase: phase}, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Этап открыт', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при открытии этапа', life: 3000 });
  }
};

const replaceShaft = async () => {
  try {
    const url = routeZiggy('routeMap.replaceShaft', {}, undefined, Ziggy);

    await router.post(url, { engravingOrderShaft: engravingOrderShaft.value}, {
      preserveScroll: true,
      preserveState: true,
    });

    toast.add({ severity: 'success', summary: 'Запрос на замену отправлен', life: 3000 });

  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка отправке запроса', life: 3000 });
  }
};

const details = computed(() => [
    { label: "Оквид", value: engravingOrderShaft.value.engraving_order.okvid_number, edit: false },
    { label: "Формат", value: engravingOrderShaft.value.engraving_order.format, edit: false },
    { label: "Сепарация", value: engravingOrderShaft.value.separation, edit: false },
    { label: "D, конечный", value: engravingOrderShaft.value.final_diameter, edit: false },
    { label: "Комментарий", value: "Пример комментария", edit: false }
]);
</script>
