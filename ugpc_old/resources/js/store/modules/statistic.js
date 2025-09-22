import axios from 'axios';

const state = {
    filters: {
        dates: [],
        letters: [],
        user: null,
    },
};
 
const mutations = {
    SET_FILTERS(state, filters) {
        state.filters = { ...state.filters, ...filters };
    },
};

const actions = {
    updateFilters({ commit }, filters) {
        commit('SET_FILTERS', filters);
    },
};

const getters = {
    getFilters: (state) => state.filters,
};
 
export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}