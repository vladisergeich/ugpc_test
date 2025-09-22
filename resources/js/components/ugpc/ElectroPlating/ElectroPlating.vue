<template>
  <div class="wrapper">
    <h2>Гальваника</h2>
    <template v-if="!showCard">
      <div class="cards_electroplating_wrapper">
        <div class="row">
          <div v-for="uniqueMachine in uniqueMachines" :key="uniqueMachine.machine" class="col-lg-3" style="margin-top: 20px;"> 
            <div @click="showElectroPlatingCard(uniqueMachine)">
              <electroplating-card
                :electroplating_card_title="uniqueMachine.description"
                :electroplating_card_shaft="getShaftId(uniqueMachine)"
                :electroplating_card_qty_operations="getQtyOperations(uniqueMachine)"
              ></electroplating-card>
            </div>
          </div>
        </div>
      </div>
      <div class="electroplating_general_operations-block">
        <div class="electroplating_navbar_table">
          <span class="electroplating_general_operations-title">Общие операции</span>
          <a-button class="electroplating_additional_operation_btn" @click="showModalAdditionalOperation()">Добавить доп. операцию</a-button>
          <a-modal
            title="Добавить доп. операцию"
            :visible="visibleModalAdditionalOperation"
            @cancel="handleCancelAdditionalOperation"
          >
            <template slot="footer">
              <a-button key="submit" type="primary" @click="startSecondaryOperation(selectedSecondaryOperation)">
                Начать
              </a-button>
            </template>
            <a-select style="width: 100%" v-model="selectedSecondaryOperation">
              <a-select-option v-for="operation in secondary_operations" :key="operation">
                {{ operation }}
              </a-select-option>
            </a-select>
          </a-modal>
        </div>
        <div class="table_wrapper_electroplating">
          <a-table style="width: 100%;" :columns="columns_general_operations" :data-source="selectGeneralOperations" size="small" bordered>
          </a-table>
        </div>
      </div>
    </template>
    <template v-if="showCard">
      <div @click="showCard = !showCard" style="display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
        <a-icon class="icon_back" type="arrow-left" /><span class="button_back">Назад</span>
      </div>
      <electroplating-bath-card :machine_operations="selectMachineOperations" :machine="selectMachine"></electroplating-bath-card>
    </template>
  </div>
</template>

<script>
import ElectroplatingBathCard from "./ElectroPlatingBathCard.vue";
import ElectroplatingCard from "./ElectroPlatingCard.vue";

export default {
  components: {
    ElectroplatingBathCard,
    ElectroplatingCard,
  },
  props: {
    operations: Array,
    secondary_operations: Array,
    shafts_for_electroplating: Array,
    shafts_on_electroplating: Array,
    machines: Array,
    urlstartsecondaryoperation: String,
  },
  data() {
    return {
      selectGeneralOperations: [],
      selectMachineOperations: [],
      selectMachine: "",
      selectedSecondaryOperation: "",
      visibleModalAdditionalOperation: false,
      columns_general_operations: [
        {
          title: "Операция",
          dataIndex: "description",
          key: "description",
        },
        {
          title: "Оператор",
          dataIndex: "operator",
          key: "operator",
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
      showCard: false,
      filterGeneralOperArray: ["Обед", "Ремонт", "Собрание", "Отключение электричества", "Отключение воздуха", "Прием смены"],
    };
  },
  computed: {
    uniqueMachines() {
      // Функция для получения уникальных машин на основе поля machine
      const uniqueMachineIds = Array.from(new Set(this.machines.map((machine) => machine.machine)));
      return uniqueMachineIds.map((machineId) => {
        // Для каждого id выбираем первую машину с этим id
        return this.machines.find((machine) => machine.machine === machineId);
      });
    },
  },
  created() {
    this.filtersGeneralOperation();
  },
  methods: {
    handleCancelAdditionalOperation() {
      this.visibleModalAdditionalOperation = false;
    },
    showModalAdditionalOperation() {
      this.visibleModalAdditionalOperation = true;
    },
    showElectroPlatingCard(machine) {
        this.selectMachine = machine.description;

        const operationsForMachine = this.machines
            .filter((mach) => mach.machine === machine.machine)
            .map((mach) => mach.operation);

        const filteredShaftsOnMachine = this.shafts_on_electroplating.filter((shaft) => shaft.machine === machine.machine);

        const filteredShaftsForMachine = this.shafts_for_electroplating.filter(
            (shaft) => operationsForMachine.includes(shaft.description)
        );

        const combinedArray = [...filteredShaftsOnMachine, ...filteredShaftsForMachine];

        this.selectMachineOperations = combinedArray;

        this.showCard = true;
    },
    getShaftId(machine) {

      const filteredShafts = this.shafts_on_electroplating.filter((shaft) => shaft.machine === machine.machine && shaft.status == 'в работе');
      return filteredShafts.length > 0 ? filteredShafts[0].shaft_id : "-";
    },
    getQtyOperations(machine) {

      const filteredShafts = this.shafts_on_electroplating.filter((shaft) => shaft.machine === machine.machine && shaft.status == 'Завершен');

      const total = filteredShafts.reduce((acc, item) => {
          return acc + 1; 
      }, 0);

      return total;
    },
    filtersGeneralOperation() {
      this.selectGeneralOperations = this.operations
        .filter((item) => this.filterGeneralOperArray.includes(item.description))
        .sort((a, b) => Date.parse(a.starting_time) - Date.parse(b.starting_time));
    },
    startSecondaryOperation() {
      axios
        .post(this.urlstartsecondaryoperation, {
          description: this.selectedSecondaryOperation,
        })
        .then((response) => {
          this.selectGeneralOperations = response.data;
          this.visibleModalAdditionalOperation = false
        })
        .catch((error) => {
          alert("Отсутствует соединение");
        });
    },
  },
};
</script>

<style scoped>
.electroplating_navbar_table {
  display: flex;
  justify-content: space-between;
}
.electroplating_additional_operation_btn {
  display: flex;
  padding: 8px 16px;
  justify-content: flex-end;
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
