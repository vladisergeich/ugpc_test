<template>
    <div class="fact_params-wrapper">
        <div class="param_block" v-for="param in factParameters.operation.params" :key="param.id">
            <span class="param_title">{{ param.title }}:</span>
            <a-input v-if="param.id != 12 && param.id != 13" :type="param.type" class="param_value"
                v-model="param.value"
            />
            <a-select v-if="param.id == 12" class="param_value" v-model="param.value" :value="engravingHead" style="width: 100%; display: block;">
                <a-select-option style="width: 100%" v-for="(head, index) in engravingHeads" :key="head.variable_desc">{{ head.variable_desc }}</a-select-option>
            </a-select>
            <a-select v-if="param.id == 13" class="param_value" v-model="param.value" :value="engravingHead" style="width: 100%; display: block;">
                <a-select-option style="width: 100%" v-for="(head, index) in engravingHeads" :key="head.variable_body">{{ head.variable_body }}</a-select-option>
            </a-select>
            <span style="font-size: 11px; color: red;" class="pre_copper_title" v-if="param.id == 1 && param.value">Толщина медного покрытия: {{ param?.value && ((param.value-(Number(consumpShaft.initial_size).toFixed(3)-(Number(consumpShaft.copper_thickness).toFixed(3)*2/1050)))*1050/2).toFixed(3) >= 0 ? ((param.value-(Number(consumpShaft.initial_size).toFixed(3)-(Number(consumpShaft.copper_thickness).toFixed(3)*2/1050)))*1050/2).toFixed(3) : 0}}</span>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

    components: {

    },
    props: {
        factParameters: Object,
    },
    data() {
        return {
            engravingHead: null,
        };
    },

    mounted: {

    },

    methods: {

    },

    computed: {
        ...mapGetters({
            consumpShaft: 'getConsumpShaft',
            engravingHeads: 'getEngravingHeads',
        }),
    },

};
</script>

<style scoped>
.param_value {
    display: flex;
    gap: 6px;
    flex: 1 0 0;
    align-items: center;
    align-self: stretch;
}

.param_block {
    flex: 1;
}

.param_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 16px;
    display: block;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.fact_params-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}
</style>