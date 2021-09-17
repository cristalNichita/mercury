import axios from 'axios';

export default {
  namespaced: true,
  state: {
    parameters: [],
    all_parameters: [],
  },
  mutations: {
    setParameters(state, parameters) {
      state.parameters = parameters;
    },
    setAllParameters(state, parameters) {
      state.all_parameters = parameters;
    },
  },
  actions: {
    getParameters(context, params) {
      return axios.get('/admin/catalog/parameters/resource', {
        params,
      }).then((response) => {
        context.commit('setParameters', response.data.data);
      });
    },
    getAllParameters(context) {
      return axios.get('/admin/catalog/parameters/resource?without_paginate').then((response) => {
        context.commit('setAllParameters', response.data);
      });
    },
  },
  getters: {
    parameters: (state) => state.parameters,
    allParameters: (state) => state.all_parameters,
  },

};
