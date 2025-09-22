<template>
    <div class="wrapper">
        <span class="title">Заявки на перемещение</span>        
        <a-button :href="'https://okvid.danaflex.ru/ugpc/sendrequestshaft/addrequest'" class="add_btn" type="primary">Создать заявку</a-button>
        <div class="applications_list_wrapper">
            <div class="nav-bar">
                <a-input-search
                    style=" width: 200px;"
                    :allow-clear="true"
                    v-model="searchText"
                />
                <button class="export_excel_btn" @click="exportExcel" style="margin-left: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24" viewBox="0 0 23 24" fill="none">
                        <g clip-path="url(#clip0_1631_4544)">
                        <path d="M20.3742 12.8023H16.7163V10.9302H20.3742V12.8023ZM20.3742 13.8721H16.7163V15.7442H20.3742V13.8721ZM20.3742 5.0465H16.7163V6.91858H20.3742V5.04655V5.0465ZM20.3742 7.98836H16.7163V9.86044H20.3742V7.98836ZM20.3742 16.8139H16.7163V18.6861H20.3742V16.8139V16.8139ZM22.9007 20.5047C22.7962 21.061 22.143 21.0743 21.7066 21.0931H13.581V23.5H11.9585L0 21.3604V2.64223L12.0291 0.5H13.581V2.63151H21.4271C21.8687 2.65023 22.3546 2.61813 22.7387 2.88823C23.0078 3.28405 22.9817 3.78686 23 4.24149L22.9895 18.1645C22.9765 18.9428 23.0601 19.7371 22.9007 20.5047ZM9.58089 16.3085C8.8598 14.8109 8.12565 13.3238 7.40712 11.8261C8.11778 10.3686 8.81803 8.90571 9.51562 7.44278C8.92251 7.47221 8.32941 7.50964 7.73896 7.55245C7.29738 8.65161 6.78266 9.72139 6.4378 10.858C6.11643 9.78558 5.69057 8.75323 5.30127 7.70755C4.72645 7.73964 4.15163 7.77441 3.57686 7.80917C4.18299 9.17852 4.82838 10.529 5.41623 11.9064C4.72385 13.2436 4.0759 14.5996 3.4044 15.9448C3.97657 15.9688 4.54879 15.9929 5.12096 16.0009C5.52858 14.9365 6.03543 13.9122 6.39076 12.8264C6.70953 13.9925 7.25034 15.0702 7.69453 16.1855C8.3242 16.231 8.95126 16.2711 9.58093 16.3085H9.58089ZM21.7539 3.90168H13.581V5.0465H15.6713V6.91858H13.581V7.98836H15.6713V9.86044H13.581V10.9302H15.6713V12.8023H13.581V13.8721H15.6713V15.7442H13.581V16.8139H15.6713V18.6861H13.581V19.9228H21.7539V3.90168Z" fill="#13AA19"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_1631_4544">
                        <rect width="23" height="23" fill="white" transform="translate(0 0.5)"/>
                        </clipPath>
                        </defs>
                    </svg>
                </button>
            </div>
            <a-table
                :columns="columns_applications"
                :data-source="filteredData"
            >      
                <div
                    slot="filterDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
                    style="padding: 8px"
                    >
                    <a-input
                        v-ant-ref="c => (searchInput = c)"
                        :placeholder="`Search ${column.dataIndex}`"
                        :value="selectedKeys[0]"
                        style="width: 188px; margin-bottom: 8px; display: block;"
                        @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
                        @pressEnter="() => handleSearch(selectedKeys, confirm, column.dataIndex)"
                    />
                    <a-button
                        type="primary"
                        icon="search"
                        size="small"
                        style="width: 90px; margin-right: 8px"
                        @click="() => handleSearch(selectedKeys, confirm, column.dataIndex)"
                    >
                        Search
                    </a-button>
                    <a-button size="small" style="width: 90px" @click="() => handleReset(clearFilters)">
                        Reset
                    </a-button>
                </div>
                <span slot="document_number" slot-scope="text, record" style="font-weight: 600;">{{ text }}</span>
                <span @click="showApplicationCard(record)" slot="shipping_date" slot-scope="text, record">{{ formatDate(text) }}</span>
                <span slot="status" slot-scope="text, record"><span style="color: green" v-if="record.post == false">Открыто</span><span style="color: red" v-if="record.post == true">Учтено</span></span>
                <a :href="'https://okvid.danaflex.ru/ugpc/sendrequestshaftcard/'+record.id" slot="show" slot-scope="text, record">Открыть</a>
            </a-table>
        </div>
    </div>
