<template>
    <div class="fact-card">
        <div class="fact-title">
            <span class="title">{{ title }}</span>
            <a-button v-if="!isToggle" @click="isToggle = true"><span>Посмотреть в графике</span></a-button>
            <a-button v-if="isToggle" @click="isToggle = false"><span>Посмотреть в таблице</span></a-button>
        </div>
        <div class="fact-details">
            <a-table 
                :columns="columns" 
                :data-source="groupedOperations" 
                size="small"
                bordered
                v-if="!isToggle"  
            >
            </a-table>
            <apexchart
            width="100%"
            height="350"
            type="bar" 
            :options="chartOptions"
            :series="series"
            v-if="isToggle"  
            ></apexchart>
        </div>
    </div>
  </template>
  
  <script>
  import VueApexCharts from 'vue-apexcharts'
  export default {
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        title: {
            type: String,
            required: true,
        },

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
        isToggle: false, 
        monthNames: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"],
        series: [
            {
                name: 'План',
                data: [],
            },
            {
                name: 'Факт',
                data: [],
            }
        ],
        categories: [],
        columns: [
            {
                title: "Месяц",
                dataIndex: "month",
                key: "month"
            },
            {
                title: "План",
                dataIndex: "plan",
                key: "plan",
            },
            {
                title: "Факт",
                dataIndex: "fact",
                key: "fact",
            },
            {
                title: "Отставание",
                dataIndex: "deviation",
                key: "deviation",
            },
        ],
      };
    },
    computed: {
        groupedOperations() {
            const grouped = {};

            this.data.forEach(operation => {

                if (operation.posting_date) {
                    const operationDate = new Date(operation.posting_date); 

                    const year = operationDate.getFullYear();
                    const month = operationDate.getMonth() + 1 < 10 ? `0${operationDate.getMonth() + 1}` : `${operationDate.getMonth() + 1}`;
                    const monthKey = `${month}.${year}`;
                    if (!grouped[monthKey]) {
                        grouped[monthKey] = { fact: 0, plan: this.plan.filter(value => value.month == monthKey)[0]?.plan_qty || 0 };   
                    }

                    grouped[monthKey].fact += 1; 
                }
            });

            return Object.keys(grouped).map(monthKey => {
                const data = grouped[monthKey];
                const deviation = data.plan - data.fact;
                const [day, month, year] = (`01.${monthKey}`).split('.').map(Number);
                const formattedMonth = this.formatMonth(new Date(new Date(year, month - 1, day))); 
                
                this.series[0].data.push(data.plan);
                this.series[1].data.push(data.fact);

                this.categories.push(formattedMonth);

                return {
                    month: formattedMonth,
                    plan: data.plan,
                    fact: data.fact,
                    deviation: Math.abs(deviation),
                };
            });
        },

        chartOptions() {
            return {
                chart: {
                type: 'bar',
                height: 350
                },
                plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end'
                },
                },
                dataLabels: {
                enabled: false
                },
                stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
                },
                xaxis: {
                categories: this.categories,
                },
                yaxis: {
                title: {
                    
                }
                },
                fill: {
                opacity: 1
                },
                tooltip: {
                y: {
                    formatter: function (val) {
                    return "$ " + val + " thousands"
                    }
                }
                }
            }     
        },

    },
    methods: {
        formatMonth(date) {
            return date.toLocaleDateString('ru-RU', { year: 'numeric', month: 'short' }).replace('.', '');
        },
    },
  };
  </script>
  
  <style scoped>  
  .fact-card {
    background: #FFFFFF;
    box-shadow: 0px 4px 20px 0px #BDBDBD40;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
  }

  .fact-title {
    border-bottom: 1px solid #E8E8E8;
    display: flex;
    justify-content: space-between;
  }
  
  .title {
    font-size: 18px;
    font-weight: 600;
    text-align: left;
    color: #363F51;
  }

  .fact-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
  }
  </style>
  