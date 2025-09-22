<template>

    <div class="container" style="font-family: open sans,sans-serif; font-size: 14px;color: #888;font-weight: 400;background: #f4f7fa;position: relative;">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <div ref="content">
                                <label class="col-form-label">Номер заявки</label>
                                </div>
                                <input type="text" class="form-control" @change="getRequest(selectShaftRequest.document_number), getRequestComposition(selectShaftRequest.document_number)" v-model="selectShaftRequest.document_number">
                                <label class="col-form-label">Дата заявки:</label>
                                <div class="form-group form-primary mb-0">
                                    <input type="date" class="form-control" v-model="selectShaftRequest.document_date" @change="submitRequest(selectShaftRequest)">
                                    <span class="form-bar"></span>
                                </div>
                                <label class="col-form-label">Дата отгрузки:</label>
                                <div class="form-group form-primary mb-0">
                                    <input type="date" class="form-control" readonly v-if="selectShaftRequest.post" v-model="selectShaftRequest.shipping_date" @change="submitRequest(selectShaftRequest)">
                                    <input type="date" class="form-control" v-if="!selectShaftRequest.post" v-model="selectShaftRequest.shipping_date" @change="submitRequest(selectShaftRequest)">
                                    <span class="form-bar"></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Заполнил</label>
                                <select class="form-control" v-model="selectShaftRequest.request_creator" @change="submitRequest(selectShaftRequest)">
                                    <option v-for="(item, key) in this.designers" :key="key" >
                                        {{item.fio}}
                                    </option>
                                </select> 
                                <label class="col-form-label">Отправитель</label>
                                <select class="form-control" v-model="selectShaftRequest.sender" @change="submitRequest(selectShaftRequest)">
                                    <option v-for="(item, key) in this.vendors" :key="key" >
                                        {{item.vendor_name}}
                                    </option>
                                </select> 
                                <label class="col-form-label">Получатель</label>
                                <select class="form-control" v-model="selectShaftRequest.recipient" @change="submitRequest(selectShaftRequest)">
                                    <option v-for="(item, key) in this.vendors" :key="key" >
                                        {{item.vendor_name}}
                                    </option>
                                </select> 
                            </div>
                            <div class="col-md-4">
                                <label class="col-form-label">Комментарий:</label>
                                <textarea style="height: 50%; text-align: left;" type="text" class="form-control" v-model="selectShaftRequest.comment" @change="submitRequest(selectShaftRequest)">
                                </textarea>
                                <button class="btn-sm" style=" border: 2px solid; font-size: 13px; margin-top: 35px;" v-if="!selectShaftRequest.post"  @click="confirmRequest()" >Подтвердить</button>
                                <button class="btn-sm" style=" border: 2px solid; font-size: 13px; margin-top: 35px;" v-if="selectShaftRequest.post"  @click="updateRequest()" >Обновить заявку</button>
                            </div>
                        </div>
                        <div class="row" style="text-align: right;">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-sm" style="margin-right: 10px; border: 2px solid; font-size: 14px;" v-if="!selectShaftRequest.post" @click="PostShaftRequest()">Учет</button>
                                <button class="btn btn-primary btn-sm" style=" border: 2px solid; font-size: 14px;" v-if="!selectShaftRequest.post" v-b-modal.ShaftWarehouseModal>Подбор по валам</button>
                                <button class="btn btn-primary btn-sm" style=" border: 2px solid; font-size: 14px;" v-if="!selectShaftRequest.post"  @click="upakList()">Упаковочный лист</button>
                                <b-modal id="ShaftWarehouseModal" title="Подбор по валам">
                                    <a-input-search 
                                        style="margin-bottom: 10px;"
                                        :allow-clear="true"
                                        v-model="searchText"
                                    />
                                    <a-table
                                        :columns="columns_shaft"
                                        :data-source="filteredShafts"
                                        :rowKey="record => record.uid"
                                        >
                                        <span slot="add_btn" slot-scope="text, record, index" style="cursor: pointer;"><i class="feather icon-plus" v-if="!record.added" @click="AddRequestShaft(record)"></i></span>
                                    </a-table>
                                </b-modal>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        
                         <div id="content">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 20%" >№</th>
                                        <th scope="col" >ID вала</th>
                                        <th scope="col" >FF</th>
                                        <th scope="col" >Формат</th>
                                        <th scope="col">ОКВИД</th>
                                        <th scope="col">Пред. ОКВИД</th>
                                        <th scope="col">Назначение</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(shaft, index) in this.selectShaftRequestComposition" :key="index">
                                        <td style="width: 10%">{{shaft.line_number}}</td>
                                        <td >{{shaft.shaft_id}}</td>
                                        <td >{{shaft.ff}}</td>
                                        <td >{{shaft.shaft_format}}</td>
                                        <td ><input type="text" class="input_request_shaft" aria-describedby="inputGroup-sizing-sm" @change="updateShaft(shaft)" v-model="shaft.okvid_number"></td>
                                        <td ><span v-if="selectShaftRequest.sender == 'БиекТау' && shaft.shaft.last_okvid">{{ shaft.shaft.last_okvid.okvid_number ?? '' }}</span></td>
                                        <td ><span v-if="selectShaftRequest.sender == 'ИП Калмыкова' && shaft.shaft.last_okvid">{{ shaft.shaft.last_okvid.okvid_number ?? '' }}</span></td>
                                        <td >
                                            <select class="form-control" v-model="shaft.destination" @change="updateShaft(shaft)">
                                                <option v-for="(shaft, key) in ['Гравировка','Печать','Ламинация']" :key="key" >
                                                    {{shaft}}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <span class="m-r-10">
                                                <a href="#!" class="text-muted f-16" v-if="!selectShaftRequest.post"><i class="fas fa-trash" @click="DeleteRequestOkvid(shaft.id)"></i></a>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    </template>
    
    <script>
    
        export default {
         
              props: {
                shafts: Array,
                shaftrequest: Object,
                shaftrequestcomposition: Array,
                urlrequest: String,
                designers: Array,
                urladdrequestorder: String,
                urldeleterequestorder: String,
                vendors: Array,
                urlgetokvid: String,
                urlsendpdf: String,
                urlgetpdf: String,
                urlsubmitrequestcomposition: String,
                urlsubmitrequest: String,
                shaftrequestlast: Array,
                urlpostshaftrequest: String,
                urlconfirmrequest: String,
                urlupdateshaft: String,
                urlupdaterequest: String,
                
               
            },  
            mounted () {
                
               
            },
    
            computed: {

                filteredShafts() {
                    const searchTextLower = this.searchText.toLowerCase().trim();
                    return this.selectShaftWarehouse.filter(shaft =>
                        (shaft.shaft_id && shaft.shaft_id.toString().toLowerCase().startsWith(searchTextLower)) ||
                        (shaft.okvid_number && shaft.okvid_number.toString().toLowerCase().startsWith(searchTextLower))
                    );
                }

                
    
            },
    
            data() {
                return {
                    searchText: '',
                    selectShaftRequest: {...this.shaftrequest},
                    selectShaftRequestComposition: this.shaftrequestcomposition,
                    selectShaftWarehouse: [...this.shafts],
                    columns_shaft: [
                        {
                        title: "№ Вала",
                        dataIndex: "shaft_id",
                        key: "shaft_id",
                        },
                        {
                        title: "Оквид",
                        dataIndex: "okvid.okvid_number",
                        key: "okvid.okvid_number",
                        },
                        {
                        title: "",
                        dataIndex: "add_btn",
                        key: "add_btn",
                        scopedSlots: { customRender: 'add_btn' },   
                        },
                    ],
                }
            },
    
            methods: {
    
                getRequest(){
                    
                    axios
                    .get(this.urlrequest,{ params: { requestshaft: this.selectShaftRequest.document_number } })
                    .then(response => {
                        
                        this.selectShaftRequest = response.data;
                    });
                },
    
                AddRequestShaft(shaft) {
                  axios
                    .post(this.urladdrequestorder,{requestshaft: this.selectShaftRequest.document_number, shaft: shaft })
                    .then(response => {
                        
                        if (response.data == 0) {
                            alert('Вал уже добавлен в заявку');
                        } else {
                            this.selectShaftRequestComposition.push(response.data);
                            this.selectShaftWarehouse.filter(shaft_id =>shaft_id.id == shaft.id)[0].added = true;
                        }
                        
                    });
                },
    
                PostShaftRequest() {
                    let isPost = confirm("Учесть документ?");
    
                    if (isPost) {
                        let poddon_count = prompt('Введите кол-во поддонов', 0);
    
                        if (poddon_count > 0) {
                                               
                            axios
                            .get(this.urlpostshaftrequest,{ params: {poddon_count: poddon_count, requestshaft: this.selectShaftRequest.document_number, recipient: this.selectShaftRequest.recipient, sender: this.selectShaftRequest.sender} })
                                .then(response => {
                                    console.log(response.data);
                                    //this.selectShaftRequest = response.data;
                                    //alert('Перемещение учтено');
                                    
                                });
                        }
                    }
                },
    
                submitRequestComposition(request_composition) {
                  
                  axios
                    .post(this.urlsubmitrequestcomposition,{ composition: request_composition } )
                    .then(response => {
                        
                      console.log(response);
                        
                    });
                },
    
                submitRequest(request_id) {
                  
                  axios
                    .post(this.urlsubmitrequest,{ request_id: request_id } )
                    .then(response => {
                        
                      console.log(response);
                        
                    });
                },
    
                updateShaft(shaft_id) {
                  axios
                    .post(this.urlupdateshaft,{ shaft: shaft_id } )
                    .then(response => {
                        
                    });
                },
    
                DeleteRequestOkvid(item){
                       
                    axios
                    .get(this.urldeleterequestorder,{ params: { order: item, document: this.selectShaftRequest.document_number}})
                    .then(response => {
                        
                        this.selectShaftRequestComposition = response.data;
                        
                    });
                },
    
                upakList() {
                  axios
                    .get(this.urlsendpdf,{ params: {shaftrequest: this.selectShaftRequest.id,okvid: this.selectShaftRequest.okvid_number}, responseType: 'blob', headers: {'Content-Type': 'application/pdf', },})
                    .then(response => {
                        
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'file.pdf');
                        document.body.appendChild(link);
                        link.click();
                        
                    });
                },
    
                createPdfGet() {
                  axios
                    .get(this.urlgetpdf,{ params: {shaftrequest: this.selectShaftRequest.id} })
                    .then(response => {
                        
                    });
                },
    
                confirmRequest() {
                    let isConfirm = confirm("Подтвердить перемещение?");
    
                    if (isConfirm) {
                        axios
                        .get(this.urlconfirmrequest,{ params: {sender: this.selectShaftRequest.sender, recipient: this.selectShaftRequest.recipient, document_number: this.selectShaftRequest.document_number} })
                        .then(response => {
                        
                            if (response.data == 'ok') {
                                alert('Перемещение подтверждено');
                            }
                        });
                    }
                },
    
                updateRequest() {
                    let isConfirm = confirm("Обновить перемещение?");
    
                    if (isConfirm) {
                        axios
                        .get(this.urlupdaterequest,{ params: {document_number: this.selectShaftRequest.document_number} })
                        .then(response => {
    
                            this.selectShaftRequest = response.data;
                        
                        });
                    }
                },
    
            }
        }   
    </script>
    
    <style scoped>
    
        .form-control {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
    
        .col-form-label {
            font-weight: bold;
        }
    
        .form-control-request {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
    
        select.form-control {
            height: calc(2rem + 2px) !important;
            padding: 0;
            text-align: center;
        }
    
        input.form-control {
        height: calc(2rem + 2px) !important;
        padding: 0;
        text-align: center;
        }
    
        button.form-control {
        height: calc(2rem + 2px) !important;
        padding: 0;
        text-align: center;
        }
    
        .input_request_shaft {
            border-radius: 3px;
            border: 1px solid;
            line-height: 2;
            text-align: center;
            padding: 3px;
            font-weight: bold;
            font-size: 14px;
        }
    
    </style>
    
    