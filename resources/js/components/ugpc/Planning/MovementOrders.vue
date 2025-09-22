<template>
    <div class="wrapper">
        <span class="wrapper_title">Движение заказов</span>
        <div class="wrapper_body">
            <div class="nav_bar_row">
                <div class="nav_bar_left_block">
                    <a-select class="engraver_select" v-model="selectEngraver">
                        <a-select-option v-for="engraver in this.engravers" :key="engraver">
                            {{ engraver }}
                        </a-select-option>
                    </a-select>
                    <div class="filters" ref="filters_btn">
                        <a-dropdown :trigger="['click']"> 
                            <div class="filter_button" :class="{ 'active-filters': selectedFilterCount > 0 }">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                    <mask id="mask0_1709_9721" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="22">
                                    <rect width="22" height="22" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_1709_9721)">
                                    <path d="M10.4359 17.875C10.2056 17.875 10.0129 17.7974 9.85772 17.6423C9.70259 17.4872 9.62503 17.2944 9.62503 17.0641V11.758L4.49348 5.23912C4.3172 5.00407 4.29164 4.75962 4.4168 4.50578C4.54196 4.25194 4.75319 4.12502 5.05051 4.12502H16.9495C17.2468 4.12502 17.458 4.25194 17.5832 4.50578C17.7084 4.75962 17.6828 5.00407 17.5065 5.23912L12.375 11.758V17.0641C12.375 17.2944 12.2974 17.4872 12.1423 17.6423C11.9872 17.7974 11.7944 17.875 11.5641 17.875H10.4359ZM11 11.275L15.5375 5.5H6.4625L11 11.275Z" fill="#363F51"/>
                                    </g>
                                </svg>
                                <span  style="cursor: pointer;"  class="filters_title">Фильтр</span>  
                                <span style="margin-left: 8px;" v-if="selectedFilterCount>0">{{ selectedFilterCount }}</span> 
                                <svg @click="clearFilters()" style="margin-left: 8px; cursor: pointer" v-if="selectedFilterCount>0" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                    <mask id="mask0_2992_6177" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="22" height="22">
                                    <rect width="22" height="22" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_2992_6177)">
                                    <path d="M5.86689 17.0993L4.90088 16.1333L10.0342 11L4.90088 5.86667L5.86689 4.90066L11.0002 10.034L16.1336 4.90066L17.0996 5.86667L11.9662 11L17.0996 16.1333L16.1336 17.0993L11.0002 11.966L5.86689 17.0993Z" fill="#363F51"/>
                                    </g>
                                </svg>
                            </div>
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
                                        @change="changeFilters()"
                                    >
                                        {{ button }}
                                    </button>
                                </div>
                            </div>
                            <div class="filters_novizna_block">
                                <span class="filters_novizna_title">Новизна</span>
                                <a-select class="filters_novizna_select" v-model="filterNovizna" allowClear :value="filterNovizna" @change="changeFilters()">
                                    <a-select-option v-for="novizna in this.movement_novizna" :key="novizna">
                                        {{ novizna }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_condition_block">
                                <span class="filters_condition_title">Тех. Состояние</span>
                                <a-select class="filters_condition_select" v-model="filterCondition" mode="multiple" allowClear @change="changeFilters()">
                                    <a-select-option v-for="condition in this.conditions" :key="condition">
                                        {{ condition }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_status_block">
                                <span class="filters_status_title">Статус</span>
                                <a-select class="filters_status_select"  v-model="filterStatus" allowClear @change="changeFilters()">
                                    <a-select-option v-for="status in this.movement_statuses" :key="status">
                                        {{ status }}
                                    </a-select-option>
                                </a-select>
                            </div>
                            <div class="filters_customer_block">
                                <span class="filters_customer_title">Клиент</span>
                                <a-select class="filters_status_select" 
                                    show-search  
                                    v-model="filterCustomer" 
                                    allowClear 
                                    option-filter-prop="children"
                                    :filter-option="filterOption"
                                    @change="changeFilters()">
                                    <a-select-option v-for="customer in this.customers" :key="customer">
                                        {{ customer }}
                                    </a-select-option>
                                </a-select>                           
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
                    <a-input-search 
                        placeholder="Поиск по № заказа или клиенту" 
                        style="width: 300px; height: 100%; margin-right: 20px;" 
                        :allow-clear="true"
                        v-model="searchText"
                    />
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
                    <a-dropdown :placement="'bottomRight'">
                        <button class="other_btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <mask id="mask0_1631_4546" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                <rect width="24" height="24" fill="#D9D9D9"/>
                                </mask>
                                <g mask="url(#mask0_1631_4546)">
                                <path d="M12 19.2692C11.5875 19.2692 11.2344 19.1223 10.9406 18.8286C10.6469 18.5348 10.5 18.1817 10.5 17.7692C10.5 17.3567 10.6469 17.0036 10.9406 16.7099C11.2344 16.4161 11.5875 16.2693 12 16.2693C12.4125 16.2693 12.7656 16.4161 13.0593 16.7099C13.3531 17.0036 13.5 17.3567 13.5 17.7692C13.5 18.1817 13.3531 18.5348 13.0593 18.8286C12.7656 19.1223 12.4125 19.2692 12 19.2692ZM12 13.5C11.5875 13.5 11.2344 13.3531 10.9406 13.0594C10.6469 12.7656 10.5 12.4125 10.5 12C10.5 11.5875 10.6469 11.2344 10.9406 10.9406C11.2344 10.6469 11.5875 10.5 12 10.5C12.4125 10.5 12.7656 10.6469 13.0593 10.9406C13.3531 11.2344 13.5 11.5875 13.5 12C13.5 12.4125 13.3531 12.7656 13.0593 13.0594C12.7656 13.3531 12.4125 13.5 12 13.5ZM12 7.73075C11.5875 7.73075 11.2344 7.58388 10.9406 7.29013C10.6469 6.99639 10.5 6.64328 10.5 6.23078C10.5 5.81829 10.6469 5.46518 10.9406 5.17143C11.2344 4.87769 11.5875 4.73083 12 4.73083C12.4125 4.73083 12.7656 4.87769 13.0593 5.17143C13.3531 5.46518 13.5 5.81829 13.5 6.23078C13.5 6.64328 13.3531 6.99639 13.0593 7.29013C12.7656 7.58388 12.4125 7.73075 12 7.73075Z" fill="#363F51"/>
                                </g>
                            </svg>
                        </button>
                        <a-menu slot="overlay">
                            <a-menu-item>
                                <a href="javascript:;" @click="showDowntimeModal">Добавить простой</a>
                            </a-menu-item>
                            <a-menu-item>
                                <a href="javascript:;" @click="showDistributionModal">Распределить по выработке</a>
                            </a-menu-item>
                            <a-menu-item>
                                <a href="javascript:;" @click="showPostModal">Подтвердить график на УГПЦ</a>
                            </a-menu-item>
                        </a-menu>
                    </a-dropdown>
                    <a-modal
                        title="Распределить"
                        :visible="visibleDistributionModal"
                        @cancel="handleDistributionModal"
                        >
                        <template slot="footer">
                            <a-button key="distribute_orders" type="primary" @click="dailyDistribution()">
                                Распределить
                            </a-button>
                        </template>
                        <div class="row">
                            <div class="col-lg-6">
                                <span class="distribute_modal_title">Период с:</span>
                                <date-picker
                                    style="margin-bottom: 20px;"
                                    type="date"
                                    value-type="format"
                                    format="YYYY-MM-DD"
                                    v-model="DistributeStartDate"
                                ></date-picker>
                            </div>
                            <div class="col-lg-6">
                            <span class="distribute_modal_title">Период до:</span>
                                <date-picker
                                    type="date"
                                    value-type="format"
                                    format="YYYY-MM-DD"
                                    v-model="DistributeEndDate"
                                ></date-picker>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="distribute_modal_title">Кол-во валов:</span>
                                <a-input style="margin-bottom: 20px;" v-model="QtyDistributeShaft"/>
                            </div>
                        </div>
                    </a-modal>
                    <a-modal
                        title="Добавить простой"
                        :visible="visibleDowntimeModal"
                        @cancel="handleDowntimeModal"
                        >
                        <template slot="footer">
                            <a-button key="distribute_orders" type="primary" @click="addDowntime()">
                                Добавить
                            </a-button>
                        </template>
                        <div class="distribute_modal_row">
                            <span class="distribute_modal_title">Вид простоя:</span>
                            <a-select v-model="selectDownTime" style="width: 100%;">
                                <a-select-option v-for="downtime in this.downtimes" :key="downtime">
                                    {{ downtime }}
                                </a-select-option>
                            </a-select>
                        </div>
                        <div class="distribute_modal_row">
                            <div class="row">
                                <div class="col-lg-6">
                                    <span class="distribute_modal_title">Дата</span>
                                    <date-picker
                                        type="date"
                                        value-type="format"
                                        format="YYYY-MM-DD"
                                        v-model="selectDownTimeDate"
                                    ></date-picker>
                                </div>
                                <div class="col-lg-6">
                                    <span class="distribute_modal_title">Время</span>
                                    <a-input v-model="selectDownTimeQty"/>
                                </div>
                            </div>
                        </div>
                    </a-modal>
                    <a-modal
                        title="Подтвердить график УГПЦ"
                        :visible="visiblePostModal"
                        @cancel="handlePostModal"
                        >
                        <template slot="footer">
                            <a-button key="distribute_orders" type="primary" @click="postMovementOrders()">
                                Создать график
                            </a-button>
                        </template>
                        <div class="distribute_modal_row">
                            <span class="distribute_modal_title">Дата</span>
                            <date-picker
                                type="date"
                                value-type="format"
                                format="DD.MM.YYYY"
                                v-model="selectPostDate"
                            ></date-picker>
                        </div>
                    </a-modal>
                </div>
            </div>
            <div class="statistics_blocks">
                <div class="statistic_block">
                    <span class="statistic_title">План валов:</span>
                    <span class="statistic_value">{{this.countExecutionShaftsPlan[0].plan_qty}}</span>
                </div>
                <div class="statistic_block">
                    <span class="statistic_title">Запланировано валов:</span><span class="statistic_value">{{this.countShaftsPlan}}</span>
                </div>
                <div class="statistic_block">
                    <span class="statistic_title">Запланировано заказов:</span><span class="statistic_value">{{this.countOrders}}</span>
                </div>
                <div class="statistic_block">
                    <span class="statistic_title">Выпущено валов:</span><span v-if="Math.round(this.countShaftsFact/(new Date(new Date().getFullYear(), new Date().getMonth()+1, 0).getDate())*(new Date().getDate()))<this.countExecutionShaftsPlan[0].plan_qty" class="statistic_value not_executed">{{this.countShaftsFact}}</span><span v-if="Math.round(this.countShaftsFact/(new Date(new Date().getFullYear(), new Date().getMonth()+1, 0).getDate())*(new Date().getDate()))>this.countExecutionShaftsPlan[0].plan_qty" class="statistic_value overfulfilled">{{this.countShaftsFact}}</span><span v-if="Math.round(this.countShaftsFact/(new Date(new Date().getFullYear(), new Date().getMonth()+1, 0).getDate())*(new Date().getDate()))==this.countExecutionShaftsPlan[0].plan_qty" class="statistic_value">{{this.countShaftsFact}}</span>
                </div>
                <div class="statistic_block">
                    <span class="statistic_title">Выпущено заказов:</span><span class="statistic_value">{{this.countFactOrders}}</span>
                </div>
                <div class="statistic_block">
                    <span class="statistic_title">Выполнение плана:</span><span class="statistic_value">{{100-(Math.round((this.countExecutionShaftsPlan[0].plan_qty-this.countShaftsFact)*100/this.countExecutionShaftsPlan[0].plan_qty))}}%</span>
                </div>
            </div>
            <div class="table_wrapper">
                <DragDropContextProvider :backend="html5Backend" style="width: 100%">
                <a-table 
                    :dataSource="filteredData" 
                    :columns="columns_orders_movement" 
                    :components="components"
                    :rowClassName="rowClassName"
                    :custom-row="customRow"
                    @change="handleTableChange"
                    :scroll="{ x: '1800px', y: '800px' }"
                    size="middle"
                    :pagination="false"
                    style="font-size: 12px;"
                    bordered
                >   
                <template  slot="technical_condition" slot-scope="text, record">                
                        <span style="color:red; cursor: pointer;" @click="cancelChanges(record)" v-if="record.post && record.isGroupHeader" >Отменить изменения</span>
                        <span style="color:red; cursor: pointer;" @click="deleteDownTime(record)" v-if="record.status === 'Простой'" >Удалить</span>
                        <span v-if="!record.isGroupHeader" style="user-select: all;">{{text}}</span>
                </template>  
                <template  slot="okvid_number" slot-scope="text, record">              
                        <span style="color: var(--primary-300-activ, #4A9DFF); user-select: all;" @click="showModalInfo(record)" v-if="!record.isGroupHeader && text != null && record.status != 'Простой'">{{String(text).slice(0,String(text).length-2)+'-'+String(text).slice(-2)}}</span>
                </template>  
                <template  slot="order_number" slot-scope="text, record">              
                        <span style="user-select: all;">{{text}}</span>
                </template>  
                <template  slot="customer" slot-scope="text, record">              
                        <span style="user-select: all;">{{text}}</span>
                </template>  
                <template  slot="description" slot-scope="text, record">              
                        <span style="user-select: all;">{{text}}</span>
                </template>  
                <template  slot="removed" slot-scope="text, record">
                    <div class="" style="text-align: center; width: 100%;" v-if="!record.post && !record.isGroupHeader && record.status != 'Простой'">
                         <input
                            style="width: 15px; height: 15px;"
                            v-model="record.removed"
                            type="checkbox"
                            @change="saveMovementDataRow(record)"
                            />
                    </div>     
                </template> 
                <template  slot="shafts_in_warehouse" slot-scope="text, record">
                    <div v-if="!record.isGroupHeader && record.status != 'Простой' && !record.shafts_in_warehouse"  class="status_box" :style="{ display: 'flex', justifyContent: 'center' }">
                        <span style="background-color: #f70f02; width:20px; height: 20px; display:flex; border-radius: 3px;"></span>
                    </div>
                    <div v-if="!record.isGroupHeader && record.status != 'Простой' && record.shafts_in_warehouse"  class="status_box" :style="{ display: 'flex', justifyContent: 'center' }">
                        <span style="background-color: #1ced09; width:20px; height: 20px; display:flex; border-radius: 3px;"></span>
                    </div>
                </template>
                <template  slot="variable_body" slot-scope="text, record">
                    <div class="status_box" :style="{ display: 'flex', justifyContent: 'center' }">
                        <span v-if="record.isGroupHeader" style="color: black;">{{ countShaftsFactDay(record.completion_date)}} валов</span>
                    </div>
                    <a-popover v-if="!record.isGroupHeader" placement="topLeft" arrow-point-at-center>
                        <template #content>
                            <h6>{{record.status}}</h6>
                        </template>
                        <div class="status_box" :style="{ display: 'flex', justifyContent: 'center' }">
                            <span :style="{ backgroundColor: text, width: '20px', height: '20px', display: 'flex', borderRadius: '3px' }"></span>
                        </div>
                    </a-popover>
                </template>
                <template  slot="shaft_quantity_fact" slot-scope="text, record">
                    <span v-if="!record.isGroupHeader && record.status != 'Простой'">
                        <a-input :class="{ 'not_finished': record.shaft_quantity_fact<record.shaft_quantity && record.shaft_quantity_fact != null }" v-model="record.shaft_quantity_fact" @change="saveMovementDataRow(record)"/>
                    </span>
                </template>
                <template  slot="comment" slot-scope="text, record">
                    <a-popover placement="topLeft" arrow-point-at-center>
                    <template #content>
                        <h6>{{record.comment}}</h6>
                    </template>
                    <span v-if="!record.isGroupHeader && record.status != 'Простой'">
                        <a-input v-model="record.comment" @change="saveMovementDataRow(record)"/>
                    </span>
                    </a-popover>
                </template>
                <template slot="completion_date" slot-scope="text, record, index">
                    <span v-if="!record.editing" @dblclick="handleDoubleClickInput(record)" @click="showModalInfo(record)" style="min-width: 50px;min-height: 50px; display: flex; align-items: center;"> 
                        <span :class="{ 'group_row': record.isGroupHeader }" style="color: black;">{{ formatDate(text)}}</span>
                    </span>
                    <span v-else>
                        <date-picker
                            type="date"
                            value-type="format"
                            format="YYYY-MM-DD"
                            v-model="record.completion_date"
                            @change="saveMovementDataRow(record)"
                        ></date-picker>
                    </span>
                </template>
                </a-table>
                </DragDropContextProvider>
            </div>
                <template v-if="visibleModalInfo">
                    <div class="modal_info_wrapper">
                        <div class="block_info_status">
                            <div class="info_status_block" style="display: flex;">
                                <div class="status_box" :style="{ display: 'flex', justifyContent: 'center', marginRight: '20px' }">
                                    <span :style="{ backgroundColor: selectRow.variable_body, width: '20px', height: '20px', display: 'flex', borderRadius: '3px' }"></span>
                                </div>
                                <span style="color: var(--txt, #363F51);">{{ selectRow.status }}</span>
                            </div>
                            <svg style="cursor: pointer;" @click="handleCancelInfo" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <mask id="mask0_1780_13680" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                <rect width="24" height="24" fill="#D9D9D9"/>
                                </mask>
                                <g mask="url(#mask0_1780_13680)">
                                <path d="M14.0481 17.6538L12.9942 16.5692L16.8135 12.75H4.2981V11.25H16.8135L12.9942 7.43077L14.0481 6.34617L19.7019 12L14.0481 17.6538Z" fill="#363F51"/>
                                </g>
                            </svg>
                        </div>
                        <div class="block_info_order">
                            <div class="modal_info_row">
                                <span class="modal_info_okvid">{{String(selectRow.okvid_number).slice(0,String(selectRow.okvid_number).length-2)+'-'+String(selectRow.okvid_number).slice(-2)}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата готовности заказа:</span>
                                <span class="modal_info_value">{{ selectRow.completion_date }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Клиент:</span>
                                <span class="modal_info_value">{{ selectRow.customer }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Описание:</span>
                                <span class="modal_info_value">{{ selectRow.description }}</span>
                            </div>
                            <div class="modal_info_row" style="margin-bottom: 15px;">
                                <span class="modal_info_title">Менеджер:</span>
                                <span class="modal_info_value">{{ selectRow.manager_osz}}</span>
                            </div>
                            <div class="modal_info_row" style="margin-bottom: 15px;">
                                <span class="modal_info_title">Дизайнер:</span>
                                <span class="modal_info_value">{{ selectRow.designer_new}}</span>
                            </div>
                        </div>
                        <div class="block_info_okvid">
                            <div class="modal_info_row">
                                <span class="modal_info_title">Кол-во валов:</span>
                                <span class="modal_info_value">{{ selectRow.shaft_quantity }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Номера валов:</span>
                                <span class="modal_info_value">{{  selectRow.shafts_numbers}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата заведения Оквид:</span>
                                <span class="modal_info_value">{{ selectRow.order_accepted_date}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата заявки на гравировку:</span>
                                <span class="modal_info_value">{{ selectRow.request_install_date}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата передачи валов на гравировку:</span>
                                <span class="modal_info_value">{{  }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Наличие job ticket:</span>
                                <span class="modal_info_value">{{ selectRow.request_engraving_date }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Гравировщик:</span>
                                <span class="modal_info_value">{{ selectRow.engraver }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Подготовщик файлов:</span>
                                <span class="modal_info_value">{{ selectRow.repro}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Изменение элементов дизайна:</span>
                                <span class="modal_info_value">{{ selectRow.edit_design}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Этап прохождения заказа:</span>
                                <span class="modal_info_value">{{ selectRow.stage_order}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Новизна:</span>
                                <span class="modal_info_value">{{ selectRow.novizna }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Номер предыдущего заказа:</span>
                                <span class="modal_info_value">{{ selectRow.previous_order_number }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Номер следующего заказа:</span>
                                <span class="modal_info_value">{{selectRow.next_order_number }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Предыдущий гравировщик:</span>
                                <span class="modal_info_value">{{ selectRow.previous_engraver }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Номер предыдущего оквид:</span>
                                <span class="modal_info_value">{{ selectRow.previos_okvid}}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата печати/ламинации предыдущего заказа:</span>
                                <span class="modal_info_value">{{ selectRow.previous_order_printing_date }}</span>
                            </div>
                            <div class="modal_info_row">
                                <span class="modal_info_title">Дата отгрузки/отправки валов:</span>
                                <span class="modal_info_value">{{ selectRow.shipping_date}}</span>
                            </div>
                        </div>
                        <div class="block_info_btn">
                            <button class="row_btn">
                                <span class="row_btn_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <mask id="mask0_1780_12149" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                    <rect width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_1780_12149)">
                                    <path d="M6.08975 17.0833C5.67415 17.0833 5.31919 16.9362 5.02485 16.6418C4.73051 16.3475 4.58333 15.9925 4.58333 15.5769V5.00002H3.75V3.75005H7.49998V3.01288H12.5V3.75005H16.25V5.00002H15.4166V15.5769C15.4166 15.9979 15.2708 16.3542 14.9791 16.6458C14.6875 16.9375 14.3312 17.0833 13.9102 17.0833H6.08975ZM14.1666 5.00002H5.83331V15.5769C5.83331 15.6517 5.85735 15.7131 5.90544 15.7612C5.95352 15.8093 6.01496 15.8334 6.08975 15.8334H13.9102C13.9743 15.8334 14.0331 15.8066 14.0865 15.7532C14.1399 15.6998 14.1666 15.641 14.1666 15.5769V5.00002ZM7.83654 14.1667H9.08652V6.66669H7.83654V14.1667ZM10.9134 14.1667H12.1634V6.66669H10.9134V14.1667Z" fill="#363F51"/>
                                    </g>
                                </svg>
                                </span>
                                <span class="row_btn_title" @click="deleteRow()">Удалить строку</span>
                            </button>
                            <button class="row_btn">
                                <span class="row_btn_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <mask id="mask0_1780_12196" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                    <rect width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_1780_12196)">
                                    <path d="M11.0417 17.0833V13.9423C10.781 12.9893 10.3226 12.2823 9.66667 11.8213C9.01068 11.3603 8.29433 11.1297 7.5176 11.1297C7.29004 11.1297 7.0598 11.1428 6.8269 11.169C6.59398 11.1952 6.36641 11.2275 6.14419 11.266L7.67304 12.8077L6.79485 13.6858L3.75 10.641L6.79485 7.59614L7.67304 8.47433L6.14419 10.016C6.34931 9.97754 6.56139 9.94869 6.78042 9.92945C6.99944 9.91023 7.22808 9.90062 7.46633 9.90062C8.15224 9.90062 8.80475 10.0304 9.42387 10.29C10.043 10.5497 10.5823 10.9562 11.0417 11.5096V5.31885L9.49998 6.86054L8.62179 5.96954L11.6666 2.92468L14.7115 5.96954L13.8333 6.84772L12.2916 5.31885V17.0833H11.0417Z" fill="#1C1B1F"/>
                                    </g>
                                </svg>
                                </span>
                                <span class="row_btn_title" @click="showBreakRow()">Разбить строку</span>
                            </button>
                            <button class="row_btn" v-if="selectRow.stage_engraving_order != null">
                                <span class="row_btn_icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <mask id="mask0_1780_12196" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                    <rect width="20" height="20" fill="#D9D9D9"/>
                                    </mask>
                                    <g mask="url(#mask0_1780_12196)">
                                    <path d="M11.0417 17.0833V13.9423C10.781 12.9893 10.3226 12.2823 9.66667 11.8213C9.01068 11.3603 8.29433 11.1297 7.5176 11.1297C7.29004 11.1297 7.0598 11.1428 6.8269 11.169C6.59398 11.1952 6.36641 11.2275 6.14419 11.266L7.67304 12.8077L6.79485 13.6858L3.75 10.641L6.79485 7.59614L7.67304 8.47433L6.14419 10.016C6.34931 9.97754 6.56139 9.94869 6.78042 9.92945C6.99944 9.91023 7.22808 9.90062 7.46633 9.90062C8.15224 9.90062 8.80475 10.0304 9.42387 10.29C10.043 10.5497 10.5823 10.9562 11.0417 11.5096V5.31885L9.49998 6.86054L8.62179 5.96954L11.6666 2.92468L14.7115 5.96954L13.8333 6.84772L12.2916 5.31885V17.0833H11.0417Z" fill="#1C1B1F"/>
                                    </g>
                                </svg>
                                </span>
                                <span class="row_btn_title" @click="backBreakRow()">Отменить разделение</span>
                            </button>
                        </div>
                    </div>
                </template>
                <a-modal
                    title="Разбить строку"
                    :visible="visibleBreakRowModal"
                    @cancel="handleBreakRowModal"
                    style=" z-index: 10000;"
                    >
                    <template slot="footer">
                        <a-spin :spinning="spinning"></a-spin>
                        <a-button v-if="!spinning" key="distribute_orders" type="primary" @click="breakRow()">
                            Разбить
                        </a-button>
                    </template>
                    <h5>№ ОКВИДА  {{selectRow.okvid_number}}</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <span class="distribute_modal_title">Дата готовности:</span>
                            <date-picker
                                style="margin-bottom: 20px;"
                                type="date"
                                value-type="format"
                                format="YYYY-MM-DD"
                                v-model="firstBreakDate"
                            ></date-picker>
                            <span class="distribute_modal_title">Вторая дата готовности:</span>
                            <date-picker
                                type="date"
                                value-type="format"
                                format="YYYY-MM-DD"
                                v-model="secondBreakDate"
                            ></date-picker>
                        </div>
                        <div class="col-lg-6">
                            <span class="distribute_modal_title">Кол-во валов:</span>
                            <a-input style="margin-bottom: 20px;" v-model="firstQtyBreak"/>
                            <span class="distribute_modal_title">Кол-во валов:</span>
                            <a-input v-model="secondQtyBreak"/>
                        </div>
                    </div>
                </a-modal>
        </div>
    </div>  
</template>



<script>
import { DragDropContextProvider } from "vue-react-dnd";
import { HTML5Backend } from "react-dnd-html5-backend";
import DraggableRow from "./DraggableRow.vue";
import { Excel } from "antd-table-saveas-excel";
import DatePicker from 'vue2-datepicker';
import moment from 'moment';
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

    export default {
        components: {
            DragDropContextProvider,
            DatePicker
        },
        props: {
            movement_orders: Array,
            engravers: Array,
            execution_plan: Array,
            movement_novizna: Array,
            urlsavemovementdataprioruty: String,  
            urlsavemovementdatarow: String, 
            conditions: Array,     
            movement_statuses: Array, 
            downtimes: Array, 
            urladddowntime: String,
            urlpostmovementorders: String,
            urldeleterow: String,
            customers: Array,
            urlbreakrow: String,
            urlbackbreakrow: String,
            urlcancelchanges: String,
            urldailydistribution: String,
            urldeletedowntime: String,
        },  
        data() {
            return {
                spinning: false,
                html5Backend: HTML5Backend,
                selectMovementOrders: [...this.movement_orders],
                buttonsArea: ["Данафлекс", "Данафлекс-НАНО", "Данафлекс-Алабуга"],
                activeButtonsArea: [],
                visibleDelDownTime: false,
                components: {
                    body: {
                    row: DraggableRow,
                    },
                },
                columns_orders_movement: [
                    {
                    title: "№",
                    dataIndex: "priority",
                    key: "priority",
                    width: '4%',
                    scopedSlots: { 
                        customRender: 'priority', 
                    },
                    },
                    {
                    title: "Дата готовности",
                    dataIndex: "completion_date",
                    key: "completion_date",
                    width: '7%',
                    scopedSlots: { 
                        customRender: 'completion_date', 
                    },
                    },
                    {
                    title: "Дата печати / ламинации",
                    dataIndex: "printing_date",
                    key: "printing_date",
                    width: '7%',
                    customRender: this.formatDate,
                    },
                    {
                    title: "Статус",
                    dataIndex: "variable_body",
                    key: "variable_body",
                    width: '5%',
                    scopedSlots: { 
                        customRender: 'variable_body', 
                    },
                    },
                    {
                    title: "Валы на складе",
                    dataIndex: "shafts_in_warehouse",
                    key: "shafts_in_warehouse",
                    width: '5%',
                    scopedSlots: { 
                        customRender: 'shafts_in_warehouse', 
                    },
                    },
                    {
                    title: "Заказ",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    width: '5%',
                    scopedSlots: { 
                        customRender: 'okvid_number', 
                    },
                    },
                    {
                    title: "Партия",
                    dataIndex: "order_number",
                    key: "order_number",
                    width: '6%',
                    scopedSlots: { 
                        customRender: 'order_number', 
                    },
                    },
                    {
                    title: "Клиент",
                    dataIndex: "customer",
                    key: "customer",
                    ellipsis: true,
                    width: '10%',
                    scopedSlots: { 
                        customRender: 'order_number', 
                    },
                    },
                    {
                    title: "Описание",
                    dataIndex: "description",
                    key: "description",
                    width: '10%',
                    ellipsis: true,
                    scopedSlots: { 
                        customRender: 'order_number', 
                    },
                    },
                    {
                    title: "Формат",
                    dataIndex: "format",
                    key: "format",
                    width: '4%',
                    scopedSlots: { 
                        customRender: 'format', 
                    },
                    },
                    {
                    title: "Кол-во валов план",
                    dataIndex: "shaft_quantity",
                    key: "shaft_quantity",
                    width: '5%',
                    },
                    {
                    title: "Кол-во валов факт",
                    dataIndex: "shaft_quantity_fact",
                    key: "shaft_quantity_fact",
                    width: '5%',
                    scopedSlots: { 
                        customRender: 'shaft_quantity_fact', 
                    },
                    },
                    {
                    title: "Номер заявки на отправку валов",
                    dataIndex: "transfer_document",
                    key: "transfer_document",
                    width: '7%',
                    },
                    {
                    title: "Номер заявки на получение валов",
                    dataIndex: "transfer_document_print",
                    key: "transfer_document_print",
                    width: '7%',
                    },
                    {
                    title: "Комментарий Опл",
                    dataIndex: "comment",
                    key: "comment",
                    width: '7%',
                    scopedSlots: { 
                        customRender: 'comment', 
                    },
                    },
                    {
                    title: "Тех состояние заказа",
                    dataIndex: "technical_condition",
                    key: "technical_condition",
                    width: '10%',
                    scopedSlots: { 
                        customRender: 'technical_condition', 
                    },
                    },
                    {
                    title: "Снят с заказа",
                    dataIndex: "removed",
                    key: "removed",
                    width: '5%',
                    scopedSlots: { 
                        customRender: 'removed', 
                    },
                    },
                ],
                columns_orders_movement_export: [
                    {
                    title: "Дата готовности",
                    dataIndex: "completion_date",
                    key: "completion_date",
                    render: (value) => {
                        return value != null ? this.formatDate(value) : '';
                    },
                    },
                    {
                    title: "Статус",
                    dataIndex: "status",
                    key: "status",
                    width: '300',
                    scopedSlots: { 
                        customRender: 'variable_body', 
                    },
                    },
                    {
                    title: "№ Заказа",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    render: (value) => {
                        return value != null ? String(value).slice(0,String(value).length-2)+'-'+String(value).slice(-2) : '';
                    },
                    },
                    {
                    title: "№ Партии",
                    dataIndex: "order_number",
                    key: "order_number",
                    },
                    {
                    title: "Клиент",
                    dataIndex: "customer",
                    key: "customer",
                    width: '200',
                    },
                    {
                    title: "Описание",
                    dataIndex: "description",
                    key: "description",
                    width: '400',
                    },
                    {
                    title: "Формат",
                    dataIndex: "format",
                    key: "format",
                    render: (value,record) => {
                        if (record.isGroupHeader == true) {
                            return 'Итого:';
                        } else {
                            return value
                        }
                    },
                    },
                    {
                    title: "Кол-во валов",
                    dataIndex: "shaft_quantity",
                    key: "shaft_quantity",
                    render: (value,record) => {
                        if (record.isGroupHeader == true) {
                            return this.countShaftsFactDay(record.completion_date);
                        } else {
                            return value
                        }
                    },
                    },
                    {
                    title: "Кол-во валов факт",
                    dataIndex: "shaft_quantity_fact",
                    key: "shaft_quantity_fact",
                    },
                    {
                    title: "Дата печати / ламинации",
                    dataIndex: "printing_date",
                    key: "printing_date",
                    render: (value) => {
                        return value != null ? this.formatDate(value) : '';
                    },
                    },
                    {
                    title: "Номер заявки на отгрузку/ отправку валов",
                    dataIndex: "transfer_document",
                    key: "transfer_document",
                    },
                    {
                    title: "Номер заявки на получение валов",
                    dataIndex: "transfer_document_print",
                    key: "transfer_document_print",
                    },
                    {
                    title: "Комментарий Опл",
                    dataIndex: "comment",
                    key: "comment",
                    },
                    {
                    title: "Тех состояние заказа",
                    dataIndex: "technical_condition",
                    key: "technical_condition",
                    },
                ],
                visibleModalInfo: false,
                selectRow: {},
                currentPage: 1,
                pageSize: 10,
                QtyDistributeShaft: 1,
                selectEngraver: "Данафлекс-НАНО",
                visibleFilters: false,
                filterDate: [null,null],
                searchText: "",
                filters: {
                    'filterDate': [null,null],
                    'filterCondition': undefined,
                    'filterStatus': undefined,
                    'filterNovizna': undefined,
                    'filterCustomer': undefined,
                    'activeButtonsArea': undefined,
                },
                filterCondition: undefined,
                filterStatus: undefined,
                filterEngraver: "",
                filterNovizna: undefined,
                filterCustomer: undefined,
                visibleDowntimeModal: false,
                selectDownTime: "",
                selectDownTimeDate: "",
                selectDownTimeQty: 0,
                visiblePostModal: false,
                selectPostDate: "",
                DistributeStartDate: "",
                DistributeEndDate: "",
                visibleBreakRowModal: false,
                firstBreakDate: "",
                secondBreakDate: "",
                firstQtyBreak: 0,
                visibleDistributionModal: false,
            }
        },
        mounted() {
            document.addEventListener('click', this.handleClickOutsideFilters);
        },
        computed: {
            countExecutionShaftsPlan() {

                const filteredData = this.execution_plan.filter(item => item.engraver === this.selectEngraver);
                
                return filteredData;
            },

            countShaftsPlan() {
                const currentDate = new Date();
                const currentDay = new Date().getDate();
                const currentMonth = currentDate.getMonth() + 1;

                const filteredData = this.selectMovementOrders.filter(item => item.engraver === this.selectEngraver).filter(item => new Date(item.completion_date).getFullYear() === currentDate.getFullYear()).filter((item) => {
                    const itemDate = new Date(item.completion_date); 

                    if (currentDay == 1) {
                        return (((itemDate.getMonth() + 1 == currentMonth - 1) && (new Date(currentDate.getFullYear(), currentDate.getMonth()-1, 1).toLocaleDateString() != itemDate.toLocaleDateString())) || (currentDate.toLocaleDateString() == itemDate.toLocaleDateString()));
                    } else {
                        return (itemDate.getMonth() + 1 == currentMonth) && (itemDate.toLocaleDateString() != new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toLocaleDateString()) || (itemDate.toLocaleDateString() == new Date(currentDate.getFullYear(), currentDate.getMonth()+1, 1).toLocaleDateString());
                    }
                });
                
                const total = filteredData.reduce((acc, item) => {
                    return acc + Number(item.shaft_quantity); 
                }, 0);

                return total;
            },

            countShaftsFact() {
                const currentDate = new Date();
                const currentDay = new Date().getDate();
                const currentMonth = currentDate.getMonth() + 1;


                const filteredData = this.selectMovementOrders.filter(item => item.engraver === this.selectEngraver).filter(item => new Date(item.completion_date).getFullYear() === currentDate.getFullYear()).filter((item) => {
                    const itemDate = new Date(item.completion_date); 

                    if (currentDay == 1) {
                        return (((itemDate.getMonth() + 1 == currentMonth - 1) && (new Date(currentDate.getFullYear(), currentDate.getMonth()-1, 1).toLocaleDateString() != itemDate.toLocaleDateString())) || (currentDate.toLocaleDateString() == itemDate.toLocaleDateString()));
                    } else {
                        return (itemDate.getMonth() + 1 == currentMonth) && (itemDate.toLocaleDateString() != new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toLocaleDateString()) || (itemDate.toLocaleDateString() == new Date(currentDate.getFullYear(), currentDate.getMonth()+1, 1).toLocaleDateString());
                    }
                });

                const total = filteredData.reduce((acc, item) => {
                    return acc + Number(item.shaft_quantity_fact); 
                }, 0);

                return total;
            },

            countOrders() {

                const currentDate = new Date();
                const currentDay = new Date().getDate();
                const currentMonth = currentDate.getMonth() + 1;

                const filteredData = this.selectMovementOrders.filter(item => item.engraver === this.selectEngraver).filter(item => new Date(item.completion_date).getFullYear() === currentDate.getFullYear()).filter((item) => {
                    const itemDate = new Date(item.completion_date); 

                    if (currentDay == 1) {
                        return (((itemDate.getMonth() + 1 == currentMonth - 1) && (new Date(currentDate.getFullYear(), currentDate.getMonth()-1, 1).toLocaleDateString() != itemDate.toLocaleDateString())) || (currentDate.toLocaleDateString() == itemDate.toLocaleDateString()));
                    } else {
                        return (itemDate.getMonth() + 1 == currentMonth) && (itemDate.toLocaleDateString() != new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toLocaleDateString()) || (itemDate.toLocaleDateString() == new Date(currentDate.getFullYear(), currentDate.getMonth()+1, 1).toLocaleDateString());
                    }
                });

                const uniqueOkvidNumbers = [...new Set(filteredData.map((item) => item.okvid_number))];

                return uniqueOkvidNumbers.length;   
            },

            countFactOrders() {

                const currentDate = new Date();
                const currentDay = new Date().getDate();
                const currentMonth = currentDate.getMonth() + 1;

                const filteredData = this.selectMovementOrders.filter(item => item.engraver === this.selectEngraver).filter(item => item.shaft_quantity == item.shaft_quantity_fact).filter(item => new Date(item.completion_date).getFullYear() === currentDate.getFullYear()).filter((item) => {
                    const itemDate = new Date(item.completion_date); 

                    if (currentDay == 1) {
                        return (((itemDate.getMonth() + 1 == currentMonth - 1) && (new Date(currentDate.getFullYear(), currentDate.getMonth()-1, 1).toLocaleDateString() != itemDate.toLocaleDateString())) || (currentDate.toLocaleDateString() == itemDate.toLocaleDateString()));
                    } else {
                        return (itemDate.getMonth() + 1 == currentMonth) && (itemDate.toLocaleDateString() != new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toLocaleDateString()) || (itemDate.toLocaleDateString() == new Date(currentDate.getFullYear(), currentDate.getMonth()+1, 1).toLocaleDateString());
                    }
                });

                const uniqueOkvidNumbers = [...new Set(filteredData.map((item) => item.okvid_number))];

                return uniqueOkvidNumbers.length;   
            },

            secondQtyBreak() {
                return this.selectRow.shaft_quantity - this.firstQtyBreak;
            },

            selectedFilterCount() {
                let count = 0;
                if (this.filterCondition !== undefined) count++;
                if (this.filterStatus !== undefined) count++;
                if (this.filterNovizna !== undefined) count++;
                if (this.filterCustomer !== undefined) count++;
                if (this.filterDate[0] !== null || this.filterDate[1] !== null) count++;
                count += this.activeButtonsArea.length;
                return count;
            },
            filteredData() {
            
                if (!this.searchText) {
                    const enhancedData = [];
                    let currentDate = null;
                    let yesterdayDate = new Date(new Date().setDate(new Date().getDate()-2));


                    this.selectMovementOrders
                        .filter(item => item.engraver === this.selectEngraver && (this.filterDate[0] != null || new Date(item.completion_date) >= yesterdayDate || item.completion_date == null) )
                        .sort((a, b) => Date.parse(a.completion_date) - Date.parse(b.completion_date))
                        .forEach((record, index) => {
                          
                        if (record.engraver === this.selectEngraver && (this.filterDate[0] === null || record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1]) && (this.filterCustomer === undefined || record.customer === this.filterCustomer) && (this.filterStatus === undefined || record.status === this.filterStatus) && (this.filterNovizna === undefined || record.novizna === this.filterNovizna) && (this.filterCondition === undefined || this.filterCondition.includes(record.technical_condition)) || record.isGroupHeader || (record.status === 'Простой' && record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1])) {
                            
                            if ((record.completion_date !== currentDate) && (!record.isGroupHeader)) {
                                if (record.post) {
                                    enhancedData.push({ key: `date_${record.completion_date}`, completion_date: record.completion_date, isGroupHeader: true, post: true});
                                } else {
                                    enhancedData.push({ key: `date_${record.completion_date}`, completion_date: record.completion_date, isGroupHeader: true, post: false});
                                }

                                currentDate = record.completion_date;
                                
                            } else {
                                currentDate = record.completion_date;
                            }

                            if (this.activeButtonsArea.length === 0) {
                                if (record.engraver === this.selectEngraver) {
                                    enhancedData.push(record);
                                } else {
                                    enhancedData.pop();
                                }
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

                    if (enhancedData.length > 0 && enhancedData[enhancedData.length - 1].isGroupHeader) {
                        enhancedData.pop(); 
                    }

                    return enhancedData.sort((a, b) => {
                            if ((a.completion_date == b.completion_date) && (a.priority !== b.priority)) {
                                return a.priority - b.priority;
                            }
                                return new Date(a.completion_date) - new Date(b.completion_date);
                            });
                    
                } else {
                    let yesterdayDate = new Date(new Date().setDate(new Date().getDate()-2));
                    const searchTextLower = this.searchText.toLowerCase().trim();
                    const formatSearchTextLower = searchTextLower.replace(/-/g, "");
                    const filteredOrders = this.selectMovementOrders.filter(item =>
                        Object.values(item).some(val =>
                            (val !== null && val !== undefined && val.toString().toLowerCase().includes(searchTextLower)) || (val !== null && val !== undefined && val.toString().toLowerCase().includes(formatSearchTextLower))
                        ) && item.engraver === this.selectEngraver && (this.filterDate[0] != null || new Date(item.completion_date) >= yesterdayDate || item.completion_date == null)
                    );

                    const enhancedData = [];
                    let currentDate = null;

                    
                    filteredOrders.forEach((record, index) => {

                        if (record.engraver === this.selectEngraver && (this.filterDate[0] === null || record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1]) && (this.filterCustomer === undefined || record.customer === this.filterCustomer) && (this.filterStatus === undefined || record.status === this.filterStatus) && (this.filterNovizna === undefined || record.novizna === this.filterNovizna) && (this.filterCondition === undefined || this.filterCondition.includes(record.technical_condition)) || record.isGroupHeader || (record.status === 'Простой' && record.completion_date >= this.filterDate[0] && record.completion_date <= this.filterDate[1])) {
                            
                            if ((record.completion_date !== currentDate) && (!record.isGroupHeader) && (record.engraver === this.selectEngraver)) {
                                if (record.post) {
                                    enhancedData.push({ key: `date_${record.completion_date}`, completion_date: record.completion_date, isGroupHeader: true, post: true });
                                } else {
                                    enhancedData.push({ key: `date_${record.completion_date}`, completion_date: record.completion_date, isGroupHeader: true, post: false});
                                }
                                currentDate = record.completion_date;
                            } else {
                                currentDate = record.completion_date;
                            }

                            if (this.activeButtonsArea.length === 0) {
                                if (record.engraver === this.selectEngraver) {
                                    enhancedData.push(record);
                                }
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

                    if (enhancedData.length > 0 && enhancedData[enhancedData.length - 1].isGroupHeader) {
                        enhancedData.pop(); 
                    }

                    return enhancedData.sort((a, b) => {
                            if ((a.completion_date == b.completion_date) && (a.priority !== b.priority)) {
                                return a.priority - b.priority;
                            }
                                return new Date(a.completion_date) - new Date(b.completion_date);
                            });
                }
            },
            itemKey() {
                return (record) => record.key;
            },
        },
        methods: {

            savePlan() {
                axios
                .post(route('ugpc.executionplan.saveplan'),{engraver: this.selectEngraver,plan: this.plan})
                .then(response => {
                    window.location.href = 'https://okvid.danaflex.ru/ugpc/reengravingapps';
                });
            },
            countShaftsFactDay(day) {

                const filteredData = this.selectMovementOrders.filter(item => item.engraver === this.selectEngraver).filter(item => item.completion_date === day);

                const total = filteredData.reduce((acc, item) => {
                    return acc + item.shaft_quantity; 
                }, 0);

                return total;
            },
            handleDivClick(event) {
                event.stopPropagation();
            },
            disabledDate(date) {
                const today = new Date();
                const sevenDaysFromNow = new Date();
                sevenDaysFromNow.setDate(today.getDate() + 7);
                return date < sevenDaysFromNow;
            },
            formatDate(text) {
                if (text != null) {
                    return new Date(text).toLocaleDateString('ru-RU', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                }
            },
            cancelChanges(record) {
                axios.post(this.urlcancelchanges,{ 
                    params: 
                        { 
                            date: record.completion_date, 
                            engraver:  this.selectEngraver,
                        } 
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            breakRow() {
                if (this.firstBreakDate == null || this.secondBreakDate == null) {
                    alert('Необходимо ввести даты');
                } else {
                    this.spinning = true;
                    axios.post(this.urlbreakrow,{ 
                        params: 
                            { 
                                first_date: this.firstBreakDate, 
                                second_date: this.secondBreakDate, 
                                first_qty: this.firstQtyBreak,
                                second_qty:  this.secondQtyBreak,
                                row: this.selectRow,
                            } 
                    })
                    .then(response => {
                        this.selectMovementOrders = response.data;
                        this.visibleBreakRowModal = false;
                        this.spinning = false;
                    })
                    .catch(error => {
                        alert("Отсутствует соединение");
                    });
                }
            },

            backBreakRow() {
                axios.post(this.urlbackbreakrow,{ 
                    params: 
                        { 
                            row: this.selectRow,
                        } 
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                    this.visibleModalInfo = false;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });              
            },

            toggleButtonArea(button) {

                if (this.activeButtonsArea.includes(button)) {
                    this.activeButtonsArea = this.activeButtonsArea.filter(b => b !== button);
                } else {
                    this.activeButtonsArea.push(button);
                }
            },

            showBreakRow() {
                this.visibleBreakRowModal = true;
                this.visibleModalInfo = false;
            },

            handleBreakRowModal() {
                this.visibleBreakRowModal = false;
            },

            handleDoubleClickInput(record) {            
                this.filteredData.forEach(item => this.$set(item, 'editing', false));
                this.$set(record, 'editing', true);
                this.$set(record, 'originalCompletionDate', record.completion_date);
            },

            handleDoubleClickComment(record) {            
                this.filteredData.forEach(item => this.$set(item, 'editing_comment', false));
                this.$set(record, 'editing_comment', true);
            },

            showPostModal() {
                this.visiblePostModal = true;
            },

            showDistributionModal() {
                this.visibleDistributionModal = true;
            },

            handleDistributionModal() {
                this.visibleDistributionModal = false;
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

            handlePostModal() {
                this.visiblePostModal = false;
            },

            showDowntimeModal() {
                this.visibleDowntimeModal = true;
            },

            filterPlaces(place){
                this.filterPlaces = true;
            },

            handleDowntimeModal() {
                this.visibleDowntimeModal = false;
            },

            handleTableChange(pagination) {
                this.currentPage = pagination.current;
            },
            showModalInfo(record) {
                if (!record.isGroupHeader && record.status != 'Простой') {
                    this.visibleModalInfo = true;
                    this.selectRow = record;
                }
            },

            showFilters() {
                if (this.visibleFilters == true) {
                    this.visibleFilters = false;
                } else {
                    this.visibleFilters = true;
                }
            },

            handleFilters() {
                this.visibleFilters = false;
            },

            handleCancelInfo() {
                this.visibleModalInfo = false;
                this.selectRow = {};
            },

            saveMovementData() {
                axios.post(this.urlsavemovementdataprioruty,this.filteredData)
                .then(response => {

                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            saveMovementDataRow(record) {
                const currentDate = new Date();
                const CompletionDate = new Date(record.completion_date);

                if (record.editing && CompletionDate != currentDate && CompletionDate < currentDate && record.completion_date != null) {
                    this.$confirm({
                        title: 'Изменить дату?',
                        content: 'Дата меньше текущей даты. Хотите изменить дату?',
                        okText: 'Да',
                        cancelText: 'Нет',
                        onOk: () => {
                            axios.post(this.urlsavemovementdatarow,record)
                            .then(response => {
                                record.editing = false;
                                record.originalCompletionDate = record.completion_date;
                            })
                            .catch(error => {
                                alert("Отсутствует соединение");
                            });
                        },
                        onCancel() {
                            console.log(record);
                            record.completion_date = record.originalCompletionDate;
                            record.editing = false;
                        },
                    });
                } else {
                    axios.post(this.urlsavemovementdatarow,record)
                            .then(response => {
                                record.editing = false;
                            })
                            .catch(error => {
                                alert("Отсутствует соединение");
                            });
                }
            },

            exportExcel() {
                const excel = new Excel();
                excel
                .addSheet("1")
                .addColumns(this.columns_orders_movement_export)
                .addDataSource(this.filteredData, {
                    str2Percent: true
                })
                .saveAs("Excel.xlsx");
            },

            deleteRow() {
                axios.post(this.urldeleterow,this.selectRow)
                .then(response => {
                    this.selectMovementOrders = response.data;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            rowClassName(record) {
                const isDowntime = record.status === "Простой";
                const isGroup = record.isGroupHeader; 
                const isPost = record.post; //

                if (isDowntime) {
                    return 'background_downtime_row';
                }else if (isGroup){
                    return 'background_group_row';
                }else if (isPost){
                    return 'background_post_row';
                }   
            },
            
            moveRow(dragIndex, hoverIndex) {
                let moveData = this.filteredData;
                let draggingRow = moveData[dragIndex];
                let draggingDate = moveData[dragIndex].completion_date;
                let hoverRow = '';


                if (!moveData[dragIndex].isGroupHeader && !moveData[dragIndex].post && moveData[dragIndex].status != 'Простой') {
                    
                    if (hoverIndex < dragIndex) {
                        if (!moveData[hoverIndex].isGroupHeader) {
                            hoverRow = moveData[hoverIndex];
                        } else {
                            hoverRow = moveData[hoverIndex-1];
                        }
                    }else {
                        if (!moveData[hoverIndex].isGroupHeader) {
                            hoverRow = moveData[hoverIndex];
                        } else {
                            hoverRow = moveData[hoverIndex+1];
                        }
                    }

                    

                    if (draggingRow && hoverRow) {
                        if (dragIndex !== hoverIndex) {

                            if (draggingRow.completion_date !== hoverRow.completion_date) {

                                let newCompletionDate = '';
                                
                                
                                if (hoverIndex < dragIndex) {
                                    for (let i = hoverIndex-1; i >= 0; i--) {
                                        if (moveData[i].isGroupHeader) {
                                            newCompletionDate = moveData[i].completion_date;
                                            break;
                                        }
                                    }

                                } else {
                                    
                                    if (!moveData[hoverIndex].isGroupHeader) {                                   
                                        newCompletionDate = moveData[hoverIndex].completion_date;                           
                                    } else {
                                        newCompletionDate = moveData[hoverIndex+1].completion_date;
                                    }
                                }
                            

                                moveData.splice(dragIndex, 1);
                                moveData.splice(hoverIndex, 0, draggingRow);

                                draggingRow.completion_date = newCompletionDate;

                                
                                let priorityCounterHover = 0;
                                moveData
                                    //.filter(item => item.completion_date === draggingRow.completion_date || item.completion_date === hoverRow.completion_date)
                                    .forEach(item => {
                                        if (item.completion_date === draggingRow.completion_date || item.completion_date === hoverRow.completion_date) {
                                            item.priority = priorityCounterHover;
                                            priorityCounterHover++;
                                        }
                                    });

                                let priorityCounterDregging = 0;
                                moveData
                                    //.filter(item => item.completion_date === draggingDate)
                                    .forEach(item => {
                                        if (item.completion_date === draggingDate) {
                                            item.priority = priorityCounterDregging;
                                            priorityCounterDregging++;
                                        }
                                    });

                            } else {
                                moveData.splice(dragIndex, 1);
                                moveData.splice(hoverIndex, 0, draggingRow);

                                let priorityCounter = 0;
                                moveData.forEach(item => {
                                    if (item.completion_date === draggingRow.completion_date) {
                                        item.priority = priorityCounter;
                                        priorityCounter++;
                                    }
                                });
                            }

                            this.filteredData = moveData;

                            this.saveMovementData();
                            //this.$forceUpdate();
                        }
                    }
                }
            },
            customRow(record, index) {
                return {
                    attrs: {
                    index,
                    record,
                    moveRow: this.moveRow,
                    },
                };
            },

            changeFilters() {
            },

            filterOption(input, option) {
                return (
                    option.componentOptions.children[0].text.toLowerCase().indexOf(input.toLowerCase()) >= 0
                );
            },

            addDowntime() {
                axios.post(this.urladddowntime,{ 
                    params: 
                        { 
                            type: this.selectDownTime, 
                            date: this.selectDownTimeDate, 
                            qty: this.selectDownTimeQty,
                            engraver:  this.selectEngraver,
                        } 
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                    this.visibleDowntimeModal = false;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            deleteDownTime(record) {
                axios.post(this.urldeletedowntime,{ 
                    record
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            dailyDistribution() {
                axios.post(this.urldailydistribution,{ 
                    params: 
                        { 
                            date_start: this.DistributeStartDate, 
                            date_end: this.DistributeEndDate, 
                            qty: this.QtyDistributeShaft,
                            engraver: this.selectEngraver,
                        } 
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                    this.visibleDistributionModal = false;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            postMovementOrders() {
                axios.post(this.urlpostmovementorders,{ 
                    params: 
                        { 
                            date: this.selectPostDate, 
                            engraver:  this.selectEngraver,
                        } 
                })
                .then(response => {
                    this.selectMovementOrders = response.data;
                    this.visiblePostModal = false;
                    this.$success({
                        title: 'График создан Начальник смены получит уведомление о новом графике на '+this.selectPostDate,
                    });
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },
        }   
    }   
</script>

<style>
.not_finished {
    border: 1px solid red !important;
}
.statistics_blocks {
    display: flex;
}

.statistic_block {
    display: flex;
    margin-right: 30px;
}

.statistic_title {
    color: var(--grayscale-700, #7B7B7B);
    /* Body/Regular_16 */
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
    /* Body/Regular_16 */
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.statistic_percent {
    color: var(--primary-300-activ, #4A9DFF);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}
.active_button_area {
    background-color: #1890ff;
    color: white;
    border: 0;
    display: flex;
    padding: 8px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 10px;
}

.filters_customer_block {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.not_executed {
    color: red;
    font-weight: bold;
}

.overfulfilled {
    color: green;
    font-weight: bold;
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

.filter_button {
    display: flex;
}

.filters_area_value_block {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 400px;
    margin-top: 15px;
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

.active-filters {
    border-radius: 10px;
    background: var(--primary-100, #E5EEFF);
    padding: 10px;
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
    border: 0;
}

.row_btn_title {
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    margin-left: 8px;
}

.row_btn:hover {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    border: none;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-left: 20px;
}

.row_btn:hover .row_btn_icon svg {  
    stroke: #fff;
    fill: #fff;
}


.row_btn {
    color: var(--txt, #363F51);
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

.block_info_btn {
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
    height: 100%;
    margin-top: 30px;
}

.block_info_status {
    display: flex;
    justify-content: space-between;
    padding: 0 0 20px 0;
    align-items: center;
}
.engraver_select {
    margin-right: 20px;
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

.modal_info_row {
    margin-top: 14px;
    display: flex;
    width: 100%;
}

.modal_info_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    width: 50%;
}
.modal_info_okvid {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}
.modal_info_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    width: 50%;
}

.table_wrapper {
  position: relative;
  display: flex;
  align-items: stretch;
  width: 100%;
}

.a-table {
  flex: 1;
  transition: margin-left 0.3s;
}

.block_info_status {
    border-bottom: 1px solid #E8E8E8;
}

.block_info_order {
    border-bottom: 1px solid #E8E8E8;
}

.modal_info_wrapper {
    background-color: #f0f0f0;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    position: fixed;
    top: 0;
    right: 0;
    box-sizing: border-box;
    border-radius: 10px;
    background: #FFF;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    z-index: 5000; 
    top: 50px;
    right: 30px;
    transition: right 0.3s;
    width: 40%;
    display: flex;
    flex-direction: column;
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

.other_btn {
    background: none;
    border: none;
}

.export_excel_btn {
    border-radius: 10px;
    background: var(--bg, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    padding: 8px;
    border: 0;
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
.group_row {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 18px !important;
    font-style: normal;
    font-weight: 800 !important;
}
.background_group_row {
    background: var(--primary-0, #F3F9FF);
}

.background_group_row td {
    border-right: 0 !important;
}

.background_post_row {
    background: var(--grayscale-200, #F7F7F8);
}

.background_downtime_row {
    background: var(--additional-light-red, #FFEBEB);
}
</style>