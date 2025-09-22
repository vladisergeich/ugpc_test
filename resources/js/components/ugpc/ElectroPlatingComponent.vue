<template>
    <div class="wrapper">
      <h2>Гальваника</h2>
      <template v-if="!showCard">
        <div class="cards_electroplating_wrapper">
          <div class="row">
            <div v-for="machine in machines" :key="machine.id" class="col-lg-3" style="margin-top: 20px;"> 
              <div @click="showElectroPlatingCard(machine)">
                <ElectroPlatingCardElement
                  :electroplating_card_title="machine.machine"
                  :electroplating_card_shaft="getShaftId(machine.id)"
                  :electroplating_card_qty_operations="getQtyOperations(machine.id)"
                >
                </ElectroPlatingCardElement>
              </div>
            </div>
          </div>
        </div>
        <div class="electroplating_general_operations-block">
            <div class="electroplating_navbar_table">
                <span class="electroplating_general_operations-title">Общие операции</span>
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
            </div>
            <div class="table_wrapper_electroplating">
                <a-table style="width: 100%;" :columns="columns_general_operations" :data-source="operations.filter(operation=>operation.engraving_order_id == null)" size="small" bordered>
                </a-table>
            </div>
        </div>
      </template>
      <template v-if="showCard">
        <div @click="showCard = !showCard" style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
          <a-icon class="icon_back" type="arrow-left" /><span class="button_back">Назад</span>
        </div>
        <ElectroPlatingBathComponent :urlpostsecondaryoperation="urlpostsecondaryoperation" :secondary_operation="secondary_operation" :machine_operations="selectMachineOperations" :machine="selectMachine"></ElectroPlatingBathComponent>
      </template>
    </div>
  </template>
  
  <script>
  import ElectroPlatingCardElement from "./elements/ElectroPlatingCardElement.vue";
  import ElectroPlatingBathComponent from "./ElectroPlatingBathComponent.vue";
  
  export default {
    components: {
        ElectroPlatingCardElement,
        ElectroPlatingBathComponent
    },
    props: {
      operations: Array,
      machines: Array,
      secondary_operation: Array,
      urlpostsecondaryoperation: String,
    },
    data() {
      return {
        startTime: null,
        endTime: null,
        selectOperation: null,
        visibleModalOperation: false,
        operations: [...this.operations],
        columns_general_operations: [
            {
                title: "Операция",
                dataIndex: "operation.description",
                key: "operation.description",
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
        showCard: false,
        selectMachine: null,
        selectMachineOperations: [],
      };
    },
    computed: {

    },
    created() {

    },
    methods: {
        startOperation() { 
            axios
                .post(this.urlpostsecondaryoperation,{operationId: this.selectOperation, startTime: this.startTime, endTime: this.endTime})
                .then(response => {
                    this.visibleModalOperation = false;
                    this.operations = response.data;
                });
        },

        getShaftId(machineId) {
            const filteredShafts = this.operations.filter((shaft) => shaft.machine_id == machineId && shaft.end_time == null);
            return filteredShafts.length > 0 ? filteredShafts[0].engraving_order.shaft.shaft_id : "-";
        },
        getQtyOperations(machineId) {
            const filteredShafts = this.operations.filter((shaft) => shaft.machine_id == machineId && shaft.end_time != null && shaft.engraving_order_id != null);

            const total = filteredShafts.reduce((acc, item) => {
                return acc + 1; 
            }, 0);

            return total;
        },

        showElectroPlatingCard(machine) {
            this.selectMachine = machine;

            this.selectMachineOperations = this.operations.filter((shaft) => shaft.machine_id == machine.id);

            this.showCard = true;
        },
    },
  };
  </script>
  
  <style scoped>
  .time_wrapper {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
  }

  .input_time {
    width: 45%;
  }
  .electroplating_navbar_table {
    display: flex;
    justify-content: space-between;
  }
  .electroplating_additional_operation_btn {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    color: #4a9dff;
    border: 1px solid var(--primary-300-activ, #4a9dff);
  }
  .electroplating_general_operations-block {
    margin-top: 50px;
  }
  .electroplating_general_operations-title {
    color: var(--txt, #363f51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: -0.2px;
  }
  .table_wrapper_electroplating {
    margin-top: 20px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    display: flex;
    width: 100%;
    padding: 20px;
    gap: 15px;
  }
  </style>
  