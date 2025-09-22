<template>
    <div class="wrapper">
        <span class="title">Заявка на изготовление стальной заготовки</span>    
        <template v-if="!showAppCard">
            <a-button @click="addApp" class="add_btn" type="primary">Создать заявку</a-button>
            <div class="applications_list_wrapper">
            <a-input-search
                style=" width: 200px; margin-bottom: 20px; "
                :allow-clear="true"
                v-model="searchText"
            />
            <a-table
                :columns="columns_applications"
                :data-source="filteredData"
            >
                <a @click="showApplicationCard(record)" slot="document_date" slot-scope="text, record">{{ formatDate(text) }}</a>
                <a @click="showApplicationCard(record)" slot="vendor" slot-scope="text, record">{{ text}}</a>
                <a @click="showApplicationCard(record)" slot="format" slot-scope="text, record">{{ text}}</a>
                <a-checkbox slot="manufactured" slot-scope="text, record" :checked="record.manufactured"  v-model="record.manufactured" @change="submitApp(record)">
                    
                </a-checkbox>            
            </a-table>
                </div>
        </template>
        <template v-if="showAppCard">
            <div @click="showAppCard = !showAppCard"  style="display: flex; align-items: center; margin-bottom: 20px; cursor: pointer;">
                <a-icon class="icon_back" type="arrow-left" /><span class="button_back">Назад</span>
            </div>
            <applicationcard :application="application" :customers="customers" :urlpostapp="urlpostapp" :urldeletefiles="urldeletefiles" :urluploadfiles="urluploadfiles" :urlsubmitapp="urlsubmitapp" :urldeleteshaft="urldeleteshaft" :urladdshafts="urladdshafts" :formats="formats" :vendors="vendors" :urlgetshaftslist="urlgetshaftslist" :urldeleteallshafts="urldeleteallshafts"></applicationcard>
        </template>
</div>
</template>



<script>
import applicationcard from "./ApplicationCard.vue";
export default {
    components: {
        applicationcard
    },
    props: {
        applications: Array,
        customers: Array,
        urladdapp: String,
        urlgetshaftslist: String,
        formats: Array,
        vendors: Array,
        urldeleteallshafts: String,
        urladdshafts: String,
        urldeleteshaft: String,
        urlsubmitapp: String,
        urluploadfiles: String,
        urldeletefiles: String,
        urlpostapp: String,
    },
    data() {
        return {
            application: {},
            showAppCard: false,
            searchText: '',
            Applications: [...this.applications],
            columns_applications: [
                {
                title: "№ Заявки",
                dataIndex: "document_number",
                key: "document_number",
                },
                {
                title: "Дата заявки",
                dataIndex: "document_date",
                key: "document_date",
                scopedSlots: { customRender: 'document_date' },
                },
                {
                title: "Клиент",
                dataIndex: "customer.description",
                key: "customer.description",
                scopedSlots: { customRender: 'customer.description' },
                },
                {
                title: "Изготовитель",
                dataIndex: "vendor",
                key: "vendor",
                scopedSlots: { customRender: 'vendor' },
                },
                {
                title: "FF",
                dataIndex: "first_composition.ff",
                key: "first_composition.ff",
                scopedSlots: { customRender: 'first_composition.ff' },
                },
                {
                title: "Формат",
                dataIndex: "format",
                key: "format",
                scopedSlots: { customRender: 'format' },
                },
                {
                title: "Кол-во валов",
                dataIndex: "shaft_quantity",
                key: "shaft_quantity",
                scopedSlots: { customRender: 'shaft_quantity' },
                },
                {
                title: "Изготовлено",
                dataIndex: "manufactured",
                key: "manufactured",
                scopedSlots: { customRender: 'manufactured' },
                },

            ],
        };
    },
    filters: {
        okvidNumber(value) {

            if ((value.length >= 2) && (!value.includes('T'))) {
            const lastTwoDigits = value.slice(-2);
            return value.slice(0, -2)+'-'+lastTwoDigits;
            }
        
            return value;
        },

        createDate(value) {

            const dateParts = value.split('-');
            if (dateParts.length === 3) {
                const year = dateParts[0];
                const month = dateParts[1];
                const day = dateParts[2];

                return day + '-' + month + '-' + year;
            }
        
            return value;
        },
    },
    mounted() {
        
    },
    computed: {
        filteredData() {
        return this.Applications.filter(item => {
            return Object.values(item).some(value =>
            String(value).toLowerCase().includes(this.searchText.toLowerCase())
            );
        });
        },
    },
    methods: {
        showApplicationCard(record) {
            this.application = record;
            this.showAppCard = true;
        },

        addApp() {
            axios.post(this.urladdapp)
            .then(response => {
                this.application = response.data;
                this.showAppCard = true;           
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
        },

        submitApp(app) {
            axios.post(this.urlsubmitapp,app)
            .then(response => {
                  
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
        },

        formatDate(text) {
            if (text != null) {
                return new Date(text).toLocaleDateString('ru-RU', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });
            }
        },
    },
};
</script>

<style scoped>
.add_btn {
    display: flex;
    padding: 12px 16px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
    width: 200px;
    font-size: 16px;
}
.wrapper {
    display: flex;
    flex-direction: column;
}
.title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 24px;
    margin-bottom: 30px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}
.applications_list_wrapper {
    margin-top: 20px;
    padding: 20px;
    position: relative;
    border-radius: 10px;
    background: var(--grayscale-0, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
}
</style>
