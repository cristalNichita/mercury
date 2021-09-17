import { createStore } from 'vuex';
import categories from '@/store/modules/categories';
import products from '@/store/modules/products';
import parameters from '@/store/modules/parameters';
import brands from '@/store/modules/brands';
import dadata from '@/store/modules/dadata';

// Create a new store instance.
const store = createStore({
  state: {
  },
  mutations: {
  },
  actions: {

  },
  getters: {

  },
  modules: {
    categories,
    products,
    parameters,
    brands,
    dadata,
  },
});

export default store;
