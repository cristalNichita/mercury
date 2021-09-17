<template>
  <el-table
    :data="products"
    class="w-100"
    :default-sort="defaultSort"
    @sort-change="sortChange"
  >
    <el-table-column
      prop="image"
      label=""
      width="64"
    >
      <template #default="scope">
        <img
          v-if="!!scope.row.image"
          :src="scope.row.image.small"
          :alt="scope.row.title"
          class="img-fluid"
        >
      </template>
    </el-table-column>

    <el-table-column
      prop="title"
      label="Наименование"
    >
      <template #default="scope">
        <div class="text-truncate">
          <inertia-link :href="route('catalog.product', scope.row.id)">
            {{ scope.row.title }}
          </inertia-link>
        </div>
        <div class="text-muted">
          <span v-if="scope.row.id_1c">
            1C: {{ scope.row.id_1c }}
          </span>
        </div>
      </template>
    </el-table-column>
    <el-table-column
      prop="price"
      label="Цена"
      width="110"
      sortable
    >
      <template #default="scope">
        <div class="text-right">
          <del
            v-if="scope.row.old_price"
            class="small"
          >{{ $filters.moneyFormat(scope.row.old_price) }}
          </del>
          <div>{{ $filters.moneyFormat(scope.row.price) }} р.</div>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      prop="quantity"
      label="Остаток"
      width="110"
      sortable
    >
      <template #default="scope">
        <div class="text-right">
          <div>{{ $filters.moneyFormat(scope.row.quantity) }} шт.</div>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      label="Метки"
      width="100"
    >
      <template #default="scope">
        <img
          v-if="scope.row.is_new "
          src="@assets/is_new.svg"
          width="16"
          alt="Новинка"
          class="mr-1"
        >
        <img
          v-if="scope.row.is_sale "
          src="@assets/is_sale.svg"
          width="16"
          alt="Акция"
          class="mr-1"
        >
        <img
          v-if="scope.row.is_offer "
          src="@assets/is_offer.svg"
          width="16"
          alt="Выгодное предложение"
        >
      </template>
    </el-table-column>
    <el-table-column
      prop="rating"
      label="Рейтинг"
      width="110"
      sortable
    />
    <slot />
  </el-table>
</template>

<script>
export default {
  name: 'ProductsTable',
  props: {
    products: {
      type: Array,
      required: true,
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
