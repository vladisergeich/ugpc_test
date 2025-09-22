<template>
    <div class="defect_wrapper">
        <div class="defect-row">
            <div class="defect-block">
                <span class="defect-block_title">Кол-во бракованных цилиндров</span>
                <div style="display: flex;">
                    <span class="defect-block_value">{{ this.data.filter(operation => operation.operation_id == 68).length }}</span>
                    <span class="defect-block_percent">{{ Math.round(this.data.filter(operation => operation.operation_id == 68).length*100/this.data.filter(operation => operation.operation_id == 36).length) }}%</span>
                </div>
                <div class="defect-block_desc">
                    <span class="desc-title">Валов, факт</span>
                    <span class="desc-value">{{ this.data.filter(operation => operation.operation_id == 36).length }}</span>
                </div>
            </div>
            <div class="defect-block">
                <span class="defect-block_title">Больше всего брака на Рабочем центре</span>
                <span class="defect-block_value">{{ this.mostFrequentDefect[0].work_center }}</span>
                <div class="defect-block_desc">
                    <span class="desc-title">Кол-во брак-ых валов на Рабочем центре</span>
                    <span class="desc-value">{{ this.data.filter(operation => operation.operation_id == 68).filter(operation => operation.work_center_id == this.mostFrequentDefect[0].id).length }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    components: {

    },
    props: {
        data: {
            type: Array,
            required: true,
        },

        workCenters: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            mostFrequentDefect: null,
        }
    },

    watch: {
        data: {
            immediate: true,
            handler() {
                this.calculateMostFrequentDefect();
            }
        }
    },

    methods: {
        calculateMostFrequentDefect() {
            const frequencyMap = this.data.filter(operation => operation.operation_id == 68).reduce((acc, item) => {
                const category = item.work_center_id;
                acc[category] = (acc[category] || 0) + 1;
                return acc;
            }, {});

            this.mostFrequentDefect = this.workCenters.filter(wc => wc.id == Object.entries(frequencyMap).reduce((a, b) => {
                return b[1] > a[1] ? b : a;
            })[0]);


            console.log(this.mostFrequentDefect);
        }
    }
};
</script>

<style scoped>
.defect_wrapper {
    display: flex;
    flex-direction: column;
    width: 100%;
}
.defect-row {
    display: flex;
    gap: 20px;
}

.defect-block   {
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 20px;
    gap: 16px;
    border-radius: 10px 0px 0px 0px;
    background: #FFFFFF;
}

.defect-block_desc {
    display: flex;
}

.defect-block_percent {
    padding: 3px 8px 3px 8px;
    border-radius: 20px;
    background: #E31212;
    color: #FFFFFF;
    font-size: 16px;
    font-weight: 600;
}

.desc-title {
    font-size: 16px;
    font-weight: 400;
    text-align: left;
    color: #7B7B7B;
    margin-right: 20px;
}

.desc-value {
    font-size: 18px;
    font-weight: 400;
    text-align: left;
    color: #363F51;
}

.defect-block_title {
    font-size: 18px;
    font-weight: 400;
    color: #363F51;
    border-bottom: 1px solid #E8E8E8;
    padding-bottom: 8px;
}

.defect-block_value {
    font-size: 20px;
    font-weight: 600;
    text-align: left;
    color: #363F51;
    margin-right: 10px;
}
</style>
  