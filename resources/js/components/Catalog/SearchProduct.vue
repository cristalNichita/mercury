<template>
  <div class="d-flex justify-content-between flex-nowrap">
    <el-autocomplete
      v-model="search"
      placeholder="Начните вводить для поиска"
      prefix-icon="el-icon-search"
      class="w-100"

      :fetch-suggestions="querySearch"
      @select="addProduct"
    />
  </div>
</template>

<script>
export default {
  name: 'SearchProduct',
  props: {
    parent_product: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      search: '',
    };
  },
  methods: {
    querySearch(queryTitle, callback) {
      const url = this.route('api.catalog.products', {
        _query: {
          filter: {
            title: queryTitle,
          },
        },
      });

      fetch(url)
        .then((res) => res.json())
        .then((res) => {
          callback(res.data.map((el) => ({ value: el.title, product: el })));
        });
    },

    addProduct(item) {
      this.search = '';

      let route = '';
      if (this.parent_product) {
        route = this.route('catalog.recommended-products.store', { parent_product: this.parent_product });
      } else {
        route = this.route('catalog.recommended-products.store');
      }

      this.$inertia.post(route, {
        product: item.product.id,
      });
    },
  },
};
</script>

<style scoped>

</style>
