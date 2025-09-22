<template>
    <div class="cards-container">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-6">
                <h5>Этапы согласования перегравировки</h5>
            </div>
            <div class="col-md-6" style="display: flex; justify-content: flex-end;">
                <a target="_blank" href="/ugpc/reengravingapps/createapp/" class="create_btn"
                style=" 
                    padding: 10.5px 17.5px 10.5px 17.5px;
                    gap: 7px;
                    border-radius: 6px;
                    background: #4A9DFF;
                    color: #FFFFFF; border: none"
                >+ Новая заявка</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <FiltersBlock
                :customers="customers"
                :orders="orders"
                :btnClickChangeFilters="changeFilters"
                >
                </FiltersBlock>  
            </div>
            <div class="col-md-9">
                <div v-for="application in this.apps" :key="application.id">
                    <CardBlock
                        :application="application"
                    >
                    </CardBlock> 
                </div> 
            </div>
        </div>
    </div>
</template>


<script>
import CardBlock from "./blocks/CardBlock.vue";
import FiltersBlock from "./blocks/FiltersBlock.vue";
import { mapGetters } from 'vuex';
export default {

    components: {
        CardBlock,
        FiltersBlock
    },
    props: {
        applications: Array,
        customers: Array,
        orders: Array,
    },
    data() {
        return {
            apps: [...this.applications]
        };
    },

    created() {
        
    },

    methods: {
        changeFilters(filters) {

            this.apps = this.applications.filter(application => {

                const itemDate = new Date(application.start_date);

                const isDateInRange = (!filters.filterDate[0] || itemDate >= new Date(filters.filterDate[0])) && (!filters.filterDate[1] || itemDate <= new Date(filters.filterDate[1]));

                const isOrderMatch = !filters.order || application.order_number.includes(filters.order);

                const isPlaceMatch = !filters.place || filters.place.includes(application.place);

                const isStage = !filters.stage || application?.active_stage?.approv_stage.title.includes(filters.stage);

                const isStatus = !filters.status || application?.status.includes(filters.status);

                const isReason = !filters.reason || 
                         (application.shafts && application.shafts.some(shaft => shaft.reason.includes(filters.reason)));

                const isClientMatch = !filters.customer || application?.customer_desc.includes(filters.customer);

                return isDateInRange && isOrderMatch && isClientMatch && isPlaceMatch && isStage && isStatus && isReason;
            });
            
        }
    },

    computed: {

    }
};
</script>

<style scoped>

</style>