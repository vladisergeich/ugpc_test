<template>
    <div class="wrapper">
        <MachineHeaderElement :headerInfo="headerInfo" ref="MachineHeaderElement"/>
        <StageInfoElement ref="StageInfoElement"/>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import StageInfoElement from "../elements/StageInfoElement.vue";
import MachineHeaderElement from "../elements/MachineHeaderElement.vue";
export default {

    components: {
        MachineHeaderElement,
        StageInfoElement,
    },
    props: {
        headerInfo: Array,
    },
    data() {
        return {

        };
    },

    methods: {
        
    },

};
</script>

<style scoped>
.wrapper {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
    margin-bottom: 30px;
}
</style>