<template>
    <div>
        <div class="plan-title">
            <span class="title">{{ title }}  (шт)</span>
        </div>
        <div class="plan-details">
            <div class="plan">
                <span>План:</span>
                <strong class="value">{{ plan }}</strong>
            </div>
            <div class="fact">
                <span>Факт:</span>
                <div class="value_block">
                  <strong class="value">{{ fact }}</strong>
                  <div class="value_percent">
                    <span class="percent">{{ Math.round(fact*100/plan) }}%</span>
                  </div>
                </div>
            </div>
            <div class="deviation">
                <span>Отставание:</span>
                <strong class="value">{{ Number((Number(plan/daysInCurrentMonth).toFixed(1)*workDaysPassed)-fact).toFixed(0) }}</strong>
            </div>
            <div class="deviation">
                <span>Отклонение:</span>
                <strong class="value">{{ plan-fact }}</strong>
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

        plan: {
            type: Number,
            required: true,
        },

        fact: {
            type: Number,
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
        daysInCurrentMonth() {
            const now = new Date(); 
            const year = now.getFullYear(); 
            const month = now.getMonth(); 

            return new Date(year, month + 1, 0).getDate();
        },

        workDaysPassed() {
            //const now = new Date(); 
            return new Set(this.data.map(operation => operation.date)).size; 
        },

        daysPassed() {
            const now = new Date(); 
            return now.getDate(); 
        }
    }
  };
  </script>
  
<style scoped>  

.title {
  font-size: 18px;
  font-weight: 600;
  text-align: left;
}

.plan-details {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}

.value {
  font-size: 20px;
  font-weight: 600;
}

.plan, .fact, .deviation {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.value_block {
  display: flex;
}

.value_percent {
  display: flex;
  margin-left: 10px;
  padding: 3px 8px 3px 8px;
  border-radius: 20px;
  background: #FFFFFF;
}

.percent {
  color: #4067B1;
  display: flex;
  align-items: center;
}
</style>
  