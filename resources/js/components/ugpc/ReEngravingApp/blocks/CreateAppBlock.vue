<template>
    <div class="create_wrapper">
      <template v-if="!next">
      <div class="radio_row">
        <a-radio-group default-value="okvid" @change="handleSearchChange">
            <a-radio-button value="okvid">
              Искать по №OKВид
            </a-radio-button>
        </a-radio-group>
      </div>
      <div class="search_row">
          <div style="margin-top: 10px">
            <a-input
              placeholder="Введите номер OKВид"
              v-if="searchType == 'okvid'"
              v-model="searchText"
              style="width: 200px;"
            />
            <a-button type="primary" @click="searchShafts">Поиск</a-button>
          </div>
      </div>
      <div class="shafts_row" v-if="macroOrder">
        <span style="margin-bottom: 10px;">Выберете валы которые необходимо перегравировать</span>
        <a-table
        bordered
        size="small"
        :pagination="false"
        :row-selection="rowSelection"
        :columns="shaftsColumns"
        :data-source="macroOrder.shafts"
        >
        </a-table>
      </div>
      <div class="next_btn_row" v-if="macroOrder">
        <a-button type="primary" @click="next = true">Далее</a-button>
      </div>
      </template>
      <template>
          <div v-if="next">
            <div class="shafts_wrapper">
              <ShaftCardElement v-for="shaft in this.selectShafts" :key="shaft.id" :shaft="shaft" ref="ShaftCardElement"/>
            </div>
            <div class="create_btn_block">
              <a-button style="width: 20%;" type="primary" @click="createApp()">Отправить на согласование</a-button>
            </div>
          </div>
      </template>
    </div>
  </template>
  
  <script>
  import ShaftCardElement from "../elements/ShaftCardElement.vue";
  export default {
    components: {
        ShaftCardElement,
    },
    data() {
      return {
        next: false,
        searchText: '',
        searchType: 'okvid',
        macroOrder: null,
        selectShafts: null,
        shaftsColumns: [
          {
            title: '№OKВид',
            dataIndex: 'okvid_number',
            key: 'okvid_number',
          },
          {
            title: '№ вала',
            dataIndex: 'shaft_id',
            key: 'shaft_id',
          },
          {
            title: 'Цвет',
            dataIndex: 'color',
            key: 'color',
          },
          {
            title: 'Цвет DF',
            dataIndex: 'color_df',
            key: 'color_df',
          },
          {
            title: 'Статус вала',
            dataIndex: 'order_status',
            key: 'order_status',
          },
          {
            title: 'Ячейка',
            dataIndex: 'warehouse_place',
            key: 'warehouse_place',
          },
        ],
        rowSelection: {
          onChange: (selectedRowKeys, selectedRows) => {
            this.selectShafts = selectedRows;
          },
          onSelect: (record, selected, selectedRows) => {
            console.log(record, selected, selectedRows);
          },
          onSelectAll: (selected, selectedRows, changeRows) => {
            this.selectShafts = selectedRows;
          },
        },
      };
    },
    methods: {
      handleSearchChange(e) {
        this.searchType = e.target.value;
      },

      searchShafts() {
        axios
            .post(route('ugpc.reengravingapps.createapp.search'),{type: this.searchType, searchText: this.searchText})
            .then(response => {

              this.macroOrder = response.data;

              this.macroOrder.shafts = this.macroOrder.orders.reduce((acc, order) => {
                  order.shafts.forEach((shaft) => {
                    acc.push({
                      okvid_number: shaft.okvid_number,
                      shaft_id: shaft.shaft_id,
                      color: shaft.color,
                      color_df: shaft.color_df,
                      order_status: shaft.order_status,
                      warehouse_place: shaft.warehouse_place,
                    });
                });
                return acc;
              }, []);

            });
      },

      createApp() {
          const formData = new FormData();

          formData.append('macroOrder', JSON.stringify(this.macroOrder));

          this.selectShafts.forEach((shaft, index) => {
              formData.append(`shafts[${index}][shaft_id]`, shaft.shaft_id);

              if (shaft.reason) {
                formData.append(`shafts[${index}][reason]`, shaft.reason);
              } else {
                alert('Заполните причину перегравировки!');
                throw new Error('Заполните причину перегравировки!');
              }

              if (shaft.comment) {
                console.log(shaft.comment);
                formData.append(`shafts[${index}][comment]`, shaft.comment);
              }

              if (Array.isArray(shaft.files) && shaft.files.length > 0) {
                  shaft.files.forEach((file, fileIndex) => {
                      formData.append(`shafts[${index}][files][${fileIndex}]`, file.raw);
                  });
              } 
          });

          axios.post(route('ugpc.reengravingapps.postapp'), formData, {
              headers: {
                  'Content-Type': 'multipart/form-data'
              }
          })
          .then(response => {
              window.location.href = 'https://okvid.danaflex.ru/ugpc/reengravingapps';
          })
          .catch(error => {
              console.error(error);
          });
      }
      
    },
  };
  </script>
  
  <style scoped>
  .create_btn_block {
    display: flex;
    justify-content: flex-end;
  }
  .next_btn_row {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
  }
  .radio_row {
    margin-bottom: 20px;
  }
  .search_row {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
  }

  .shafts_wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
  }
  .create_wrapper {
    padding: 20px;
    border-radius: 10px;
    background: #FFFFFF;
  }
  </style>
  