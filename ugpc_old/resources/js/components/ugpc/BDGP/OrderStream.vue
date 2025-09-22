<template>
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
                          <td>{{gp[0].note}}</td>
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
</template>

<script>

    export default {
     
        props: {
            orders: Object, 
            ordergp: Array,
            urldeletegp: String,
            urlgetstreams: String,
            urladdgp: String,
        }, 

        mounted () {



        },
        data() {
            return {
                selectOrder: {...this.orders},
                selectStream: [...this.ordergp],
                gp: 0,
                qty: '',
            }
                
        },
        computed: {

          
        },

        methods: {
            deleteGp(id_gp) {         
              axios
                .get(this.urldeletegp,{ params: { gp: id_gp, okvid: this.selectOrder.okvid_number} })
                .then(response => {
                    console.log(response);

                   this.selectStream = response.data;
                    
                });
            },

            getStreams() {
                axios
                .get(this.urlgetstreams,{ params: { okvid: this.selectOrder.okvid_number } })
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
        },
        
    } 
    
    
</script>
