<template>
    <div class="page-wrapper">
        <div class="card_wrapper">
        <div class="card_parametr">
        <div class="card attributes-card" >
            <div class="button_collapse">
                <i class="feather icon-chevron-left" ></i>
            </div>
        <div class="order-card" v-if="!show_param">
            <div class="row">
                <div class="col-lg-12">
                    <button  class="btn btn-primary btn-sm" style="font-size: 14px; padding: 8px 10px !important;" @click="createPdfShaft()">Заявка на гравировку</button>
                    <button class="btn btn-primary btn-sm" style="font-size: 14px; padding: 8px 10px !important;" @click="createDouble()">ДУБЛЬ</button> 
                    <button  class="btn btn-primary btn-sm" style="font-size: 14px; padding: 8px 10px !important;" v-b-modal.mountingModal>Генерация монтаж</button>
                    <button  class="btn btn-primary btn-sm" style="font-size: 14px; padding: 8px 10px !important;" @click="SendDataShafts()">Информация для шкал</button>
                    <button  class="btn btn-primary btn-sm" style="font-size: 14px; padding: 8px 10px !important;" @click="autoApproval()">Согласовать Координатор(валы)</button>
                    <b-modal id="mountingModal" title="Генерация монтаж">
                        <table  class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">№ Ручья</th>
                                    <th scope="col">ГП</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(stream, key) in selectLayoutConstructor" :key="key">
                                <td><input type="text" class="form-control" v-model="stream.stream_number"></td>
                                <td><input type="text" class="form-control" v-model="stream.gp_code" @change="submitStreams(stream)"></td>
                                <td style="vertical-align: middle; color: red; cursor: pointer;"><i class="feather icon-delete" @click="DeleteStream(stream.id)"></i></td>
                                
                                </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px; padding: 1rem;" class="row">
                            <button  class="btn btn-primary btn-sm" @click="AddStream()">Добавить Ручей</button>
                            <button  class="btn btn-primary btn-sm" @click="SendDataStream()">Отправить</button>
                        </div>
                    </b-modal>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <label for="">ОКВИД: </label>
                    <button  @click="firstOkvid()"><i class="feather icon-chevrons-left"></i></button>
                    <button @click="BackOkvid()"><i class="feather icon-chevron-left"></i></button>
                    <label for="">{{this.Okvid_number}}</label>
                    <button  @click="NextOkvid()"><i class="feather icon-chevron-right"></i></button>
                    <button  @click="lastOkvid()"><i class="feather icon-chevrons-right"></i></button>
                    <button  class="btn_sc" @click="SC_preset()">SC</button>
                    <button  class="btn_rm" @click="RM_preset()">RM</button>
                    <div class="input-group" style="width: 20%; display: inline-flex;">
                        <input type="text" id="m-search" class="form-control" placeholder="Поиск по Партии . . ." style="padding: 3px 5px;" v-model="serachOrder">
                        <span class="input-group-append search-btn btn btn-primary" style="padding: 0;" @click="searchOrder()" v-b-modal.searchOrderModal>
                            <i class="feather icon-search input-group-text"></i>
                        </span>
                    </div>
                    <b-modal id="searchOrderModal" title="Поиск по партии" v-if="this.searchOrderInfo">
                        <table  class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Партия</th>
                                    <th scope="col">Оквид</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr  >
                                    <td>{{this.searchOrderInfo.prod_order}}</td>
                                    <td>{{this.searchOrderInfo.okvid_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </b-modal>
                    <div class="input-group" style="width: 20%; display: inline-flex;">
                        <input type="text" id="m-search" class="form-control" placeholder="Поиск по ГП . . ." style="padding: 3px 5px;" v-model="serachGp">
                        <span class="input-group-append search-btn btn btn-primary" style="padding: 0;" @click="searchGp()" v-b-modal.searchGpModal>
                            <i class="feather icon-search input-group-text"></i>
                        </span>
                    </div>
                    <b-modal id="searchGpModal" title="ГП заказов" v-if="this.searchGpInfo">
                        <table  class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">№ ГП</th>
                                    <th scope="col">Оквид</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr  v-for="(item, key) in searchGpInfo" :key="key">
                                    <td>{{item.gp_code}}</td>
                                    <td>{{item.okvid_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </b-modal>
                    <div class="input-group" style="width: 20%; display: inline-flex;">
                        <input type="text" id="m-search" class="form-control" placeholder="Поиск по Валу . . ." style="padding: 3px 5px;" v-model="searchShaftInput">
                        <span class="input-group-append search-btn btn btn-primary" style="padding: 0;" @click="searchShaft()" v-b-modal.searchShaftModal>
                            <i class="feather icon-search input-group-text"></i>
                        </span>
                    </div>
                    <b-modal id="searchShaftModal" title="Валы заказов" size="lg" v-if="this.searchShaftInfo">
                        <div class="card statistial-visit">
                            <div class="card-header">
                            </div>
                            <table  class="table table-hover" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th scope="col">Вал</th>
                                        <th scope="col">Папка</th>
                                        <th scope="col">Оквид</th>
                                        <th scope="col">Формат</th>
                                        <th scope="col">Статус</th>
                                        <th scope="col">Ячейка</th>
                                        <th scope="col">Дата списания</th>
                                        <th scope="col">Перемещ.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(shaft_order, key) in this.searchShaftInfo" :key="key">
                                        <td>{{shaft_order.shaft_id}}</td>
                                        <td v-if="shaft_order.order.prod_order != null">{{shaft_order.order.prod_order}}</td>
                                        <td v-if="shaft_order.order.prod_order == null"></td>
                                        <td>{{String(shaft_order.okvid_number).slice(0,String(shaft_order.okvid_number).length-2)+'-'+String(shaft_order.okvid_number).slice(-2)}}</td>
                                        <td>{{shaft_order?.order?.phototag_step*shaft_order?.order?.fragment_in_circulation}}</td>
                                        <td>{{shaft_order.order_status}}</td>
                                        <td>{{shaft_order.warehouse_place}}</td>
                                        <td>{{shaft_order.writeoff_date}}</td>
                                        <td>{{shaft_order?.order?.request_shaft?.document_number}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    
                                </tfoot>
                            </table>
                        </div>
                    </b-modal>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Партия</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.prod_order), checkNavParametr(selectOrder.prod_order)" v-model="selectOrder.prod_order">
                            </div>
                            <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Кол-во валов</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.cylinder_quantity)" v-model="selectOrder.cylinder_quantity">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Формат</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.format)" v-model="selectOrder.format">
                                    <option v-for="(item, key) in this.formats" :key="key" >
                                        {{item.format}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Степпинг</span>
                                </div>
                                <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.gradation_increase)" v-model="selectOrder.gradation_increase">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="color: blue;">Профиль</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.profile)" v-model="selectOrder.profile">
                                    <option v-for="(item, key) in this.profile" :key="key">
                                        {{item.short_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Статус</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.order_status)" v-model="selectOrder.order_status">
                                    <option v-for="(item, key) in this.orderstatus" :key="key" >
                                        {{item.order_status}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Тех. состояние</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.condition)" v-model="selectOrder.condition">
                                    <option v-for="(item, key) in this.orderstate" :key="key" >
                                        {{item.order_state}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Грав. из проф.</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.supplier_engraving)" v-model="selectOrder.supplier_engraving">
                                    <option v-for="(item, key) in this.vendors" :key="key">
                                        {{item.vendor_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Репро</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.repro)" v-model="selectOrder.repro">
                                    <option v-for="(item, key) in this.vendors" :key="key">
                                        {{item.vendor_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Гравировка</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.engraving)" v-model="selectOrder.engraving">
                                    <option v-for="(item, key) in this.vendors" :key="key">
                                        {{item.vendor_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="color: blue;">Спец. Оквид</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.designer_new)" v-model="selectOrder.designer_new">
                                    <option v-for="(item, key) in this.designers" :key="key">
                                        {{item.fio}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Менеджер ОСЗ</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.manager_osz)" v-model="selectOrder.manager_osz">
                                    <option v-for="(item, key) in this.osz " :key="key">
                                        {{item.manager_fio}}
                                    </option>
                                </select>
                            </div>  
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Базы отправят с</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.supplier)" v-model="selectOrder.supplier">
                                    <option v-for="(item, key) in this.vendors" :key="key">
                                        {{item.vendor_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Дата утверждения заказа</span>
                                </div>
                                <input type="date" class="form-control" v-model="selectOrder.request_engraving_date" @change="submit(selectOrder.request_engraving_date)">
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="color: blue;">Заявка на монтаж</span>
                                </div>
                                <select class="form-control" @change="submit(selectOrder.application_install_completed)" v-model="selectOrder.application_install_completed">
                                    <option v-for="(item, key) in this.designers" :key="key">
                                        {{item.fio}}
                                    </option>
                                </select>
                                <input style="width:100%;" type="date" class="form-control" v-model="selectOrder.request_install_date" @change="submit(selectOrder.request_install_date)">
                            </div>                     
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Повтор с:</span>
                                        </div>
                                        <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.test_number)" v-model="selectOrder.test_number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">Кол-во секций:</span>
                                        </div>
                                        <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="addShaftSection()"  v-model="SelectQtySection">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Комментарий внутр-ий</font></font></label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" @change="submit(selectOrder.internal_comment)" v-model="selectOrder.internal_comment"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-check_materials" style="border: 0.1px solid grey; padding: 5px; border-radius: 5px; margin-bottom: 5px;">
                        <label for="">Материалы заказчика:</label>
                        <div class="form-check">
                            <input class="form-check-input" id="electronicfile" type="checkbox" @change="submit(selectOrder.electronic_file)" v-model="selectOrder.electronic_file" :value="selectOrder.electronic_file">
                            <label for="electronicfile">Электронный файл</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" id="promosample" type="checkbox" @change="submit(selectOrder.promo_sample)" v-model="selectOrder.promo_sample" :value="selectOrder.promo_sample">
                            <label for="promosample">Промообразец</label>
                        </div>
                    </div>
                    <div class="form-check_job" style="border: 0.1px solid grey; padding: 5px; border-radius: 5px; margin-bottom: 5px;">
                        <label for="">Вид работы:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.new_job)" v-model="selectOrder.new_job" :value="selectOrder.new_job">
                            <label for="inputCity">Новая работа</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.order_reengraving)" v-model="selectOrder.order_reengraving" :value="selectOrder.order_reengraving">
                            <label for="inputCity">Перегравировка</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.engraving_with_change)" v-model="selectOrder.engraving_with_change" :value="selectOrder.engraving_with_change">
                            <label for="inputCity">Грав. с изм.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.diametr_change)" v-model="selectOrder.diametr_change" :value="selectOrder.diametr_change">
                            <label for="inputCity">Изм. диаметра</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.overchroming)" v-model="selectOrder.overchroming" :value="selectOrder.overchroming">
                            <label for="inputCity">Перехром</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.prepress)" v-model="selectOrder.prepress" :value="selectOrder.prepress">
                            <label for="inputCity">Монтаж</label>
                            </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.color_proof)" v-model="selectOrder.color_proof" :value="selectOrder.color_proof">
                            <label for="inputCity">Цветопроба</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.test_print)" v-model="selectOrder.test_print" :value="selectOrder.test_print">
                            <label for="inputCity">Пробопечать</label>
                        </div>
                    </div>
                    <div class="form-check_tech" style="border: 0.1px solid grey; padding: 5px; border-radius: 5px; margin-bottom: 5px;">
                        <label for="">Технологические элементы:</label>
                        <table style="font-size: 13px;">
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Слева</td>
                                    <td>Справа</td>
                                </tr>
                                <tr>
                                    <td>метки привода</td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.drive_label_left)" v-model="selectOrder.drive_label_left" :value="selectOrder.drive_label_left"></td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.drive_label_right)" v-model="selectOrder.drive_label_right" :value="selectOrder.drive_label_right"></td>
                                </tr>
                                    <tr>
                                    <td>светофор</td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.traffic_lights_left)" v-model="selectOrder.traffic_lights_left" :value="selectOrder.traffic_lights_left"></td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.traffic_lights_right)" v-model="selectOrder.traffic_lights_right" :value="selectOrder.traffic_lights_right"></td>
                                </tr>
                                    <tr>
                                    <td>кресты</td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.cross_left)" v-model="selectOrder.cross_left" :value="selectOrder.cross_left"></td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.cross_right)" v-model="selectOrder.cross_right" :value="selectOrder.cross_right"></td>
                                </tr>
                                    <tr>
                                    <td>линия реза</td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.cutting_line_left)" v-model="selectOrder.cutting_line_left" :value="selectOrder.cutting_line_left"></td>
                                    <td style="text-align: center;"><input type="checkbox" @change="submit(selectOrder.cutting_line_right)" v-model="selectOrder.cutting_line_right" :value="selectOrder.cutting_line_right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-check_job" style="border: 0.1px solid grey; padding: 5px; border-radius: 5px; margin-bottom: 5px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.marker_cs)" v-model="selectOrder.marker_cs" :value="selectOrder.marker_cs">
                            <label for="inputCity">метка ColdSeal</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.shevrony)" v-model="selectOrder.shevrony" :value="selectOrder.shevrony">
                            <label for="inputCity">шевроны</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" @change="submit(selectOrder.сentering_line)" v-model="selectOrder.сentering_line" :value="selectOrder.сentering_line">
                            <label for="inputCity">Линия центрирования</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="print_condition" style="border: 0.1px solid grey; padding: 10px; border-radius: 5px; margin-bottom: 5px;">
                        <label for="">Условия печати:</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Первичка</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.primary)" v-model="selectOrder.primary">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Ширина мат-ла</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.material_width)" v-model="selectOrder.material_width">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Вторичка</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.secondary)" v-model="selectOrder.secondary">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Краски</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.paint_series)" v-model="selectOrder.paint_series">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Вывод</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.output)" v-model="selectOrder.output">
                        </div>
                    </div>
                    <div class="print_condition" style="border: 0.1px solid grey; padding: 10px; border-radius: 5px; margin-bottom: 5px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Ширина ручья</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.width_stream)" v-model="selectOrder.width_stream">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Шаг печати</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.phototag_step)" v-model="selectOrder.phototag_step">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Кол-во ручьев</span>
                            </div>
                            <select class="form-control" @change="submit(selectOrder.amount_stream)" v-model="selectOrder.amount_stream">
                                <option v-for="(item, key) in this.amountStreamArr" :key="key">
                                    {{item}}
                                </option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Цвет линии реза</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.cut_line_color)" v-model="selectOrder.cut_line_color">
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Фрагм. в обороте</span>
                            </div>
                            <select class="form-control" @change="submit(selectOrder.fragment_in_circulation)" v-model="selectOrder.fragment_in_circulation">
                                <option v-for="(item, key) in this.treamReleaseArr" :key="key">
                                    {{item}}
                                </option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">Число стадий</span>
                            </div>
                            <select class="form-control" @change="submit(selectOrder.number_stages)" v-model="selectOrder.number_stages">
                                <option v-for="(item, key) in this.qtyStage" :key="key" >
                                    {{item}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Длина гильзы</span>
                        </div>
                        <select class="form-control" @change="submit(selectOrder.sleeve_lenght)" v-model="selectOrder.sleeve_lenght">
                            <option v-for="(item, key) in this.sleeveLenght" :key="key" >
                                {{item}}
                            </option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Ширина гр-ки</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.width_engraving)" v-model="selectOrder.width_engraving">
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Варианты намотки</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" @change="submit(selectOrder.winding_option)" v-model="selectOrder.winding_option">
                    </div>
                    <div class="form-check" style="display: inline-flex;">
                        <input class="form-check-input" type="checkbox" @change="submit(selectOrder.print)" v-model="selectOrder.print" :value="selectOrder.print">
                        <label for="inputCity">Уточненая</label>
                    </div>
                    <div class="form-check" style="display: inline-flex; margin-left: 15px;">
                        <input class="form-check-input" type="checkbox" @change="submit(selectOrder.no_folder)" v-model="selectOrder.no_folder" :value="selectOrder.no_folder">
                        <label for="inputCity">Без выгрузки</label>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Последнее перем-ие</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="selectLastRequestOrder.document_number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Обратная связь от технологов</font></font></label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" @change="submit(selectOrder.feedback_technologist)" v-model="selectOrder.feedback_technologist"></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Комментарий для гравировщика</font></font></label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" @change="submit(selectOrder.external_comment)" v-model="selectOrder.external_comment"></textarea>
                    </div>
                </div>
            </div>
         </div>
        </div>
        </div>
        <div class="cards_shaft_stream">
        <div class="card attributes-card">
            <div class="card-header">
                <h5>
                    ГП заказа:
                </h5> 
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" data-toggle="modal" data-target="#modalAddGp" class="btn dropdown-toggle">
                            <i class="feather icon-plus-circle">
                            </i>
                        </button>
                    </div>
                    <div class="modal fade bd-example-modal-lg show" id="modalAddGp">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title h4" id="myLargeModalLabel">Добавить ГП</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Код ГП</span>
                                </div>
                                <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="gp">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">Кол-во</span>
                                </div>
                                <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="qty">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-lg-12">
                                <button  class="btn btn-primary btn-sm" @click="addGp()">Добавить</button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div> 
            <div class="card-body px-3 py-0">
                <div class="table-responsive Recent-Users">
                    <table class="table table-striped table-bordered" style="font-size: 12px;">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Код ГП</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Заказчик</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Категория</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Бренд</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Название</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Кол-во</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Прим.</th>
                                <th class="sorting" tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1">Сап</th>
                                <th tabindex="0" aria-controls="okvid-table" rowspan="1" colspan="1"></th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr v-for="(gp, key) in this.selectStream" :key="key">
                            <td>{{gp[0].gp_code}}</td>
                            <td>{{gp[0].customer}}</td>
                            <td>{{gp[0].product_category}}</td>
                            <td>{{gp[0].brand}}</td>
                            <td>{{gp[0].description}}</td>
                            <td>{{gp[1].quantity}}</td>
                            <td>{{gp[1].note}}</td>
                            <td>{{gp[0].sap_code}}</td>
                            <td class="text-center">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-settings"></i>
                                    </button>
                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-106px, 41px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <li style="text-align: center;">
                                            <a >
                                                <span style="cursor: pointer;" @click="deleteGp(gp[0].gp_code)">Удалить</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            </tr>         
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
        <b-tabs style="padding: 10px 0px !important">
            <div class="card-header-right" style="justify-content: flex-end; display: flex;">
                    <div class="btn-group card-option">
                        <button type="button"  class="btn" v-b-modal.copyShaftsOrder>
                            <i class="feather icon-copy">
                            </i>
                        </button>
                    </div>
                    <b-modal id="copyShaftsOrder">
                        <div class="card">
                            <div class="card-body">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">№ ОКВИД</span>
                                    </div>
                                    <input type="text" class="form-control" v-model="CopyShaftsOkvid" aria-describedby="inputGroup-sizing-sm">
                                </div>
                                <button class="btn btn-primary btn-sm" @click="copyShaftParametrs()">Копировать</button>
                            </div>
                        </div>  
                    </b-modal>
                    <div class="btn-group card-option">
                        <button type="button" @click="addShaft()"  class="btn">
                            <i class="feather icon-plus-circle">
                            </i>
                        </button>
                    </div>
            </div>
            <b-tab title="Валы заказа" active>
                <div class="card-body px-3 py-0">
                    <div class="table-responsive Recent-Users" style="min-height: 400px;">
                        <table class="table table-striped table-bordered" style="font-size: 11px; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th  scope="col">№</th>
                                    <th  scope="col">Грав.</th>
                                    <th  scope="col">ID вала</th>
                                    <th  scope="col">ff</th>
                                    <th  scope="col">цвет</th>
                                    <th  scope="col">цвет DF</th>
                                    <th  scope="col">П.</th>
                                    <th  scope="col">Линиатура</th>
                                    <th  scope="col">Угол</th>
                                    <th  scope="col">Резец</th>
                                    <th  scope="col">Примечание</th>
                                    <th  scope="col"></th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="(shaftorder, key) in sortArrayShaftOrder(selectShafts)" :key="key" v-bind:class="{ line_opacity_true: shaftorder.written_off }">
                                    <td class="tabledit-view-mode">{{shaftorder.shaft_order_number}}</td>
                                    <td><input type="checkbox" @change="submitShaft(shaftorder.dispatch,shaftorder,'dispatch')" v-model="shaftorder.dispatch" :value="shaftorder.dispatch"></td>
                                    <td>
                                        <div class="select_shaft">
                                            <v-select :value="shaftorder.shaft_id"
                                                    v-on:input="submitShaft($event.shaft_id,shaftorder,'shaft_id')"
                                                    :options="ShaftsArr" 
                                                    label="shaft_id"
                                                ></v-select>
                                        </div>
                                    </td>
                                    <td><input type="text" readonly style="width: 100px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" v-model="shaftorder.ff"></td>
                                    <td>
                                        <div class="select_shaft" >
                                            <v-select  :value="shaftorder.color"
                                                    v-on:input="submitShaft($event.color_description,shaftorder,'color')"
                                                    :options="ColorsArr" 
                                                    label="color_description"
                                                ></v-select>
                                        </div>
                                    
                                    </td>
                                    <td><input type="text" style="width: 100px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder.panton,shaftorder,'panton')" v-model="shaftorder.panton"></td>
                                    <td><input type="checkbox" @change="submitShaft(shaftorder.base,shaftorder,'base')" v-model="shaftorder.base" :value="shaftorder.base"></td>
                                    <td><input type="text" style="max-width: 90px !important;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder.corner,shaftorder,'corner')" v-model="shaftorder.lineature"></td>
                                    <td><input type="text" style="max-width: 90px !important;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder.corner,shaftorder,'corner')" v-model="shaftorder.corner"></td>
                                    <td><input type="text" style="max-width: 90px !important;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder.cutter,shaftorder,'cutter')" v-model="shaftorder.cutter"></td>
                                    <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder.note,shaftorder,'note')" v-model="shaftorder.note"></td>
                                    <td class="text-center">
                                    <div class="btn-group card-option">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="feather icon-settings"></i>
                                            </button>
                                            <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="padding: 10px; font-size: 14px; position: absolute; transform: translate3d(-106px, 41px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <li style="text-align: center;">
                                                    <a>
                                                        <span style="cursor: pointer;" @click="deleteShaft(shaftorder.id), getShafts()">Удалить</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>             
                            </tbody>
                        </table>
                        <b-modal
                            v-model="show"
                            id="shaftModal" 
                            title="Ресурс валов"
                        >
                            <div class="card statistial-visit">
                                <div class="card-header">
                                    <h5>Вал:{{shaft_id}}</h5>
                                    <span class="text-muted d-block mt-1">Status : reserved</span>
                                </div>
                                <table  class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">№ партии</th>
                                            <th scope="col">Метраж</th>
                                            <th scope="col">Дата</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(resourse, key) in selectShaftResourses" :key="key">
                                        <td><input type="text" class="form-control" v-model="resourse.batch" @change="submitResource(resourse)"></td>
                                        <td><input type="text" class="form-control" v-model="resourse.edition_batch" @change="submitResource(resourse)"></td>
                                        <td>
                                            <input type="date" class="form-control" v-model="resourse.batch_date" @change="submitResource(resourse)">
                                        </td>
                                        <td style="vertical-align: middle; color: red; cursor: pointer;"><i class="feather icon-delete" @click="DeleteResource(resourse)"></i></td>
                                        
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        
                                    </tfoot>
                                </table>
                                <div style="margin-top: 10px; padding: 1rem;" class="row">
                                    <button @click="ShaftResourceModal=true" class="btn btn-primary btn-sm">Добавить Ресурс</button>
                                </div>
                                <b-modal
                                    v-model="ShaftResourceModal"
                                    id="ShaftResourceModal" 
                                >                                
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">№ партии</span>
                                                </div>
                                                <input type="text" class="form-control" v-model="ShaftBath" aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-sm">Метраж</span>
                                                </div>
                                                <input type="text" class="form-control" v-model="ShaftEditionBath" aria-describedby="inputGroup-sizing-sm">
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control" v-model="ShaftBathDate">
                                            </div>
                                            <button class="btn btn-primary btn-sm" @click="AddResourse()">Добавить</button>
                                        </div>
                                    </div>                              
                                </b-modal>                             
                            </div>
                        </b-modal>
                        <b-modal
                            v-model="showTransfer"
                            id="shaftModalTransfer" 
                            title="Переброс валов на заказ"
                        >
                            <div class="card-block">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Номер ОкВид</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm"  v-model="OkvidTransfer">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">Номер секции</span>
                                    </div>
                                    <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm"  v-model="SectionTransfer">
                                </div>
                                <button class="btn btn-primary btn-sm" @click="shaftTransfer()">ПЕРЕБРОСИТЬ</button> 
                            </div>
                        </b-modal>
                    </div>
                </div> 
                <div class="card-body px-3 py-0">
                    <div class="" style="vertical-align: bottom;">
                            <img width="400" height="180" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAZABkAAD/7AARRHVja3kAAQAEAAAACgAA/+ENO2h0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8APD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNC4yLjItYzA2MyA1My4zNTI2MjQsIDIwMDgvMDcvMzAtMTg6MTI6MTggICAgICAgICI+CiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgICB4bWxuczp4bXBSaWdodHM9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9yaWdodHMvIgogICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgeG1sbnM6SXB0YzR4bXBDb3JlPSJodHRwOi8vaXB0Yy5vcmcvc3RkL0lwdGM0eG1wQ29yZS8xLjAveG1sbnMvIgogICB4bXBSaWdodHM6V2ViU3RhdGVtZW50PSIiCiAgIHBob3Rvc2hvcDpBdXRob3JzUG9zaXRpb249IiI+CiAgIDxkYzpyaWdodHM+CiAgICA8cmRmOkFsdD4KICAgICA8cmRmOmxpIHhtbDpsYW5nPSJ4LWRlZmF1bHQiLz4KICAgIDwvcmRmOkFsdD4KICAgPC9kYzpyaWdodHM+CiAgIDxkYzpjcmVhdG9yPgogICAgPHJkZjpTZXE+CiAgICAgPHJkZjpsaT5hbGV4ZXk8L3JkZjpsaT4KICAgIDwvcmRmOlNlcT4KICAgPC9kYzpjcmVhdG9yPgogICA8ZGM6dGl0bGU+CiAgICA8cmRmOkFsdD4KICAgICA8cmRmOmxpIHhtbDpsYW5nPSJ4LWRlZmF1bHQiLz4KICAgIDwvcmRmOkFsdD4KICAgPC9kYzp0aXRsZT4KICAgPHhtcFJpZ2h0czpVc2FnZVRlcm1zPgogICAgPHJkZjpBbHQ+CiAgICAgPHJkZjpsaSB4bWw6bGFuZz0ieC1kZWZhdWx0Ii8+CiAgICA8L3JkZjpBbHQ+CiAgIDwveG1wUmlnaHRzOlVzYWdlVGVybXM+CiAgIDxJcHRjNHhtcENvcmU6Q3JlYXRvckNvbnRhY3RJbmZvCiAgICBJcHRjNHhtcENvcmU6Q2lBZHJFeHRhZHI9IiIKICAgIElwdGM0eG1wQ29yZTpDaUFkckNpdHk9IiIKICAgIElwdGM0eG1wQ29yZTpDaUFkclJlZ2lvbj0iIgogICAgSXB0YzR4bXBDb3JlOkNpQWRyUGNvZGU9IiIKICAgIElwdGM0eG1wQ29yZTpDaUFkckN0cnk9IiIKICAgIElwdGM0eG1wQ29yZTpDaVRlbFdvcms9IiIKICAgIElwdGM0eG1wQ29yZTpDaUVtYWlsV29yaz0iIgogICAgSXB0YzR4bXBDb3JlOkNpVXJsV29yaz0iIi8+CiAgPC9yZGY6RGVzY3JpcHRpb24+CiA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgCjw/eHBhY2tldCBlbmQ9InciPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAUEBAZEhknFxcnMiYfJjIuJiYmJi4+NTU1NTU+REFBQUFBQUREREREREREREREREREREREREREREREREREREREARUZGSAcICYYGCY2JiAmNkQ2Kys2REREQjVCRERERERERERERERERERERERERERERERERERERERERERERERERET/wAARCAOCCPMDASIAAhEBAxEB/8QA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
                            aqpOab4DdAGlqqk5pvgGqqTmm+A3QBpaqpOab4Bqqk5pvgN0AaWqqTmm+AaqpOab4DdAGlqqk5pvgGqqTmm+A3QBpaqpOab4Bqqk5pvgN0AaWqqTmm+AaqpOab4DdAHlrUaiNTAiYEPQAAAACN2c0T6bvEWSN2c0T6bvEBt3nXtoYVkXC5cDE7pGuOKNXLWVL25aquTlOS3uqVr0uzWDWty8jJW3at8aEr+K/+76n3gKd6VMTqWVGvaqq1cCOQl9mZo42SZbkbardtbN8w1fZzo0L5uVtyUtsyLPGat1XRrFHLl5GSqJ6tu330A7VrkclrVtRd1D0abYn0lLycfpPYz0bU21TuEy5b4krJXRTqlqpayxLNrbA3pLlpJFc5zLXOVVVcpdte+c7X0slzTtkgcuS71e9tou+dmcx2onaqxwphclrl7m8B0FJUJUwsmT5yWk9/7q34P+SmxdESxUkbXbdlvhwmu/8AdW/B/wAlArgAAAABhnpYqhESVqORNq0zADS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfAbTGNjajGpY1EsRD2AAAAAACJcH+/4ilsiXB/v+IpbAGOWVImOkdtNRVXvGQh9pKrkoEhTbkXDwJsQDnqatYlX0qoRVwq+xu/ueAv/AMop/Yf8nlPNyXVC+mSSdiOc9VVLdxNmEp6qpOab4AOQvOsjqqjl4UVtqJbbvodlQVSVcDJk21TDw7pPvO6IFpnrCxGvamUlibxo9mKuxX0zt302+MDqAABJqf3KDivxKViTU/uUHFfiUrAAAAAAGGenjqG5MrUciLbYpg1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA8RRNiajGJY1NpEPYAAAACHculVfxPG4uEO5dKq/ieNwFwAATL90KT6P2kIVyXXDXMe6XKtaqImSpdv3QpPo/aQ0Oy3u5eMgGx/GqTffnJ5DRruzvIMWWmcqq3Dkrt95UOoNatqWU0LpJFwIi2d1d4CR2fvR9RbTzLa5qWtcu2qd06A47s1ErqpXptNatvfOxAEm8dMpOGTEhWJN46ZScMmJAKwAAAAAYpoI525ErUc3eUygDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfANVUnNN8BugDS1VSc03wDVVJzTfAboA0tVUnNN8A1VSc03wG6ANLVVJzTfAbMMLIW5EaI1qbiGQAAAAAAEih0+q/LxFckUOn1X5eIrgAAAAAAAAAAAAAAAAAAANK9tEl4qm6aV7aJLxVA9XbosXEbiNs1Lt0WLiNxG2AJV+UktXTpHC3KdlItlqJgsXfKoAn3PTyU1K2OVMlyK61O/wBwoAAczXXJNDL0ihXdtyUWxU4O53O8YpH3vUN5JWqiLgVbEb8p1YAj3Pc6UKLJJYsq720iFgACTdmlVXHb4ysSbs0qq47fGVgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA1rw0aX4b8SmC5tDi4PGpnvDRpfhvxKYLm0OLg8agUAAAObfdlSt49IRn4eWjsrKTa4LbTpAAAAHl6WtVE3jnriu2opJ3PmZktVqptouG1N5TowAJFH+41PBH9krkij/cangj+yBXAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxVHun8V2IymKo90/iuxAT7g0JnC77SlUlXBoTOF32lKoA0rzhfPTPjjS1zkwJ3zdAEe4aKakje2ZuSqutTCi7ncNu82TSUz2QJa9yWbaJg3dvuG6AOKiue8YVyo2q1d9r2p4zN0K+N9/wD9U/8AI68Ach0K+N9//wBU/wDI6ejbI2FjZfXRqZVq24TYAEmL90k+EmNCsSYv3ST4SY0KwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACN2c0T6bvEWSN2c0T6bvEBZAAGpeETpqeSNiWuc2xEJ9wUM1G2RJ25KuVLMKLiLYAHN3jcUiSdIo1sW3KybbLF/tU6QAct0m+LMjJW3fyUx7RkoLhkdJy9atq225Nttq/wBynSgASH/urfg/5KVyQ/8AdW/B/wAlArgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIlwf7/iKWyJcH+/4ilsAcxe121ddVZSM/DSxrVym7W6tltp04A8RxpExGN2moiJ3j2AB8OUS6Kukq+VgZaxrrW+k1PR3tvewHWAAAAJNT+5QcV+JSsSan9yg4r8SlYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABDuXSqv4njcXCHculVfxPG4C4AANG9oH1FK+KJLXLk2J30Oahuu84EVIkc1F28mRE/yOzAHIdCvjff8A/VP/ACPrbhrqlyLO6zuvdlL4zrgBp0FBHQx8nHhVcLnLuqbgAAk3jplJwyYkKxJvHTKThkxIBWAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAASKHT6r8vEVyRQ6fVfl4iuAAAAAAAAAAAAAAAAAAAA0r20SXiqbppXqltJLxVA9XbosXEbiNsgUVBVPgjcypVrVa1UbkbXymxq2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84H27NKquO3xlYhMuSoY5z21Tkc/C5UZt/WMmqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAULw0aX4b8SmC5tDi4PGpoVV2VTIXudVOciNcqtydvBtesY7soqqWmY+OoVjVtsbkW2YV7oHRgkatrOtuzPONW1nW3ZnnArgkatrOtuzPONW1nW3ZnnArgkatrOtuzPONW1nW3ZnnArgkatrOtuzPONW1nW3ZnnArkij/cangj+yNW1nW3ZnnMKXJUNe6VKlUe6zKVGbdn0gLoI2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsmKo90/iuxEvVVX1t2b948SXVVIxVWrcqWLgyfvAZ7g0JnC77SlU5m6KOpmpmujqFjbavo5Fu7wm/q2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84FcEjVtZ1t2Z5xq2s627M84H2L90k+EmNCsQ9S1HKLL0pctUyVdkbmce9VVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAskbs5on03eIaqq+tuzfvE+5qSomgVYp1jajlTJRtu9h2wOpBI1bWdbdmecatrOtuzPOBXBI1bWdbdmecatrOtuzPOBXBI1bWdbdmecatrOtuzPOBXBI1bWdbdmecatrOtuzPOBXJD/3Vvwf8lGrazrbszzmJ1y1DpOVWpXLRMnKyNzOAuAjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gLII2qqvrbs37w1VV9bdm/eAsgjaqq+tuzfvDVVX1t2b94CyCNqqr627N+8NVVfW3Zv3gPNwf7/AIils5S7Lvnm5Xk53R5L1atiet3dtCjqir62/wAC/wDkBaBF1RV9bf4F/wDIaoq+tv8AAv8A5AWgRdUVfW3+Bf8AyGqKvrb/AAL/AOQFoEXVFX1t/gX/AMhqir62/wAC/wDkBaBF1RV9bf4F/wDIaoq+tv8AAv8A5AZKn9yg4r8SlYgrcdQr0kWqcr27TsnCn1jLqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkh3LpVX8TxuPeqqvrbs37xMu+gnlmnaydzFY6xzkT1sK4Vw7LQOsBF1RV9bf4F/8hqir62/wL/5AWgRdUVfW3+Bf/Iaoq+tv8C/+QFoEXVFX1t/gX/yGqKvrb/Av/kBaBF1RV9bf4F/8hqir62/wL/5AWiTeOmUnDJiQx6oq+tv8C/+RjdcdQ9zXuqnK5vqqrdq36QF4EbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8BZBG1VV9bdm/eGqqvrbs37wFkEbVVX1t2b94aqq+tuzfvAWQRtVVfW3Zv3hqqr627N+8B6odPqvy8RXIV0RPhq6hj3q9yIy1y7uAugAAAAAAAAAAAAAAAAAAANO89El4jsRuGneeiS8R2ID7duixcRuI2zUu3RYuI3EbYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGtX6NLxH4lNa49Cj+l9pTZr9Gl4j8SmtcehR/S+0oFIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxzeo7gUyGOb1HcCgTez2ht4XYysSez2ht4XYysAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIvZvRnfEdiQtEXs3ozviOxIBaAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEa4v8Af8VxZI1xf7/iuLIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAiXPpNX8TxuLZEufSav4njcBbAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEih0+q/LxFckUOn1X5eIrgAAAAAAAAAAAAAAAAAAANO89El4jsRuGneeiS8R2ID7duixcRuI2zUu3RYuI3EbYAAAAAAAAAAmz3zBE7k2Wyv9mNLQKQJC11e/DHTWcd6YsBhkvK8YUtfTp9FbcVoF0HOU9+VVSuTFE1XJuZdi+BVNvpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTynzpd59Xbnp5QLII/TrxZhkpkVP7Xp5z3DfkLncnMjoX70iWJ4fLYBVB8RUVLU2j6AAAAAAAABrV+jS8R+JTWuPQo/pfaU2a/RpeI/EprXHoUf0vtKBSAAAAAAAAAAAE6pvingdyaKsj/YjS1TD0+uk91TWJ/e9E+TABXBCkvG8YktfTovFW3FaYIL9qp3cnHE1XeyrrF+WwDpAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXn1duenlCVt4swvpkVP7Xp5wLAJMV+RZXJ1DXQv8A70weHylRrkciK1bUXdQD0AAAAAAAAY5vUdwKZDHN6juBQJvZ7Q28LsZWJPZ7Q28LsZWAAAAAAAAAAGhVXtT0zshVVz/YZhUDfBI1hWyYYqZUT+9yJ8mAxPr7yjS11Oi8VbcSqBcBzcV/VMz+TbE3L9lzslflsNzpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI/TLy6u3PTyjpl5dXbnp5QLAI6Vl5Nwup0VP7XpbjU+x35G12RUsdC5faTB4fMBXB5Y9r0RzVRUXaVNo9AAAAAAAAACL2b0Z3xHYkLRF7N6M74jsSAWgAAAAAAAAAABo1d6U9KuS91r/YbhXZwmtrGrlww0zrN97sn5AK4Ij668o0tdTovFW3Eqmqy/6l7+SSJqP3nOycdgHSgj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXl1duenlAsAj9MvLq7c9PKOmXk3C6naqdx6W4wLAI7b8YxcmqjfCq7rktTw+YqxyNlaj2KjmrtKgHsAAAAAAAAAARri/3/ABXFkjXF/v8AiuLIAAAAAAAAAA06u8qekwSO9L2W4XbOEDcBI1nVS+4pnWb73ZPyecxurbzZhdToqf2ut8agWwc0naCpV/JrE1r957snHYbnTLy6u3PTygWQR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWAR+mXl1duenlHTLy6u3PTygWARul3mmFadtnHTyn1L8SNcmrifDbu2Wt8PkAsAxxTMmaj43I5q7qGQAAAAAAAAARLn0mr+J43FsiXPpNX8TxuAtgAAAAAAAAAADUq7wgo0/FdYu41MK+A09aVM2GnpnKm+9cnZ4QK4Irq282YVp2qncdb4zUXtBUtfyboUa7eeuTjsA6UEZKy8lwpTtz08p96ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08o6ZeXV256eUCwCP0y8urtz08p86Xee30dtnHTygWQR0vtYlsq4XxJ7XrN8PktKcNRHUNy4nI5u+gGUAAAAAAAAAASKHT6r8vEVyRQ6fVfl4iuAAAAAAAAAAAAAAAAAAAA07z0SXiOxG4ad56JLxHYgPt26LFxG4jbNS7dFi4jcRtgAAAAAAxyythYski2NTCqqeyJYt8T/AP8AliXPd5Nm6ARJ73W1VWKl3Paf5tmEq09LFTNyImo1DMiIiWJgRD6AAAGlWXbDV4XJY9Np7cDkNOCtlopEp61bWrgjm3+Ns8pZMNTTsqY1ikS1qgZgSLunfTSLQVC2qiWxP9pvlQrgAA
                            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
                            rV+QzADw2JjFVWtRFXCqom2ezyjkVVRFwptgegABJqf3KDivxKViTU/uUHFfiUrAAAAAAEm/NqD4zPGViTfm1B8ZnjKwAAAAAAAAAAAAAAAAAAAAAAAAAAADBV+5k4rsRnMFX7mTiuxAa1y6HFwLjUoE+5dDi4FxqUAAAAEO5dKq/ieNxcIdy6VV/E8bgLgAAgdqPcM4/iU2uz+hM4XY1NXtR7hnH8Sm12f0JnC7GoFUAAcTF/y3ojW4ESXJ7zlsxKdscS78a9MGH8VPkU7YASbx0yk4ZMSFYk3jplJwyYkArAAAAABJ7QaMnHaViT2g0ZOO0CsAAAAAAAAAAAAAAAAAAAAAAAAAAAAA8v9VeAmdn9CZwuxqU3+qvATOz+hM4XY1AqgAAAAJFDp9V+XiK5IodPqvy8RXAAAAAAAAAAAAAAAAAAAAaV7aJLxVN00r20SXiqB6u3RYuI3EaV6XLrCVJeUyLG5NmTbuqu+m+bt26LFxG4jbA5n+K/+76n3jSvO5OgRJLymXa5G2ZNm/3VOzJV+UktXTpHC3KdlItlqJgsXfAiXdcPTIWz8pk2quDJt2u+Wr0ufWD2vy8jJSz1bfGhmuenkpqVscqWORXYO/3CgBzP8V/931PvGpeNxdChWblMqxUSzJs2++p2JNvmmkqqZY4kynWpgtsxgc/dtx9Oi5XlMnCqWZNu130OwY3Jajd5LCbclLLS0/JzJkuylWy1FxFQCTdmlVXHb4ysSbs0qq47fGVgAAAAACVV0NQ+pSpp3tauRkeklu7afOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8xhnu+vqURk0rFajkdgbvd4uAAAAAAA1rw0aX4b8SmC5tDi4PGpnvDRpfhvxKYLm0OLg8agYb0ufWDmuy8jJSz1bfGhP/iv/u+p946YAcdeNw9ChWblMqxUSzJs2++ebtuPp8XK8pk4VbZk27XfQ6K+aaSppljiTKcqpg2sZ4uSllpKdY5kyXZSrZai728BnrpZKSlc+JEc5iJtpuJt+U1LlvV1cj2y2I9uFETeKzmo5FRcKLtnMVVxVFLLy1EtqbaJbY5PKB1JyPaOdJqhsLMKsSxbN925iMjp74lTk8lU7uSifKbd1XEtO/l6hcqRMLW7y7676gWKSLkIWRrtta1F7yE+j/cangj+yVyRR/uNTwR/ZArgAAAABLrqGeWdlRTva1zGq30kt2yoAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAhVN33hVRrFLKxWrZ83e7xbaliIm8egAAAAxVHun8V2IymKo90/iuxAT7g0JnC77Snq9bq1jkenkZGV82222zupvHm4NCZwu+0pVA5n+K/+76n3jWruz/RIHT8rlZNmDIs21s3zrzRvWB9RSvijS1zrLE76Acxddzawjc/lMixcmzJt8aHUTUauo1pkW1UZkIvdRMBqXDRzUkTmTNyVV1qYUXc7hYA5PszUJFK+B2BXolnC23Bs3jrDn70uJ0snSKVbHqtqt2sO+i75qpPfDU5PJW32slMe0A7TztfJHC3CrUVV79lh0NDCsFPHGu21qIvCRrtuORJekVi2uRcpG224d9VOiAkxfuknwkxoViTF+6SfCTGhWAAAAAAJt40U1RJFLA5rXR5Xrd0x8hefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5jFPQ3hURrFJKxWu2/R8xbAGOBixxtYu21ETwGQAAAABG7OaJ9N3iLJG7OaJ9N3iA2L0u3WDGsy8jJW3at8aEr+K/8Au+p946YAcnV9nOjwvl5W3JS2zIs8Zp3VdOsUeuXkZNnzbdvvoddeETpqeSNiWuc1URCdcFDNRtkSduTlK2zCi7Vu8BUpIOjQshttyUst2jWvibkaSRybapkp9LAUCVflLNVU6RwNynZSKuFEwYd8DQ7LwWNkmXdVGJ3sK+I6Qm3LSvpaZI5Usfaqqn9CkAJD/wB1b8H/ACUrkh/7q34P+SgVwAAAAAnXlRS1KxuhcjXRuyvSKIAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACLLR3jMx0b5Y8lyKi+jv94p0kKwQsiVbVa1G+AzgAAAAAAiXB/v+IpbIlwf7/iKWwBC7SUazQpM1MMe3xV8hdPioipYuFFAhdnrxZJElM9bHs9Xup5i8czX9nHI7lKRe7kKtlnApro6+IUyUy17yO+XCB09TUspY1lkWxqfKcldrZLwr+VwomVyjrN7e8RkS6bwr3ItQqom+9drgT+h0lBQR0MeRHtr6zl21A3AABJqf3KDivxKViTU/uUHFfiUrAAAAAAE+9KKSrYxInI1zHo+13ctMPIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+YchefPR5vmKwAk8hefPR5vmHIXnz0eb5isAJPIXnz0eb5hyF589Hm+YrACTyF589Hm+Y8vpbye1WuljsVLF9HzFgAatBTrSwMhcqKrU20NoAAAABDuXSqv4njcXCHculVfxPG4C4AAIHaj3DOP4lNrs/oTOF2NTxf1HNVxMbC3KVHWrhRNzukaO7r1ibkR5TWpuJIif5AdiaF53iyhjVVVOUVPQb3fIc/0C93YFV//ANfvGSn7NzSOyql6Im7ZhcB47O0jpp1qXbTLcO+5TrjDT07KdiRRJY1DMAJN46ZScMmJCsSbx0yk4ZMSAVgAAAAA0bzo31kPJsVEW1HWr3DeAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASeQvPno83zDkLz56PN8xWAEnkLz56PN8w5C8+ejzfMVgBJ5C8+ejzfMOQvPno83zFYASFp7zXBy0eb5jauykdRwNhcqKqW4U7qm6AAAAAACRQ6fVfl4iuSKHT6r8vEVwAAAAAAAAAAAAAAAAAAAGle2iS8VTdNK9tEl4qgert0WLiNxG2al26LFxG4jbAAAAAAAAAAACTdmlVXHb4ysSbs0qq47fGVgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA1rw0aX4b8SmC5tDi4PGpnvDRpfhvxKYLm0OLg8agUAAAAAAAAAAAJFH+41PBH9krkij/cangj+yBXAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxVHun8V2IymKo90/iuxAT7g0JnC77SlUlXBoTOF32lKoAAAAAAAAAAASYv3ST4SY0KxJi/dJPhJjQrAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAI3ZzRPpu8RZI3ZzRPpu8QFkAAAAAAAAAACQ/91b8H/JSuSH/ALq34P8AkoFcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABEuD/f8AEUtkS4P9/wARS2AAAAAAAAAAAEmp/coOK/EpWJNT+5QcV+JSsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAh3LpVX8TxuLhDuXSqv4njcBcAAAAAAAAAAAk3jplJwyYkKxJvHTKThkxIBWAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAASKHT6r8vEVyRQ6fVfl4iuAAAAAAAAAAAAAAAAAAAA16yBaiF8SLYrkstNgARI7ur4moxlQiNaliJkIe+hXj1lMxCwAI/Qrx6ymYg6FePWUzELAAj9CvHrKZiDoV49ZTMQsACP0K8espmIOhXj1lMxCwAI/Qrx6ymYg6FePWUzELAAhRXVWROe9tQiOetrlyEwmfoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAI0l3V0jVY6pRUcioqcmm6eIbtr4GJGyoRGptJkIXABH6FePWUzEHQrx6ymYhYAEfoV49ZTMQdCvHrKZiFgAR+hXj1lMxB0K8espmIWABH6FePWUzEHQrx6ymYhYAEfoV49ZTMQxR3VWskdKlQiPfZlLkbdhdAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NDy6gr3IrVqcC4PdoWABBp7rrqdiRxzo1qbSZJm6FePWUzELAAj9CvHrKZiDoV49ZTMQsACP0K8espmIOhXj1lMxCwAI/Qrx6ymYg6FePWUzELAAj9CvHrKZiDoV49ZTMQsACE26q1JVn6QiPVMlXZCbRn6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACR0K8OtfpoYKa6a2mZkRTo1tttmSXgBH6FePWUzEHQrx6ymYhYAEfoV49ZTMQdCvHrKZiFgAR+hXj1lMxB0K8espmIWABH6FePWUzEHQrx6ymYhYAEfoV49ZTMQxaqrVlSdahMtEycrI3MRdAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBAguesp8rk50blLavo7Zm6DePWUzSyAI3Qbx6ymaOg3j1lM0sgCN0G8espmjoN49ZTNLIAjdBvHrKZo6DePWUzSyAI3Qbx6ymaOg3j1lM0sgCEt01rpGzOqEy22o1cjatM/Qrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NB0K8OtfpoVgBJ6FeHWv00HQrw61+mhWAEnoV4da/TQdCvDrX6aFYASehXh1r9NDWiueshe98c6Ir1tcuTt7LS+AI3Qbx6ymaOg3j1lM0sgCN0G8espmjoN49ZTNLIAjdBvHrKZo6DePWUzSyAI3Qbx6ymaOg3j1lM0sgCN0G8espmmJ901sj2SPqEVzLclcnatLwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJPQrw61+mg6FeHWv00KwAk9CvDrX6aDoV4da/TQrACT0K8OtfpoOhXh1r9NCsAJ1BQSU0kkssnKOkybVybNoogAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//9k="   alt="" />
                    </div>
                </div>
            </b-tab>
            <b-tab title="Статус валов">
                <div class="table-responsive Recent-Users" style="min-height: 400px;">
                    <table class="table table-striped table-bordered" style="font-size: 11px; table-layout: auto;">
                        <thead>
                            <tr>
                                <th  scope="col">№</th>
                                <th  scope="col">Оквид</th>
                                <th  scope="col">ID вала</th>
                                <th  scope="col">цвет</th>
                                <th  scope="col">цвет DF</th>
                                <th  scope="col">Статус вала</th>
                                <th  scope="col">Ячейка</th>
                                <th  scope="col">Ресурс</th>
                                <th  scope="col">Метраж</th>
                                <th  scope="col"></th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr v-for="(shaftorder, key) in sortArrayShaftOrder(selectShaftResoursesOrders)" :key="key">
                                <td class="tabledit-view-mode">{{shaftorder[0].shaft_order_number}}</td>
                                <td class="tabledit-view-mode">{{String(shaftorder[0].okvid_number).slice(0,String(shaftorder[0].okvid_number).length-2)+'-'+String(shaftorder[0].okvid_number).slice(-2)}}</td>
                                <td><input type="text" readonly class="input_shaft" aria-describedby="inputGroup-sizing-sm" v-model="shaftorder[0].shaft_id"></td>
                                <td class="tabledit-view-mode">{{shaftorder[0].color}}</td>
                                <td class="tabledit-view-mode">{{shaftorder[0].panton}}</td>
                                <td><input type="text" readonly class="input_shaft" aria-describedby="inputGroup-sizing-sm" v-model="shaftorder[0].warehouse"></td>
                                <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder[0].warehouse_place,shaftorder[0],'warehouse_place')" v-model="shaftorder[0].warehouse_place"></td>
                                <td class="tabledit-view-mode" style="font-size: 14px;font-weight: bold;" >{{calcResource(shaftorder[1])}} %</td>
                                <td class="tabledit-view-mode" style="font-size: 14px;font-weight: bold;" >{{shaftorder[1]}}</td>
                                <td class="text-center">
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-settings"></i>
                                        </button>
                                        <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="padding: 10px; font-size: 14px; position: absolute; transform: translate3d(-106px, 41px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <li style="text-align: center;">
                                                <a>
                                                    <span style="cursor: pointer;" @click="showTransfer=true, shaft_transfer=shaftorder[0], section=shaftorder[0].shaft_order_number">Переброс</span>
                                                </a>
                                            </li>
                                            <li style="text-align: center;">
                                                <a>
                                                    <span style="cursor: pointer;" @click="show=true, shaft_id=shaftorder[0].shaft_id, getShaftResource(shaftorder[0].shaft_id)">Ресурс</span>
                                                </a>
                                            </li>
                                            <li style="text-align: center;">
                                                <a>
                                                    <span style="cursor: pointer;" @click="backShaft(shaftorder[0].shaft_id)">Перенести в предыдущий</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>             
                        </tbody>
                    </table>
                </div>
            </b-tab>
        </b-tabs>
        </div>
        </div>
    </div>
