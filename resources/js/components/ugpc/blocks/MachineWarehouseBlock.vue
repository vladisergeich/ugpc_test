<template>
    <div class="warehouse-container">
        <span class="warehouse_title">Валы в очереди</span>
        <a-table
        :columns="columnsWarehouseShafts"
        :data-source="Shafts"
        >
        <a @click="consumpShaft(record)" slot="shaft.shaft_id" slot-scope="text, record">{{ text }}</a>
        <a @click="consumpShaft(record)" slot="design_order.okvid_number" slot-scope="text, record">{{ text }}</a>
        <a @click="consumpShaft(record)" slot="type_shaft" slot-scope="text, record">{{ text }}</a>
        </a-table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

  props: {

    },
    data() {
        return {
            columnsWarehouseShafts: [
                {
                title: "№ Вала",
                dataIndex: "shaft.shaft_id",
                key: "shaft.shaft_id",
                scopedSlots: { 
                    customRender: 'shaft.shaft_id',
                },
                },
                {
                title: "№ Оквид",
                dataIndex: "design_order.okvid_number",
                key: "design_order.okvid_number",
                scopedSlots: { 
                    customRender: 'design_order.okvid_number'
                },
                },
                {
                title: "Вид вала",
                dataIndex: "type_shaft",
                key: "type_shaft",
                scopedSlots: { 
                    customRender: 'type_shaft',
                },
                },
            ],
        };
    },

    methods: {
        async consumpShaft(shaft) {
            await this.$store.dispatch('consumpShaft',shaft);
        },

        async getWarehouseShafts() {
            await this.$store.dispatch('getWarehouseShafts');
        }
    },

    computed: {
        ...mapGetters({
            Shafts: 'getWarehouseShafts'
        })
    },

    created() {
        this.getWarehouseShafts();
    },

};
</script>

<style scoped>
.warehouse-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.warehouse_title {
    margin-bottom: 20px;
    color: var(--txt, #363F51);
    font-size: 20px;
    font-family: Open Sans;
    letter-spacing: -0.2px;
    display: block;
}
</style>