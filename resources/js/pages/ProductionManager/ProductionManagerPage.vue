<template>
    <div class="plan-container">
      <a-tabs default-active-key="1">
        <a-tab-pane key="1" tab="План">
          <div class="plan-form">
            <a-radio-group v-model="planType" button-style="solid">
              <a-radio-button value="date">План на конкретную дату</a-radio-button>
            </a-radio-group>
  
            <div class="plan-inputs">
              <a-form>
                <!-- Выбор даты или месяца -->
                <a-form-item>
                  <template v-if="planType === 'date'">
                    <date-picker
                        type="date"
                        value-type="format"
                        format="YYYY-MM-DD"
                        v-model="selectedDate"
                        @change="getPlan()"
                    ></date-picker>
                  </template>
                </a-form-item>
  
                <!-- Поле ввода плана -->
                <a-form-item>
                  <a-input
                    placeholder="Введите план производства"
                    v-model="plan"
                  />
                </a-form-item>
  
                <!-- Кнопка отправки -->
                <a-form-item>
                  <a-button type="primary" @click="savePlan()">
                    Сохранить план
                  </a-button>
                </a-form-item>
              </a-form>
            </div>
          </div>
        </a-tab-pane>
      </a-tabs>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        planType: 'date',
        selectedDate: null,
        plan: null,
      };
    },
    methods: {

        async getPlan() {
            const data = await axios.get(route('ugpc.productionmanager.getPlan', {date: this.selectedDate}));
            this.plan = data.data.plan?.plan_qty;
        },

        async savePlan() {
          await axios.post(route('ugpc.productionmanager.savePlan', {date: this.selectedDate, plan: this.plan}));
        },
    }
  };
  </script>
  
  <style scoped>
  .plan-container {
    padding: 24px;
    background-color: #f5f5f5;
    border-radius: 8px;
    max-width: 600px;
    margin: 0 auto;
  }
  
  .plan-form {
    margin-top: 24px;
  }
  
  .plan-inputs {
    margin-top: 16px;
  }
  
  a-radio-group {
    display: block;
    margin-bottom: 16px;
  }
  
  a-button {
    width: 100%;
  }
  </style>
  