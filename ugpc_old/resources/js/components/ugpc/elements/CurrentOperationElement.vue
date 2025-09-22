<template>
    <div class="current_operation-wrapper">
        <div class="current_operation_info">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_1144_5589" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                <rect width="24" height="24" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask0_1144_5589)">
                <path d="M15.4731 16.5269L16.5269 15.4731L12.75 11.6959V6.99998H11.25V12.3038L15.4731 16.5269ZM12.0016 21.5C10.6877 21.5 9.45268 21.2506 8.29655 20.752C7.1404 20.2533 6.13472 19.5765 5.2795 18.7217C4.42427 17.8669 3.74721 16.8616 3.24833 15.706C2.74944 14.5504 2.5 13.3156 2.5 12.0017C2.5 10.6877 2.74933 9.45268 3.248 8.29655C3.74667 7.1404 4.42342 6.13472 5.27825 5.2795C6.1331 4.42427 7.13834 3.74721 8.29398 3.24833C9.44959 2.74944 10.6844 2.5 11.9983 2.5C13.3122 2.5 14.5473 2.74933 15.7034 3.248C16.8596 3.74667 17.8652 4.42342 18.7205 5.27825C19.5757 6.1331 20.2527 7.13834 20.7516 8.29398C21.2505 9.44959 21.5 10.6844 21.5 11.9983C21.5 13.3122 21.2506 14.5473 20.752 15.7034C20.2533 16.8596 19.5765 17.8652 18.7217 18.7205C17.8669 19.5757 16.8616 20.2527 15.706 20.7516C14.5504 21.2505 13.3156 21.5 12.0016 21.5ZM12 20C14.2166 20 16.1041 19.2208 17.6625 17.6625C19.2208 16.1041 20 14.2166 20 12C20 9.78331 19.2208 7.89581 17.6625 6.33748C16.1041 4.77914 14.2166 3.99998 12 3.99998C9.78331 3.99998 7.89581 4.77914 6.33748 6.33748C4.77914 7.89581 3.99998 9.78331 3.99998 12C3.99998 14.2166 4.77914 16.1041 6.33748 17.6625C7.89581 19.2208 9.78331 20 12 20Z" fill="#4A9DFF"/>
                </g>
            </svg>
            <span class="current_operation_title">Текущая операция:</span>
            <span class="current_operation">{{currentOperation.operation.description}}</span>
            <svg @click="visibleDeleteOperation = true"  class="delete_operation_btn" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_1144_5594" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="25">
                <rect y="0.5" width="24" height="24" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask0_1144_5594)">
                <path d="M7.3077 20.9998C6.80898 20.9998 6.38302 20.8232 6.02982 20.47C5.67661 20.1168 5.5 19.6908 5.5 19.1921V6.49981H4.5V4.99983H8.99997V4.11523H15V4.99983H19.5V6.49981H18.5V19.1921C18.5 19.6972 18.325 20.1248 17.975 20.4748C17.625 20.8248 17.1974 20.9998 16.6922 20.9998H7.3077ZM17 6.49981H6.99997V19.1921C6.99997 19.2818 7.02883 19.3556 7.08652 19.4133C7.14422 19.471 7.21795 19.4998 7.3077 19.4998H16.6922C16.7692 19.4998 16.8397 19.4678 16.9038 19.4037C16.9679 19.3395 17 19.269 17 19.1921V6.49981ZM9.40385 17.4998H10.9038V8.49981H9.40385V17.4998ZM13.0961 17.4998H14.5961V8.49981H13.0961V17.4998Z" fill="#7B7B7B"/>
                </g>
            </svg>
            <a-modal
                :visible="visibleDeleteOperation"
                @cancel="visibleDeleteOperation = false"
                >
                <template slot="footer">
                    <a-button key="cancel_delete_operation" type="default" @click="visibleDeleteOperation = false">
                    Отмена
                    </a-button>
                    <a-button key="delete_operation" type="primary" @click="deleteOperation()">
                    Удалить
                    </a-button>
                </template>
                <span class="delete_operation_body">Удалить операцию?</span>
            </a-modal>
        </div>
        <a-button class="current_operation-btn_close" @click="confirmCloseOperation()">Завершить</a-button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

    props: {
        currentOperation: Object,
    },
    data() {
        return {
            visibleDeleteOperation: false,
        };
    },

    methods: {
        async deleteOperation() {
            await this.$store.dispatch('deleteOperation');
            localStorage.preCopperOrder = null;
            this.visibleDeleteOperation = false;
        },

        async confirmCloseOperation() {
            const confirmOptions = {
                title: 'Завершить операцию?',
                content: 'Вы больше не сможете добавить операции для данного вала, завершить работу с валом?',
                okText: 'Да',
                cancelText: 'Нет',
                onOk: () => {
                    this.closeOperation();
                },
                onCancel() {
                    
                },
            };

            if (!this.currentOperation.operation.params.every(param => param.value)) {
                alert('Заполните параметры');
            } else {
                //if (consumpShaft.engraving_time) {
                    this.$confirm(confirmOptions);
                //} else {
                    //alert('Заполните время гравировки в плане!');
                //}
            }
        },

        async closeOperation() {
            if (localStorage.preCopperOrder != 'null') {
                await this.$store.dispatch('addPreCopperPlating');
                this.$store.dispatch('setPreCopper', false);
                localStorage.preCopperOrder = null;
            } else {
                await this.$store.dispatch('closeOperation');
            }
        }
    },

    computed: {
        ...mapGetters({
            consumpShaft: 'getConsumpShaft',
        }),
    },

};
</script>

<style scoped>
.current_operation-btn_close {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-left: 20px;
}

.current_operation-wrapper {
    margin-top: 20px;
    display: flex;
    align-items: center;
}

.current_operation_info {
    display: flex;
    padding: 15px 20px;
    justify-content: flex-end;
    align-items: flex-end;
    gap: 20px;
    flex: 1 0 0;
    border-radius: 20px;
    background: var(--primary-0, #F3F9FF);
    margin-right: 20px;
}

.current_operation_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.18px;
}

.current_operation {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.18px;
}

.delete_operation_btn {
    margin-left: auto;
    cursor: pointer;
}
</style>