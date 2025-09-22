<template>
        <div class="row">
            <a-modal
                title="Выберите машинный центр"
                :visible="visibleModalMachine"
                >
                <template slot="footer">
                    <a-button key="submit" type="primary" @click="startWorkShiftCard()">
                    Сохранить
                    </a-button>
                </template>
                <a-select style="width: 100%" v-model="selectMachine">
                    <a-select-option v-for="machine in this.machines" :key="machine">
                        {{ machine }}
                    </a-select-option>
                </a-select>
            </a-modal>
            <div class="col-lg-8">
                <div class="info-container">
                    <div class="info-container_header">
                        <span class="info_title">МЦ:</span> <span class="info_value">{{this.selectMachine}}</span>
                        <span class="info_title">Оператор:</span> <span class="info_value">{{this.selectOperator.employer_name_1c}}</span>
                        <span class="info_title">Смена:</span> <span class="info_value">{{this.selectWorkShiftCode.letter}}</span>
                        <span class="info_value">{{this.selectWorkShiftCode.starting_time+'-'+this.selectWorkShiftCode.ending_time}}</span>
                        <span class="info_value">{{this.selectWorkShiftCode.date}}</span>
                        <a-button class="operation__second_btn" @click="showModalSecondaryOperation()">Доп. операции</a-button>
                        <a-modal
                            title="Доп. операции"
                            :visible="visibleModalSecondaryOperation"
                            @cancel="handleCancelSecondaryOperation"
                            >
                            <template slot="footer">
                                <a-button key="submit" type="primary" @click="startSecondaryOperation(selectedSecondaryOperation)">
                                Начать
                                </a-button>
                            </template>
                            <a-select style="width: 100%" v-model="selectedSecondaryOperation">
                                <a-select-option v-for="operation in selectSecondaryOperations" :key="operation">
                                    {{ operation }}
                                </a-select-option>
                            </a-select>
                        </a-modal>
                    </div>
                    <div class="info-container_body">
                        <span class="operation_description_title"></span>
                        <span v-if="(selectedSecondaryOperation ==null) && (selectConsumpShaft==null) && (this.shiftReceptionOperation == null)" class="no_operation_title">Выберите вал из списка или доп.операцию</span>
                        <template v-if="(selectedSecondaryOperation !=null) && (selectConsumpShaft==null) && (this.shiftReceptionOperation == null)">
                            <h5 style="margin-top: 20px;">{{this.selectedSecondaryOperation}}</h5>
                            <template v-if="(this.selectedSecondaryOperation == 'Замена алмазного резца') || (this.selectedSecondaryOperation == 'Замена торцевого резца') || (this.selectedSecondaryOperation == 'Замена каскадных фильтров') || (this.selectedSecondaryOperation == 'Замена фильтровальных патронов') || (this.selectedSecondaryOperation == 'Замена камня') || (this.selectedSecondaryOperation == 'Замена полировальной ленты')">
                                <span class="secondary_qty_title">Количество</span>
                                <a-input v-model="selectQtySecondayOperation" class="secondary_qty_value" placeholder="Введите кол-во" />
                            </template>
                            <div class="row_shift_reception_btn">
                                <a-button class="operation_grinder_timing" @click="showModalTimingOperation()">Выставить время вручную</a-button>
                                <a-button class="operation_grinder_btn_close" @click="closeOperation(selectedSecondaryOperation)">Завершить</a-button>
                                <a-modal
                                    title="Выставить время вручную"
                                    :visible="visibleModalTimingOperation"
                                    @cancel="handleModalTimingOperation"
                                    >
                                    <template slot="footer">
                                        <a-button style="border-radius: 8px; background: var(--primary-100, #E5EEFF); border: none;" key="submit" @click="startSecondaryOperation(selectedSecondaryOperation)">
                                        Сохранить
                                        </a-button>
                                    </template>
                                    <h5>Операция: {{this.selectedSecondaryOperation}}</h5>
                                    <span class="secondary_qty_title">Количество</span>
                                    <a-input v-model="selectQtySecondayOperation" class="secondary_qty_value" placeholder="Введите кол-во" />
                                    <h5 style="margin-top: 20px;">Когда была операция:</h5>
                                    <span @click="changeBackGroundBtnTiming(slot)" :class="{ 'timing_btn_click': isClickedTimingBtnIndex === slot }" class="timing_btn" v-for="slot in this.selectFreeTimeSlots" :key="slot">{{slot.start_time}}-{{slot.end_time}}</span>
                                </a-modal>
                            </div>
                        </template>
                        <template v-if="(this.shiftReceptionOperation != null)">
                            <h5 class="shift_reception_title">Прием смены</h5>
                            <h6 class="shift_handover_title">Сдал смену:</h6> <h6 class="shift_handover_value">{{this.shiftHandoverOperation.operator}}</h6>
                            <h6 v-if="this.shiftRolloverShaft != null" class="shift_handover_title">Переходящий вал:</h6> <h6 v-if="this.shiftRolloverShaft != null" class="shift_handover_value">{{this.shiftRolloverShaft.shaft_id}}</h6>
                            <div class="row_shift_reception_btn">
                                <a-button class="operation_grinder_btn_close" @click="closeOperation(shiftReceptionOperation.description)">Завершить</a-button>
                            </div>
                        </template>
                        <template v-if="(selectConsumpShaft!=null) && (this.shiftReceptionOperation == null)">
                            <div class="info-shaft_wrapper">
                                <div class="shaft_info_row_top">
                                    <div class="">
                                    <span class="shaft_id_title">Вал № {{this.selectConsumpShaft.shaft_id}}</span>
                                    <span class="shaft_number_title"></span>
                                    <span class="okvid_number_title">Оквид:</span>
                                    <span class="okvid_number_value">{{this.selectConsumpShaft.okvid_number}}</span>
                                    <span class="okvid_number_title">Заказ:</span>
                                    <span class="okvid_number_value">{{this.selectConsumpShaft.order_number}}</span>
                                    <span class="format_title">Формат:</span>
                                    <span class="format_value">{{selectConsumpShaft.shaft_format}}</span>
                                    </div>
                                    <a-dropdown :placement="'bottomRight'">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="cursor: pointer;" @click="e => e.preventDefault()"> 
                                            <mask id="mask0_1294_11427" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                            <rect width="24" height="24" fill="#D9D9D9"/>
                                            </mask>
                                            <g mask="url(#mask0_1294_11427)">
                                            <path d="M12 19.2688C11.5875 19.2688 11.2344 19.122 10.9406 18.8282C10.6469 18.5345 10.5 18.1814 10.5 17.7689C10.5 17.3564 10.6469 17.0033 10.9406 16.7095C11.2344 16.4158 11.5875 16.2689 12 16.2689C12.4125 16.2689 12.7656 16.4158 13.0593 16.7095C13.3531 17.0033 13.5 17.3564 13.5 17.7689C13.5 18.1814 13.3531 18.5345 13.0593 18.8282C12.7656 19.122 12.4125 19.2688 12 19.2688ZM12 13.4996C11.5875 13.4996 11.2344 13.3527 10.9406 13.059C10.6469 12.7652 10.5 12.4121 10.5 11.9996C10.5 11.5872 10.6469 11.234 10.9406 10.9403C11.2344 10.6465 11.5875 10.4997 12 10.4997C12.4125 10.4997 12.7656 10.6465 13.0593 10.9403C13.3531 11.234 13.5 11.5872 13.5 11.9996C13.5 12.4121 13.3531 12.7652 13.0593 13.059C12.7656 13.3527 12.4125 13.4996 12 13.4996ZM12 7.73039C11.5875 7.73039 11.2344 7.58352 10.9406 7.28977C10.6469 6.99604 10.5 6.64292 10.5 6.23042C10.5 5.81794 10.6469 5.46482 10.9406 5.17107C11.2344 4.87734 11.5875 4.73047 12 4.73047C12.4125 4.73047 12.7656 4.87734 13.0593 5.17107C13.3531 5.46482 13.5 5.81794 13.5 6.23042C13.5 6.64292 13.3531 6.99604 13.0593 7.28977C12.7656 7.58352 12.4125 7.73039 12 7.73039Z" fill="#4A9DFF"/>
                                            </g>
                                        </svg>
                                        <a-menu slot="overlay">
                                            <a-menu-item>
                                                <a href="javascript:;" @click="showBrakShaft">Вал на рассмотрени (брак)</a>
                                            </a-menu-item>
                                            <a-menu-item>
                                                <a href="javascript:;">Приостановить</a>
                                            </a-menu-item>
                                            <a-menu-item>
                                                <a href="javascript:;" @click="addSteps()">Отправить вал на обезжиривание и пред. меднение</a>
                                            </a-menu-item>
                                        </a-menu>
                                    </a-dropdown>
                                    <a-modal
                                        title="Вал на рассмотрение"
                                        :visible="visibleBrakShaft"
                                        @cancel="handleBrakShaft"
                                        >
                                        <template slot="footer">
                                            <a-button key="delete_operation" type="primary" @click="brakShaft()">
                                            На рассмотрение
                                            </a-button>
                                        </template>
                                        <a-textarea
                                            v-model="noteBrakShaft"
                                            placeholder="Напишите причину"
                                            :auto-size="{ minRows: 3, maxRows: 5 }"
                                        />
                                    </a-modal>
                                </div>
                                <div class="shaft_info_row">
                                    <div class="diameter_block">
                                        <span class="block_title">Диаметр Вх. контроля:</span>
                                        <span class="block_value">{{Number(selectConsumpShaft.shaft_diameter).toFixed(3)}}</span>
                                    </div>
                                    <div v-if="selectConsumpShaft.roughness != null" class="diameter_block">
                                        <span class="block_title">Шероховатость:</span>
                                        <span class="block_value">{{selectConsumpShaft.roughness}}</span>
                                    </div>                             
                                    <div class="plan_info_block">
                                        <span class="block_title">Плановые показатели:</span>
                                        <div class="plan_diameter_block" v-if="startGrindingEdition">
                                            <span class="plan_title">D-база факт:</span>
                                            <span class="plan_value">{{selectConsumpShaft.base_grinding_diameter_fact}}</span>
                                        </div>
                                        <div class="plan_diameter_block">
                                            <span class="plan_title">D-тираж план:</span>
                                            <span class="plan_value">{{Number(selectConsumpShaft.grinding_edition_diameter_plan).toFixed(3)}}</span>
                                        </div>
                                        <div class="plan_diameter_block" v-if="startGrindingEdition">
                                            <span class="plan_title">T, мкм:</span>
                                            <span class="plan_value">{{Number(selectConsumpShaft.copper_plating_edition_t_plan).toFixed(3)}}</span>
                                        </div>
                                        <div class="plan_diameter_block" v-if="startGrindingEdition && selectConsumpShaft.copper_plating_edition_bath_fact != null">
                                            <span class="plan_title">Ванна:</span>
                                            <span class="plan_value">{{selectConsumpShaft.copper_plating_edition_bath_fact}}</span>
                                        </div>
                                        <div class="plan_diameter_block" v-if="startBaseGrinding">
                                            <span class="plan_title">D-база план:</span>
                                            <span class="plan_value">{{Number(selectConsumpShaft.base_grinding_diameter_plan).toFixed(3)}}</span>
                                        </div>
                                        <div class="plan_edge_block" v-if="startBaseGrinding">
                                            <span class="plan_title">Rz:</span>
                                            <span class="plan_value">{{selectConsumpShaft.base_grinding_rz_plan}}</span>
                                        </div>
                                        <div class="plan_edge_block" v-if="startGrindingEdition" >
                                            <span class="plan_title">Rz:</span>
                                            <span class="plan_value">{{selectConsumpShaft.grinding_edition_rz_plan}}</span>
                                        </div>
                                        <div class="plan_edge_block" v-if="startGrindingEdition">
                                            <span class="plan_title">Кромка:</span>
                                            <span class="plan_value">{{selectConsumpShaft.base_grinding_kromka_fact}}</span>
                                        </div>
                                        <div class="plan_diameter_block" v-if="startGrindingEdition && (Number(selectConsumpShaft.copper_plating_edition_t_plan).toFixed(3) < 150)">
                                            <span class="plan_value" style="font-weight: bold;">Шлифовка без резца</span>
                                        </div>
                                        <div class="plan_diameter_block" v-if="startGrindingEdition && selectConsumpShaft.pre_copper_plating">
                                            <span class="plan_value" style="font-weight: bold;">Предмедь</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="shaft_info_row">
                                    <div v-if="selectConsumpShaft.note" class="note_block">
                                        <span class="block_title">Примечание:</span>
                                        <span class="block_note_value">{{selectConsumpShaft.note}}</span>
                                    </div>
                                    <div v-if="selectConsumpShaft.engraving_note" class="note_engraving_block">
                                        <span class="block_title">Для гравировщика:</span>
                                        <span class="block_note_value">{{selectConsumpShaft.engraving_note}}</span>
                                    </div>
                                </div>
                                <div v-show="(startBaseGrinding) && (this.selectedMainOperation != null)" class="shaft_info_row_base_grinding">
                                    <div class="base_grinding_block">
                                        <span class="block_title">Разница диаметра:</span>
                                        <a-input type="number" class="input_value" 
                                        v-model="selectConsumpShaft.base_grinding_diameter_difference"
                                        />
                                    </div>
                                    <div class="base_grinding_block">
                                        <span class="block_title">Фактический диаметр:</span>
                                        <a-input type="number" :max="Number(selectConsumpShaft.base_grinding_diameter_plan).toFixed(3)" class="input_value"
                                        v-model="selectConsumpShaft.base_grinding_diameter_fact"
                                        />
                                    </div>
                                    <div class="base_grinding_block">
                                        <span class="block_title">Шероховатость (Rz):</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.base_grinding_rz_fact"
                                        />
                                    </div>
                                    <div class="base_grinding_block">
                                        <span class="block_title">Кромка:</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.base_grinding_kromka_fact"
                                        />
                                    </div>
                                </div>
                                <div v-show="(startGrindingEdition) && (this.selectedMainOperation != null)" class="shaft_info_row_grinding_edition">
                                    <div class="grinding_edition_block">
                                        <span class="block_title">Разница диаметра:</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.grinding_edition_diameter_difference"
                                        />
                                    </div>
                                    <div class="grinding_edition_block">
                                        <span class="block_title">Фактический диаметр:</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.grinding_edition_diameter_fact"
                                        />
                                    </div>
                                    <div class="grinding_edition_block">
                                        <span class="block_title">Шереховатость (Rz):</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.grinding_edition_rz_fact"
                                        />
                                    </div>
                                    <div class="grinding_edition_block">
                                        <span class="block_title">Твердость (HV):</span>
                                        <a-input type="number" class="input_value"
                                        v-model="selectConsumpShaft.grinding_edition_hv_fact"
                                        />
                                    </div>
                                </div>
                                <div class="shaft_info_row_btn">
                                    <div class="current_operation_info" v-if="this.selectedMainOperation != null">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <mask id="mask0_1144_5589" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                            <rect width="24" height="24" fill="#D9D9D9"/>
                                            </mask>
                                            <g mask="url(#mask0_1144_5589)">
                                            <path d="M15.4731 16.5269L16.5269 15.4731L12.75 11.6959V6.99998H11.25V12.3038L15.4731 16.5269ZM12.0016 21.5C10.6877 21.5 9.45268 21.2506 8.29655 20.752C7.1404 20.2533 6.13472 19.5765 5.2795 18.7217C4.42427 17.8669 3.74721 16.8616 3.24833 15.706C2.74944 14.5504 2.5 13.3156 2.5 12.0017C2.5 10.6877 2.74933 9.45268 3.248 8.29655C3.74667 7.1404 4.42342 6.13472 5.27825 5.2795C6.1331 4.42427 7.13834 3.74721 8.29398 3.24833C9.44959 2.74944 10.6844 2.5 11.9983 2.5C13.3122 2.5 14.5473 2.74933 15.7034 3.248C16.8596 3.74667 17.8652 4.42342 18.7205 5.27825C19.5757 6.1331 20.2527 7.13834 20.7516 8.29398C21.2505 9.44959 21.5 10.6844 21.5 11.9983C21.5 13.3122 21.2506 14.5473 20.752 15.7034C20.2533 16.8596 19.5765 17.8652 18.7217 18.7205C17.8669 19.5757 16.8616 20.2527 15.706 20.7516C14.5504 21.2505 13.3156 21.5 12.0016 21.5ZM12 20C14.2166 20 16.1041 19.2208 17.6625 17.6625C19.2208 16.1041 20 14.2166 20 12C20 9.78331 19.2208 7.89581 17.6625 6.33748C16.1041 4.77914 14.2166 3.99998 12 3.99998C9.78331 3.99998 7.89581 4.77914 6.33748 6.33748C4.77914 7.89581 3.99998 9.78331 3.99998 12C3.99998 14.2166 4.77914 16.1041 6.33748 17.6625C7.89581 19.2208 9.78331 20 12 20Z" fill="#4A9DFF"/>
                                            </g>
                                        </svg>
                                        <span class="current_operation_title">Текущая операция:</span>
                                        <span class="current_operation">{{this.selectedMainOperation}}</span>
                                        <svg @click="showDeleteOperation()"  class="delete_operation_btn" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <mask id="mask0_1144_5594" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="25">
                                            <rect y="0.5" width="24" height="24" fill="#D9D9D9"/>
                                            </mask>
                                            <g mask="url(#mask0_1144_5594)">
                                            <path d="M7.3077 20.9998C6.80898 20.9998 6.38302 20.8232 6.02982 20.47C5.67661 20.1168 5.5 19.6908 5.5 19.1921V6.49981H4.5V4.99983H8.99997V4.11523H15V4.99983H19.5V6.49981H18.5V19.1921C18.5 19.6972 18.325 20.1248 17.975 20.4748C17.625 20.8248 17.1974 20.9998 16.6922 20.9998H7.3077ZM17 6.49981H6.99997V19.1921C6.99997 19.2818 7.02883 19.3556 7.08652 19.4133C7.14422 19.471 7.21795 19.4998 7.3077 19.4998H16.6922C16.7692 19.4998 16.8397 19.4678 16.9038 19.4037C16.9679 19.3395 17 19.269 17 19.1921V6.49981ZM9.40385 17.4998H10.9038V8.49981H9.40385V17.4998ZM13.0961 17.4998H14.5961V8.49981H13.0961V17.4998Z" fill="#7B7B7B"/>
                                            </g>
                                        </svg>
                                        <a-modal
                                            :visible="visibleDeleteOperation"
                                            @cancel="handleCancelDeleteOperation"
                                            >
                                            <template slot="footer">
                                                <a-button key="cancel_delete_operation" type="default" @click="handleCancelDeleteOperation()">
                                                Отмена
                                                </a-button>
                                                <a-button key="delete_operation" type="primary" @click="deleteOperation()">
                                                Удалить
                                                </a-button>
                                            </template>
                                            <span class="delete_operation_body">Удалить операцию?</span>
                                        </a-modal>
                                    </div>
                                    <a-button class="operation_grinder_btn" @click="showModalOperation()">Добавить операцию</a-button>
                                    <a-button v-if="this.selectedMainOperation != null" class="operation_grinder_btn_close" @click="closeOperation(selectedMainOperation)">Завершить</a-button>
                                    <a-button v-if="this.selectedMainOperation == null" class="operation_grinder_btn_close" @click="deleteConsumpShaft(selectConsumpShaft)">Отменить</a-button>
                                    <a-modal
                                        title="Операции для вала"
                                        :visible="visibleModalOperation"
                                        @cancel="handleCancelOperation"
                                        >
                                        <template slot="footer">
                                            <a-button key="submit" type="primary" @click="startOperation(selectedMainOperation)">
                                            Начать
                                            </a-button>
                                        </template>
                                        <a-radio-group style="display: flex; flex-direction: column;"
                                            v-model="selectedMainOperation"
                                            :options="selectMainOperation" 
                                            @change="onChangeMainOperation" />
                                    </a-modal>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="operation-container">
                    <div class="operation_info_title">                  
                        <div class="operation_info_shaft">
                            <div class="shafts_made">
                                <span class="shafts_made_title">Сделано валов:</span>
                                <span class="shafts_made_value">{{this.shaftsMadeWorkShift}}</span>
                            </div>
                            <div class="base_grinding_make">
                                <span class="base_grinding_make_title">Шлифовка базы:</span>
                                <span class="base_grinding_make_value">{{this.baseGrindingMadeWorkShift}}</span>
                            </div>
                            <div class="grinding_edition_make">
                                <span class="grinding_edition_make_title">Шлифовка тираж:</span>
                                <span class="grinding_edition_make_value">{{this.grindingEditionMadeWorkShift}}</span>
                            </div>
                        </div>
                        <div class="format_difference">
                            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_1327_15392" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                <rect width="20" height="20" fill="#D9D9D9"/>
                                </mask>
                                <g mask="url(#mask0_1327_15392)">
                                <path d="M6.11673 14.4604C6.33506 14.4604 6.51762 14.3865 6.66442 14.2388C6.81122 14.0911 6.88463 13.9081 6.88463 13.6897C6.88463 13.4714 6.81078 13.2889 6.66308 13.1421C6.51538 12.9953 6.33236 12.9219 6.11403 12.9219C5.89569 12.9219 5.71313 12.9957 5.56635 13.1434C5.41955 13.2911 5.34615 13.4741 5.34615 13.6925C5.34615 13.9108 5.42 14.0933 5.5677 14.2401C5.71538 14.3869 5.89839 14.4604 6.11673 14.4604ZM6.11673 10.6911C6.33506 10.6911 6.51762 10.6173 6.66442 10.4696C6.81122 10.3219 6.88463 10.1389 6.88463 9.92053C6.88463 9.70219 6.81078 9.51963 6.66308 9.37283C6.51538 9.22604 6.33236 9.15265 6.11403 9.15265C5.89569 9.15265 5.71313 9.22649 5.56635 9.37418C5.41955 9.52188 5.34615 9.70489 5.34615 9.92322C5.34615 10.1416 5.42 10.3241 5.5677 10.4709C5.71538 10.6177 5.89839 10.6911 6.11673 10.6911ZM6.11673 6.92188C6.33506 6.92188 6.51762 6.84802 6.66442 6.70033C6.81122 6.55264 6.88463 6.36963 6.88463 6.1513C6.88463 5.93297 6.81078 5.7504 6.66308 5.6036C6.51538 5.4568 6.33236 5.3834 6.11403 5.3834C5.89569 5.3834 5.71313 5.45725 5.56635 5.60495C5.41955 5.75265 5.34615 5.93567 5.34615 6.154C5.34615 6.37233 5.42 6.55489 5.5677 6.70168C5.71538 6.84848 5.89839 6.92188 6.11673 6.92188ZM9.1923 14.1911H14.5769V13.1911H9.1923V14.1911ZM9.1923 10.4219H14.5769V9.42188H9.1923V10.4219ZM9.1923 6.65265H14.5769V5.65265H9.1923V6.65265ZM3.61538 17.9219C3.15513 17.9219 2.77083 17.7677 2.4625 17.4594C2.15417 17.151 2 16.7668 2 16.3065V3.53725C2 3.077 2.15417 2.69271 2.4625 2.38438C2.77083 2.07604 3.15513 1.92188 3.61538 1.92188H16.3846C16.8449 1.92188 17.2292 2.07604 17.5375 2.38438C17.8458 2.69271 18 3.077 18 3.53725V16.3065C18 16.7668 17.8458 17.151 17.5375 17.4594C17.2292 17.7677 16.8449 17.9219 16.3846 17.9219H3.61538ZM3.61538 16.9219H16.3846C16.5385 16.9219 16.6795 16.8578 16.8077 16.7296C16.9359 16.6014 17 16.4603 17 16.3065V3.53725C17 3.38342 16.9359 3.24239 16.8077 3.11418C16.6795 2.98598 16.5385 2.92188 16.3846 2.92188H3.61538C3.46154 2.92188 3.32052 2.98598 3.1923 3.11418C3.0641 3.24239 3 3.38342 3 3.53725V16.3065C3 16.4603 3.0641 16.6014 3.1923 16.7296C3.32052 16.8578 3.46154 16.9219 3.61538 16.9219Z" fill="#363F51"/>
                                </g>
                            </svg>
                            <span class="format_difference_title" @click="showModalFormatDifference()">
                                Разница формата
                            </span>
   
                        </div>
                        <a-modal width="1500px"
                            title="Данные за прошлые смены"
                            :visible="visibleFormatDifference"
                            @cancel="handleFormatDifference"
                            >
                            <a-tabs>
                                <a-tab-pane key="1" tab="Таблица форматов">
                                    <a-table
                                    :columns="columns_work_shift_last_format_difference"
                                    :data-source="selectWorkShiftLastFormatDifference"
                                    >
                                    </a-table>
                                </a-tab-pane>
                                <a-tab-pane key="2" tab="Замена оснастки">
                                    <a-table
                                    :columns="columns_work_shift_last_tooling_replacement"
                                    :data-source="selectWorkShiftLastToolingReplacement"
                                    >
                                    </a-table>
                                </a-tab-pane>
                            </a-tabs>
                        </a-modal>
                    </div>
                    <a-table
                    :columns="columns_operations_grinder"
                    :data-source="selectOperations"
                    :rowClassName="(record, index) => record.type != 'main' ? 'secondary_operation' :  'main_operation'"
                    >
                    </a-table>
                </div>
            </div> 
            <div class="col-lg-4">
                <div class="warehouse-container" v-if="this.shiftReceptionOperation == null">
                    <span class="warehouse_title">Валы в очереди</span>
                    <a-table
                    :columns="columns_shafts_for_grinder"
                    :data-source="selectShaftForGrinder"
                    >
                    <a @click="consumpShaft(record)" slot="shaft_id" slot-scope="text, record">{{ text }}</a>
                    <a @click="consumpShaft(record)" slot="okvid_number" slot-scope="text, record">{{ text }}</a>
                    <a @click="consumpShaft(record)" slot="description" slot-scope="text, record">{{ text }}</a>
                    </a-table>
                </div>
            </div>
        </div>