</template>

<script>
import Vue from "vue";
import vSelect from "vue-select";

Vue.component("v-select", vSelect);

import "vue-select/dist/vue-select.css";

    export default {
     
        props: {
            user: Object,
            orders: Object,
            orderstatus: Array,
            orderstate: Array,
            profile: Array,
            materials: Array,
            formats: Array,
            designers: Array,
            osz: Array,
            url: String,
            urldouble: String,
            vendors: Array,
            producttypes: Array,
            layoutconstructor: Array,          
            urlstreamxml: String,
            urlshaftpdf: String,
            urladdstream: String, 
            urllayout: String,    
            urldeletestream: String,    
            urlsubmitstream: String, 
            urldouble: String,
            urlcreateroutemap: String,
            urlshaftsxml: String,
            urlchecknavparam: String,
            urlscpreset: String,
            urlrmpreset: String,
            urlnextokvid: String,
            last_request_order: Object,


            ordergp: Array,
            urldeletegp: String,
            urladdgp: String,

            shaftorders: Array,
            producttypes: Array,
            shaftresourses: Array,
            shafts: Array,
            colors: Array,
            urlgetshafts: String,
            urlsubmitshaft: String,
            urlgetshaft: String,
            urldeleteshaft: String,
            urladdshaft: String,
            urlshaftresource: String,
            urlshafttransfer: String,
            urladdresource: String,
            urlgetshaftresource: String,
            urlsubmitresource: String,
            urldeleteresource: String,
            urladdsection: String,
            urlsearchorder: String,
            urlsearchgp: String,
            urlsearchshaft: String,
            shaftordersallokvid: Array,
            shafts_resource: Array,
            urlfreeshaft: String,
            urlbackshaft: String,
            urlcopyshaftparametrs: String,
        }, 

        mounted () {

        },
        data() {
            return {
               i: 0,
               show_param: false,
               selectedStatus: '',
               selectedState: '',
               selectOrder: {...this.orders},
               selectLastRequestOrder: {...this.last_request_order},
               okvid_number: this.orders.upak_order+'-'+this.orders.okvid_number.toString().substr(this.orders.okvid_number.length-2,this.orders.okvid_number.length),
               selectLayoutConstructor: [...this.layoutconstructor],
               sleeveLenght: ['','1370','1360','1380','1400'],
               qtyStage: ['','ЧЕТНОЕ','НЕЧЕТНОЕ'],
               outputArr: ['','Прямая','Зеркальная'],
               paintSeriesArr: ['','Flint Eliolam','Flint Pluriprint','Flint Plurilam','Siegwerk NC189'],
               amountStreamArr: ['','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'],
               treamReleaseArr: ['','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'],

                selectStream: [...this.ordergp],
                gp: 0,
                qty: '',

                CopyShaftsOkvid: '',
                ShaftBath: '',
                ShaftEditionBath: 0,
                ShaftBathDate: '',
                ShaftResourceModal:false,
                show: false,
                showTransfer: false,
                shaft_id: 0,
                shaft_transfer: {},
                section: 0,
                serachOrder: '',
                serachGp: '',
                searchShaftInput: '',
                searchGpInfo: [],
                searchOrderInfo: [],
                searchShaftInfo: [],
                SelectQtySection: 0,
                AddQtySec: 0,
                ShaftsArr: [...this.shafts],
                ColorsArr: [...this.colors],
                liniatures: ["48","54","60","70","80","90","70/85","70/100","100"],
                corners: ["0","2","3","4"],
                cutters: ["120","130","120/130"],
                shaft_resource: 0,
                Shaft: [],
                OkvidTransfer: '',
                SectionTransfer: '',
                selectShafts: [...this.shaftorders],
                selectAllShafts: [...this.shaftordersallokvid],
                selectShaftResourses: [...this.shaftresourses],
                selectShaftResoursesOrders: [...this.shafts_resource],
            }
                
        },
        computed: {
            Okvid_number: function(){
                return String(this.selectOrder.okvid_number).slice(0,String(this.selectOrder.okvid_number).length-2)+'-'+String(this.selectOrder.okvid_number).slice(-2);
            },
        },
        methods: {

            submit(){  

                if (this.user.department != 'Chief technologist department') {
                    axios.post(this.url, { 
                
                    order: this.selectOrder, 

                    })
                    .then(response => {
                        //this.selectOrder = response.data; 
                    })
                    .catch(err => {});
                }
            },

            createDouble(){
                let isDouble = confirm("Создать дубль?");

                if (isDouble) {

                    axios.get(this.urldouble, { params: 
                    { 
                        upakorder: this.selectOrder.upak_order, 
                        okvid: this.selectOrder.okvid_number, 
                    } })
                    .then(response => {
                        this.selectOrder = response.data;
                        this.lastOkvid();
                    })
                    .catch(err => {});
                }
            },

            SendDataStream () {
                let isPost = confirm("Отправить?");

                if (isPost) {
                    axios
                    .get(this.urlstreamxml,{ params: {order: this.selectOrder.id}})
                    .then(response => {
                        
                    });
                }
            },

            SendDataShafts () {
                let isPost = confirm("Отправить?");

                if (isPost) {
                    axios
                    .get(this.urlshaftsxml,{ params: {order: this.selectOrder.id}})
                    .then(response => {
                        
                    });
                }
            },

            autoApproval() {
                let isPost = confirm("Согласовать?");

                if (isPost) {
                    axios
                    .post(route('ugpc.bdgp.autoapproval'),{ params: {order: this.selectOrder.id}})
                    .then(response => {
                        alert('Данные отправлены');
                    });
                }
            },

            createPdfShaft() {
            let isPost = confirm("Отправить заявку?");

                if (this.selectOrder.application_install_completed != null) {

                    if (isPost) {
                        axios
                            .get(this.urlshaftpdf,{ params: {order: this.selectOrder.id}, responseType: 'blob', headers: {'Content-Type': 'application/pdf',},})
                            .then(response => {
                                const url = window.URL.createObjectURL(new Blob([response.data]));
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', 'file.pdf');
                                document.body.appendChild(link);
                                link.click();
                                

                            });
                    }

                } else {
                    alert('Укажите специалиста из заявки на монтаж!');
                }
            },

            AddStream() {
              axios
                .get(this.urladdstream,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    this.selectLayoutConstructor = response.data;
                    
                });
            },

            DeleteStream(item) {
              axios
                .get(this.urldeletestream,{ params: { stream: item, okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    this.selectLayoutConstructor = response.data;
                    
                });
            },

            submitStreams(streamorder) {
              
              axios
                .post(this.urlsubmitstream,{ stream: streamorder } )
                .then(response => {
                    
                  
                    
                });
            },

            checkNavParametr() {             
              axios
                .get(this.urlchecknavparam,{ params: {order: this.selectOrder.prod_order, order_id: this.selectOrder.id}} )
                .then(response => {

                  this.selectOrder = response.data;
                    
                });
            },

            SC_preset() {
                axios
                .get(this.urlscpreset,{ params: {order_id: this.selectOrder.id}} )
                .then(response => {
                    
                  this.selectOrder = response.data;
                    
                });
            },

            RM_preset() {
               axios
                .get(this.urlrmpreset,{ params: {order_id: this.selectOrder.id}} )
                .then(response => {
                    
                  this.selectOrder = response.data;
                    
                });
            },

            firstOkvid() {
               axios
                .get(this.urlnextokvid,{ params: {okvid: Number(this.selectOrder.okvid_number), type: 'first'}} )
                .then(response => {
                   
                 if (response.data.length != 0) {
                      this.selectOrder = response.data[0];
                      this.selectShafts = response.data[1];
                      this.selectStream = response.data[2];
                      this.selectShaftResourses = response.data[3];
                      this.selectLayoutConstructor = response.data[4];
                      this.selectShaftResoursesOrders = response.data[5];
                      this.selectLastRequestOrder = response.data[6];
                  } else {
                      this.selectLayoutConstructor = this.selectLayoutConstructor
                      this.selectOrder = this.selectOrder
                      this.selectShafts = this.selectShafts
                      this.selectStream = this.selectStream
                      this.selectShaftResourses = this.selectShaftResourses
                      this.selectShaftResoursesOrders = this.selectShaftResoursesOrders
                      this.selectLastRequestOrder = this.selectLastRequestOrder;
                  }
                    
                });
            },

            lastOkvid() {
               axios
                .get(this.urlnextokvid,{ params: {okvid: Number(this.selectOrder.okvid_number), type: 'last'}} )
                .then(response => {
                   
                 if (response.data.length != 0) {
                      this.selectOrder = response.data[0];
                      this.selectShafts = response.data[1];
                      this.selectStream = response.data[2];
                      this.selectShaftResourses = response.data[3];
                      this.selectLayoutConstructor = response.data[4];
                      this.selectShaftResoursesOrders = response.data[5];
                      this.selectLastRequestOrder = response.data[6];
                  } else {
                      this.selectLayoutConstructor = this.selectLayoutConstructor
                      this.selectOrder = this.selectOrder
                      this.selectShafts = this.selectShafts
                      this.selectStream = this.selectStream
                      this.selectShaftResourses = this.selectShaftResourses
                      this.selectShaftResoursesOrders = this.selectShaftResoursesOrders
                      this.selectLastRequestOrder = this.selectLastRequestOrder;
                  }
                    
                });
            },
            
            NextOkvid() {
               axios
                .get(this.urlnextokvid,{ params: {okvid: Number(this.selectOrder.okvid_number)+1, type: ''}} )
                .then(response => {
                    
                 if (response.data.length != 0) {
                    
                      this.selectOrder = response.data[0];
                      this.selectShafts = response.data[1];
                      this.selectStream = response.data[2];
                      this.selectShaftResourses = response.data[3];
                      this.selectLayoutConstructor = response.data[4];
                      this.selectShaftResoursesOrders = response.data[5];
                      this.selectLastRequestOrder = response.data[6];
                  } else {
                      
                  }
                    
                });
            },

            BackOkvid() {
               axios
                .get(this.urlnextokvid,{ params: {okvid: Number(this.selectOrder.okvid_number)-1, type: ''}} )
                .then(response => {

                 if (response.data.length != 0) {
                      this.selectOrder = response.data[0];
                      this.selectShafts = response.data[1];
                      this.selectStream = response.data[2];
                      this.selectShaftResourses = response.data[3];
                      this.selectLayoutConstructor = response.data[4];
                      this.selectShaftResoursesOrders = response.data[5];
                      this.selectLastRequestOrder = response.data[6];
                      
                  } else {
                      this.selectLayoutConstructor = this.selectLayoutConstructor
                      this.selectOrder = this.selectOrder
                      this.selectShafts = this.selectShafts
                      this.selectStream = this.selectStream
                      this.selectShaftResourses = this.selectShaftResourses
                      this.selectShaftResoursesOrders = this.selectShaftResoursesOrders
                      this.selectLastRequestOrder = this.selectLastRequestOrder;
                  }
                    
                });
            },

            deleteGp(id_gp) {         
              axios
                .get(this.urldeletegp,{ params: { gp: id_gp, okvid: this.selectOrder.okvid_number} })
                .then(response => {
        
                   this.selectStream = response.data;
                    
                });
            },

            addGp() {  
                 
              axios
                .get(this.urladdgp,{ params: { gp_code: this.gp, quantity: this.qty, okvid: this.selectOrder.okvid_number} })
                .then(response => {

                     this.selectStream = response.data;
                    
                });
            },

            sortArrayShaftOrder(arrays) {
              return _.orderBy(arrays, 'shaft_order_number', 'asc');
            },

            calcResource(arrays) {
              return (Math.ceil(arrays*100/2000000));
            },

            submitShaft(value, shaftorder, target_field) {
              shaftorder[target_field] = value;
                
              axios
                .post(this.urlsubmitshaft,{ shaft: shaftorder} )
                .then(response => {

                    
                });
            },

            getShafts() {
                axios
                .get(this.urlgetshafts,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    this.selectShafts = response.data;
                    
                });
            },

            deleteShaft(shaftid) {
             
              axios
                .get(this.urldeleteshaft,{ params: { shaftid: shaftid} })
                .then(response => {
                        
                });
            },

            backShaft(shaft) {
              let isBack = confirm("Отменить списание вала?");

              if (isBack) {
                axios
                    .get(this.urlbackshaft,{ params: { shaftid: shaft, okvid: this.selectOrder.okvid_number} })
                    .then(response => {
                            
                    });
              }
            },

            addShaft() {
                axios
                    .get(this.urladdshaft,{ params: { 
                        okvid: this.selectOrder.okvid_number, 
                        } })
                    .then(response => {

                        this.selectShafts = response.data;
                        
                    });
              
            },

            shaftResourse() {
                axios
                    .get(this.urlshaftresource,{ params: { 
                            okvid: this.selectOrder.okvid_number, 
                            format: this.selectOrder.format,
                            fragments: this.selectOrder.fragment_in_circulation,
                        } })
                    .then(response => {
                        
                        this.shaft_resource = response.data;
                        
                    });
            },
            shaftTransfer() {

                if (this.shaft_transfer.written_off != true) {
                    axios
                    .get(this.urlshafttransfer,{ params: { 
                            okvid: this.OkvidTransfer, 
                            shaft: this.shaft_transfer.shaft_id,
                            okvid_old: this.shaft_transfer.okvid_number,
                            section: this.SectionTransfer, 
                        } })
                    .then(response => {
                        
                        this.selectShafts = response.data;
                        
                    });
                } else {
                    alert('Данный вал уже списан с этого заказа!');
                }
            },
            AddResourse() {
              let isAdd = confirm("Добавить ресурс на все валы?");

              if (isAdd) {
                axios
                    .get(this.urladdresource,{ params: { shaft_id: null, okvid: this.selectOrder.upak_order, shaft_bath: this.ShaftBath, shaft_edition_bath: this.ShaftEditionBath, shaft_bath_date: this.ShaftBathDate} })
                    .then(response => {
                        this.ShaftBath = '';
                        this.ShaftEditionBath = '';
                        this.ShaftBathDate = '';
                        alert('Ресурс добавлен');
                    });
              }else {
                axios
                    .get(this.urladdresource,{ params: { shaft_id: this.shaft_id, okvid: this.selectOrder.okvid_number, shaft_bath: this.ShaftBath, shaft_edition_bath: this.ShaftEditionBath, shaft_bath_date: this.ShaftBathDate} })
                    .then(response => {
                        this.ShaftBath = '';
                        this.ShaftEditionBath = '';
                        this.ShaftBathDate = '';
                        alert('Ресурс добавлен');
                    });
              }
            },

            getShaftResource(shaft) {
              axios
                .get(this.urlgetshaftresource,{ params: { okvid: this.selectOrder.okvid_number, shaft_id: shaft} })
                .then(response => {
                    this.selectShaftResourses = response.data;
                });
            },

            copyShaftParametrs() {
              axios
                .get(this.urlcopyshaftparametrs,{ params: { okvid: this.selectOrder.okvid_number, shaft_param: this.CopyShaftsOkvid} })
                .then(response => {
                    
                    alert('Параметры скопированы');
                });

            },

            submitResource(resource_order) {
              axios
                .post(this.urlsubmitresource,{ resource: resource_order } )
                .then(response => {
                    
                    
                });
            },

            DeleteResource(resource_order) {
              axios
                .get(this.urldeleteresource,{ params: {okvid: this.selectOrder.okvid_number, resource: resource_order.id, shaft_id:  resource_order.shaft_id} })
                .then(response => {
                    this.selectShaftResourses = response.data;
                    
                });
            },
            addShaftSection(){
             axios
                .get(this.urladdsection,{ params: { okvid: this.selectOrder.okvid_number, qtysection: this.SelectQtySection } })
                .then(response => {

                    this.selectShafts = response.data;

                });
            },
            searchOrder() {
                axios
                .get(this.urlsearchorder,{ params: { order: this.serachOrder } })
                .then(response => {
                    if (response.data != '') {

                        this.searchOrderInfo = response.data;

                    } else {
                        alert('Данный заказ отсутствует!');
                    }
                })
                .catch(error => {
                    alert('Данный заказ не существует!');
                })
                .finally(() => {

                });
            },

            searchGp() {
                axios
                .get(this.urlsearchgp,{ params: { item: this.serachGp } })
                .then(response => {
                    this.searchGpInfo = response.data;
                    
                })
                .catch(error => {
                    alert('Данное ГП не существует!');
                })
                .finally(() => {
                    
                });
            },

            searchShaft() {
                axios
                .get(this.urlsearchshaft,{ params: { shaft: this.searchShaftInput } })
                .then(response => {
                    this.searchShaftInfo = response.data;
                })
                .catch(error => {
                    alert('Данный Вал не существует!');
                })
                .finally(() => {
                    
                });
            },
        
        },
        
    } 
    
    
