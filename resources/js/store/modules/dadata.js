import axios from 'axios';

export default {
  namespaced: true,
  state: () => ({
    url: 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/',
    priority_city: 72,
  }),
  mutations: {

  },
  getters: {

  },
  actions: {
    company({ state }, { query, token }) {
      return axios.post(`${state.url}suggest/party`, {
        query,
        count: 5,
        restrict_value: false,
      }, {
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Token ${token}`,
        },
      }).then((response) => {
        const suggestions = response.data?.suggestions;
        console.log(suggestions);
        return suggestions.map((item) => {
          item.name = item.value;
          item.inn = item.data.inn;
          item.address = item.data.address.value;
          item.liquidated = item.data.state.status === 'LIQUIDATED';
          item.management = item.data.management;
          item.type = item.data.type;
          return item;
        });
      });
    },
  },
};
