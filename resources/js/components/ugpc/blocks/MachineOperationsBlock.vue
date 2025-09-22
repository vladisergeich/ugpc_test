<template>
    <div class="operation-container">
        <div class="operation_info_title">                  
            <div class="operation_info_shaft">
                <div class="shafts_made">
                    <span class="shafts_made_title">Сделано валов:</span>
                    <span class="shafts_made_value">{{this.shaftsMadeWorkShift}}</span>
                </div>
            </div>
            <div class="operation_info_shaft">
                <div class="shafts_made" v-if="this.machine.cascade">
                    <span class="shafts_made_title">Каскад:</span>
                    <span class="shafts_made_value">{{this.machine.cascade.variable_body}}</span>
                </div>
                <div class="shafts_made" v-if="this.machine.main_cutter">
                    <span class="shafts_made_title">Основной резец:</span>
                    <span class="shafts_made_value">{{this.machine.main_cutter.variable_body}}</span>
                </div>
                <div class="shafts_made" v-if="this.machine.end_cutter">
                    <span class="shafts_made_title">Торцевой резец:</span>
                    <span class="shafts_made_value">{{this.machine.end_cutter.variable_body}}</span>
                </div>
            </div>
        </div>
        <a-table
        :columns="dynamicColumns"
        :data-source="Operations"
        :rowClassName="(record, index) => record.operation.type != 'main' ? 'secondary_operation' :  'main_operation'"
        >
        </a-table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

    props: {
        workCenter: String,
    },
    data() {
        return {
            columns_operations: [
                {
                title: "Операция",
                dataIndex: "operation.description",
                key: "operation.description",
                },
                {
                title: "Вал",
                dataIndex: "engraving_order.shaft.shaft_id",
                key: "engraving_order.shaft.shaft_id",
                },
                {
                title: "Оквид",
                dataIndex: "engraving_order.design_order.okvid_number",
                key: "engraving_order.design_order.okvid_number",
                },
                {
                title: "Время начала",
                dataIndex: "start_time",
                key: "start_time",
                },
                {
                title: "Время окончания",
                dataIndex: "end_time",
                key: "end_time",
                },
            ],
        };
    },

    created() {
        this.getOperationsMachine();
    },

    methods: {
        async getOperationsMachine() {
            await this.$store.dispatch('getOperationsMachine');
        }
    },

    computed: {
        ...mapGetters({
            Operations: 'getOperationsMachine',
            machine: 'getMachine',
        }),

        shaftsMadeWorkShift() {
            const uniqueShafts = new Set();

            this.Operations.forEach(shaft => {
                if (shaft.engraving_order !== null) {
                    uniqueShafts.add(shaft.engraving_order);
                }
            });

            return uniqueShafts.size;
        },
 
        dynamicColumns() {
            let dynamicColumns = [...this.columns_operations]; 

            if (this.workCenter == "CFM") {
                dynamicColumns.push({
                    title: "Диаметр",
                    dataIndex: "fact_diameter.float_value",
                    key: "fact_diameter.float_value",
                });

                dynamicColumns.push({
                    title: "Разница D",
                    dataIndex: "diameter_difference.float_value",
                    key: "diameter_difference.float_value",
                });

            } else if (this.workCenter == "K5") {
                dynamicColumns.push({
                    title: "Номер головы",
                    dataIndex: "head_number.float_value",
                    key: "head_number.float_value",
                });
            }

            return dynamicColumns;
        }
    }
};
</script>

<style scoped>
.operation-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.operation_info_title {
    display: flex;
    justify-content: space-between;
}

.operation_info_shaft {
    display: flex;
    margin-bottom: 15px;
}

.shafts_made_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.shafts_made_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.shafts_made {
    display: flex;
    margin-right: 20px;
}

.format_difference_title {
    cursor: pointer;
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.secondary_operation {
    border-bottom: 1px solid var(--grayscale-400, #E8E8E8) !important;
    background: var(--additional-light-green, #EFFFE2) !important;
}
</style>