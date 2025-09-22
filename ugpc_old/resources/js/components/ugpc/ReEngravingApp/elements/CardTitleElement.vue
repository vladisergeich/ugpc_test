<template>
    <div class="title-container">
        <div class="title_line">
            <div class="line_block">
                <div class="order_elem">
                    <span>Заказ:</span>
                    <span>{{ app.order_number }}</span>
                </div>
                <div class="element">
                    <a-tooltip placement="rightTop">
                        <template slot="title">
                        <span>{{ app.novizna }}</span>
                        </template>
                        <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.25 14.25H10.75V8.5H9.25V14.25ZM10 6.7885C10.2288 6.7885 10.4207 6.71108 10.5755 6.55625C10.7303 6.40142 10.8077 6.20958 10.8077 5.98075C10.8077 5.75192 10.7303 5.56008 10.5755 5.40525C10.4207 5.25058 10.2288 5.17325 10 5.17325C9.77117 5.17325 9.57933 5.25058 9.4245 5.40525C9.26967 5.56008 9.19225 5.75192 9.19225 5.98075C9.19225 6.20958 9.26967 6.40142 9.4245 6.55625C9.57933 6.71108 9.77117 6.7885 10 6.7885ZM10.0017 19C8.68775 19 7.45267 18.7507 6.2965 18.252C5.14033 17.7533 4.13467 17.0766 3.2795 16.2218C2.42433 15.3669 1.74725 14.3617 1.24825 13.206C0.749417 12.0503 0.5 10.8156 0.5 9.50175C0.5 8.18775 0.749333 6.95267 1.248 5.7965C1.74667 4.64033 2.42342 3.63467 3.27825 2.7795C4.13308 1.92433 5.13833 1.24725 6.294 0.74825C7.44967 0.249417 8.68442 0 9.99825 0C11.3123 0 12.5473 0.249333 13.7035 0.748C14.8597 1.24667 15.8653 1.92342 16.7205 2.77825C17.5757 3.63308 18.2528 4.63833 18.7518 5.794C19.2506 6.94967 19.5 8.18442 19.5 9.49825C19.5 10.8123 19.2507 12.0473 18.752 13.2035C18.2533 14.3597 17.5766 15.3653 16.7218 16.2205C15.8669 17.0757 14.8617 17.7528 13.706 18.2518C12.5503 18.7506 11.3156 19 10.0017 19ZM10 17.5C12.2333 17.5 14.125 16.725 15.675 15.175C17.225 13.625 18 11.7333 18 9.5C18 7.26667 17.225 5.375 15.675 3.825C14.125 2.275 12.2333 1.5 10 1.5C7.76667 1.5 5.875 2.275 4.325 3.825C2.775 5.375 2 7.26667 2 9.5C2 11.7333 2.775 13.625 4.325 15.175C5.875 16.725 7.76667 17.5 10 17.5Z" fill="#9A9A9A"/>
                        </svg>
                    </a-tooltip>
                </div>
                <div class="change_order" v-if="app.active_stage && app.active_stage.approv_stage.id == 2 && change" @click="showChange = true">
                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.609628 14.459C0.530658 14.4599 0.452305 14.4451 0.379077 14.4156C0.305849 14.3861 0.239191 14.3424 0.18294 14.287C0.118447 14.2234 0.0688234 14.1464 0.0376166 14.0614C0.00640987 13.9765 -0.00560954 13.8857 0.00241768 13.7956L0.25679 10.6501C0.267698 10.5041 0.331934 10.3671 0.437312 10.2652L9.51265 1.20572C10.0183 0.733014 10.6911 0.479696 11.3835 0.501274C12.0714 0.506602 12.7315 0.77311 13.2298 1.24667C13.7076 1.73651 13.9824 2.38848 13.9992 3.07201C14.0159 3.75554 13.7733 4.42015 13.32 4.9327L4.23648 14.0331C4.13482 14.1342 4.0017 14.1978 3.85903 14.2133L0.667067 14.5L0.609628 14.459ZM1.43018 10.9696L1.25787 13.214L3.50619 13.0092L12.4502 4.06444C12.6246 3.85841 12.7361 3.60674 12.7714 3.33933C12.8067 3.07193 12.7644 2.80002 12.6495 2.55591C12.5346 2.3118 12.3519 2.10575 12.1231 1.96224C11.8943 1.81873 11.6291 1.7438 11.3589 1.74633C11.1813 1.73508 11.0033 1.75975 10.8355 1.81887C10.6677 1.87798 10.5136 1.97033 10.3824 2.09036L1.43018 10.9696Z" fill="#04BA0B"/>
                    </svg>
                    <span>Изменить номер заказа</span>
                </div>
                <a-modal
                    title="Изменить текущий номер заказа"
                    :visible="showChange"
                    @cancel="showChange = false"
                    >
                    <template slot="footer">
                        <a-button key="cancel_delete_operation" type="default" @click="showChange = false">
                        Отмена
                        </a-button>
                        <a-button key="delete_operation" type="primary" @click="changeOrder()">
                        Сохранить
                        </a-button>
                    </template>
                    <a-input placeholder="Введите новый номер заказа" v-model="order"></a-input>
                </a-modal>
                <div class="element">
                    <span>Оквид:</span>
                    <span>{{ app.macro_order_id }}</span>
                </div>
            </div>
            <div class="line_block">
                <span style="margin-right: 20px;">{{ formatDate(app.start_date) }}</span>
                <span class="app_status" :class="{ 'active': app.status == 'Идет согласование', 'finish': app.status == 'Выполнено' , 'error' : app.status == 'Не согласовано' || app.status == 'Ожидание заказа' || app.status == 'Гравировка не согласована'}">{{ app.status }}</span>
            </div>
        </div>
        <div class="title_line">
            <div class="line_block">
                <span class="element">{{ app.customer_desc }}</span>
                <span>{{ app.customer_number }}</span>
                <div class="print_date-block">
                    <span>Дата печати:</span>
                    <span>{{ formatDate(app.print_date) }}</span>
                </div>
            </div>
        </div>
        <div class="title_line">
            <div class="element">
                <span>{{ app.order_desc }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    props: {
        application: Object,
        change: Boolean,
    },
    data() {
        return {
            showChange: false,
            order: null,
            app: this.application,
        };
    },

    created() {

    },

    methods: {
        changeOrder() {
            let order = this.app.order_number;
            let newOrder = this.order;
            this.$confirm({
                title: 'Изменить номер заказа c'+' '+order+' '+'на'+' '+newOrder,
                okText: 'Да',
                cancelText: 'Нет',
                onOk: () => {
                    axios
                        .post(route('ugpc.reengravingapps.changeorder'),{app: this.application, order: this.order})
                        .then(response => {
                            this.app = response.data;
                            this.showChange = false;
                        });
                },
                onCancel() {
                
                },
            });
        },

        formatDate(date) {
            const validDate = new Date(date);

            const day = String(validDate.getDate()).padStart(2, '0');
            const month = String(validDate.getMonth() + 1).padStart(2, '0');
            const year = validDate.getFullYear();
            return `${day}.${month}.${year}`;
        }
    },

    computed: {

    }
};
</script>

<style scoped>
.app_status {
    padding: 3px 8px 3px 8px;
    border-radius: 100px;
}


.active {
    background: #F3F9FF;
    color: #4A9DFF;
}

.finish {
    background: #EFFFE2;
    color: #04BA0B;
}

.error {
    background: #FFEBEB;
    color: #E31212;
}

.change_order {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    color: #04BA0B;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
}
.title_line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    align-items: center;
}

.line_block {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    color: #4B5563;

}

.order_elem {
    font-family: "Open Sans";
    font-size: 20px;
    font-weight: 600;
    color: #363F51;
}

.element {
    font-family: "Open Sans";
    font-size: 16px;
    font-weight: 400;
}

.print_date-block {
    padding: 1px 8px 1px 8px;
    border-radius: 2px;
    border: 1px solid #D9D9D9;
    background: #FAFAFA;
    color: #000000D9;
    font-family: Open Sans;
    font-size: 14px;
    font-weight: 400;
}
</style>