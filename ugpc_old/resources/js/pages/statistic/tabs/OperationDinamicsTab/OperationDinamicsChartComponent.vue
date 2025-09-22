<template>
    <div class="chart-wrapper">
        <span class="chart-title">Динамика (шт)</span>
        <span class="chart-title">{{ this.series[0].data.reduce(function(a, b){return a + b;}, 0) }} шт</span>
        <apexchart
            height="450"
            width="100%"
            type="bar" 
            :options="chartOptions"
            :series="series"
            v-if="series.length > 0"  
            ></apexchart>
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
            monthNames: ["янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"],
        };
    },

    computed : {
        series() {
            const groupedData = this.data.reduce((acc, item) => {
                const date = new Date(item.posting_date);
                const monthIndex = date.getMonth(); // индекс месяца (0 для января, 1 для февраля и т.д.)
                const month = `${this.monthNames[monthIndex]} ${date.getFullYear()}`; // Формат "янв 2024"

                if (!acc[month]) {
                    acc[month] = 0;
                }

                acc[month] += 1;

                return acc;
            }, []); 

            const series = [];
            series.push(
                {
                    name: "Operations", 
                    data: Object.values(groupedData), 
                },
            );

            return series;
        },

        categories() {
            const groupedData = this.data.reduce((acc, item) => {
                const date = new Date(item.posting_date);
                const monthIndex = date.getMonth(); // индекс месяца (0 для января, 1 для февраля и т.д.)
                const month = `${this.monthNames[monthIndex]} ${date.getFullYear()}`; // Формат "янв 2024"

                if (!acc[month]) {
                    acc[month] = 0;
                }

                acc[month] += 1;

                return acc;
            }, []); 

            return _.keys(groupedData);
        },

        chartOptions() {
            return {
                chart: {
                    height: 350,
                    toolbar: {
                        show: false  // Скрываем панель инструментов, если она не нужна
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,     // Закругленные углы колонок
                        horizontal: false,    // Вертикальные колонки (не горизонтальные)
                        distributed: true,    // Различные цвета для каждой колонки
                        dataLabels: {
                            position: 'top', // Метки сверху колонок
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val;  // Отображаем значение как есть
                    },
                    offsetY: -10,       // Смещение метки вверх
                    style: {
                        fontSize: '14px',
                        colors: ["#333"]  // Цвет шрифта для меток
                    }
                },
                xaxis: {
                    categories: this.categories,
                    labels: {
                        style: {
                            fontSize: '12px',
                        }
                    }
                },
                yaxis: {
                    show: false,  // Полностью отключаем ось Y
                },
                grid: {
                    show: true,  // Включаем сетку
                    xaxis: {
                        lines: {
                            show: true  // Включаем вертикальные линии сетки
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false  // Отключаем горизонтальные линии сетки
                        }
                    }
                },
            }     
        },
    },

    methods: {

    },
    mounted() {

    },

};
</script>

<style scoped>
.chart-title {
    font-size: 24px;
    font-weight: 400;
    color: #363F51;
}
.chart-wrapper {
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 16px;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
  