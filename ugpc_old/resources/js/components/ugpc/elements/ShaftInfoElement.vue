<template>
    <div class="shaft_info-wrapper">
        <div style="display: flex;">
            <div class="shaft_info-block" v-for="info in Info" :key="info.title">
                <span class="info_title">{{ info.title }}:</span>
                <span class="info_value" v-if="info.title != 'Вал'">{{ info.value }}</span>
                <a target="_blank"  class="info_value" v-if="info.title == 'Вал'" :href="'https://okvid.danaflex.ru/ugpc/engravingorders/order/'+shaftInfo.id">{{ info.value }}</a>
            </div>
        </div>
        <a-dropdown :placement="'bottomRight'">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;" @click="e => e.preventDefault()"> 
                <mask id="mask0_1294_11427" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                <rect width="24" height="24" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask0_1294_11427)">
                <path d="M12 19.2688C11.5875 19.2688 11.2344 19.122 10.9406 18.8282C10.6469 18.5345 10.5 18.1814 10.5 17.7689C10.5 17.3564 10.6469 17.0033 10.9406 16.7095C11.2344 16.4158 11.5875 16.2689 12 16.2689C12.4125 16.2689 12.7656 16.4158 13.0593 16.7095C13.3531 17.0033 13.5 17.3564 13.5 17.7689C13.5 18.1814 13.3531 18.5345 13.0593 18.8282C12.7656 19.122 12.4125 19.2688 12 19.2688ZM12 13.4996C11.5875 13.4996 11.2344 13.3527 10.9406 13.059C10.6469 12.7652 10.5 12.4121 10.5 11.9996C10.5 11.5872 10.6469 11.234 10.9406 10.9403C11.2344 10.6465 11.5875 10.4997 12 10.4997C12.4125 10.4997 12.7656 10.6465 13.0593 10.9403C13.3531 11.234 13.5 11.5872 13.5 11.9996C13.5 12.4121 13.3531 12.7652 13.0593 13.059C12.7656 13.3527 12.4125 13.4996 12 13.4996ZM12 7.73039C11.5875 7.73039 11.2344 7.58352 10.9406 7.28977C10.6469 6.99604 10.5 6.64292 10.5 6.23042C10.5 5.81794 10.6469 5.46482 10.9406 5.17107C11.2344 4.87734 11.5875 4.73047 12 4.73047C12.4125 4.73047 12.7656 4.87734 13.0593 5.17107C13.3531 5.46482 13.5 5.81794 13.5 6.23042C13.5 6.64292 13.3531 6.99604 13.0593 7.28977C12.7656 7.58352 12.4125 7.73039 12 7.73039Z" fill="#4A9DFF"/>
                </g>
            </svg>
            <a-menu slot="overlay">
                <a-menu-item>
                    <a href="javascript:;" @click="visibleDefectShaft = true">Вал на рассмотрени (брак)</a>
                </a-menu-item>
                <a-menu-item>
                    <a href="javascript:;" v-if="stagesArr.includes(currentOperation.operation_id)" @click="confirmAddPreCopperPlating()">Отправить вал на обезжиривание и пред. меднение</a>
                </a-menu-item>
            </a-menu>
        </a-dropdown>
        <a-modal
            title="Вал на рассмотрение"
            :visible="visibleDefectShaft"
            @cancel="visibleDefectShaft = false"
            >
            <template slot="footer">
                <a-button key="delete_operation" type="primary" @click="confirmDefectShaft()">
                На рассмотрение
                </a-button>
            </template>
            <a-textarea
                v-model="shaftInfo.active_engraving_order_stage.active_engraving_stage.defect_note"
                placeholder="Напишите причину"
                :auto-size="{ minRows: 3, maxRows: 5 }"
            />
        </a-modal>
    </div>
</template>

<script>
export default {

    components: {

    },
    props: {
        shaftInfo: Object,
        currentOperation: Object,
    },
    data() {
        return {    
            visibleDefectShaft: false,
            stagesArr: [8,9,10,11],
        };
    },

    methods: {
        
        async confirmDefectShaft() {
            const confirmOptions = {
                title: 'Отправить вал в брак?',
                content: 'Вы больше не сможете добавить операции для данного вала, завершить работу с валом?',
                okText: 'Да',
                cancelText: 'Нет',
                onOk: () => {
                    this.defectShaft();
                },
                onCancel() {
                    
                },
            };

            if (!this.currentOperation.operation.params.every(param => param.value)) {
                alert('Заполните параметры');
            } else {
                this.$confirm(confirmOptions);
            }
        },

        async defectShaft() {
            await this.$store.dispatch('defectShaft');

            this.visibleDefectShaft = false;
        },  

        async confirmAddPreCopperPlating() {
            const confirmOptions = {
                title: 'Отправить вал на пред. меднение?',
                content: 'После завершения операции вал будет отправлен на пред. меднение',
                okText: 'Да',
                cancelText: 'Нет',
                onOk: () => {
                    this.addPreCopperPlating();
                },
                onCancel() {
                    
                },
            };


            this.$confirm(confirmOptions);
            
        },

        async addPreCopperPlating() {
            if (this.currentOperation.operation_id == 8) {
                localStorage.preCopperOrder = this.currentOperation.engraving_order_id;
                this.$store.dispatch('setPreCopper', true);
            } else {
                await this.$store.dispatch('addPreCopperPlating');
            }
        },  
    },

    computed: {
        Info() {
            return [
                { title: 'Вал', value: this.shaftInfo.shaft.shaft_id },
                { title: 'Оквид', value: this.shaftInfo.design_order.okvid_number},
                { title: 'Заказ', value: this.shaftInfo.design_order.prod_order},
                { title: 'Формат', value: this.shaftInfo.format},
                { title: 'Проход', value: this.shaftInfo.active_engraving_order_stage.stage_number},
            ];
        },
    },

};
</script>

<style scoped>
.shaft_info-wrapper {
    display: flex;
    justify-content: space-between;
}
</style>