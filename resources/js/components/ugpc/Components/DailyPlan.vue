<template>
    <div class="daily_plan_wrapper">
        <span class="wrapper_title">Валы в работе</span>
        <div class="select_row">

        </div>
        <div class="tabs_row">
            <a-tabs>
                <a-tab-pane key="1" tab="Свод">
                    <a-modal v-model="visibleMachineTable" width="1000px" :title="selectMachine">
                        <a-table 
                            :dataSource="selectMachineOperations" 
                            :columns="columns_machine_operation"
                            size="small"
                            bordered
                        >  
                        </a-table> 
                        <template slot="footer">
                            <a-button key="distribute_orders" type="primary">
                                OK
                            </a-button>
                        </template>
                    </a-modal>
                    <div class="info_card_row">
                        <div class="brak_card">
                            <span class="card_title">Брак</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">0</span>
                                <span class="made_shaft_desc">вала</span>
                            </div>
                        </div>
                        <div class="machine_card">
                            <span class="card_title">Входной контроль</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['VK'].madeShafts}}</span>
                                <span class="made_shaft_desc">вала сделано</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('CFM-1')">
                            <span class="card_title">CFM-1</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['CFM-1'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['CFM-1'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['CFM-1'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                    <rect x="0.333496" width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                    <path d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z" fill="#BCDCF8"/>
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{machines['CFM-1'].workingShaft}}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('CFM-2')">
                            <span class="card_title">CFM-2</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['CFM-2'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['CFM-2'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['CFM-2'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                    <rect x="0.333496" width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                    <path d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z" fill="#BCDCF8"/>
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{machines['CFM-2'].workingShaft}}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                    </div>
                    <div class="info_card_row" @click="showMachineTable('K5-1')">
                        <div class="machine_card">
                            <span class="card_title">Гравер №1</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['K5-1'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['K5-1'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['K5-1'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                    <rect x="0.333496" width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                    <path d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z" fill="#BCDCF8"/>
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{machines['K5-1'].workingShaft}}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('K5-2')">
                            <span class="card_title">Гравер №2</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['K5-2'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['K5-2'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['K5-2'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                    <rect x="0.333496" width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                    <path d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z" fill="#BCDCF8"/>
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{machines['K5-2'].workingShaft}}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('PP')">
                            <span class="card_title">Печать</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['PP'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['PP'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['PP'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="21" height="20">
                                    <rect x="0.333496" width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                    <path d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z" fill="#BCDCF8"/>
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{machines['PP'].workingShaft}}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                    </div>
                    <div class="info_card_row">
                        <div class="electroplating_card">
                            <span class="card_title">Гальваника</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{machines['EP'].madeShafts}}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="electroplating_baths_row">
                                <div class="electroplating_baths_card" @click="showMachineTable('Pol')">
                                    <span class="electroplating_card_title">Полировка</span>
                                    <div class="electroplating_working_free_row" v-if="machines['Pol'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Pol'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['Pol'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Chr')">
                                    <span class="electroplating_card_title">Хромирование</span>
                                    <div class="electroplating_working_free_row" v-if="machines['Chr'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Chr'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['Chr'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Deh')">
                                    <span class="electroplating_card_title">Дехромирование</span>
                                    <div class="electroplating_working_free_row" v-if="machines['Deс'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Deс'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['Deс'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Ob')">
                                    <span class="electroplating_card_title">Обезжиривание</span>
                                    <div class="electroplating_working_free_row" v-if="machines['Ob'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Ob'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['Ob'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Pred')">
                                    <span class="electroplating_card_title">Предмедь</span>
                                    <div class="electroplating_working_free_row" v-if="machines['Pred'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Pred'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['Pred'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M81')">
                                    <span class="electroplating_card_title">Меднение 8.1</span>
                                    <div class="electroplating_working_free_row" v-if="machines['M81'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['M81'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['M81'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M82')">
                                    <span class="electroplating_card_title">Меднение 8.2</span>
                                    <div class="electroplating_working_free_row" v-if="machines['M82'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['M82'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['M82'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M83')">
                                    <span class="electroplating_card_title">Меднение 8.3</span>
                                    <div class="electroplating_working_free_row" v-if="machines['M83'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['M83'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{machines['M83'].workingShaft}}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a-tab-pane>
                <a-tab-pane key="2" tab="Табличный план" force-render>
                    <div class="table_daily_plan_wrapper">
                        <a-table 
                            :dataSource="dailyPlanShafts" 
                            :columns="columns_daily_plan"
                            bordered
                            :rowClassName="getRowClassName"
                            style="border-radius: 10px"
                            size="middle"
                        >  
                            <template  slot="shaft_id" slot-scope="text, record" > 
                                <span v-if="!record.group_row" class="" style="text-align: center; width: 100%; color: var(--primary-300-activ, #4A9DFF); cursor: pointer;">
                                    {{record.shaft_id}}
                                </span> 
                                <span v-if="record.group_row" class="" style="text-align: center; width: 100%;">
                                    Заказ - {{record.okvid_number}}
                                </span>           
                            </template>
                        </a-table>
                    </div>
                </a-tab-pane>
            </a-tabs>
        </div>
    </div>  
</template>



<script>

    const renderContent = (value, row, index) => {
        const obj = {
            children: value,
            attrs: {},
        };
        if (row.group_row == true) {
            obj.attrs.colSpan = 0;
        }
        return obj;
    };
    export default {
        components: {

        },
        props: {
            daily_plan_shafts: Array,
            operations: Array,
        },  
        data() {
            return {
                columns_daily_plan: [
                    {
                    title: "Вал",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    scopedSlots: { 
                        customRender: 'shaft_id', 
                    },
                    customRender: (value, row, index) => {

                        if (!row.group_row) {
                            return {
                                children: value,
                                attrs: {},
                            };
                        } else {
                            return {
                                children: <div><span style="margin-right: 20px;">Заказ: {row.order_number}</span> <span> Оквид: {row.okvid_number}</span></div>,
                                attrs: {
                                colSpan: 16,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Вх. контроль",
                    dataIndex: "input_control",
                    key: "input_control",
                    scopedSlots: { 
                        customRender: 'input_control', 
                    },
                    customRender: (value, row, index) => {

                        if (!row.group_row) {
                            if (row.input_control == 0) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Дехром",
                    dataIndex: "dechromization",
                    key: "dechromization",
                    scopedSlots: { 
                        customRender: 'dechromization', 
                    },
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.dechromization && !row.dechromization_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.dechromization && row.dechromization_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Шлифовка базы",
                    dataIndex: "base_grinding",
                    key: "base_grinding",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.base_grinding && !row.base_grinding_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.base_grinding && row.base_grinding_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Обезжир",
                    dataIndex: "steel_degreasing",
                    key: "steel_degreasing",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.steel_degreasing && !row.steel_degreasing_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.steel_degreasing && row.steel_degreasing_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Пред. меднение",
                    dataIndex: "pre_copper_plating",
                    key: "pre_copper_plating",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.pre_copper_plating && !row.pre_copper_plating_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.pre_copper_plating && row.pre_copper_plating_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Меднение базы",
                    dataIndex: "base_copper_plating",
                    key: "base_copper_plating",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.base_copper_plating && !row.base_copper_plating_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.base_copper_plating && row.base_copper_plating_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Обезжир",
                    dataIndex: "base_degreasing",
                    key: "base_degreasing",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.base_degreasing && !row.base_degreasing_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.base_degreasing && row.base_degreasing_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Меднение тираж",
                    dataIndex: "copper_plating_edition",
                    key: "copper_plating_edition",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.copper_plating_edition && !row.copper_plating_edition_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.copper_plating_edition && row.copper_plating_edition_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Шлифовка тираж",
                    dataIndex: "grinding_edition",
                    key: "grinding_edition",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.grinding_edition && !row.grinding_edition_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.grinding_edition && row.grinding_edition_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Гравирование",
                    dataIndex: "engraving",
                    key: "engraving",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.engraving && !row.engraving_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.engraving && row.engraving_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Обезжир",
                    dataIndex: "degreasing_edition",
                    key: "degreasing_edition",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.degreasing_edition && !row.degreasing_edition_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.degreasing_edition && row.degreasing_edition_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Хромирование",
                    dataIndex: "chrome_plating",
                    key: "chrome_plating",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.chrome_plating && !row.chrome_plating_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.chrome_plating && row.chrome_plating_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Полировка хром",
                    dataIndex: "polishing_chrome",
                    key: "polishing_chrome",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.polishing_chrome && !row.polishing_chrome_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.polishing_chrome && row.polishing_chrome_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Проб печать",
                    dataIndex: "test_print",
                    key: "test_print",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.test_print && !row.test_print_post) {
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF"/>
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            }else if (row.test_print && row.test_print_post){
                                return {
                                    children: <div class="" style="text-align: center; width: 100%;">
                                                <svg v-if="row.input_control == 1" xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" fill="#4A9DFF"/>
                                                    <rect x="1.10718" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M13.8144 4.70711C14.2049 5.09763 14.2049 5.7308 13.8144 6.12132L8.02148 11.9142C7.63096 12.3047 6.9978 12.3047 6.60727 11.9142L3.31438 8.62132C2.92385 8.2308 2.92385 7.59763 3.31438 7.20711C3.7049 6.81658 4.33807 6.81658 4.72859 7.20711L7.31438 9.79289L12.4002 4.70711C12.7907 4.31658 13.4239 4.31658 13.8144 4.70711Z" fill="white"/>
                                                </svg>
                                            </div>,
                                        attrs: {},
                                };
                            } else {
                                return {
                                    children: <span>
                                                    -
                                                </span> 
                                                ,
                                        attrs: {},
                                };
                            }
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                    {
                    title: "Время гравировки",
                    dataIndex: "",
                    key: "",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            return {
                                children: value,
                                attrs: {
                                },
                            };
                        } else {
                            return {
                                attrs: {
                                colSpan: 0,
                                },
                            };
                        }
                    },
                    },
                ],
                columns_machine_operation: [
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description"
                    },
                    {
                    title: "Номер вала",
                    dataIndex: "shaft_id",
                    key: "variable_body",
                    },
                    {
                    title: "№ Заказа",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    },
                    {
                    title: "Формат",
                    dataIndex: "",
                    key: "",
                    },
                    {
                    title: "Время начала",
                    dataIndex: "starting_time",
                    key: "starting_time",
                    },
                    {
                    title: "Время окончания",
                    dataIndex: "ending_time",
                    key: "ending_time",
                    },
                ],
                visibleMachineTable: false,
                selectMachine: '',
                selectMachineOperations: [],
                dailyPlanShafts: [...this.daily_plan_shafts],
                dailyPlanOperations: [...this.operations],
                machines: {
                    'EP': {
                        madeShafts: 0,
                    },
                    'CFM-1': {
                        madeShafts: 0,
                        workingShaft: '',
                        operations: [],
                    },
                    'CFM-2': {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    'K5-1': {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    'K5-2': {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    PP: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    VK: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    Ob: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    Pol: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    Chr: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    Dec: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    Pred: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    M81: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    M82: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                    M83: {
                        madeShafts: 0,
                        workingShaft: ''
                    },
                }
            }
        },
        mounted() {
            
        },
        computed: {
            shaftMadeInputControl() {               
                const dailyPlanOperations = this.dailyPlanOperations;
                dailyPlanOperations.forEach(item => {
                    if (item.machine in this.machines) {
                        const machine = this.machines[item.machine];
                        if (item.shaft_id !== null && item.ending_time !== '00:00:00') {
                            if (item.shaft_id !== null && item.ending_time !== '00:00:00') {
                                if (item.work_center === 'EP') {
                                    machine['EP'].madeShafts++;                                  
                                } else {
                                    machine.madeShafts++;
                                    machine.operations.push(item);
                                }
                            }
                        } else if (item.shaft_id !== null && item.ending_time === '00:00:00') {
                            machine.workingShaft = item.shaft_id;
                        } else if (item.shaft_id === null && item.ending_time !== '00:00:00') {
                            machine.operations.push(item);
                        } 
                        
                    }
                });
            },
        },
        methods: {
            showMachineTable(machine) {
                this.visibleMachineTable = true;
                this.selectMachineOperations = this.machines[machine].operations;
                this.selectMachine = machine;
            },
            getRowClassName(record, index) {
                return record.group_row ? 'group-row' : ''; // Добавляем класс 'group-row' для строк с group_row === true
            },
        }   
    }   
</script>

<style>
.group-row {
  background: var(--additional-lavender, #F2ECFF);
}
.table_daily_plan_wrapper {
    display: flex;
    padding: 20px;
    border-radius: 10px;
    background: #FFF;
}

.ant-tabs-content {
    background: none !important;
    box-shadow: none !important;
}
.brak_card {
    flex: 1;
    display: flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    margin-right: 15px;
    border-radius: 10px;
    background: var(--additional-light-red, #FFEBEB);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
}

.brak_card:hover {
    background: var(--primary-300-activ, #4A9DFF);
}

.brak_card:hover .made_shaft_qty {
    color: var(--grayscale-0, #FFF);
}

.brak_card:hover .made_shaft_desc {
    color: var(--grayscale-0, #FFF);
}

.brak_card:hover .card_title {
    color: var(--grayscale-0, #FFF);
}


.working_free_row {
    display: flex;
    height: 32px;
    padding: 5px 10px;
    align-items: center;
    gap: 8px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--additional-light-green, #EFFFE2);
}

.working_free_desc {
    color: var(--additional-dark-green, #04BA0B);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.electroplating_working_free_row {
    display: flex;
    height: 32px;
    padding: 5px 10px;
    align-items: center;
    gap: 8px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--additional-light-green, #EFFFE2);
}

.electroplating_working_free_desc {
    color: var(--additional-dark-green, #04BA0B);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.working_shaft_row {
    display: flex;
    padding: 5px 10px;
    align-items: center;
    gap: 8px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
}

.working_shaft_qty {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.working_shaft_desc {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.info_card_row {
    display: flex;
    margin-top: 15px;
    justify-content: space-between;
}

.machine_card:not(:last-child) {
    margin-right: 15px;
}

.machine_card {
    cursor: pointer;
    display: flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    flex: 3;
    border-radius: 10px;
    background: var(--grayscale-0, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25)
}

.machine_card:hover .made_shaft_qty {
    color: var(--grayscale-0, #FFF);
}

.machine_card:hover .card_title {
    color: var(--grayscale-0, #FFF);
}

.machine_card:hover .made_shaft_desc  {
    color: var(--grayscale-0, #FFF);
}

.machine_card:hover {
    background: var(--primary-300-activ, #4A9DFF);
}

.machine_card:hover .electroplating_working_free_row .working_free_row {
    background: var(--additional-light-green, #EFFFE2);
}

.machine_card:hover .electroplating_working_free_desc .working_free_desc {
    color: var(--additional-dark-green, #04BA0B) !important;
}

.electroplating_baths_card:hover .electroplating_card_title {
    color: var(--grayscale-0, #FFF);
}

.electroplating_baths_card:hover {
    background: var(--primary-300-activ, #4A9DFF);
}


.electroplating_card {
    cursor: pointer;
    display: flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    width: 100%;
    border-radius: 10px;
    background: var(--grayscale-0, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25)
}

.electroplating_baths_row {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.electroplating_baths_card:not(:last-child) {
    margin-right: 15px;
}


.electroplating_baths_card {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    padding: 6px 10px;
    justify-content: center;
    align-items: flex-start;
    gap: 8px;
    flex: 1 0 0;
    align-self: stretch;
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
}

.electroplating_card_title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.card_title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.18px;
}

.made_shaft_qty {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    margin-right: 10px;
}

.made_shaft_desc {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.tabs_row {
    margin-top: 20px;
}


</style>