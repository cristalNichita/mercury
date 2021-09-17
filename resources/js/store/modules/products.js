import axios from 'axios';

export default {
  namespaced: true,
  state: {
    products: [],
  },
  mutations: {
    setProducts(state, products) {
      state.products = products;
    },
  },
  actions: {
    getProducts(context, params) {
      return axios.get('/admin/catalog/products/resource', {
        params,
      }).then((response) => {
        context.commit('setProducts', response.data.data);
      });
    },
  },
  getters: {
    products: (state) => state.products,
  },

};
