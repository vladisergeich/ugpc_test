<template>

            <b-tabs content-class="mt-3" class="handbook-conteiner">
                <b-tab title="Тайминг" active>
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                            <table class="display table nowrap table-striped table-hover dataTable" id="timing" style="font-size: 12px;">
                                <thead>
                                <tr role="row">
                                        <th rowspan="1" colspan="1">
                                        Партия
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Грав из профиля
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Подготовка
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Гравировка
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Оквид
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Формат
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Кол-во
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Общее описание
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Диз
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        В раб.
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        монтаж
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        грав.
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        базы
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        № перемещения
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        дата прихода
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Тех. состояние
                                        </th>
                                         <th rowspan="1" colspan="1">
                                        Статус заказа
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, key) in this.orders_timing" :key="key">
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].prod_order}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].supplier_engraving}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].repro}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].engraving}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{String(order[1].okvid_number).slice(0,String(order[1].okvid_number).length-2)+'-'+String(order[1].okvid_number).slice(-2)}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].format}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].cylinder_quantity}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[0].description}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].designer_new}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].order_accepted_date}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].request_install_date}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].request_engraving_date}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[1].application_manufacture_bases}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[2]}}</td>
                                        <td class="tabledit-view-mode" style="border: 1px solid; padding: 2px;">{{order[3]}}</td>
                                        <td class="tabledit-view-mode" style="width: 15%; border: 1px solid; padding: 2px;">{{order[1].condition}}</td>
                                        <td class="tabledit-view-mode" style="width: 15%; border: 1px solid; padding: 2px;">{{order[1].order_status}}</td>
                                    </tr>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </b-tab>
                <b-tab title="В ОКВИД">
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                            <table class="display table nowrap table-striped table-hover dataTable" id="in_okvid" style="font-size: 12px;"> 
                                <thead>
                                <tr role="row">
                                        <th rowspan="1" colspan="1">
                                        Подготовка
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Партия
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Оквид
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Формат
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Валов
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Общее описание
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        базы
                                        </th>
                                         <th rowspan="1" colspan="1">
                                        № перемещения
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        дата прихода
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Диз
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Тех. Состояние
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Гравировка
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        В раб.
                                        </th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, key) in this.orders_in_okvid" :key="key">
                                        <td class="tabledit-view-mode">{{order[1].repro}}</td>
                                        <td class="tabledit-view-mode">{{order[1].prod_order}}</td>
                                        <td class="tabledit-view-mode">{{String(order[1].okvid_number).slice(0,String(order[1].okvid_number).length-2)+'-'+String(order[1].okvid_number).slice(-2)}}</td>
                                        <td class="tabledit-view-mode">{{order[1].format}}</td>
                                        <td class="tabledit-view-mode">{{order[1].cylinder_quantity}}</td>
                                        <td class="tabledit-view-mode">{{order[0].description}}</td>
                                        <td class="tabledit-view-mode">{{order[1].application_manufacture_bases}}</td>
                                        <td class="tabledit-view-mode">{{order[2]}}</td>
                                        <td class="tabledit-view-mode"></td>
                                        <td class="tabledit-view-mode">{{order[1].designer_new}}</td>
                                        <td class="tabledit-view-mode" style="width: 15%;">{{order[1].condition}}</td>
                                        <td class="tabledit-view-mode">{{order[1].engraving}}</td>
                                        <td class="tabledit-view-mode">{{order[1].order_accepted_date}}</td>
                                    </tr>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Препресс">
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                            <table class="display table nowrap table-striped table-hover dataTable" id="prepress_external" style="font-size: 12px;">
                                <thead>
                                <tr role="row">
                                        <th rowspan="1" colspan="1">
                                        Подготовка
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Партия
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Оквид
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Формат
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Валов
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Общее описание
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Диз
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        В раб.
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        на монтаж
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        базы
                                        </th>
                                         <th rowspan="1" colspan="1">
                                        № перемещения
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        дата прихода
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Тех. Состояние
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        Статус заказа
                                        </th>
                                        <th rowspan="1" colspan="1">
                                        грав.
                                        </th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, key) in this.order_prepress_external" :key="key">
                                        <td class="tabledit-view-mode">{{order[1].repro}}</td>
                                        <td class="tabledit-view-mode">{{order[1].prod_order}}</td>
                                        <td class="tabledit-view-mode">{{String(order[1].okvid_number).slice(0,String(order[1].okvid_number).length-2)+'-'+String(order[1].okvid_number).slice(-2)}}</td>
                                        <td class="tabledit-view-mode">{{order[1].format}}</td>
                                        <td class="tabledit-view-mode">{{order[1].cylinder_quantity}}</td>
                                        <td class="tabledit-view-mode">{{order[0].description}}</td>
                                        <td class="tabledit-view-mode">{{order[1].designer_new}}</td>
                                        <td class="tabledit-view-mode">{{order[1].order_accepted_date}}</td>
                                        <td class="tabledit-view-mode">{{order[1].request_install_date}}</td>
                                        <td class="tabledit-view-mode">{{order[1].application_manufacture_bases}}</td>
                                        <td class="tabledit-view-mode">{{order[2]}}</td>
                                        <td class="tabledit-view-mode"></td>
                                        <td class="tabledit-view-mode" style="width: 15%;">{{order[1].condition}}</td>
                                        <td class="tabledit-view-mode">{{order[1].order_status}}</td>
                                        <td class="tabledit-view-mode">{{order[1].engraving}}</td>
                                    </tr>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                            </div>
                        </div>
                        
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Утвержден в гравировку">
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                <table class="display table nowrap table-striped table-hover dataTable" id="in_grav_utv_external" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1">
                                            Подготовка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            папка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Оквид
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Валов
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Общее описание
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            В раб.
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            на монтаж
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            на грав.
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            базы
                                            </th>
                                             <th rowspan="1" colspan="1">
                                            № перемещения
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            дата прихода
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Статус заказа
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Гравировка
                                            </th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order, key) in this.orders_in_grav_external" :key="key">
                                            <td class="tabledit-view-mode">{{order[1].repro}}</td>
                                            <td class="tabledit-view-mode">{{order[1].prod_order}}</td>
                                            <td class="tabledit-view-mode">{{String(order[1].okvid_number).slice(0,String(order[1].okvid_number).length-2)+'-'+String(order[1].okvid_number).slice(-2)}}</td>
                                            <td class="tabledit-view-mode">{{order[1].cylinder_quantity}}</td>
                                            <td class="tabledit-view-mode">{{order[0].description}}</td>
                                            <td class="tabledit-view-mode">{{order[1].order_accepted_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].request_install_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].request_engraving_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].application_manufacture_bases}}</td>
                                            <td class="tabledit-view-mode">{{order[2]}}</td>
                                            <td class="tabledit-view-mode"></td>
                                            <td class="tabledit-view-mode">{{order[1].order_status}}</td>
                                            <td class="tabledit-view-mode">{{order[1].engraving}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="В гравировке">
                    <div class="dt-responsive table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                <table class="display table nowrap table-striped table-hover dataTable" id="in_grav_external" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1">
                                            Выполнен
                                            </th> 
                                            <th rowspan="1" colspan="1">
                                            Подготовка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            папка
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Оквид
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Валов
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Общее описание
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            В раб.
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            на монтаж
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            на грав.
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            базы
                                            </th>
                                             <th rowspan="1" colspan="1">
                                            № перемещения
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            дата прихода
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Статус заказа
                                            </th>
                                            <th rowspan="1" colspan="1">
                                            Гравировка
                                            </th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(order, key) in this.orders_grav_external" :key="key">
                                            <td class="tabledit-view-mode"><input type="checkbox" @change="okvidFulfilled(order[1])" v-model="order[1].engraved" :value="order[1].engraved"></td>
                                            <td class="tabledit-view-mode">{{order[1].repro}}</td>
                                            <td class="tabledit-view-mode">{{order[1].prod_order}}</td>
                                            <td class="tabledit-view-mode">{{String(order[1].okvid_number).slice(0,String(order[1].okvid_number).length-2)+'-'+String(order[1].okvid_number).slice(-2)}}</td>
                                            <td class="tabledit-view-mode">{{order[1].cylinder_quantity}}</td>
                                            <td class="tabledit-view-mode">{{order[0].description}}</td>
                                            <td class="tabledit-view-mode">{{order[1].order_accepted_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].request_install_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].request_engraving_date}}</td>
                                            <td class="tabledit-view-mode">{{order[1].application_manufacture_bases}}</td>
                                            <td class="tabledit-view-mode">{{order[2]}}</td>
                                            <td class="tabledit-view-mode"></td>
                                            <td class="tabledit-view-mode">{{order[1].order_status}}</td>
                                            <td class="tabledit-view-mode">{{order[1].engraving}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Валы выгрузка">
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
                                        <tr v-for="(order, key) in this.shafts_excel" :key="key">
                                            <td class="tabledit-view-mode">{{order.shaft_id}}</td>
                                            <td class="tabledit-view-mode">{{order.ff}}</td>
                                            <td class="tabledit-view-mode">{{order.shaft_format}}</td>
                                            <td class="tabledit-view-mode">{{order.okvid_number}}</td>
                                            <td class="tabledit-view-mode">{{order.warehouse}}</td>
                                            <td class="tabledit-view-mode">{{order.warehouse_place}}</td>
                                            <td class="tabledit-view-mode">{{order.note}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Утвержденные заказы за прошлый месяц">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="input-group input-group-sm">
                                <date-picker 
                                    valueType="YYYY-MM-DD"
                                    v-model="date_start" type="date">
                                </date-picker>
                                <date-picker 
                                    valueType="YYYY-MM-DD"
                                    v-model="date_end" type="date">
                                </date-picker>                   
                                <button  class="btn btn-primary btn-sm" style="margin-top: 10px;" @click="changeDateOrders()">Показать</button>
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
            </b-tabs>
</template>



<script>
    export default {
     
        props: {
            orders_timing: Array,
            orders_in_okvid: Array,
            order_prepress_external: Array,
            orders_in_grav_external: Array,
            orders_grav_external: Array,
            orders_approved_arr: Array,
            shafts_excel: Array,
            urlokvidfulfilled: String,
            urlchangedateorders: String,

           
        },  
        async mounted () {

            document.addEventListener('DOMContentLoaded', function () {
                $('#timing').DataTable( {
                    
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
                $('#in_okvid').DataTable(
                    {
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
                $('#prepress_external').DataTable(
                    {
                        pagingType: 'full_numbers',
                            pageLength: 20,
                            processing: true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel'
                            ]
                    }
                );
            }),

            document.addEventListener('DOMContentLoaded', function () {
                $('#in_grav_external').DataTable(
                    {
                        pagingType: 'full_numbers',
                            pageLength: 20,
                            processing: true,
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'excel'
                            ]
                    }
                );
            }),

            document.addEventListener('DOMContentLoaded', function () {
                $('#in_grav_utv_external').DataTable(
                    {
                        pagingType: 'full_numbers',
                            pageLength: 20,
                            processing: true,
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
                date_start: 0,
                date_end: 0,
                selectOrdersApproved: [],
            }
        },
        computed: {

        },
        methods: {
            okvidFulfilled(okvid) {
               axios
                .post(this.urlokvidfulfilled,{ okvid_number: okvid } )
                .then(response => {
                    
                    
                    
                });
            },

            changeDateOrders() {
                axios
                    .get(this.urlchangedateorders,{ params: {date_start: this.date_start, date_end: this.date_end}})
                    .then(response => {
                        this.selectOrdersApproved = response.data;
                        
                        $(document).ready(function () {
                            $('#in_grav_approved').DataTable(
                                {
                                    pagingType: 'full_numbers',
                                        pageLength: 10,
                                        processing: true,
                                        dom: 'Bfrtip',
                                        buttons: [
                                            'copy', 'excel'
                                        ]
                                }
                            );
                        })
                    });
            },

        }
    }   
</script>

<style scoped>

</style>