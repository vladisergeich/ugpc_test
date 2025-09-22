<template>
    <div id="app">
        <h3 style="margin-bottom: 20px;">Маршрутные карты</h3>
        <div class="route_list_wrapper">
            <a-tabs>
                <a-tab-pane key="1" tab="В работе" force-render>
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
                              :columns="columnsShafts"
                              :data-source="record.design_order.engraving_orders"
                              :pagination="false"
                              >
                              <a target="_blank" :href="'https://okvid.danaflex.ru/ugpc/engravingorders/order/'+record.id" slot="shaft.shaft_id" slot-scope="text, record">{{text}}</a>
                              <span slot="shaft.last_okvid.order.engraving" slot-scope="text, record">{{ text }}</span>
                          </a-table>
                          <span slot="status" slot-scope="text, record">
                            <span v-if="record.design_order.engraving_orders.filter((order) => order.status == 'Ожидает подтверждения').length == 0" style="color: green;">Подтвержден</span>
                            <span v-if="record.design_order.engraving_orders.filter((order) => order.status == 'Ожидает подтверждения').length > 0">Ожидает подтверждения</span>
                          </span>
                          <span slot="okvid_number" slot-scope="text, record">
                            {{ okvidNumber(text) }}
                          </span>
                        </a-table>
                </a-tab-pane>
                <a-tab-pane key="2" tab="Завершены" force-render>
                  <a-input-search 
                    style="width: 200px; margin-bottom: 10px;"
                    :allow-clear="true"
                    v-model="searchTextEndedOrders"
                  />
                  <a-table
                  :columns="columnsOrders"
                  :data-source="filteredDataEndedOrders"
                  >
                    <a-table
                        slot="expandedRowRender"
                        slot-scope="record"
                        :columns="columnsShafts"
                        :data-source="record.design_order.engraving_orders"
                        :pagination="false"
                        >
                      <a target="_blank" :href="'https://okvid.danaflex.ru/ugpc/engravingorders/order/'+record.id" slot="shaft.shaft_id" slot-scope="text, record">{{text}}</a>
                      
                    </a-table>
                  </a-table>
                </a-tab-pane>
            </a-tabs>
        </div>
    </div>
    </template>
    
    
    
    <script>
    export default {
        components: {

        },
        props: {
            engraving_orders: Array,
            engraving_orders_ended: Array,
        },
      data() {
        return {
            searchText: '',
            searchTextEndedOrders: '',
            columnsShafts: [
                {
                title: "",
                dataIndex: "shaft_number",
                key: "shaft_number",
                scopedSlots: { customRender: 'shaft_number' },
                },
                {
                title: "Вал",
                dataIndex: "shaft.shaft_id",
                key: "shaft.shaft_id",
                scopedSlots: { customRender: 'shaft.shaft_id' },
                },
                {
                title: "Формат",
                dataIndex: "format",
                key: "format",
                scopedSlots: { customRender: 'format' },
                },
                {
                title: "Пред. Грав.",
                dataIndex: "shaft.last_okvid.order.engraving",
                key: "shaft.last_okvid.order.engraving",
                scopedSlots: { customRender: 'shaft.last_okvid.order.engraving' },
                },
                {
                title: "Статус",
                dataIndex: "status",
                key: "status",
                scopedSlots: { customRender: 'status' },
                },
            ],
            columnsOrders: [
                {
                title: "Оквид",
                dataIndex: "okvid_number",
                key: "okvid_number",
                width: '200',
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
            ],
        };
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

        filteredDataEndedOrders() {
          const searchTextLower = this.searchTextEndedOrders.toLowerCase().trim();
          const filteredPlace = this.engraving_orders_ended.filter(order =>
              Object.values(order).some(val =>
                  (val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower))) ||
              Object.values(order.design_order).some(val =>
                  (val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower)))
          );

          return filteredPlace;
        },
      },
      methods: {
        okvidNumber(okvid_number) {
            return String(okvid_number).slice(0,String(okvid_number).length-2)+'-'+String(okvid_number).slice(-2);
        },
      },
    };
    </script>
    
    <style>

    </style>
    