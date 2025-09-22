<template>
    <div class="input_control_conteiner">
        <h3 style="margin-bottom: 20px;">Входной контроль</h3>
        <a-tabs>
          <a-tab-pane key="1" tab="Валы на складе">
            <a-input-search 
                style="width: 200px; margin-bottom: 10px;"
                :allow-clear="true"
                v-model="searchText"
            />
            <a-table
                :columns="columnsOrders"
                :data-source="filteredData"
                >
                    <a-table
                        slot="expandedRowRender"
                        slot-scope="record"
                        :columns="columns_shaft"
                        :data-source="record.design_order.engraving_orders"
                        :pagination="false"
                        >
                    
                        <a-button slot="print_label" slot-scope="text, record, index" class="print_btn" data-toggle="modal" data-target=".bd-example-modal-sm" @click="openLabel(record)">Напечатать</a-button>
                    </a-table>
                <template  slot="accepted" slot-scope="text, record, index">
                    <div style="text-align: center; width: 100%;">
                         <input
                            style="width: 15px; height: 15px;"
                            v-model="record.accepted"
                            type="checkbox"
                            @change="acceptedOrder(record)"
                            />
                    </div>     
                </template>                                                                                                                      
            </a-table>
            <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal_wrapper" style="width: 250px; padding: 10px">
                        <div class="modal-content">
                            <div class="modal-content-body" ref="modalLabelContent" id="modalLabelContent">
                                <div class="label_block">
                                    <div class="info_block" v-if="selectedShaft">
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;"><span>Вал: {{selectedShaft.shaft.shaft_id}}</span>    <span style="margin-left: 20px;">№: {{selectedShaft.shaft_number}} из {{ selectedShaft.design_order.cylinder_quantity }}</span></span>
                                        <span style="display: block; margin-bottom: 10px;">№ сепарации: {{selectedShaft.separation_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Формат: {{selectedShaft.format}}</span>
                                        <span style="display: block; margin-bottom: 10px;">№ Партии: {{selectedShaft.design_order.prod_order}}</span>
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 17px;">Оквид: {{selectedShaft.design_order.okvid_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Линиатура: {{selectedShaft.lineature}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Угол: {{selectedShaft.corner}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Резец: {{selectedShaft.cutter}}</span>
                                        <span style="display: block; margin-bottom: 10px; font-size: 17px;">Цвет: {{selectedShaft.color}}</span>
                                        <div class="barcode_block" style="display: block;" v-if="qr_data" v-html="qr_data"></div>
                                    </div>
                                </div>
                            </div>
                            <a-button style="width: 100%;" @click="printLabel()">Распечатать</a-button>
                        </div>
                    </div>
                </div>
            </div>
          </a-tab-pane>
          <a-tab-pane key="2" tab="Учтенные валы" force-render>
            <a-table
            :columns="operationsColumns"
            :data-source="operations"
            >   
            </a-table>
          </a-tab-pane>
      </a-tabs>
    </div>
  </template>
  
  
  
<script>
import QRCode from 'qrcode-generator';
import printJS from 'print-js';

  export default {
    components: {

    },
    props: {
        work_shift_code: Object,
        operator: Object,
        engraving_orders: Array,
        operations: Array,
    },
    data() {
      return {
        searchText: '',
        columnsOrders: [
            {
            title: "Оквид",
            dataIndex: "okvid_number",
            key: "okvid_number",
            scopedSlots: { customRender: 'okvid_number' },
            },
            {
            title: "Заказ",
            dataIndex: "order_number",
            key: "order_number",
            scopedSlots: { customRender: 'order_number' },
            },
            {
            title: "Кол-во валов",
            dataIndex: "design_order.cylinder_quantity",
            key: "design_order.cylinder_quantity",
            scopedSlots: { customRender: 'design_order.cylinder_quantity' },
            },
            {
            title: "Наименование",
            dataIndex: "design_order.macro_order.description",
            key: "design_order.macro_order.description",
            scopedSlots: { customRender: 'design_order.macro_order.description' },
            },
            {
            title: "Клиент",
            dataIndex: "design_order.macro_order.customer",
            key: "design_order.macro_order.customer",
            scopedSlots: { customRender: 'design_order.macro_order.customer' },
            },
            {
                title: 'Статус',
                dataIndex: 'status',
                key: 'status',  
                scopedSlots: { customRender: 'status' },            
            },
            {
            title: "Принят",
            dataIndex: "accepted",
            key: "accepted",
            width: '10%',
            scopedSlots: { 
                customRender: 'accepted', 
            },
            },
        ],
        columns_shaft: [
            {
            title: "№ Вала",
            dataIndex: "shaft.shaft_id",
            key: "shaft.shaft_id",
            },
            {
            dataIndex: "print_label",
            key: "print_label",
            scopedSlots: { customRender: 'print_label' }, 
            },
        ],
        operationsColumns: [
            {
            title: "Операция",
            dataIndex: "operation.description",
            key: "operation.description",
            scopedSlots: { customRender: 'operation.description' },   
            },
            {
            title: "№ Вала",
            dataIndex: "engraving_order.shaft.shaft_id",
            key: "engraving_order.shaft.shaft_id",
            scopedSlots: { customRender: 'engraving_order.shaft.shaft_id' },   
            },
            {
            title: "№ Заказа",
            dataIndex: "engraving_order.design_order.prod_order",
            key: "engraving_order.design_order.prod_order",
            scopedSlots: { customRender: 'engraving_order.design_order.prod_order' },   
            },
            {
            title: "№ Оквид",
            dataIndex: "engraving_order.design_order.okvid_number",
            key: "engraving_order.design_order.okvid_number",
            scopedSlots: { customRender: 'engraving_order.design_order.okvid_number' },   
            },
            {
            title: "Время начала",
            dataIndex: "start_time",
            key: "start_time",
            },
            {
            title: "Время окончания",
            dataIndex: "end_time",
            key: "end_time",
            },
            {
            title: "Дата",
            dataIndex: "posting_date",
            key: "posting_date",
            scopedSlots: { customRender: 'posting_date' },   
            },
            {
            title: "Оператор",
            dataIndex: "user.employer_name_1c",
            key: "user.employer_name_1c",
            scopedSlots: { customRender: 'user.employer_name_1c' },   
            },
        ],
        selectedShaft: null,
        qr_data: null,
      };
    },
    created() {

    },
    mounted() {
      
    },
    computed: {

        filteredData() {
          const searchTextLower = this.searchText.toLowerCase().trim();
          const filteredPlace = this.engraving_orders.filter(order =>
              Object.values(order).some(val =>
                  (val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower))) ||
              Object.values(order.design_order).some(val =>
                  (val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower)))
          );

          return filteredPlace;
        },
    },
    methods: {
        openLabel(record) {
            this.selectedShaft = record;

            this.qr_data = 'shaft|'+String(record.id);
            this.generateQRCode();
        },

        generateQRCode() {
            const size = 200;

            const qr = QRCode(0, 'M');
            qr.addData(this.qr_data);
            qr.make();
            qr.createSvgTag({
                scale: size / qr.getModuleCount()
            });
            const svgCode = qr.createSvgTag();
            this.qr_data = svgCode;
        },

        printLabel() {
            printJS({ printable: 'modalLabelContent', type: 'html' });
        },

        acceptedOrder(order) {
            axios
                .post(route('ugpc.inputcontrol.acceptedorder'),{order: order})
                .then(response => {

                });
        }
    },
  };
  </script>
  
  <style>
  .input_control_conteiner {
      background: white;
      padding: 20px;
      width: 1400px;
      position: relative;
  }

  .print_btn {
    position: absolute;
    top: 10px;
    right: 0px;
    color: #4A9DFF;
    border-color: #4A9DFF;
    cursor: pointer;
}
  </style>
  