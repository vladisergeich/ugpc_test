<template>
    <div class="general-statistics">
      <div class="statistic-row">
        <GeneralStatisticPlanComponent class="plan_card blue" :title="'Статистика месячного плана'" :data="data.filter(machine => machine.machine_id != 16).filter(op => op.type == 'main')" :plan="plan.filter(value => value.start_date != value.end_date).reduce((sum, item) => sum + item.plan_qty, 0)" :fact="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main').reduce((sum, item) => sum + 1, 0)"/>
        <GeneralStatisticPlanComponent class="plan_card white" :title="'Статистика суточного плана'" :data="data.filter(machine => machine.machine_id != 16).filter(op => op.type == 'main')" :plan="plan.filter(value => value.start_date == value.end_date).reduce((sum, item) => sum + item.plan_qty, 0)" :fact="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main').reduce((sum, item) => sum + 1, 0)"/>
      </div>

      <div class="statistic-row">
          <GeneralStatisticBrakComponent v-if="data" :title="'Брак'" :data="data.filter(machine => machine.operation_id == 68)" :fact="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main').reduce((sum, item) => sum + 1, 0)"/>
          <GeneralStatisticPaceDayComponent v-if="data"  :title="'Темп по выпуску готовых ПЦ в сутки'" :data="data.filter(machine => machine.machine_id != 16).filter(op => op.type == 'main')" :plan="plan.filter(value => value.start_date != value.end_date).reduce((sum, item) => sum + item.plan_qty, 0)" :fact="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main').reduce((sum, item) => sum + 1, 0)"/>
      </div>

      <div class="statistic-row">
        <GeneralStatisticMcFact :title="'CFM-1'" v-if="data" :data="data.filter(op => op.type == 'main').filter(machine => machine.machine_id == 14)"/>
        <GeneralStatisticMcFact :title="'CFM-2'" v-if="data" :data="data.filter(machine => machine.machine_id == 15).filter(op => op.type == 'main')"/>
        <GeneralStatisticMcFact :title="'Гальваника'" v-if="data" :data="data.filter(machine => machine.work_center_id == 1).filter(op => op.type == 'main')"/>
      </div>
  
      <div class="statistic-row">
        <GeneralStatisticMcFact :title="'K5-1'" v-if="data" :data="data.filter(machine => machine.machine_id == 11).filter(op => op.type == 'main')"/>
        <GeneralStatisticMcFact :title="'K5-2'" v-if="data" :data="data.filter(machine => machine.machine_id == 12).filter(op => op.type == 'main')"/>
        <GeneralStatisticMcFact :title="'Пробпечать'" v-if="data" :data="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main')"/>
      </div>
  
      <div class="statistic-row">
        <GeneralStatisticTablePlan :title="'План/Факт от месячного плана в динамике'" v-if="data" :plan="plan" :data="data.filter(machine => machine.machine_id == 13).filter(op => op.type == 'main')"/>
      </div>
    </div>
  </template>
  
  <script>
  import GeneralStatisticMcFact from './GeneralStatisticMcFact.vue';
  import GeneralStatisticPlanComponent from './GeneralStatisticPlanComponent.vue';
  import GeneralStatisticPaceDayComponent from './GeneralStatisticPaceDayComponent.vue';
  import GeneralStatisticBrakComponent from './GeneralStatisticBrakComponent.vue';
  import GeneralStatisticTablePlan from './GeneralStatisticTablePlan.vue';
  export default {
    components: {
      GeneralStatisticPlanComponent,
      GeneralStatisticMcFact,
      GeneralStatisticPaceDayComponent,
      GeneralStatisticBrakComponent,
      GeneralStatisticTablePlan
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
    },
    data() {
      return {

      };
    }
  };
  </script>
  
  <style scoped>
  .blue {
    background: linear-gradient(220.08deg, rgba(154, 200, 255, 0.7) -13.36%, rgba(86, 163, 255, 0.7) 40.31%, rgba(25, 130, 254, 0.7) 85.92%);
    color: #FFFFFF;
  }

  .white {
    background: #FFFFFF;
  }

  .plan_card {
    box-shadow: 0px 4px 20px 0px #BDBDBD40;
    padding: 20px;
    border-radius: 8px;
    flex: 2;
  }
  .general-statistics {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .statistic-row {
    display: flex;
    gap: 20px;
    margin-top: 15px;
  }
  
  .plan-fact-dynamics table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  
  .plan-fact-dynamics th, .plan-fact-dynamics td {
    padding: 10px;
    border: 1px solid #ddd;
  }
  
  .plan-fact-dynamics th {
    background-color: #f2f2f2;
  }
  </style>
  