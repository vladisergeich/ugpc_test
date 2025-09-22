<template>
    <div class="pace-card">
        <div class="pace-title">
            <span class="title">{{ title }}  (шт)</span>
        </div>
        <div class="pace-details">
            <div class="pace">
                <span>План</span>
                <strong class="value">{{Number(plan/daysInCurrentMonth).toFixed(1) }}</strong>
            </div>
            <div class="pace">
                <span>Факт</span>
                <strong class="value">{{ Number(fact/workDaysPassed).toFixed(1)}}</strong>
            </div>
            <div class="pace">
                <span>Кол-во ПЦ в текущем темпе</span>
                <div class="value_block">
                  <strong class="value">{{ Number(fact+Number(fact/workDaysPassed).toFixed(1)*(daysInCurrentMonth-workDaysPassed)).toFixed(0) }}</strong>
                  <div class="value_percent">
                    <span class="percent">{{ Math.round(Number(fact+Number(plan/daysInCurrentMonth).toFixed(1)*(daysInCurrentMonth-workDaysPassed)).toFixed(0)*100/plan) }}%</span>
                  </div>
                </div>
            </div>
            <div class="pace">
                <span>С учетом отклонения</span>
                <strong class="value">{{ Number((plan-fact)/(daysInCurrentMonth-workDaysPassed)).toFixed(1) }}</strong>
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
.pace-card {
  background: #FFFFFF;
  box-shadow: 0px 4px 20px 0px #BDBDBD40;
  padding: 20px;
  border-radius: 8px;
  flex: 2;
}

.pace-details {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
}

.title {
  font-size: 18px;
  font-weight: 600;
  text-align: left;
  color: #363F51;
}

.value {
  font-size: 20px;
  font-weight: 600;
  color: #363F51;
}

.pace {
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
background: #363F51;
}

.percent {
  color: #FFFFFF;
  display: flex;
  align-items: center;
}
</style>
  