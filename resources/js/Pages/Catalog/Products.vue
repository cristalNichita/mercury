<template>
  <div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          v-model="quickFilter"
          placeholder="Быстрый поиск"
          prefix-icon="el-icon-search"
          @input="quickSearch"
        />
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <products-table
        v-loading="loading"
        :products="products.data"
        @update:loading="loading = $event"
      />
      <ui-pagination
        :max="products.last_page"
        :page="products.current_page"
        @update:loading="loading = $event"
      />
    </div>

    <el-dialog
      v-model="filter_show"
      title="Tips"
      width="90%"
    >
      <FilterProduct
        :filter="filter"
        @cancel-form="filter_show = false"
        @submit="sendFilter"
      />
    </el-dialog>
  </div>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';
import FilterProduct from '@/components/Filters/Catalog/FilterProduct';
import ProductsTable from '@/Pages/Catalog/ProductsTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'Products',
  components: {
    UiPagination, ProductsTable, FilterProduct,
  },
  layout: (h, page) => h(CatalogLayout, [page]),
  props: {
    filter: {
      type: Object,
      default: () => {},
    },
    products: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      filter_show: false,
      // Нихера не работает хер знает почему - в filter лежит какойто Proxy
      quickFilter: this.filter.title ?? '',
      debounceTimeout: 0,
    };
  },
  methods: {
    quickSearch() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => this.doQuickSearch(), 300);
    },
    doQuickSearch() {
      let data = {};
      if (this.quickFilter) {
        data = { filter: { title: this.quickFilter } };
      }

      this.$inertia.replace(route(route().current()), {
        method: 'get',
        data,
        replace: false,
        preserveState: true,
        preserveScroll: false,
        onBefore: () => {
          this.loading = true;
        },
        onFinish: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>
