<template>
    <div class="order_wrapper">
        <a-page-header
            style="border: 1px solid rgb(235, 237, 240)"
            :title="'Вал: '+engravingOrder.shaft.shaft_id"
            :sub-title="'Статус: '+engravingOrder.status"
        >
            <template #extra>
                <a-button key="3" type="primary" data-toggle="modal" data-target=".modal-label" @click="openLabel()">Бирка</a-button>
                <a-button key="2" v-if="editFinalDiameter || editInitialDiameter || editCopperThicknes || editComment" @click="updateEngravingOrder">Сохранить</a-button>
                <a-button key="1" type="primary" @click="confirmOrder()" v-if="engravingOrder.status == 'Ожидает подтверждения'">Подтвердить</a-button>
            </template>
            <div class="modal fade modal-label" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal_wrapper" style="width: 250px; padding: 10px">
                        <div class="modal-content">
                            <div class="modal-content-body" ref="modalLabelContent" id="modalLabelContent">
                                <div class="label_block">
                                    <div class="info_block" v-if="engravingOrder">
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;"><span>Вал: {{engravingOrder.shaft.shaft_id}}</span>    <span style="margin-left: 20px;">№: {{engravingOrder.shaft_number}} из {{ engravingOrder.design_order.cylinder_quantity }}</span></span>
                                        <span style="display: block; margin-bottom: 10px;">№ сепарации: {{engravingOrder.separation_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Формат: {{engravingOrder.format}}</span>
                                        <span style="display: block; margin-bottom: 10px;">№ Партии: {{engravingOrder.design_order.prod_order}}</span>
                                        <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;">Оквид: {{engravingOrder.design_order.okvid_number}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Линиатура: {{engravingOrder.lineature}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Угол: {{engravingOrder.corner}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Резец: {{engravingOrder.cutter}}</span>
                                        <span style="display: block; margin-bottom: 10px;">Цвет: {{engravingOrder.color}}</span>
                                        <div class="barcode_block" style="display: block;" v-if="qr_data" v-html="qr_data"></div>
                                    </div>
                                </div>
                            </div>
                            <a-button style="width: 100%;" @click="printLabel()">Распечатать</a-button>
                        </div>
                    </div>
                </div>
            </div>
            <a-descriptions size="small" :column="3">
                <a-descriptions-item label="Оквид">{{ okvidNumber(engravingOrder.design_order.okvid_number) }}</a-descriptions-item>
                <a-descriptions-item label="Формат">{{ engravingOrder.format }}</a-descriptions-item>
                <a-descriptions-item label="Вид">{{ engravingOrder.type_shaft }}</a-descriptions-item>
                <a-descriptions-item label="Сепарация">{{ engravingOrder.separation_number }}</a-descriptions-item>
                <a-descriptions-item label="D,входной контроль">
                    <span v-show="!editInitialDiameter">{{ engravingOrder.initial_size }}</span>
                    <a-input
                        v-show="editInitialDiameter"
                        v-model="engravingOrder.initial_size"
                        size="small"
                        style="width: 50%"
                        />
                    <svg @click="editInitialDiameter = true" style="cursor: pointer;margin-left: 8px;width: 14px;" width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.15376 15.5H3.39028L12.65 6.24036L11.4134 5.00381L2.15376 14.2635V15.5ZM15.8576 5.15576L12.4826 1.81158L13.7865 0.507758C14.0801 0.214174 14.4371 0.0673828 14.8576 0.0673828C15.2781 0.0673828 15.6352 0.214174 15.9287 0.507758L17.146 1.72503C17.4396 2.01862 17.5915 2.37053 17.6018 2.78078C17.612 3.19103 17.4704 3.54295 17.1768 3.83653L15.8576 5.15576ZM14.773 6.25573L4.02873 17H0.653809V13.625L11.398 2.88078L14.773 6.25573ZM12.0269 5.61728L11.4134 5.00381L12.65 6.24036L12.0269 5.61728Z" fill="#4A9DFF"/>
                    </svg>
                </a-descriptions-item>
                <a-descriptions-item label="D, конечный">
                    <span v-show="!editFinalDiameter">{{ engravingOrder.final_size }}</span>
                    <a-input
                        v-show="editFinalDiameter"
                        v-model="engravingOrder.final_size"
                        size="small"
                        style="width: 50%"
                        />
                    <svg @click="editFinalDiameter = true" style="cursor: pointer;margin-left: 8px;width: 14px;" width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.15376 15.5H3.39028L12.65 6.24036L11.4134 5.00381L2.15376 14.2635V15.5ZM15.8576 5.15576L12.4826 1.81158L13.7865 0.507758C14.0801 0.214174 14.4371 0.0673828 14.8576 0.0673828C15.2781 0.0673828 15.6352 0.214174 15.9287 0.507758L17.146 1.72503C17.4396 2.01862 17.5915 2.37053 17.6018 2.78078C17.612 3.19103 17.4704 3.54295 17.1768 3.83653L15.8576 5.15576ZM14.773 6.25573L4.02873 17H0.653809V13.625L11.398 2.88078L14.773 6.25573ZM12.0269 5.61728L11.4134 5.00381L12.65 6.24036L12.0269 5.61728Z" fill="#4A9DFF"/>
                    </svg>
                </a-descriptions-item>
                <a-descriptions-item label="Толщина меди, входной контроль">
                    <span v-show="!editCopperThicknes">{{ engravingOrder.copper_thickness }}</span>
                    <a-input
                        v-show="editCopperThicknes"
                        v-model="engravingOrder.copper_thickness"
                        size="small"
                        style="width: 50%"
                        />
                    <svg @click="editCopperThicknes = true" style="cursor: pointer;margin-left: 8px;width: 14px;" width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.15376 15.5H3.39028L12.65 6.24036L11.4134 5.00381L2.15376 14.2635V15.5ZM15.8576 5.15576L12.4826 1.81158L13.7865 0.507758C14.0801 0.214174 14.4371 0.0673828 14.8576 0.0673828C15.2781 0.0673828 15.6352 0.214174 15.9287 0.507758L17.146 1.72503C17.4396 2.01862 17.5915 2.37053 17.6018 2.78078C17.612 3.19103 17.4704 3.54295 17.1768 3.83653L15.8576 5.15576ZM14.773 6.25573L4.02873 17H0.653809V13.625L11.398 2.88078L14.773 6.25573ZM12.0269 5.61728L11.4134 5.00381L12.65 6.24036L12.0269 5.61728Z" fill="#4A9DFF"/>
                    </svg>
                </a-descriptions-item>
                <a-descriptions-item label="Комментарий">
                    <span v-show="!editComment">{{ engravingOrder.comment }}</span>
                    <a-input
                        v-show="editComment"
                        v-model="engravingOrder.comment"
                        size="small"
                        style="width: 50%"
                        />
                    <svg @click="editComment = true" style="cursor: pointer;margin-left: 8px;width: 14px;" width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.15376 15.5H3.39028L12.65 6.24036L11.4134 5.00381L2.15376 14.2635V15.5ZM15.8576 5.15576L12.4826 1.81158L13.7865 0.507758C14.0801 0.214174 14.4371 0.0673828 14.8576 0.0673828C15.2781 0.0673828 15.6352 0.214174 15.9287 0.507758L17.146 1.72503C17.4396 2.01862 17.5915 2.37053 17.6018 2.78078C17.612 3.19103 17.4704 3.54295 17.1768 3.83653L15.8576 5.15576ZM14.773 6.25573L4.02873 17H0.653809V13.625L11.398 2.88078L14.773 6.25573ZM12.0269 5.61728L11.4134 5.00381L12.65 6.24036L12.0269 5.61728Z" fill="#4A9DFF"/>
                    </svg>
                </a-descriptions-item>
            </a-descriptions>
            <a-row>
                <a-spin :spinning="spinning"></a-spin>
                <div v-if="!spinning" v-for="orderStage in engravingOrder.engraving_order_stages" :key="orderStage.id">
                    <a-collapse style="margin-top: 20px;">
                        <a-collapse-panel :class="{'defect_stage': orderStage.status == 'брак'}" key="1" :header="'Этап - ' + orderStage.stage_number+'    '+orderStage.status" >
                            <OrderStageBlock :orderStage="orderStage" :alteration_stages="alteration_stages" :alteration="orderStage.status == 'брак' && engravingOrder.status == 'брак'"></OrderStageBlock>
                        </a-collapse-panel>
                    </a-collapse>
                </div>
            </a-row>
        </a-page-header>
    </div>
</template>
    
    
    
<script>
import { mapGetters } from 'vuex'
import QRCode from 'qrcode-generator';
import OrderStageBlock from "./blocks/OrderStageBlock.vue";
export default {  
    components: {
        OrderStageBlock,
    },
    props: {
        engraving_order: Object,
        urlupdateorder: String,
        alteration_stages: Array,
    },
    data() {
        return {
            qr_data: null,
            spinning: false,
            editFinalDiameter: false,
            editInitialDiameter: false,
            editCopperThicknes: false,
            editComment: false,
        };
    },

    created() {
        this.setEngravingOrder(this.engraving_order);
    },

    computed: {
        ...mapGetters({
            engravingOrder: 'getEngravingOrder'
        }),
    },

    methods: {
        okvidNumber(okvid_number) {
            return String(okvid_number).slice(0,String(okvid_number).length-2)+'-'+String(okvid_number).slice(-2);
        },

        async setEngravingOrder(engraving_order) {
            await this.$store.dispatch('setEngravingOrder',engraving_order);
        },

        confirmOrder() {
            this.engravingOrder.status = 'Подтвержден';
            this.engravingOrder.confirmed = true;
            this.updateEngravingOrder();
        },
        updateEngravingOrder() {
            this.spinning = true;
            axios.post(this.urlupdateorder, this.engravingOrder 
                )
                .then(response => {
                    this.setEngravingOrder(response.data);
                    this.editFinalDiameter = false;
                    this.editInitialDiameter = false;
                    this.editCopperThicknes = false;
                    this.editComment = false;
                    this.spinning = false;
                })
                .catch(err => {
                    alert('Ошибка!!! проверьте соединение с интернетом');
                });
        },

        openLabel() {
            this.qr_data = 'shaft|'+String(this.engravingOrder.id);
            this.generateQRCode();
        },

        generateQRCode() {
            const size = 200;

            const qr = QRCode(0, 'M');
            qr.addData(this.qr_data);
            qr.make();
            qr.createSvgTag({
                scale: size / qr.getModuleCount()
            });
            const svgCode = qr.createSvgTag();
            this.qr_data = svgCode;
        },

        printLabel() {
            printJS({ printable: 'modalLabelContent', type: 'html' });
        },
    },
    
};
</script>

<style>
.order_wrapper {
    background-color: #fff;
    width: 80%;
}

.defect_stage {
    color: red !important;
}
</style>
    