<template>
    <div>
        <div class="shaft_info_row">
            <div class="diameter_block">
                <span class="block_title">Диаметр Вх. контроля:</span>
                <span class="block_value">{{Number(planParameters.initial_size).toFixed(3)}}</span>
                <span class="block_title">Толщина меди Вх. контроль:</span>
                <span class="block_value">{{Number(planParameters.copper_thickness).toFixed(3)}}</span>
            </div>
            <div class="plan_parameters-wrapper">
                <span class="block_title">Плановые показатели:</span>
                <div class="plan_block" v-for="parameter in planParameters.active_engraving_order_stage.active_engraving_stage.plan_params" :key="parameter.id">
                    <span class="plan_title">{{ parameter.parameter.title }}:</span>
                    <span class="plan_value">{{ parameter.float_value !== null ? Number(parameter.float_value).toFixed(3) : parameter.string_value }}</span>
                </div>
                <span class="block_title">Время гравировки:</span>
                <span class="not_cutter_title" v-if="planParameters.engraving_time">{{planParameters.engraving_time}}</span>
                <div style="margin-top: 15px;" class="" v-if="planParameters.active_engraving_order_stage.copper_plating_edition_stage && planParameters.active_engraving_order_stage.copper_plating_edition_stage.operation">
                    <span class="block_title">Показатели Мед. тираж:</span>
                    <div class="plan_block" v-for="parameter in planParameters.active_engraving_order_stage.copper_plating_edition_stage.operation.fact_parameters" :key="parameter.id">
                        <span class="plan_title">{{ parameter.parameter.title }}:</span>
                        <span class="plan_value">{{ parameter.float_value !== null ? parameter.float_value : parameter.string_value }}</span>
                    </div>
                    <span class="not_cutter_title" v-if="planParameters.active_engraving_order_stage.copper_plating_edition_stage.operation.thickness.float_value <= 150">Шлифовка без резца</span>
                </div>
                <div style="margin-top: 15px;" class="" v-if="planParameters.active_engraving_order_stage.grinding_base && planParameters.active_engraving_order_stage.grinding_base.operation">
                    <span class="block_title">Показатели Шлифовка базы:</span>
                    <div class="plan_block" v-for="parameter in planParameters.active_engraving_order_stage.grinding_base.operation.fact_parameters" :key="parameter.id">
                        <span class="plan_title">{{ parameter.parameter.title }}:</span>
                        <span class="plan_value">{{ parameter.float_value !== null ? parameter.float_value : parameter.string_value }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="shaft_info_row">
            <div class="note_block">
                <span class="block_title">Примечание:</span>
                <span v-if="planParameters.design_order.prod_order.charAt(0) == 'N'" class="block_note_value">Необходима Шлифовка с сеткой</span>
            </div>
            <div v-if="planParameters.design_order.external_comment" class="note_block">
                <span class="block_title">Для гравировщика:</span>
                <span class="block_note_value">{{planParameters.design_order.external_comment}}</span>
            </div>
        </div>
        <span class="pre_copper_title" v-if="preCopper">Вал будет отправлен на Пред. меднение</span>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

    components: {

    },
    props: {
        planParameters: Object,
    },
    data() {
        return {

        };
    },

    methods: {

    },
    mounted() {

        if (localStorage.preCopperOrder && localStorage.preCopperOrder != 'null') {
            this.$store.dispatch('setPreCopper', true);
        }
    },

    computed: {
        ...mapGetters({
            preCopper: 'getPreCopper',
        }),
    },
};
</script>

<style scoped>

.pre_copper_title {
    font-size: 16px;
    font-weight: 900;
    color: black;
    margin-top: 20px;
}

.not_cutter_title {
    font-size: 16px;
    font-weight: 900;
    color: black;
    margin-top: 20px;
}
.note_block {
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    display: block;
    padding: 10px;

}
.block_note_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    margin-top: 10px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    letter-spacing: 0.14px;
}

.plan_parameters-wrapper {
    flex-grow: 1;
    padding: 10px;
    gap: 6px;
    align-self: stretch;
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);

}

.block_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 16px;
    display: block;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.block_value {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 18px;
    font-family: Open Sans;
    font-weight: 600;
    letter-spacing: 0.18px;
}

.plan_title {
    color: var(--grayscale-600, #9A9A9A);
    font-size: 16px;
    margin-right: 8px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.plan_value {
    color: var(--txt, #363F51);
    font-size: 16px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.plan_block {
    display: inline-block;
    margin-right: 25px;
}

.diameter_block {
    display: flex;
    padding: 10px;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
    margin-right: 20px;
}

.shaft_info_row {
    margin-bottom: 20px;
    display: flex;
}
</style>