<template>
        <div class="row">
            <a-modal title="Выберите машинный центр" :visible="visibleModalMachine">
                <template slot="footer">
                    <a-button key="submit" type="primary" @click="startWorkShiftCard">Сохранить</a-button>
                </template>
                <a-select style="width: 100%" v-model="selectMachine">
                    <a-select-option v-for="machine in machines" :key="machine">{{ machine }}</a-select-option>
                </a-select>
            </a-modal>
            <div v-if="selectMachine !== ''" class="col-lg-8">
                <div class="info-container">
                    <div class="info-container_header">
                        <div class="header_info_blok" v-for="info in headerInfo" :key="info.title">
                            <span class="info_title">{{ info.title }}:</span>
                            <span class="info_value">{{ info.value }}</span>
                        </div>
                        <a-button class="operation__second_btn" @click="showModalSecondaryOperation">Доп. операции</a-button>

                        <a-modal title="Доп. операции" :visible="visibleModalSecondaryOperation" @cancel="handleCancelSecondaryOperation">
                            <template slot="footer">
                            <a-button key="submit" type="primary" @click="startSecondaryOperation(selectedSecondaryOperation)">Начать</a-button>
                            </template>
                            <a-select style="width: 100%" v-model="selectedSecondaryOperation">
                            <a-select-option v-for="operation in selectSecondaryOperations" :key="operation">{{ operation }}</a-select-option>
                            </a-select>
                        </a-modal>
                    </div>
                    <div class="info-container_body">
                        <span class="operation_description_title"></span>
                        <span v-if="(selectedSecondaryOperation == null) && (selectConsumpShaft == null) && (shiftReceptionOperation == null)" class="no_operation_title">Выберите вал из списка или доп.операцию</span>

                        <template v-if="(selectedSecondaryOperation != null) && (selectConsumpShaft == null) && (shiftReceptionOperation == null)">
                            <h5 style="margin-top: 20px;">{{ selectedSecondaryOperation }}</h5>
                            <div class="row_shift_reception_btn">
                                <a-button class="operation_engraving_timing" @click="showModalTimingOperation">Выставить время вручную</a-button>
                                <a-button class="operation_engraver_btn_close" @click="closeOperation(selectedSecondaryOperation)">Завершить</a-button>

                                <a-modal title="Выставить время вручную" :visible="visibleModalTimingOperation" @cancel="handleModalTimingOperation">
                                    <template slot="footer">
                                    <a-button style="border-radius: 8px; background: var(--primary-100, #E5EEFF); border: none;" key="submit" @click="startSecondaryOperation(selectedSecondaryOperation)">Сохранить</a-button>
                                    </template>
                                    <h5>Операция: {{ selectedSecondaryOperation }}</h5>
                                    <span class="secondary_qty_title">Количество</span>
                                    <a-input v-model="selectQtySecondayOperation" class="secondary_qty_value" placeholder="Введите кол-во" />
                                    <h5 style="margin-top: 20px;">Когда была операция:</h5>
                                    <span @click="changeBackGroundBtnTiming(slot)" :class="{ 'timing_btn_click': isClickedTimingBtnIndex === slot }" class="timing_btn" v-for="slot in selectFreeTimeSlots" :key="slot">{{ slot.start_time }}-{{ slot.end_time }}</span>
                                </a-modal>
                            </div>
                        </template>
                        <template v-if="this.shiftReceptionOperation != null">
                            <h5 class="shift_reception_title">Прием смены</h5>
                            <h6 class="shift_handover_title">Сдал смену:</h6> <h6 class="shift_handover_value">{{this.shiftHandoverOperation.operator}}</h6>
                            <h6 v-if="this.shiftRolloverShaft != null" class="shift_handover_title">Переходящий вал:</h6> <h6 v-if="this.shiftRolloverShaft != null" class="shift_handover_value">{{this.shiftRolloverShaft.shaft_id}}</h6>
                            <div class="row_shift_reception_btn">
                                <a-button class="operation_engraver_btn_close" @click="closeOperation(shiftReceptionOperation.description)">Завершить</a-button>
                            </div>
                        </template>
                        <template v-if="((selectConsumpShaft!=null) && (this.shiftReceptionOperation == null))">
                            <ShaftInfoContainer :selectConsumpShaft="selectConsumpShaft" :brakShaft="brakShaft"/>
                        </template>
                        <template v-if="(selectConsumpShaft!=null) && (this.shiftReceptionOperation == null)">
                            <EngravingOperationInfoContainer :selectConsumpShaft="selectConsumpShaft" :selectMainOperation="selectMainOperation" :selectedMainOperation="selectedMainOperation" :deleteConsumpShaft="deleteConsumpShaft" :closeOperation="closeOperation" :startOperation="startOperation" :deleteOperation="deleteOperation"/>
                        </template>
                    </div>
                </div>
                <OperationContainer :operations="selectOperations" :columns_operations="columns_operations"/>
            </div> 
            <div v-if="this.selectMachine != ''" class="col-lg-4">
                <WarehouseShaftsContainer :shiftReceptionOperation="shiftReceptionOperation" :consumpShaft="consumpShaft" :shafts="selectShaftForEngraving"/>
            </div>
        </div>
