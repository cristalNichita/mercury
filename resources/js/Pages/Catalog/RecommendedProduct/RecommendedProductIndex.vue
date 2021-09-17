<template>
  <catalog-layout>
    <div class="mb-3">
      <search-product />
    </div>

    <recommended-product-table
      v-loading="loading"
      :products="products.data"
      @update:loading="loading = $event"
    />

    <ui-pagination
      :max="products.last_page"
      :page="products.current_page"
      @update:loading="loading = $event"
    />
  </catalog-layout>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';
import RecommendedProductTable from '@/components/Catalog/RecommendedProductTable';
import UiPagination from '@/components/UI/UiPagination';
import SearchProduct from '@/components/Catalog/SearchProduct';

export default {
  name: 'RecommendedProductIndex',
  components: {
    SearchProduct,
    UiPagination,
    RecommendedProductTable,
    CatalogLayout,
  },
  props: {
    products: Object,
  },
  data() {
    return {
      loading: false,
    };
  },
};
</script>

<style scoped>

</style>
