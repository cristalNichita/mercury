<template>
  <div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          placeholder="Быстрый поиск"
          prefix-icon="el-icon-search"
          class="mr-3"
        />
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <orders-table
        v-loading="loading"
        :orders="orders.data"
        @update:loading="loading = $event"
      />
      <pre>{{orders.data[0]}}</pre>
      <ui-pagination
        :max="orders.meta.last_page"
        :page="orders.meta.current_page"
        @update:loading="loading = $event"
      />
    </div>
  </div>
</template>

<script>
import OrderLayout from '@/Layouts/OrderLayout';
import UiPagination from '@/components/UI/UiPagination';
import OrdersTable from '@modules/Order/Resources/OrdersTable';

export default {
  name: 'Orders',
  components: { OrdersTable, UiPagination },
  layout: (h, page) => h(OrderLayout, [page]),
  props: {
    orders: {
      type: Object,
      required: true,
    },
    sort: {
      type: String,
      default: 'id-desc',
    },
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
