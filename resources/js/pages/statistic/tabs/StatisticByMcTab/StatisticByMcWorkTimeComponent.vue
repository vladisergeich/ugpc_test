<template>
    <div class="work_time-wrapper">
        <span class="work_time-title">Время работы</span>
        <a-spin :spinning="isLoading"></a-spin>
        <a-table
            v-if="!isLoading" 
            :columns="columns" 
            :data-source="tableData" 
            size="small"
            bordered
            :scroll="{ x: '1800px', y: true }"
        >
        </a-table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    components: {

    },
    props: {
        operations: {
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
            columns: [],
            tableData: [],
            isLoading: true,
        };
    },

    watch: {
        machines: {
            handler: 'fetchTableData', // Вызываем fetchTableData при изменении
            immediate: true, // Вызываем сразу при инициализации компонента
            deep: true, // Отслеживаем изменения внутри массива
        },
        
        operations: {
            handler: 'fetchTableData', // Вызываем fetchTableData при изменении
            immediate: true, // Вызываем сразу при инициализации компонента
            deep: true, // Отслеживаем изменения внутри массива
        },

        getFilters: {
            handler: 'fetchTableData', // Вызываем fetchTableData при изменении
            immediate: true, // Вызываем сразу при инициализации компонента
            deep: true, // Отслеживаем изменения внутри массива
        },
    },

    computed: {
        ...mapGetters('statistic', ['getFilters']),
    },

    methods: {
        async fetchTableData() {
            try {
                this.isLoading = true;
                const response = await axios.get(route('ugpc.statistic.getShiftData'), {
                    params: {
                        filters: this.getFilters,
                        operations: this.operations,
                        machines: this?.machines,
                    }
                });
                
                this.tableData = response.data;
                this.setupColumns(response.data);
            } finally {
                this.isLoading = false;
            }
        },
        setupColumns(data) {
            // Собираем все уникальные операции
            const operationDescriptions = new Set();
            data.forEach(user => {
                Object.keys(user.operations).forEach(operation => {
                operationDescriptions.add(operation);
                });
            });

            // Формируем колонки для каждой операции
            const operationColumns = Array.from(operationDescriptions).map((operation) => ({
                title: operation,
                children: [
                { title: 'Время работы, мин', dataIndex: `operations.${operation}.workTime`, key: `operations.${operation}.workTime`,width: 150, },
                { title: 'Ср. время работы на 1 ПЦ, мин', dataIndex: `operations.${operation}.avgTimePerUnit`, key: `operations.${operation}.avgTimePerUnit`,width: 150, },
                { title: 'ПВ, %', dataIndex: `operations.${operation}.usefulTime`, key: `operations.${operation}.usefulTime`,width: 50, },
                ],
            }));

            // Формируем столбцы таблицы
            this.columns = [
                { title: 'Смены', dataIndex: 'letter', key: 'letter',width: 100, },
                ...operationColumns,
                { title: 'Время работы, мин', dataIndex: 'workTimeAll', key: 'workTimeAll',width: 200, },
                { title: 'Отсутствие работы, мин', dataIndex: 'absenceTime', key: 'absenceTime',width: 200, },
            ];
        },
    },
    mounted() {
        this.fetchTableData();
    },

};
</script>

<style scoped>

.work_time-wrapper {
    max-width: 100%; /* Ограничиваем ширину контейнера */
    overflow: auto; 
    display: flex;
    justify-content: center;
    flex-direction: column;
    background: #FFFFFF;
    gap: 15px;
    width: 100%;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.work_time-title {
    font-family: Open Sans;
    font-size: 18px;
    font-weight: 400;
    color: #363F51;
}
</style>
  