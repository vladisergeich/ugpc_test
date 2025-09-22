<template>
  <div id="app">
    <div style="display: flex; flex-direction: column">
        <h2>Статистика УГПЦ</h2>
        <div class="statistic-container">
            <div class="filters">
                <FiltersComponent :users="data?.users" @filtersApplied="fetchStatistics" @filter-change="applyFilters" />
            </div>
            <div class="tabs">
                <a-tabs default-active-key="1">
                    <a-tab-pane key="1" tab="Общая статистика">
                        <GeneralStatisticTab v-if="data" :data="filteredData" :plan="data.plan"/>
                    </a-tab-pane>
                    <a-tab-pane key="2" tab="Свод по МЦ и операторам">
                        <StatisticByMcTab v-if="data" :data="filteredData" :plan="data.plan" :workCenters="workCenters"/>
                    </a-tab-pane>
                    <a-tab-pane key="3" tab="Динамика по кол-ву операций">
                        <OperationDimanicsTab v-if="data" :data="filteredData"/>
                    </a-tab-pane>
                    <a-tab-pane key="4" tab="Брак">
                        <DefectTab v-if="data" :data="filteredData" :workCenters="workCenters"/>
                    </a-tab-pane>
                    <a-tab-pane key="5" tab="Медь">
                        <CopperTab v-if="data" :data="filteredData"/>
                    </a-tab-pane>
                    <a-tab-pane key="6" tab="Резцы">
                        <CutterTab v-if="data" :data="filteredData"/>
                    </a-tab-pane>
                </a-tabs>
            </div>
        </div>
    </div>
  </div>
</template>
  
  <script>
  import { mapGetters } from 'vuex';
  import FiltersComponent from './components/FiltersComponent.vue';
  import GeneralStatisticTab from './tabs/GeneralStatisticTab/GeneralStatisticTab.vue';
  import OperationDimanicsTab from './tabs/OperationDinamicsTab/OperationDimanicsTab.vue';
  import StatisticByMcTab from './tabs/StatisticByMcTab/StatisticByMcTab.vue';
  import DefectTab from './tabs/DefectTab/DefectTab.vue';
  import CopperTab from './tabs/CopperTab/CopperTab.vue';
  import CutterTab from './tabs/CutterTab/CutterTab.vue';

  export default {
    components: {
        FiltersComponent,
        GeneralStatisticTab,
        StatisticByMcTab,
        OperationDimanicsTab,
        DefectTab,
        CopperTab,
        CutterTab
    },

    data() {
      return {
        data: null,
        workCenters: null,
        filters: {
          letters: ['А','Б','В','Г'],
        },
      };
    },
    computed: {
      currentTabComponent() {
          return this.tabs.find(tab => tab.name === this.currentTab).component;
      },

      filteredData() {
        return this.data.data.filter(operation => {

          const matchesLetters = this.filters.letters.length === 0 || this.filters.letters.includes(operation.letter);
          const matchesOperator = !this.filters.user || this.filters.user === operation.user_id;

          return matchesLetters && matchesOperator;
        });
      }
    },

    created() {
        this.fetchMachines();
    },

    methods: {
      async fetchStatistics(filters) {
        try {
          const response = await axios.get(route('ugpc.statistic.getData'), {
            params: {
              filters: filters,
            }
          });
          this.data = response.data; 
        } catch (error) {
          console.error('Ошибка при загрузке статистики:', error);
        }
      },

      async fetchMachines() {
        try {
          const response = await axios.get(route('ugpc.statistic.getWorkCenters'));
          this.workCenters = response.data.workCenters; 
        } catch (error) {
          console.error('Ошибка при загрузке статистики:', error);
        }
      },

      async applyFilters(filters) {
          this.filters = filters.filters;
      },
    }
  };
  </script>
  
  <style scoped>
  body {
    background: #f4f7fa;
  }
  .statistic-container {
    display: flex;
    gap: 20px;
  }

  .filters {
    display: flex;
    flex-direction: column;
    padding: 20px;
    border-radius: 10px;
    background: #FFFFFF;
    box-shadow: 0px 4px 20px 0px #BDBDBD40;
    width: 20%;
    height: max-content;
  }

  .tabs {
    width: 80%;
  }
 
  </style>
  