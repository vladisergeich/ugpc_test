<template>
    <div class="copper_wrapper">
        <div class="copper-row">
            <CopperTable :data="data.filter(operation => [6,7,8].includes(operation.machine_id))"/>
        </div>
    </div>
</template>

<script>

import CopperTable from './CopperTable.vue';
export default {
    components: {
        CopperTable,
    },
    props: {
        data: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {

        }
    },

    watch: {

    },

    methods: {

    }
};
</script>

<style scoped>

</style>
  