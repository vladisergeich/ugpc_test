<template>
<div id="app">
    <h3 style="margin-bottom: 20px;">Маршрутные карты</h3>
    <div class="route_list_wrapper">
    <a-button class="test_route_map__btn" type="primary" ghost @click="showModalTest()" v-if="showTab">Тестовая МК</a-button>
    <a-tabs v-if="showTab">
        <a-tab-pane key="1" tab="На заполнении">
            <template v-if="!show">
                <a-input-search 
                    style="width: 200px; margin-bottom: 10px;"
                    :allow-clear="true"
                    v-model="searchText"
                />
                <a-table
                    :columns="columns_orders"
                    :data-source="filteredData"
                >
                <a-icon
                    slot="filterIcon"
                    slot-scope="filtered"
                    type="search"
                    :style="{ color: filtered ? '#108ee9' : undefined }"
                />
                <a @click="showRouteMapCard(record)" slot="okvid_number" slot-scope="text, record">{{ text | okvidNumber }}</a>
                <a @click="showRouteMapCard(record)" slot="create_document_date" slot-scope="text, record">{{ text | createDate }}</a>
                <a @click="showRouteMapCard(record)" slot="order_number" slot-scope="text, record">{{ text}}</a>
                <a @click="showRouteMapCard(record)" slot="shaft_qty" slot-scope="text, record">{{ text}}</a>
                <a @click="showRouteMapCard(record)" slot="description" slot-scope="text, record">{{ text}}</a>
                <a @click="showRouteMapCard(record)" slot="customer" slot-scope="text, record">{{ text}}</a>
                </a-table>
            </template>
            
            <template v-if="show">
                <template v-if="showCard">
                    <div @click="show = !show" style="    display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
                        <a-icon class="icon_back" type="arrow-left" /><span class="button_back">Назад</span>
                    </div>
                    <span class="route_card_title">Валы к заказу <a>№ {{ orderOkvid | okvidNumber }}</a></span>
                    <a-table
                        :columns="columns_route_map"
                        :data-source="selectRouteMap"
                        style="margin-top: 20px;"
                    >
                    <span  slot="shaft_order_number" slot-scope="text, record, index">{{text}}</span>
                    <template slot="input_control" slot-scope="text, record, index">
                        <span v-if="record.input_control" class="status_completed">Пройден</span>
                        <span v-if="!record.input_control" class="status_work">Ожидает</span>
                    </template>
                    <a target="_blank" @click="showRouteShaftCard(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>
                    <a  target="_blank" @click="showRouteShaftCard(record)" slot="shaft_format" slot-scope="text, record">{{ text }}</a>
                    </a-table>
                    </template>
            </template>
        </a-tab-pane>
        <a-tab-pane key="2" tab="Не в работе" force-render>
                <a-table
                :columns="columns_orders_not_work"
                :data-source="selectOrdersNotWork"
                :pagination="false"
                :scroll="{ y: 400 }"
                >
                <a @click="showRouteShaftCard(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>
                <a @click="showRouteShaftCard(record)" slot="okvid_number" slot-scope="text, record">{{ text | okvidNumber }}</a>
                <span slot="status" slot-scope="text, record, index"><span v-if="!record.post && !record.defect" class="status_in_work">Ожидает</span><span v-if="!record.post && record.defect"  class="status_not_work">На рассмотрении</span></span>
                </a-table>
        </a-tab-pane>
        <a-tab-pane key="3" tab="В работе" force-render>
                <a-table
                :columns="columns_orders_work"
                :data-source="selectOrdersWork"
                :pagination="false"
                :scroll="{ y: 400 }"
                >
                <a @click="showRouteShaftCard(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>
                <a @click="showRouteShaftCard(record)" slot="okvid_number" slot-scope="text, record">{{ text | okvidNumber }}</a>
                <span slot="status" slot-scope="text, record, index"><span v-if="record.post && !record.defect" class="status_in_work">В работе</span><span v-if="record.post && record.defect"  class="status_not_work">На рассмотрении</span></span>
                </a-table>
        </a-tab-pane>
        <a-modal
            title="Тестовая маршрутная карта"
            :visible="visibleModalTest"
            @cancel="handleCancelTest"
            >
            <template slot="footer">
                <a-button key="submit" type="primary" @click="createTestRouteMap(shaft_id_test)">
                Создать
                </a-button>
            </template>
            <label for="">№ Вала</label>
            <a-input placeholder="Введите номер вала" v-model="shaft_id_test" />
            
        </a-modal>
    </a-tabs>
    <template v-if="!showCard">
        <div @click="showCard = !showCard, showTab=!showTab"  style="    display: flex; align-items: center; margin-bottom: 15px; cursor: pointer;">
            <div class="col-lg-12">
                 <div class="row" style="margin-bottom: 20px;">
                    <a-icon class="icon_back" type="arrow-left" /><span class="button_back">Назад</span>
                 </div>
                <div class="row" style="margin-bottom: 20px;">
                    <span class="shaft_title">Вал №: {{this.selectRouteShaft.shaft_id}}</span>
                </div>
                <div class="row" style="margin-bottom: 10px;">
                    <span class="info_title">Заказ:</span> <span class="info_value">{{ this.selectRouteShaft.okvid_number | okvidNumber }}</span>
                    <span class="info_title">Формат:</span> <span class="info_value">{{this.selectRouteShaft.shaft_format}}</span>
                    <span class="info_title">Вид</span><span class="info_value"></span>
                </div>
            </div>
        </div>
        <div v-for="route_operation in route_operations" :key="route_operation">
            <a-collapse style="margin-top: 20px;" >
                <a-collapse-panel key="1" :header="'Этап - ' + route_operation.route_map_number">
                    <routemapshaft :urlskipbrakshaft="urlskipbrakshaft" :urlreplaceshaft="urlreplaceshaft" :urladdnewroutemap="urladdnewroutemap" :route_operation="route_operation" :urlsubmit="urlsubmit" :urlpostroutemap="urlpostroutemap" :urladdtestmap="urladdtestmap" :urldeletemap="urldeletemap"></routemapshaft>
                </a-collapse-panel>
            </a-collapse>
        </div>
    </template>
    </div>
