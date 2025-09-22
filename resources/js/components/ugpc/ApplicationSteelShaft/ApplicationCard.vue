<template>
    <div class="app_card_wrapper">
        <div class="row">
            <div class="col-lg-5">
                <div class="info_app_wrapper">
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Дата заявки</span>
                            <a-date-picker style="width: 100%;"
                                type="date"
                                value-type="format"
                                format="DD.MM.YYYY" 
                             @change="submitApp" v-model="App.document_date" />
                        </div>
                        <div class="info_app_block">
                            <span class="info_app_block-title" >№ Заявки</span>
                            <a-input readOnly v-model="App.document_number" />
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Заявку оформил</span>
                            <a-input @change="submitApp" v-model="App.manager" />
                        </div>
                        <div class="info_app_block">
                            <span class="info_app_block-title">№ партии</span>
                            <a-input @change="submitApp" v-model="App.order_number" />
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Изготовитель</span>
                            <a-select @change="submitApp" v-model="App.vendor" style="width: 100%">
                                <a-select-option v-for="vendor in this.vendors" :key="vendor">
                                    {{ vendor }}
                                </a-select-option>
                            </a-select>
                        </div>
                        <div class="info_app_block">
                            <span class="info_app_block-title">Гравируем</span>
                            <a-select @change="submitApp" v-model="App.engraver" style="width: 100%">
                                <a-select-option v-for="engraver in this.vendors" :key="engraver">
                                    {{ engraver }}
                                </a-select-option>
                            </a-select>
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Формат</span>
                            <a-select @change="submitApp" v-model="App.format" style="width: 100%">
                                <a-select-option v-for="item in this.formats" :key="item.format">
                                    {{ item.format }}
                                </a-select-option>
                            </a-select>
                        </div>
                        <div class="info_app_block">
                            <span class="info_app_block-title">Кол-во валов</span>
                            <a-input @change="submitApp" v-model="App.shaft_quantity" />
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Длина гильзы</span>
                            <a-select @change="submitApp" v-model="App.sleeve_length" style="width: 100%">
                                <a-select-option v-for="sleeve_length in sleeveLenght" :key="sleeve_length">
                                    {{ sleeve_length }}
                                </a-select-option>
                            </a-select>
                        </div>
                        <div class="info_app_block">
                            <span class="info_app_block-title">Длина цилиндра</span>
                            <a-select @change="submitApp" v-model="App.shaft_length" style="width: 100%">
                                <a-select-option v-for="shaft_length in sleeveLenght" :key="shaft_length">
                                    {{ shaft_length }}
                                </a-select-option>
                            </a-select>
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Клиент</span>
                            <a-select 
                                show-search 
                                @change="submitApp" 
                                allowClear 
                                v-model="App.customer_id" 
                                style="width: 100%"
                                :option-filter-prop="'children'"
                                :option-label-prop="'children'"
                                >
                                <a-select-option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                    {{ customer.description }}
                                </a-select-option>
                            </a-select>
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Комментарий</span>
                            <a-textarea
                            @change="submitApp"
                            v-model="App.comment"
                            :auto-size="{ minRows: 2, maxRows: 5 }"
                            />
                        </div>
                    </div>
                    <div class="info_app_row">
                        <div class="info_app_block">
                            <span class="info_app_block-title">Файлы</span>
                            <div class="info_app_block_files">
                                <a-upload
                                    name="file"
                                    :beforeUpload="beforeUpload"
                                    :file-list="Files"
                                    :remove="deleteFile"
                                >

                                    <span>
                                    <upload-outlined></upload-outlined>
                                    Прикрепите или перетащите файл
                                    </span>
                                </a-upload>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <template v-if="ShaftsList.length == 0">
                    <div class="isnot_shafts_list_wrapper">
                        <span class="isnot_shafts_title">Чтобы сформировать и отправть заявку на изготовление стальной заготовки, добавьте вал</span>
                        <a-button class="add_shaft_btn" @click="addShaftShowModal" type="primary">+ Добавить вал</a-button>
                    </div>
                </template>
                <template v-if="ShaftsList.length != 0">
                    <div class="shafts_list_wrapper">
                        <div class="shafts_list_buttons">
                            <a-button class="add_shaft_btn" @click="addShaftShowModal" type="primary">+ Добавить вал</a-button>
                            <a-button class="add_shaft_btn" @click="deleteAllShaftsConfirm">Удалить все</a-button>
                        </div>
                        <a-table class="shafts_list_table" :columns="shafts_columns" :data-source="ShaftsList" >
                            <template  slot="shaft_id" slot-scope="text, record">                
                                <a-input readOnly v-model="record.shaft_id" />
                            </template>  
                            <template  slot="ff" slot-scope="text, record">                
                                <a-input readOnly v-model="record.ff" />
                            </template>  
                            <template  slot="diameter" slot-scope="text, record">                
                                <a-input readOnly v-model="record.diameter" />
                            </template>  
                            <template  slot="shaft_ra" slot-scope="text, record">                
                                <a-input readOnly v-model="record.shaft_ra" />
                            </template>  
                            <template  slot="shaft_rz" slot-scope="text, record">                
                                <a-input readOnly v-model="record.shaft_rz" />
                            </template>  
                            <template  slot="delete_btn" slot-scope="text, record">      
                                <span @click="deleteShaft(record)" class="delete_btn" style="cursor: pointer;" >         
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                        <mask id="mask0_419_748" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="25" height="25">
                                        <rect x="0.5" y="0.5" width="24" height="24" fill="#D9D9D9"/>
                                        </mask>
                                        <g mask="url(#mask0_419_748)">
                                        <path d="M7.8077 20.9998C7.30898 20.9998 6.88302 20.8232 6.52982 20.47C6.17661 20.1168 6 19.6908 6 19.1921V6.49981H5V4.99983H9.49997V4.11523H15.5V4.99983H20V6.49981H19V19.1921C19 19.6972 18.825 20.1248 18.475 20.4748C18.125 20.8248 17.6974 20.9998 17.1922 20.9998H7.8077ZM17.5 6.49981H7.49997V19.1921C7.49997 19.2818 7.52883 19.3556 7.58652 19.4133C7.64422 19.471 7.71795 19.4998 7.8077 19.4998H17.1922C17.2692 19.4998 17.3397 19.4678 17.4038 19.4037C17.4679 19.3395 17.5 19.269 17.5 19.1921V6.49981ZM9.90385 17.4998H11.4038V8.49981H9.90385V17.4998ZM13.5961 17.4998H15.0961V8.49981H13.5961V17.4998Z" fill="#4A9DFF"/>
                                        </g>
                                    </svg>
                                </span> 
                            </template>  
                        </a-table>
                    </div>
                </template>
                <a-modal v-model="addShaftModal">
                    <template slot="footer">
                        <div class="footer_modal_wrapper">
                            <a-button @click="addShafts" style="width: 100%" key="submit" type="primary">
                            + Добавить вал
                            </a-button>
                        </div>
                    </template>
                    <span class="info_app_block-title">ID вала</span>
                    <a-input style="margin-top: 5px" v-model="addShaftId" />
                    <span style="margin-top: 20px" class="info_app_block-title">FF</span>
                    <a-select v-model="addShaftFF" style="width: 100%; margin-top: 5px">
                        <a-select-option v-for="ff in ff_array" :key="ff">
                            {{ ff }}
                        </a-select-option>
                    </a-select>
                    <span style="margin-top: 20px" class="info_app_block-title">Кол-во валов</span>
                    <a-input style="margin-top: 5px" v-model="addShaftQty" />
                </a-modal>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row_post_btn">
                    <a-button class="add_shaft_btn" @click="postApp" v-if="!this.App.post" type="primary">Сформировать и отправить заявку</a-button>
                    <a-button class="add_shaft_btn" @click="postApp" v-if="this.App.post" type="primary">Обновить и отправить заявку</a-button>
                </div>
            </div>
        </div>
    </div>
