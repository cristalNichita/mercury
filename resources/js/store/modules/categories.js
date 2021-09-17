import axios from 'axios';

export default {
  namespaced: true,
  state: {
    categories: [],
    all_categories: [],
  },
  mutations: {
    setCategories(state, categories) {
      state.categories = categories;
    },
    setAllCategories(state, categories) {
      state.all_categories = categories;
    },
  },
  actions: {
    getCategories(context, params) {
      return axios.get('/admin/catalog/categories/resource', {
        params,
      }).then((response) => {
        context.commit('setCategories', response.data);
      });
    },
    getAllCategories(context) {
      return axios.get('/admin/catalog/categories/resource?without_paginate').then((response) => {
        context.commit('setAllCategories', response.data);
      });
    },
  },
  getters: {
    categories: (state) => state.categories,
    allCategories: (state) => state.all_categories,

  },

};
