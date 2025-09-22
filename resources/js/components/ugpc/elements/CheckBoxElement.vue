<template>
    <div>
        <svg v-if="data.open" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
            <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
            <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
        </svg>
        <svg v-if="data.defect" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#fa0f02"/>
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
        </svg>
        <svg v-if="data.post" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#03fc39"/>
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
        </svg>
        <svg v-if="!data.open && !data.post && !data.defect" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
            <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
        </svg>
        <span style="position: absolute; margin-left: 5px; margin-top: -3px;" v-if="data?.stage_id == 6 || data?.stage_id == 12">{{data?.plan_copper_plating?.float_value}}</span>
    </div>
</template>

<script>
export default {

    components: {

    },
    props: {
        data: Object,
    },
    data() {
        return {

        };
    },

    methods: {

    },

    computed: {

    },

};
</script>

<style scoped>

</style>