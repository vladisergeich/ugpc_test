<template>
    <div class="electroplating_bath_card_wrapper">
        <span class="electroplating_bath_card_title">
            {{machine}}
        </span>
        <a-table
            :columns="columns_operations"
            :data-source="this.machine_operations"
            size="small"
            bordered
        >    
        <template  slot="status" slot-scope="text, record">     
                <span v-if="record.status=='Завершен'" class="status_completed">{{ text}}</span>
                <span v-if="record.status=='На рассмотрении'"  class="status_not_work">{{ text}}</span>        
                <span  v-if="record.status == ''" >В очереди</span>
        </template>        
        <template  slot="posting_date" slot-scope="text, record">                
                <span  v-if="record.posting_date" >{{text}}</span>
        </template>     
        </a-table>
    </div>
</template>



<script>

export default {
  props: {
    machine_operations: Array,
    machine: String,
  },
  data() {
    return {
        renderAs: 'svg',
        columns_operations: [
            {
            title: "№ вала",
            dataIndex: "shaft_id",
            key: "shaft_id",
            },
            {
            title: "№ заказа",
            dataIndex: "okvid_number",
            key: "okvid_number",
            },
            {
            title: "Время",
            dataIndex: "starting_time",
            key: "starting_time",
            },
            {
            title: "Дата",
            dataIndex: "posting_date",
            key: "posting_date",
            scopedSlots: { 
                customRender: 'posting_date', 
            },
            },
            {
            title: "Оператор",
            dataIndex: "operator",
            key: "operator",
            },
            {
            title: "Статус",
            dataIndex: "status",
            key: "status",
            scopedSlots: { 
                customRender: 'status', 
            },
            },
        ],

    };
  },
  created() {

  },
  mounted() {
    
  },
  computed: {
  },
  methods: {

  },
};
</script>

<style>
.status_completed {
    background: #04BA0B;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_not_work {
    background: #E31212;
    border-radius: 10px;
    padding: 4px 15px;
    color: #FFFFFF;
}

.status_queue {
    border-radius: 10px;
    background: var(--Grayscale-500, #BFBFBF);
    padding: 4px 15px;
    color: #FFFFFF;
}
.electroplating_bath_card_title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: -0.2px;
}
.electroplating_bath_card_wrapper {
    margin-top: 20px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    display: flex;
    padding: 20px;
    gap: 15px;
    flex-direction: column;
}

</style>
