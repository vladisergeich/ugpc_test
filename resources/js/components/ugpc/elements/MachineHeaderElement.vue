<template>
    <div class="info-container_header">
        <div class="header_info_blok" v-for="info in headerInfo" :key="info.title">
            <span class="info_title">{{ info.title }}:</span>
            <span class="info_value">{{ info.value }}</span>
        </div>
        <a-button class="operation__second_btn" v-if="!getConsumpShaft" @click="visibleModalSecondaryOperation = true">Доп. операции</a-button>
        <a-modal title="Доп. операции" :visible="visibleModalSecondaryOperation" @cancel="visibleModalSecondaryOperation = false">
            <template slot="footer">
                <a-button key="submit" type="primary" @click="startOperation()">Начать</a-button>
            </template>
            <a-select style="width: 100%" v-model="selectedSecondaryOperation">
                <a-select-option v-for="operation in secondOperationsMachine" :key="operation.id">{{ operation.description }}</a-select-option>
            </a-select>
        </a-modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

  props: {
        headerInfo: Array,
    },
    data() {
        return {
            visibleModalSecondaryOperation: false,
            selectedSecondaryOperation: null,
        };
    },

    methods: {
        async startOperation() {
            await this.$store.dispatch('startOperation',this.selectedSecondaryOperation);
            this.visibleModalSecondaryOperation = false;
        },

        async getSecondOperationMachine() {
            await this.$store.dispatch('getSecondOperationMachine');
        }
    },

    computed: {
        ...mapGetters({
            secondOperationsMachine: 'getSecondOperationsMachine',
            getConsumpShaft: 'getConsumpShaft'
        }),
    },

    created() {
        this.getSecondOperationMachine();
    },

};
</script>

<style scoped>
.info-container_header {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #E8E8E8;
    padding: 0 0 20px 0;
}

.info_title {
    font-size: 16px;
}

.info_value {
    font-size: 16px;
}

.operation__second_btn {
    border-radius: 8px;
    background: var(--primary-100, #E5EEFF);
    box-shadow: 0px 4px 4px 0px rgba(214, 214, 214, 0.25);
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    right: 20px;
    position: absolute;
}
</style>