<template>
    <div class="bg-white p-5 rounded-xl shadow-md">
        <div class="space-y-6">
            <div>
                <Calendar 
                    v-model="filterDates" 
                    selection-mode="range" 
                    date-format="yy-mm-dd"
                    @update:model-value="applyFilters"
                    placeholder="Выберите период"
                    class="w-full"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Смена</label>
                <div class="space-y-2">
                    <div 
                        v-for="letter in letters" 
                        :key="letter"
                        class="flex items-center"
                    >
                    
                        <Checkbox 
                            :input-id="letter" 
                            v-model="filters.letters" 
                            :value="letter"
                            @change="onFilterChange"
                        />
                        <label :for="letter" class="ml-2 text-sm text-gray-700">{{ letter }}</label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Оператор</label>
                <Select 
                    v-model="filters.user" 
                    :options="users"
                    option-label="employer_name_1c"
                    option-value="id"
                    placeholder="Выбрать"
                    show-clear
                    class="w-full"
                    @change="onFilterChange"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Calendar, Checkbox, Select } from '@danaflex/ui/components'

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['filtersApplied', 'filterChange'])

const today = new Date()
const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 2)
const endOfMonth = today

const filterDates = ref([startOfMonth, endOfMonth])
const filters = ref({
    letters: ['А', 'Б', 'В', 'Г'],
    user: null,
})
const letters = ref(['А', 'Б', 'В', 'Г'])

const applyFilters = () => {
    const formattedDates = filterDates.value ? [
        filterDates.value[0]?.toISOString().split('T')[0],
        filterDates.value[1]?.toISOString().split('T')[0]
    ] : null

    const updatedFilters = {
        dates: formattedDates,
        letters: filters.value.letters,
        user: filters.value.user,
    }

    emit('filtersApplied', updatedFilters)
    emit('filterChange', { filters: filters.value })
}

const onFilterChange = () => {
    emit('filterChange', { filters: filters.value })
}

onMounted(() => {
    applyFilters()
})
</script>