<template>
  <div class="col-span-full justify-center flex flex-col items-center">
    <input
      ref="scanInput"
      type="text"
      class="absolute opacity-0"
      style="z-index: 9999; left: 0; top: 0; width: 200px;"
      @input="onInput"
      @keydown.enter.prevent="onSubmit"
      @change="onScanComplete"
      @blur="onScanComplete"
      autocomplete="off"
      @focus="disableSoftKeyboard"
      tabindex="1"
    />
    <p v-if="!isScanning && step === 0">Ожидаем сканирование...</p>
    <div v-if="shaft && step > 0" class="p-4 space-y-4 w-full max-w-md">
      <Card>
        <template #title>Информация о вале</template>
        <template #content>
          <div class="grid grid-cols-2 gap-4">
            <div><strong>ID:</strong> {{ shaft.code }}</div>
            <div><strong>Тип:</strong> {{ shaft.type }}</div>
            <div><strong>Диаметр:</strong> {{ shaft.diameter }}</div>
            <div><strong>Толщина:</strong> {{ shaft.thickness ?? '-' }}</div>
          </div>
        </template>
      </Card>
      <div v-if="step === 1" class="flex flex-col gap-4 mt-4">
        <Button label="Далее" @click="step = 2; focusInput()" />
      </div>
      <div v-if="step === 2" class="flex flex-col gap-4 mt-4">
        <label class="block text-sm font-medium">Выберите тип</label>
        <select v-model="form.type" class="w-full border rounded p-2">
          <option disabled value="">Выберите тип</option>
          <option v-for="option in typeOptions" :key="option" :value="option">{{ option }}</option>
        </select>
        <Button label="Далее" :disabled="!form.type" @click="step = 3; focusInput()" />
      </div>
      <div v-if="step === 3" class="flex flex-col gap-4 mt-4">
        <label class="block text-sm font-medium">Введите диаметр</label>
        <InputText v-model.number="form.diameter" type="number" class="w-full" placeholder="Диаметр" />
        <Button label="Далее" :disabled="!form.diameter" @click="step = 4; focusInput()" />
      </div>
      <div v-if="step === 4" class="flex flex-col gap-4 mt-4">
        <label class="block text-sm font-medium">Введите толщину</label>
        <InputText v-model.number="form.thickness" type="number" class="w-full" placeholder="Толщина" />
        <Button label="Далее" :disabled="!form.thickness" @click="step = 5; focusInput()" />
      </div>
      <div v-if="step === 5" class="flex flex-col gap-4 mt-4">
        <h3 class="text-lg font-bold mb-2">Проверьте параметры</h3>
        <div class="bg-gray-100 rounded p-4">
          <div><strong>Тип:</strong> {{ form.type }}</div>
          <div><strong>Диаметр:</strong> {{ form.diameter }}</div>
          <div><strong>Толщина:</strong> {{ form.thickness }}</div>
        </div>
        <Button label="Завершить" @click="finishForm" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from 'vue';
import { InputText, Card, Button } from "@danaflex/ui/components";
import { router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js"
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  engravingOrderShaft: Object,
});

const toast = useToast();
const scanInput = ref(null);
const scannedData = ref('');
const isScanning = ref(false);
const step = ref(0);
const shaft = ref(null);
const form = ref({ type: '', diameter: '', thickness: '' });
const typeOptions = ["Стальной", "Алюминиевый", "Композитный"];

const page = usePage();

watch(
  () => props.engravingOrderShaft,
  (val) => {
    if (val) {
      shaft.value = val.shaft;
      step.value = 1;
      form.value = {
        type: val.shaft?.type || '',
        diameter: val.shaft?.diameter || '',
        thickness: val.shaft?.thickness || '',
      };
    }
  },
  { immediate: true }
);

const disableSoftKeyboard = (e) => {
  e.target.readOnly = true
  setTimeout(() => e.target.readOnly = false, 100)
}


const showToast = (severity, summary, detail) => {
  toast.add({ severity, summary, detail, life: 3000 });
};

const focusInput = () => {
  nextTick(() => scanInput.value?.focus());
};

const onInput = (e) => {
  scannedData.value = e.target.value;
  isScanning.value = true;
};

const onScanComplete = (e) => {
  if (scannedData.value) {
    onSubmit();
  }
};

const onSubmit = () => {
  const input = scannedData.value.trim();
  const parts = input.split('|');
  const lastPart = parts.at(-1)?.trim();

  if (!lastPart || isNaN(lastPart)) {
    showToast('warn', 'Ошибка', 'QR не распознан');
  } else {
    router.post(
      routeZiggy('inputControl.infoShaft', {}, undefined, Ziggy),
      { shaftId: lastPart },
      {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
          // После завершения визита снова ставим фокус
          scannedData.value = '';
          isScanning.value = false;
          nextTick(() => scanInput.value?.focus());
        },
      }
    );
    return; // выходим, остальное сделает onFinish
  }

  // для невалидного QR:
  scannedData.value = '';
  isScanning.value = false;
  if (scanInput.value) {
    scanInput.value.value = '';
    scanInput.value.focus();
  }
};


const finishForm = async () => {
  try {
    router.post(
      routeZiggy('inputControl.acceptShaft', {}, undefined, Ziggy),
      {
        engravingOrderShaftId: props.engravingOrderShaft.id,
        shaft_id: shaft.value?.id,            // безопасно, если вдруг нет shaft
        type_shaft: form.value.type,
        diameter: form.value.diameter,
        copper_platting: form.value.thickness,
      },
      {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
          // Сброс шага/формы после завершения визита
          step.value = 0;
          shaft.value = null;
          form.value = { type: '', diameter: '', thickness: '' };
          scannedData.value = '';
          isScanning.value = false;
          // Фокус обязательно после nextTick
          nextTick(() => scanInput.value?.focus());
        },
      }
    );
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Ошибка при сохранении данных', life: 3000 });
  }
};

onMounted(() => {
  focusInput();
});
</script>

<style scoped>

</style>