</template>

<script>
import OperationContainer from "../Components/OperationContainer.vue";
import WarehouseShaftsContainer from "../Components/WarehouseShaftsContainer.vue";
import ShaftInfoContainer from "../Components/ShaftInfoContainer.vue";
import EngravingOperationInfoContainer from "../Components/EngravingOperationInfoContainer.vue";

export default {
        components: {
            OperationContainer,
            WarehouseShaftsContainer,
            ShaftInfoContainer,
            EngravingOperationInfoContainer,
        },

        props: {
            secondary_operations: Array,
            operation_engraving: Array,
            urlstartsecondaryoperation: String,
            work_shift_code: Object,
            operator: Object,
            urlgetshaftconsump: String,
            urlstartoperation: String,
            urlgetinfomachine: String,
            urldeleteoperation: String,
            urlcloseoperation: String,
            urlbrakshaft: String,
            urldeleteshaftconsump: String,
        }, 

        created() {
            window.Echo.private('shaft-selected').listen('LotConsumpEngravingMachine', (e) => {
                this.selectConsumpShaft = e;
            });
        },

        mounted () {
            
        },
        data() {
            return {
                columns_operations: [
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description",
                    },
                    {
                    title: "№ Вала",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    },
                    {
                    title: "№ Оквид",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    },
                    {
                    title: "№ гравировальной головки",
                    dataIndex: "",
                    key: "",
                    },
                    {
                    title: "№ резца",
                    dataIndex: "",
                    key: "",
                    },
                    {
                    title: "Время начала",
                    dataIndex: "starting_time",
                    key: "starting_time",
                    },
                    {
                    title: "Время окончания",
                    dataIndex: "ending_time",
                    key: "ending_time",
                    },
                ],
                machines: ['K5-1', 'K5-2'],
                visibleModalSecondaryOperation: false,
                visibleInfoShaft: false,
                selectedSecondaryOperation: null,
                selectedMainOperation: null,
                selectOperations: [],
                selectShaftForEngraving: [],
                selectSecondaryOperations: [...this.secondary_operations],
                selectMainOperation: "",
                selectMachine: '',
                selectOperator: { ...this.operator },
                selectWorkShiftCode: { ...this.work_shift_code },
                visibleModalMachine: true,
                visibleModalTimingOperation: false,
                selectConsumpShaft: {},
                noteBrakShaft: '',
                shiftReceptionOperation: null,
                shiftHandoverOperation: null,
                shiftRolloverShaft: null,
                selectQtySecondayOperation: 0,
                selectFreeTimeSlots: [],
                isClickedTimingBtnIndex: null,
            }   
                
        },
        computed: {
            headerInfo() {
                return [
                    { title: 'МЦ', value: this.selectMachine },
                    { title: 'Оператор', value: this.selectOperator.employer_name_1c },
                    { title: 'Смена', value: this.selectWorkShiftCode.letter },
                    { title: 'Время', value: `${this.selectWorkShiftCode.starting_time}-${this.selectWorkShiftCode.ending_time}` },
                    { title: 'Дата', value: this.selectWorkShiftCode.date },
                ];
            },
        },

        methods: {
            changeBackGroundBtnTiming(slot) {
                this.isClickedTimingBtnIndex = slot;
            },
            
            showModalTimingOperation() {
                this.visibleModalTimingOperation = true;
            },

            handleModalTimingOperation() {
                this.visibleModalTimingOperation = false;
            },

            showModalSecondaryOperation() {
                this.visibleModalSecondaryOperation = true;
            },

            handleCancelSecondaryOperation() {
                this.visibleModalSecondaryOperation = false;
            },

            deleteOperation() {
                axios.post(this.urldeleteoperation,{
                    operation: this.selectedMainOperation,
                    machine: this.selectMachine,
                    shaft: this.selectConsumpShaft,
                })
                .then(response => {
                    this.selectOperations = response.data[0];
                    this.selectedMainOperation = response.data[1];
                })
                .catch(error => {
                    alert("Ошибка! Обратитесь поддержку");
                });
            },

            startWorkShiftCard(){
                if (this.selectMachine != '') {
                    axios.post(this.urlgetinfomachine,{
                        machine: this.selectMachine,
                    })
                    .then(response => {
                        this.selectShaftForEngraving = response.data[0];
                        this.selectOperations = response.data[1];
                        this.selectConsumpShaft = response.data[2];
                        this.selectedMainOperation = response.data[3];
                        this.selectedSecondaryOperation = response.data[4];
                        this.selectMainOperation = response.data[5];

                        this.visibleModalMachine = false;
                    })
                    .catch(error => {
                        alert("Ошибка! Обратитесь поддержку");
                    });
                } else {
                    alert('Выберите машинный центр!');
                }
            },

            startSecondaryOperation() {
                if (this.selectConsumpShaft !== null) {
                    alert('Вначале завершите работу с валом!');
                    return;
                }

                this.postOperation(this.urlstartsecondaryoperation, this.selectedSecondaryOperation, this.isClickedTimingBtnIndex, () => {
                    this.visibleInfoShaft = false;
                    this.selectConsumpShaft = null;
                    this.isClickedTimingBtnIndex = null;
                    this.visibleModalTimingOperation = false;
                    this.handleCancelSecondaryOperation();
                });
            },

            startOperation() {

                this.postOperation(this.urlstartoperation, 'Гравирование', null, () => {
                    this.selectedMainOperation = 'Гравирование';
                });
            },

            postOperation(url, description, timingIndex, callback) {
                axios.post(url, {
                    description: description,
                    machine: this.selectMachine,
                    shaft: this.selectConsumpShaft,
                    timing_index: timingIndex,
                })
                .then(response => {
                    this.selectOperations = response.data;
                    if (callback) {
                        callback();
                    }
                })
                .catch(error => {
                    alert("Ошибка! Обратитесь поддержку");
                });
            },

            closeOperation(operation) {
                let inputsFilled = false;

                if (this.selectedMainOperation != null){
                    inputsFilled = [this.selectConsumpShaft.engraving_head_number, this.selectConsumpShaft.engraving_cutter_number].every(input => !!input);
                    this.selectConsumpShaft.engraving = true;
                } else {
                    inputsFilled = true;
                }

                if (this.selectConsumpShaft == null && this.selectedSecondaryOperation == null) {
                    alert('Выберите вал');
                    return;
                }

                if (!inputsFilled) {
                    alert('Заполните параметры');
                 
                }else {

                    const confirmOptions = {
                        title: 'Завершить вал?',
                        content: 'Вы больше не сможете добавить операции для данного вала, завершить работу с валом?',
                        okText: 'Да',
                        cancelText: 'Нет',
                        onOk: () => {
                            this.handlePostRequest(operation);
                        },
                        onCancel() {
                            
                        },
                    };

                    if (this.shouldConfirmSecondaryOperation()) {
                        this.$confirm(confirmOptions);
                    } else {
                        this.handlePostRequest(operation);
                    }
                }
            },

            shouldConfirmSecondaryOperation() {
                const secondaryOperations = [
                    'Замена алмазного резца',
                    'Замена торцевого резца',
                    'Замена каскадных фильтров',
                    'Замена фильтровальных патронов',
                    'Замена камня',
                    'Замена полировальной ленты'
                ];

                return secondaryOperations.includes(this.selectedSecondaryOperation) && this.selectQtySecondayOperation !== 0;
            },

            handlePostRequest(operation) {
                this.$confirm({
                    title: 'Завершить операцию?',
                    okText: 'Да',
                    cancelText: 'Нет',
                    onOk: () => {
                        axios.post(this.urlcloseoperation, {
                                description: operation,
                                machine: this.selectMachine,
                                shaft: this.selectConsumpShaft,
                                qty: this.selectQtySecondayOperation,
                            })
                            .then(response => {
                                this.selectOperations = response.data[0];
                                this.selectedMainOperation = response.data[1];
                                this.selectConsumpShaft = response.data[2];
                                this.selectedSecondaryOperation = response.data[3];
                                this.selectShaftForEngraving = response.data[4];
                                this.selectQtySecondayOperation = 0;
                            })
                            .catch(error => {
                                
                            });
                    },
                    onCancel() {

                    },
                });
            },

            consumpShaft(shaft) {
                if (this.selectConsumpShaft != null) {
                    alert('Машина'+' '+this.selectMachine+' '+'в данный момент занята валом'+' '+this.selectConsumpShaft.shaft_id);
                } else {
                    axios.post(this.urlgetshaftconsump,{
                        shaft: shaft,
                        machine: this.selectMachine,
                    })
                    .then(response => {
                        this.visibleInfoShaft = true;
                        this.selectConsumpShaft = response.data[0];
                        this.selectMainOperation = response.data[1];
                        this.selectShaftForEngraving = response.data[2];
                    })
                    .catch(error => {
                        alert("Ошибка! Обратитесь поддержку");
                    });
                }
            },

            deleteConsumpShaft(shaft) {
                axios.post(this.urldeleteshaftconsump,{
                    shaft: shaft,
                    machine: this.selectMachine,
                })
                .then(response => {
                    this.visibleInfoShaft = false;
                    this.selectConsumpShaft = response.data[0];
                    this.selectMainOperation = response.data[1];
                    this.selectShaftForEngraving = response.data[2];
                })
                .catch(error => {
                    alert("Ошибка! Обратитесь поддержку");
                });
            },

            brakShaft(noteBrakShaft) {
                axios.post(this.urlbrakshaft,{
                    shaft: this.selectConsumpShaft,
                    note: noteBrakShaft,
                    machine: this.selectMachine,
                })
                .then(response => {
                    this.visibleInfoShaft = false;
                    this.selectConsumpShaft = response.data[0];
                    this.selectedMainOperation = response.data[1];
                    this.selectShaftForEngraving = response.data[2];
                })
                .catch(error => {
                    alert("Ошибка! Обратитесь поддержку");
                });
            },
        },

        
    } 
    
    
