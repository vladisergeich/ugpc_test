/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');



window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue';

import "./directives";


import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import Vue from 'vue';
import Antd from 'ant-design-vue';
import router from './route.js'
import store from './store' 
import { route } from '../../vendor/tightenco/ziggy';
import StatisticPage from './pages/statistic/StatisticPage.vue';
import TransferPage from './pages/transfers/TransferPage.vue';
import ProductionManagerPage from './pages/ProductionManager/ProductionManagerPage.vue';
import 'ant-design-vue/dist/antd.css';
//import VueHtmlToPaper from 'vue-html-to-paper';
//Vue.use(VueHtmlToPaper);

window.axios = require('axios');
Vue.use(Antd);
Vue.use(BootstrapVue);

Vue.config.devtools = true;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component(
    "handbook-component",
    require("./components/ugpc/BDGP/Handbook.vue").default
  );
Vue.component(
  "ordercard-component",
  require("./components/ugpc/BDGP/OrderCard.vue").default
);
Vue.component(
  "okvidcard-component",
  require("./components/ugpc/BDGP/OkvidCard.vue").default
);

Vue.component(
  "sendrequestshaft-component",
  require("./components/ugpc/BDGP/SendRequestShaft.vue").default
);

Vue.component(
  "orderparametr-component",
  require("./components/ugpc/BDGP/OrderParametr.vue").default
);

Vue.component(
  "orderstream-component",
  require("./components/ugpc/BDGP/OrderStream.vue").default
);

Vue.component(
  "ordershaft-component",
  require("./components/ugpc/BDGP/OrderShaft.vue").default
);

Vue.component(
  "routemaplist-component",
  require("./components/ugpc/RouteMapCard/routeMapList.vue").default
);

Vue.component(
  "routemapcard-component",
  require("./components/ugpc/RouteMapCard/routeMapCard.vue").default
);

Vue.component(
  "routemapshaft-component",
  require("./components/ugpc/RouteMapCard/routeMapShaft.vue").default
);

Vue.component(
  "register-component",
  require("./components/ugpc/BDGP/Register.vue").default
);

Vue.component(
  "planning-component",
  require("./components/ugpc/Planning/Planning.vue").default
);

Vue.component(
  "executionplan-component",
  require("./components/ugpc/Planning/ExecutionPlan.vue").default
);
Vue.component(
  "inputcontrollist-component",
  require("./components/ugpc/InputControl/InputControlList.vue").default
);

Vue.component(
  "lathegrindingmachinecard-component",
  require("./components/ugpc/LatheGrindingMachine/LatheGrindingMachineCard.vue").default
);

Vue.component(
  "engravingmachine-component",
  require("./components/ugpc/EngravingMachine/EngravingMachine.vue").default
);

Vue.component(
  "testprintmachine-component",
  require("./components/ugpc/TestPrintMachine/TestPrintMachine.vue").default
);

Vue.component(
  "movementorders-component",
  require("./components/ugpc/Planning/MovementOrders.vue").default
);

Vue.component(
  "unloadingorders-component",
  require("./components/ugpc/Planning/UnloadingOrders.vue").default
);

Vue.component(
  "electroplating-component",
  require("./components/ugpc/ElectroPlating/ElectroPlating.vue").default
);

Vue.component(
  "electroplating-component",
  require("./components/ugpc/ElectroPlating/ElectroPlating.vue").default
);

Vue.component(
  "dailyplan-component",
  require("./components/ugpc/DailyPlan/DailyPlan.vue").default
);

Vue.component(
  "applicationlist-component",
  require("./components/ugpc/ApplicationSteelShaft/ApplicationList.vue").default
);

Vue.component(
  "applicationcard-component",
  require("./components/ugpc/ApplicationSteelShaft/ApplicationCard.vue").default
);


Vue.component(
  "machine-component",
  require("./components/ugpc/MachineComponent.vue").default
);

Vue.component(
  "inputcontrol-component",
  require("./components/ugpc/InputControlComponent.vue").default
);

Vue.component(
  "engravingorders-component",
  require("./components/ugpc/EngravingOrdersComponent.vue").default
);

Vue.component(
  "ordercard-component",
  require("./components/ugpc/OrderCardComponent.vue").default
);

Vue.component(
  "ordersplan-component",
  require("./components/ugpc/DailyPlanComponent.vue").default
);


Vue.component(
  "electroplatingcenter-component",
  require("./components/ugpc/ElectroPlatingComponent.vue").default
);

Vue.component(
  "electroplatingbath-component",
  require("./components/ugpc/ElectroPlatingBathComponent.vue").default
);

Vue.component(
  "profileregistry-component",
  require("./components/ugpc/ProfileRegistryComponent.vue").default
);

Vue.component(
  "profilecard-component",
  require("./components/ugpc/ProfileCardComponent.vue").default
);

Vue.component(
  "reengravingapps-component",
  require("./components/ugpc/ReEngravingApp/ReEnravingAppsComponent.vue").default
);

Vue.component(
  "reengravingapp-component",
  require("./components/ugpc/ReEngravingApp/AppComponent.vue").default
);

Vue.component(
  "createappblock-component",
  require("./components/ugpc/ReEngravingApp/blocks/CreateAppBlock.vue").default
);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    router,
    store,
    route,
    components: {
      StatisticPage,
      ProductionManagerPage,
      TransferPage
    }
});
