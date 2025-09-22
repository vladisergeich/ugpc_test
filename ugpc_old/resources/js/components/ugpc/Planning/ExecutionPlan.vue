<template>
    <div class="wrapper">
        <h5>Администрирование</h5>
        <div class="select_month">
            <span class="select_month_title">Месяц</span>
            <a-select class="select_month_select" v-model="selectedMonth">
                <a-select-option v-for="month in this.selectExecutionPlan" :key="month.month">
                    {{ getMonthName(month.month) }}
                </a-select-option>
            </a-select>
        </div>
        <div class="plan_fact_card">
            <div class="plan_card">
                <div class="plan_card_row">
                    <span class="plan_card_title">План за выбранный период:</span>
                </div>
                <div class="plan_card_row">
                    <a-input @change="savePlan()" style="margin-bottom: 20px;" v-model="selectExecute.plan_qty"/>
                </div>
            </div>
            <div class="fact_card">
                <div class="fact_card_row">
                    <span class="fact_card_title">Факт за выбранный период:</span>
                </div>
                <div class="fact_card_row">
                    <span class="fact_card_qty"></span>
                </div>
            </div>
        </div>
        <div class="fact_table">
            
        </div>
    </div>

</template>



<script>


    export default {
        components: {
        },
        props: {
            execution_plan: Array,   
            urlsaveplan: String,     
        },  
        data() {
            return {
                selectExecutionPlan: [...this.execution_plan],
                selectExecute: null,
                selectedMonth: null,
                selectedPlanQty : null,
                months: [
                    'Январь',
                    'Февраль',
                    'Март',
                    'Апрель',
                    'Май',
                    'Июнь',
                    'Июль',
                    'Август',
                    'Сентябрь',
                    'Октябрь',
                    'Ноябрь',
                    'Декабрь',
                ],
            }
        },
        watch: {
            selectedMonth: 'updateSelectedPlanQty',
        },
        created() {
            if (this.selectExecutionPlan.length > 0) {
                this.selectedMonth = this.selectExecutionPlan[0].month;
                this.updateSelectedPlanQty(); // Обновляем plan_qty при загрузке компонента
            }
        },
        computed: {
            getMonthName(month) {
            return (month) => {
                const [monthNumber, year] = month.split('.');
                const date = new Date(year, monthNumber - 1, 1);
                const options = { month: 'long' };
                
                return date.toLocaleDateString('ru-RU', options);
            };
            },
        },
        methods: {
            updateSelectedPlanQty() {
            
            // Находим соответствующий plan_qty для выбранного месяца
            const selectedMonthData = this.selectExecutionPlan.find((monthData) => monthData.month === this.selectedMonth);
            this.selectExecute = selectedMonthData;
            },

            savePlan() {
                axios.post(this.urlsaveplan,this.selectExecute)
                .then(response => {

                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            }
        }   
    }   
</script>

<style scoped>
.plan_card_qty {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.fact_card_qty {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.plan_card_title {
    margin-right: 5px;
    color: var(--grayscale-600, #9A9A9A);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.fact_card_title {
    margin-right: 5px;
    color: var(--grayscale-600, #9A9A9A);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}
.plan_card {
    display: flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
}

.fact_card {
    display: flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
}

.plan_fact_card {
    display: flex;
    margin-top: 20px;
}
.select_month_select {

}
.select_month {
    display: flex;
    flex-direction: column;
    width: 230px;
}
.select_month_title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 5px;
}

</style>