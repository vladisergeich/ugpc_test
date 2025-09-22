<template>
    <div class="info_wrapper">
        <span class="no_operation_title" v-if="!currentOperation && !getConsumpShaft">Выберите вал из списка или доп.операцию</span>
        <ShaftInfoElement class="shaft_info-block" v-if="getConsumpShaft" :shaftInfo="getConsumpShaft" :currentOperation="currentOperation" ref="ShaftInfoElement"/>
        <PlanParamElement class="plan_param-block" v-if="getConsumpShaft" :planParameters="getConsumpShaft"  ref="PlanParamElement"/>
        <FactParamElement class="fact_param-block" v-if="currentOperation" :factParameters="currentOperation"  ref="FactParamElement"/>
        <CurrentOperationElement v-if="currentOperation" :currentOperation="currentOperation" ref="CurrentOperationElement"/>
        <template v-if="!currentOperation && getConsumpShaft">
            <div class="info_row_btn">
                <a-button class="operation_stage_btn" @click="visibleModalOperation = true">Добавить операцию</a-button>
                <a-button class="operation_stage_btn_close" @click="deleteConsumpShaft()">Отменить</a-button>
            </div>
            <a-modal
                title="Операции для вала"
                :visible="visibleModalOperation"
                @cancel="visibleModalOperation = false"
                >
                <template slot="footer">
                    <a-button key="submit" type="primary" @click="startOperation()">
                    Начать
                    </a-button>
                </template>
                <a-select style="width: 100%" v-model="selectedMainOperation" >
                    <a-select-option v-for="(operation, index) in getConsumpShaft.active_engraving_order_stage.active_engraving_stage.stage.operations" :key="operation.id">{{ operation.description }}</a-select-option>
                </a-select>
            </a-modal>
        </template>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import CurrentOperationElement from "../elements/CurrentOperationElement.vue"
import ShaftInfoElement from "../elements/ShaftInfoElement.vue"
import PlanParamElement from "../elements/PlanParamElement.vue"
import FactParamElement from "../elements/FactParamElement.vue"
export default {

    components: {
        CurrentOperationElement,
        ShaftInfoElement,
        PlanParamElement,
        FactParamElement,
    },
    props: {
        headerInfo: Array,
    },
    data() {
        return {
            selectedMainOperation: null,
            visibleModalOperation: false,
        };
    },

    methods: {
        async getCurrentOperation() {
            await this.$store.dispatch('getCurrentOperation');
        },

        async getСonsumedShaft() {
            await this.$store.dispatch('getСonsumedShaft');
        },

        async startOperation() {
            if (this.selectedMainOperation) {
                await this.$store.dispatch('startOperation',this.selectedMainOperation);
                this.visibleModalOperation = false;
            }
        },

        async deleteConsumpShaft() {
            await this.$store.dispatch('deleteConsumpShaft');
        },
    },

    computed: {
        ...mapGetters({
            currentOperation: 'getCurrentOperation',
            getConsumpShaft: 'getConsumpShaft'
        }),
    },

    created() {
        this.getCurrentOperation();
        this.getСonsumedShaft();
    },

};
</script>

<style scoped>
.operation_stage_btn {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    border: 1px solid var(--primary-300-activ, #4A9DFF);
    color: var(--primary-300-activ, #4A9DFF);
}

.operation_stage_btn_close {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-left: 20px;
}
.info_row_btn {
    margin-top: 20px;
    display: flex;
    align-items: center;
}
.no_operation_title {
    color: var(--grayscale-500, #BFBFBF);
    font-size: 20px;
    font-family: Open Sans;
    letter-spacing: -0.2px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info_wrapper {
    margin-top: 20px;
}

.shaft_info-block {
    margin-bottom: 30px;
}

.plan_param-block {
    margin-bottom: 30px;
}

.fact_param-block {
    margin-bottom: 30px;
}

</style>