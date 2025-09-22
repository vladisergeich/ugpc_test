<template>
    <div class="warehouse-container" v-if="this.shiftReceptionOperation == null">
        <span class="warehouse_title">Валы в очереди</span>
        <a-table
        :columns="columns_shafts_for_engraving"
        :data-source="shafts"
        >
        <a @click="consumpShaft(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>
        <a @click="consumpShaft(record)" slot="okvid_number" slot-scope="text, record">{{ text }}</a>
        <a @click="consumpShaft(record)" slot="type_shaft" slot-scope="text, record">{{ text }}</a>
        </a-table>
    </div>
</template>

<script>

export default {

  props: {
        shafts: Array,
        consumpShaft: Function,
        shiftReceptionOperation: Object,
    },
    data() {
        return {
            columns_shafts_for_engraving: [
                {
                title: "№ Вала",
                dataIndex: "shaft_id",
                key: "shaft_id",
                scopedSlots: { 
                    customRender: 'shaft_id',
                },
                },
                {
                title: "№ Оквид",
                dataIndex: "okvid_number",
                key: "okvid_number",
                scopedSlots: { 
                    customRender: 'okvid_number'
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
        }
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