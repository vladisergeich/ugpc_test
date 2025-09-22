<template>
    <div class="work_time-wrapper">
        <span class="work_time-title">По операторам</span>
        <a-spin :spinning="isLoading"></a-spin>
        <a-table 
            v-if="!isLoading"
            :columns="columns" 
            :data-source="tableData" 
            size="small"
            bordered
            style="min-width: 100%;"
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
            fetchTimeout: null,
        };
    },

    watch: {
        machines: {
            handler() {
                this.debouncedFetchData();
            },
            deep: true,
        },
        operations: {
            handler() {
                this.debouncedFetchData();
            },
            deep: true,
        },
        getFilters: {
            handler() {
                this.debouncedFetchData();
            },
            deep: true,
        },
    },

    computed: {
        ...mapGetters('statistic', ['getFilters']),
    },

    methods: {
        debouncedFetchData() {
            clearTimeout(this.fetchTimeout);
            this.fetchTimeout = setTimeout(() => {
                this.fetchTableData();
            }, 500); // Задержка 500 мс
        },

        async fetchTableData() {
            try {
                this.isLoading = true;
                const response = await axios.get(route('ugpc.statistic.getOperatorData'), {
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
                { title: 'ПЦ, шт', dataIndex: `operations.${operation}.qtyShaft`, key: `operations.${operation}.qtyShaft`, width: 50, },
                { title: 'Время работы, мин', dataIndex: `operations.${operation}.workTime`, key: `operations.${operation}.workTime`,width: 100, },
                { title: 'Ср. время работы на 1 ПЦ, мин', dataIndex: `operations.${operation}.avgTimePerUnit`, key: `operations.${operation}.avgTimePerUnit`,width: 150, },
                { title: 'ПВ, %', dataIndex: `operations.${operation}.usefulTime`, key: `operations.${operation}.usefulTime`, width: 50, },
                ],
            }));

            // Формируем столбцы таблицы
            this.columns = [
                { title: 'ФИО сотрудника', dataIndex: 'user_name', key: 'user_name', width: 200, },
                { title: 'Всего валов', dataIndex: 'totalShaft', key: 'totalShaft',width: 100, },
                ...operationColumns,
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
    padding: 16px;
    gap: 15px;
    width: 100%;
    background: #FFFFFF;
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
  