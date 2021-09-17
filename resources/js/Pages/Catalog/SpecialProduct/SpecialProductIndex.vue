<template>
  <catalog-layout :special_field="special_field">
    <div class="mb-3">
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
    </div>

    <div class="bg-white shadow-sm">
      <special-product-table
        v-loading="loading"
        :products="products.data"
        :special_field="special_field"
        @update:loading="loading = $event"
      />

      <ui-pagination
        :max="products.last_page"
        :page="products.current_page"
        @update:loading="loading = $event"
      />
    </div>
  </catalog-layout>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';
import SpecialProductTable from '@/components/Catalog/SpecialProductTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'SpecialProductIndex',
  components: {
    UiPagination, SpecialProductTable, CatalogLayout,
  },
  props: {
    products: Object,
    special_field: String,
  },
  data() {
    return {
      loading: false,
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

      console.log(url);

      fetch(url)
        .then((res) => res.json())
        .then((res) => {
          console.log(res);
          callback(res.data.map((el) => ({ value: el.title, product: el })));
        });
    },

    addProduct(item) {
      this.search = '';

      const route = this.route('catalog.special-products.update', {
        special_field: this.special_field,
        product: item.product.id,
      });

      this.$inertia.patch(route);
    },
  },
};
</script>

<style scoped>

</style>