</template>



<script>
export default {
    props: {
        application: Object,
        customers: Array,
        formats: Array,
        vendors: Array,
        urlgetshaftslist: String,
        urldeleteallshafts: String,
        urladdshafts: String,
        urldeleteshaft: String,
        urlsubmitapp: String,
        urluploadfiles: String,
        urldeletefiles: String,
        urlpostapp: String,
    },
    data() {
        return {
            App: {...this.application},
            addShaftId: '',
            addShaftFF: '',
            addShaftQty: '',
            addShaftModal: false,
            shafts_columns: [
                { title: '№', dataIndex: 'shaft_number', key: 'shaft_number', width: '10%', },
                { title: 'ID вала', dataIndex: 'shaft_id', key: 'shaft_id', scopedSlots: { customRender: 'shaft_id'}},
                { title: 'FF', dataIndex: 'ff', key: 'ff', scopedSlots: { customRender: 'ff'} },
                { title: 'Диаметр', dataIndex: 'diameter', key: 'diameter', scopedSlots: { customRender: 'diameter'} },
                { title: 'RA', dataIndex: 'shaft_ra', key: 'shaft_ra', scopedSlots: { customRender: 'shaft_ra'} },
                { title: 'RZ', dataIndex: 'shaft_rz', key: 'shaft_rz', scopedSlots: { customRender: 'shaft_rz'} },
                { title: '',key: 'delete_btn', scopedSlots: { customRender: 'delete_btn'} },
            ],
            sleeveLenght: ['','1370','1360','1380','1400','2108'],
            ShaftsList: [],
            ff_array: ['HS','RM','SC'],
            Files: [
               
            ],
        };
    },
    filters: {
        okvidNumber(value) {

            if ((value.length >= 2) && (!value.includes('T'))) {
                const lastTwoDigits = value.slice(-2);
                return value.slice(0, -2)+'-'+lastTwoDigits;
            }
        
            return value;
        },

        createDate(value) {

            const dateParts = value.split('-');
            if (dateParts.length === 3) {
                const year = dateParts[0];
                const month = dateParts[1];
                const day = dateParts[2];

                return day + '-' + month + '-' + year;
            }
        
            return value;
        }
    },
    mounted() {
        this.getShaftsList();
    },
    computed: {

    },
    methods: {
        deleteFile(file) {
            console.log(file);
        
            axios.post(this.urldeletefiles, file, { params: {filename: file.filename, document_number: this.App.document_number } })
                .then(response => {
                    const Files = response.data.map(obj => {
                        return { uid: obj.id, name: obj.filename, url: 'https://okvid.danaflex.ru/storage/'+obj.path, ...obj};
                    });


                    this.Files = Files;
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                });
        },
        beforeUpload(file) {
            const formData = new FormData();
            formData.append('file', file);

            axios.post(this.urluploadfiles, formData, { params: { document_number: this.App.document_number } })
                .then(response => {
                    const Files = response.data.map(obj => {
                        return { uid: obj.id, name: obj.filename, url: 'https://okvid.danaflex.ru/storage/'+obj.path, ...obj};
                    });


                    this.Files = Files;
                })
                .catch(error => {
                    console.error('Error uploading file:', error);
                });

            return false; 
        },
        getShaftsList() {
            axios.post(this.urlgetshaftslist,this.application)
            .then(response => {
                this.ShaftsList = response.data[0];      
                
                const Files = response.data[1].map(obj => {
                    return { uid: obj.id, name: obj.filename, url: 'https://okvid.danaflex.ru/storage/'+obj.path, ...obj};
                });


                this.Files = Files;
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
        },
        submitApp() {
            axios.post(this.urlsubmitapp,this.App)
            .then(response => {
                  
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
        },
        addShaftShowModal() {
            this.addShaftModal = true;
        },
        deleteAllShaftsConfirm() {
            const self = this;
            this.$confirm({
                content: 'Вы уверены что хотите удалить все валы?',
                onOk() {
                    self.deleteAllShafts();
                },
                onCancel() {},
            });
        },

        deleteAllShafts() {
            axios.post(this.urldeleteallshafts,this.application)
            .then(response => {
                this.ShaftsList = response.data;       
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
        },

        addShafts() {
            if (this.App.format != null || this.App.format != 0) {
                axios.post(this.urladdshafts,{
                    app: this.App,
                    start_shaft_id: this.addShaftId,
                    shaft_ff: this.addShaftFF,
                    shafts_qty: this.addShaftQty,
                    })
                .then(response => {
                    this.ShaftsList = response.data;  
                    this.addShaftModal = false;     
                })
                .catch(err => {
                    alert('Ошибка! Обратитесь в поддержку');
                });
            } else {
                alert('Заполните формат валов');
            }
        },

        deleteShaft(record) {
            const self = this;
            this.$confirm({
                content: 'Вы уверены, что хотите удалить вал?',
                onOk() {
                    
                    axios.post(self.urldeleteshaft,record)
                        .then(response => {
                            self.ShaftsList = response.data;     
                        })
                        .catch(err => {
                            alert('Ошибка! Обратитесь в поддержку');
                        });
                },
                onCancel() {},
                });
        },

        postApp() {
            axios.post(this.urlpostapp, this.App)
            .then(response => {
                this.App = response.data;
            })
            .catch(err => {
                alert('Ошибка! Обратитесь в поддержку');
            });
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




    },
};
</script>

<style scoped>
.delete_btn {
    display: flex;
    padding: 4px 4px;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    background: var(--grayscale-0, #FFF);
}
.footer_modal_wrapper {
    display: flex;
    justify-content: center;
}
.row_post_btn {
    width: 100%;
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
}
.shafts_list_buttons {
    width: 100%;
    flex-wrap: wrap;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
.shafts_list_table {
    width: 100%;
}
.shafts_list_wrapper {
    display: flex;
    padding: 20px;
    flex-direction: column;
    align-items: center;
    gap: 28px;
    align-self: stretch;
    border-radius: 10px;
    background: var(--primary-0, #F3F9FF);
}
.info_app_wrapper {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.isnot_shafts_list_wrapper {
    display: flex;
    padding: 20px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 16px;
    flex: 1 0 0;
    align-self: stretch;
    border-radius: 10px;
    border: 1px dashed var(--grayscale-400, #E8E8E8);
    width: 100%;
    height: 100%;
}
.info_app_block-title {
    color: var(--grayscale-700, #7B7B7B);
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.14px;
    margin-bottom: 5px;
    display: flex;
}
.info_app_row {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.info_app_block {
    flex: 1;
}

.info_app_block_files {
    display: flex;
    padding: 24px 20px;
    border-radius: 8px;
    background: var(--grayscale-200, #F7F7F8);
    
}

.app_card_wrapper {
    padding: 20px;
    position: relative;
    border-radius: 10px;
    background: var(--grayscale-0, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
}
</style>
