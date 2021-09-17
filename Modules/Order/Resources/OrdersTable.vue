<template>
  <el-table
    :data="orders"
    class="w-100"
    :default-sort="defaultSort"
    @sort-change="sortChange"
  >
    <el-table-column
      prop="id"
      label="ID"
      width="64"
      sortable
    />

    <el-table-column
      prop="code"
      label="Код"
      width="130"
    >
      <template #default="scope">
        <inertia-link :href="route('orders.web.show', scope.row.id)">
          {{ scope.row.code || 'Отсутсвует' }}
        </inertia-link>
      </template>
    </el-table-column>

    <el-table-column
      prop="status"
      label="Статус"
      width="130"
      sortable
    >
      <template #default="scope">
        <inertia-link :href="route('orders.web.show', scope.row.id)">
          <order-status :status="scope.row.status" />
        </inertia-link>
      </template>
    </el-table-column>

    <el-table-column
      prop="payment"
      label="Оплата"
      width="130"
      sortable
    >
      <template #default="scope">
        <inertia-link :href="route('orders.web.show', scope.row.id)">
          <order-payment-info :order="scope.row" />
        </inertia-link>
      </template>
    </el-table-column>

    <el-table-column
      prop="delivery"
      label="Доставка"
      sortable
    >
      <template #default="scope">
        <order-delivery-info :order="scope.row" />
      </template>
    </el-table-column>

    <el-table-column
      prop="total"
      label="Стоимость"
      width="130"
      align="right"
      sortable
    >
      <template #default="scope">
        {{ $filters.moneyFormat(scope.row.total ) }} р.
      </template>
    </el-table-column>

    <el-table-column
      prop="contact"
      label="Контактное лицо"
      v-if="!excludeContactColumn"
    >
      <template #default="scope">
        <order-contact :order="scope.row" />
      </template>
    </el-table-column>
    <slot />
  </el-table>
</template>

<script>
import OrderStatus from '@modules/Order/Resources/OrderStatus';
import OrderContact from '@modules/Order/Resources/OrderContact';
import OrderPaymentInfo from '@modules/Order/Resources/OrderPaymentInfo';
import OrderDeliveryInfo from '@modules/Order/Resources/OrderDeliveryInfo';

export default {
  name: 'OrdersTable',
  components: {
    OrderDeliveryInfo, OrderPaymentInfo, OrderContact, OrderStatus,
  },
  props: {
    orders: {
      type: Array,
      required: true,
    },
    excludeContactColumn: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    // eslint-disable-next-line consistent-return,vue/return-in-computed-property
    defaultSort() {
      if (this.$page.props.sort) {
        const [prop, order] = this.$page.props.sort.split('-');
        return { prop, order };
      }
    },
  },
  methods: {
    sortChange(column) {
      let sort;
      if (column.prop) {
        sort = `${column.prop}-${column.order}`;
      }

      this.$inertia.replace(route(route().current(), route().params), {
        method: 'get',
        data: { sort },
        replace: false,
        preserveState: true,
        preserveScroll: false,
        onBefore: () => {
          this.$emit('update:loading', true);
        },
        onFinish: () => {
          this.$emit('update:loading', false);
        },
      });
    },
  },
};
</script>

<style scoped>

</style>
