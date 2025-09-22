<template>
    <div class="wrapper">
        <div class="row-btn">
            <a-button type="primary" @click="showModal = true" v-if="alteration">Переделка</a-button>
            <a-button type="primary" style="margin-left: 20px;"  v-if="alteration" @click="skipDefect">Пропустить</a-button>
            <a-button type="primary" style="margin-left: 20px;"  v-if="alteration" @click="confirmReplaceShaft()">Заменить вал</a-button>
        </div>
        <a-modal
            title="Переделка"
            :visible="showModal"
            @cancel="showModal = false"
            >
            <template slot="footer">
                <a-button key="delete_operation" type="primary" @click="alterationStage()">
                    Отправить на переделку
                </a-button>
            </template>
            <div class="checkbox_wrapper">
                <a-checkbox style="margin-top: 5px;" @change="stage.open = !stage.open" v-for="stage in alterationStages" :key="stage.id">
                    {{ stage.description }}
                </a-checkbox>
            </div>
        </a-modal>
        <a-table
            :columns="columnsOrderStage"
            :data-source="orderStage.engraving_stages"
            :rowKey="record => record.uid"
            :rowClassName="getRowClassName"
            :pagination="false"
            >

            <div slot="stage.description" slot-scope="text, record, index">
                <span style="display: flex; align-items: center; height: 100%;">{{ text }}</span>
            </div>
            <div slot="plan_params" slot-scope="text, record, index">
                <div v-for="parameter in text">
                    <span class="param_title">{{ parameter.parameter.title }}:</span>
                    <span>{{ parameter.float_value }}</span>
                    <span>{{ parameter.string_value }}</span>
                </div>
            </div>
            <div slot="operation.fact_parameters" slot-scope="text, record, index">
                <div v-for="parameter in text">
                    <span class="param_title">{{ parameter.parameter.title }}:</span>
                    <span>{{ parameter.float_value }}</span>
                    <span>{{ parameter.string_value }}</span>
                </div>
            </div>
        </a-table>
    </div>
</template>

<script>
export default {

    components: {

    },
    props: {
        orderStage: Object,
        alteration: Boolean,
    },
    data() {
        return {
            showModal: false,
            alterationStages: [
                {'id': 1, 'description': 'Дехромирование'},
                {'id': 3, 'description': 'Шлифовка базы'},
                {'id': 2, 'description': 'Обезжиривание стали'},
                {'id': 5, 'description': 'Предварительное меднение'},
                {'id': 6, 'description': 'Меднение базы'},
                {'id': 4, 'description': 'Обезжиривание базы'},
                {'id': 12, 'description': 'Меднение тираж'},
                {'id': 7, 'description': 'Шлифовка тираж'},
                {'id': 9, 'description': 'Гравирование'},
                {'id': 8, 'description': 'Обезжиривание тираж'},
                {'id': 10, 'description': 'Хромирование'},
                {'id': 11, 'description': 'Полировка хром'},
                {'id': 13, 'description': 'Пробная печать'},
            ],
            columnsOrderStage: [
                {
                title: "Операция",
                dataIndex: "stage.description",
                key: "stage.description",
                },
                {
                title: "План",
                dataIndex: "plan_params",
                key: "plan_params",
                scopedSlots: { customRender: 'plan_params' }, 
                },
                {
                title: "Факт",
                dataIndex: "operation.fact_parameters",
                key: "operation.fact_parameters",
                scopedSlots: { customRender: 'operation.fact_parameters' }, 
                },
                {
                title: "Машина",
                dataIndex: "operation.machine.machine",
                key: "operation.machine.machine",
                scopedSlots: { customRender: 'operation.machine.machine' }, 
                },
                {
                title: "Оператор",
                dataIndex: "operation.user.employer_name_1c",
                key: "operation.user.employer_name_1c",
                scopedSlots: { customRender: 'operation.user.employer_name_1c' }, 
                },
                {
                title: "Смена",
                dataIndex: "operation.work_shift.letter",
                key: "operation.work_shift.letter",
                scopedSlots: { customRender: 'operation.work_shift.letter' }, 
                },
                {
                title: "Дата",
                dataIndex: "operation.posting_date",
                key: "operation.posting_date",
                scopedSlots: { customRender: 'operation.posting_date' }, 
                },
            ],
        };
    },

    methods: {
        async alterationStage() {
            await this.$store.dispatch('alterationStage',{orderStage: this.orderStage,alterationStages: this.alterationStages});
            this.showModal = false;
        },

        async skipDefect() {
            await this.$store.dispatch('skipDefect',{orderStage: this.orderStage});
        },

        async replaceShaft() {
            await this.$store.dispatch('replaceShaft',{orderStage: this.orderStage});
        },

        async confirmReplaceShaft() {
            const confirmOptions = {
                title: 'Заменить вал?',
                content: 'Данный вал будет завершен?',
                okText: 'Да',
                cancelText: 'Нет',
                onOk: () => {
                    this.replaceShaft();
                },
                onCancel() {
                    
                },
            };

            this.$confirm(confirmOptions);   
        },

        getRowClassName(record, index) {
            if (record.defect) {
                return 'defect-row'; 
            } else if (record.post) {
                return 'post-row'; 
            }
        },
    },

};
</script>

<style scoped>
.checkbox_wrapper {
    display: flex;
    flex-direction: column;
}
.wrapper {
    background: white;
}

.param_title {
    font-weight: 400;
    color: #9A9A9A;
    margin-right: 15px;
}

.defect-row {
    background: #FFEBEB !important;
}

.post-row {
    background: #7dfa39 !important;
}

.row-btn {
    display: flex;
    margin-bottom: 15px;
}
</style>