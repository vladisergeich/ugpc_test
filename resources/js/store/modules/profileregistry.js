import axios from 'axios';

const state = {
    profiles: null,
    selectProfile: null,
    vendor: null,
    typeRegistry: null,
};
 
const getters = {
    getProfiles(state) {
        return state.profiles
    },

    getProfile(state) {
        return state.selectProfile
    },

    getType(state) {
        return state.typeRegistry
    },
};
 
const mutations = {
    SET_VENDOR(state, vendor) {
        state.vendor = vendor;
    },

    SET_PROFILES(state, profiles) {
        state.profiles = profiles;
    },

    SET_PROFILE(state, profile) {
        state.selectProfile = profile;
    },

    SET_TYPE_REGISTRY(state, type) {
        state.typeRegistry = type;
    },
};
 
const actions = {
    getProfiles (context,vendor) {
        axios
            .post(route('ugpc.profileregistry.getprofiles'),{vendor: vendor, type: state.typeRegistry})
            .then(response => {
                context.commit('SET_PROFILES', response.data);
                context.commit('SET_VENDOR', vendor);
            });
    },

    setTypeRegistry (context,type) {
        context.commit('SET_TYPE_REGISTRY', type);
    },

    setProfile (context,profile) {
        context.commit('SET_PROFILE', profile);
    },

    addNewProfile(context,newProfile) {
        axios
            .post(route('ugpc.profileregistry.addnewprofile'),{profile: newProfile, type: state.typeRegistry})
            .then(response => {
                context.commit('SET_PROFILES', response.data);
                this.$success({
                    title: 'Профиль добавлен',
                });           
            })
            .catch(err => {

            });
    },

    addRow(context,type) {
        axios
            .post(route('ugpc.profileregistry.addrow'),{type: type, profile: state.selectProfile})
            .then(response => {
                context.commit('SET_PROFILE', response.data);
            })
            .catch(err => {

            });
    },

    deleteRow(context,type) {
        console.log(type);
        axios
            .post(route('ugpc.profileregistry.deleterow'),{type: type, profile: state.selectProfile})
            .then(response => {
                context.commit('SET_PROFILE', response.data);
            })
            .catch(err => {

            });
    },

    saveProfile(context) {
        axios
            .post(route('ugpc.profileregistry.saveprofile'),{profile: state.selectProfile})
            .then(response => {

            })
            .catch(err => {

            });
    },

    sendDataToNav(context) {
        axios
            .post(route('ugpc.profileregistry.senddatatonav'),{profile: state.selectProfile})
            .then(response => {

            })
            .catch(err => {

            });
    },

    copyProfile(context,profileNumber) {
        axios
            .post(route('ugpc.profileregistry.copyprofile'),{colyProfile: profileNumber, profile: state.selectProfile})
            .then(response => {
                context.commit('SET_PROFILE', response.data);
            })
            .catch(err => {

            });
    },
};
 
export default {
    state,
    getters,
    mutations,
    actions
}