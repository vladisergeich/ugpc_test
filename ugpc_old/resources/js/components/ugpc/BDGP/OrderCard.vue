<template>
    <b-tabs content-class="mt-3" class="card-conteiner">
      <b-tab title="Параметры заказа" active>
        <div class="row">
          <div class="form-group col-md-1">
            <label>ОКВИД</label>
            <input type="text" class="form-control" @change="getOkvid(selectOrder.okvid_number), getShafts(), getStreams(), getLayoutConstructor(), getShaftResource()" v-model="selectOrder.okvid_number"> 
          </div>
          <div class="form-group col-md-1">
            <label>ДУБЛЬ</label>
            <b-button class="form-control" @click="createDouble()">ДУБЛЬ</b-button> 
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
              <div class="row">
                  <div class="form-group col-md-6">
                      <label>Партия</label>
                      <input type="text" class="form-control" @change="submit(selectOrder.prod_order)" v-model="selectOrder.prod_order">
                      <label>Формат</label>
                      <select class="form-control" @change="submit(selectOrder.format)" v-model="selectOrder.format">
                          <option v-for="(item, key) in this.formats" :key="key" >
                              {{item.format}}
                          </option>
                      </select>
                  </div>
                  <div class="form-group col-md-6">                           
                      <label>Валов</label>
                      <input type="text" class="form-control" @change="submit(selectOrder.cylinder_quantity)" v-model="selectOrder.cylinder_quantity">
                      <label>Степпинг</label>
                      <input type="text" class="form-control" @change="submit(selectOrder.gradation_increase)" v-model="selectOrder.gradation_increase">
                  </div>
              </div>
              <div class="row">
                  <div class="form-group col-md-12">
                  <label>Профиль</label>
                  <select class="form-control" @change="submit(selectOrder.profile)" v-model="selectOrder.profile">
                      <option v-for="(item, key) in this.profile" :key="key">
                          {{item.short_name}}
                      </option>
                  </select>
                  <label>Статус</label>
                  <select class="form-control" style="margin-bottom: 1rem;" @change="submit(selectOrder.order_status)" v-model="selectOrder.order_status">
                      <option v-for="(item, key) in this.orderstatus" :key="key" >
                          {{item.order_status}}
                      </option>
                  </select>
                  <label>Тек. состояние</label>
                  <select class="form-control" @change="submit(selectOrder.condition)" v-model="selectOrder.condition">
                      <option v-for="(item, key) in this.orderstate" :key="key">
                          {{item.order_state}}
                      </option>
                  </select>
                  <label>Комментарий внутренний</label>
                  <textarea type="text" class="form-control" @change="submit(selectOrder.internal_comment)" v-model="selectOrder.internal_comment">
                  </textarea>
                  <label>Грав. из профиля</label>
                  <select class="form-control" @change="submit(selectOrder.supplier_engraving)" v-model="selectOrder.supplier_engraving">
                      <option v-for="(item, key) in this.vendors" :key="key">
                          {{item.vendor_name}}
                      </option>
                  </select>
                  <label>Репро</label>
                  <select class="form-control" @change="submit(selectOrder.repro)" v-model="selectOrder.repro">
                      <option v-for="(item, key) in this.vendors" :key="key">
                          {{item.vendor_name}}
                      </option>
                  </select>
                  <label>Гравировка</label>
                  <select class="form-control" @change="submit(selectOrder.engraving)" v-model="selectOrder.engraving">
                      <option v-for="(item, key) in this.vendors" :key="key">
                          {{item.vendor_name}}
                      </option>
                  </select>
                  <label for="inputCity">Базы отправят с</label>
                  <select id="inputState" class="form-control">
                      <option>...</option>
                  </select>
                  </div>
              </div>
          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="inputCity">Спец. ОКВИД</label>
                <select id="inputState" class="form-control" @change="submit(selectOrder.designer_new)" v-model="selectOrder.designer_new">
                      <option v-for="(item, key) in this.designers" :key="key" >
                          {{item.fio}}
                      </option>
                </select>
                <label for="inputCity">Менед. ОСЗ</label>
                <select id="inputState" class="form-control" @change="submit(selectOrder.manager_osz)" v-model="selectOrder.manager_osz">
                      <option v-for="(item, key) in this.osz" :key="key" >
                          {{item.manager_fio}}
                      </option>
                </select>
              </div>
              <div class="form-group col-md-6">
                  <label for="inputCity">Зна0</label>
                  <input class="form-control" @change="submit(selectOrder.no_three_o)" v-model="selectOrder.no_three_o">
                  <label for="inputCity">ЗнаПБ</label>
                  <input class="form-control" @change="submit(selectOrder.no_three_pb)" v-model="selectOrder.no_three_pb">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label>ЗнаП</label>
                <input class="form-control" @change="submit(selectOrder.no_three_p)" v-model="selectOrder.no_three_p">
                <label>Треб. ширина гра-ки</label>
                <input class="form-control" @change="submit(selectOrder.width_engraving)" v-model="selectOrder.width_engraving">
              </div>
              <div class="form-group col-md-6">
                <label>Длина гильзы</label>
                <select class="form-control" @change="submit(selectOrder.sleeve_lenght)" v-model="selectOrder.sleeve_lenght">
                      <option v-for="(item, key) in this.sleeveLenght" :key="key" >
                          {{item}}
                      </option>
                </select>
                <label>Варианты намотки</label>
                <select  class="form-control" @change="submit(selectOrder.winding_option)" v-model="selectOrder.winding_option">
                      <option v-for="(item, key) in this.sleeveLenght" :key="key" >
                          {{item}}
                      </option>
                </select>
              </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="inputCity">Первичка</label>
                  <input type="text" class="form-control" id="inputCity">
                  <label for="inputCity">Ширина</label>
                  <input type="text" class="form-control" id="inputCity">
                  <label for="inputCity">Вторичка</label>
                  <input type="text" class="form-control" id="inputCity">
                  <label for="inputCity">Краски</label>
                  <input type="text" class="form-control" id="inputCity">
                  <label for="inputCity">Вывод</label>
                  <input type="text" class="form-control" id="inputCity">
                  <label>Фрагм. в обороте</label>
                  <select  class="form-control" @change="submit(selectOrder.fragment_in_circulation)" v-model="selectOrder.fragment_in_circulation">
                          <option v-for="(item, key) in [1,2,3,4,5,6]" :key="key" >
                              {{item}}
                          </option>
                  </select>
                </div>
            </div>
          </div>
          <div class="col-md-2">
            <b-form-group label="Материалы заказчика:">
                <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="submit(selectOrder.electronic_file)" v-model="selectOrder.electronic_file" :value="selectOrder.electronic_file">
                <label for="inputCity">Электронный файл</label>
              </div>
                <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="submit(selectOrder.promo_sample)" v-model="selectOrder.promo_sample" :value="selectOrder.promo_sample">
                <label for="inputCity">Промообразец</label>
              </div>
            </b-form-group>
            <b-form-group label="Вид работы:">
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
            </b-form-group>
            <b-form-group>
              <label>Ширина ручья</label>
              <input class="form-control" @change="submit(selectOrder.width_stream)" v-model="selectOrder.width_stream">
              <label>Шаг печати</label>
              <input class="form-control" @change="submit(selectOrder.phototag_step)" v-model="selectOrder.phototag_step">
              <label>Кол-во ручьев</label>
              <select  class="form-control" @change="submit(selectOrder.amount_stream)" v-model="selectOrder.amount_stream">
                      <option v-for="(item, key) in [1,2,3,4,5,6,7,8,9,10]" :key="key" >
                          {{item}}
                      </option>
              </select>
              <label>Цвет линии реза</label>
              <input class="form-control" @change="submit(selectOrder.cut_line_color)" v-model="selectOrder.cut_line_color">
              <label>Число стадий</label>
              <select  class="form-control" @change="submit(selectOrder.number_stages)" v-model="selectOrder.number_stages">
                      <option v-for="(item, key) in ['ЧЕТНОЕ','НЕЧЕТНОЕ']" :key="key" >
                          {{item}}
                      </option>
              </select>
            </b-form-group>
            
          </div>
          <div class="col-md-5">
            <div class="row">
                <b-button v-b-modal.shaftModal>Ресурс валов</b-button>
                
                <b-modal id="shaftModal" title="Ресурс валов">
                  <table class="table table-hover">
                    <thead>
                        <tr>                                  
                            <th scope="col">ID Вала</th>
                            <th scope="col">Партия</th>
                            <th scope="col">Тираж</th>
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(shaft, key) in selectShaftResourses" :key="key">
                          <td>
                            <select  class="form-control" @change="submitShaftBatch(shaft)" v-model="shaft.shaft_id">
                              <option v-for="(item, key) in sortArrayShaftOrder(selectShafts)" :key="key" >
                                  {{item.shaft_id}}
                              </option>
                            </select>
                          </td>
                          <td><input type="text" class="form-control" v-model="shaft.batch" @change="submitShaftBatch(shaft)"></td>
                          <td><input type="text" class="form-control" v-model="shaft.edition_batch" @change="submitShaftBatch(shaft)"></td>
                          <td><input type="text" class="form-control" v-model="shaft.batch_date" @change="submitShaftBatch(shaft)"></td>
                          <b-button style="background-color: red;" @click="DeleteShaftBatch(shaft.id), getShaftResource()">Х</b-button>
                        </tr>
                        <b-button style="margin-top: 10px" @click="AddShaftBatch()">Добавить партию</b-button>
                    </tbody>
                    <tfoot>
                      
                    </tfoot>
                  </table>
                </b-modal>

                <b-button v-b-modal.mountingModal>Генерация монтаж</b-button>

                <b-modal id="mountingModal" title="Генерация монтаж">
                  <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">№ Ручья</th>
                            <th scope="col">ГП</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(stream, key) in selectLayoutConstructor" :key="key">
                          <td><input type="text" class="form-control" v-model="stream.stream_number"></td>
                          <td><input type="text" class="form-control" v-model="stream.gp_code" @change="submitStreams(stream)"></td>
                          <b-button style="background-color: red;" @click="DeleteStream(stream.id), getLayoutConstructor()">Х</b-button>
                        </tr>
                    </tbody>
                    <tfoot>
                        
                    </tfoot>
                  </table>
                  <div style="margin-top: 10px; padding: 1rem;" class="row">
                    <b-button  @click="AddStream()">Добавить Ручей</b-button>
                    <b-button  @click="SendDataStream()">Отправить</b-button>
                  </div>
                </b-modal>

                <b-button @click="createPdfShaft()">Заявка на гравировку</b-button>

            </div>
          </div>
        </div>
      </b-tab>
      
    </b-tabs>
