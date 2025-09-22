<template>
    <div class="vendors-container">
        <a-button class="editable-add-btn" @click="showModalAdd = true">
            Добавить
        </a-button>
        <a-table
            :columns="profilesColumns"
            :data-source="profiles"
            >
            <a target="_blank" :href="'https://okvid.danaflex.ru/ugpc/profileregistry/profilecard/'+record.id" slot="short_name" slot-scope="text, record">{{text}}</a>
        </a-table>
        <a-modal 
            v-model="showModalAdd" 
            title="Добавление нового профиля"
        >
            <a-input class="add_profile_input" addon-before="Краткое название" placeholder="Введите название" v-model="newProfile.short_name" />
            <a-input class="add_profile_input" addon-before="Аналог поставщика" placeholder="Введите название" v-model="newProfile.supplier_analog_icc" />
            <a-input class="add_profile_input" addon-before="Печать" placeholder="Введите название" v-model="newProfile.print" />
            <a-form-item v-if="this.type === 'roto'" label="Гравировщик" name="region">
                <a-select style="margin-top: 15px; width: 100%;" default-value="Выберите гравировщика" :allowClear="true" v-model="newProfile.engraver">
                    <a-select-option v-for="vendor in vendors" :key="vendor.vendor_name">
                        {{vendor.vendor_name }}
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item v-if="this.type === 'flexo'" label="Вывод клише" name="region">
                <a-select style="margin-top: 15px; width: 100%;" default-value="Выберите поставщика" :allowClear="true" v-model="newProfile.engraver">
                    <a-select-option v-for="vendor in vendors" :key="vendor.vendor_name">
                        {{vendor.vendor_name }}
                    </a-select-option>
                </a-select>
            </a-form-item>
            <a-input class="add_profile_input" addon-before="Первичка" placeholder="Введите название" v-model="newProfile.primary" />
            <a-input class="add_profile_input" addon-before="Вторичка" placeholder="Введите название" v-model="newProfile.secondary_housing" />
            <a-input class="add_profile_input" addon-before="Серия красок" placeholder="Введите название" v-model="newProfile.paint_series" />
            <template slot="footer">
                <a-button key="submit" type="primary" @click="addNewProfile()">
                Создать
                </a-button>
            </template>
        </a-modal>
    </div>
</template>

<script>
export default {

    props: {
        profiles: Array,
        vendors: Array,
        type: String,
    },
    data() {
        return {
            newProfile: {
                short_name: "",
                supplier_analog_icc: "",
                print: "",
                engraver: "",
                primary: "",
                secondary_housing: "",
                paint_series: ""
            },
            showModalAdd: false,
            profilesColumns: [
                {
                    title: "Название",
                    dataIndex: "short_name",
                    key: "short_name",
                    scopedSlots: { 
                        customRender: 'short_name'
                    },
                },
                {
                    title: "Печать",
                    dataIndex: "print",
                    key: "print",
                    scopedSlots: { 
                        customRender: 'print'
                    },
                },
                {
                    title: this.type === 'roto' ? 'Гравировщик' : 'Вывод клише',
                    dataIndex: "engraver",
                    key: "engraver",
                    scopedSlots: { 
                        customRender: 'engraver'
                    },
                },
                {
                    title: "Первичка",
                    dataIndex: "primary",
                    key: "primary",
                    scopedSlots: { 
                        customRender: 'primary'
                    },
                },
                {
                    title: "Вторичка",
                    dataIndex: "secondary_housing",
                    key: "secondary_housing",
                    scopedSlots: { 
                        customRender: 'secondary_housing'
                    },
                },
                {
                    title: "Серия красок",
                    dataIndex: "paint_series",
                    key: "paint_series",
                    scopedSlots: { 
                        customRender: 'paint_series'
                    },
                },
                {
                    dataIndex: "details",
                    key: "details",
                    scopedSlots: { 
                        customRender: 'details'
                    },
                },
            ],
        };
    },

    created() {

    },

    methods: {
        async addNewProfile() {
            await this.$store.dispatch('addNewProfile',this.newProfile);
            this.showModalAdd = false;   
        },
    },
};
</script>

<style scoped>
.add_profile_input {
    margin-top: 15px;
}
</style>