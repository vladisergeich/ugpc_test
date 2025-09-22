<template>
    <div>
        <h5>Параметры печатного процесса:</h5>
        <div class="printing_parameters_input_block">    
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Печатная машина" v-model="profile.printing_machine"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Тип печати" v-model="profile.print_type"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Скорость печати" v-model="profile.print_speed"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Субстрат (тип/марка/поставщик)" v-model="profile.substrate"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Тип обработки материала" v-model="profile.material_processing_type"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Поверхностное натяжение материала" v-model="profile.surface_tension_material"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Производитель / серия красок" v-model="profile.vendor_paint_series"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Температура воздуха" v-model="profile.air_temp"/>
            <a-input class="printing_parameters_input" @change="saveDataProfile()" addon-before="Влажность" v-model="profile.humidity"/>
        </div>
        <div class="nav_button">
            <a-button class="editable-add-btn" @click="addRow()">
                +
            </a-button>
            <a-button style="margin-left: 15px;" class="editable-add-btn" @click="deleteRow()">
                -
            </a-button>
        </div>
        <a-table style="margin-top: 15px;" size="small" bordered :data-source="profile.printing_process_param" :columns="dynamicColumns">
            <template slot="color" slot-scope="text, record, index" >
                <a-input v-model="record.color" @change="saveProfile()"/>
            </template>
            <template slot="paint_viscosity" slot-scope="text, record, index" >
                <a-input v-model="record.paint_viscosity" @change="saveProfile()"/>
            </template>
            <template slot="solvent" slot-scope="text, record, index" >
                <a-input v-model="record.solvent_used" @change="saveProfile()"/>
            </template>
            <template slot="inhibitor" slot-scope="text, record, index" >
                <a-input v-model="record.inhibitor" @change="saveProfile()"/>
            </template>
            <template slot="percentage_inhibitor" slot-scope="text, record, index" >
                <a-input v-model="record.inhibitor" @change="saveProfile()"/>
            </template>
            <template slot="coordinates_lab" slot-scope="text, record, index" >
                <a-input v-model="record.coordinates_lab" @change="saveProfile()"/>
            </template>
            <template slot="eltex_value" slot-scope="text, record, index" >
                <a-input v-model="record.eltex_value" @change="saveProfile()"/>
            </template>
            <template slot="raquel" slot-scope="text, record, index" >
                <a-input v-model="record.raquel" @change="saveProfile()"/>
            </template>
            <template slot="optical_density" slot-scope="text, record, index" >
                <a-input v-model="record.optical_density" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_50" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_50" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_15" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_15" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_5" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_5" @change="saveProfile()"/>
            </template>
            <template slot="optical_density_admission" slot-scope="text, record, index" >
                <a-input v-model="record.optical_density_admission" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_50_admission" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_50_admission" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_15_admission" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_15_admission" @change="saveProfile()"/>
            </template>
            <template slot="raster_tone_5_admission" slot-scope="text, record, index" >
                <a-input v-model="record.raster_tone_5_admission" @change="saveProfile()"/>
            </template>

        </a-table>
    </div>
</template>
    
    
    
<script>
  import { mapGetters } from 'vuex'
export default {  
    components: {

    },
    props: {
        profile: Object,
    },
    data() {
        return {
            columnsPrintingProcess: [
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
                {
                title: 'Вязкость',
                dataIndex: 'paint_viscosity',
                scopedSlots: { 
                        customRender: 'paint_viscosity', 
                    },
                },
                {
                title: 'Растворитель',
                dataIndex: 'solvent',
                scopedSlots: { 
                        customRender: 'solvent', 
                    },
                },
                {
                title: 'Замедлитель',
                dataIndex: 'inhibitor',
                scopedSlots: { 
                        customRender: 'inhibitor', 
                    },
                },
                {
                title: '% Замедлителя',
                dataIndex: 'percentage_inhibitor',
                scopedSlots: { 
                        customRender: 'percentage_inhibitor', 
                    },
                },
            ],
        };
    },

    created() {

    },

    computed: {

        dynamicColumns() {
            let dynamicColumns = [...this.columnsPrintingProcess]; 

            if (this.profile.type == "flexo") {
                dynamicColumns.push({
                    title: 'Координаты Lab',
                    dataIndex: 'coordinates_lab',
                    scopedSlots: { 
                        customRender: 'coordinates_lab', 
                    },
                });

            } else if (this.profile.type == "roto") {
                dynamicColumns.push({
                    title: 'Координаты Lab',
                    dataIndex: 'coordinates_lab',
                    scopedSlots: { 
                        customRender: 'coordinates_lab', 
                    },
                });

                dynamicColumns.push({
                    title: "Значение Eltex",
                    dataIndex: "eltex_value",
                    key: "eltex_value",
                    scopedSlots: { 
                        customRender: 'eltex_value', 
                    },
                });

                dynamicColumns.push({
                    title: "Ракель",
                    dataIndex: "raquel",
                    key: "raquel",
                    scopedSlots: { 
                        customRender: 'raquel', 
                    },
                });
            }

            

            dynamicColumns.push({
            title: 'Оптическая плотность',
            dataIndex: 'optical_density',
            scopedSlots: { 
                    customRender: 'optical_density', 
                },
            });
            
            dynamicColumns.push({
            title: '%',
            dataIndex: 'optical_density_admission',
            scopedSlots: { 
                    customRender: 'optical_density_admission', 
                },
            });
            dynamicColumns.push({
            title: 'TVI 50',
            dataIndex: 'raster_tone_50',
            scopedSlots: { 
                    customRender: 'raster_tone_50', 
                },
            });
            dynamicColumns.push({
            title: '+/-',
            dataIndex: 'raster_tone_50_admission',
            scopedSlots: { 
                    customRender: 'raster_tone_50_admission', 
                },
            });
            dynamicColumns.push({
            title: 'TVI 15',
            dataIndex: 'raster_tone_15',
            scopedSlots: { 
                    customRender: 'raster_tone_15', 
                },
            });
            dynamicColumns.push({
            title: '+/-',
            dataIndex: 'raster_tone_15_admission',
            scopedSlots: { 
                    customRender: 'raster_tone_15_admission', 
                },
            });
            dynamicColumns.push({
            title: 'TVI 5',
            dataIndex: 'raster_tone_5',
            scopedSlots: { 
                    customRender: 'raster_tone_5', 
                },
            });
            dynamicColumns.push({
            title: '+/-',
            dataIndex: 'raster_tone_5_admission',
            scopedSlots: { 
                    customRender: 'raster_tone_5_admission', 
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
            await this.$store.dispatch('addRow','printing_process');
        },

        async deleteRow() {
            await this.$store.dispatch('deleteRow','printing_process');
        }
    },
    
};
</script>

<style>
.nav_button {
    display: flex;
    margin-top: 15px;
}

.printing_parameters_input_block {
    margin-top: 15px;
    display: flex;
    flex-wrap: wrap;
}

.printing_parameters_input {
    margin-top: 10px;
    margin-right: 20px;
    width: 30%;
}

</style>
    