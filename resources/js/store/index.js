import Vue from 'vue';
import Vuex from 'vuex';
 
import machine from './modules/machine';
import profileregistry from './modules/profileregistry';
import statistic from './modules/statistic';
 
Vue.use(Vuex);
 
export default new Vuex.Store({
    modules: {
        machine,
        profileregistry,
        statistic
    }
});