</script>

<style>

.mx-datepicker {
    width: 100% !important;
}

.form-control {
    font-size: 13px !important;
}
а
.input-group-text {
    font-size: 13px !important;
}

select.form-control {
        height: calc(1.5em + 0.5rem + 2px) !important;
}

.form-check_materials {
    font-size: 13px;
}

.form-check_job {
    font-size: 13px;
}

.form-check_tech {
   font-size: 13px; 
}

.order-card {
    padding: 10px;
}

.form-control-sm, .input-group-sm>.form-control, .input-group-sm>.input-group-append>.btn, .input-group-sm>.input-group-append>.input-group-text, .input-group-sm>.input-group-prepend>.btn, .input-group-sm>.input-group-prepend>.input-group-text {
    padding: 0.2rem 0.2rem !important;
    line-height: 1 !important;
}

.input-group {
    margin-bottom: 0.5em;
}

.input-group>.input-group-append:last-child>.btn:not(:last-child):not(.dropdown-toggle), .input-group>.input-group-append:last-child>.input-group-text:not(:last-child), .input-group>.input-group-append:not(:last-child)>.btn, .input-group>.input-group-append:not(:last-child)>.input-group-text, .input-group>.input-group-prepend>.btn, .input-group>.input-group-prepend>.input-group-text {
    font-weight: bold;
}


.card_parametr {
    display: flex;
}

.cards_shaft_stream {
    display: flex;
    flex-direction: column;
    width: 50%;
    margin-left: 10px;

}

.card_wrapper {
    display: flex;
}

.btn_sc {
    border: 0;
    margin-left: 10px;
    padding: 3px 15px;
    background: #04a9f5;
    color: white;
}

.btn_rm {
    border: 0;
    margin-left: 10px;
    padding: 3px 15px;
    background: #04a9f5;
    color: white;
}

.vs__clear {
    display: none;
}

.select_shaft {
    font-size: 12px;
    font-weight: bold;
    min-width: 120px;
    background: white;
}

.vs__search, .vs__search:focus {
    /* flex-grow: 1; */
     line-height: 0 !important;
     margin: 0 !important; 
     padding: 0 !important;
}

.button_collapse {
    margin-right: 10px;
    right: 0;
    display: inline-block;
    font-size: 20px;
    position: absolute;
    margin-top: 10px;
    cursor: pointer;
}

.line_opacity_true {
    opacity: 0.3;
    
}

.tab-content {
    padding: 10px 0 !important;
}
</style>