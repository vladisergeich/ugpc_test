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
            :columns="columns_shaft"
            :data-source="filteredData"
            :rowKey="record => record.uid"
            >
            <a-button slot="print_label" slot-scope="text, record, index" class="print_btn" data-toggle="modal" data-target=".bd-example-modal-sm" @click="openLabel(record)">Напечатать</a-button>
            </a-table>
            <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal_wrapper" style="width: 250px; padding: 10px">
                        <div class="modal-content">
                            <div class="modal-content-body" ref="modalLabelContent" id="modalLabelContent">
                                <div class="label_block">
                                    <div class="info_block">
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;"><span>Вал: {{selectedShaft.shaft_id}}</span>    <span style="margin-left: 20px;">№: {{selectedShaft.shaft_qty_number}} из {{selectedShaft.qty_shaft}}</span></span>
                                        <span style="display: block; margin-bottom: 10px;">№ сепарации: {{selectedShaft.shaft_order_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Формат: {{selectedShaft.shaft_format}}</span>
                                        <span style="display: block; margin-bottom: 10px;">№ Партии: {{selectedShaft.order_number}}</span>
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;">Оквид: {{selectedShaft.okvid_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Линиатура: {{selectedShaft.lineature}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Угол: {{selectedShaft.corner}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Резец: {{selectedShaft.cutter}}</span>
                                        <span style="display: block; margin-bottom: 10px;" v-if="selectedShaft.panton != null">Цвет: {{selectedShaft.panton}}</span>
                                        <span style="display: block; margin-bottom: 10px;" v-if="selectedShaft.color != null">Цвет: {{selectedShaft.color}}</span>
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
            <a-button class="operation_btn" @click="showModalOperation()">Добавить доп. операцию</a-button>
            <a-table
            :columns="columns_input_shaft"
            :data-source="selectInputShafts"
            :rowClassName="(record, index) => record.description != 'Чистка вала' ? 'additional_operation' :  'table-row-dark'"
            >
            <span slot="status" slot-scope="text, record, index"><span v-if="record.status=='Завершен'" class="status_completed">{{ text}}</span><span v-if="record.status=='На рассмотрении'"  class="status_not_work">{{ text}}</span><span v-if="record.status=='На заполнении'"  class="status_work">{{ text}}</span></span>  
            <a @click="showEditShaft(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>       
            </a-table>
            <a-modal
                title="Доп. операции"
                :visible="visibleModalOperation"
                @cancel="handleCancelOperation"
                >
                <template slot="footer">
                    <a-button key="submit" type="primary" @click="startOperation(selectedOperation)">
                    Начать
                    </a-button>
                </template>
                <a-select style="width: 100%" v-model="selectedOperation">
                    <a-select-option v-for="operation in secondary_operations" :key="operation.id">
                        {{ operation.description }}
                    </a-select-option>
                </a-select>
            </a-modal>
            <a-modal
                title="Редактирование"
                :visible="visibleModalEditShaft"
                @cancel="handleCancelEditShaft"
                width="1000px"
                >
                <template slot="footer">
                    <a-button key="submit" type="primary" @click="saveEditShaft(selectEditShaft)">
                    Сохранить изменения
                    </a-button>
                </template>
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Вид вала:</label>
                        <a-select style="width: 100%" v-model="selectEditShaft.type_shaft">
                            <a-select-option v-for="type_shaft in types_shaft" :key="type_shaft">
                                {{ type_shaft }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <div class="col-lg-4">
                        <label for="" style="display: block;">Диаметр</label>
                        <a-input
                        style="width: 100%;"
                        v-model="selectEditShaft.shaft_diameter"
                        />
                    </div>
                    <div class="col-lg-4">
                        <label for="" style="display: block;">Толщина покрытия, мкм</label>
                        <a-input
                        style="width: 100%;"
                        v-model="selectEditShaft.copper_plating"
                        />
                    </div>
                </div>
            </a-modal>
        </a-tab-pane>
    </a-tabs>
  </div>
</template>



<script>
import html2canvas from 'html2canvas';
import JsBarcode from 'jsbarcode';
import QRCode from 'qrcode-generator';
import printJS from 'print-js';

export default {
  props: {
    shafts: Array,
    input_control_shafts: Array,
    secondary_operations: Array,
    urlprintlabel: String,
    urlstartoperation: String,
    urlinputcontrolcard: String,
  },
  data() {
    return {
        renderAs: 'svg',
        columns_shaft: [
            {
            title: "№ Вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            },
            {
            title: "№ Заказа",
            dataIndex: "order_number",
            key: "order_number",
            },
            {
            title: "№ Оквид",
            dataIndex: "okvid_number",
            key: "okvid_number",
            },
            {
            title: "Дата",
            dataIndex: "create_document_date",
            key: "create_document_date",
            },
            {
            dataIndex: "print_label",
            key: "print_label",
            scopedSlots: { customRender: 'print_label' }, 
            },
        ],
        columns_input_shaft: [
            {
            title: "Операция",
            dataIndex: "description",
            key: "description",
            scopedSlots: { customRender: 'description' },   
            },
            {
            title: "№ Вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            scopedSlots: { customRender: 'shaft_id' },   
            },
            {
            title: "№ Заказа",
            dataIndex: "order_number",
            key: "order_number",
            scopedSlots: { customRender: 'order_number' },   
            },
            {
            title: "№ Оквид",
            dataIndex: "okvid_number",
            key: "okvid_number",
            scopedSlots: { customRender: 'okvid_number' },   
            },
            {
                title: 'Время',
                dataIndex: 'starting_time_ending_time',
                scopedSlots: { customRender: 'starting_time_ending_time' },   
            },
            {
            title: "Дата",
            dataIndex: "posting_date",
            key: "posting_date",
            scopedSlots: { customRender: 'posting_date' },   
            },
            {
            title: "Оператор",
            dataIndex: "operator",
            key: "operator",
            scopedSlots: { customRender: 'operator' },   
            },
            {
                title: 'Статус',
                dataIndex: 'status',
                key: 'status',
                scopedSlots: { customRender: 'status' },       
                
            },
        ],
        searchText: '',
        code: '',
        options: {
            format: 'code128',
            displayValue: false,
        },
        selectEditShaft: {},
        statuses: ['Завершен','До рассмотрения'],
        types_shaft: ['Медь','Сталь','Хром'],
        selectedShaft: {},
        selectShafts: [...this.shafts],
        selectInputShafts: [...this.input_control_shafts],
        selectedRowKeys: [],
        visibleModalOperation: false,
        visibleModalEditShaft: false,
        selectedOperation: null,
        qr_data: null,
    };
  },
  created() {
        window.Echo.private('input-control-consupm-shaft')
            .listen('InputControlConsumpShaft', (e) => {
                this.selectInputShafts.push(e.shaft);
        }),

        window.Echo.private('input-control-status-update')
            .listen('InputControlStatusUpdated', (event) => {
                console.log(event);
                const updatedRow = this.selectInputShafts.find(row => row.shaft_id === event.shaft_id);
                if (updatedRow) {
                    updatedRow.status = event.status;
                }
        })
  },
  mounted() {
    
  },
  computed: {
    filteredData() {
      return this.selectShafts.filter(item => {
        return Object.values(item).some(value =>
          String(value).toLowerCase().includes(this.searchText.toLowerCase())
        );
      });
    },
  },
  methods: {
    generateQRCode() {
      // Создать QR-код
      const size = 200;

      const qr = QRCode(0, 'M');
      qr.addData(this.qr_data);
      qr.make();
    qr.createSvgTag({
        scale: size / qr.getModuleCount()
    });
      // Получить SVG-код QR-кода
      const svgCode = qr.createSvgTag();

      // Установить SVG-код в переменную qrCode
      this.qr_data = svgCode;
    },
    showModalOperation() {
      this.visibleModalOperation = true;
    },

    showEditShaft(record) {
        this.selectEditShaft = record;

        this.visibleModalEditShaft = true;
    },

    handleCancelEditShaft() {
      this.visibleModalEditShaft = false;
    },

    startOperation() {
        const lastOperation = this.input_control_shafts .filter(operation => {
                return operation.ending_time == "00:00:00" && operation.description == 'Чистка вала';
        });

        if (lastOperation.length != 0) {

            alert('Необходимо завершить чистку вала '+lastOperation[0].shaft_id);
            
        } else {

            axios.post(this.urlstartoperation, {
                id: this.selectedOperation
            })
            .then(response => {
                this.selectInputShafts = response.data;
                this.visibleModalOperation = false;
            })
            .catch(error => {
                console.log(error);
                alert("Отсутствует соединение");
            });

        }
    },

    handleCancelOperation() {
      this.visibleModalOperation = false;
    },

    consumpShaft() {
        axios
        .post(this.urlprintlabel,this.selectedShaft)
        .then(response => {
            this.selectInputShafts = response.data;
            printJS({ printable: 'modalLabelContent', type: 'html' });
        })
        .catch(error => {
            alert("Отсутствует соединение");
        });
    },

    saveEditShaft() {
        axios
        .post(this.urlinputcontrolcard,this.selectEditShaft)
        .then(response => {
            this.selectInputShafts = response.data;
            this.visibleModalEditShaft = false;
        })
        .catch(error => {
            console.log(error);
            alert("Отсутствует соединение");
        });
    },
    
    openLabel(record) {
        this.selectedShaft = record;
        this.qr_data = record.shaft_id+'|'+record.okvid_number+'|'+record.order_number;
        this.generateQRCode();
    },

    printLabel() {
       this.consumpShaft();
    }

  },
};
</script>

<style>
.info_block {
    padding: 10px 10px 0 10px;
}

.modal_wrapper {
    width: 250px;
}

.label_block {
    display: block;
}

.barcode_block {
    display: block;
}

.barcode_block>svg {
    width: 100%;
    height: 100%;
}

.input_control_conteiner {
    background: white;
    padding: 20px;
    width: 1400px;
    position: relative;
}
.operation_btn {
    position: absolute;
    top: 10px;
    right: 0px;
    color: white;
    background: #4A9DFF;
    cursor: pointer;
}
.print_btn {
    position: absolute;
    top: 10px;
    right: 0px;
    color: #4A9DFF;
    border-color: #4A9DFF;
    cursor: pointer;
}
.ant-table-thead > tr > th, .ant-table-tbody > tr > td {
    font-family: 'Open Sans' !important;
    font-style: normal !important;
    font-weight: 400 !important;
    color: #363F51 !important;
}


.ant-tabs-nav .ant-tabs-tab {
    height: inherit !important;
}

.ant-table-thead > tr > th {
    background: #E5EEFF;
}

a-space {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.custom-button {
  margin-left: 10px;
}

.ant-tabs-content {
    padding: 0 !important;
}

.status_completed {
    background: #04BA0B;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_not_work {
    background: #E31212;
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

.additional_operation {
    background: #EFFFE2;
}
</style>
