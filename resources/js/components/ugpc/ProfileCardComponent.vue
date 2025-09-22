<template>
    <div class="profile_wrapper">
        <div class="btn_row" style="margin-bottom: 20px; display: flex;justify-content: flex-start;">
            <a-button type="primary" @click="sendDataToNav()">
                Отправить в Navision
            </a-button>
            <a-button style="margin-left: 20px;" @click="showCopyModal = true" type="primary">
                Копировать
            </a-button>
        </div>
        <a-modal 
            v-model="showCopyModal" 
            title="Введите номер профиля"
        >
            <a-input class="add_profile_input" addon-before="Номер профиля" placeholder="Введите номер" v-model="copyProfileNumber" />
            <template slot="footer">
                <a-button key="submit" type="primary" @click="copyProfile()">
                Копировать
                </a-button>
            </template>
        </a-modal>
        <div class="info_profile_block">
            <a-input class="info_profile_input" addon-before="Краткое название" placeholder="Введите название" v-model="infoProfile.short_name" />
            <a-input class="info_profile_input" addon-before="Аналог поставщика" placeholder="Введите название" v-model="infoProfile.supplier_analog_icc" />
            <a-input class="info_profile_input" addon-before="Печать" placeholder="Введите название" v-model="infoProfile.print" />
            <a-input class="info_profile_input" v-if="this.profile.type === 'roto'" addon-before="Гравировщик" placeholder="Введите название" v-model="infoProfile.engraver" />
            <a-input class="info_profile_input" v-if="this.profile.type === 'flexo'" addon-before="Вывод клише" placeholder="Введите название" v-model="infoProfile.engraver" />
            <a-input class="info_profile_input" addon-before="Первичка" placeholder="Введите название" v-model="infoProfile.primary" />
            <a-input class="info_profile_input" addon-before="Вторичка" placeholder="Введите название" v-model="infoProfile.secondary_housing" />
            <a-input class="info_profile_input" addon-before="Серия красок" placeholder="Введите название" v-model="infoProfile.paint_series" />
        </div>
        <div class="fingerprint_param_block">
            <FingerPrintParamBlock v-if="profile" :profile="infoProfile"/>
        </div>
        <div class="printing_param_block">
            <PrintingProcessParamBlock v-if="profile" :profile="infoProfile"/>
        </div>
    </div>
</template>
    
    
    
<script>
import { mapGetters } from 'vuex'
import FingerPrintParamBlock from "./blocks/FingerPrintParamBlock.vue";
import PrintingProcessParamBlock from "./blocks/PrintingProcessParamBlock.vue";
export default {  
    components: {
        PrintingProcessParamBlock,
        FingerPrintParamBlock,
    },
    props: {
        profile: Object,
    },
    data() {
        return {
            showCopyModal: false,
            copyProfileNumber: null,
        };
    },

    created() {
          this.setProfile();
    },

    computed: {
        ...mapGetters({
            infoProfile: 'getProfile',
        }),
    },

    methods: {
        async setProfile() {
            await this.$store.dispatch('setProfile',this.profile);
        },

        async sendDataToNav() {
            await this.$store.dispatch('sendDataToNav');
            this.$success({
                    title: 'Профиль сохранен',
                });    
        },

        async copyProfile() {
            if (this.copyProfileNumber) {
                await this.$store.dispatch('copyProfile',this.copyProfileNumber);
                this.showCopyModal = false;
                this.$success({
                        title: 'Профиль скопирован',
                    });    
            } else {
                alert('Введите номер');
            }
        }
    },
    
};
</script>

<style>
.profile_wrapper {
    background: white;
    padding: 20px;
    border-radius: 10px;
}

.info_profile_block {
    margin-top: 15px;
    display: flex;
    flex-wrap: wrap;
}

.info_profile_input {
    margin-top: 10px;
    margin-right: 20px;
    width: 30%;
}

.fingerprint_param_block {
    margin-top: 20px;
}

.printing_param_block {
    margin-top: 20px;
}

</style>
    