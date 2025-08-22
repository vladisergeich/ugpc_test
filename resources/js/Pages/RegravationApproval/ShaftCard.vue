<template>
    <Card>
        <template #title>
            Вал: {{ shaft.shaft?.code }}
        </template>
        <template #content>
            <div class="gap-4 grid">
                <div class="field col-12 md:col-6">
                    <label for="reason">Причина перегравировки</label>
                    <Dropdown 
                    id="reason"
                    v-model="shaft.reason_id"
                    :options="reasons"
                    optionLabel="name"
                    class="w-full"
                    size="small"
                    placeholder="Выберите причину"
                    />
                </div>
                <div class="field col-12 md:col-6">
                    <label for="note">Комментарий</label>
                    <Textarea size="small" class="w-full" v-model="shaft.note" placeholder="Комментарий" rows="5" />
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup>
import { ref, toRefs, computed } from "vue";
import { router } from '@inertiajs/vue3';
import { Card, Button,FileUpload,Textarea,Dropdown } from "@danaflex/ui/components";

const props = defineProps({
    shaft: Object,
});

const { shaft } = toRefs(props); 

</script>
