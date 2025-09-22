<template>
    <div class="table-wrapper">
        <div class="fact-title">
            <a-button v-if="!isToggle" @click="isToggle = true"><span>Посмотреть в графике</span></a-button>
            <a-button v-if="isToggle" @click="isToggle = false"><span>Посмотреть в таблице</span></a-button>
        </div>
        <div class="fact-details">
            <a-table 
                :columns="columns" 
                :data-source="tableData" 
                size="small"
                bordered
            >
            </a-table>
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
        data: {
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
                name: 'Смена А',
                data: [],
            },
            {
                name: 'Смена Б',
                data: [],
            },
            {
                name: 'Смена В',
                data: [],
            },
            {
                name: 'Смена Г',
                data: [],
            }
        ],
        categories: [],
        tableData: [],
        columns: []
      };
    },

    watch: {
        data: {
            immediate: true,
            handler() {
                this.generateTableData();
            }
        }
    },

    computed: {
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
        generateTableData() {
            const groupedData = {};
            const monthSet = new Set();

            // Шаг 1: Группировка данных по сменам и месяцам
            this.data.forEach((item) => {
                const date = new Date(item.posting_date);
                const month = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}`; // Формат "2024-01"
                
                // Добавляем месяц в Set, чтобы собрать уникальные месяцы
                monthSet.add(month);

                // Инициализируем смену, если её еще нет в groupedData
                if (!groupedData[item.letter]) {
                    groupedData[item.letter] = { letter: item.letter, total: 0 };
                }

                // Инициализируем месяц для данной смены, если его еще нет
                if (!groupedData[item.letter][month]) {
                    groupedData[item.letter][month] = 0;
                }

                // Добавляем количество операций для смены и месяца
                groupedData[item.letter][month] += 1;
                groupedData[item.letter].total += 1; // Добавляем в итог
            });

            // Шаг 2: Формирование колонок таблицы
            this.columns = [
                { 
                    title: 'Смена', 
                    dataIndex: 'letter', 
                    key: 'letter',
                    customHeaderCell: (column) => ({
                        style: {
                            background: '#F7F7F8',
                        },
                    }), 
                },
                { 
                    title: 'Итого', 
                    dataIndex: 'total', 
                    key: 'total',
                    customHeaderCell: (column) => ({
                        style: {
                            background: '#F7F7F8',
                        },
                    }), 
                }
            ];

            // Добавляем динамически колонки для каждого уникального месяца
            Array.from(monthSet).sort().forEach((month) => {
                const [year, monthNumber] = month.split('-');
                const monthNames = ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"];
                const monthLabel = `${monthNames[parseInt(monthNumber, 10) - 1]} ${year}`; // Формат "янв 2024"
                this.columns.push(
                    { 
                        title: monthLabel, 
                        dataIndex: month, 
                        key: month,
                        customHeaderCell: (column) => ({
                            style: {
                                background: '#F7F7F8',
                            },
                        }), 
                    }
                );
            });

            // Шаг 3: Формируем данные для таблицы
            this.tableData = Object.values(groupedData);

            // Шаг 4: Добавляем строку "Сумма" с итогами по месяцам и общим итогом
            const totalRow = { letter: 'Сумма', total: 0 };
            Array.from(monthSet).sort().forEach((month) => {
                totalRow[month] = this.tableData.reduce((sum, row) => sum + (row[month] || 0), 0);
                totalRow.total += totalRow[month];
            });
            
            this.tableData.unshift(totalRow);
        }
    },
  };
  </script>
  
  <style scoped>  
  .table-wrapper {
    background: white;
    width: 100%;
    padding: 20px;
  }

  .fact-title {
    border-bottom: 1px solid #E8E8E8;
    display: flex;
    justify-content: flex-end;
  }

  .fact-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-top: 10px;
  }
  </style>
  