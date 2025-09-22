<template>
    <div class="daily_plan_wrapper">
        <span class="wrapper_title">План УГПЦ</span>
        <div class="tabs_row">
            <a-tabs>
                <a-tab-pane key="1" tab="Свод">
                    <a-modal v-model="visibleMachineTable" width="1500px" :title="selectMachine">
                        <button class="export_excel_btn" @click="exportExcel" style="margin-right: 20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24" viewBox="0 0 23 24" fill="none">
                                <g clip-path="url(#clip0_1631_4544)">
                                <path d="M20.3742 12.8023H16.7163V10.9302H20.3742V12.8023ZM20.3742 13.8721H16.7163V15.7442H20.3742V13.8721ZM20.3742 5.0465H16.7163V6.91858H20.3742V5.04655V5.0465ZM20.3742 7.98836H16.7163V9.86044H20.3742V7.98836ZM20.3742 16.8139H16.7163V18.6861H20.3742V16.8139V16.8139ZM22.9007 20.5047C22.7962 21.061 22.143 21.0743 21.7066 21.0931H13.581V23.5H11.9585L0 21.3604V2.64223L12.0291 0.5H13.581V2.63151H21.4271C21.8687 2.65023 22.3546 2.61813 22.7387 2.88823C23.0078 3.28405 22.9817 3.78686 23 4.24149L22.9895 18.1645C22.9765 18.9428 23.0601 19.7371 22.9007 20.5047ZM9.58089 16.3085C8.8598 14.8109 8.12565 13.3238 7.40712 11.8261C8.11778 10.3686 8.81803 8.90571 9.51562 7.44278C8.92251 7.47221 8.32941 7.50964 7.73896 7.55245C7.29738 8.65161 6.78266 9.72139 6.4378 10.858C6.11643 9.78558 5.69057 8.75323 5.30127 7.70755C4.72645 7.73964 4.15163 7.77441 3.57686 7.80917C4.18299 9.17852 4.82838 10.529 5.41623 11.9064C4.72385 13.2436 4.0759 14.5996 3.4044 15.9448C3.97657 15.9688 4.54879 15.9929 5.12096 16.0009C5.52858 14.9365 6.03543 13.9122 6.39076 12.8264C6.70953 13.9925 7.25034 15.0702 7.69453 16.1855C8.3242 16.231 8.95126 16.2711 9.58093 16.3085H9.58089ZM21.7539 3.90168H13.581V5.0465H15.6713V6.91858H13.581V7.98836H15.6713V9.86044H13.581V10.9302H15.6713V12.8023H13.581V13.8721H15.6713V15.7442H13.581V16.8139H15.6713V18.6861H13.581V19.9228H21.7539V3.90168Z" fill="#13AA19"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_1631_4544">
                                <rect width="23" height="23" fill="white" transform="translate(0 0.5)"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </button>
                        <a-table :dataSource="selectMachineOperations" :columns="dynamicColumns" size="small" bordered>
                            <span slot="engraving_order.design_order.okvid_number" slot-scope="text, record, index">{{
                        okvidNumber(text) }}</span>
                            <span slot="duration" slot-scope="text, record, index">{{
                        timeDifference(record.start_time, record.end_time) }}</span>
                        </a-table>
                        <template slot="footer">
                            <a-button key="distribute_orders" type="primary">
                                OK
                            </a-button>
                        </template>
                    </a-modal>
                    <div class="dailyplan_filters" style="width: 800px; display: flex;">
                        <a-select default-value="Выберите смену" :allowClear="true" style="width: 50%"
                            v-model="filters.workShift" @change="changeFilters()">
                            <a-select-option v-for="work_shift_code in work_shift_codes" :key="work_shift_code.code">
                                {{ work_shift_code.letter }} - {{ work_shift_code.date }}
                            </a-select-option>
                        </a-select>
                        <date-picker type="date" style="margin-left: 20px; width: 20%;" range value-type="format"
                            format="YYYY-MM-DD" ref="filters_datapicker" v-model="filters.dates"
                            @change="changeFilters()"></date-picker>
                        <a-select default-value="Выберите оператора" :allowClear="true"
                            style="margin-left: 20px; width: 50%" v-model="filters.user" @change="changeFilters()">
                            <a-select-option v-for="user in users" :key="user.id">
                                {{ user.employer_name_1c ?? user.name }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <a-spin :spinning="spinning"></a-spin>
                    <div class="info_card" v-if="!spinning">
                    <div class="info_card_row">
                        <div class="brak_card" @click="showMachineTable('Brak')">
                            <span class="card_title">Брак</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['Brak'].madeShafts }}</span>
                                <span class="made_shaft_desc">вала</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('VK')">
                            <span class="card_title">Входной контроль</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['VK'].madeShafts }}</span>
                                <span class="made_shaft_desc">вала сделано</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('CFM-1')">
                            <span class="card_title">CFM-1</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['CFM-1'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['CFM-1'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['CFM-1'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                    fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="21" height="20">
                                        <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                        <path
                                            d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                                            fill="#BCDCF8" />
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{ machines['CFM-1'].workingShaft }}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('CFM-2')">
                            <span class="card_title">CFM-2</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['CFM-2'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['CFM-2'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['CFM-2'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                    fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="21" height="20">
                                        <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                        <path
                                            d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                                            fill="#BCDCF8" />
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{ machines['CFM-2'].workingShaft }}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                    </div>
                    <div class="info_card_row">
                        <div class="machine_card" @click="showMachineTable('K5-1')">
                            <span class="card_title">Гравер №1</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['K5-1'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['K5-1'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['K5-1'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                    fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="21" height="20">
                                        <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                        <path
                                            d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                                            fill="#BCDCF8" />
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{ machines['K5-1'].workingShaft }}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('K5-2')">
                            <span class="card_title">Гравер №2</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['K5-2'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['K5-2'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['K5-2'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                    fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="21" height="20">
                                        <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                        <path
                                            d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                                            fill="#BCDCF8" />
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{ machines['K5-2'].workingShaft }}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                        <div class="machine_card" @click="showMachineTable('PP-1')">
                            <span class="card_title">Печать</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['PP-1'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="working_free_row" v-if="machines['PP-1'].workingShaft == ''">
                                <span class="working_free_desc">Свободен</span>
                            </div>
                            <div class="working_shaft_row" v-if="machines['PP-1'].workingShaft != ''">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                    fill="none">
                                    <mask id="mask0_3548_4456" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                        y="0" width="21" height="20">
                                        <rect x="0.333496" width="20" height="20" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_3548_4456)">
                                        <path
                                            d="M13.2276 13.7723L14.1058 12.8941L10.9584 9.7465V5.83323H9.70842V10.2531L13.2276 13.7723ZM10.3348 17.9165C9.23984 17.9165 8.21065 17.7088 7.24721 17.2932C6.28375 16.8777 5.44568 16.3137 4.733 15.6013C4.0203 14.889 3.45609 14.0513 3.04035 13.0882C2.62462 12.1252 2.41675 11.0962 2.41675 10.0013C2.41675 8.90635 2.62453 7.87715 3.04008 6.91371C3.45564 5.95025 4.0196 5.11218 4.73196 4.3995C5.44433 3.68681 6.28203 3.12259 7.24506 2.70686C8.20807 2.29112 9.23705 2.08325 10.332 2.08325C11.4269 2.08325 12.4561 2.29103 13.4196 2.70659C14.383 3.12214 15.2211 3.6861 15.9338 4.39846C16.6465 5.11084 17.2107 5.94854 17.6264 6.91156C18.0422 7.87458 18.25 8.90356 18.25 9.9985C18.25 11.0934 18.0423 12.1226 17.6267 13.0861C17.2112 14.0495 16.6472 14.8876 15.9348 15.6003C15.2225 16.313 14.3848 16.8772 13.4217 17.2929C12.4587 17.7087 11.4297 17.9165 10.3348 17.9165ZM10.3334 16.6666C12.1806 16.6666 13.7535 16.0173 15.0521 14.7186C16.3508 13.42 17.0001 11.8471 17.0001 9.9999C17.0001 8.15268 16.3508 6.57976 15.0521 5.28115C13.7535 3.98254 12.1806 3.33323 10.3334 3.33323C8.48617 3.33323 6.91326 3.98254 5.61464 5.28115C4.31603 6.57976 3.66673 8.15268 3.66673 9.9999C3.66673 11.8471 4.31603 13.42 5.61464 14.7186C6.91326 16.0173 8.48617 16.6666 10.3334 16.6666Z"
                                            fill="#BCDCF8" />
                                    </g>
                                </svg>
                                <span class="working_shaft_qty">{{ machines['PP-1'].workingShaft }}</span>
                                <span class="working_shaft_desc">вал в работе</span>
                            </div>
                        </div>
                    </div>
                    <div class="info_card_row">
                        <div class="electroplating_card">
                            <span class="card_title" @click="showMachineTable('EP')">Гальваника</span>
                            <div class="made_shaft_row">
                                <span class="made_shaft_qty">{{ machines['EP'].madeShafts }}</span>
                                <span class="made_shaft_desc">валов сделано</span>
                            </div>
                            <div class="electroplating_baths_row">
                                <div class="electroplating_baths_card" @click="showMachineTable('Pol')">
                                    <span class="electroplating_card_title">Полировка</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['Pol'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['Pol'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['Pol'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['Pol'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Chr')">
                                    <span class="electroplating_card_title">Хромирование</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['Chr'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['Chr'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['Chr'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['Chr'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Dec')">
                                    <span class="electroplating_card_title">Дехромирование</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['Dec'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['Dec'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['Dec'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['Dec'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Ob')">
                                    <span class="electroplating_card_title">Обезжиривание</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['Ob'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['Ob'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row" v-if="machines['Ob'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['Ob'].workingShaft }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('Pred')">
                                    <span class="electroplating_card_title">Предмедь</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['Pred'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['Pred'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['Pred'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['Pred'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M81')">
                                    <span class="electroplating_card_title">Меднение 8.1</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['M81'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['M81'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['M81'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['M81'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M82')">
                                    <span class="electroplating_card_title">Меднение 8.2</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['M82'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['M82'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['M82'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['M82'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                                <div class="electroplating_baths_card" @click="showMachineTable('M83')">
                                    <span class="electroplating_card_title">Меднение 8.3</span>
                                    <div class="made_shaft_row">
                                        <span class="made_shaft_qty">{{ machines['M83'].madeShafts }}</span>
                                        <span class="made_shaft_desc">валов сделано</span>
                                    </div>
                                    <div class="electroplating_working_free_row"
                                        v-if="machines['M83'].workingShaft == ''">
                                        <span class="electroplating_working_free_desc">Свободен</span>
                                    </div>
                                    <div class="electroplating_made_shaft_row"
                                        v-if="machines['M83'].workingShaft != ''">
                                        <span class="electroplating_shaft_work">{{ machines['M83'].workingShaft
                                            }}</span>
                                        <span class="electroplating_shaft_desc">вал в работе</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </a-tab-pane>
                <a-tab-pane key="2" tab="Табличный план" force-render>
                    <div class="statistic_block">
                        <span class="statistic_title">Валов в плане:</span><span class="statistic_value">{{this.countShafts}}</span>
                    </div>
                    <div class="table_daily_plan_wrapper">
                        <a-table 
                            :columns="columnsDailyPlan" 
                            :data-source="routeMaps" 
                            :rowKey="(record, index) => index"
                            :pagination="false" style="font-size: 12px;" 
                            :rowClassName="getRowClassName" bordered
                            :scroll="{ y: 800 }"
                            size="small"
                        >
                            <span style="display: flex; justify-content: center; align-items: center; gap: 5px;" slot="checkbox"
                                slot-scope="text, record, index">
                                <a-popover placement="topLeft" arrow-point-at-center>
                                    <template #content>
                                        <h6 v-if="text && text.defect_note">{{text.defect_note}}</h6>
                                    </template>
                                    <CheckBoxElement v-if="text" :data="text"></CheckBoxElement>
                                    <span v-if="!text">-</span>
                                </a-popover>
                            </span>
                        </a-table>
                    </div>
                </a-tab-pane>
            </a-tabs>
        </div>
        <a-modal
            :visible="visibleAddComment"
            @cancel="visibleAddComment = false"
            title="Добавить комментарий"
            >
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" v-model="orderComment" placeholder="Комментарий"></textarea>
            <template slot="footer">
                <a-button key="cancel_delete_operation" type="default" @click="visibleAddComment = false">
                Отмена
                </a-button>
                <a-button key="delete_operation" type="primary" @click="saveComment()">
                Сохранить
                </a-button>
            </template>
        </a-modal>
    </div>
</template>



<script>
import DatePicker from 'vue2-datepicker';
import CheckBoxElement from "./elements/CheckBoxElement.vue";
import { Excel } from "antd-table-saveas-excel";
export default {
    components: {
        CheckBoxElement,
        DatePicker
    },
    props: {
        route_maps: Array,
        operations: Array,
        work_shift_codes: Array,
        urldailyplangetdata: String,
        users: Array,
        urlupdateorder: String,
        urladdcomment: String,
    },
    data() {
        return {
            dailyPlanOperations: [...this.operations],
            visibleAddComment: false,
            orderComment: '',
            selectOrderId: null,
            filters: {
                dates: [null, null],
                workShift: null,
                user: null,
            },
            Brak: {
                madeShafts: 0,
                operations: [],
            },
            spinning: false,
            routeMaps: [...this.route_maps],
            machines: {
                'Brak': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'EP': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'CFM-1': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'CFM-2': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'K5-1': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'K5-2': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'PP-1': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'VK': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'Ob': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'Pol': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'Chr': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'Dec': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'Pred': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'M81': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'M82': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
                'M83': {
                    madeShafts: 0,
                    workingShaft: '',
                    operations: [],
                },
            },
            columnsDailyPlan: [
                {
                    title: "Вал",
                    dataIndex: "engraving_order.shaft.shaft_id",
                    key: "engraving_order.shaft.shaft_id",
                    width: "7%",
                    customRender: (value, row, index) => {

                        if (!row.group_row) {

                            const obj = {
                                children: 
                                    <div style="display:flex; justify-content: space-between;">
                                        <a 
                                            target="_blank" 
                                            href={`https://okvid.danaflex.ru/ugpc/engravingorders/order/${row.engraving_order.id}`}
                                        >
                                            {value}
                                        </a>
                                        {row.comment && (
                                            <a-tooltip placement="topLeft">
                                                <template slot="title">
                                                    <span>{row.comment}</span>
                                                </template>
                                                <a-icon type="message" />
                                            </a-tooltip>
                                        )}
                                    </div>,
                                attrs: {
                                    class: row.engraving_order?.shaft.last_okvid ? "" : "steel"
                                },
                            };

                            if (this.routeMaps[index-1].engraving_order?.shaft.shaft_id != value) {

                                row.rowSpan = true;
                                obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == value).length;

                            } else {
                                row.rowSpan = false;
                                obj.attrs.rowSpan = 0;
                            }

                            return obj;
                        } else {
                            return {
                                children: <div style="display: flex; justify-content: space-between"><div><span style="margin-right: 20px;">Оквид: {this.okvidNumber(row.okvid_number)}</span> <span style="margin-right: 20px;"> Дата готовности: {this.formatDate(row.completion_date)}</span><span style="margin-right: 20px;"> Заказ: {row?.order_number}</span><span style="margin-right: 20px; font-weight: bold;">{row?.comment}</span></div><div><a-icon style="cursor: pointer" onClick={() => this.showAddComment(row)} type="plus-circle" /></div></div>,
                                attrs: {
                                    colSpan: 18,
                                },
                            };
                        }
                    },
                },
                
                {
                    title: "Сеп.",
                    dataIndex: "engraving_order.separation_number",
                    key: "engraving_order.separation_number",
                    width: "3%",
                    customRender: (value, row, index) => {

                        if (!row.group_row) {
                            if (row.rowSpan) {

                                const obj = {
                                    children: value,
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                return obj;
                                

                            } else {
                                const obj = {
                                    children: value,
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = 0;


                                return obj;
                            }
                        } else {
                            return {
                                children: '',
                                attrs: {
                                    colSpan: 0,
                                },
                            };
                        }
                    },
                },
                {
                    title: "Вх.конт.",
                    dataIndex: "engraving_order.input_control",
                    key: "engraving_order.input_control",
                    width: "5%",
                    customRender: (value, row, index) => {

                        if (!row.group_row) {
                            if (row.rowSpan) {

                                if (value == true) {
                                    const obj = {
                                    children: 
                                            <div style="display: flex; justify-content: center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                    height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#03fc39" />
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                        ,
                                        attrs: {

                                        },
                                    };

                                    obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                    return obj;
                                }

                                if (value == false) {

                                    const obj = {
                                        children: 
                                                <div style="display: flex; justify-content: center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                        height="16" viewBox="0 0 17 16" fill="none">
                                                        <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF" />
                                                        <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                        ,
                                        attrs: {

                                        },
                                    };

                                    obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                    return obj;
                                }

                            } else {
                                const obj = {
                                    children: value,
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = 0;

                        
                                return obj;
                            }
                        } else {
                            return {
                                children: '',
                                attrs: {
                                    colSpan: 0,
                                },
                            };
                        }
                    },
                },
                {
                    title: "Подтв.",
                    dataIndex: "engraving_order.confirmed",
                    key: "engraving_order.confirmed",
                    width: "5%",
                    customRender: (value, row, index) => {
                        if (!row.group_row) {
                            if (row.rowSpan) {

                                if (value == true) {
                                    const obj = {
                                    children: 
                                            <div style="display: flex; justify-content: center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                    height="16" viewBox="0 0 17 16" fill="none">
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#03fc39" />
                                                    <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                        ,
                                        attrs: {

                                        },
                                    };

                                    obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                    return obj;
                                }

                                if (value == false) {

                                    const obj = {
                                        children: 
                                                <div style="display: flex; justify-content: center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                        height="16" viewBox="0 0 17 16" fill="none">
                                                        <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" fill="#BFBFBF" />
                                                        <rect x="1.17871" y="0.5" width="15" height="15" rx="3.5" stroke="#9A9A9A" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M13.8859 4.70711C14.2764 5.09763 14.2764 5.7308 13.8859 6.12132L8.09302 11.9142C7.70249 12.3047 7.06933 12.3047 6.6788 11.9142L3.38591 8.62132C2.99539 8.2308 2.99539 7.59763 3.38591 7.20711C3.77644 6.81658 4.4096 6.81658 4.80012 7.20711L7.38591 9.79289L12.4717 4.70711C12.8622 4.31658 13.4954 4.31658 13.8859 4.70711Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                        ,
                                        attrs: {

                                        },
                                    };

                                    obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                    return obj;
                                }

                            } else {
                                const obj = {
                                    children: value,
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = 0;


                                return obj;
                            }
                        } else {
                            return {
                                children: '',
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
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 1);
                    },   
                },
                {
                    title: "Шлифовка базы",
                    key: "base_grinding",
                    dataIndex: "base_grinding",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 3);
                    },   
                },
                {
                    title: "Обезжир",
                    dataIndex: "steel_degreasing",
                    key: "steel_degreasing",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 2);
                    },   
                },
                {
                    title: "Пред. меднение",
                    dataIndex: "pre_copper_plating",
                    key: "pre_copper_plating",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 5);
                    },   
                },
                {
                    title: "Меднение базы",
                    dataIndex: "base_copper_plating",
                    key: "base_copper_plating",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 6);
                    },   
                },
                {
                    title: "Обезжир",
                    dataIndex: "base_degreasing",
                    key: "base_degreasing",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 4);
                    },   
                },
                {
                    title: "Меднение тираж",
                    dataIndex: "copper_plating_edition",
                    key: "copper_plating_edition",
                    scopedSlots: { customRender: 'copper_plating_edition' },
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 12);
                    },   
                },
                {
                    title: "Шлифовка тираж",
                    dataIndex: "grinding_edition",
                    key: "grinding_edition",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 7);
                    },   
                },
                {
                    title: "Гравирование",
                    dataIndex: "engraving",
                    key: "engraving",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 9);
                    },   
                },
                {
                    title: "Обезжир",
                    dataIndex: "degreasing_edition",
                    key: "degreasing_edition",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 8);
                    },   
                },
                {
                    title: "Хромирование",
                    dataIndex: "chrome_plating",
                    key: "chrome_plating",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 10);
                    },   
                },
                {
                    title: "Полировка хром",
                    dataIndex: "polishing_chrome",
                    key: "polishing_chrome",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 11);
                    },   
                },
                {
                    title: "Проб печать",
                    dataIndex: "test_print",
                    key: "test_print",
                    customRender: (value, row, index, column) => {
                        return this.customRow(value, row, index, column, 13);
                    },     
                },
                {
                    title: "Время грав.",
                    dataIndex: "engraving_order.engraving_time",
                    key: "engraving_order.engraving_time",
                    customRender: (value, row, index, column) => {
                        if (!row.group_row) {
                            if (row.rowSpan) {

                                const obj = {
                                    children: (
                                        <div>
                                            <a-input v-model={row.engraving_order.engraving_time} onChange={() => this.updateEngravingOrder(row)} />
                                        </div>
                                    ),
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = this.routeMaps.filter((elem) => elem.engraving_order?.shaft.shaft_id == row.engraving_order?.shaft.shaft_id).length;

                                return obj;
                                

                            } else {
                                const obj = {
                                    children: value,
                                    attrs: {

                                    },
                                };

                                obj.attrs.rowSpan = 0;


                                return obj;
                            }
                        } else {
                            return {
                                children: '',
                                attrs: {
                                    colSpan: 0,
                                },
                            };
                        }
                    },
                },
            ],
            filters: {
                workShiftCode: null,
                dateStart: null,
                dateEnd: null,
                operator: null,
            },
            visibleMachineTable: false,
            selectMachine: '',
            selectMachineOperations: [],
            columns_machine_operation: [
                {
                    title: "Операция",
                    dataIndex: "operation.description",
                    key: "operation.description"
                },
                {
                    title: "Номер вала",
                    dataIndex: "engraving_order.shaft.shaft_id",
                    key: "engraving_order.shaft.shaft_id",
                },
                {
                    title: "Оквид",
                    dataIndex: "engraving_order.design_order.okvid_number",
                    key: "engraving_order.design_order.okvid_number",
                    scopedSlots: { customRender: 'engraving_order.design_order.okvid_number' },
                },
                {
                    title: "№ заказа",
                    dataIndex: "engraving_order.design_order.prod_order",
                    key: "engraving_order.design_order.prod_order",
                    scopedSlots: { customRender: 'engraving_order.design_order.prod_order' },
                },
                {
                    title: "Формат",
                    dataIndex: "engraving_order.format",
                    key: "engraving_order.format",
                },
                {
                    title: "Дата",
                    dataIndex: "posting_date",
                    key: "posting_date",
                },
                {
                    title: "Смена",
                    dataIndex: "work_shift.letter",
                    key: "work_shift.letter",
                },
                {
                    title: "Время начала",
                    dataIndex: "start_time",
                    key: "start_time",
                },
                {
                    title: "Время окончания",
                    dataIndex: "end_time",
                    key: "end_time",
                },
                {
                    title: "Длительность",
                    dataIndex: "duration",
                    key: "duration",
                    scopedSlots: {
                        customRender: 'duration',
                    },
                },
                {
                    title: "Оператор",
                    dataIndex: "user.employer_name_1c",
                    key: "user.employer_name_1c",
                },
            ],
            columns_machine_operation_export: [
                {
                    title: "Операция",
                    dataIndex: "operation",
                    key: "operation",
                    render: (value) => {
                        return value != null ? String(value.description): '';
                    },
                },
                {
                    title: "Номер вала",
                    dataIndex: "engraving_order",
                    key: "engraving_order",
                    render: (value) => {
                        return value != null ? String(value.shaft.shaft_id): '';
                    },
                },
                {
                    title: "Оквид",
                    dataIndex: "engraving_order",
                    key: "engraving_order",
                    scopedSlots: { customRender: 'engraving_order' },
                    render: (value) => {
                        return value != null ? String(this.okvidNumber(value.design_order.okvid_number)): '';
                    },
                },
                {
                    title: "№ заказа",
                    dataIndex: "engraving_order",
                    key: "engraving_order",
                    scopedSlots: { customRender: 'engraving_order' },
                    render: (value) => {
                        return value != null ? String(value.design_order.prod_order): '';
                    },
                },
                {
                    title: "Формат",
                    dataIndex: "engraving_order",
                    key: "engraving_order",
                    render: (value) => {
                        return value != null ? String(value.format): '';
                    },
                },
                {
                    title: "Дата",
                    dataIndex: "posting_date",
                    key: "posting_date",
                },
                {
                    title: "Смена",
                    dataIndex: "work_shift",
                    key: "work_shift",
                    render: (value) => {
                        return value != null ? String(value.letter): '';
                    },
                },
                {
                    title: "Время начала",
                    dataIndex: "start_time",
                    key: "start_time",
                },
                {
                    title: "Время окончания",
                    dataIndex: "end_time",
                    key: "end_time",
                },
                {
                    title: "Длительность",
                    dataIndex: "duration",
                    key: "duration",
                    render: (row) => {
                        return row != null ? timeDifference(row.start_time, row.end_time) : '';
                    },
                },
                {
                    title: "Оператор",
                    dataIndex: "user",
                    key: "user",
                    render: (value) => {
                        return value != null ? String(value.employer_name_1c): '';
                    },
                },
            ],
        };
    },
    mounted() {
        this.shaftMadeInputControl();
    },
    computed: {
        countShafts() {
            const count = this.routeMaps.filter((shaft) => {
                return (shaft.engraving_order);
            }).length;

            return count;   
        },

        dynamicColumns() {
            let dynamicColumns = [...this.columns_machine_operation];

            if (this.selectMachine == "CFM-1" || this.selectMachine == "CFM-2") {
                dynamicColumns.push({
                    title: "Диаметр",
                    dataIndex: "fact_diameter.float_value",
                    key: "fact_diameter.float_value",
                });

                dynamicColumns.push({
                    title: "Разница D",
                    dataIndex: "diameter_difference.float_value",
                    key: "diameter_difference.float_value",
                });

                dynamicColumns.push({
                    title: "Твердость",
                    dataIndex: "fact_hardness.float_value",
                    key: "fact_hardness.float_value",
                });

                dynamicColumns.push({
                    title: "Ванна",
                    dataIndex: "bath.string_value",
                    key: "bath.string_value",
                });

            } else if (this.selectMachine == "K5-1" || this.selectMachine == "K5-2") {
                dynamicColumns.push({
                    title: "Номер головы",
                    dataIndex: "head_number",
                    key: "head_number",
                });

                dynamicColumns.push({
                    title: "Номер головы",
                    dataIndex: "cutter.float_value",
                    key: "cutter.float_value",
                });

            } else if (this.selectMachine == "M81" || this.selectMachine == "M82" || this.selectMachine == "M82") {
                dynamicColumns.push({
                    title: "Толщина",
                    dataIndex: "thickness.float_value",
                    key: "thickness.float_value",
                });
            } else if (this.selectMachine == "Brak") {
                dynamicColumns.push({
                    title: "Описание",
                    dataIndex: "stage.defect_note",
                    key: "stage.defect_note",
                });
            }



            return dynamicColumns;
        }
    },
    methods: {
        formatDate(text) {
            if (text != null) {
                return new Date(text).toLocaleDateString('ru-RU', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                });
            }
        },

        showAddComment(shaft) {
            this.selectOrderId = shaft.id;
            this.orderComment = shaft.comment;
            this.visibleAddComment = true;

        },

        saveComment() {
            axios.post(this.urladdcomment, { id: this.selectOrderId, comment: this.orderComment} 
                )
                .then(response => {
                    this.visibleAddComment = false;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
        },

        customRow(value, row, index, column, stage) {
            if (!row.group_row) {
                if (row.engraving_stages_by_stage_id && row.engraving_stages_by_stage_id.hasOwnProperty(stage)) {
                    let stageData = row.engraving_stages_by_stage_id[stage];
                    
                    return column.title[0].context.$parent.$scopedSlots.checkbox(stageData);
                } else {
                    return column.title[0].context.$parent.$scopedSlots.checkbox(null);
                }
            } else {
                return {
                    children: '',
                    attrs: { colSpan: 0 },
                };
            }
        },


        updateEngravingOrder(order) {
            axios.post(this.urlupdateorder, order.engraving_order 
                )
                .then(response => {

                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
        },

        okvidNumber(okvid_number) {
            return String(okvid_number).slice(0, String(okvid_number).length - 2) + '-' + String(okvid_number).slice(-2);
        },

        changeFilters() {
            this.spinning = true;
            axios.post(this.urldailyplangetdata, { code: this.filters })
                .then(response => {
                    this.dailyPlanOperations = response.data;
                    this.shaftMadeInputControl();
                    this.spinning = false;
                })
                .catch(err => {
                    console.log(err);
                });
        },

        timeDifference(time_start, time_end) {
            const seconds1 = this.timeToSeconds(time_start);
            const seconds2 = this.timeToSeconds(time_end);
            if (seconds2 < seconds1) {
                const differenceInSeconds = Math.abs(24*60*60)-Math.abs((seconds2 - seconds1));

                return this.secondsToTimeFormat(differenceInSeconds);
            } else {
                const differenceInSeconds = Math.abs(seconds2 - seconds1);

                return this.secondsToTimeFormat(differenceInSeconds);
            }
        },
        timeToSeconds(time) {
            const [hours, minutes, seconds] = time.split(':').map(Number);
            return hours * 3600 + minutes * 60 + seconds;
        },
        secondsToTimeFormat(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        },
        getRowClassName(record, index) {
            return record.group_row ? 'group-row' : '';
        },

        showMachineTable(machine) {
            this.visibleMachineTable = true;
            this.selectMachineOperations = this.machines[machine].operations;
            this.selectMachine = machine;
        },

        exportExcel() {
            console.log(this.selectMachineOperations);
                const excel = new Excel();
                excel
                .addSheet("1")
                .addColumns(this.columns_machine_operation_export)
                .addDataSource(this.selectMachineOperations)
                .saveAs("Excel.xlsx");
            },

        shaftMadeInputControl() {
            Object.keys(this.machines).forEach(machine => {
                this.machines[machine].madeShafts = 0;
                this.machines[machine].workingShaft = '';
                this.machines[machine].operations = [];
            });

            const dailyPlanOperations = this.dailyPlanOperations;
            dailyPlanOperations.forEach(operation => {
                if ((operation.machine) && (operation.machine.machine in this.machines) && (operation.operation_id != 68)) {
                    const machine = this.machines[operation.machine.machine];
                    if (operation.engraving_order_id !== null && operation.end_time !== null) {
                        if (operation.machine.work_center == 'EP') {
                            this.machines['EP'].madeShafts++;
                            this.machines['EP'].operations.push(operation);
                        }
                        machine.madeShafts++;
                        machine.operations.push(operation);
                    } else if (operation.engraving_order_id !== null && operation.end_time === null) {
                        machine.workingShaft = operation.engraving_order.shaft.shaft_id;
                    }
                } else {
                    this.machines['Brak'].madeShafts++;
                    this.machines['Brak'].operations.push(operation);
                }
            });
        },
    },
};
</script>

<style>
.steel {
    background: #c5d4e6;
}
.group-row {
    background: var(--additional-lavender, #F2ECFF);
}

.table_daily_plan_wrapper {
    display: flex;
    padding: 20px;
    border-radius: 10px;
    background: #FFF;
}

.tabs_row {
    margin-top: 20px;
}

.order_header {
    display: flex;
    width: 100%;
    background-color: #f2ecff;
    color: #363f51;
}

.info_card_row {
    display: flex;
    margin-top: 15px;
    justify-content: space-between;
}

.statistic_block {
    display: flex;
    margin-right: 30px;
}

.statistic_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.statistic_value {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}
</style>