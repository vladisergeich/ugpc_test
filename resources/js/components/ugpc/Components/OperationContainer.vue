<template>
    <div class="operation-container">
        <div class="operation_info_title">                  
            <div class="operation_info_shaft">
                <div class="shafts_made">
                    <span class="shafts_made_title">Сделано валов:</span>
                    <span class="shafts_made_value">{{this.shaftsMadeWorkShift}}</span>
                </div>
            </div>
        </div>
        <a-table
        :columns="columns_operations"
        :data-source="operations"
        :rowClassName="(record, index) => record.type != 'main' ? 'secondary_operation' :  'main_operation'"
        >
        </a-table>
    </div>
</template>

<script>

export default {

  props: {
        operations: Array,
        columns_operations: Array,
    },
    data() {
        return {
            
        };
    },
    methods: {

    },

    computed: {
        shaftsMadeWorkShift() {
            const uniqueShafts = new Set();

            this.operations.forEach(shaft => {
                console.log(shaft);
                if (shaft.shaft_id !== null) {
                    uniqueShafts.add(shaft.shaft_id);
                }
            });

            return uniqueShafts.size;
        }
    }
};
</script>

<style scoped>
.operation-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.operation_info_title {
    display: flex;
    justify-content: space-between;
}

.operation_info_shaft {
    display: flex;
    margin-bottom: 15px;
}

.shafts_made_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.shafts_made_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.shafts_made {
    display: flex;
    margin-right: 20px;
}

.format_difference_title {
    cursor: pointer;
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.secondary_operation {
    border-bottom: 1px solid var(--grayscale-400, #E8E8E8) !important;
    background: var(--additional-light-green, #EFFFE2) !important;
}
</style>