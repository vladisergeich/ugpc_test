<template>
    <div class="operation-dinamics">
        <a-tabs :default-active-key="14">
            <a-tab-pane v-for="machine in this.machines" :key="machine.id" :tab="machine.title">
                <OperationDinamicsComponent :data="data.filter(operation => operation.work_center_id == machine.work_center_id).filter(operation => machine.id === null || operation.machine_id == machine.id)"/>
            </a-tab-pane>
        </a-tabs>
    </div>
</template>

<script>
import OperationDinamicsComponent from './OperationDinamicsComponent.vue';
export default {
components: {
    OperationDinamicsComponent,
},
props: {
    data: {
        type: Array,
        required: true,
    },
},
data() {
    return {
        machines: [

            {
                'id' : 14,
                'title':'CFM-1',
                'work_center_id' : 2,
            },
            {
                'id' : 15,
                'title':'CFM-2',
                'work_center_id' : 2,
            },
            {
                'id' : 11,
                'title':'K5-1',
                'work_center_id' : 3,
            },
            {
                'id' : 12,
                'title':'K5-2',
                'work_center_id' : 3,
            },
            {
                'id' : null,
                'title':'Гальваника',
                'work_center_id' : 1,
            },
            {
                'id' : 13,
                'title':'Пробпечать',
                'work_center_id' : 4,
            },
        ],
    };
}
};
</script>

<style scoped>
  .operation-dinamics {
    display: flex;
    flex-direction: column;
  }
</style>
  