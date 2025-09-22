<template>
    <div class="card attributes-card">
        <div class="card-header">
            <h5>
                Валы заказа:
            </h5> 
            <div class="card-header-right">
                <div class="btn-group card-option">
                    <button type="button" data-toggle="modal" data-target="#modalAddShaft" class="btn dropdown-toggle">
                        <i class="feather icon-plus-circle">
                        </i>
                    </button>
                </div>
                <div class="modal fade bd-example-modal-lg show" id="modalAddShaft">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title h4" id="myLargeModalLabel">Добавить Вал</h5>
                                <h5 class="modal-title h4" id="alertshaft" style="display: none; color: red;">Вал зарезервирован</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-gthtпереhidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">ID Вала</span>
                                            </div>
                                            <select class="form-control" v-model="Shaft.shaft_id">
                                                <option v-for="(item, key) in this.shafts" :key="key">
                                                    {{item.shaft_id}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Линиатура</span>
                                            </div>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="Shaft.lineature">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Цвет</span>
                                            </div>
                                            <select class="form-control" v-model="Shaft.color">
                                                <option v-for="(item, key) in this.colors" :key="key">
                                                    {{item.color_description}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Угол</span>
                                            </div>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="Shaft.corner">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Резец</span>
                                            </div>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="Shaft.cutter">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputGroup-sizing-sm">Примечание</span>
                                            </div>
                                            <input type="text" class="form-control" aria-describedby="inputGroup-sizing-sm" v-model="Shaft.note">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button  class="btn btn-primary btn-sm" @click="addShaft(Shaft)">Добавить</button>
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
                <table class="table table-striped table-bordered" style="font-size: 11px; table-layout: auto;">
                    <thead>
                        <tr>
                            <th  scope="col">№</th>
                            <th  scope="col">Грав.</th>
                            <th  scope="col">ID вала</th>
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
                         <tr v-for="(shaftorder, key) in sortArrayShaftOrder(selectShafts)" :key="key">
                            <td class="tabledit-view-mode">{{shaftorder.shaft_order_number}}</td>
                            <td><input type="checkbox" @change="submit(shaftorder)" v-model="shaftorder.dispatch" :value="shaftorder.dispatch"></td>
                            <td>
                                <input type="text" style="width: 90px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.shaft_id" list="shafts">
                                <datalist id="shafts">
                                    <option v-for="(item, key) in shafts" :key="key" v-bind:value="item.shaft_id"  v-bind:label="item.shaft_id">
                                        {{item.shaft_id}}
                                    </option>
                                </datalist>
                            </td>
                            <td>
                                <input type="text" style="width: 80px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.color" list="colors">
                                <datalist id="colors">
                                    <option v-for="(item, key) in colors" :key="key" v-bind:value="item.color_description"  v-bind:label="item.color_description">
                                        {{item.color_description}}
                                    </option>
                                </datalist>
                            </td>
                            <td><input type="text" style="width: 100px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.panton"></td>
                            <td><input type="checkbox" @change="submit(shaftorder)" v-model="shaftorder.base" :value="shaftorder.base"></td>
                            <td>
                                <input type="text" style="width: 70px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.lineature" list="lineatures">
                                <datalist id="lineatures">
                                    <option v-for="(item, key) in liniatures" :key="key" v-bind:value="item"  v-bind:label="item">
                                        {{item}}
                                    </option>
                                </datalist>
                            </td>
                            <td>
                                <input type="text" style="width: 50px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.corner" list="corners">
                                <datalist id="corners">
                                    <option v-for="(item, key) in corners" :key="key" v-bind:value="item"  v-bind:label="item">
                                        {{item}}
                                    </option>
                                </datalist>
                            </td>
                            <td>
                                <input type="text" style="width: 50px;" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.cutter" list="cutters">
                                <datalist id="cutters">
                                    <option v-for="(item, key) in cutters" :key="key" v-bind:value="item"  v-bind:label="item">
                                        {{item}}
                                    </option>
                                </datalist>
                            </td>
                            <td><input type="text" class="input_shaft" aria-describedby="inputGroup-sizing-sm" @change="submitShaft(shaftorder)" v-model="shaftorder.note"></td>
                            <td class="text-center">
                            <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-settings"></i>
                                    </button>
                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="padding: 10px; font-size: 14px; position: absolute; transform: translate3d(-106px, 41px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <li style="text-align: center;">
                                            <a>
                                                <span style="cursor: pointer;" @click="showTransfer=true, shaft_id=shaftorder.shaft_id, section=shaftorder.shaft_order_number">Переброс</span>
                                            </a>
                                        </li>
                                        <li style="text-align: center;">
                                            <a>
                                                <span style="cursor: pointer;" @click="show=true, shaft_id=shaftorder.shaft_id, getShaftResource(shaftorder.shaft_id)">Ресурс</span>
                                            </a>
                                        </li>
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
                                    <th scope="col">Кол-во оттисков</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(resourse, key) in selectShaftResourses" :key="key">
                                <td><input type="text" class="form-control" v-model="resourse.batch" @change="submitResource(resourse)"></td>
                                <td><input type="text" class="form-control" v-model="resourse.edition_batch" @change="submitResource(resourse)"></td>
                                <td style="vertical-align: middle; color: red; cursor: pointer;"><i class="feather icon-delete" @click="DeleteResource(resourse)"></i></td>
                                
                                </tr>
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                        <div style="margin-top: 10px; padding: 1rem;" class="row">
                            <button  class="btn btn-primary btn-sm" @click="AddResourse(shaft_id)">Добавить Ресурс</button>
                        </div>
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
                        </div>
                        <button class="btn btn-primary btn-sm" @click="shaftTransfer()">ПЕРЕБРОСИТЬ</button> 
                    </div>
                </b-modal>
            </div>
        </div> 
        <div class="card-footer">
        </div>
    </div>
</template>


<script>

    export default {
     
          props: {
            orders: Object,
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

        }, 

        mounted () {



        },
        data() {
            return {
                show: false,
                showTransfer: false,
                shaft_id: 0,
                section: 0,
                liniatures: ["48","54","60","70","80","90","70/85","70/100","100"],
                corners: ["0","2","3","4"],
                cutters: ["120","130","120/130"],
                shaft_resource: 0,
                Shaft: [],
                OkvidTransfer: '',
                selectShafts: [...this.shaftorders],
                selectOrder: {...this.orders},
                selectShaftResourses: [...this.shaftresourses],
            }
                
        },
        computed: {

          
        },

        methods: {
            sortArrayShaftOrder(arrays) {
              return _.orderBy(arrays, 'shaft_order_number', 'asc');
            },

            submitShaft(shaftorder) {
              
              axios
                .post(this.urlsubmitshaft,{ shaft: shaftorder } )
                .then(response => {
                    
                  
                    
                });
            },

            getShafts() {
                axios
                .get(this.urlgetshafts,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    this.selectShafts = response.data;
                    console.log(response);
                });
            },

            deleteShaft(shaftid) {
             
              axios
                .get(this.urldeleteshaft,{ params: { shaftid: shaftid} })
                .then(response => {
                        
                });
            },

            addShaft(Shaft) {
                axios
                    .get(this.urladdshaft,{ params: { 
                        okvid: this.selectOrder.okvid_number, 
                        shaft: Shaft.shaft_id, 
                        color: Shaft.color, 
                        panton: Shaft.panton,
                        lineature: Shaft.lineature,
                        corner: Shaft.corner,
                        cutter: Shaft.cutter,
                        } })
                    .then(response => {
                        console.log(response.data);
                        if (response.data == 0) {
                            alert('Вал зарезервирован');
                        } else {
                            this.selectShafts = response.data;
                        }
                        
                        
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
                axios
                    .get(this.urlshafttransfer,{ params: { 
                            okvid: this.OkvidTransfer, 
                            shaft: this.shaft_id,
                            okvid_old: this.selectOrder.okvid_number,
                            section: this.section, 
                        } })
                    .then(response => {
                        
                        console.log(response);
                        
                    });
            },
            AddResourse(shaft) {
              axios
                .get(this.urladdresource,{ params: { okvid: this.selectOrder.okvid_number, shaft_id: shaft} })
                .then(response => {
                    this.selectShaftResourses = response.data;
                    
                });
            },

            getShaftResource(shaft) {
              axios
                .get(this.urlgetshaftresource,{ params: { okvid: this.selectOrder.okvid_number, shaft_id: shaft} })
                .then(response => {
                    this.selectShaftResourses = response.data;
                    
                });
            },

            submitResource(resource_order) {
              console.log(resource_order);
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
        },
        
    } 
    
    
</script>

<style>

.table td, .table th {
    padding: 0.5rem !important;
    text-align: center !important;
    vertical-align: middle !important;
}

.input_shaft {
    border-radius: 3px;
    border: 1px solid;
    line-height: 2;
    text-align: center;
    padding: 3px;
    font-weight: bold;
    font-size: 11px;
}

.table td {
    padding: 0.1rem !important;
  
}

.card {
    margin-bottom: 10px !important;
}


.card .card-header h5 {
    font-size: 15px !important;
}

</style>