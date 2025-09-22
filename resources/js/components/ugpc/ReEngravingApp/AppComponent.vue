<template>
    <div>
      <a-card>
        <a-row style="margin-bottom: 15px;">
        <CardTitleElement :change="true" :application="app" ref="CardTitleElement"/>
        </a-row>
        <a-row style="margin-bottom: 15px;">
          <div class="title_block">
            <span class="title_info">
              МЦ:
            </span>
            <span class="value_info">
              {{ application.machine_center }}
            </span>
            <span class="title_info">
              Плановый метраж:
            </span>
            <span class="value_info">
              {{ application.planned_footage }}
            </span>
            <span class="title_info">
              Фактический метраж:
            </span>
            <span class="value_info">
              {{ application.actual_footage }}
            </span>
          </div>
          <a-table :dataSource="app.shafts" :columns="columnsShafts" size="small" bordered :pagination="false" rowKey="number">
            <span slot="comment" slot-scope="text, record" v-if="text">{{ text }}</span>
            <template slot="files" slot-scope="text, record">
              <a-dropdown>
                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M11.3528 4.00938L7.99026 0.646875C7.89651 0.553125 7.76995 0.5 7.63713 0.5H0.999634C0.723071 0.5 0.499634 0.723437 0.499634 1V14C0.499634 14.2766 0.723071 14.5 0.999634 14.5H10.9996C11.2762 14.5 11.4996 14.2766 11.4996 14V4.36406C11.4996 4.23125 11.4465 4.10313 11.3528 4.00938ZM10.3465 4.59375H7.40588V1.65313L10.3465 4.59375ZM10.3746 13.375H1.62463V1.625H6.34338V5C6.34338 5.17405 6.41252 5.34097 6.5356 5.46404C6.65867 5.58711 6.82559 5.65625 6.99963 5.65625H10.3746V13.375ZM5.87463 9.15625H2.99963C2.93088 9.15625 2.87463 9.2125 2.87463 9.28125V10.0312C2.87463 10.1 2.93088 10.1562 2.99963 10.1562H5.87463C5.94338 10.1562 5.99963 10.1 5.99963 10.0312V9.28125C5.99963 9.2125 5.94338 9.15625 5.87463 9.15625ZM2.87463 7.15625V7.90625C2.87463 7.975 2.93088 8.03125 2.99963 8.03125H8.99963C9.06839 8.03125 9.12463 7.975 9.12463 7.90625V7.15625C9.12463 7.0875 9.06839 7.03125 8.99963 7.03125H2.99963C2.93088 7.03125 2.87463 7.0875 2.87463 7.15625Z" fill="#7B7B7B"/>
                </svg>
                <a-menu slot="overlay">
                  <a-menu-item v-for="file in record.files" :key="file.id">
                    <a :href="'https://okvid.danaflex.ru/storage/'+file.path">{{ file.filename }}</a>
                  </a-menu-item>
                </a-menu>
              </a-dropdown>
            </template>
          </a-table>
        </a-row>
        <a-row v-if="app.active_stage.approv_stage.id == 3" style="margin-bottom: 15px; display: flex; flex-direction: column;">
          <span style="margin-bottom: 5px;">Гравировщик:</span>
          <a-select style="width: 25%; margin-bottom: 15px;" v-model="app.engraver" v-if="app.active_stage.approv_stage.id == 3">
              <a-select-option v-for="engraver in this.engravers" :key="engraver">{{ engraver }}</a-select-option>
          </a-select>
        </a-row>
        <a-row v-if="app.active_stage.approv_stage.id != 5" style="margin-bottom: 15px; display: flex; flex-direction: column;">
          <span style="margin-bottom: 5px;">Ваш комментарий:</span>
          <a-textarea style="width: 25%;" placeholder="Напишите комментарий" v-model="app.active_stage.comment":rows="4" />
        </a-row>
        <MoreDetailsElement v-if="app.active_stage.approv_stage.id == 5" :link="false" style="margin-top: 10px;" :stages="application.re_engraving_stage" ref="MoreDetailsElement"/>
        <a-row style="display: flex; justify-content: flex-end; margin-top: 15px;">
          <div style="display: flex; flex-wrap: wrap; gap: 20px;" v-if="app.active_stage.approv_stage.id != 5">
              <a-button @click="showSelectStage = true">Отправить на другой этап</a-button>
              <a-button type="default" @click="approveStage('Не гравировать')">Не согласовать(не гравировать)</a-button>
              <a-button type="default" @click="approveStage('Нет заявки')">Не согласовать(нет заявки)</a-button>
              <a-button type="primary" @click="approveStage('Согласовано')">Согласовать</a-button>
          </div>
          <a-modal
                title="Перенос согласование на другую стадию"
                :visible="showSelectStage"
                @cancel="showSelectStage = false"
                >
                <template slot="footer">
                    <a-button key="cancel_delete_operation" type="default" @click="showSelectStage = false">
                    Отмена
                    </a-button>
                    <a-button key="delete_operation" type="primary" @click="translationStage()">
                    Отправить
                    </a-button>
                </template>
                <a-select style="width: 100%;" v-model="selectStage" v-if="app.active_stage.approv_stage.id != 1">
                  <a-select-option v-for="stage in this.app.last_stages" :key="stage.id">{{ stage.approv_stage.title }}</a-select-option>
                </a-select>
            </a-modal>
          <a-button v-if="app.active_stage.approv_stage.id == 5" type="primary" @click="approveStage('Выполнено')">Заявка выполнена</a-button>
        </a-row>
      </a-card>
    </div>
  </template>
  
  <script>
  import CardTitleElement from "./elements/CardTitleElement.vue";
  import MoreDetailsElement from "./elements/MoreDetailsElement.vue";
  export default {
    props: {
      application: Object,
      message: String,
    },
    components: {
        CardTitleElement,
        MoreDetailsElement
    },
    data() {
      return {
        showSelectStage: false,
        selectStage: null,
        app: this.application,
        engravers: ['Данафлекс-НАНО','Упак-Рото','Яношка-Павловск'],
        columnsShafts: [
          {
            title: '№ ОКВиД',
            dataIndex: 'shaft_order.okvid_number',
            key: 'shaft_order.okvid_number',
            customRender: this.okvidNumber,
          },
          {
            title: '№ вала',
            dataIndex: 'shaft_id',
            key: 'shaft_id',
          },
          {
            title: 'Цвет',
            dataIndex: 'shaft_order.color',
            key: 'shaft_order.color',
          },
          {
            title: 'Цвет DF',
            dataIndex: 'shaft_order.panton',
            key: 'shaft_order.panton',
          },
          {
            title: 'Репро',
            dataIndex: 'shaft_order.order.repro',
            key: 'shaft_order.order.repro',
          },
          {
            title: 'Пред. гравировщик',
            dataIndex: 'last_okvid.order.engraving',
            key: 'last_okvid.order.engraving',
          },
          {
            title: 'Ресурс',
            dataIndex: 'resourse',
            key: 'resourse',
            customRender: this.calcResource,
          },
          {
            title: 'Причина гравировки',
            dataIndex: 'reason',
            key: 'reason',
          },
          {
            title: 'Комментарий',
            dataIndex: 'comment',
            key: 'comment',
            scopedSlots: { customRender: 'comment' }
          },
          {
            title: '',
            dataIndex: 'files',
            key: 'files',
            scopedSlots: { customRender: 'files' }
          },
        ],
      };
    },
    mounted() {
      this.fetchApp();
    },
    methods: {
      async fetchApp() {
        if (this.message) {
          alert(this.message);

          window.location.href = 'https://okvid.danaflex.ru/ugpc/reengravingapps';
        }
      },
      openFile() {
        console.log("File opened");
      },

      calcResource(resourses) {
        let total = resourses.reduce( (acc, resourse) => {
            return acc + resourse.edition_batch;
        }, 0)

        return (Math.ceil(total*100/2000000)+'%');
      },

      approveStage(status) {

        if (this.app.active_stage.approv_stage.id == 3 && !this.app.engraver) {
            alert('Укажите гравировщика');
        } else {
            axios
            .post(route('ugpc.reengravingapps.approvestage'),{app: this.app, status: status})
            .then(response => {
                window.location.href = 'https://okvid.danaflex.ru/ugpc/reengravingapps';
            });
        }
      },

      okvidNumber(okvid_number) {
          return String(okvid_number).slice(0,String(okvid_number).length-2)+'-'+String(okvid_number).slice(-2);
      },

      translationStage() {
        axios
          .post(route('ugpc.reengravingapps.translationstage'),{app: this.app,stage: this.selectStage})
          .then(response => {
              window.location.href = 'https://okvid.danaflex.ru/ugpc/reengravingapps';
          });
      }
    },
  };
  </script>
  
  <style>
.title_block {
  display: flex;
  margin-bottom: 10px;
}

.title_info {
  font-family: Open Sans;
  font-size: 18px;
  font-weight: 400;
  margin-right: 10px;
  color: #6B7280;
}

.value_info {
  font-family: Open Sans;
  font-size: 18px;
  font-weight: 400;
  margin-right: 10px;
  color: #363F51;
}
  </style>
  