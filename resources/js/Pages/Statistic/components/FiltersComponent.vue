<template>
    <div class="filters-container">
      <div class="filter-group">
        <date-picker
            type="date"
            range
            value-type="format"
            format="YYYY-MM-DD"
            ref="filters_datapicker"
            v-model="filterDates"
            @change="applyFilters"
        ></date-picker>
      </div>
  
      <div class="filter-group">
        <label>Смена</label>
        <a-checkbox-group
            :options="letters"
            v-model="filters.letters"
            @change="onFilterChange"
            style="display: flex; flex-direction: column"
        />
      </div>
  
      <div class="filter-group">
        <label>Оператор</label>
        <a-select
            placeholder="Выбрать"
            style="width: 100%"
            v-model="filters.user"
            :allowClear="true"
            @change="onFilterChange"
        >
            <a-select-option v-for="user in this.users" :key="user.id">
              {{ user.employer_name_1c ?? user.name }}
            </a-select-option>
        </a-select>
      </div>
    </div>
  </template>
  
  <script>
  import { mapActions, mapGetters } from 'vuex';

  export default {
    props: {
        users: {
            type: Array,
            required: true,
        },
    },
    data() {
      const today = new Date();
      const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 2);
      const endOfMonth = today;

      return {
        filterDates: [
          startOfMonth.toISOString().split('T')[0], // Форматируем дату в 'YYYY-MM-DD'
          endOfMonth.toISOString().split('T')[0]
        ],
        filters: {
          letters: ['А','Б','В','Г'],
          user: null,
        },
        letters: ['А','Б','В','Г'],
        operators: ['Оператор 1', 'Оператор 2', 'Оператор 3'],
      };
    },
    methods: {
      ...mapActions('statistic', ['updateFilters']),

      applyFilters() {
        const updatedFilters = {
          dates: this.filterDates,
          letters: this.filters.letters,
          user: this.filters.user,
        };

        this.updateFilters(updatedFilters);

        
        this.$emit('filtersApplied', {
          dates: this.filterDates,
        });
      },

      onFilterChange() {
        const updatedFilters = {
          dates: this.filterDates,
          letters: this.filters.letters,
          user: this.filters.user,
        };

        this.updateFilters(updatedFilters);
        
        this.$emit('filter-change', {
          filters: this.filters,
        });
      },
    },

    computed: {

    },
    mounted() {
      // Вызываем applyFilters сразу после инициализации компонента
      this.applyFilters();
    },
  };
  </script>
  
  <style scoped>
  .filters-container {
    display: flex;
    width: 100%;
    flex-direction: column;
  }
  
  .filter-group {
    margin-bottom: 20px;
  }
  
  .checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
  }
  
  .operator-select {
    width: 100%;
    padding: 8px;
    font-size: 14px;
    border-radius: 4px;
    border: 1px solid #ccc;
  }
  
  .apply-btn {
    background: #4A9DFF;
    color: #FFFFFF;
    width: 100%;
    padding: 8px 16px 8px 16px;
    border-radius: 8px;
    border: none;
  }
  
  .apply-btn:hover {
    background-color: #0056b3;
  }
  </style>
  