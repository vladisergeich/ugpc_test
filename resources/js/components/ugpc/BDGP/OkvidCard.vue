<template>
<div class="okvid-card">
    <div class="row">
        <div class="form-group col-md-3">
            <label>ОКВИД</label>
            <div class="okvid_wrapper" style="display: flex; align-items: center;">
                <a-icon @click="updateOkvid(selectOkvid.macro_id-1)" style="cursor: pointer; margin-right: 15px; font-size: 20px;" type="left-circle" />
                <input type="text" class="form-control" @change="updateOkvid(selectOkvid.macro_id)" v-model="selectOkvid.macro_id"> 
                <a-icon @click="updateOkvid(selectOkvid.macro_id+1)" style="cursor: pointer; margin-left: 15px; font-size: 20px;" type="right-circle" />
            </div>
            <label>Примечание</label>
            <input type="text" class="form-control" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.note">
        </div>
        <div class="form-group col-md-3">
            <label>Клиент</label>
            <v-select  :value="selectOkvid.customer"
                v-on:input="updateOkvidData($event.description,selectOkvid,'customer')"
                :options="customers" 
                label="description"
            ></v-select>
            <label>Описание</label>
            <input type="text" class="form-control" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.description">
        </div>
        <div class="form-group col-md-2">
            <label>Всего секций:</label>
            <input type="text" class="form-control" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.section_qty">
            <label>Параметры монтажа</label>
            <select class="form-control" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.crosses_bleed">
                <option v-for="(item, key) in this.mountig_parameters" :key="key" >
                    {{item.description}}
                </option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.reservation" :value="selectOkvid.reservation">
                <label class="form-check-label">Бронь валов:</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.written_off" :value="selectOkvid.written_off">
                <label class="form-check-label">Дубликат</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.auto_offset" :value="selectOkvid.auto_offset">
                <label class="form-check-label">Auto</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.zazor" :value="selectOkvid.zazor">
                <label class="form-check-label">Зазор</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.relsa" :value="selectOkvid.relsa">
                <label class="form-check-label">relsa 100%</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.bez_reza" :value="selectOkvid.bez_reza">
                <label class="form-check-label">БезРеза</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" @change="updateOkvidData('',selectOkvid,'')" v-model="selectOkvid.rotate_factor" :value="selectOkvid.rotate_factor">
                <label class="form-check-label">RotateFactor</label>
            </div>
        </div>
        <div class="form-group col-md-2">
            <button  class="btn btn-primary btn-sm" @click="newOkvid()">Создать ОКВИД</button>
        </div>
    </div>
</div>
</template>

<script>
    export default {
     
          props: {
            customers: Array,
            urlokvid: String,
            macroorder: Object,
            urladdokvid: String,
            urlupdateokvid: String,
            mountig_parameters: Array,

           
        },  
        mounted () {
           
        },
        data() {
            return {
                okvid: [],
                selectOkvid: {...this.macroorder},
            }
        },

        methods: {
            updateOkvid(macro_id) {
                axios
                .get(this.urlokvid,{ params: { okvid: macro_id } })
                .then(response => {
                    if (response.data != '') {
                        this.selectOkvid = response.data;
                        window.location.href = 'https://okvid.danaflex.ru/ugpc/bdgp/ordercard/'+response.data['id'];
                    }else {
                        alert('Данный Оквид не существует!');
                    }
                });
            },

            newOkvid() {
                axios
                .get(this.urladdokvid)
                .then(response => {
                    this.selectOkvid = response.data;
                    window.location.href = 'https://okvid.danaflex.ru/ugpc/bdgp/ordercard/'+response.data['id'];
                });
            },

            updateOkvidData(value, select_okvid, target_field){ 
                select_okvid[target_field] = value;  

                axios.post(this.urlupdateokvid, {        
                        okvid: select_okvid, 
                    })
                    .then(response => {

                    })
                    .catch(err => {});
            },
        }
    }   
</script>

<style scoped>
  .okvid-card {
      padding: 10px;
      background-color: white;
      margin-bottom: 10px;
      font-size: 13px;
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
</style>