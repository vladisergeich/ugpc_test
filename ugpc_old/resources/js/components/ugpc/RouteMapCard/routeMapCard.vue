<template>
    <div class="col-lg-12">
        <span class="route_card_title">Валы к заказу <a>№ {{orderOkvid}}</a></span>
        <a-table
            :columns="columns_route_map"
            :data-source="selectRouteMapCard"
            style="margin-top: 20px;"
        >
        <span  slot="shaft_number" slot-scope="text, record, index">{{ index + 1 }}</span>
        <a  slot="shaft_id" slot-scope="text, record"><router-link :to="{ name: 'routeMapShaft', query: { shaft_route_card: record } }">{{ text }}</router-link></a>
        </a-table>
    </div> 
</template>

<script>
  import DatePicker from 'vue2-datepicker';
  import 'vue2-datepicker/index.css';

    export default {
        components: { DatePicker },
     
        props: {
            route_map_card: Object,
        }, 

        mounted () {
            this.selectRouteMapCard = this.$route.query.route_map_card;
            this.orderOkvid = this.$route.query.order_okvid;
        },
        data() {
            return {
                columns_route_map: [
                    {
                    title: "№ вала",
                    dataIndex: "shaft_number",
                    key: "shaft_number",
                    scopedSlots: { customRender: 'shaft_number' },
                    },
                    {
                    title: "Вал",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    scopedSlots: { customRender: 'shaft_id' },
                    },
                    {
                    title: "Формат",
                    dataIndex: "shaft_format",
                    key: "shaft_format",
                    },
                ],
                selectRouteMapCard: [],
                orderOkvid: '',
            }
                
        },
        computed: {

          
        },

        methods: {

        },
        
    } 
    
    
</script>



<style>



.line_opacity_true {
    opacity: 0.3;
    
}

.line_opacity_false {
    opacity: 1;
}


tr.strikeout td:before {
  content: " ";
  position: absolute;
  top: 50%;
  left: 0;
  border-bottom: 1px solid #111;
  width: 100%;
}

tr.strikeout td:after {
  content: "\00B7";
  font-size: 1px;
}

table { border-collapse: collapse; empty-cells: show; }

td { position: relative; }


</style>
