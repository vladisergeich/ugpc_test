<template>
    <div class="card-container">
        <CardTitleElement :change="false"  :application="application" ref="CardTitleElement"/>
        <ProgressBarElement style="margin-top: 10px;" :stages="application.re_engraving_stage" ref="ProgressBarElement"/>
        <a-collapse ghost style="margin-top: 15px;">
            <a-collapse-panel key="1" header="Подробнее">
                <MoreDetailsElement style="margin-top: 10px;" :link="true" :stages="application.re_engraving_stage" ref="MoreDetailsElement"/>
            </a-collapse-panel>
        </a-collapse>
    </div>
</template>

<script>
import ProgressBarElement from "../elements/ProgressBarElement.vue";
import CardTitleElement from "../elements/CardTitleElement.vue";
import MoreDetailsElement from "../elements/MoreDetailsElement.vue";
import { mapGetters } from 'vuex';
export default {

    components: {
        ProgressBarElement,
        CardTitleElement,
        MoreDetailsElement,
    },
    props: {
        application: Object,
    },
    data() {
        return {

        };
    },

    created() {

    },

    methods: {

    },

    computed: {

    }
};
</script>

<style scoped>
.card-container {
    padding: 20px;
    gap: 16px;
    border-radius: 10px;
    background: #FFFFFF;
    margin-bottom: 20px;
}
</style>