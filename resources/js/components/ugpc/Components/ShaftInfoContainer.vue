<template>
    <div class="info-shaft_wrapper">
        <div class="shaft_info_row_top">
            <div class="">
            <span class="shaft_id_title">Вал № {{this.selectConsumpShaft.shaft_id}}</span>
            <span class="okvid_number_title">Заказ:</span>
            <span class="okvid_number_value">{{this.selectConsumpShaft.okvid_number}}</span>
            <span class="format_title">Формат:</span>
            <span class="format_value">{{selectConsumpShaft.shaft_format}}</span>
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
                        <a href="javascript:;" @click="showBrakShaft" >Вал на рассмотрени (брак)</a>
                    </a-menu-item>
                </a-menu>
            </a-dropdown>
            <a-modal
                title="Вал на рассмотрение"
                :visible="visibleBrakShaft"
                @cancel="handleBrakShaft"
                >
                <template slot="footer">
                    <a-button key="delete_operation" type="primary" @click="brakShaft(noteBrakShaft)">
                    На рассмотрение
                    </a-button>
                </template>
                <a-textarea
                    v-model="noteBrakShaft"
                    placeholder="Напишите причину"
                    :auto-size="{ minRows: 3, maxRows: 5 }"
                />
            </a-modal>
        </div>
        <div class="shaft_info_row">
            <div class="shaft_info_block">
                <span class="block_title">Угол резца:</span>
                <span class="block_value">{{selectConsumpShaft.engraving_degree_plan}}</span>
            </div>
            <div class="shaft_info_block">
                <span class="block_title">Линиатура:</span>
                <span class="block_value">{{selectConsumpShaft.engraving_liniatura_plan}}</span>
            </div>
            <div class="shaft_info_block">
                <span class="block_title">Угол растра:</span>
                <span class="block_value">{{selectConsumpShaft.engraving_number_plan}}</span>
            </div>
            <div class="shaft_info_block">
                <span class="block_title">Цвет:</span>
                <span class="block_value">{{selectConsumpShaft.test_print_color_plan}}</span>
            </div>
            <div class="shaft_info_block">
                <span class="block_title">№ сепарации:</span>
                <span class="block_value">{{selectConsumpShaft.engraving_separation_plan}}</span>
            </div>
        </div>
        <div class="shaft_info_row">
            <div v-if="selectConsumpShaft.note" class="note_block">
                <span class="block_title">Примечание:</span>
                <span class="block_note_value">{{selectConsumpShaft.note}}</span>
            </div>
            <div v-if="selectConsumpShaft.engraving_note" class="note_engraving_block">
                <span class="block_title">Для гравировщика:</span>
                <span class="block_note_value">{{selectConsumpShaft.engraving_note}}</span>
            </div>
        </div>
    </div>
</template>

<script>

export default {

  props: {
        selectConsumpShaft: Object,
        brakShaft: Function,
    },
    data() {
        return {
            visibleBrakShaft: false,
            noteBrakShaft: '',
        };
    },
    methods: {
        callbrakShaft() {
            if (this.brakShaft) {
                this.brakShaft(noteBrakShaft);
            }
        },

        showBrakShaft() {
            this.visibleBrakShaft = true;
        },

        handleBrakShaft() {
            this.visibleBrakShaft = false;
        },
    },

    computed: {

    }
};
</script>

<style scoped>
.shaft_id_title {
    color: var(--txt, #363F51);
    font-size: 20px;
    font-family: Open Sans;
    font-weight: 600;
    margin-right: 20px;
}

.okvid_number_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 10px;
}

.format_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 10px;
}

.okvid_number_value {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 20px;
}

.format_value {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 20px;
}

.info-shaft_wrapper {
    padding: 20px 0 0 0;
}

.shaft_info_row {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
}

.shaft_info_row_top {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
}

.shaft_info_block {
    display: flex;
    padding: 10px;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    width: 18%;
    align-self: stretch;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
}

.block_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 16px;
    display: block;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.block_value {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 18px;
    font-family: Open Sans;
    font-weight: 600;
    letter-spacing: 0.18px;
}

.note_block {
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    display: block;
    padding: 10px;
    width: 100%;

}

.note_engraving_block {
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    display: block;
    padding: 10px;

}
</style>