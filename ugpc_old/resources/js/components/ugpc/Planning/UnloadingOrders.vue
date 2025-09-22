<template>
    <div class="wrapper">
        <span class="wrapper_title">Выгрузка заказов</span>
        <div class="wrapper_body">
            <div class="nav_bar_row">
                <div class="nav_bar_left_block">
                    <a-input-search 
                        placeholder="Поиск по № заказа или клиенту" 
                        style="width: 300px; height: 100%; margin-right: 20px;
                        display: flex;padding: 6px 0px;align-items: flex-start;gap: 4px;flex: 1 0 0;" 
                        :allow-clear="true"
                        v-model="searchText"
                    />
                    <div class="filters">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <mask id="mask0_1709_9721" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="22">
                            <rect width="22" height="22" fill="#D9D9D9"/>
                            </mask>
                            <g mask="url(#mask0_1709_9721)">
                            <path d="M10.4359 17.875C10.2056 17.875 10.0129 17.7974 9.85772 17.6423C9.70259 17.4872 9.62503 17.2944 9.62503 17.0641V11.758L4.49348 5.23912C4.3172 5.00407 4.29164 4.75962 4.4168 4.50578C4.54196 4.25194 4.75319 4.12502 5.05051 4.12502H16.9495C17.2468 4.12502 17.458 4.25194 17.5832 4.50578C17.7084 4.75962 17.6828 5.00407 17.5065 5.23912L12.375 11.758V17.0641C12.375 17.2944 12.2974 17.4872 12.1423 17.6423C11.9872 17.7974 11.7944 17.875 11.5641 17.875H10.4359ZM11 11.275L15.5375 5.5H6.4625L11 11.275Z" fill="#363F51"/>
                            </g>
                        </svg>
                        <a-dropdown :trigger="['click']"> 
                            <span  style="cursor: pointer;"  class="filters_title">Фильтр</span> 
                            <a-menu slot="overlay" style="box-shadow: none !important; width: 0%;">
                            <a-menu-item key="1">
                            <div class="filters_wrapper" ref="filters_wrapper" @click="handleDivClick">
                                <date-picker
                                type="date"
                                range
                                value-type="format"
                                format="YYYY-MM-DD"
                                ref="filters_datapicker"
                                v-model="filterDate"
                                @change="changeFilters()"
                            ></date-picker>
                            <div class="filters_area">
                                <span class="filters_area_title">Заказы на площадках</span>
                                <div class="filters_area_value_block">
                                    <button
                                        v-for="(button, index) in buttonsArea"
                                        :key="index"
                                        :class="{
                                        active_button_area: activeButtonsArea.includes(button),
                                        filters_area_value: !activeButtonsArea.includes(button)
                                        }"
                                        @click="toggleButtonArea(button)"
                                    >
                                        {{ button }}
                                    </button>
                                </div>
                            </div>
                            <div class="filters_novizna_block">
                                <span class="filters_novizna_title">Новизна</span>
                                <a-select class="filters_novizna_select" v-model="filterNovizna" allowClear :value="filterNovizna">
                                    <a-select-option v-for="novizna in this.movement_novizna" :key="novizna">
                                        {{ novizna }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_condition_block">
                                <span class="filters_condition_title">Тех. Состояние</span>
                                <a-select class="filters_condition_select" v-model="filterCondition" allowClear>
                                    <a-select-option v-for="condition in this.conditions" :key="condition">
                                        {{ condition }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_status_block">
                                <span class="filters_status_title">Статус</span>
                                <a-select class="filters_status_select" v-model="filterStatus" allowClear>
                                    <a-select-option v-for="status in this.movement_statuses" :key="status">
                                        {{ status }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_customer_block">
                                <span class="filters_customer_title">Клиент</span>
                                <v-select
                                    class="filters_status_select"
                                    style="border: 1px solid #d9d9d9;border-top-width: 1.02px;border-radius: 4px;"
                                    @search="query => (searchName = query)"
                                    :options="this.customers"
                                    :value="filterCustomer"
                                    v-model="filterCustomer"
                                >
                                </v-select>  
                            </div>
                            <div class="filters_btn_clear_block">
                                <span style="cursor: pointer;" @click="clearFilters()">Очистить фильтр</span>
                            </div>
                            </div>
                            </a-menu-item>
                            </a-menu>
                        </a-dropdown>                    
                    </div>
                </div>
                <div class="nav_bar_right_block">
                    <button @click="showAllEditModal" v-if="showAllEditBtn" class="all_edit_btn">Редактировать</button>
                    <button @click="distributeOrdersModal" v-if="!showAllEditBtn" class="distribute_btn">Распределить заказы</button>
                    <a-dropdown
                        :trigger="['click']"
                        style="padding: 10px;"
                        v-model="visible"
                    >
                        <button class="setting_btn"
                            href="javascript:void(0)"
                            @click="e => e.preventDefault()"
                            :disabled="!columns_orders_unloading.length"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24" viewBox="0 0 23 24" fill="none">
                                <path d="M22.8686 10.2257C22.54 9.76573 22.2772 9.70001 21.2914 9.56858C20.24 9.43715 20.24 9.43715 20.1743 9.04287C20.1086 8.91144 19.9772 8.51715 19.8457 8.18858L19.78 8.12287C19.5172 7.59715 19.5172 7.59715 20.1086 6.8743L20.1743 6.74287C20.7657 6.02001 20.8972 5.75715 20.8972 5.42858C20.8972 4.96858 20.7 4.70572 19.7143 3.72001C18.86 2.86572 18.7286 2.80001 18.4657 2.7343C17.94 2.60287 17.6772 2.7343 16.8886 3.39144C16.4943 3.72001 16.1657 3.98287 16.1 3.98287C16.0343 3.98287 15.8372 3.98287 15.3772 3.78572C15.0486 3.6543 14.6543 3.45715 14.4572 3.45715C14.1286 3.39144 14.1286 3.32572 13.9972 2.40572C13.8657 1.94572 13.8 1.48572 13.8 1.3543C13.7343 1.02572 13.4714 0.697153 13.1429 0.565724C12.88 0.434296 12.7486 0.434296 11.6314 0.434296C11.04 0.434296 10.3829 0.434296 10.1857 0.50001C9.79145 0.565724 9.52859 0.762867 9.33145 1.02572C9.20002 1.28858 9.20002 1.3543 9.06859 2.40572C8.93716 3.32572 8.93716 3.32572 8.60859 3.45715C8.47716 3.52287 8.08288 3.6543 7.68859 3.78572C7.36002 3.91715 7.09716 4.04858 6.96573 3.98287C6.90002 3.98287 6.57145 3.72001 6.17716 3.39144C5.38859 2.7343 5.06002 2.60287 4.53431 2.7343C4.33716 2.80001 4.20573 2.93144 3.35145 3.72001C2.36573 4.70572 2.16859 4.96858 2.16859 5.42858C2.16859 5.82287 2.30002 6.08572 2.89145 6.74287L2.95716 6.8743C3.48288 7.53144 3.48288 7.53144 3.28573 8.12287L3.22002 8.18858C3.08859 8.51715 2.89145 8.91144 2.82573 9.04287C2.69431 9.43715 2.69431 9.43715 1.70859 9.56858C0.657163 9.70001 0.328591 9.83144 0.0657342 10.2257L-0.131409 10.4886V13.38L0.0657342 13.6429C0.328591 14.0372 0.657163 14.1686 1.64288 14.3C2.16859 14.3657 2.49716 14.4314 2.56288 14.4314C2.62859 14.4972 2.89145 15.0229 2.89145 15.1543C2.89145 15.1543 2.89145 15.22 3.15431 15.7457C3.28573 16.0743 3.41716 16.3372 3.41716 16.3372C3.41716 16.4029 3.22002 16.6657 2.89145 17.06C2.62859 17.3886 2.30002 17.7829 2.23431 17.98C2.10288 18.3086 2.10288 18.6372 2.23431 18.9657C2.36573 19.2943 4.07431 21.0029 4.33716 21.0686C4.60002 21.1343 5.12573 21.1343 5.32288 21.0686C5.45431 21.0029 5.84859 20.74 6.17716 20.4772C6.76859 20.0172 6.90002 19.9514 6.96573 19.9514C7.03145 19.9514 7.22859 20.0172 7.42573 20.0829C7.62288 20.1486 8.01716 20.3457 8.28002 20.4772C8.60859 20.6086 8.87145 20.74 8.87145 20.74C8.87145 20.74 8.93716 20.8714 9.06859 21.66C9.20002 22.6457 9.33145 22.9743 9.79145 23.2372C10.0543 23.3686 10.1857 23.3686 11.4343 23.3686C12.6829 23.3686 12.88 23.3686 13.0772 23.2372C13.6029 22.9743 13.6686 22.6457 13.8 21.66C13.9314 20.6743 13.9314 20.6743 14.4572 20.5429C14.6543 20.4772 15.0486 20.3457 15.3114 20.2143C15.64 20.0829 15.9029 19.9514 15.9029 19.9514C15.9686 19.9514 16.2314 20.0829 16.6914 20.4772C17.48 21.1343 17.7429 21.2 18.2686 21.1343C18.5972 21.0686 18.7286 20.9372 19.5172 20.1486C20.5029 19.1629 20.7 18.9 20.7 18.44C20.7 18.0457 20.5686 17.7829 19.9772 17.06C19.6486 16.6657 19.4514 16.4029 19.4514 16.3372C19.4514 16.2714 19.5172 16.0086 19.7143 15.7457C19.8457 15.4172 19.9772 15.0886 20.0429 14.9572C20.1743 14.4972 20.3057 14.4314 20.3057 14.4314C20.3714 14.4314 20.7 14.3657 21.2257 14.3C22.2114 14.1686 22.54 14.0372 22.8029 13.6429L23 13.38V10.4229L22.8686 10.2257ZM18.86 15.68C18.4657 16.5343 18.5314 16.7972 19.32 17.7172C19.8457 18.3743 19.9114 18.5057 19.8457 18.6372C19.8457 18.7029 19.5172 19.0972 18.9914 19.5572L18.9257 19.6229C18.0714 20.4772 18.0714 20.4772 17.3486 19.8857L17.2829 19.82C16.2972 19.0314 16.0343 18.9657 15.0486 19.4257C14.72 19.5572 14.26 19.7543 13.9972 19.8857C13.2086 20.1486 13.0772 20.4114 12.9457 21.7914C12.88 22.1857 12.88 22.4486 12.8143 22.5143C12.7486 22.6457 12.42 22.7114 11.5 22.7114C10.58 22.7114 10.2514 22.6457 10.1857 22.5143C10.1857 22.4486 10.12 22.1857 10.0543 21.7914C9.92288 20.4772 9.79145 20.2143 8.93716 19.8857C8.6743 19.7543 8.2143 19.5572 7.88573 19.4257C7.55716 19.2286 7.2943 19.1629 7.03145 19.1629C6.63716 19.1629 6.30859 19.36 5.65145 19.8857L5.58573 19.9514C4.92859 20.4772 4.92859 20.4772 4.00859 19.6229L3.94288 19.5572C3.54859 19.1629 3.08859 18.7029 3.08859 18.5714C3.08859 18.5057 3.15431 18.3743 3.54859 17.8486C4.40288 16.7972 4.46859 16.6 4.07431 15.68C3.94288 15.4172 3.81145 14.9572 3.68002 14.76C3.41716 13.84 3.15431 13.6429 1.97145 13.5114C1.05145 13.4457 1.05145 13.38 0.92002 13.3143C0.854306 13.2486 0.854306 13.1172 0.854306 12.0657V11.8029C0.854306 10.6857 0.854306 10.6857 1.64288 10.62H1.84002C2.30002 10.5543 2.82573 10.4886 2.95716 10.4229C3.35145 10.2914 3.61431 9.96287 3.74573 9.50287C3.81145 9.30572 4.00859 8.91144 4.14002 8.58287C4.33716 8.12287 4.40288 7.92572 4.40288 7.66287C4.40288 7.26858 4.33716 7.07144 3.61431 6.21715C3.15431 5.62572 3.08859 5.4943 3.08859 5.42858C3.08859 5.29715 3.48288 4.90287 3.94288 4.44287C4.60002 3.78572 4.79716 3.58858 4.92859 3.58858C5.06002 3.58858 5.25716 3.72001 5.65145 4.04858C6.50573 4.77144 6.63716 4.83715 7.09716 4.83715C7.36002 4.83715 7.55716 4.77144 7.82002 4.70572C7.95145 4.64001 8.41145 4.44287 8.74002 4.31144C9.72573 3.98287 9.85716 3.78572 9.98859 2.47144C10.0543 1.94572 10.12 1.55144 10.12 1.48572C10.1857 1.3543 10.5143 1.28858 11.4343 1.28858C12.1572 1.28858 12.4857 1.28858 12.6172 1.3543C12.7486 1.42001 12.8143 1.48572 12.8143 1.48572C12.8143 1.55144 12.88 1.88001 12.9457 2.47144C13.0114 2.93144 13.0772 3.45715 13.1429 3.58858C13.2743 3.91715 13.6686 4.18001 14.0629 4.31144C14.26 4.37715 14.6543 4.50858 14.9172 4.64001C15.3114 4.83715 15.5086 4.90287 15.7714 4.90287C16.2314 4.90287 16.2972 4.83715 17.1514 4.18001C17.7429 3.78572 17.94 3.6543 18.0714 3.6543C18.2029 3.6543 18.4 3.78572 19.0572 4.50858C19.4514 4.90287 19.9114 5.36287 19.9114 5.4943C19.9114 5.56001 19.8457 5.69144 19.4514 6.21715C18.5972 7.26858 18.4657 7.53144 18.7943 8.18858C18.86 8.38572 19.0572 8.84573 19.2543 9.24001C19.7143 10.3572 19.8457 10.4229 21.2257 10.5543C22.0143 10.62 22.1457 10.6857 22.2114 10.7514C22.2772 10.8172 22.2772 11.0143 22.2772 12V12.2629C22.2772 13.38 22.2772 13.38 21.3572 13.4457H21.16C19.9772 13.5772 19.7143 13.7743 19.3857 14.6943C19.1886 15.0229 18.9914 15.4172 18.86 15.68Z" fill="#4A9DFF"/>
                                <path d="M14.7857 7.40002C13.6028 6.61145 12.3543 6.28287 10.9743 6.4143C8.60856 6.54573 6.57142 8.32002 5.91428 10.6857C5.71713 11.4743 5.71713 12.7229 5.91428 13.5114C6.5057 15.7457 8.21428 17.3229 10.4486 17.7172C10.7114 17.7829 11.04 17.7829 11.3686 17.7829C11.8286 17.7829 12.2886 17.7172 12.6171 17.6514C14.3257 17.3229 15.8371 16.14 16.56 14.5629C17.02 13.7086 17.1514 13.1172 17.1514 12.0657C17.1514 11.3429 17.1514 11.08 17.02 10.6857C16.6914 9.30573 15.8371 8.12287 14.7857 7.40002ZM16.1657 13.2486C15.64 15.22 13.9971 16.6 12.0257 16.7972C10.5143 16.9286 9.13428 16.4686 8.08285 15.4172C7.03142 14.3657 6.5057 12.92 6.76856 11.4086C6.9657 9.89716 7.81999 8.64859 9.19999 7.86002C9.92285 7.46573 10.7114 7.26859 11.5657 7.26859C11.96 7.26859 12.3543 7.3343 12.7486 7.40002C14.3914 7.7943 15.7057 9.10859 16.1657 10.7514C16.2971 11.4086 16.2971 12.6572 16.1657 13.2486Z" fill="#4A9DFF"/>
                            </svg>
                        </button>

                        <a-menu
                            slot="overlay"
                            class="dropdown-config-menu"

                        >
                            <a-menu-item
                                style="display: flex; flex-direction: column;"
                            >
                            <a-checkbox-group v-model="selectedColumns" style="display: flex; flex-direction: column;" >
                                <a-checkbox v-for="column in columns_orders_unloading" :key="column.key" :value="column.key">
                                {{ column.title }}
                                </a-checkbox>
                            </a-checkbox-group>
                            </a-menu-item>
                        </a-menu>
                    </a-dropdown>
                </div>
                <a-modal
                    title="Редактировать"
                    :visible="visibleAllEditModal"
                    @cancel="handleAllEditModal"
                    >
                    <template slot="footer">
                        <a-button key="distribute_orders" type="primary" @click="allEditOrders()">
                            Изменить
                        </a-button>
                    </template>
                    <h6>При внесении изменений отредактируются все выбранные заказы</h6>
                    <div class="distribute_modal_row">
                        <span class="distribute_modal_title">Участок</span>
                        <a-select v-model="allEditArea">
                            <a-select-option v-for="engraver in this.engravers" :key="engraver">
                                {{ engraver }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <div class="distribute_modal_row">
                        <span class="distribute_modal_title">Дата</span>
                        <date-picker
                            type="date"
                            value-type="format"
                            format="YYYY-MM-DD"
                            v-model="allEditDate"
                        ></date-picker>
                    </div>
                </a-modal>
                <a-modal
                    title="Распределить заказы"
                    :visible="visibledistributeOrdersModal"
                    @cancel="handledistributeOrdersModal"
                    >
                    <template slot="footer">
                        <a-button key="distribute_orders" type="primary" @click="distributeOrders()">
                            Распределить
                        </a-button>
                    </template>
                    <div class="distribute_modal_row">
                        <span class="distribute_modal_title">Участок</span>
                        <a-select v-model="distributeArea">
                            <a-select-option v-for="engraver in this.engravers" :key="engraver">
                                {{ engraver }}
                            </a-select-option>
                        </a-select>
                    </div>
                    <div class="distribute_modal_row">
                        <span class="distribute_modal_title">Дата</span>
                        <date-picker
                            type="date"
                            value-type="format"
                            format="YYYY-MM-DD"
                            v-model="distributeDate"
                        ></date-picker>
                    </div>
                </a-modal>
            </div>
            <div class="table_wrapper">
                <a-table 
                    ref="table"
                    :dataSource="filteredData" 
                    :columns="visibleColumns"
                    :row-selection="{ selectedRowKeys: selectedRowKeys, onChange: onSelectChange }"
                    :row-key="record => record.id"
                    :custom-row="customRow"
                    bordered
                > 
                <template slot="engraver" slot-scope="text, record, index" >
                    <span v-if="!record.selectHover">
                        <span style="display: flex; align-items: center; min-width: 100px; height: 30px;vcursor: pointer;" @dblclick="handleDoubleClickSelect(record)">
                            {{ text }}
                        </span>
                    </span>
                    <span v-else>
                        <a-select style="width: 100%;" v-model="record.engraver" @change="saveMovementData(record)">
                            <a-select-option v-for="engraver in engravers" :key="engraver">
                                {{ engraver }}
                            </a-select-option>
                        </a-select>  
                    </span>
                </template>
                <template  slot="okvid_number" slot-scope="text, record">                
                        <span>{{String(text).slice(0,String(text).length-2)+'-'+String(text).slice(-2)}}</span>
                </template>  
                <template slot="completion_date" slot-scope="text, record, index">
                    <span v-if="!record.editing">
                        <span style="display: flex; align-items: center; min-width: 100px; height: 30px;cursor: pointer;" @dblclick="handleDoubleClickInput(record)">
                            {{ formatDate(text) }}
                        </span>
                    </span>
                    <span v-else>
                        <date-picker
                            type="date"
                            value-type="format"
                            format="YYYY-MM-DD"
                            v-model="record.completion_date"
                            @change="saveMovementData(record)"
                            ref="datePicker"
                        ></date-picker>
                    </span>
                </template>
                </a-table>
            </div>
        </div>
    </div>  
</template>



<script>
import DatePicker from 'vue2-datepicker';
import moment from 'moment';

    export default {
        components: {
            DatePicker
        },
        props: {
            unloading_orders: Array,
            engravers: Array,
            urlsavemovementdata: String, 
            urldistributeorders: String, 
            conditions: Array,  
            movement_statuses: Array, 
            movement_novizna: Array,
            customers: Array,             
        },  
        data() {
            return {
                selectUnloadingOrders: [...this.unloading_orders],
                buttonsArea: ["Данафлекс", "Данафлекс-НАНО", "Данафлекс-Алабуга"],
                activeButtonsArea: [],
                selectedColumns: [],
                selectedRowKeys: [],
                columns_orders_unloading: [
                    {
                        title: "Участок гравировки",
                        dataIndex: "engraver",
                        key: "engraver",
                        scopedSlots: { 
                            customRender: 'engraver', 
                        },
                    },
                    {
                        title: "Дата готовности",
                        dataIndex: "completion_date",
                        key: "completion_date",
                        scopedSlots: { 
                            customRender: 'completion_date', 
                        },
                    },
                    {
                    title: "Дата печати/ламинации",
                    dataIndex: "printing_date",
                    key: "printing_date",
                    customRender: this.formatDate,
                    },
                    {
                    title: "№ Оквид",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    scopedSlots: { 
                            customRender: 'okvid_number', 
                        },
                    },
                    {
                    title: "№ Заказа",
                    dataIndex: "order_number",
                    key: "order_number",
                    },
                    {
                    title: "Клиент",
                    dataIndex: "customer",
                    key: "customer",
                    width: '7%',
                    ellipsis: true,
                    },
                    {
                    title: "Описание",
                    dataIndex: "description",
                    key: "description",
                    width: '10%',
                    ellipsis: true,
                    },
                    {
                    title: "Кол-во валов",
                    dataIndex: "shaft_quantity",
                    key: "shaft_quantity",
                    width: '4%',
                    },
                    {
                    title: "Номер заявки на отгрузку",
                    dataIndex: "transfer_document",
                    key: "transfer_document",
                    },
                    {
                    title: "Новизна",
                    dataIndex: "novizna",
                    key: "novizna",
                    width: '8%',
                    },
                    {
                    title: "Гравировка из профиля",
                    dataIndex: "profil_engraving",
                    key: "profil_engraving",
                    },
                    {
                    title: "Подготовка репро",
                    dataIndex: "repro",
                    key: "repro",
                    },
                    {
                    title: "Номер пред. заказа",
                    dataIndex: "previous_order_number",
                    key: "previous_order_number",
                    },
                    {
                    title: "Пред. гравировщик",
                    dataIndex: "previous_engraver",
                    key: "previous_engraver",
                    },
                    {
                    title: "Дата печати пред. заказа",
                    dataIndex: "previous_order_printing_date",
                    key: "previous_order_printing_date",
                    customRender: this.formatDate,
                    },
                ],
                visibleAllEditModal: false,
                selectedRows: [],
                showAllEditBtn: false,                
                allEditArea: "",
                allEditDate: "",
                pageSize: 10,
                visible: false,
                visibleFilters: false,
                filterDate: [null,null],
                filterNovizna: undefined,
                filterStatus: undefined,
                searchText: '',
                visibledistributeOrdersModal: false,
                distributeDate: "",
                distributeArea: "",
                filterEngraver: "",
                filterCondition: undefined,
                filterCustomer: undefined,
            }
        },
        created() {
            // Устанавливаем начальное состояние выбранных столбцов
            this.selectedColumns = this.columns_orders_unloading.map(column => column.key);
        },
        computed: {
            visibleColumns() {
            // Фильтруем столбцы, чтобы отобразить только выбранные
                return this.columns_orders_unloading.filter(column => this.selectedColumns.includes(column.key));
            },
            itemKey() {
                return (record) => record.key;
            },

            filteredData() {
                console.log('ok');
                if (!this.searchText) {
                    const enhancedData = [];
                    this.selectUnloadingOrders.forEach((record, index) => {
                        if ((this.filterDate[0] === null || record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1]) && (this.filterCustomer === undefined || record.customer === this.filterCustomer) && (this.filterStatus === undefined || record.status === this.filterStatus) && (this.filterNovizna === undefined || record.novizna === this.filterNovizna) && (this.filterCondition === undefined || record.technical_condition === this.filterCondition) || record.isGroupHeader || record.status === 'Простой') {
                            if (this.activeButtonsArea.length === 0) {
                                enhancedData.push(record);
                            } else {
                                if (record.order_number && record.order_number.startsWith('N') && this.activeButtonsArea.includes('Данафлекс-НАНО')) {
                                    enhancedData.push(record);
                                } else if (record.order_number && record.order_number.startsWith('A') && this.activeButtonsArea.includes('Данафлекс-Алабуга')) {
                                    enhancedData.push(record);
                                } else if (record.order_number && /^[0-9]/.test(record.order_number) && this.activeButtonsArea.includes('Данафлекс')) {
                                    enhancedData.push(record);
                                }
                            }
                        } 
                    });

                    return enhancedData;
                } else {
                    const enhancedData = [];
                    const searchTextLower = this.searchText.toLowerCase();
                    const filteredOrders = this.selectUnloadingOrders.filter(item =>
                        Object.values(item).some(val =>
                            val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower)
                        ) &&
                        (this.filterDate[0] === null || item.completion_date >= this.filterDate[0] && item.completion_date <= this.filterDate[1]) && (this.filterCustomer === undefined || item.customer === this.filterCustomer) && (this.filterStatus === undefined || item.status === this.filterStatus) && (this.filterNovizna === undefined || item.novizna === this.filterNovizna) && (this.filterCondition === undefined || item.technical_condition === this.filterCondition)
                    );

                    filteredOrders.forEach((record, index) => {
                        
                        if ((this.filterDate[0] === null || record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1]) && (this.filterCustomer === undefined || record.customer === this.filterCustomer) && (this.filterStatus === undefined || record.status === this.filterStatus) && (this.filterNovizna === undefined || record.novizna === this.filterNovizna) && (this.filterCondition === undefined || record.technical_condition === this.filterCondition) || record.isGroupHeader || record.status === 'Простой') {
                            if (this.activeButtonsArea.length === 0) {
                                enhancedData.push(record);
                            } else {
                                if (record.order_number && record.order_number && record.order_number.startsWith('N') && this.activeButtonsArea.includes('Данафлекс-НАНО')) {
                                    enhancedData.push(record);
                                } else if (record.order_number && record.order_number && record.order_number.startsWith('A') && this.activeButtonsArea.includes('Данафлекс-Алабуга')) {
                                    enhancedData.push(record);
                                } else if (record.order_number && record.order_number && /^[0-9]/.test(record.order_number) && this.activeButtonsArea.includes('Данафлекс')) {
                                    enhancedData.push(record);
                                }
                            }
                        }
                    });

                    return enhancedData;
                }
            },
        },
        methods: {
            customRow(record) {
                const ThreeDayAgo = new Date();
                ThreeDayAgo.setDate(ThreeDayAgo.getDate() - 3);

                if ((record.completion_date == null) || (record.completion_date > ThreeDayAgo)) {
                    return { style: { background: '#FFEBEB' } };
                }

                return {};
            },
            onSelectChange(selectedRowKeys, selectedRows) {
                this.selectedRowKeys = selectedRowKeys;
                this.selectedRows = selectedRows;

                if (selectedRowKeys.length > 0) {
                    this.showAllEditBtn = true;
                } else {
                    this.showAllEditBtn = false;
                }
            },
            clearSelection() {
                this.selectedRowKeys = [];
                this.selectedRows = [];
                    
                this.onSelectChange([]);
            },
            formatDate(text) {
                if ((text != null) && (text != '0001-01-01')) {
                    return new Date(text).toLocaleDateString('ru-RU', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }
            },
            handleAllEditModal() {
                this.visibleAllEditModal = false;
            },

            showAllEditModal() {
                this.visibleAllEditModal = true;
            },

            handleDoubleClickSelect(record) {
                this.filteredData.forEach(item => (item.selectHover = false));
                record.selectHover = true;
            },
            handleDoubleClickInput(record) {
                this.filteredData.forEach(item => (item.editing = false));
                record.editing = true;
                document.addEventListener('click', (event) => this.handleDocumentClick(event, record));
            },
            handleDocumentClick(event, record) {
                /*
                const datePickerElement = this.$refs.datePicker.$el;
                if (!datePickerElement.contains(event.target)) {
                    this.stopEditing(record);
                }
                */
            },
            stopEditing(record) {
                record.editing = false;
            },
            showFilters() {
                if (this.visibleFilters == true) {
                    this.visibleFilters = false;
                } else {
                    this.visibleFilters = true;
                }
            },

            distributeOrdersModal() {
                this.visibledistributeOrdersModal = true;
            },

            handledistributeOrdersModal() {
                this.visibledistributeOrdersModal = false;
            },

            handleFilters() {
                this.visibleFilters = false;
            },

            saveMovementData(record) {
                const currentDate = new Date();
                const CompletionDate = new Date(record.completion_date);

                if (CompletionDate != currentDate && CompletionDate < currentDate && record.completion_date != null) {
                    this.$confirm({
                        title: 'Изменить дату?',
                        content: 'Дата меньше текущей даты. Хотите изменить дату?',
                        okText: 'Да',
                        cancelText: 'Нет',
                        onOk: () => {
                            axios.post(this.urlsavemovementdata,record)
                            .then(response => {
                                this.selectUnloadingOrders = response.data;
                                record.selectHover = false;
                                record.editing = false;
                            })
                            .catch(error => {
                                alert("Отсутствует соединение");
                            });
                        },
                        onCancel() {
                        
                        },
                    });
                } else if (record.profil_engraving == 'Яношка-Павловск' && record.engraver != record.profil_engraving){
                    this.$confirm({
                        title: 'Изменить гравировщика?',
                        content: 'Выбранный гравировщик отличается от гравировщика из профиля. Изменить гравировщика',
                        okText: 'Да',
                        cancelText: 'Нет',
                        onOk: () => {
                            axios.post(this.urlsavemovementdata,record)
                            .then(response => {
                                this.selectUnloadingOrders = response.data;
                                record.selectHover = false;
                                record.editing = false;
                            })
                            .catch(error => {
                                alert("Отсутствует соединение");
                            });
                        },
                        onCancel() {
                        
                        },
                    });
                }else {
                    axios.post(this.urlsavemovementdata,record)
                    .then(response => {
                        this.selectUnloadingOrders = response.data;
                        record.selectHover = false;
                        record.editing = false;
                    })
                    .catch(error => {
                        alert("Отсутствует соединение");
                    });
                }
            },

            allEditOrders() {
                this.selectedRows.forEach((record, index) => {

                    if (this.allEditDate != "") {
                        record.completion_date = this.allEditDate;
                    }
                    if (this.allEditArea != "") {
                        record.engraver = this.allEditArea;
                    }

                    this.saveMovementData(record);
                    this.showAllEditBtn = false;
                    this.clearSelection();
                })
                this.allEditDate = "";
                this.allEditArea = "";
                this.visibleAllEditModal = false;
            },

            distributeOrders() {
                axios.post(this.urldistributeorders,{distributeDate: this.distributeDate,distributeArea: this.distributeArea})
                .then(response => {
                    this.selectUnloadingOrders = response.data;
                    this.handledistributeOrdersModal();
                    this.clearSelection();
                    this.$success({
                        title: 'Заказы распределены',
                    });
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            changeFilters() {

            },

            toggleButtonArea(button) {

                if (this.activeButtonsArea.includes(button)) {
                    this.activeButtonsArea = this.activeButtonsArea.filter(b => b !== button);
                } else {
                    this.activeButtonsArea.push(button);
                }
            },

            
            clearFilters() {
                this.filterCondition = undefined;
                this.filterStatus = undefined;
                this.filterNovizna = undefined;
                this.filterCustomer = undefined;
                this.filterDate[0] = null;
                this.filterDate[1] = null;
                this.activeButtonsArea = [];
                this.$forceUpdate();
            },

            handleDivClick(event) {
                event.stopPropagation();
            },
        }   
    }   
</script>

<style scoped>
.ant-checkbox-wrapper + .ant-checkbox-wrapper {
    margin-left: 0 !important;
}
.ant-table-body>table {
    table-layout: fixed;
    font-size: 14px;
}
.distribute_modal_row {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.distribute_modal_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 20px;
}

.filters_customer_block {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.filters_customer_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 15px;
}

.filters_condition_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 15px;
}

.filters_condition_block {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.setting_btn {
    border: none;
    border-radius: 10px;
    background: var(--grayscale-0, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    display: flex;
    padding: 8px;
    align-items: flex-start;
    gap: 10px;
}
.distribute_btn {
    display: flex;
    padding: 6px 12px;
    justify-content: center;
    align-items: center;
    gap: 4px;
    border-radius: 8px;
    border: none;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-right: 20px;
}

.all_edit_btn {
    display: flex;
    padding: 6px 12px;
    justify-content: center;
    align-items: center;
    gap: 4px;
    border-radius: 8px;
    border: none;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-right: 20px;
}

.distribute_disable_btn {
    display: flex;
    padding: 6px 12px;
    justify-content: center;
    align-items: center;
    gap: 4px;
    border-radius: 8px;
    border: none;
    background: var(--grayscale-500, #BFBFBF);
    color: var(--grayscale-0, #FFF);
    margin-right: 20px;
}

.filters_wrapper {
    display: inline-flex;
    padding: 15px;
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    position: absolute;
    z-index: 10;
}

.filters_area_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 15px;
}

.filters_area_value_block {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 400px;
}

.filters_novizna_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 15px;
}

.filters_novizna_block {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.filters_status_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-bottom: 15px;
}

.filters_status_block {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.filters_btn_clear_block {
    display: flex;
    justify-content: flex-end;
    width: 100%;
}

.filters_area_value {
    display: flex;
    padding: 8px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 10px;
    background: var(--primary-100, #E5EEFF);
}

.row_btn_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    margin-left: 8px;
}

.row_btn {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    border: 1px solid var(--txt, #363F51);
    background: none;
    margin-left: 20px;
}

.filters {
    display: flex;
    align-items: center;
}

.filters_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.table_wrapper {
  position: relative;
  display: flex;
  align-items: stretch;
  width: 100%;
  overflow-x: auto;
  min-width: 1800px;
}

.ant-table-body > table {
    overflow-x: auto;
}
.a-table {
  flex: 1;
  transition: margin-left 0.3s;
}

.block_info_status {
    border-bottom: 1px solid #E8E8E8;
}

.block_info_okvid {
    margin-top: 20px;
}

.block_info_order {
    border-bottom: 1px solid #E8E8E8;
}

.table_wrapper.showModalInfo .a-table {
  margin-left: -50%;
  transition-delay: 0.3s; 
}

.table_wrapper.showModalInfo .modal_info_wrapper {
  right: 50%; 
  transition-delay: 0s; 
}

.nav_bar_row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.nav_bar_left_block {
    display: flex;
    align-items: center;
}

.nav_bar_right_block {
    display: flex;
    align-items: center;
}

.wrapper_title {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 24px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.wrapper_body {
    display: flex;
    padding: 20px;
    flex-direction: column;
    gap: 20px;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    margin-top: 20px;
    position: relative;
}

</style>