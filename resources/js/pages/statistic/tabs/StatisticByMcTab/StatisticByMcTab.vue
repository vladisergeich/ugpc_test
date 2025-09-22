<template>
    <div class="statistics-by-mc">
        <div class="filter-row">
            <label>Машина:</label>
            <a-select
                placeholder="Выберите машину"
                v-model="selectMachine"
                mode="multiple"
                style="width: 100%;"
            >
            <a-select-opt-group
                v-for="center in workCenters"
                    :key="center.id"
                    :label="center.work_center"
            >
                <a-select-option
                    v-for="machine in center.machines"
                    :key="machine.machine"
                >
                    {{ machine.machine }}
                </a-select-option>
            </a-select-opt-group>
            </a-select>
        </div>
        <StatisticByMcComponent v-if="selectMachine.length > 0" :machines="selectMachine" :data="data.filter(operation => selectMachine.includes(operation.machine.machine))"/>
    </div>
</template>

<script>
import StatisticByMcComponent from './StatisticByMcComponent.vue';
export default {
    components: {
        StatisticByMcComponent,
    },
    props: {
        data: {
            type: Array,
            required: true,
        },

        plan: {
            type: Array,
            required: true,
        },

        workCenters: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selectMachine: [],
            machines: [
                {
                    'id' : 14,
                    'title':'CFM-1',
                    'work_center_id' : 2,
                    'operations' : ['Итого','Брак','Шлифовка тираж','Шлифовка базы'],
                },
                {
                    'id' : 15,
                    'title':'CFM-2',
                    'work_center_id' : 2,
                    'operations' : ['Итого','Брак','Шлифовка тираж','Шлифовка базы'],
                },
                {
                    'id' : 11,
                    'title':'K5-1',
                    'work_center_id' : 3,
                    'operations' : ['Итого','Брак','Гравировка'],
                },
                {
                    'id' : 12,
                    'title':'K5-2',
                    'work_center_id' : 3,
                    'operations' : ['Итого','Брак','Гравировка'],
                },
                {
                    'id' : null,
                    'title':'Гальваника',
                    'work_center_id' : 1,
                },
                {
                    'id' : 13,
                    'title':'Пробпечать',
                    'work_center_id' : 4,
                },
            ],
        };
    }
};
</script>

<style scoped>
    .statistics-by-mc {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .filter-row {
        display: flex;
        flex-direction: column;
    }
</style>
  