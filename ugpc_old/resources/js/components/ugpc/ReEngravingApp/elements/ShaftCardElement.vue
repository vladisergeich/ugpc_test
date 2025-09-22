<template>
    <div class="shaft_container">
        <h5>ВАЛ  {{ shaft.shaft_id }}</h5>
        <span style="margin-bottom: 10px;">Причина</span>
        <a-select style="margin-bottom: 20px;" v-model="shaft.reason">
            <a-select-option v-for="reason in this.reasons" :key="reason">
                {{ reason }}
            </a-select-option>
        </a-select>
        <span style="margin-bottom: 10px;">Комментарий</span>
        <a-textarea style="margin-bottom: 20px;" v-model="shaft.comment" placeholder="Напишите комментарий" :rows="4" />
        <span style="margin-bottom: 10px;">Загрузите изображение брака</span>
        <div class="info_app_block_files">
            <a-upload
                name="file"
                :beforeUpload="beforeUpload"
                :file-list="shaft.files || []"
                :remove="deleteFile"
            >

            <a-button> <a-icon type="upload" /> Upload </a-button>
            </a-upload>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    props: {
        shaft: Object,
    },
    data() {
        return {
            reasons: ['Износ вала','Мех. повреждение вала','Дефект гравировки','Изменение макета','Изменение параметров гравировки'],
        };
    },

    created() {

    },

    methods: {
        beforeUpload(file) {
            if (!this.shaft.files) {
                this.$set(this.shaft, 'files', []);
            }

            this.shaft.files.push({
                uid: file.uid,
                name: file.name,
                type: file.type,
                size: file.size,
                raw: file // Сохраняем сырой файл для отправки
            });

            // Останавливаем автоматическую загрузку
            return false;
        },

        deleteFile(file) {
            const index = this.shaft.files.findIndex(f => f.uid === file.uid); 
            if (index !== -1) {
                this.shaft.files.splice(index, 1);
            }
        },
    },

    computed: {

    }
};
</script>

<style scoped>
.shaft_container {
    display: flex;
    flex-direction: column;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #E0E0E0;
    width: 25%;
}

</style>