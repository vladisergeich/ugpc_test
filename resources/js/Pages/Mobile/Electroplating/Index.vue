<template>
  <div class="grid card bg-white col-span-full">

    <!-- Невидимый input под HID-сканер -->
    <input
      ref="scanInput"
      type="text"
      class="absolute opacity-0"
      style="z-index: 9999; left: 0; top: 0; width: 200px;"
      @input="onInput"
      @keydown.enter.prevent="onScan"
      @focus="disableSoftKeyboard"
      autocomplete="off"
      tabindex="1"
    />

    <Toast
      group="pwa"
      position="top-center"
      :appendTo="'body'"
      :pt="{
        root: {
          style: `
            position: fixed;
            top: env(safe-area-inset-top, 0px);
            left: 50%;
            transform: translateX(-50%);
            width: calc(100vw - env(safe-area-inset-left, 0px) - env(safe-area-inset-right, 0px) - 16px);
            max-width: 480px;
            box-sizing: border-box;
            z-index: 2147483647;
            pointer-events: none; /* контейнер пропускает клики */
          `
        },
        container: { style: 'pointer-events: auto;' }, /* само сообщение кликабельно */
        content: {
          style: `
            word-break: break-word;
            overflow-wrap: anywhere;
            white-space: normal;
          `
        },
        message: { style: 'margin: 8px;' }
      }"
    />
    <div v-if="!visibleShaft" class="p-4 space-y-4 w-full">
      <div class="flex items-center justify-center w-full">
        <h2 class="text-lg font-bold">Текущие операции</h2>
      </div>

      <div v-if="runningOperations.length" class="grid gap-3 w-full">
        <Card v-for="op in runningOperations" :key="op.id" class="shadow">
          <template #content>
            <div class="flex gap-1">
              <span class="font-medium text-sm">{{ op.operation?.name }}</span>
              <span class="text-sm text-gray-500">Вал: {{ op.engraving_order_shaft?.shaft?.code }}</span>
            </div>
          </template>
        </Card>
      </div>

      <div v-else class="flex flex-col items-center justify-center h-72 text-center">
        <i class="pi pi-qrcode text-5xl text-gray-400 mb-3"></i>
        <div class="text-gray-700 font-medium">Отсканируйте QR‑код вала, чтобы начать</div>
      </div>
    </div>

    <div v-if="visibleShaft" class="p-4 space-y-4 w-full max-w-md">
      <Card>
        <template #title>Информация о вале</template>
        <template #content>
          <div v-if="shaftInfo?.shaft" class="grid gap-2">
            <div><strong>Вал:</strong> {{ shaftInfo.shaft.code }}</div>
            <div><strong>Заказ:</strong> {{ shaftInfo.engraving_order.order.order_number }}</div>
            <div><strong>Оквид:</strong> {{ shaftInfo.engraving_order.okvid_number }}</div>
          </div>
          <div v-if="shaftInfo?.active_stage?.operations" class="grid gap-2">
            <div><strong>Операция</strong> {{ shaftInfo?.active_stage?.operations[0].operation.name }}</div>
          </div>
          <div class="w-full flex justify-center items-center">
            <div class="text-gray-500">
              Далее отсканируйте <span class="font-semibold">машину</span> для старта операции
            </div>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick, onBeforeUnmount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'
import Toast from 'primevue/toast'
import { Card, Dialog } from '@danaflex/ui/components'
import { route as routeZiggy } from 'ziggy-js'
import { Ziggy } from '@/ziggy.js'
import axios from 'axios'

const page = usePage()
const toast = useToast()

const workCenterId = page.props.workCenterId
const initialOps = page.props.runningOperations ?? []
const runningOperations = ref([...initialOps])

// ===== Reverb / Echo =====
let channel
const subscribeWs = () => {
  if (!window.Echo || !workCenterId) return
  channel = window.Echo.channel(`workcenter.${workCenterId}`)
  channel
    .listen('.operation.started', ({ entry }) => {
      const idx = runningOperations.value.findIndex(e => e.id === entry.id)
      if (idx === -1) runningOperations.value.unshift(entry)
      else runningOperations.value[idx] = entry
    })
    .listen('.operation.closed', ({ entryId, machine_id }) => {
      runningOperations.value = runningOperations.value.filter(
        e => e.id !== entryId && e.machine_id !== machine_id
      )
    })
}

onMounted(() => {
  focusInput()
  subscribeWs()
})

onBeforeUnmount(() => {
  if (channel) {
    channel.stopListening('.operation.started')
    channel.stopListening('.operation.closed')
  }
})

const scanInput = ref(null)
const scannedData = ref('')
const visibleShaft = ref(false)
const pendingShaftId = ref(null)
const shaftInfo = ref(null)

const focusInput = () => nextTick(() => scanInput.value?.focus())

const disableSoftKeyboard = e => {
  // на Android убираем софт-клаву
  e.target.readOnly = true
  setTimeout(() => (e.target.readOnly = false), 100)
}

const parse = raw => {
  const [type, value] = String(raw || '').trim().split('|')
  return { type, value }
}

const onInput = e => {
  scannedData.value = e.target.value
}

