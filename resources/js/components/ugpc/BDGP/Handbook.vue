<template>
    <div class="row">
        <div class="col-md-12">
            <b-tabs content-class="mt-3" class="handbook-conteiner">
                <b-tab title="Реестр ГП" active>
                    <div class="row">
                        <div style="height: 350px;" class="col-lg-2 overflow-auto">
                            <b-list-group-item style="border-bottom: 2px solid; font-size: 12px; padding: 5px; text-align: center; font-weight: bolder; color: black;" @click="updateDataGp(customer)"  v-for="(customer, index) in customers" :key="index">{{customer.description}}</b-list-group-item>
                        </div> 
                        <div style="height: 350px;" class="col-lg-10 overflow-auto">
                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">Код ГП</th>
                                        <th scope="col">Категория</th>
                                        <th scope="col">Бренд</th>
                                        <th scope="col">Название</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(producttype, key) in producttypes" :key="key" style="cursor: pointer;" v-b-modal.ProductInfoModal @click="getProductInfo(producttype.gp_code), gp_code=producttype.gp_code">
                                        <td>{{producttype.gp_code}}</td>
                                        <td>{{producttype.product_category}}</td>
                                        <td>{{producttype.brand}}</td>
                                        <td>{{producttype.description}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>   
                    <b-modal
                        v-model="showProductInfo"
                        id="ProductInfoModal" 
                        title="История ГП"
                        size=xl
                    >
                        <div class="card statistial-visit">
                            <div class="card-header">
                                <h5>ГП:{{gp_code}}</h5>
                            </div>
                            <table  class="table table-hover" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">ГП</th>
                                        <th scope="col">ОКВиД</th>
                                        <th scope="col">Папка</th>
                                        <th scope="col">От</th>
                                        <th scope="col">Формат</th>
                                        <th scope="col">Поставщик гравировки</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product_order, key) in this.SelectProductInfo" :key="key">
                                    <td>{{product_order[0].gp_code}}</td>
                                    <td>{{product_order[1].okvid_number}}</td>
                                    <td>{{product_order[1].prod_order}}</td>
                                    <td>{{product_order[1].order_accepted_date}}</td>
                                    <td>{{product_order[1].format}}</td>
                                    <td>{{product_order[1].engraving}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
                        </div>
                    </b-modal>      
                </b-tab>
                <b-tab title="Валы">
                    <div class="handbook_shafts-conteiner">
                        <div style="height: 420px; width: 10%; padding: 0px 10px;" class="overflow-auto">
                            <span style="color: black; margin-bottom: 10px;">Формат:</span>
                            <a-select
                                placeholder="Выберите формат"
                                style="width: 100%"
                                v-model="selectFormat"
                                :allowClear="true"
                                @change="updateDataVal"
                                mode="multiple"
                                showSearch
                                optionFilterProp="children"
                                >
                                <a-select-option
                                    v-for="format in formats"
                                    :key="format.format"
                                    :value="String(format.format)"
                                >
                                    {{ format.format }}
                                </a-select-option>
                                </a-select>
                        </div> 
                        <div class="handbook_shafts">
                            <a-spin :spinning="spinning"></a-spin>
                            <b-tabs v-if="!spinning" style="width: 100%;" content-class="mt-3" class="handbook-conteiner">
                                <b-tab title="Реестр валов">
                                        <div style="height: 350px;" class="overflow-auto">
                                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ID вала</th>
                                                        <th scope="col">Формат</th>
                                                        <th scope="col">FF</th>
                                                        <th scope="col">Изготовитель</th>
                                                        <th scope="col">Дата заказа</th>
                                                        <th scope="col">Примечание</th>
                                                        <th scope="col">Свободен</th>
                                                        <th scope="col">Статус вала</th>
                                                        <th scope="col">Ячейка</th>
                                                        <th scope="col">Диаметр</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                        <tr  v-for="(shaft, key) in shafts" :key="key">
                                                            <td style="cursor: pointer;" v-b-modal.shaftInfoModal @click="getShaftInfo(shaft.shaft_id), shaft_id=shaft.shaft_id">{{shaft.shaft_id}}</td>
                                                            <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-xl" v-model="shaft.shaft_format" @change="submitShaftNote(shaft,'not_free_shaft')"></td>
                                                            <td>{{shaft.ff}}</td>
                                                            <td>{{shaft.manufacturer}}</td>
                                                            <td>{{shaft.manufacture_date}}</td>
                                                            <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-xl" v-model="shaft.note" @change="submitShaftNote(shaft,'not_free_shaft')"></td>
                                                            <td>
                                                                <a-checkbox v-model="shaft.free" @change="submitShaftNote(shaft,'not_free_shaft')">
                                                                    
                                                                </a-checkbox>
                                                            </td>
                                                            <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-xl" v-model="shaft.warehouse" @change="submitShaftNote(shaft,'not_free_shaft')"></td>
                                                            <td>
                                                            <input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-xl" v-model="shaft.warehouse_place" @change="submitShaftNote(shaft,'not_free_shaft')">
                                                            </td>
                                                            <td>{{shaft.diameter}}</td>         
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div> 
                                    <b-modal
                                        id="shaftInfoModal" 
                                        title="История Вала"
                                        size="lg"
                                    >
                                        <div class="card statistial-visit">
                                            <div class="card-header">
                                            </div>
                                            <table  class="table table-hover" style="font-size: 12px; width: 800px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Вал</th>
                                                        <th scope="col">Папка</th>
                                                        <th scope="col">Оквид</th>
                                                        <th scope="col">Формат</th>
                                                        <th scope="col">Статус</th>
                                                        <th scope="col">Ячейка</th>
                                                        <th scope="col">Дата списания</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(shaft_order, key) in this.SelectShaftInfo" :key="key">
                                                        <td>{{shaft_order.shaft_id}}</td>
                                                        <td>{{shaft_order?.order.prod_order}}</td>
                                                        <td>{{String(shaft_order.okvid_number).slice(0,String(shaft_order.okvid_number).length-2)+'-'+String(shaft_order.okvid_number).slice(-2)}}</td>
                                                        <td>{{shaft_order?.order?.phototag_step*shaft_order?.order?.fragment_in_circulation}}</td>
                                                        <td>{{shaft_order.order_status}}</td>
                                                        <td>{{shaft_order.warehouse_place}}</td>
                                                        <td>{{shaft_order.writeoff_date}}</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    
                                                </tfoot>
                                            </table>
                                        </div>
                                    </b-modal> 
                                </b-tab>
                                <b-tab title="Свободные валы">
                                        <div style="height: 350px;" class="overflow-auto">
                                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ID вала</th>
                                                        <th scope="col">FF</th>
                                                        <th scope="col">Изготовитель</th>
                                                        <th scope="col">Дата заказа</th>
                                                        <th scope="col">Статус вала</th>
                                                        <th scope="col">Примечание</th>
                                                        <th scope="col">Ячейка</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="(shaft, key) in shafts_free" :key="key" style="cursor: pointer;">
                                                        <td v-b-modal.shaftInfoModal @click="getShaftInfo(shaft.shaft_id), shaft_id=shaft.shaft_id">{{shaft.shaft_id}}</td>
                                                        <td v-b-modal.shaftInfoModal @click="getShaftInfo(shaft.shaft_id), shaft_id=shaft.shaft_id">{{shaft.ff}}</td>
                                                        <td v-b-modal.shaftInfoModal @click="getShaftInfo(shaft.shaft_id), shaft_id=shaft.shaft_id">{{shaft.manufacturer}}</td>
                                                        <td>{{shaft.manufacture_date}}</td>
                                                        <td>{{shaft.warehouse}}</td>
                                                        <td>{{shaft.note}}</td>
                                                        <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-xl" v-model="shaft.warehouse_place" @change="submitShaftNote(shaft,'free_shaft')"></td>
                                                    </tr>   
                                                </tbody>
                                            </table>
                                        </div> 
                                </b-tab>
                                <b-tab title="Валы заказов">
                                        <div style="height: 350px;" class="overflow-auto">
                                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Формат</th>
                                                        <th scope="col">Кол-во валов</th>
                                                        <th scope="col">ID макро</th>
                                                        <th scope="col">Клиент</th>
                                                        <th scope="col">Общее описание</th>
                                                        <th scope="col">Гравировщик</th>
                                                        <th scope="col">Дата последней гравировки</th>
                                                        <th scope="col">ГП</th>
                                                        <th scope="col">САП-коды</th>
                                                        <th scope="col">Дата печати</th>
                                                        <th scope="col">Склад</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="(shaft, key) in this.orders" :key="key" @click="getShaftInfo(shaft.shaft_id), shaft_id=shaft.shaft_id" style="cursor: pointer;">
                                                        <td>{{shaft.format}}</td>
                                                        <td>{{shaft.count}}</td>
                                                        <td>{{shaft.upak_order}}</td>
                                                        <td>{{shaft.customer}}</td>
                                                        <td>{{shaft.description}}</td>                                      
                                                        <td>{{shaft.engraving}}</td>
                                                        <td>{{formatDate(shaft.request_engraving_date)}}</td>  
                                                        <td>
                                                            <span v-if="shaft?.order?.macro_order?.last_orders?.streams" v-for="stream in shaft.order.macro_order?.last_orders?.streams">
                                                            {{ stream.gp_code + ',' }}
                                                            </span>
                                                        </td> 
                                                        <td>
                                                            <span v-if="shaft?.order?.macro_order?.last_orders?.streams" v-for="stream in shaft.order.macro_order?.last_orders?.streams"><span v-if="stream.product_type?.sap_code">{{ stream.product_type?.sap_code+',' }}</span></span>
                                                        </td>  
                                                        <td>{{formatDate(shaft.printing_date)}}</td>  
                                                        <td>{{shaft.warehouse}}</td>                                       
                                                    </tr>   
                                                </tbody>
                                            </table>
                                        </div> 
                                </b-tab>
                            </b-tabs>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Валы выгрузка" style="padding: 20px;">
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                <table class="display table nowrap table-striped table-hover dataTable" id="shafts_excel" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1">
                                            ID
                                            </th> 
                                            <th rowspan="1" colspan="1">
                                            FF
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Формат
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Оквид(последний)
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Статус вала(Склад)
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Ячейка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Примечание
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(shaft, key) in this.shafts_excel" :key="key">
                                            <td class="tabledit-view-mode">{{shaft.shaft_id}}</td>
                                            <td class="tabledit-view-mode">{{shaft.ff}}</td>
                                            <td class="tabledit-view-mode">{{shaft.shaft_format}}</td>
                                            <td class="tabledit-view-mode">{{shaft.okvid_number}}</td>
                                            <td class="tabledit-view-mode">{{shaft.warehouse}}</td>
                                            <td class="tabledit-view-mode">{{shaft.warehouse_place}}</td>
                                            <td class="tabledit-view-mode">{{shaft.note}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Утвержденные заказы за прошлый месяц" style="padding: 20px;">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="filters" style="display: flex;">
                                <date-picker
                                    type="date"
                                    range
                                    value-type="format"
                                    format="YYYY-MM-DD"
                                    ref="filters_datapicker"
                                    v-model="filterDate"
                                    style="width: 200px;"
                                ></date-picker>                  
                                <button  class="btn btn-primary btn-sm" style="margin-left: 15px;" @click="changeDateOrders()">Показать</button>
                            </div>
                        </div>
                    </div>
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                <table class="display table nowrap table-striped table-hover dataTable" id="in_grav_approved" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1">
                                            Номер
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            папка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Дата заявки
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Заказчик
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Нзавание заказа
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Дизайнер
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Гравировщик
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Валов
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Формат
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Статус заказа
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Репро
                                            </th>  
                                            <th rowspan="1" colspan="1">
                                            Гравировщик
                                            </th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order, key) in this.selectOrdersApproved" :key="key">
                                            <td class="tabledit-view-mode">{{String(order.okvid_number).slice(0,String(order.okvid_number).length-2)+'-'+String(order.okvid_number).slice(-2)}}</td>
                                            <td class="tabledit-view-mode">{{order.prod_order}}</td>
                                            <td class="tabledit-view-mode">{{order.request_engraving_date}}</td>
                                            <td class="tabledit-view-mode">{{order.customer}}</td>
                                            <td class="tabledit-view-mode">{{order.description}}</td>
                                            <td class="tabledit-view-mode">{{order.designer_new}}</td>
                                            <td class="tabledit-view-mode">{{order.engraving}}</td>
                                            <td class="tabledit-view-mode">{{order.cylinder_quantity}}</td>
                                            <td class="tabledit-view-mode">{{order.format}}</td>
                                            <td class="tabledit-view-mode">{{order.order_status}}</td>
                                            <td class="tabledit-view-mode">{{order.repro}}</td>
                                            <td class="tabledit-view-mode">{{order.supplier_engraving}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Добавление формата">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">№ Формата</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="NewFormat">
                                </div>
                                <div style="margin-top: 10px; padding: 1rem;" class="row">
                                    <button  class="btn btn-primary btn-sm" @click="AddFormat()">Добавить формат</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Добавление клиента">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Наименование клиента</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="NewCustomer">
                                </div>
                                <div style="margin-top: 10px; padding: 1rem;" class="row">
                                    <button  class="btn btn-primary btn-sm" @click="AddCustomer()">Добавить клиента</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Ресурс валов">
                    <div class="row">
                        <div style="height: 350px;" class="col-lg-11 overflow-auto">
                            <table class="table table-striped table-bordered" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">Оквид</th>
                                        <th scope="col">Наименование</th>
                                        <th scope="col">Вал</th>
                                        <th scope="col">Ресурс</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(shaft, key) in this.shaft_resources" :key="key">
                                        <td>{{String(shaft.okvid_number).slice(0,String(shaft.okvid_number).length-2)+'-'+String(shaft.okvid_number).slice(-2)}}</td>
                                        <td>{{shaft.description}} %</td> 
                                        <td>{{shaft.shaft_id}}</td>
                                        <td>{{shaft.qty}} %</td>                              
                                    </tr>   
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </b-tab>
            </b-tabs>
        </div>       
    </div>
</template>





<script>
import DatePicker from 'vue2-datepicker';
    export default {
        components: {
            DatePicker
        },
          props: {
            customers: Array,
            urlgp: String,
            urlval: String,
            formats: Array, 
            shaft_resources: Array,
            shaftorders: Array,
            urlgetshaftinfo: String,
            urlgetproductinfo: String,
            urladdshaft: String,
            vendors: Array,
            urlsubmitshaftnote: String,
            urladdformat: String,
            urladdcustomer: String,
            urlchangedateorders: String,
            shafts_excel: Array,
           
        },  
        mounted () {
            document.addEventListener('DOMContentLoaded', function () {
                $('#shafts_excel').DataTable( {
                    
                        pagingType: 'full_numbers',
                            pageLength: 10,
                            processing: true,
                            retrieve: true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel'
                            ]
                    }
                );
            });

            document.addEventListener('DOMContentLoaded', function () {
                $('#shafts_excel').DataTable( {
                    
                        pagingType: 'full_numbers',
                            pageLength: 10,
                            processing: true,
                            retrieve: true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel'
                            ]
                    }
                );
            });

            document.addEventListener('DOMContentLoaded', function () {
                $('#in_grav_approved').DataTable( {
                    
                        pagingType: 'full_numbers',
                            pageLength: 10,
                            processing: true,
                            retrieve: true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel'
                            ]
                    }
                );
            });
        },
        data() {
            return {
                selectFormat: [460],
                spinning: false,
                gp_code: '',
                shaft_id: '',
                showProductInfo: false,
                SelectProductInfo: [],
                SelectShaftInfo: [],
                producttypes: [],
                shafts: [],
                shafts_free: [],
                shafts_not_free: [],
                orders: [],
                NewFormat: 0,
                NewCustomer: '',
                selectOrdersApproved: [],
                filterDate: [null,null],
            }
        },

        methods: {
            updateDataGp(customer) {
                
            axios
                .get(this.urlgp,{ params: { data: customer.id } })
                .then(response => {
                    console.log(response.data);
                    this.producttypes = response.data;
                });
            },

            calcResource(arrays) {
              return (Math.ceil(arrays*100/2000000));
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

            updateDataVal(index) {
                this.spinning = true;
                this.orders = [];
            axios
                .get(this.urlval,{ params: { data: this.selectFormat } })
                .then(response => {

                    this.shafts = response.data[0];
                    this.shafts_free = response.data[1];
                    this.shafts_not_free = response.data[2];
                    this.orders = response.data[3];
                    this.spinning = false;
                });
            },

            getShaftInfo(shaft_id) {
                
            axios
                .get(this.urlgetshaftinfo,{ params: { shaft: shaft_id } })
                .then(response => {
                    this.SelectShaftInfo = response.data;
                  
                });
            },

            getProductInfo(gp_code) {
            axios
                .get(this.urlgetproductinfo,{ params: { gp: gp_code } })
                .then(response => {

                    this.SelectProductInfo = response.data;
                });
            },
            AddFormat() {
                axios.post(this.urladdformat, { 
                    
                        new_format: this.NewFormat, 

                    })
                    .then(response => {
                        this.NewFormat = 0;

                        alert('Формат добавлен');
                    })
                    .catch(err => {});
            },

            AddCustomer() {
                axios.post(this.urladdcustomer, { 
                    
                        new_customer: this.NewCustomer, 

                    })
                    .then(response => {
                        this.NewCustomer = 0;

                        alert('Клиент добавлен');
                    })
                    .catch(err => {});
            },

            submitShaftNote(shaft_note,freeshaft) {
                axios
                .post(this.urlsubmitshaftnote,{ shaft: shaft_note} )
                .then(response => {

                });
            },

            changeDateOrders() {
                axios
                    .get(this.urlchangedateorders,{ params: {dates: this.filterDate}})
                    .then(response => {
                        this.selectOrdersApproved = response.data;
                    });
            },
        }
    }   
</script>

<style scoped>


  .tab-content {
    padding: 0px !important;

}

.input_shaft {
    width: 100%;
}

.handbook_shafts-conteiner {
    display: flex;

}

.handbook_shafts {
    display: flex;
    justify-content: center;
    width: 90%;
}

.current_format {
    color: red;
}
</style>