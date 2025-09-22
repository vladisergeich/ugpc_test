<template>
    <div class="electroplating_bath_card_wrapper">
        <span class="electroplating_bath_card_title">
            {{machine.description}}
        </span>
        <a-button class="electroplating_additional_operation_btn" @click="visibleModalOperation = true">Добавить доп. операцию</a-button>
        <a-modal
        title="Добавить доп. операцию"
        :visible="visibleModalOperation"
        @cancel="visibleModalOperation = false"
        >
            <template slot="footer">
                <a-button key="submit" type="primary" @click="startOperation(selectedSecondaryOperation)">
                Начать
                </a-button>
            </template>
            <div class="operation_wrapper">
                <span>Операция</span>
                <a-select style="width: 100%; margin-top: 10px;" v-model="selectOperation">
                    <a-select-option v-for="operation in secondary_operation" :key="operation.id">
                    {{ operation.description }}
                    </a-select-option>
                </a-select>
            </div>
            <div class="time_wrapper">
                <a-time-picker format="HH:mm:ss" v-model="startTime" class="input_time" />
                <a-time-picker format="HH:mm:ss" v-model="endTime" class="input_time"/>
            </div>
        </a-modal>
        <a-table
            :columns="columns_operations"
            :data-source="this.machine_operations"
            size="small"
            bordered
        >    
        <template  slot="status" slot-scope="text, record">     
                <span v-if="record.status=='Завершен'" class="status_completed">{{ text}}</span>
                <span v-if="record.status=='На рассмотрении'"  class="status_not_work">{{ text}}</span>        
                <span  v-if="record.status == ''" >В очереди</span>
        </template>        
        <template  slot="posting_date" slot-scope="text, record">                
                <span  v-if="record.posting_date" >{{text}}</span>
        </template>     
        </a-table>
    </div>
</template>

<script>
export default {
    props: {
        machine_operations: Array,
        machine: Object,
        secondary_operation: Array,
        urlpostsecondaryoperation: String,
    },

    data() {
        return {
            startTime: null,
            endTime: null,
            selectOperation: null,
            visibleModalOperation: false,
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
                title: "Оператор",
                dataIndex: "user.employer_name_1c",
                key: "user.employer_name_1c",
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
            {
                title: "Машина",
                dataIndex: "machine.machine",
                key: "machine.machine",
            },
        ],

        };
    },

    created() {

    },
    mounted() {
    
    },
    computed: {
    },
    methods: {
        startOperation() { 
            axios
                .post(this.urlpostsecondaryoperation,{operationId: this.selectOperation, startTime: this.startTime, endTime: this.endTime, machineId: this.machine.id})
                .then(response => {
                    this.visibleModalOperation = false;
                    this.machine_operations = response.data.filter((shaft) => shaft.machine_id == this.machine.id);;
                });
        },
    },
}
</script>

<style>
.electroplating_additional_operation_btn {
display: flex;
padding: 8px 16px;
justify-content: center;
align-items: center;
gap: 8px;
border-radius: 8px;
color: #4a9dff;
border: 1px solid var(--primary-300-activ, #4a9dff);
width: 15%;
}
.time_wrapper {
margin-top: 20px;
display: flex;
justify-content: space-between;
}

.input_time {
width: 45%;
}
.status_completed {
    background: #04BA0B;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_not_work {
    background: #E31212;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_queue {
    border-radius: 10px;
    background: var(--Grayscale-500, #BFBFBF);
    padding: 4px 15px;
    color: #FFFFFF;
}
.electroplating_bath_card_title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: -0.2px;
}
.electroplating_bath_card_wrapper {
    margin-top: 20px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    display: flex;
    padding: 20px;
    gap: 15px;
    flex-direction: column;
}

</style>
