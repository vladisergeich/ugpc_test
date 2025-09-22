<template>
    <div class="brak-card">
        <div class="brak-title">
            <span class="title">{{ title }}  (шт)</span>
        </div>
        <div class="brak-details">
            <div class="brak">
                <span>Кол-во ПЦ</span>
                <div class="value_block">
                  <strong class="value">{{ data.length }}</strong>
                  <div class="value_percent">
                    <span class="percent">{{ Math.round(data.length*100/fact) }}%</span>
                  </div>
                </div>
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

        fact: {
            type: Number,
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
.brak-card {
  box-shadow: 0px 4px 20px 0px #BDBDBD40;
  background: #FFFFFF;
  padding: 20px;
  border-radius: 8px;
  flex: 1;
}

.value {
  font-size: 24px;
  font-weight: 600;
  color: #363F51;
}

.title {
  font-size: 18px;
  font-weight: 400;
  text-align: left;
  color: #E31212;
}

.brak-details {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin-top: 10px;
}

.brak, .temp {
  flex: 1;
  text-align: left;
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
background: #E31212;
}

.percent {
  color: #FFFFFF;
  display: flex;
  align-items: center;
}

</style>
  