</template>



<script>
import { Excel } from "antd-table-saveas-excel";
export default {
    components: {
        
    },
    props: {
        transfers: Array,
    },
    data() {
        return {
            searchText: '',
            senderFilter: null,
            recipientFilter: null,
            Transfers: [...this.transfers],
            columns_applications: [
                {
                title: "№ Заявки",
                dataIndex: "document_number",
                key: "document_number",
                scopedSlots: { 
                    customRender: 'document_number',
                },
                },
                {
                title: "Дата отгрузки",
                dataIndex: "shipping_date",
                key: "shipping_date",
                scopedSlots: { customRender: 'shipping_date' },
                },
                {
                title: "Отправитель",
                dataIndex: "sender",
                key: "sender",
                scopedSlots: { 
                    customRender: 'sender',
                    filterDropdown: 'filterDropdown',
                    filterIcon: 'filterIcon',
                },
                onFilter: (value, record) => {
                    this.senderFilter = value;
                    return record.sender.toString().toLowerCase().includes(value.toLowerCase());
                },
                onFilterDropdownVisibleChange: visible => {
                    if (visible) {
                    setTimeout(() => {
                        this.searchInput.focus();
                    });
                    }
                },
                },
                {
                title: "Получатель",
                dataIndex: "recipient",
                key: "recipient",
                scopedSlots: { 
                    customRender: 'recipient',
                    filterDropdown: 'filterDropdown',
                    filterIcon: 'filterIcon', 
                },
                onFilter: (value, record) => {
                    this.recipientFilter = value;
                    return record.recipient.toString().toLowerCase().includes(value.toLowerCase());
                },
                onFilterDropdownVisibleChange: visible => {
                    if (visible) {
                    setTimeout(() => {
                        this.searchInput.focus();
                    });
                    }
                },
                },
                {
                title: "Статус",
                dataIndex: "status",
                key: "status",
                scopedSlots: { customRender: 'status' },
                },
                {
                title: "",
                dataIndex: "show",
                key: "show",
                scopedSlots: { customRender: 'show' },
                },

            ],
        };
    },
    filters: {

    },
    mounted() {
        
    },
    computed: {
        filteredData() {
            return this.Transfers.filter(item => {
                return Object.values(item).some(value =>
                String(value).toLowerCase().includes(this.searchText.toLowerCase())
                );
            });
        },
    },
    methods: {

        handleSearch(selectedKeys, confirm, dataIndex) {
            confirm();
            this.searchText = selectedKeys[0];
            this.searchedColumn = dataIndex;
        },

        handleReset(clearFilters) {
            clearFilters();
            this.searchText = '';
        },

        exportExcel() {
            const filteredData = this.Transfers.filter((transfer) => {
                const senderFilter = !this.senderFilter || transfer.sender.toLowerCase().includes(this.senderFilter.toLowerCase());
                const recipientFilter = !this.recipientFilter || transfer.recipient.toLowerCase().includes(this.recipientFilter.toLowerCase());
                return senderFilter && recipientFilter;
            });

            const excel = new Excel();
            excel
            .addSheet("1")
            .addColumns(this.columns_applications)
            .addDataSource(filteredData, {
                str2Percent: true
            })
            .saveAs("Excel.xlsx");
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
.export_excel_btn {
    border-radius: 10px;
    background: var(--bg, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    padding: 8px;
    border: 0;
}
.nav-bar {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
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
