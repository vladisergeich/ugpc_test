import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import { reactive } from 'vue';
import { useDebounceFn } from '@vueuse/core'


export const useGravureStore = defineStore('gravureStore', {
    state: () => ({
        profiles: ref([]),
        vendors: ref([]),
        designers: ref([]),
        customers: ref([]),
        engravingOrderStatuses: ref([]),
        engravingOrderConditions: ref([]),
        mountingParameters: ref([]),
    }),

    actions: {
        async getDirectory() {
            try {
                const [profilesRes, vendorsRes, designersRes, customersRes, engravingOrderStatusesRes,engravingOrderConditionsRes, mountingParametersRes] = await Promise.all([
                    axios.get('/gravuredatabase/getProfiles'),
                    axios.get('/gravuredatabase/getVendors'),
                    axios.get('/gravuredatabase/getDesigners'),
                    axios.get('/gravuredatabase/getCustomers'),
                    axios.get('/gravuredatabase/getEngravingOrderStatuses'),
                    axios.get('/gravuredatabase/getEngravingOrderConditions'),
                    axios.get('/gravuredatabase/getMountingParameters')
                ]);
                this.profiles = profilesRes.data;
                this.vendors = vendorsRes.data;
                this.designers = designersRes.data;
                this.customers = customersRes.data;
                this.engravingOrderStatuses = engravingOrderStatusesRes.data;
                this.engravingOrderConditions = engravingOrderConditionsRes.data;
                this.mountingParameters = mountingParametersRes.data;
            } catch (error) {
                console.error('Ошибка при загрузке данных:', error);
            }
        },
    }
});


