<template>
    <div class="mc-wrapper">
        <div class="filter-row">
            <label>Операция:</label>
            <a-select
                v-if="operations"
                placeholder="Выберите операцию"
                v-model="selectOperation"
                mode="multiple"
                style="width: 100%;"
            >
                <a-select-option
                    v-for="operation in operations"
                    :key="operation.id"
                >
                    {{ operation.description }}
                </a-select-option>
            </a-select>
        </div>
        <div class="statistic-row">
            <div class="generela-statistic-mc">
                <StatisticByMcChartComponent v-if="selectOperation.length > 0" :data="filteredData" />
            </div>
            <div class="shifts-chart">
                <StatisticByMcShiftChartComponent v-if="selectOperation.length > 0" :data="filteredData" />
            </div>
        </div>
        <div class="statistic-row">
            <div class="average-block">
                <StatisticByMcAverageComponent
                    v-if="selectOperation.length > 0"
                    :data="filteredData"
                    :title="'Среднее кол-во ПЦ за смену'"
                    :unit="'шт'"
                />
            </div>
            <div class="average-block">
                <StatisticByMcAverageComponent
                    v-if="selectOperation.length > 0"
                    :data="filteredData"
                    :title="'Среднее время работы на 1 ПЦ (все операции)'"
                    :unit="'мин'"
                />
            </div>
        </div>
        <div class="statistic-row">
            <StatisticByMcWorkTimeComponent v-if="selectOperation.length > 0" :operations="selectOperation" :machines="this.machines" />
        </div>
        <div class="statistic-row">
            <StatisticByMcOperatorsTableComponent v-if="selectOperation.length > 0" :operations="selectOperation" :machines="this.machines"/>
        </div>
    </div>
</template>

<script>
import StatisticByMcShiftChartComponent from "./StatisticByMcShiftChartComponent.vue";
import StatisticByMcChartComponent from "./StatisticByMcChartComponent.vue";
import StatisticByMcWorkTimeComponent from "./StatisticByMcWorkTimeComponent.vue";
import StatisticByMcOperatorsTableComponent from "./StatisticByMcOperatorsTableComponent.vue";
import StatisticByMcAverageComponent from "./StatisticByMcAverageComponent.vue";

import { mapGetters } from 'vuex';

export default {
    components: {
        StatisticByMcShiftChartComponent,
        StatisticByMcChartComponent,
        StatisticByMcWorkTimeComponent,
        StatisticByMcOperatorsTableComponent,
        StatisticByMcAverageComponent,
    },
    props: {
        data: {
            type: Array,
            required: true,
        },

        machines: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selectOperation: [],
            operations: [],
        };
    },
    computed: {
        ...mapGetters('statistic', ['getFilters']),

        filteredData() {
            if (!this.selectOperation) return this.data;
            return this.data.filter((op) =>
                this.selectOperation.includes(op.operation_id)
            );
        },
    },

    watch: {
        // Наблюдатель за изменением operations
        machines: {
            handler: 'fetchOperations', // Вызываем fetchTableData при изменении
            immediate: true, // Вызываем сразу при инициализации компонента
            deep: true, // Отслеживаем изменения внутри массива
        },

        getFilters: {
            handler: 'fetchOperations', // Вызываем fetchTableData при изменении
            immediate: true, // Вызываем сразу при инициализации компонента
            deep: true, // Отслеживаем изменения внутри массива
        },
    },

    methods: {
        async fetchOperations() {
            try {
                const response = await axios.get(route('ugpc.statistic.getOperations'), {
                    params: {
                        machines: this.machines,
                    }
                });
                this.operations = response.data.operations; 
            } catch (error) {
                console.error('Ошибка при загрузке операций:', error);
            }
        },
    },

    mounted() {
        this.fetchOperations();
    },
};
</script>

<style scoped>
.filter-row {
    display: flex;
    flex-direction: column;
}
.chart {
    display: block;
    width: 100%;
}
.generela-statistic-mc {
    display: flex;
    width: 70%;
}
.shifts-chart {
    display: flex;
    width: 30%;
}
.statistic-row {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    width: 100%;
}
.average-block {
    width: 50%;
}
</style>
