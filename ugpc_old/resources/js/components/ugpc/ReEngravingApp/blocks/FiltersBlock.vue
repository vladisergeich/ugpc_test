<template>
    <div class="filters-container">
        <div class="filters_title">
            <svg style="margin-right: 15px;" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.434 19.3588C13.5505 19.3017 13.6487 19.213 13.7175 19.1029C13.7863 18.9929 13.8229 18.8657 13.8232 18.7359V14.9915C13.8232 14.8071 13.8971 14.6298 14.0294 14.4989L18.3376 10.2379C18.468 10.1091 18.5717 9.95566 18.6425 9.78652C18.7133 9.61738 18.7498 9.43588 18.75 9.25253V7.45354C18.7496 7.36155 18.7311 7.27053 18.6955 7.1857C18.6599 7.10087 18.608 7.02389 18.5426 6.95917C18.4772 6.89445 18.3997 6.84325 18.3145 6.80852C18.2294 6.77379 18.1382 6.75619 18.0462 6.75675H6.78491C6.39569 6.75675 6.08108 7.06784 6.08108 7.45354V9.25253C6.08108 9.62204 6.22959 9.97677 6.49352 10.2379L10.8017 14.4989C10.8669 14.5632 10.9188 14.64 10.9542 14.7245C10.9896 14.8091 11.0078 14.8999 11.0079 14.9915V19.4327C11.0079 19.95 11.5583 20.2865 12.0263 20.0549L13.434 19.3588Z" fill="#4A9DFF"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M22.2973 0.675676H2.7027C1.58321 0.675676 0.675676 1.58321 0.675676 2.7027V22.2973C0.675676 23.4168 1.58321 24.3243 2.7027 24.3243H22.2973C23.4168 24.3243 24.3243 23.4168 24.3243 22.2973V2.7027C24.3243 1.58321 23.4168 0.675676 22.2973 0.675676ZM2.7027 0C1.21004 0 0 1.21004 0 2.7027V22.2973C0 23.79 1.21004 25 2.7027 25H22.2973C23.79 25 25 23.79 25 22.2973V2.7027C25 1.21004 23.79 0 22.2973 0H2.7027Z" fill="#4A9DFF"/>
            </svg>
            <span style="font-size: 16px;">Фильтры</span>  
        </div>
        <div class="filters_line">
            <date-picker
                type="date"
                range
                value-type="format"
                format="YYYY-MM-DD"
                ref="filters_datapicker"
                v-model="filters.filterDate"
            ></date-picker>
        </div>
        <div class="filters_line">
            <span class="filter_title">Площадка</span>
            <a-checkbox-group
            v-model="filters.place"
            name="checkboxgroup"
            :options="places"
            style="display: flex; flex-direction: column;"
            />
        </div>
        <div class="filters_line">
            <span class="filter_title">Клиент</span>
            <a-select v-model="filters.customer" allowClear>
                <a-select-option v-for="customer in this.customers" :key="customer">{{ customer }}</a-select-option>
            </a-select>
        </div>
        <div class="filters_line">
            <span class="filter_title">Статус заявки</span>
            <a-select v-model="filters.status" allowClear>
                <a-select-option v-for="status in this.statuses" :key="status">{{ status }}</a-select-option>
            </a-select>
        </div>
        <div class="filters_line">
            <span class="filter_title">Заказ</span>
            <a-select v-model="filters.order" allowClear>
                <a-select-option v-for="order in this.orders" :key="order">{{ order }}</a-select-option>
            </a-select>
        </div>
        <div class="filters_line">
            <span class="filter_title">Этап согласования</span>
            <a-select v-model="filters.stage" allowClear>
                <a-select-option v-for="stage in this.stages" :key="stage">{{ stage }}</a-select-option>
            </a-select>
        </div>
        <div class="filters_line">
            <span class="filter_title">Причина гравировки</span>
            <a-select v-model="filters.reason" allowClear>
                <a-select-option v-for="reason in this.reasons" :key="reason">{{ reason }}</a-select-option>
            </a-select>
        </div>
        <div class="filters_line">
            <a-button class="filters_btn" @click="changeFilters()">Применить</a-button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    props: {
        customers: Array,
        orders: Array,
        btnClickChangeFilters: Function,
    },
    data() {
        return {
            places: [
                { label: 'ЗАО-Данафлекс', value: 'navZAO' },
                { label: 'Данафлекс-НАНО', value: 'navNANO' },
                { label: 'Данафлекс-АЛАБУГА', value: 'navALABUGA' },
            ],

            stages: ['ОГТ/ОДП','ОП/ОСЗ','ОПЛ','ОКВИД','Координатор'],
            statuses: ['Идет согласование','Согласовано','Не согласовано','Завершена'],
            reasons: ['Износ вала','Мех. повреждение вала','Дефект гравировки','Изменение макета','Изменение параметров гравировки'],
            filters: {
                filterDate: [],
                place: null,
                customer: null,
                order: null,
                stage: null,
                status: null,
                reason: null,
            },
        };
    },

    created() {

    },

    methods: {
        changeFilters() {
            this.btnClickChangeFilters(this.filters);
        }
    },

    computed: {

    }
};
</script>

<style scoped>
.filters_title {
    display: flex;
    align-items: center;
}
.filters-container {
    padding: 20px;
    gap: 16px;
    border-radius: 10px;
    background: #FFFFFF;
}
.filters_line {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}
.filter_title {
    font-family: "Open Sans";
    font-size: 16px;
    font-weight: 600;
    text-align: left;
    color: #363F51;
    margin-bottom: 10px;
}

.filters_btn {
    background: #E5EEFF;
    padding: 10px;
    gap: 7px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #363F51;
}
</style>