</div>
</template>



<script>
import routemapshaft from "./routeMapShaft.vue";
export default {
    components: {
        routemapshaft
    },
    props: {
        routemapslist: Array,
        routemapslistwork: Array,
        routemapslistnotwork: Array,
        urlsubmit: String,
        urlpostroutemap: String,
        urlcreatetestroutemap: String,
        urladdtestmap: String,
        urldeletemap: String,
        urladdnewroutemap: String,
        urlreplaceshaft: String,
        urlskipbrakshaft: String,

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
        }
    },
  data() {
    return {
        searchText: '',
        searchInput: null,
        searchedColumn: '',
        columns_orders: [
            {
            title: "№ заказа",
            dataIndex: "okvid_number",
            key: "okvid_number",
            slots: { title: 'customTitle' },
            scopedSlots: { 
                customRender: 'okvid_number',
                filterIcon: 'filterIcon',
                filterDropdown: 'filterDropdown',
            },
            onFilter: (value, record) =>
                record.okvid_number
                .toString()
                .toLowerCase()
                .includes(value.toLowerCase()),
            onFilterDropdownVisibleChange: visible => {
                if (visible) {
                setTimeout(() => {
                    this.searchInput.focus();
                }, 0);
                }
            },
            },
            {
            title: "№ Партии",
            dataIndex: "order_number",
            key: "order_number",
            scopedSlots: { 
                customRender: 'order_number', 
                filterIcon: 'filterIcon',
                filterDropdown: 'filterDropdown',
            },
            onFilter: (value, record) =>
                record.okvid_number
                .toString()
                .toLowerCase()
                .includes(value.toLowerCase()),
            onFilterDropdownVisibleChange: visible => {
                if (visible) {
                setTimeout(() => {
                    this.searchInput.focus();
                }, 0);
                }
            },
            },
            {
            title: "Кол-во валов",
            dataIndex: "shaft_qty",
            key: "shaft_qty",
            scopedSlots: { customRender: 'shaft_qty' },
            },
            {
            title: "Наименование",
            dataIndex: "description",
            key: "description",
            scopedSlots: { customRender: 'description' },
            },
            {
            title: "Клиент",
            dataIndex: "customer",
            key: "customer",
            scopedSlots: { 
                customRender: 'customer',
                filterIcon: 'filterIcon',
                filterDropdown: 'filterDropdown', 
            },
            onFilter: (value, record) =>
                record.customer
                .toString()
                .toLowerCase()
                .includes(value.toLowerCase()),
            onFilterDropdownVisibleChange: visible => {
                if (visible) {
                setTimeout(() => {
                    this.searchInput.focus();
                }, 0);
                }
            },
            },
            {
            title: "Дата",
            dataIndex: "create_document_date",
            key: "create_document_date",
            scopedSlots: { customRender: 'create_document_date' },
            },
        ],
        columns_orders_work: [
            {
            title: "№ Вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            scopedSlots: { customRender: 'shaft_id' },
            },
            {
            title: "№ Заказа",
            dataIndex: "okvid_number",
            key: "okvid_number",
            scopedSlots: { customRender: 'okvid_number' },
            },
            {
            title: "Этап",
            dataIndex: "stage",
            key: "stage",
            scopedSlots: { customRender: 'stage' },
            },
            {
                title: 'Статус',
                dataIndex: 'status',
                key: 'status',  
                scopedSlots: { customRender: 'status' },            
            },
        ],
        columns_orders_not_work: [
            {
            title: "№ Вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            scopedSlots: { customRender: 'shaft_id' },
            },
            {
            title: "№ Заказа",
            dataIndex: "okvid_number",
            key: "okvid_number",
            scopedSlots: { customRender: 'okvid_number' },
            },
            {
            title: "Этап",
            dataIndex: "stage",
            key: "stage",
            scopedSlots: { customRender: 'stage' },
            },
            {
                title: 'Статус',
                dataIndex: 'status',
                key: 'status',  
                scopedSlots: { customRender: 'status' },            
            },
        ],
        columns_route_map: [
            {
            dataIndex: "shaft_order_number",
            key: "shaft_order_number",
            scopedSlots: { customRender: 'shaft_order_number' },
            },
            {
            title: "№ вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            scopedSlots: { customRender: 'shaft_id' },
            },
            {
            title: "Формат",
            dataIndex: "shaft_format",
            key: "shaft_format",
            scopedSlots: { customRender: 'shaft_format' },
            },
            {
            title: "Входной контроль",
            dataIndex: "input_control",
            key: "input_control",
            scopedSlots: { customRender: 'input_control' },
            },
        ],
        selectOrdersWork: [...this.routemapslistwork],
        selectOrdersNotWork: [...this.routemapslistnotwork],
        selectRouteMaps: [...this.routemapslist],
        selectRouteMap: [],
        selectRouteShaft: {},
        show: false,
        showCard: true,
        orderOkvid: '',
        route_operations: [],
        visibleModalTest: false,
        shaft_id_test: '',
        showTab: true,
    };
  },
  mounted() {
    
  },
  computed: {
    selectOrders() {
        const grouped = this.selectRouteMaps.reduce((result, item) => {

            const existingGroup = result.find(group => group.okvid_number === item.okvid_number);

            if (existingGroup) {
                existingGroup.shaft_qty++;
            } else {
                result.push({
                    upak_order: item.upak_order,
                    okvid_number: item.okvid_number,
                    order_number: item.order_number,
                    description: item.description,
                    customer: item.customer,
                    create_document_date: item.create_document_date,
                    shaft_qty: 1 
                });
            }
            return result;

        }, []);

        return grouped;
    },

    filteredData() {
      return this.selectOrders.filter(item => {
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
    showModalTest() {
      this.visibleModalTest = true;
    },
    handleCancelTest() {
      this.visibleModalTest = false;
    },
    createTestRouteMap(shaft_id) {
        axios.post(this.urlcreatetestroutemap,{  
         
            shaft: shaft_id, 
        })
        .then(response => {
           this.selectRouteMaps = response.data[0];
           this.show = true;
           this.visibleModalTest = false;
           this.showRouteShaftCard(response.data[1]);
            
        })
        .catch(err => {
            alert('Данного вала не существует');
        });
    },

    showRouteMapCard(record) {
        this.orderOkvid = record.okvid_number;
        const filtered = this.selectRouteMaps.filter(item => item.okvid_number === record.okvid_number);

        const grouped = _.groupBy(filtered, 'shaft_id')

        this.show = true;
        this.selectRouteMap = Object.values(grouped).reduce((result, items) => {
            result.push(items[0]);
            return result;
        }, []);
    },
    showRouteShaftCard(record) {
        
        this.selectRouteShaft = record;
        this.route_operations = this.selectRouteMaps.filter(item => item.okvid_number === record.okvid_number && item.shaft_id === record.shaft_id && item.order_number === record.order_number);

        if (this.route_operations.length == 0) {
            this.route_operations = this.selectOrdersWork.filter(item => item.okvid_number === record.okvid_number && item.shaft_id === record.shaft_id && item.order_number === record.order_number);
        } 

        if (this.route_operations.length == 0) {
            this.route_operations = this.selectOrdersNotWork.filter(item => item.okvid_number === record.okvid_number && item.shaft_id === record.shaft_id && item.order_number === record.order_number);
        } 

        //this.route_operations = record;
        this.showTab = false;
        this.showCard = false;
    },
  },
};
</script>

<style>
.status_completed {
    background: #04BA0B;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_work {
    background: #EFAF09;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}
.route_list_wrapper {
    width: 100% !important;
    position: relative;
    padding: 20px;
    background: white;
}

.ant-tabs-content {
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
    padding: 20px;
    background: white;
}   

.route_card_title {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: 400;
    font-size: 20px;
    line-height: 27px;
    color: #363F51;
}

.route_card_title a {
    color: #4A9DFF !important;
}

.button_back {
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 22px;
    color: #7B7B7B;
    letter-spacing: 0.01em;
}

.icon_back {
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 22px;
    color: #7B7B7B;
    letter-spacing: 0.01em;
    margin-right: 8px;
}

.status_in_work {
    background: #4A9DFF;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_not_work {
    background: #EFAF09;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.ant-pagination-item-link {
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.test_route_map__btn {
    position: absolute;
    top: 30px;
    right: 20px;
    cursor: pointer !important;
    z-index: 1000;
}

.ant-modal-close-x {
    display: flex !important;
    align-items: center;
    justify-content: center;
}
</style>