</script>

<style scoped>
.operation_engraver_btn_close {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-left: 20px;
}

.info_title {
    font-size: 16px;
}

.info_value {
    font-size: 16px;
}

.timing_btn {
    display: inline-block; 
    cursor: pointer; 
    margin-top: 10px; 
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    border-radius: 10px; 
    background: var(--primary-0, #F3F9FF); 
    margin-right: 10px;padding: 8px;
    gap: 10px;
}

.timing_btn_click {
    background: var(--primary-300-activ, #4A9DFF); 
}

.operation_engraving_timing {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    border: 0;
    box-shadow: none;

}

.secondary_qty_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: flex;
}

.secondary_qty_value {
    border-radius: 8px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    background: var(--grayscale-0, #FFF);
    height: 40px;
    width: 200px;
    margin-top: 10px;
    display: flex;

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

.base_engraving_make_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.base_engraving_make_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.engraving_edition_make_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.engraving_edition_make_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.base_engraving_make {
    display: flex;
    margin-right: 20px;
}
.engraving_edition_make {
    display: flex;
    margin-right: 20px;
}

.row_shift_reception_btn {
    display: flex;
    justify-content: flex-end;
}
.shift_reception_title {
    margin-top: 20px;
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.shift_handover_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: inline-block;
}

.shift_handover_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: inline-block;
}

.info-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
    margin-bottom: 30px;
}


.operation__second_btn {
    border-radius: 8px;
    background: var(--primary-100, #E5EEFF);
    box-shadow: 0px 4px 4px 0px rgba(214, 214, 214, 0.25);
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    right: 20px;
    position: absolute;
}

.info-container_header {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #E8E8E8;
    padding: 0 0 20px 0;
}

.no_operation_title {
    color: var(--grayscale-500, #BFBFBF);
    font-size: 20px;
    font-family: Open Sans;
    letter-spacing: -0.2px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-shaft_wrapper {
    padding: 20px 0 0 0;
}

.block_note_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    margin-top: 10px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.input_value {
    display: flex;
    padding: 0px 10px;
    align-items: center;
    gap: 6px;
    flex: 1 0 0;
    align-self: stretch;
    display: flex;
    padding: 0px 10px;
    align-items: center;
    gap: 6px;
    flex: 1 0 0;
    align-self: stretch;
}

.plan_title {
    color: var(--grayscale-600, #9A9A9A);
    font-size: 16px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.plan_value {
    color: var(--txt, #363F51);
    font-size: 16px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.plan_diameter_block {
    display: inline-block;
    margin-right: 30px;
}

.plan_edge_block {
    display: inline-block;
}

.ant-radio-wrapper {
    color: var(--txt, #363F51) !important;
    font-size: 16px !important;
    font-family: Open Sans !important;
    letter-spacing: 0.16px !important;
    margin-bottom: 15px !important;
}

.ant-modal-title {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 24px;
    font-family: Open Sans;
    font-weight: 600;
}

.ant-radio-input {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 20px;
}

.engraving_edition_block {
    margin-right: 20px;
}


.base_engraving_block {
    margin-right: 20px;
}

.shaft_info_row_engraving_edition {
    display: flex;
}

.shaft_info_row_base_engraving {
    display: flex;
}
</style>
