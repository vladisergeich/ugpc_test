<template>
    <div>
        <a-modal title="Выберите машинный центр" :visible="visibleModalMachine">
            <template slot="footer">
                <a-button key="submit" type="primary" @click="selectMachine">Сохранить</a-button>
            </template>
            <a-select style="width: 100%" v-model="machine" >
                <a-select-option v-for="(machine, index) in machines" :key="index">{{ machine.machine }}</a-select-option>
            </a-select>
        </a-modal>
        <div class="row">
            <div class="col-lg-8">
                <MachineInfoBlock v-if="selectedMachine" :headerInfo="headerInfo" ref="MachineInfoBlock"/>
                <MachineOperationsBlock ref="MachineOperationsBlock" :workCenter="this.machines[this.machine].work_center" v-if="selectedMachine"/>
            </div>
            <div class="col-lg-4">
                <MachineWarehouseBlock ref="MachineWarehouseBlock" :workCenter="this.machines[this.machine].work_center" v-if="selectedMachine"/>
            </div>  
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import MachineInfoBlock from "./blocks/MachineInfoBlock.vue";
import MachineWarehouseBlock from "./blocks/MachineWarehouseBlock.vue";
import MachineOperationsBlock from "./blocks/MachineOperationsBlock.vue";
export default {
    components: {
        MachineWarehouseBlock,
        MachineOperationsBlock,
        MachineInfoBlock,
    },

    props: {
        machines: Array,
        work_shift_code: Object,
        operator: Object,
        engraving_heads: Array,
    }, 

    created() {
        window.Echo.private('warehouse-orders')
            .listen('WarehouseOrders', (e) => {
                const stagesId = this.selectedMachine.stages.map(stage => stage.id);
                if (stagesId.includes(e.shaft.active_engraving_order_stage.active_engraving_stage.stage_id)) {
                    this.$store.dispatch('addShaft', e.shaft);  
                }
        })
    },

    mounted () {
        
    },
    data() {
        return {
            visibleModalMachine: true,
            machine: null,
        }   
            
    },
    computed: {
        ...mapGetters({
            selectedMachine: 'getMachine'
        }),

        headerInfo() {
            return [
                { title: 'МЦ', value: this.machines[this.machine].machine },
                { title: 'Оператор', value: this.operator.employer_name_1c },
                { title: 'Смена', value: this.work_shift_code.letter },
                { title: 'Время', value: `${this.work_shift_code.starting_time}-${this.work_shift_code.ending_time}` },
                { title: 'Дата', value: this.work_shift_code.date },
            ];
        },
    },

    methods: {
        selectMachine() {
            this.$store.dispatch('selectMachine', this.machines[this.machine]);
            this.$store.dispatch('setEngravingHeads', this.engraving_heads);
            this.visibleModalMachine = false;
        },
    },

    
} 


</script>

<style scoped>

</style>
