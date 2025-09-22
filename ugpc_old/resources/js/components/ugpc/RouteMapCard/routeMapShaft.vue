<template>
    <div class="col-lg-12">
        <div class="row" style="align-items: center; display: flex; justify-content: flex-end;">
            <span v-if="this.selectRouteMapShaft.input_control_defect" class="input_defect_title">Брак на входном контроле</span>
            <a-button style="margin-right: 15px;" class="edit_btn" @click="confirmRouteShaft()" v-if="!this.selectRouteMapShaft.confirm && !this.selectRouteMapShaft.post">Подтвердить</a-button>
            <span style="margin-right: 15px; color: green;" v-if="this.selectRouteMapShaft.confirm">Подтвержден</span>
            <a-button class="edit_btn" @click="operationEdit = true" v-if="!operationEdit" >Редактировать</a-button>
            <a-button class="edit_btn" @click="operationEdit = false" v-if="operationEdit">Сохранить</a-button>
        </div>
        
        <div class="row">
            <table style="width: 100%; margin-bottom: 20px;">
                <thead>
                    <tr class="titles">
                        <th class="title_left">Операция</th>
                        <th class="title_center" v-if="!selectRouteMapShaft.post">План</th>
                        <th class="title_center_25" v-if="selectRouteMapShaft.post">План</th>
                        <th class="title_center_25" v-if="selectRouteMapShaft.post">Факт</th>
                        <th class="title_right" v-if="!selectRouteMapShaft.post"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr :class="{ defect_row: selectRouteMapShaft.dechromization_defect, hidden_row: !selectRouteMapShaft.dechromization}">
                        <td class="cell">Дехромирование</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.dechromization" @click="selectRouteMapShaft.dechromization = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.dechromization" @click="selectRouteMapShaft.dechromization = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.base_grinding_defect, hidden_row: !selectRouteMapShaft.base_grinding}">
                        <td class="cell"><label for="">Шлифовка базы</label></td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">D:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.base_grinding" class="cell_value">{{selectRouteMapShaft.base_grinding_diameter_plan}}</span>                               
                                <a-input
                                class="cell_value"
                                v-show="operationEdit && selectRouteMapShaft.base_grinding"
                                v-model="selectRouteMapShaft.base_grinding_diameter_plan"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Кромка:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.base_grinding" class="cell_value">{{selectRouteMapShaft.base_grinding_kromka_plan}}</span>
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.base_grinding_kromka_plan"
                                v-show="operationEdit && selectRouteMapShaft.base_grinding"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.base_grinding" class="cell_value">{{selectRouteMapShaft.base_grinding_rz_plan}}</span>   
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.base_grinding_rz_plan"
                                v-show="operationEdit && selectRouteMapShaft.base_grinding"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">D:</span>
                                <span class="cell_value">{{selectRouteMapShaft.base_grinding_diameter_fact}}</span>                               
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Кромка:</span>
                                <span class="cell_value">{{selectRouteMapShaft.base_grinding_kromka_fact}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span class="cell_value">{{selectRouteMapShaft.base_grinding_rz_fact}}</span>   
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">                         
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.base_grinding" @click="selectRouteMapShaft.base_grinding = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="4" v-if="selectRouteMapShaft.base_grinding" @click="selectRouteMapShaft.base_grinding = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.steel_degreasing_defect, hidden_row: !selectRouteMapShaft.steel_degreasing}">
                        <td class="cell">Обезжир-е стали</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.steel_degreasing" @click="selectRouteMapShaft.steel_degreasing = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.steel_degreasing" @click="selectRouteMapShaft.steel_degreasing = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.pre_copper_plating_defect, hidden_row: !selectRouteMapShaft.pre_copper_plating}">
                        <td class="cell">Предв. меднение</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.pre_copper_plating" class="cell_value">{{selectRouteMapShaft.pre_copper_plating_plan}} мкм</span>
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.pre_copper_plating_plan"
                                v-show="operationEdit && selectRouteMapShaft.pre_copper_plating"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span class="cell_value">{{selectRouteMapShaft.pre_copper_plating_plan}} мкм</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.pre_copper_plating" @click="selectRouteMapShaft.pre_copper_plating = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.pre_copper_plating" @click="selectRouteMapShaft.pre_copper_plating = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.base_copper_plating_defect, hidden_row: !selectRouteMapShaft.base_copper_plating}">
                        <td class="cell">
                            <span>Меднение базы</span>
                            <a-select style="display: block; width: 30%;" v-if="operationEdit && selectRouteMapShaft.base_copper_plating" v-model="selectRouteMapShaft.base_copper_plating_add_operation" @change="submit(selectRouteMapShaft)">
                                <a-select-option v-for="operation in copper_plating_add_operations" :key="operation">
                                    {{ operation }}
                                </a-select-option>
                            </a-select>
                            <span style="display: block;" class="cell_title" v-show="!operationEdit && selectRouteMapShaft.base_copper_plating" >{{selectRouteMapShaft.base_copper_plating_add_operation}}</span>
                        </td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.base_copper_plating" class="cell_value">{{selectRouteMapShaft.base_copper_plating_t_plan}} мкм</span>
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.base_copper_plating_t_plan"
                                v-show="operationEdit && selectRouteMapShaft.base_copper_plating"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.base_copper_plating" class="cell_value">{{selectRouteMapShaft.base_copper_plating_bath_plan}}</span>   
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.base_copper_plating_bath_plan"
                                v-show="operationEdit && selectRouteMapShaft.base_copper_plating"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span class="cell_value">{{selectRouteMapShaft.base_copper_plating_t_fact}} мкм</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span class="cell_value">{{selectRouteMapShaft.base_copper_plating_bath_fact}}</span>   
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.base_copper_plating" @click="selectRouteMapShaft.base_copper_plating = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.base_copper_plating" @click="selectRouteMapShaft.base_copper_plating = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.base_degreasing_defect, hidden_row: !selectRouteMapShaft.base_degreasing}">
                        <td class="cell">Обезжир-е базы</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.base_degreasing" @click="selectRouteMapShaft.base_degreasing = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.base_degreasing" @click="selectRouteMapShaft.base_degreasing = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.copper_plating_edition_defect, hidden_row: !selectRouteMapShaft.copper_plating_edition}">
                        <td class="cell">
                            <span>Меднение тираж</span>

                            <a-select style="display: block; width: 30%;" v-if="operationEdit && selectRouteMapShaft.copper_plating_edition" v-model="selectRouteMapShaft.copper_plating_add_operation" @change="submit(selectRouteMapShaft)">
                                <a-select-option v-for="operation in copper_plating_add_operations" :key="operation">
                                    {{ operation }}
                                </a-select-option>
                            </a-select>

                            <span style="display: block;" class="cell_title" v-show="!operationEdit && selectRouteMapShaft.copper_plating_edition" >{{selectRouteMapShaft.copper_plating_add_operation}}</span>
                        </td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.copper_plating_edition" class="cell_value">{{selectRouteMapShaft.copper_plating_edition_t_plan}} мкм</span>
                                <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.copper_plating_edition_t_plan"
                                v-show="operationEdit && selectRouteMapShaft.copper_plating_edition"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span class="cell_value">{{selectRouteMapShaft.copper_plating_edition_t_fact}} мкм</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.copper_plating_edition" @click="selectRouteMapShaft.copper_plating_edition = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.copper_plating_edition" @click="selectRouteMapShaft.copper_plating_edition = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.grinding_edition_defect, hidden_row: !selectRouteMapShaft.grinding_edition}">
                        <td class="cell" >Шлифовка тираж</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">D:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.grinding_edition" class="cell_value">{{selectRouteMapShaft.grinding_edition_diameter_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.grinding_edition_diameter_plan"
                                v-show="operationEdit && selectRouteMapShaft.grinding_edition"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">HV:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.grinding_edition" class="cell_value">{{selectRouteMapShaft.grinding_edition_hv_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.grinding_edition_hv_plan"
                                v-show="operationEdit && selectRouteMapShaft.grinding_edition"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div> 
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.grinding_edition" class="cell_value">{{selectRouteMapShaft.grinding_edition_rz_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.grinding_edition_rz_plan"
                                v-show="operationEdit && selectRouteMapShaft.grinding_edition"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">D:</span>
                                <span class="cell_value">{{selectRouteMapShaft.grinding_edition_diameter_fact}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">HV:</span>
                                <span class="cell_value">{{selectRouteMapShaft.grinding_edition_hv_fact}}</span>
                            </div> 
                            <div class="row row_value">
                                <span class="cell_title">Rz:</span>
                                <span class="cell_value">{{selectRouteMapShaft.grinding_edition_rz_fact}}</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.grinding_edition" @click="selectRouteMapShaft.grinding_edition = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.grinding_edition" @click="selectRouteMapShaft.grinding_edition = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.engraving_defect, hidden_row: !selectRouteMapShaft.engraving}">
                        <td class="cell">Гравирование</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Линиатура:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_liniatura_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.engraving_liniatura_plan"
                                v-show="operationEdit && selectRouteMapShaft.engraving"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Угол растра:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_number_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.engraving_number_plan"
                                v-show="operationEdit && selectRouteMapShaft.engraving"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Угол резца:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_degree_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.engraving_degree_plan"
                                v-show="operationEdit && selectRouteMapShaft.engraving"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">№ сепарации:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_separation_plan}}</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.engraving_separation_plan"
                                v-show="operationEdit && selectRouteMapShaft.engraving"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Job Ticket:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_job_ticket}}</span>
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Линиатура:</span>
                                <span class="cell_value">{{selectRouteMapShaft.engraving_liniatura_plan}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Угол растра:</span>
                                <span class="cell_value">{{selectRouteMapShaft.engraving_number_plan}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Угол резца:</span>
                                <span class="cell_value">{{selectRouteMapShaft.engraving_degree_plan}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">№ сепарации:</span>
                                <span class="cell_value">{{selectRouteMapShaft.engraving_separation_plan}}</span>
                            </div>
                            <div class="row row_value">
                                <span class="cell_title">Job Ticket:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.engraving" class="cell_value">{{selectRouteMapShaft.engraving_job_ticket}}</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.engraving" @click="selectRouteMapShaft.engraving = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.engraving" @click="selectRouteMapShaft.engraving = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.degreasing_edition_defect, hidden_row: !selectRouteMapShaft.degreasing_edition}">
                        <td class="cell">Обезжир-е тираж</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">авто</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.degreasing_edition" @click="selectRouteMapShaft.degreasing_edition = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.degreasing_edition" @click="selectRouteMapShaft.degreasing_edition = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.chrome_plating_defect, hidden_row: !selectRouteMapShaft.chrome_plating}">
                        <td class="cell">Хромирование</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.chrome_plating" class="cell_value">{{selectRouteMapShaft.chrome_plating_plan}} мкм</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.chrome_plating_plan"
                                v-show="operationEdit && selectRouteMapShaft.chrome_plating"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Т:</span>
                                <span class="cell_value">{{selectRouteMapShaft.chrome_plating_plan}} мкм</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.chrome_plating" @click="selectRouteMapShaft.chrome_plating = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="2" v-if="selectRouteMapShaft.chrome_plating" @click="selectRouteMapShaft.chrome_plating = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.polishing_chrome_defect, hidden_row: !selectRouteMapShaft.polishing_chrome}">
                        <td class="cell">Полировка хром</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Rz, мкм:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.polishing_chrome" class="cell_value">{{selectRouteMapShaft.polishing_chrome_plan}} мкм</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.polishing_chrome_plan"
                                v-show="operationEdit && selectRouteMapShaft.polishing_chrome"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Rz, мкм:</span>
                                <span class="cell_value">{{selectRouteMapShaft.polishing_chrome_plan}} мкм</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.polishing_chrome" @click="selectRouteMapShaft.polishing_chrome = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.polishing_chrome" @click="selectRouteMapShaft.polishing_chrome = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr :class="{ defect_row: selectRouteMapShaft.test_print_defect, hidden_row: !selectRouteMapShaft.test_print}">
                        <td class="cell">Пробная печать</td>
                        <td class="cell">
                            <div class="row row_value">
                                <span class="cell_title">Цвет:</span>
                                <span v-show="!operationEdit && selectRouteMapShaft.test_print" class="cell_value">{{selectRouteMapShaft.test_print_color_plan}} мкм</span>
                                 <a-input
                                class="cell_value"
                                v-model="selectRouteMapShaft.test_print_color_plan"
                                v-show="operationEdit && selectRouteMapShaft.test_print"
                                @change="submit(selectRouteMapShaft)"
                                />
                            </div>
                        </td>
                        <td class="cell" v-if="selectRouteMapShaft.post">
                            <div class="row row_value">
                                <span class="cell_title">Цвет:</span>
                                <span class="cell_value">{{selectRouteMapShaft.test_print_color_plan}} мкм</span>
                            </div>
                        </td>
                        <td class="cell" v-if="!selectRouteMapShaft.post || operationEdit">
                            <a-dropdown>
                                <template #overlay>
                                    <a-menu >
                                        <a-menu-item key="1" v-if="!selectRouteMapShaft.test_print" @click="selectRouteMapShaft.test_print = true,submit(selectRouteMapShaft)">                                           
                                            Показать
                                        </a-menu-item>
                                        <a-menu-item key="1" v-if="selectRouteMapShaft.test_print" @click="selectRouteMapShaft.test_print = false,submit(selectRouteMapShaft)">                                           
                                            Скрыть
                                        </a-menu-item>
                                    </a-menu>
                                </template>
                                <a-icon class="icon_more" type="more" />
                            </a-dropdown>
                        </td>
                    </tr>
                    <tr>
                        <a-form-model-item style="width: 100%;margin-top: 20px;" label="Примечание">
                            <a-input 
                            v-model="selectRouteMapShaft.note"
                            @change="submit(selectRouteMapShaft)"
                            type="textarea" />
                        </a-form-model-item>
                    </tr>
                    <tr>
                        <a-form-model-item style="width: 100%;" label="Для гравировки">
                            <a-input 
                            v-model="selectRouteMapShaft.engraving_note"
                            @change="submit(selectRouteMapShaft)"
                            type="textarea" />
                        </a-form-model-item>
                    </tr>
                    <tr v-show="selectRouteMapShaft.defect">
                        <a-form-model-item style="width: 100%;" label="Комментарий к браку">
                            <a-input 
                            v-model="selectRouteMapShaft.defect_note"
                            @change="submit(selectRouteMapShaft)"
                            type="textarea" />
                        </a-form-model-item>
                    </tr>
                    <tr>
                        <a-button type="dashed" style="margin-right: 10px;" v-show="!selectRouteMapShaft.defect"  @click="addTestMap(selectRouteMapShaft.route_map_number)">Разделить МК</a-button>
                        <a-button type="dashed" style="margin-right: 10px;" v-if="!selectRouteMapShaft.defect && selectRouteMapShaft.route_map_number > 1" @click="deleteMap(selectRouteMapShaft.route_map_number)">Удалить МК</a-button>
                        <a-button v-show="selectRouteMapShaft.defect" @click="skip()" style="margin-right: 10px;">
                            Пропустить дальше
                        </a-button>
                        <a-button v-show="selectRouteMapShaft.defect" @click="replaceShaft()" type="dashed" style="margin-right: 10px;">
                            Заменить вал
                        </a-button>
                        <a-button v-show="selectRouteMapShaft.defect" @click="newRouteMap(selectRouteMapShaft)" type="primary">
                            Переделка
                        </a-button>
                        <label v-if="selectRouteMapShaft.order_number == 'Тест'" class="btn_add_test" @click="addTestMap(selectRouteMapShaft.route_map_number)">Добавить МК</label>
                        <a-button v-show="(!selectRouteMapShaft.defect && !selectRouteMapShaft.post) || (operationEdit)" @click="success()" type="primary">
                            В работу
                        </a-button>
                    </tr>
                </tbody>
            </table>   
        </div> 
    </div>
</template>

<script>
import printJS from 'print-js';

    export default {
     
        props: {
            urlsubmit: String,
            urlpostroutemap: String,
            route_operation: Object,
            urladdtestmap: String,
            urldeletemap: String,
            urladdnewroutemap: String,
            urlreplaceshaft: String,
            urlskipbrakshaft: String,
        }, 

        mounted () {
        },
        filters: {
            okvidNumber(value) {

                if ((value.length >= 2) && (!value.includes('T'))) {
                const lastTwoDigits = value.slice(-2);
                return value.slice(0, -2)+'-'+lastTwoDigits;
                }
            
                return value;
            }
        },
        data() {
            return {
                qr_data: null,
                columns_route_map_shaft: [
                    {
                    title: "Операция",
                    dataIndex: "operation",
                    key: "operation",
                    },
                    {
                    title: "План",
                    dataIndex: "plan",
                    key: "plan",
                    scopedSlots: { customRender: 'plan' },
                    },
                ],

                selectRouteMapShaft: {...this.route_operation},
                operationEdit: false,
                copper_plating_add_operations: ['мягкая медь','твердая медь'],
            }
                
        },
        computed: {

          
        },

        methods: {
            submit(record){ 
                axios.post(this.urlsubmit, record 
                )
                .then(response => {
                    this.selectRouteMapShaft = response.data;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            success() {
                axios.post(this.urlpostroutemap, this.selectRouteMapShaft, 
                )
                .then(response => {
                    this.operationEdit = false;
                    this.$success({
                        title: 'Валл'+' '+this.selectRouteMapShaft.shaft_id+' '+'в работе',
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000); 
                })
                .catch(err => {
                    
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            skip() {
                axios.post(this.urlskipbrakshaft, this.selectRouteMapShaft, 
                )
                .then(response => {
                    this.$success({
                        title: 'Валл'+' '+this.selectRouteMapShaft.shaft_id+' '+'снова в работе',
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000); 
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            newRouteMap(routemap) {
                axios.post(this.urladdnewroutemap,routemap)
                .then(response => {
                    this.selectRouteMapShaft = response.data;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            addTestMap(map) {
                axios.post(this.urladdtestmap,{          
                    shaft: this.selectRouteMapShaft.shaft_id, 
                    okvid_number: this.selectRouteMapShaft.okvid_number,
                    route_map_number: map,
                })
                .then(response => {
                    this.selectRouteMapShaft = response.data;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            deleteMap(map) {
                axios.post(this.urldeletemap,{          
                    shaft: this.selectRouteMapShaft.shaft_id, 
                    okvid_number: this.selectRouteMapShaft.okvid_number,
                    route_map_number: map,
                })
                .then(response => {
                    this.selectRouteMapShaft = response.data;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            replaceShaft(map) {
                axios.post(this.urlreplaceshaft,{          
                    shaft: this.selectRouteMapShaft.shaft_id, 
                    okvid_number: this.selectRouteMapShaft.okvid_number,
                    route_map_number: this.selectRouteMapShaft.route_map_number,

                })
                .then(response => {
                    this.$success({
                        title: 'Валл'+' '+this.selectRouteMapShaft.shaft_id+' '+'отправлен на замену',
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000); 
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
            },

            confirmRouteShaft() {
                this.selectRouteMapShaft.confirm = true;
                console.log(this.selectRouteMapShaft);
                this.submit(this.selectRouteMapShaft);
            }

        },
        
    } 
    
    
</script>



<style>
.ant-tabs-content {
    box-shadow: none;
}
.titles {
    background: #E5EEFF;
    border-radius: 10px 10px 0px 0px;
    color: #363F51;
}

.title_left {
    padding: 10px 15px; 
    border-radius: 10px 0px 0px 0px; 
    text-align: left; 
    font-weight: 400;
    width: 50%;
}

.title_center {
    padding: 10px 15px; 
    text-align: left; 
    font-weight: 400;
    width: 50%;
    padding: 0 !important;
}

.title_center_25 {
    padding: 10px 15px; 
    text-align: left; 
    font-weight: 400;
    width: 25%;
    padding: 0 !important;
}

.title_right {
    padding: 10px 15px; 
    border-radius: 0px 10px 0px 0px; 
    text-align: center; 
    font-weight: 400;
}

.cell {
    padding: 12px 15px;
    color: #363F51;
    font-weight: 400;
    border-bottom: 1px solid #E8E8E8;
}

.cell_title {
    font-weight: 400;
    color: #9A9A9A;
}

.cell_value {
    font-weight: 400;
    color: #363F51;
    margin-left: 8px;
    width: 30%;
}

.shaft_title {
    font-weight: 400;
    font-size: 24px;
    line-height: 33px;
    color: #4A9DFF;
}

.info_title {
    font-style: normal;
    font-weight: 400;
    font-size: 18px;
    line-height: 25px;
    color: #9A9A9A;
    margin-right: 5px;;
}

.info_value {
    font-style: normal;
    font-weight: 400;
    font-size: 18px;
    line-height: 25px;
    color: #363F51;
    margin-right: 20px;
}

.icon_more {
    font-size: 30px;
    cursor: pointer;
}

.row_value {
    margin-bottom: 10px;
    align-items: center;
}

.btn_ro_job {
    background: #4A9DFF;
    color: #FFFFFF;
    padding: 12px 24px;
    border-radius: 8px;
    border: 0;
}

.row_btn_to_job {
    justify-content: end;
}

.row_btn_defect {
    justify-content: end;
}

.defect_row {
    background: #FFEBEB;
}

.hidden_row {
    background: #F7F7F8;
}

.edit_btn {
    color: #9A9A9A;
    background: white;
    cursor: pointer;
    margin: 15px 0;
}

.input_defect_title {
    margin-right: 50px;
    color: red;
    font-size: 20px;
    background: white;
    cursor: pointer;
}


.btn_add_test {
    color: #9A9A9A;
    font-weight: 400;
    font-size: 16px;
    line-height: 22px;
    left: 0px;
    position: absolute;
    cursor: pointer;
}
</style>
