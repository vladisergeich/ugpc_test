<template>
    <div class="chart-wrapper">
        <span class="chart-title">{{ this.data.length }} шт</span>
        <apexchart
        type="bar" 
        height="350"
        :options="chartOptions"
        :series="series"
        key="chart2"
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
        },
    },
    data() {
        return {
            
        };
    },

    computed : {
        processedData() {
            const groupedData = this.data.reduce((acc, item) => {
                const shift = item.letter;
                acc.counts[shift] = (acc.counts[shift] || 0) + 1;
                acc.categories.add(`Смена - ${shift}`);
                return acc;
            }, { counts: {}, categories: new Set() });

            const sortedCategories = [...groupedData.categories].sort((a, b) =>
                a.localeCompare(b, 'ru', { sensitivity: 'base' })
            );
            const sortedCounts = Object.entries(groupedData.counts)
                .sort((a, b) => a[0].localeCompare(b[0], 'ru', { sensitivity: 'base' }))
                .map(([_, count]) => count);

            return { sortedCounts, sortedCategories };
        },
        series() {
            return [{
                name: "Operations",
                data: this.processedData.sortedCounts,
            }];
        },
        categories() {
            return this.processedData.sortedCategories;
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
                colors: ['#8DD690', '#FFDB7C', '#BCDCF8', '#FECAFF'],  // Индивидуальные цвета для колонок
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
  