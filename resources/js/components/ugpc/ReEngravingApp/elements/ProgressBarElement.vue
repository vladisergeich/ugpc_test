<template>
    <div class="progress-container">
        <a-steps :current="current" :status="status">
            <a-step style="font-size: 16px;" v-for="stage in stages" :key="stage.id" :title="stage.approv_stage.title" />
        </a-steps>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    props: {
        stages: Array,
    },
    data() {
        return {
            current: null,
            status: null
        };
    },

    created() {
        this.activeStage;
    },

    methods: {

    },

    computed: {
        activeStage() {
            console.log(this.stages);
            this.stages.filter((stage) => {
                
                if (stage.status == 'Идет согласование') {
                    this.current = stage.stage_number-1;
                    this.status = 'process';
                } else if (stage.status == 'Не согласовано') {
                    this.current = stage.stage_number-1;
                    this.status = 'error';
                }
            });
            console.log(this.current);
            if (!this.current) {
                this.current = this.stages.length - 1;
                this.status = 'finish';
            }

        }
    }
};
</script>

<style>
.ant-steps-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.progress-container {
    padding: 10px 16px 10px 16px;
    border-radius: 10px;
    background: #F3F9FF;
}
</style>