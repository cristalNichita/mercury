import axios from 'axios';

export default {
  namespaced: true,
  state: {
    brands: [],
    all_brands: [],
  },
  mutations: {
    setBrands(state, brands) {
      state.brands = brands;
    },
    setAllBrands(state, brands) {
      state.all_brands = brands;
    },
  },
  actions: {
    getBrands(context, params) {
      return axios.get('/admin/catalog/brands/resource', {
        params,
      }).then((response) => {
        context.commit('setBrands', response.data);
      });
    },
    getAllBrands(context) {
      return axios.get('/admin/catalog/brands/resource?without_paginate').then((response) => {
        context.commit('setAllBrands', response.data);
      });
    },
  },
  getters: {
    brands: (state) => state.categories,
    allBrands: (state) => state.all_brands,

  },

};
