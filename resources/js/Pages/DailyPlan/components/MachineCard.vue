<template>
    <div class="rounded-xl shadow p-4 bg-white cursor-pointer hover:shadow-md" @click="$emit('click')">
        <div class="text-xl font-semibold mb-1">{{ machine.name }}</div>
        <div class="flex gap-4 items-center mb-2">
            <span class="text-xl font-bold leading-tight">{{ machine?.complete_operations?.length }}</span>
            <span class="text-m text-gray-500">валов сделано</span> 
        </div>

        <div v-if="machine.current_shaft" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 21 20">
                <mask id="icon-mask" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                </mask>
                <g mask="url(#icon-mask)">
                <path
                    d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                    fill="#BCDCF8"
                />
                </g>
            </svg>
            <div class="flex gap-4 items-center">
                <div class="text-xl font-semibold">{{ machine.current_shaft?.engraving_order_shaft?.shaft?.code }}</div>
                <div class="text-m text-gray-500">вал в работе</div>
            </div>
        </div>

        <div v-else class="text-m text-green-500">Свободен</div>
    </div>
</template>

<script setup>
    const props = defineProps({
        machine: Object
    })
</script>
  