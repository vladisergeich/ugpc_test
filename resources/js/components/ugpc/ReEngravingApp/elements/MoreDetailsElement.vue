<template>
    <a-table
    :columns="columns_details"
    :data-source="stages"
    bordered
    :pagination="false"
    size="small"
    >
    <p slot="expandedRowRender" slot-scope="record" style="margin: 0">
        <a-table
        :columns="columns_history"
        :data-source="record.stage_history"
        bordered
        :pagination="false"
        size="small"
        >
        <template slot="status" slot-scope="text, record">
            <span class="app_status" :class="{ 'active': text == 'Идет согласование', 'finish': text == 'Согласовано' , 'error' : text == 'Не согласовано'}">{{ text }}</span>
        </template>
        </a-table>
    </p>
    <template slot="status" slot-scope="text, record" v-if="link">
        <a v-if="text == 'Идет согласование'" target="_blank" :href="'https://okvid.danaflex.ru/ugpc/reengravingapps/app/'+record.app_id" >Перейти к согласованию</a>
        <span class="app_status" :class="{ 'active': text == 'Идет согласование', 'finish': text == 'Согласовано' , 'error' : text == 'Не согласовано'}" v-if="text != 'Идет согласование'">{{ text }}</span>
    </template>
    <template slot="user.employer_name_1c" slot-scope="text, record">
        <span v-if="record.status != null">{{ text }}</span>
        <span v-if="record.status == null"></span>
    </template>
    <template slot="approved_date" slot-scope="text, record">
        <span v-if="record.status != null">{{ text }}</span>
        <span v-if="record.status == null"></span>
    </template>
    </a-table>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    props: {
        stages: Object,
        link: Boolean,
    },
    data() {
        return {
            columns_history: [
                {
                title: "ФИО",
                dataIndex: "user.employer_name_1c",
                key: "user.employer_name_1c",
                scopedSlots: { 
                    customRender: 'user.employer_name_1c',
                },
                },
                {
                title: "Этап",
                dataIndex: "stage.approv_stage.title",
                key: "stage.approv_stage.title",
                },
                {
                title: "Дата",
                dataIndex: "approved_date",
                key: "approved_date",
                scopedSlots: { 
                    customRender: 'approved_date',
                },
                },
                {
                title: "Статус",
                dataIndex: "status",
                key: "status",
                scopedSlots: { 
                    customRender: 'status',
                },
                },
                {
                title: "Детали",
                dataIndex: "comment",
                key: "comment",
                scopedSlots: { 
                    customRender: 'comment',
                },
                },
            ],
            columns_details: [
                {
                title: "ФИО",
                dataIndex: "last_history.user.employer_name_1c",
                key: "last_history.user.employer_name_1c",
                },
                {
                title: "Этап",
                dataIndex: "approv_stage.title",
                key: "approv_stage.title",
                },
                {
                title: "Дата",
                dataIndex: "last_history.approved_date",
                key: "last_history.approved_date",
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

    methods: {

    },

    computed: {

    }
};
</script>

<style scoped>
.app_status {
    padding: 3px 8px 3px 8px;
    border-radius: 100px;
}

.active {
    background: #F3F9FF;
    color: #4A9DFF;
}

.finish {
    background: #EFFFE2;
    color: #04BA0B;
}

.error {
    background: #FFEBEB;
    color: #E31212;
}
</style>