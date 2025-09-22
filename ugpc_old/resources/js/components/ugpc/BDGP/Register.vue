<template>
<div class="pcoded-wrapper">
    <div class="pcoded-content pt-0">
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card attributes-card">
                            <div class="card-header">
                                <h5>
                                Готовая продукция
                                </h5> 
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" data-toggle="modal" data-target="#create-status-modal" class="btn dropdown-toggle">
                                            <i class="feather icon-plus-circle">
                                                </i>
                                        </button>
                                    </div>
                                </div>
                            </div> 
                            <div class="card-body px-3 py-0">
                                <div class="table-responsive product_register">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr v-for="(product, key) in this.product_type" :key="key">
                                                <td>
                                                    {{product.gp_code}}
                                                </td>
                                                 <td>
                                                    {{product.description}}
                                                </td>
                                                 <td>
                                                    {{product.customer}}
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" data-title="Редактировать клиента" data-name="новый" data-toggle="modal" data-target="#edit-status-modal" class="icon-btn edit-btn label theme-bg2 text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-pencil-alt fa-lg fa-inverse">
                                                         </i>
                                                    </button> 
                                                    <button type="button"  data-title="Вы уверены что хотите удалить Клиента?" data-name="новый" data-toggle="modal" data-target="#delete-status-modal" class="icon-btn btn-danger label text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-trash-alt">
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                             </div> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card attributes-card">
                            <div class="card-header">
                                <h5>
                                Клиенты
                                </h5> 
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" data-toggle="modal" data-target="#create-status-modal" class="btn dropdown-toggle">
                                            <i class="feather icon-plus-circle">
                                                </i>
                                        </button>
                                    </div>
                                </div>
                            </div> 
                            <div class="card-body px-3 py-0">
                                <div class="table-responsive customers_register">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr v-for="(customer, key) in this.customers" :key="key">
                                                <td>
                                                    {{customer.description}}
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" data-title="Редактировать клиента" data-name="новый" data-toggle="modal" data-target="#edit-status-modal" class="icon-btn edit-btn label theme-bg2 text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-pencil-alt fa-lg fa-inverse">
                                                         </i>
                                                    </button> 
                                                    <button type="button"  data-title="Вы уверены что хотите удалить Клиента?" data-name="новый" data-toggle="modal" data-target="#delete-status-modal" class="icon-btn btn-danger label text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-trash-alt">
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                             </div> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card attributes-card">
                            <div class="card-header">
                                <h5>
                                Профили
                                </h5> 
                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" data-toggle="modal" data-target="#create-status-modal" class="btn dropdown-toggle">
                                            <i class="feather icon-plus-circle">
                                                </i>
                                        </button>
                                    </div>
                                </div>
                            </div> 
                            <div class="card-body px-3 py-0">
                                <div class="table-responsive profile_register">
                                    <table class="table table-hover table-striped">
                                        <tbody>
                                            <tr v-for="(profile, key) in this.profiles" :key="key">
                                                <td>
                                                    {{profile.short_name}}
                                                </td>
                                                <td>
                                                    {{profile.engraver}}
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" data-title="Редактировать клиента" data-name="новый" data-toggle="modal" data-target="#edit-status-modal" class="icon-btn edit-btn label theme-bg2 text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-pencil-alt fa-lg fa-inverse">
                                                         </i>
                                                    </button> 
                                                    <button type="button"  data-title="Вы уверены что хотите удалить Клиента?" data-name="новый" data-toggle="modal" data-target="#delete-status-modal" class="icon-btn btn-danger label text-white f-12">
                                                        <i aria-hidden="true" class="fas fa-trash-alt">
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                                <th  scope="col">Профиль</th>
                                                <th  scope="col">Гравировщик</th>
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
                                                                    <span style="cursor: pointer;" @click="showTransfer=true, shaft_id=shaftorder.shaft_id">Переброс</span>
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
                </div>
            </div>
        </div>
    </div>
</div>
</template>



<script>
    export default {
     
        props: {

            customers: Array,
            product_type: Array,
            profiles: Array,
           
        },  
        mounted () {
           
        },
        data() {
            return {

            }
        },

        methods: {
           
        }
    }   
</script>

<style scoped>
.profile_register {
    height: 400px;
}

.product_register {
    height: 400px;
}

.customers_register {
    height: 400px;
}
</style>