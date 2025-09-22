<template>
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
</template>

<script>

    export default {
     
          props: {
            orders: Object,
            shaftresourses: Array,
            shaftorders: Array,
            urlsubmitshaftbatch: String,
            urldeleteshaftbatch: String,
            urlshaftresources: String,
            urlshaftbatch: String,
        }, 
        mounted () {



        },
        data() {
            return {
               selectOrder: {...this.orders},
               selectShafts: this.shaftorders,
               selectShaftResourses: {...this.shaftresourses},
            }
                
        },
        computed: {

        },
        methods: {

            submitShaftBatch(shaftorder) {
              
              axios
                .post(this.urlsubmitshaftbatch,{ shaft: shaftorder } )
                .then(response => {
                    
                  
                    
                });
            },

            sortArrayShaftOrder(arrays) {
              return _.orderBy(arrays, 'shaft_order_number', 'asc');
            },

            DeleteShaftBatch(item) {
              axios
                .get(this.urldeleteshaftbatch,{ params: { shaftbatch: item } })
                .then(response => {
                    
                    console.log(response)
                    
                });
            },

            getShaftResource() {
              axios
                .get(this.urlshaftresources,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectShaftResourses = response.data;
                    
                });
            },

            AddShaftBatch() {
              axios
                .get(this.urlshaftbatch,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectShaftResourses.push(response.data)
                    
                });
            },
        },
        
    } 

</script>