</template>

<script>

    export default {
     
        props: {
            secondary_operations: Array,
            operation_grinder: Array,
            urlstartsecondaryoperation: String,
            work_shift_code: Object,
            operator: Object,
            urlgetshaftconsump: String,
            urlstartoperation: String,
            urlgetinfomachine: String,
            urldeleteoperation: String,
            urlcloseoperation: String,
            urlbrakshaft: String,
            urldeleteshaftconsump: String,
            urladdsteps: String,
        }, 

        created() {
            window.Echo.private('grinder-warehouse')
                .listen('GrinderWarehouse', (e) => {
                    this.selectShaftForGrinder = e.shafts;       
            })
        },

        mounted () {
            
        },
        data() {
            return {
                columns_shafts_for_grinder: [
                    {
                    title: "№ Вала",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    scopedSlots: { 
                        customRender: 'shaft_id',
                    },
                    },
                    {
                    title: "№ Оквид",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    scopedSlots: { 
                        customRender: 'okvid_number'
                    },
                    },
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description",
                    scopedSlots: { 
                        customRender: 'description',
                    },
                    },
                ],
                machines: [
                    'CFM-1',
                    'CFM-2',
                ],
                columns_operations_grinder: [
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description",
                    },
                    {
                    title: "№ Вала",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    },
                    {
                    title: "№ Оквид",
                    dataIndex: "okvid_number",
                    key: "okvid_number",
                    },
                    {
                    title: "Формат",
                    dataIndex: "shaft_format",
                    key: "shaft_format",
                    },
                    {
                    title: "Разница D",
                    dataIndex: "grinding_edition_diameter_difference",
                    key: "grinding_edition_diameter_difference",
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

                columns_work_shift_last_format_difference: [
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description",
                    },
                    {
                    title: "№ Вала",
                    dataIndex: "shaft_id",
                    key: "shaft_id",
                    },
                    {
                    title: "№ Заказа",
                    dataIndex: "order_number",
                    key: "order_number",
                    },
                    {
                    title: "Формат",
                    dataIndex: "shaft_format",
                    key: "shaft_format",
                    },
                    {
                    title: "Разница D",
                    dataIndex: "grinding_edition_diameter_difference",
                    key: "grinding_edition_diameter_difference",
                    },
                    {
                    title: "Дата",
                    dataIndex: "posting_date",
                    key: "posting_date",
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
                columns_work_shift_last_tooling_replacement: [
                    {
                    title: "Операция",
                    dataIndex: "description",
                    key: "description",
                    },
                    {
                    title: "Кол-во",
                    dataIndex: "quantity",
                    key: "quantity",
                    },
                    {
                    title: "Дата",
                    dataIndex: "posting_date",
                    key: "posting_date",
                    },
                    {
                    title: "Смена",
                    dataIndex: "work_shift_code",
                    key: "work_shift_code",
                    },
                    {
                    title: "Оператор",
                    dataIndex: "operator",
                    key: "operator",
                    },
                ],
                visibleModalSecondaryOperation: false,
                visibleModalOperation: false,
                visibleInfoShaft: false,
                selectedSecondaryOperation: null,
                selectedMainOperation: null,
                selectOperations: [],
                selectShaftForGrinder: [],
                selectSecondaryOperations: [...this.secondary_operations],
                selectMainOperation: [],
                selectMachine: '',
                selectOperator: {...this.operator},
                selectWorkShiftCode: {...this.work_shift_code},
                visibleModalMachine: true,
                visibleDeleteOperation: false,
                visibleBrakShaft: false,
                visibleModalTimingOperation: false,
                selectConsumpShaft: [],
                noteBrakShaft: '',
                startGrindingEdition: false,
                startBaseGrinding: false,
                shiftReceptionOperation: null,
                shiftHandoverOperation: null,
                shiftRolloverShaft: null,
                visibleFormatDifference: false,
                selectWorkShiftLastFormatDifference: [],
                selectWorkShiftLastToolingReplacement: [],
                selectQtySecondayOperation: 0,
                selectFreeTimeSlots: [],
                isClickedTimingBtnIndex: null,
            }   
                
        },
        computed: {
            /*
            selectShaftForGrinder() {
                if (this.selectConsumpShaft != null) {
                    return this.selectShaftForGrinder.filter(shaft => shaft.shaft_id !== this.selectConsumpShaft['shaft_id']);
                } else {
                    return this.selectShaftForGrinder;
                }
            },
            */

            shaftsMadeWorkShift() {
                const uniqueShafts = new Set();

                this.selectOperations.forEach(shaft => {
                    if (shaft.shaft_id !== null) {
                    uniqueShafts.add(shaft.shaft_id);
                    }
                });

                return uniqueShafts.size;
            },

            baseGrindingMadeWorkShift() {
                return this.selectOperations.filter(item => item.description.includes('Шлифовка базы')).length;
            },

            grindingEditionMadeWorkShift() {
                return this.selectOperations.filter(item => item.description.includes('Шлифовка тираж')).length;
            },
          
        },

        methods: {
            changeBackGroundBtnTiming(slot) {
                this.isClickedTimingBtnIndex = slot;
            },
            showBrakShaft() {
                this.visibleBrakShaft = true;
            },
            handleBrakShaft() {
                this.visibleBrakShaft = false;
            },
            
            showModalTimingOperation() {
                this.visibleModalTimingOperation = true;
            },

            handleModalTimingOperation() {
                this.visibleModalTimingOperation = false;
            },

            showModalSecondaryOperation() {
                this.visibleModalSecondaryOperation = true;
            },

            showModalFormatDifference() {
                this.visibleFormatDifference = true;
            },

            handleFormatDifference() {
                this.visibleFormatDifference = false;
            },

            showDeleteOperation() {
                this.visibleDeleteOperation = true;
            },

            showModalOperation() {
                this.visibleModalOperation = true;
            },

            handleCancelSecondaryOperation() {
                this.visibleModalSecondaryOperation = false;
            },

            handleCancelDeleteOperation() {
                this.visibleDeleteOperation = false;
            },


            handleCancelOperation() {
                this.visibleModalOperation = false;
            },

            deleteOperation() {
                axios.post(this.urldeleteoperation,{
                    operation: this.selectedMainOperation,
                    machine: this.selectMachine,
                    shaft: this.selectConsumpShaft,
                })
                .then(response => {
                    this.selectOperations = response.data[0];
                    this.selectedMainOperation = response.data[1];
                    this.startBaseGrinding = false;     
                    this.startGrindingEdition = false;

                    this.handleCancelDeleteOperation();
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            startWorkShiftCard(){
                if (this.selectMachine != '') {
                    axios.post(this.urlgetinfomachine,{
                        machine: this.selectMachine,
                    })
                    .then(response => {
                        this.selectShaftForGrinder = response.data[0];
                        this.selectOperations = response.data[1];
                        this.selectConsumpShaft = response.data[2];
                        this.selectedMainOperation = response.data[3];
                        this.shiftReceptionOperation = response.data[4];
                        this.shiftHandoverOperation = response.data[5];
                        this.selectedSecondaryOperation = response.data[6];
                        this.shiftRolloverShaft = response.data[7];
                        this.selectWorkShiftLastFormatDifference = response.data[8];
                        this.selectWorkShiftLastToolingReplacement = response.data[9];
                        this.selectFreeTimeSlots = response.data[10];
                        this.selectMainOperation = response.data[11];

                        if (this.selectedMainOperation != null) {
                            if (this.selectedMainOperation.indexOf('Шлифовка базы')  != -1) {
                                this.startBaseGrinding = true;
                            } else if (this.selectedMainOperation.indexOf('Шлифовка тираж') != -1) {
                                this.startGrindingEdition = true;
                            }
                        }
                        this.visibleModalMachine = false;
                    })
                    .catch(error => {
                        alert("Отсутствует соединение");
                    });
                } else {
                    alert('Выберите машинный центр!');
                }
            },

            startSecondaryOperation() {
                if (this.selectConsumpShaft != null) {
                    alert('Вначале завершите работу с валом!');
                } else {
                    axios.post(this.urlstartsecondaryoperation,{
                        description: this.selectedSecondaryOperation,
                        machine: this.selectMachine,
                        shaft: this.selectConsumpShaft,
                        timing_index: this.isClickedTimingBtnIndex,
                    })
                    .then(response => {
                        this.selectOperations = response.data;
                        this.visibleModalSecondaryOperation = false;
                        this.visibleInfoShaft = false;
                        this.isClickedTimingBtnIndex = null;
                        this.visibleModalTimingOperation = false;

                    })
                    .catch(error => {
                        alert("Отсутствует соединение");
                    });
                }

            },

            startOperation() {
                axios.post(this.urlstartoperation,{
                    description: this.selectedMainOperation,
                    machine: this.selectMachine,
                    shaft: this.selectConsumpShaft,
                })
                .then(response => {
                    this.selectOperations = response.data;
                    this.visibleModalOperation = false;
                    if (this.selectedMainOperation.indexOf('Шлифовка базы')  != -1) {
                        this.startBaseGrinding = true;
                    } else if (this.selectedMainOperation.indexOf('Шлифовка тираж') != -1) {
                        this.startGrindingEdition = true;
                    }
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });

            },

            closeOperation(operation) {
                let inputsFilled = false;
                if (this.startBaseGrinding) {
                    inputsFilled = [this.selectConsumpShaft.base_grinding_diameter_difference, this.selectConsumpShaft.base_grinding_diameter_fact, this.selectConsumpShaft.base_grinding_rz_fact].every(input => !!input);
                    this.selectConsumpShaft.base_grinding_post = true;
                } else if (this.startGrindingEdition) {
                    inputsFilled = [this.selectConsumpShaft.grinding_edition_diameter_difference, this.selectConsumpShaft.grinding_edition_diameter_fact, this.selectConsumpShaft.grinding_edition_rz_fact, this.selectConsumpShaft.grinding_edition_hv_fact].every(input => !!input);
                    this.selectConsumpShaft.grinding_edition_post = true;
                } else {
                    inputsFilled = true;
                }

                if (!inputsFilled) {
                    alert('Заполните параметры');
                } else if (this.selectConsumpShaft != null){
                    this.$confirm({
                        title: 'Завершить вал?',
                        content: 'Вы больше не сможете добавить операции для данного вала, завершить работу с валом?',
                        okText: 'Да',
                        cancelText: 'Нет',
                        onOk: () => {
                            axios.post(this.urlcloseoperation,{
                                description: operation,
                                machine: this.selectMachine,
                                shaft: this.selectConsumpShaft,
                            })
                            .then(response => {
                                this.selectOperations = response.data[0];
                                this.selectedMainOperation = response.data[1];     
                                this.selectConsumpShaft  = response.data[2]; 
                                this.shiftReceptionOperation = response.data[3];  
                                this.selectedSecondaryOperation = response.data[4];   
                                this.selectShaftForGrinder = response.data[5];  
                                this.startBaseGrinding = false;
                                this.startGrindingEdition = false;
                            })
                            .catch(error => {
                                alert("Отсутствует соединение");
                            });
                        },
                        onCancel() {
                        
                        },
                    });
                } else {
                    if ((this.selectedSecondaryOperation == 'Замена алмазного резца') || (this.selectedSecondaryOperation == 'Замена торцевого резца') || (this.selectedSecondaryOperation == 'Замена каскадных фильтров') || (this.selectedSecondaryOperation == 'Замена фильтровальных патронов') || (this.selectedSecondaryOperation == 'Замена камня') || (this.selectedSecondaryOperation == 'Замена полировальной ленты')) {
                        if (this.selectQtySecondayOperation != 0) {
                            this.$confirm({
                                title: 'Завершить операцию?',
                                okText: 'Да',
                                cancelText: 'Нет',
                                onOk: () => {
                                    axios.post(this.urlcloseoperation,{
                                        description: operation,
                                        machine: this.selectMachine,
                                        shaft: this.selectConsumpShaft,
                                        qty: this.selectQtySecondayOperation,
                                    })
                                    .then(response => {
                                        this.selectOperations = response.data[0];
                                        this.selectedMainOperation = response.data[1];      
                                        this.shiftReceptionOperation = response.data[3];   
                                        this.selectedSecondaryOperation = response.data[4];  
                                        this.selectConsumpShaft  = response.data[2]; 
                                        this.selectQtySecondayOperation = 0;  
                                        this.startBaseGrinding = false;     
                                        this.startGrindingEdition = false;
                                    })
                                    .catch(error => {
                                        alert("Отсутствует соединение");
                                    });
                                },
                                onCancel() {
                                
                                },
                            });
                        } else {
                            alert('Заполните количество!!!');
                        }
                    } else {
                        this.$confirm({
                            title: 'Завершить операцию?',
                            okText: 'Да',
                            cancelText: 'Нет',
                            onOk: () => {
                                axios.post(this.urlcloseoperation,{
                                    description: operation,
                                    machine: this.selectMachine,
                                    shaft: this.selectConsumpShaft,
                                })
                                .then(response => {
                                    this.selectOperations = response.data[0];
                                    this.selectedMainOperation = response.data[1];     
                                    this.selectConsumpShaft  = response.data[2]; 
                                    this.shiftReceptionOperation = response.data[3];   
                                    this.selectedSecondaryOperation = response.data[4];        
                                })
                                .catch(error => {
                                    alert("Отсутствует соединение");
                                });
                            },
                            onCancel() {
                            
                            },
                        });
                    }
                }
            },

            consumpShaft(shaft) {
                if (this.selectConsumpShaft != null) {
                    alert('Машина'+' '+this.selectMachine+' '+'в данный момент занята валом'+' '+this.selectConsumpShaft.shaft_id);
                } else {
                    axios.post(this.urlgetshaftconsump,{
                        shaft: shaft,
                        machine: this.selectMachine,
                    })
                    .then(response => {
                        this.visibleInfoShaft = true;
                        this.selectConsumpShaft = response.data[0];
                        this.selectMainOperation = response.data[1];
                        this.selectShaftForGrinder = response.data[3];
                    })
                    .catch(error => {
                        alert("Отсутствует соединение");
                    });
                }
            },

            deleteConsumpShaft(shaft) {
                axios.post(this.urldeleteshaftconsump,{
                    shaft: shaft,
                    machine: this.selectMachine,
                })
                .then(response => {
                    this.visibleInfoShaft = false;
                    this.selectConsumpShaft = response.data[0];
                    this.selectMainOperation = response.data[1];
                    this.selectShaftForGrinder = response.data[3];
                    this.startBaseGrinding = false;     
                    this.startGrindingEdition = false;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            onChangeMainOperation(checkedOperation) {
                console.log('checked = ', checkedOperation);
            },

            brakShaft() {
                axios.post(this.urlbrakshaft,{
                    shaft: this.selectConsumpShaft,
                    note: this.noteBrakShaft,
                    machine: this.selectMachine,
                })
                .then(response => {
                    this.visibleInfoShaft = false;
                    this.selectConsumpShaft = response.data[0];
                    this.selectedMainOperation = response.data[1];
                    this.shiftReceptionOperation = response.data[2];
                    this.selectShaftForGrinder = response.data[3];
                    this.startBaseGrinding = false;     
                    this.startGrindingEdition = false;
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },

            addSteps() {
                axios.post(this.urladdsteps,{
                    shaft: this.selectConsumpShaft,
                    machine: this.selectMachine,
                })
                .then(response => {                  
                    this.closeOperation(this.selectedMainOperation);
                })
                .catch(error => {
                    alert("Отсутствует соединение");
                });
            },
        },

        
    } 
    
    
</script>

<style scoped>
.timing_btn {
    display: inline-block; 
    cursor: pointer; 
    margin-top: 10px; 
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    border-radius: 10px; 
    background: var(--primary-0, #F3F9FF); 
    margin-right: 10px;padding: 8px;
    gap: 10px;
}

.timing_btn_click {
    background: var(--primary-300-activ, #4A9DFF); 
}

.operation_grinder_timing {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    border: 0;
    box-shadow: none;

}

.secondary_qty_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: flex;
}

.secondary_qty_value {
    border-radius: 8px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    background: var(--grayscale-0, #FFF);
    height: 40px;
    width: 200px;
    margin-top: 10px;
    display: flex;

}

.format_difference_title {
    cursor: pointer;
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}
.operation_info_title {
    display: flex;
    justify-content: space-between;
}
.info_title {
    font-size: 16px;
}

.info_value {
    font-size: 16px;
}
.base_grinding_make_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.base_grinding_make_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.grinding_edition_make_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.shafts_made_value{
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.grinding_edition_make_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.shafts_made_title{
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-right: 8px;
}

.shafts_made {
    display: flex;
    margin-right: 20px;
}
.base_grinding_make {
    display: flex;
    margin-right: 20px;
}
.grinding_edition_make {
    display: flex;
    margin-right: 20px;
}
.operation_info_shaft {
    display: flex;
    margin-bottom: 15px;
}
.secondary_operation {
    border-bottom: 1px solid var(--grayscale-400, #E8E8E8) !important;
    background: var(--additional-light-green, #EFFFE2) !important;
}
.row_shift_reception_btn {
    display: flex;
    justify-content: flex-end;
}
.shift_reception_title {
    margin-top: 20px;
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.shift_handover_title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: inline-block;
}

.shift_handover_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
    margin-top: 20px;
    display: inline-block;
}

.delete_operation_body {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 24px;
    font-family: Open Sans;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.current_operation_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.18px;
}

.current_operation {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.18px;
}

.delete_operation_btn {
    margin-left: auto;
    cursor: pointer;
}

.current_operation_info {
    display: flex;
    padding: 15px 20px;
    justify-content: flex-end;
    align-items: flex-end;
    gap: 20px;
    flex: 1 0 0;
    border-radius: 20px;
    background: var(--primary-0, #F3F9FF);
    margin-right: 20px;
}

.operation_grinder_btn {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    border: 1px solid var(--primary-300-activ, #4A9DFF);
    color: var(--primary-300-activ, #4A9DFF);
}

.operation_grinder_btn_close {
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    border-radius: 8px;
    background: var(--primary-300-activ, #4A9DFF);
    color: var(--grayscale-0, #FFF);
    margin-left: 20px;
}

.info-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
    margin-bottom: 30px;
}

.operation-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.warehouse-container {
    background: white;
    padding: 20px;
    position: relative;
    box-shadow: 0px 4px 20px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.operation__second_btn {
    border-radius: 8px;
    background: var(--primary-100, #E5EEFF);
    box-shadow: 0px 4px 4px 0px rgba(214, 214, 214, 0.25);
    display: flex;
    padding: 8px 16px;
    justify-content: center;
    align-items: center;
    gap: 8px;
    right: 20px;
    position: absolute;
}

.info-container_header {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #E8E8E8;
    padding: 0 0 20px 0;
}

.warehouse_title {
    margin-bottom: 20px;
    color: var(--txt, #363F51);
    font-size: 20px;
    font-family: Open Sans;
    letter-spacing: -0.2px;
    display: block;
}

.no_operation_title {
    color: var(--grayscale-500, #BFBFBF);
    font-size: 20px;
    font-family: Open Sans;
    letter-spacing: -0.2px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-shaft_wrapper {
    padding: 20px 0 0 0;
}

.shaft_id_title {
    color: var(--txt, #363F51);
    font-size: 20px;
    font-family: Open Sans;
    font-weight: 600;
    margin-right: 5px;
}

.okvid_number_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 5px;
}

.format_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 10px;
}

.okvid_number_value {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 20px;
}

.format_value {
    color: var(--txt, #363F51);
    font-size: 18px;
    font-family: Open Sans;
    letter-spacing: 0.18px;
    margin-right: 20px;
}

.block_title {
    color: var(--grayscale-700, #7B7B7B);
    font-size: 16px;
    display: block;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.block_value {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 18px;
    font-family: Open Sans;
    font-weight: 600;
    letter-spacing: 0.18px;
}

.block_note_value {
    color: var(--txt, #363F51);
    font-family: Open Sans;
    font-size: 14px;
    margin-top: 10px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
}

.input_value {
    display: flex;
    padding: 0px 10px;
    align-items: center;
    gap: 6px;
    flex: 1 0 0;
    align-self: stretch;
    display: flex;
    padding: 0px 10px;
    align-items: center;
    gap: 6px;
    flex: 1 0 0;
    align-self: stretch;
}

.plan_title {
    color: var(--grayscale-600, #9A9A9A);
    font-size: 16px;
    margin-right: 8px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.plan_value {
    color: var(--txt, #363F51);
    font-size: 16px;
    font-family: Open Sans;
    letter-spacing: 0.16px;
}

.shaft_info_row_top {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
}

.shaft_info_row {
    margin-bottom: 20px;
    display: flex;
}

.shaft_info_row_btn {
    margin-top: 20px;
    display: flex;
    align-items: center;
}

.diameter_block {
    display: flex;
    padding: 10px;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
    margin-right: 20px;
}

.edge_block {
    display: flex;
    padding: 10px;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    align-self: stretch;
    margin-right: 20px;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
}

.plan_info_block {
    flex-grow: 1;
    padding: 10px;
    gap: 6px;
    align-self: stretch;
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);

}

.plan_diameter_block {
    display: inline-block;
    margin-right: 30px;
}

.plan_edge_block {
    display: inline-block;
}

.note_block {
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    display: block;
    padding: 10px;

}

.note_engraving_block {
    border-radius: 10px;
    border: 1px solid var(--grayscale-400, #E8E8E8);
    display: block;
    padding: 10px;

}

.ant-radio-wrapper {
    color: var(--txt, #363F51) !important;
    font-size: 16px !important;
    font-family: Open Sans !important;
    letter-spacing: 0.16px !important;
    margin-bottom: 15px !important;
}

.ant-modal-title {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 24px;
    font-family: Open Sans;
    font-weight: 600;
}

.ant-radio-input {
    color: var(--primary-300-activ, #4A9DFF);
    font-size: 20px;
}

.grinding_edition_block {
    flex: 1;
}


.base_grinding_block {
    flex: 1;
}

.shaft_info_row_grinding_edition {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.shaft_info_row_base_grinding {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}
</style>
