<template>
    <div class="chart-wrapper">
        <span class="chart-title">Кол-во отработанных смен</span>
        <apexchart
        type="donut" 
        :options="chartOptions"
        :series="series"
        key="chart1"
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
            // Убираем дубликаты по `work_shift_code`
            const uniqueData = _.unionBy(this.data, "work_shift_code");

            // Группируем данные
            const groupedData = uniqueData.reduce((acc, item) => {
                const shift = item.letter;

                if (!acc[shift]) {
                    acc[shift] = 0;
                }

                acc[shift] += 1;
                return acc;
            }, {});

            // Сортируем и разделяем на `series` и `labels`
            const sortedEntries = Object.entries(groupedData).sort((a, b) =>
                a[0].localeCompare(b[0], "ru", { sensitivity: "base" })
            );

            const series = sortedEntries.map(([_, count]) => count);
            const labels = sortedEntries.map(([shift]) => `Смена - ${shift}`);

            return { series, labels };
        },

        series() {
            return this.processedData.series;
        },

        chartOptions() {
            return {
                chart: {
                    type: 'donut',
                    height: 350,
                },
                legend: {
                    formatter: function(val, opts) {
                        return val + " - " + opts.w.globals.series[opts.seriesIndex]
                    }
                },
                colors: ['#8DD690', '#FFDB7C', '#BCDCF8', '#FECAFF'],  // Цвета для каждого сегмента
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',  // Размер внутреннего кольца
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: '',
                                    formatter: function (w) {
                                        // Вычисляем общую сумму и выводим в центре
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false  // Отключаем метки на каждом сегменте
                },
                legend: {
                    show: true,
                    fontSize: '14px',           
                    fontWeight: 400,            
                    horizontalAlign: 'left',
                    position: 'bottom',    
                    formatter: function(val, opts) {
                        return `${val} - ${opts.w.globals.series[opts.seriesIndex]} шт`;
                    },              
                    labels: {
                        colors: '#333',
                        useSeriesColors: true
                    },
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(val) {
                            return `${val} шт`;  // Формат подсказки
                        }
                    }
                },
                labels: this.processedData.labels,
                responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                    width: 200
                    },
                    legend: {
                    position: 'bottom'
                    }
                },
                }]
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
    font-size: 18px;
    font-weight: 400;
    color: #363F51;
}
.chart-wrapper {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-direction: column;
    padding: 16px;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.apexcharts-legend {
    display: flex;
    flex-direction: column;
    align-items: flex-start; 
}
</style>
  