<script setup>
import { defineProps } from 'vue';
import { DataTable, Card, FileUpload,DatePicker,Select,Textarea,Button,Dialog,InputText,InputNumber } from "@danaflex/ui/components";
import { ref, toRefs, computed } from "vue";
import { useToast } from "primevue/usetoast";
import { router } from '@inertiajs/vue3';
import { route as routeZiggy } from "ziggy-js";
import { Ziggy } from "@/ziggy.js"

const props = defineProps({
    steelApp: Object,
});

const toast = useToast();

const { steelApp } = toRefs(props); 

const uploadedFiles = ref([])

const uploadFiles = async (event) => {
  const file = event.files[0];
  const formData = new FormData();
  formData.append("file", file);
  formData.append("steelApp[id]", props.steelApp.id);

  const url = routeZiggy('steelApp.store', {}, undefined, Ziggy);

  const response = await axios.post(url, formData, {
    headers: { "Content-Type": "multipart/form-data" },
  });

  // отправим patch на steelApp.update, чтобы обновить весь объект
  await router.post(
    routeZiggy("steelApp.update", {}, undefined, Ziggy),
    response.data,
    { preserveScroll: true, preserveState: true }
  );
};

const deleteFile = async (uuid) => {
  const url = routeZiggy('steelApp.deleteFile', {},undefined, Ziggy);

  await router.post(url, { file_id: uuid, app: steelApp.value }, {
    preserveScroll: true,
    preserveState: true,
  });
};

</script>

<template>
      <div class="mt-2">
        <FileUpload
            name="files[]"
            :multiple="true"
            customUpload
            @uploader="uploadFiles"
            :showUploadButton="false"
            :showCancelButton="false"
            accept=""
            size="small"
            :auto="true"
            chooseLabel="Выбрать файлы"
            >
            <template #content>
                <div v-if="steelApp.attachments?.length" class="mt-2 space-y-1 text-sm">
                    <div
                    v-for="file in steelApp.attachments"
                    :key="file.id"
                    class="flex items-center justify-between text-gray-700"
                    >
                    <a
                        :href="`/storage/${file.path}`"
                        target="_blank"
                        class="truncate max-w-xs hover:text-blue-600"
                    >
                        📎 {{ file.name }}
                    </a>
                    <button
                        class="text-red-500 hover:text-red-700 text-xs ml-2"
                        @click="deleteFile(file.id)"
                        title="Удалить"
                    >
                        🗑
                    </button>
                    </div>
                </div>
            </template>
        </FileUpload>
    </div>
</template>