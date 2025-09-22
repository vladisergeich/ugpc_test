<template>
    <div class="fact-card">
        <div class="fact-title">
            <span class="title">{{ title }}</span>
        </div>
        <div class="fact-details">
            <div class="fact">
                <strong class="value">{{ data.length }}</strong>
            </div>
            <div class="temp" v-if="data.length > 0">
                <span>Средний темп в смену:</span>
                <strong>{{Number(data.length/new Set(this.data.map(operation => operation.work_shift_code)).size).toFixed(1) }}</strong>
            </div>
            <div class="temp" v-if="(data.length > 0) && (title == 'K5-1' || title == 'K5-2')">
                <span>Среднее время гравировки:</span>
                <strong>{{Number(AverageGravure).toFixed(1) }}</strong>
            </div>
        </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
        title: {
            type: String,
            required: true,
        },

        data: {
            type: Array,
            required: true,
        },
    },
    data() {
      return {

      };
    },

    computed: {
      AverageGravure() {
        const sum = this.data.reduce((total, item) => total + item.engraving_order.engraving_time, 0);
        return this.data.length ? sum / this.data.length : 0;
      }
    }
  };
  </script>
  
  <style scoped>  
  .fact-card {
    background: #FFFFFF;
    box-shadow: 0px 4px 20px 0px #BDBDBD40;
    padding: 20px;
    border-radius: 8px;
    flex: 1;
  }

  .fact-title {
    border-bottom: 1px solid #E8E8E8
  }
  
  .title {
    font-size: 18px;
    font-weight: 400;
    text-align: left;
    color: #363F51;
  }

  .value {
    font-size: 24px;
    font-weight: 600;
    color: #363F51;
  }

  .fact-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
  }
  
  .fact, .temp {
    flex: 1;
    text-align: left;
  }

  </style>
  