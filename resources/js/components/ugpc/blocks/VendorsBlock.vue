<template>
    <div class="vendors-container">
        <a-table
        :columns="vendorsColumns"
        :data-source="vendors"
        >
        <span @click="getProfiles(record)" slot="vendor_name" slot-scope="text, record" style="cursor: pointer;">{{ text }}</span>
        </a-table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {

    props: {
        vendors: Array,
        type: String,
    },
    data() {
        return {
            vendorsColumns: [
                {
                title: this.type === 'roto' ? 'Гравировщик' : 'Вывод клише',
                dataIndex: "vendor_name",
                key: "vendor_name",
                scopedSlots: { 
                    customRender: 'vendor_name'
                },
                },
            ],
        };
    },

    created() {

    },

    methods: {
        async getProfiles(vendor) {
            await this.$store.dispatch('getProfiles',vendor);
        }
    },

    computed: {

    }
};
</script>

<style scoped>

</style>