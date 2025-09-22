<template>
    <div>
        <div class="title_fingerprint_row">
            <h5>Спецификация фингерпринта</h5>
        </div>    
        <div class="fingerprint_input_block">      
            <a-input class="fingerprint_title_input" addon-before="Id-номер комплекта цилиндров"  v-model="profile.cylinder_set_number"/>
            <a-input class="fingerprint_title_input" addon-before="Производитель" v-model="profile.manufacturer"/>
            <a-input class="fingerprint_title_input" addon-before="Номер заказа" v-model="profile.order_number"/>
        </div>
        <div class="nav_button">
            <a-button class="editable-add-btn" @click="addRow()">
                +
            </a-button>
            <a-button style="margin-left: 15px;" class="editable-add-btn" @click="deleteRow()">
                -
            </a-button>
        </div>
        <a-table style="margin-top: 15px;" size="small" bordered :data-source="profile.finger_print_param" :columns="dynamicColumns">
            <template slot="color" slot-scope="text, record, index" >
                <a-input v-model="record.color" @change="saveProfile()"/>
            </template>
            <template slot="anilox_lineature" slot-scope="text, record, index" >
                <a-input v-model="record.anilox_lineature" @change="saveProfile()"/>
            </template>
            <template slot="cell_volume" slot-scope="text, record, index" >
                <a-input v-model="record.cell_volume" @change="saveProfile()"/>
            </template>
            <template slot="pigment_paste" slot-scope="text, record, index" >
                <a-input v-model="record.pigment_paste" @change="saveProfile()"/>
            </template>
            <template slot="technical_lacquer" slot-scope="text, record, index" >
                <a-input v-model="record.technical_lacquer" @change="saveProfile()"/>
            </template>
            <template slot="base_lacquer" slot-scope="text, record, index" >
                <a-input v-model="record.base_lacquer" @change="saveProfile()"/>
            </template>
            <template slot="corner" slot-scope="text, record, index" >
                <a-input v-model="record.corner" @change="saveProfile()"/>
            </template>
            <template slot="cutter" slot-scope="text, record, index" >
                <a-input v-model="record.cutter" @change="saveProfile()"/>
            </template>
            <template slot="lineature" slot-scope="text, record, index" >
                <a-input v-model="record.lineature" @change="saveProfile()"/>
            </template>
            <template slot="tape_type" slot-scope="text, record, index" >
                <a-input v-model="record.tape_type" @change="saveProfile()"/>
            </template>
            <template slot="note" slot-scope="text, record, index" >
                <a-input v-model="record.note" @change="saveProfile()"/>
            </template>
        </a-table>
    </div>
</template>
    
    
    
<script>
export default {  
    components: {

    },
    props: {
        profile: Object,
    },
    data() {
        return {
            columnsFingerprint: [
                {
                title: '№',
                dataIndex: 'string_number',
                scopedSlots: { 
                        customRender: 'string_number', 
                    },
                },
                {
                title: 'Краска',
                dataIndex: 'color',
                scopedSlots: { 
                        customRender: 'color', 
                    },
                },
            ],
        };
    },

    created() {

    },

    computed: {
        dynamicColumns() {
            let dynamicColumns = [...this.columnsFingerprint]; 

            if (this.profile.type == "flexo") {
                dynamicColumns.push({
                    title: 'Координаты Lab',
                    dataIndex: 'coordinates_lab',
                    scopedSlots: { 
                        customRender: 'coordinates_lab', 
                    },
                });

                dynamicColumns.push({
                title: 'Линиатура Анилокса',
                dataIndex: 'anilox_lineature',
                scopedSlots: { 
                        customRender: 'anilox_lineature', 
                    },
                });
                dynamicColumns.push({
                title: 'Объем ячеек',
                dataIndex: 'cell_volume',
                scopedSlots: { 
                        customRender: 'cell_volume', 
                    },
                });
                dynamicColumns.push({
                title: 'Линиатура клише',
                dataIndex: 'lineature',
                scopedSlots: { 
                        customRender: 'lineature', 
                    },
                });
                dynamicColumns.push({
                title: 'Тип ленты',
                dataIndex: 'tape_type',
                scopedSlots: { 
                        customRender: 'tape_type', 
                    },
                });

            } else if (this.profile.type == "roto") {
                dynamicColumns.push({
                title: 'Пигментная паста',
                dataIndex: 'pigment_paste',
                scopedSlots: { 
                        customRender: 'pigment_paste', 
                    },
                });
                dynamicColumns.push({
                title: 'Технический лак',
                dataIndex: 'technical_lacquer',
                scopedSlots: { 
                        customRender: 'technical_lacquer', 
                    },
                });
                dynamicColumns.push({
                title: 'Базовый лак',
                dataIndex: 'base_lacquer',
                scopedSlots: { 
                        customRender: 'base_lacquer', 
                    },
                });
                dynamicColumns.push({
                title: 'Линиатура',
                dataIndex: 'lineature',
                scopedSlots: { 
                        customRender: 'lineature', 
                    },
                });
                dynamicColumns.push({
                title: 'Угол',
                dataIndex: 'corner',
                scopedSlots: { 
                        customRender: 'corner', 
                    },
                });
                dynamicColumns.push({
                title: 'Резец',
                dataIndex: 'cutter',
                scopedSlots: { 
                        customRender: 'cutter', 
                    },
                });
            }

            dynamicColumns.push({
                title: 'Примечание',
                dataIndex: 'note',
                scopedSlots: { 
                        customRender: 'note', 
                    },
            });

            return dynamicColumns;
        },
    },

    methods: {
        async saveProfile() {
            await this.$store.dispatch('saveProfile');
        },

        async addRow() {
            await this.$store.dispatch('addRow','finger_print');
        },

        async deleteRow() {
            await this.$store.dispatch('deleteRow','finger_print');
        }
    },
    
};
</script>

<style>
.nav_button {
    display: flex;
    margin-top: 15px;
}
.fingerprint_input_block {
    margin-top: 15px;
    display: flex;
    flex-wrap: wrap;
}

.fingerprint_title_input {
    margin-top: 10px;
    margin-right: 20px;
    width: 30%;
}
</style>
    