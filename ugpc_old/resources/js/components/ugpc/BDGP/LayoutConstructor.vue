<template>
<div class="col-lg-12">
    <table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">№ Ручья</th>
            <th scope="col">ГП</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(stream, key) in this.selectLayoutConstructor" :key="key">
        <td><input type="text" class="form-control" v-model="stream.stream_number"></td>
        <td><input type="text" class="form-control" v-model="stream.gp_code" @change="submitStreams(stream)"></td>
        <td><b-button style="background-color: red;" @click="DeleteStream(stream.id), getLayoutConstructor()">Х</b-button></td>
        </tr>
    </tbody>
    <tfoot>
        
    </tfoot>
    </table>
    <div style="margin-top: 10px; padding: 1rem;" class="row">
        <b-button  @click="AddStream()">Добавить Ручей</b-button>
        <b-button  @click="SendDataStream()">Отправить</b-button>
    </div>
</div>
</template>

<script>

    export default {
     
          props: {
            orders: Object,
            layoutconstructor: Array,
            urlstreamxml: String,
            urladdstream: String,
            urlsubmitstream: String,
            urllayout: String,
            urldeletestream: String,
        }, 
        mounted () {



        },
        data() {
            return {
               selectLayoutConstructor: [...this.layoutconstructor],
               selectOrder: {...this.orders},
            }
                
        },
        computed: {

        },
        methods: {

            getLayoutConstructor(){
              axios
                .get(this.urllayout,{ params: { okvid: this.selectOrder.okvid_number } })
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

            DeleteStream(item) {
              axios
                .get(this.urldeletestream,{ params: { stream: item } })
                .then(response => {
                    
                    
                });
            },

            AddStream() {
              axios
                .get(this.urladdstream,{ params: { okvid: this.selectOrder.okvid_number } })
                .then(response => {
                    
                    this.selectLayoutConstructor.push(response.data)
                    
                });
            },

            SendDataStream () {
                axios
                .get(this.urlstreamxml)
                .then(response => {
                    console.log(response);
                });
            },
        },
        
    } 

</script>