</template>

<script>


    export default {
     
          props: {
            orders: Object,
            orderstatus: Array,
            orderstate: Array,
            profile: Array,
            formats: Array,
            designers: Array,
            osz: Array,
            url: String,
            urldouble: String,
            vendors: Array,
            urlokvid: String,
            urlorder: String,
            urlshafts: String,
            urlstreams: String,
            shaftorders: Array,
            orderstreams: Array,
            producttypes: Array,
            layoutconstructor: Array,
            shaftresourses: Array,
            urlstreamxml: String,
            urlshaftsupdate: String,
            urladdstream: String,
            urlsubmitstream: String,
            urlshaftpdf: String,
            urllayout: String,
            urlshaftbatch: String,
            urlshaftresources: String,
            urlsubmitshaftbatch: String,
            urldeleteshaftbatch: String,
            urldeletestream: String,
            urldeleteshaft: String,
            urladdshaft: String,
            urldeletegp: String,
            urladdgp: String,
            urlsubmitshaft: String,
        }, 
        mounted () {



        },
        data() {
            return {
               selectedStatus: '',
               selectedState: '',
               selectOrder: {...this.orders},
               selectShafts: this.shaftorders,
               selectStreams: this.orderstreams,
               selectLayoutConstructor: [...this.layoutconstructor],
               selectShaftResourses: [...this.shaftresourses],
               sleeveLenght: ['','1370','1380','1400'],
            }
                
        },
        computed: {
          searchInArrayProductType() {

            const gp_code = this.selectStreams.map(orderstream=>orderstream.gp_code);
            return this.producttypes.filter(producttype=>gp_code.indexOf(producttype.gp_code)!=-1)


          },
        },
        methods: {

            submit(){   
            axios.post(this.url, { 
                
                    order: this.selectOrder, 

                 })
                .then(response => {
                    
                })
                .catch(err => {});
            },

            sortArrayShaftOrder(arrays) {
              return _.orderBy(arrays, 'shaft_order_number', 'asc');
            },

            submitselectShafts(shaftorder) {
              console.log(shaftorder)
              axios.post(this.urlshaftsupdate, { 
                
                    shaft: shaftorder, 
                    

                 })
                .then(response => {
                    console.log(response.request);
                })
                .catch(err => {});
            },

            createDouble(){
                axios.get(this.urldouble, { params: 
                { 
                    id: this.selectOrder.id, 
                } })
                .then(response => {
                    this.selectOrder = response.data;
                })
                .catch(err => {});
            },

            createPDF () {
                console.log(this.selectShafts);
                let pdfName = 'test'; 
                var doc = new jsPDF();
                doc.text("Hello World", 10, 10);
                doc.fromHTML(this.$refs.testHtml, margins.left, margins.top,{
                  'width' : margins.width
                });
                doc.save(pdfName + '.pdf');
            },

            SendDataStream () {
                axios
                .get(this.urlstreamxml)
                .then(response => {
                    console.log(response);
                });
            },

            getOkvid() {
                axios
                .get(this.urlokvid,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    if (response.data.okvid_number != undefined) {
                      this.selectOrder = response.data;
                    }
                    
                });
            },

            getShafts() {
                axios
                .get(this.urlshafts,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    this.selectShafts = response.data;
                    console.log(response);
                });
            },

            getStreams() {
                axios
                .get('/ugpc/getstreams/',{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectStreams = response.data;
                    
                });
            },

            getLayoutConstructor(){
              axios
                .get(this.urllayout,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectLayoutConstructor = response.data;
                    
                });
            },

            getShaftResource() {
              axios
                .get(this.urlshaftresources,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectShaftResourses = response.data;
                    
                });
            },

            AddStream() {
              axios
                .get(this.urladdstream,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectLayoutConstructor.push(response.data)
                    
                });
            },

            AddShaftBatch() {
              axios
                .get(this.urlshaftbatch,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectShaftResourses.push(response.data)
                    
                });
            },

            submitStreams(streamorder) {
              
              axios
                .post(this.urlsubmitstream,{ stream: streamorder } )
                .then(response => {
                    
                  
                    
                });
            },

            DeleteStream(item) {
              axios
                .get(this.urldeletestream,{ params: { stream: item } })
                .then(response => {
                    
                    
                });
            },

            submitShaftBatch(shaftorder) {
              
              axios
                .post(this.urlsubmitshaftbatch,{ shaft: shaftorder } )
                .then(response => {
                    
                  
                    
                });
            },

            submitShaft(shaftorder) {
              
              axios
                .post(this.urlsubmitshaft,{ shaft: shaftorder } )
                .then(response => {
                    
                  console.log(response);
                    
                });
            },

            DeleteShaftBatch(item) {
              axios
                .get(this.urldeleteshaftbatch,{ params: { shaftbatch: item } })
                .then(response => {
                    
                    console.log(response)
                    
                });
            },

            createPdfShaft() {
              axios
                .get(this.urlshaftpdf,{ params: {order: this.selectOrder.id} })
                .then(response => {
                    
                   
                    
                });
            },

            deleteShaft(shaftid) {
             
              axios
                .get(this.urldeleteshaft,{ params: { shaftid: shaftid} })
                .then(response => {
                    
                   
                    
                });
            },

            addShaft() {
             
              axios
                .get(this.urladdshaft,{ params: { okvid: this.selectOrder.okvid_number} })
                .then(response => {
                    
                   
                    
                });
            },

            deleteGp(id_gp) {  
                        
              axios
                .get(this.urldeletegp,{ params: { gp: id_gp, okvid: this.selectOrder.okvid_number} })
                .then(response => {
                    
                   
                    
                });
            },
        
        },
        
    } 
    
    
</script>

<style scoped>
  .ant-select-group-addon {
    display: flex;
    align-items: center;
    padding: 0 10px;
    color: rgba(0, 0, 0, 0.65);
    font-weight: normal;
    font-size: 14px;
    text-align: center;
    background-color: #fafafa;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    transition: all 0.3s;
  }
  .ant-select-selection {
    display: flex;
    width: auto;
    align-items: center;
    box-sizing: border-box;
    background-color: #fff;
    border: 1pxsolid #d9d9d9;
    border-radius: 4px;
    outline: none;
    padding: 3px;
  }

  .card-conteiner {
      border: 1px solid black;
      border-radius: 5px;
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

    .btn-table {
      padding: 0;
      margin: 0;
      font-size: 10px;
      width: 20px;
      height: 20px;
    }




table  {
  font-size: 14px;
}

table th {
  padding: 0.5em !important;
}

table td {
  padding: 0.5em !important;
}

.form-layout-constructor {
  width: 100%;
}
</style>