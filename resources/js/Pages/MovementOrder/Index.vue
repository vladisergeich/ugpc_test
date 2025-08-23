<template>
    <div class="col-span-full">
        <h1 class="text-2xl font-bold mb-4">Движение заказов</h1>

        <DataTable
            ref="dt"
            :value="movementOrders"
            rowGroupMode="subheader"
            groupRowsBy="completion_date"
            @rowReorder="onRowReorder"
            @cell-edit-complete="onCellEditComplete"
            editMode="cell"
            showGridlines
            showRowGroupHeader="true"
            size="small"
            :loading="loading"
            dataKey="id"
            :scrollable="true"
            scrollHeight="50rem"
            class="w-full text-sm"
            >
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        <Select
                        size="small"
                        placeholder="Гравировщик"
                        v-model="selectedEngraver"
                        :options="engravers"
                        optionLabel="name"
                        optionValue="id"
                        @change="fetchOrders"
                        />
                        <Calendar 
                        v-model="dates" 
                        selectionMode="range" 
                        :manualInput="false" 
                        :showTime="false"
                        dateFormat="dd.mm.yy" 
                        size="small" 
                        placeholder="Дата готовности"
                        @date-select="fetchOrders"
                        />
                    </div> 
                    <div class="flex gap-2">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="search"  placeholder="Найти" size="small" @input="onSearchInput"/>
                        </IconField>
                        <Button 
                        size="small" 
                        icon="pi pi-plus" 
                        @click="getOrders()"
                        />
                        <Button 
                        size="small" 
                        icon="pi pi-file-excel " 
                        severity="success"
                        @click="$refs.dt.exportCSV()" 
                        />
                        <Button type="button" @click="showActions($event)" icon="pi pi-ellipsis-v" severity="secondary" size="small"></Button>
                    </div>
                    <Popover ref="actions">
                        <div class="grid">
                            <Button label="Добавить простой" variant="text" @click="showDownTime = true"/>
                            <Button label="Распределить по выработке" variant="text" @click="showRedistribute = true"/>
                        </div>
                    </Popover>
                    <Dialog v-model:visible="showDownTime" modal header="Добавление простоя" :style="{ width: '25rem' }">
                        <div class="grid gap-4">
                            <Calendar
                                v-model="downTime.completion_date"
                                :manualInput="false"
                                dateFormat="dd.mm.yy"
                                size="small"
                                placeholder="Дата простоя"
                            />
                            <InputText v-model="downTime.note" size="small" placeholder="Комментарий" />
                            <Button label="Добавить" variant="text" @click="addDownTime"/>
                        </div>
                    </Dialog>
                    <Dialog v-model:visible="showRedistribute" modal header="Распределение по выработке" :style="{ width: '50%' }">
                        <div class="grid gap-4">
                            <Calendar
                                v-model="redistributeDates"
                                selectionMode="range"
                                :showTime="false"
                                :manualInput="false" 
                                dateFormat="dd.mm.yy" 
                                size="small" 
                                placeholder="Период распределения"
                                />
                            <InputText v-model="redistributeQtyShaft" type="number"  placeholder="Кол-во валов" size="small"/>
                            <Button label="Распределить" variant="text" @click="redistributeOrders()"/>
                        </div>
                    </Dialog>
                    <Dialog v-model:visible="showOrders" modal header="Выгрузка заказов" :style="{ width: '50%' }">
                        <DataTable v-if="orders" 
                            :paginator="true"
                            :rows="10"
                            stripedRows
                            :value="orders" size="small" class="min-w-[50rem] text-sm ">
                            <Column field="related.okvid_number" header="Оквид" headerClass="bg-blue-100"></Column>
                            <Column field="related.order.order_number" header="Партия" headerClass="bg-blue-100"/>
                            <Column field="related.macro_order.customer.name" header="Клиент" headerClass="bg-blue-100"/>
                            <Column class="w-1/6" style="width: 5%" headerClass="bg-blue-100">
                                <template #body="slotProps">
                                    <Button type="button"  size="small" @click="addOrder(slotProps.data)">Выбрать</Button>
                                </template>
                            </Column>
                        </DataTable>
                    </Dialog>
                </div>
                <div class="flex justify-start items-center gap-4 mt-2.5">
                    <div class="flex gap-2">
                        <span class="text-color text-base">План валов:</span>
                        <span class="text-color text-base"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-color text-base">Запланировано валов:</span><span class="text-color text-base">{{ countShafts() }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-color text-base">Запланировано заказов:</span><span class="text-color text-base">{{ countOrders() }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-color text-base">Выпущено валов:</span><span class="text-color text-base">{{ countFactShafts() }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-color text-base">Выпущено заказов:</span><span class="text-color text-base">{{ countFactOrders() }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-color text-base">Выполнение плана:</span><span class="text-color text-base"></span>
                    </div>
                </div>
            </template>

            <Column rowReorder headerStyle="width: 3rem" headerClass="bg-blue-100"/>
            <Column field="priority_number" header="№" headerClass="bg-blue-100"/>
            <Column header="Дата готовности" headerClass="bg-blue-100">
                <template #body="{ data }">
                    {{ new Date(data.completion_date).toLocaleDateString('ru-RU') }}
                </template>
                <template #editor="{ data, field }">
                    <InputText type="date" v-model="data.completion_date" :showTime="false"  dateFormat="yy-mm-dd" :manualInput="false" size="small" autofocus /> 
                </template>
            </Column>
            <Column field="related.printing_date" header="Дата печати / ламинации" headerClass="bg-blue-100">
                <template #body="{ data }" >
                    {{ new Date(data.related?.printing_date).toLocaleDateString('ru-RU') ?? '' }}
                </template>
            </Column>
            <Column header="Статус" headerClass="bg-blue-100"/>
            <Column header="Валы на складе" headerClass="bg-blue-100">
                <template #body="{ data }">
                    <div class="flex justify-center">
                    <span
                        :class="[
                        'w-5 h-5 rounded-sm',
                        data.shafts_in_warehouse ? 'bg-green-500' : 'bg-red-500'
                        ]"
                    ></span>
                    </div>
                </template>
            </Column>
            <Column header="Заказ" headerClass="bg-blue-100" style="width: 8%">
                <template #body="slotProps">
                    <Button @click="showInfo(slotProps.data)" link>
                        {{ slotProps.data?.related?.okvid_number }}
                    </Button>
                </template>
            </Column>
            <Column field="related.order.order_number" header="Партия" headerClass="bg-blue-100" style="width: 5%"/>
            <Column field="related.macro_order.customer.name" header="Клиент" headerClass="bg-blue-100" style="width: 10%"/>
            <Column header="Описание" headerClass="bg-blue-100" style="width: 10%">
                <template #body="{ data }">
                    {{ (data.related?.order?.items[0]?.item?.category ?? '')+'  '+(data.related?.order?.items[0]?.item?.brand ?? '')}}
                </template>
            </Column>
            <Column field="related.format" header="Формат" headerClass="bg-blue-100"/>
            <Column field="quantity_shaft" header="Кол-во план" headerClass="bg-blue-100"/>
            <Column header="Кол-во факт" headerClass="bg-blue-100">
                <template #body="{ data }">
                    {{ data.related?.engraving_order_shaft.filter(shaft => {
                                return shaft.status == 'completed'; 
                        }).length}}
                </template>
            </Column>
            <Column field="related.transfer_shaft_engraving.transfer_id" header="Номер заявки на отправку валов" headerClass="bg-blue-100"/>
            <Column field="related.transfer_shaft_printing.transfer_id" header="Номер заявки на получение валов" headerClass="bg-blue-100"/>
            <Column field="note" header="Комментарий Опл" headerClass="bg-blue-100" style="width: 10%">
                <template #body="{ data }">
                    {{ data.note }}
                </template>
                <template #editor="{ data, field }">
                    <!-- Добавляем @input, чтобы отслеживать изменения данных и делать их реактивными -->
                    <InputText 
                        v-model="data.note" 
                        fluid 
                        size="small" 
                        autofocus  
                    /> 
                </template>
            </Column>
            <Column field="related.condition.name" header="Тех состояние заказа" headerClass="bg-blue-100" style="width: 10%"/>
            <Column header="Снят с заказа" headerClass="bg-blue-100"/>
            <template #groupheader="slotProps">
                <div class="flex gap-4 items-center font-bold">
                    <div>
                        <span>{{ new Date(slotProps.data.completion_date).toLocaleDateString('ru-RU') }}</span>
                    </div>
                    <span class="text-blue-800 px-2 py-1 rounded text-m">
                        {{ movementOrders.filter(item => {
                                return item.completion_date == slotProps.data.completion_date; // или условие видимости
                            }).length }} валов
                    </span>
                </div>
            </template>
        </DataTable>
        <Drawer v-model:visible="dataDrawer" :header="selectedOrder?.related?.okvid_number" position="right" class="md:!w-80 lg:!w-[40rem]">
            <div class="grid gap-4">
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Дата готовности заказа:</span>
                    <span class="text-color text-sm">{{ selectedOrder.completion_date }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Клиент:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.macro_order?.customer.name }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Описание:</span>
                    <span class="text-color text-sm">{{ (selectedOrder.related?.order?.items[0]?.item?.category ?? '')+'  '+(selectedOrder.related?.order?.items[0]?.item?.brand ?? '')}}</span>
                </div>
                <div class="flex justify-between gap-5" style="margin-bottom: 15px;">
                    <span class="text-gray-500 text-sm">Менеджер:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related?.order?.support_manager_name}}</span>
                </div>
                <div class="flex justify-between gap-5" style="margin-bottom: 15px;">
                    <span class="text-gray-500 text-sm">Дизайнер:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related?.designer?.name}}</span>
                </div>
            </div>
            <div class="grid gap-4 mt-1.5">
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Кол-во валов:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.quantity_shaft }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Номера валов:</span>
                    <span class="text-color text-sm">
                        {{ selectedOrder.related.engraving_order_shaft?.map(shaft => shaft.code).join(', ') || 'Нет данных' }}
                    </span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Дата заявки на гравировку:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.engraving_request_date}}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Наличие job ticket:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.engraving_request_date }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Гравировщик:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.engraver?.name }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Подготовщик файлов:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.repro?.name}}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Изменение элементов дизайна:</span>
                    <span class="text-color text-sm">{{ }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Этап прохождения заказа:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.order?.current_stage_approval}}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Новизна:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.order?.status }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Номер предыдущего заказа:</span>
                    <span class="text-color text-sm">{{ selectedOrder.related.order?.previous_order_number }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Номер следующего заказа:</span>
                    <span class="text-color text-sm">{{selectedOrder.related.order?.next_order_number }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Предыдущий гравировщик:</span>
                    <span class="text-color text-sm">{{ }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Номер предыдущего оквид:</span>
                    <span class="text-color text-sm">{{ }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Дата печати/ламинации предыдущего заказа:</span>
                    <span class="text-color text-sm">{{  }}</span>
                </div>
                <div class="flex justify-between gap-5">
                    <span class="text-gray-500 text-sm">Дата отгрузки/отправки валов:</span>
                    <span class="text-color text-sm">{{ }}</span>
                </div>
            </div>
            <div class="grid gap-4 mt-1.5">
                <div class="flex justify-between gap-5">
                    <Button size="small" severity="contrast" variant="outlined" label="Удалить строку" icon="pi pi-trash" @click="deleteOrder()"/>
                    <Button size="small" severity="contrast" variant="outlined" label="Разбить строку" @click="openSplitDialog()" />
                    <Button size="small" severity="contrast" variant="outlined" label="Отменить разделение" icon="pi pi-undo" />
                </div>
            </div>
        </Drawer>
        <Dialog v-model:visible="showSplitDialog" modal header="Разделить заказ" :style="{ width: '30%' }">
            <div v-for="(part, idx) in splitParts" :key="idx" class="flex gap-2 mb-2">
                <InputText type="date" v-model="part.date" dateFormat="yy-mm-dd" placeholder="Дата" />
                <InputText v-model.number="part.quantity" type="number" min="1" placeholder="Кол-во" />
                <Button icon="pi pi-trash" severity="danger" @click="removeSplitPart(idx)" size="small" v-if="splitParts.length > 1" />
            </div>
            <Button label="Добавить строку" @click="addSplitPart" size="small" class="mb-2" />
            <div class="flex justify-end gap-2 mt-4">
                <Button label="Разделить" @click="splitOrder" :disabled="!canSplit" />
                <Button label="Отмена" severity="secondary" @click="showSplitDialog = false" />
            </div>
            <div v-if="splitOrderOriginal">
                <p class="text-xs text-gray-500 mt-2">
                Сумма количеств должна быть равна исходному количеству: {{ splitOrderOriginal.related?.quantity_shaft || 0 }}
                </p>
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, toRefs, reactive,computed } from 'vue';
import { router, usePage } from "@inertiajs/vue3";
import { FilterMatchMode } from '@primevue/core/api';
import { DataTable, InputText, DatePicker, Column, IconField, InputIcon,Drawer,Button,Calendar,Select,Popover,Dialog } from "@danaflex/ui/components";
import { useToast } from "primevue/usetoast";
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js";
import axios from 'axios';
import { debounce } from 'lodash';

const props = defineProps({
    movementOrders: Array,
    filters: Object,
    engravers: Array,
});

const actions = ref(false);
const dataDrawer = ref(false);
const selectedOrder = ref(null);
const toast = useToast();
const { movementOrders, filters, engravers } = toRefs(props);
const showOrders = ref(false);
const redistributeQtyShaft = ref(null);
const redistributeDates = ref(null);
const showRedistribute = ref(false);
const orders = ref([]);
const loading = ref(false);
const showDownTime = ref(false);
const downTime = ref({
    completion_date: null,
    note: '',
});

const dates = ref([new Date(filters.value.start_date),new Date(filters.value.end_date)] || null); 

const selectedEngraver = ref(Number(filters.value?.engraver_id) || null);

const search = ref(filters.value?.search || '');

const showActions = (event) => { 
    actions.value.toggle(event);
}

const showInfo = (record) => {
    selectedOrder.value = record;
    dataDrawer.value = true;
};

const countShafts = () => {
  if (!movementOrders.value || !Array.isArray(movementOrders.value)) return 0;
  
  return movementOrders.value.reduce((total, order) => {
    try {
      const quantity = Number(order.related?.quantity_shaft);
      return total + (isNaN(quantity) ? 0 : quantity);
    } catch {
      return total;
    }
  }, 0);
};

const countFactShafts = () =>
  Array.isArray(movementOrders.value)
    ? movementOrders.value.reduce(
        (sum, o) =>
          sum + (o.related?.engraving_order_shaft?.filter(s => s.status === 'completed').length || 0),
        0
      )
    : 0;
 
const countOrders = () => {
  if (!Array.isArray(movementOrders.value)) return 0;

  const uniqueIds = new Set(
    movementOrders.value
      .map(order => order.related?.okvid_number)
      .filter(id => id !== undefined && id !== null)
  );

  return uniqueIds.size;
};

const countFactOrders = () => {
  if (!Array.isArray(movementOrders.value)) return 0;

  const uniqueIds = new Set();

  for (const order of movementOrders.value) {
    const engravingOrder = order.related;
    if (
      engravingOrder?.okvid_number &&
      engravingOrder.condition_id === 9
    ) {
      uniqueIds.add(engravingOrder.okvid_number);
    }
  }

  return uniqueIds.size;
};

const onCellEditComplete = ({ newData, index }) => {
    update([newData]);
};

const onRowReorder = (event) => {
  const rows = event.value;
  const moved = rows[event.dropIndex];

  const targetDate = movementOrders.value[event.dropIndex].completion_date;
  const sourceDate = moved.completion_date;

  moved.completion_date = targetDate;

  const updatedRows = rows.filter(
    r => r.completion_date === targetDate || (r.id !== moved.id && r.completion_date === sourceDate)
  );

  update(updatedRows);
};

const onSearchInput = debounce(() => {
    fetchOrders();
}, 500);

const fetchOrders = () => {
    loading.value = true;

    const params = {
        search: search.value,
    };

    if (dates.value && dates.value.length === 2) {
        params.start_date = dates.value[0].toLocaleDateString('sv-SE');
        params.end_date = dates.value[1].toLocaleDateString('sv-SE');
    }

    if (selectedEngraver.value) {
        params.engraver_id = selectedEngraver.value;
    }

    router.get('/movement-order', params, {
        preserveState: true,
        only: ['movementOrders', 'filters','engravers'],
        replace: true,
        onFinish: () => loading.value = false
    });
};

const getOrders = async () => {
  try {
    const url = routeZiggy(
        'movementOrder.getOrders', 
        {},
        undefined,
        Ziggy
    ); 
    const response = await axios.get(url);

    orders.value = response.data;
    showOrders.value = true;
  }
  catch (error) {
      console.error('Ошибка в загрузки заказов', error);
  }
};

const addOrder = (order) => {
    order.status = 'in_progress';
    order.completion_date = new Date();
    order.engraver_id = selectedEngraver.value;

    showOrders.value = false;
    update([order]);
}

const addDownTime = () => {
    const url = routeZiggy('movementOrder.addDownTime', {}, undefined, Ziggy);

    const payload = {
        ...downTime.value,
        engraver_id: selectedEngraver.value,
    };

    if (payload.completion_date) {
        payload.completion_date = payload.completion_date.toLocaleDateString('sv-SE');
    }

    router.post(url, payload, {
        onSuccess: () => {
            showDownTime.value = false;
            downTime.value = { completion_date: null, note: '' };
            toast.add({ severity: 'success', summary: 'Простой добавлен', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Ошибка добавления простоя', life: 3000 });
        },
    });
};


const redistributeOrders = async () => {
    redistributeDates.value[0] = redistributeDates.value[0].toLocaleDateString('sv-SE');
    redistributeDates.value[1] = redistributeDates.value[1].toLocaleDateString('sv-SE');
    try { 
        const url = routeZiggy('movementOrder.redistributeOrders', {}, undefined, Ziggy);
        await router.post(url, {dates: redistributeDates.value, qty: redistributeQtyShaft.value, engraverId: selectedEngraver.value} , {
            preserveScroll: true,
            preserveState: true,
        });

        redistributeDates.value = null;
        redistributeQtyShaft.value = null;
        showRedistribute.value = false;
        toast.add({ severity: 'success', summary: 'Заказ удален', life: 3000 });
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при удалении заказа', life: 3000 });
    }
};

const deleteOrder = async () => {
    selectedOrder.value.status = 'await';
    try {
        const url = routeZiggy('movementOrder.update', {}, undefined, Ziggy);
        await router.post(url, [selectedOrder.value], {
            preserveScroll: true,
            preserveState: true,
        });

        dataDrawer.value = false;
        toast.add({ severity: 'success', summary: 'Заказ удален', life: 3000 });
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при удалении заказа', life: 3000 });
    }
};

const update = async (rows) => {
    try {
        const url = routeZiggy('movementOrder.update', {}, undefined, Ziggy);
        await router.post(url, rows, {
            preserveScroll: true,
            preserveState: true,
        });

        toast.add({ severity: 'success', summary: 'Строки обновлены', life: 3000 });
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Ошибка при обновлении строк', life: 3000 });
    }
};

const showSplitDialog = ref(false);
const splitParts = ref([
  { date: '', quantity: 1 }
]);
const splitOrderOriginal = ref(null);

function openSplitDialog() {
  if (!selectedOrder.value) return;
  splitOrderOriginal.value = selectedOrder.value;
  splitParts.value = [
    { date: selectedOrder.value.completion_date, quantity: selectedOrder.value.related?.quantity_shaft || 1 }
  ];
  showSplitDialog.value = true;
}

function addSplitPart() {
  splitParts.value.push({ date: '', quantity: 1 });
}

function removeSplitPart(idx) {
  if (splitParts.value.length > 1) splitParts.value.splice(idx, 1);
}

const canSplit = computed(() => {
  if (!splitOrderOriginal.value) return false;
  const total = splitParts.value.reduce((sum, s) => sum + (Number(s.quantity) || 0), 0);
  return (
    splitParts.value.length > 1 &&
    splitParts.value.every(s => s.date && s.quantity > 0) &&
    total === (splitOrderOriginal.value.related?.quantity_shaft || 0)
  );
});

const splitOrder = async () => {
    const url = routeZiggy('movementOrder.split', {}, undefined, Ziggy);
    await router.post(
    url,
    {
      order_id: splitOrderOriginal.value.id,
      splits: splitParts.value,
      start_date: dates.value?.[0]?.toISOString().slice(0, 10),
      end_date: dates.value?.[1]?.toISOString().slice(0, 10),
      engraver_id: selectedEngraver.value,
      search: search.value,
    },
    {
      onSuccess: () => {
        showSplitDialog.value = false;
        splitOrderOriginal.value = null;
        splitParts.value = [{ date: '', quantity: 1 }];
        dataDrawer.value = false;
        toast.add({ severity: 'success', summary: 'Успех', detail: 'Заказ разделён', life: 3000 });
      }
    }
  );
}
</script>
