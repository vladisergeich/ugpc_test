<template>
    <div class="mc-wrapper">
        <div class="statistic-row">
            <OperationDinamicsChartComponent :data="data"/>
        </div>
        <div class="statistic-row">
            <OperationDinamicsFactTableComponent :data="data"/>
        </div>
    </div>
</template>

<script>
import OperationDinamicsChartComponent from './OperationDinamicsChartComponent.vue';
import OperationDinamicsFactTableComponent from './OperationDinamicsFactTableComponent.vue';
export default {
    components: {
        OperationDinamicsChartComponent,
        OperationDinamicsFactTableComponent
    },
    props: {
        data: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            
        };
    },

    computed : {

    },

    methods: {

    },
    mounted() {

    },

};
</script>

<style scoped>
.filter-row {
    display: flex;
    flex-direction: column;
}
.chart {
    display: block;
    width: 100%;
}

.generela-statistic-mc {
    display: flex;
    width: 70%;
}

.shifts-chart {
    display: flex;
    width: 30%;
}

.statistic-row {
    display: flex;
    gap: 20px;
    margin-top: 15px;
    width: 100%;
}

.average-block {
    width: 50%;
}
</style>
  