const cancelPending = () => {
  pendingShaftId.value = null
  shaftInfo.value = null
  visibleShaft.value = false
}

const reset = (dropPending = false) => {
  scannedData.value = ''
  if (scanInput.value) scanInput.value.value = ''
  if (dropPending) cancelPending()
  focusInput()
}

const onScan = async () => {
  const { type, value } = parse(scannedData.value)

  if (!type || !value) {
    toast.add({  group: 'pwa', severity: 'warn', summary: 'Ошибка', detail: 'Пустой или неверный QR', life: 2500 })
    reset()
    return
  }

  // 1) Ждём машину после скана вала
  if (pendingShaftId.value) {
    if (type != 'machine') {
      toast.add({
        group: 'pwa',
        severity: 'info',
        summary: 'Требуется машина',
        detail: 'Отсканируйте QR машины',
        life: 2500
      })
      reset(false) // НЕ закрываем модалку, продолжаем ждать
      return
    }

    // старт операции
    router.post(
      routeZiggy('operationLedgerEntry.create', {}, undefined, Ziggy),
      { machineId: value, operationId: shaftInfo.value.active_stage?.operations[0].operation.id, engravingOrderShaft:  shaftInfo.value },
      {
        preserveScroll: true,
        onSuccess: () => {
          toast.add({  group: 'pwa', severity: 'success', summary: 'ОК', detail: 'Операция начата', life: 1500 })
          cancelPending() // закроем модалку и сбросим ожидание
        },
        onError: errs => {
          if (errs?.machine) {
            toast.add({  group: 'pwa', severity: 'warn', summary: 'Машина занята', detail: errs.machine, life: 2500 })
          } else {
            toast.add({   group: 'pwa', severity: 'error', summary: 'Ошибка', detail: 'Не удалось начать', life: 2500 })
          }
          // остаёмся в модалке, ждём корректный скан машины
        },
        onFinish: focusInput
      }
    )
    reset()
    return
  }

  // 2) Обычный экран (не ждём машину)
  switch (type) {
    case 'shaft': {
      try {
        const { data } = await axios.post(routeZiggy('mobileElectroPlating.infoShaft', {}, undefined, Ziggy), {
          shaftId: value,
        })

        if (data.ok) {
          pendingShaftId.value = data.shaft.id
          shaftInfo.value = data.shaft
          visibleShaft.value = true
          scannedData.value = ''
          nextTick(() => scanInput.value?.focus())
        }
      } catch (err) {
        if (err.response?.status === 409 && err.response?.data?.code === 'WRONG_STAGE') {
          const stageName = err.response.data.stage?.name ?? 'другая стадия'
          toast.add({
            group: 'pwa',
            severity: 'warn',
            summary: 'Вал в другой стадии',
            detail: `Сейчас: «${stageName}». Начать операцию здесь нельзя.`,
            life: 4000,
          })
        } else if (err.response?.status === 422) {
          toast.add({  group: 'pwa', severity: 'error', summary: 'Ошибка', detail: 'Вал не найден', life: 2500 })
        } else {
          toast.add({   group: 'pwa',severity: 'error', summary: 'Ошибка', detail: 'Не удалось получить данные', life: 2500 })
        }
        scannedData.value = ''
        nextTick(() => scanInput.value?.focus())
      }
      break
    }
    case 'machine': {
      // Если на машине есть активная — закрываем
      const current = runningOperations.value.find(op => String(op.machine_id) === String(value))
      if (!current) {
        toast.add({   group: 'pwa',severity: 'info', summary: 'Свободна', detail: 'На машине нет активных операций', life: 2000 })
        break
      }

      if (current && current.operation.parameters) {
        current.operation.parameters.forEach(function(param) {
          if (param.parameter_id == 5) {
            param.value = current.phase_stage.parameters[0].float_value;
          }

          if (param.parameter_id == 16) {
            param.value = current.machine.code;
          }
        });
      }
      
      router.post(
        routeZiggy('operationLedgerEntry.update', {}, undefined, Ziggy),
        { operation: current },
        {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () =>
            toast.add({   group: 'pwa',severity: 'success', summary: 'Закрыта', detail: 'Операция завершена', life: 1500 }),
          onError: () =>
            toast.add({   group: 'pwa',severity: 'error', summary: 'Ошибка', detail: 'Не удалось завершить', life: 2500 }),
          onFinish: focusInput
        }
      )
      break
    }
    case 'defect': {
      router.post(
        routeZiggy('defect.create', {}, undefined, Ziggy),
        { machine_id: value },
        {
          preserveScroll: true,
          onSuccess: () =>
            toast.add({   group: 'pwa',severity: 'success', summary: 'Учтено', detail: 'Дефект учтён', life: 1500 }),
          onError: () =>
            toast.add({   group: 'pwa',severity: 'error', summary: 'Ошибка', detail: 'Не удалось учесть дефект', life: 2500 }),
          onFinish: focusInput
        }
      )
      break
    }
    default:
      toast.add({
        group: 'pwa',
        severity: 'warn',
        summary: 'Неизвестный QR',
        detail: 'Отсканируйте вал или машину',
        life: 2500
      })
  }

  reset()
}
</script>

<style scoped>

</style>
